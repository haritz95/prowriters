<?php

namespace App\Http\Controllers\Customer;

use App\Enums\InvoiceStatusType;
use App\Http\Controllers\Controller;
use App\Models\Accounting\Invoice;
use App\Models\Accounting\InvoiceStatus;
use App\Services\DownloadService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('Customer/Invoices/Index', [
            'data'     => function () {
                // ALWAYS included on first visit
                // OPTIONALLY included on partial reloads
                // ONLY evaluated when needed
                return [
                    'title'     => __('Invoices'),
                    'dropdowns' => [
                        'invoice_statuses' => InvoiceStatus::select('id', 'name')->get()->toArray(),
                    ],
                ];
            },
            'filters'  => $request->only('filters'),
            'invoices' => Invoice::where('customer_id', auth()->user()->id)->with(['status'])
                ->when($request->filled('search.status'), function ($q) use ($request) {
                    return $q->where(function ($subQuery) use ($request) {
                        return $subQuery->where('invoice_status_id', $request->input('search.status'));
                    });
                })
                ->when($request->filled('search.due_date') && is_array($request->input('search.due_date')), function ($q) use ($request) {
                    return $q->where(function ($subQuery) use ($request) {
                        $date = format_date_range_from_client_side($request->input('search.due_date'));
                        return $subQuery->whereDateBetween('due_date', $date['from'], $date['to']);

                    });
                })
                ->orderBy('id', 'DESC')
                ->paginate(config('app.pagination.per_page'))
                ->withQueryString(),
        ]);
    }

    /**
     * Show the the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['items', 'customer', 'status', 'items.invoiceable' => function ($q) {
            $q->select(['id', 'number', 'uuid']);
        }]);

        return inertia('Customer/Invoices/Show', [
            'invoice' => $invoice->makeHidden(['admin_note']),
            'data'    => [
                'title'             => __('Invoice') . ' ' . $invoice->number,
                'is_user_logged_in' => auth()->check(),
                'allow'             => [
                    'pay_now'                  => !in_array($invoice->invoice_status_id, [InvoiceStatusType::PAID, InvoiceStatusType::FORWARDED]),

                ],
                'company'                  => [
                    'name'    => get_company_name(),
                    'address' => get_company_address(),
                ],
                'invoiceable_types'        => Invoice::invoiceableTypes(),
                'link_to_invoiceable_type' => [
                    'task' => 'customer.tasks.show',
                ],
            ],
        ]);
    }

    public function download(Invoice $invoice, DownloadService $downloadService)
    {
        return $downloadService->invoice($invoice);
    }

}
