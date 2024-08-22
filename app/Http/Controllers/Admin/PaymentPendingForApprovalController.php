<?php

namespace App\Http\Controllers\Admin;

use App\Events\PaymentApprovedEvent;
use App\Events\PaymentDisapprovedEvent;
use App\Http\Controllers\Controller;
use App\Models\Payments\Payment;
use App\Models\Payments\PendingForApprovalPayment;
use App\Services\PaymentRecordService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentPendingForApprovalController extends Controller
{

    public function index(Request $request)
    {
        return inertia('Admin/Payments/PendingApprovals/Index', [
            'data'     => [
                'title' => __('Payments pending for approval'),
                'urls'  => [
                    'search' => route('admin.payments.pendingApprovals.index'),
                ],
            ],
            'filters'  => $request->only('filters'),
            'payments' => PendingForApprovalPayment::with(['from' => function ($query) {
                $query->select('id', 'uuid', 'first_name', 'last_name');
            }, 'method'])
                ->when($request->filled('filters.search'), function ($q) use ($request) {
                    return $q->where('reference', $request->filters['search'])
                        ->orWhere('number', 'like', '%' . $request->filters['search'] . '%')
                        ->orWhere('amount', 'like', '%' . $request->filters['search'] . '%');
                })
                ->orderBy('id', 'DESC')
                ->paginate(config('app.pagination.per_page'))
                ->withQueryString(),
        ]);
    }

    /**
     * Show the payment for a given resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show(PendingForApprovalPayment $pendingApproval)
    {

        $pendingApproval->load(['from', 'attachments', 'method']);

        return inertia('Admin/Payments/PendingApprovals/Show', [
            'payment' => $pendingApproval,
            'data'    => [
                'title' => __('Payment pending for approval') . ' ' . $pendingApproval->number,
            ],
        ]);
    }

    public function approve(PendingForApprovalPayment $pendingApproval, PaymentRecordService $paymentRecordService)
    {
        DB::beginTransaction();
        $success = false;
        try {

            // Store the payment
            $files       = null;
            $attachments = $pendingApproval->attachments()->get();
            if ($attachments->count() > 0) {
                $files = $attachments->toArray();
            }

            $payment = $paymentRecordService->store($pendingApproval->customer_id, $pendingApproval->method->name, $pendingApproval->amount, $pendingApproval->reference, $files);

            // Now delete the record
            $pendingApproval->attachments()->delete();
            $pendingApproval->delete();

            // Trigger event
            event(new PaymentApprovedEvent($payment));

            $success = true;
            DB::commit();
        } catch (\Exception$e) {
            $success = false;
            DB::rollback();
        }

        if ($success) {
            // the transaction worked ...
            return redirect()->route('admin.payments.pendingApprovals.index')->withSuccess(__('Payment Approved'));
        } else {

            return redirect()->route('admin.payments.pendingApprovals.index')->withFail(__('Sorry the request was not successful, please try again'));
        }
    }

    public function disapprove(PendingForApprovalPayment $pendingApproval)
    {
        // Now delete the pending payment record
        $data = $pendingApproval->toArray();
        $pendingApproval->attachments()->delete();
        $pendingApproval->delete();
        event(new PaymentDisapprovedEvent($data));

        return redirect()->route('admin.payments.pendingApprovals.index')->withSuccess(__('Payment Disapproved'));
    }

}
