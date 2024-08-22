<?php

namespace App\Http\Controllers\Admin;

use App\Enums\InvoiceStatusType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvoiceRequest;
use App\Models\Accounting\Invoice;
use App\Models\Accounting\InvoiceStatus;
use App\Models\ProjectManagement\Task;
use App\Models\ProjectManagement\TaskStatus;
use App\Models\User;
use App\Rules\MoneyFormat;
use App\Services\CartService;
use App\Services\InvoiceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('Admin/Invoices/Index', [
            'data'    => function () {
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
            'filters' => $request->only('filters'),

            'invoices' => Invoice::with(['customer', 'status'])
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return inertia('Admin/Invoices/Create', [
            'data'            => [
                'title'            => __('Add invoice'),
                'sales_tax_rate'   => settings('sales_tax_rate'),
                'enable_sales_tax' => settings('enable_sales_tax'),
                'dropdowns'        => [
                    'customers' => ($request->customer_id) ? [User::find($request->customer_id)] : [],
                ],
            ],
            'existing_record' => [
                'customer_id' => ($request->customer_id) ? $request->customer_id : null,
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreInvoiceRequest $request, InvoiceService $invoiceService, CartService $cartService)
    {
        DB::beginTransaction();

        try {

            $sub_total = 0;

            foreach ($request->invoice_items as $item) {
                $invoice_item = $this->prepareInvoiceItem($item);
                $sub_total += $invoice_item['sub_total'];
                $invoice_items[] = $invoice_item;
            }

            $customer = User::find($request->customer_id);

            // Calculate Sales Tax if enabled
            $tax_information = $cartService->calculateSalesTax($sub_total);

            // Calculate Total
            $total = ($sub_total - $request->discount) + $tax_information['sales_tax_amount'];

            $invoice = $invoiceService->create([
                'invoice_status_id'    => InvoiceStatusType::UNPAID,
                'customer_id'          => $customer->id,
                'sub_total'            => $sub_total,
                'discount'             => ($request->discount) ? $request->discount : 0,
                'coupon_id'            => null,
                'coupon_discount'      => 0,
                'sales_tax_rate'       => $tax_information['sales_tax_rate'],
                'sales_tax_amount'     => $tax_information['sales_tax_amount'],
                'total'                => $total,
                'amount_paid'          => 0,
                'billing_address'      => $request->billing_address,
                'admin_note'           => $request->admin_note,
                'customer_note'        => $request->customer_note,
                'terms_and_conditions' => $request->terms_and_conditions,
                'invoice_date'         => Carbon::parse($request->invoice_date)->setTimezone('UTC')->format('Y-m-d'),
                'due_date'             => Carbon::parse($request->due_date)->setTimezone('UTC')->format('Y-m-d'),
                'user_id'              => auth()->user()->id,
            ], $invoice_items, $customer);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $invoice = null;
        }

        if ($invoice) {
            return redirect()->route('admin.invoices.index')->withSuccess(__('Successfully created'));
        } else {
            return redirect()->back()->withFail(__('Could not perform the requested action'));
        }

    }

    public function prepareInvoiceItem($item)
    {
        $sub_total = ($item['quantity'] * $item['price']);

        $invoiceable_type  = null;
        $invoiceable_id    = null;
        $invoiceable_model = null;

        if (isset($item['linked_task_id']) && $item['linked_task_id']) {
            $invoiceable_type  = strtolower(class_basename(Task::class));
            $invoiceable_id    = $item['linked_task_id'];
            $invoiceable_model = Task::find($item['linked_task_id']);
        }

        return [
            'invoiceable_type'  => $invoiceable_type,
            'invoiceable_id'    => $invoiceable_id,
            'invoiceable_model' => $invoiceable_model,
            'name'              => $item['name'],
            'description'       => $item['description'],
            'price'             => $item['price'],
            'quantity'          => $item['quantity'],
            'sub_total'         => $sub_total,
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['items', 'customer', 'status', 'items.invoiceable' => function ($q) {
            $q->select(['id', 'number', 'uuid']);
        }]);

        return inertia('Admin/Invoices/Show', [
            'invoice' => $invoice,
            'data'    => [
                'title'                    => __('Customer') . ' ' . __('Invoice') . ' ' . $invoice->number,
                'company'                  => [
                    'name'    => get_company_name(),
                    'address' => get_company_address(),
                ],
                'invoiceable_types'        => Invoice::invoiceableTypes(),
                'show_admin_note'          => true,
                'link_to_invoiceable_type' => [
                    'task' => 'admin.tasks.show',
                ],
            ],
        ]);
    }

    public function notInvoicedTasks(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
        ]);

        return response()->json(Task::select(['id', 'number', 'total', 'service_id', 'title'])
                ->with('service')
                ->where('customer_id', $request->customer_id)
                ->whereNull('invoice_id')
                ->where('task_status_id', '<>', TaskStatus::CANCELED)
                ->get()->toArray());
    }

    public function createAdjustPaymentFromWallet(Invoice $invoice)
    {
        if ($response = $this->isInvoiceBalanceNotAdjustable($invoice)) {
            return $response;
        }

        return inertia()->modal('Admin/Invoices/AdjustFromWallet', [
            'data'    => [
                'title'       => __('Adjust payment from wallet'),
                'balance_due' => ($invoice->total - $invoice->amount_paid),
            ],
            'invoice' => $invoice,
        ])->baseRoute('admin.invoices.show', $invoice->uuid);
    }

    public function storeAdjustPaymentAdjustFromWallet(Request $request, Invoice $invoice, InvoiceService $invoiceService)
    {
        if ($response = $this->isInvoiceBalanceNotAdjustable($invoice)) {
            return $response;
        }

        $balance_due = $invoice->total - $invoice->amount_paid;

        $request->validate([
            'amount' => ['required', new MoneyFormat, 'lte:' . $balance_due, 'gt:0'],
        ]);

        $invoice->load(['customer' => function ($q) {
            $q->select('id');
        }]);

        $customer_wallet_balance = $invoice->customer->wallet()->balance();

        if ($customer_wallet_balance < $request->amount) {
            return redirect()->back()->withFail(__('The customer does not have sufficient fund'));
        }

        $invoiceService->adjustBalanceAndPayFromWallet($invoice, $request->amount);

        return redirect()->route('admin.invoices.show', $invoice->uuid)->withSuccess(__('Successfully updated'));
    }

    private function isInvoiceBalanceNotAdjustable(Invoice $invoice)
    {
        if (!($invoice->amount_paid < $invoice->total)) {
            return redirect()->back()->withFail(__('There is nothing to adjust'));
        }
    }
}
