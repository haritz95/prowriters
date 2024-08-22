<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Payments\Payment;
use App\Services\DownloadService;
use App\Services\PaymentRecordService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    public function index(Request $request)
    {
        return inertia('Admin/Payments/Index', [
            'data'     => [
                'title' => __('Received Payments'),
                'urls'  => [
                    'search' => route('admin.payments.index'),
                ],
            ],
            'filters'  => $request->only('filters'),
            'payments' => Payment::with(['from' => function ($query) {
                $query->select('id', 'uuid', 'first_name', 'last_name');
            }])

                ->when($request->filled('filters.search'), function ($q) use ($request) {
                    return $q->where('number', $request->filters['search'])
                        ->orWhere('reference', $request->filters['search']);
                })
                ->orderBy('id', 'DESC')
                ->paginate(config('app.pagination.per_page'))
                ->withQueryString(),
        ]);
    }

    public function create()
    {
        return inertia('Admin/Payments/Create', [
            'data' => [
                'title' => __('Add Payment Entry'),

            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePaymentRequest $request, PaymentRecordService $paymentRecordService)
    {
        $data = $request->validated();

        $data['date'] = Carbon::parse($data['date'])->setTimezone('UTC')->format('Y-m-d');

        // Record the Payment Information
        $payment = $paymentRecordService->store($data['customer_id'], $data['method'], $data['amount'], $data['reference'], null, $data['date'], $data['internal_note'], auth()->user()->id);

        return redirect()->route('admin.payments.show', $payment->uuid)->withSuccess(__('Successfully created'));
    }

    /**
     * Show the payment for a given resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show(Payment $payment)
    {
        return inertia('Admin/Payments/Show', [
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

}
