<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Payments\Payment;
use App\Models\Payments\PendingForApprovalPayment;
use App\Services\DownloadService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    public function index(Request $request)
    {
        return inertia('Customer/Payments/Index', [
            'data'     => [
                'title' => __('Payments'),
            ],
            'payments' => Payment::whereHas('from', function ($q) {
                return $q->where('customer_id', auth()->user()->id);
            })->orderBy('id', 'DESC')
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
    public function show(Payment $payment)
    {
        return inertia('Customer/Payments/Show', [
            'payment' => $payment,
            'data'    => [
                'title'   => __('Payment') . ' ' . $payment->number,
                'company' => [
                    'name'    => get_company_name(),
                    'address' => get_company_address(),
                ],
            ],
        ]);
    }

    public function download(Payment $payment, DownloadService $downloadService)
    {
        return $downloadService->paymentReceipt($payment);
    }

    public function pendingApprovals()
    {
        return inertia('Customer/Payments/PendingApprovals', [
            'data'     => [
                'title' => __('Payments Pending Approval'),
            ],
            'payments' => PendingForApprovalPayment::whereHas('from', function ($q) {
                return $q->where('customer_id', auth()->user()->id);
            })->with(['method'])->orderBy('id', 'DESC')
                ->paginate(config('app.pagination.per_page'))
                ->withQueryString(),
        ]);
    }
    
}
