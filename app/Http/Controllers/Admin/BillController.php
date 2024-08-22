<?php

namespace App\Http\Controllers\Admin;

use App\Events\BillPaidEvent;
use App\Http\Controllers\Controller;
use App\Models\Billing\Bill;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BillController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('Admin/Bills/Index', [
            'data'    => [
                'title'     => __('Bills'),
                'urls'      => [
                    'search' => route('admin.bills.index'),
                ],
                'dropdowns' => [
                    'statuses' => [
                        ['id' => '', 'name' => __('All')],
                        ['id' => 'paid', 'name' => __('Paid')],
                        ['id' => 'unpaid', 'name' => __('Unpaid')],
                    ],
                ],
            ],
            'filters' => $request->only('filters'),
            'bills'   => Bill::with(['from' => function ($q) {
                $q->select('id', 'uuid', 'first_name', 'last_name');
            }])->orderBy('id', 'DESC')
                ->when($request->has('search.status'), function ($query) use ($request) {
                    if ($request->input('search.status') == 'paid') {
                        return $query->whereNotNull('paid');
                    } elseif ($request->input('search.status') == 'unpaid') {
                        return $query->whereNull('paid');
                    }
                })
                ->when($request->filled('search.number'), function ($query) use ($request) {
                    return $query->where('number', $request->input('search.number'));
                })
                ->paginate(config('app.pagination.per_page'))
                ->withQueryString(),
        ]);
    }

    public function show(Bill $bill)
    {
        $bill->load(['items', 'items.task' => function ($q) {
            $q->select(['id', 'uuid', 'title', 'number', 'service_id']);
        }, 'items.task.service' => function ($q) {
            $q->select(['id', 'name']);
        }]);

        return inertia('Admin/Bills/Show', [
            'data' => [
                'title'              => $bill->number,
                'previous_link_text' => __('back to ') . ' ' . __('Bills'),
                'urls'               => [
                    'previous_page'              => route('admin.bills.index'),
                    'submit_form_mark_as_paid'   => route('admin.bills.mark.as_paid', $bill->uuid),
                    'submit_form_mark_as_unpaid' => route('admin.bills.mark.as_unpaid', $bill->uuid),
                ],
                'company'            => Setting::get(['company_name', 'company_address']),
            ],
            'bill' => $bill,

        ]);
    }

    public function markAsPaid(Request $request, Bill $bill)
    {
        $request->validate([
            'payment_reference' => 'required|max:192',
        ]);

        $bill->payment_reference = $request->input('payment_reference');
        $bill->paid              = Carbon::now()->format("Y-m-d");
        $bill->save();

        // Dispatching Event
        event(new BillPaidEvent($bill));
  
        return redirect()->back()->withSuccess(__('Marked as paid'));
    }

    public function markAsUnpaid(Request $request, Bill $bill)
    {
        $bill->payment_reference = null;
        $bill->paid              = null;
        $bill->save();
        return redirect()->back()->withSuccess(__('Marked as unpaid'));
    }
}
