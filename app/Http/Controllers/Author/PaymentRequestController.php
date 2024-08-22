<?php

namespace App\Http\Controllers\Author;

use App\Events\BillReceivedEvent;
use App\Http\Controllers\Controller;
use App\Models\Author\AuthorProfile;
use App\Models\Billing\Bill;
use App\Models\Billing\BillItem;
use App\Models\Country;
use App\Models\NumberGenerator;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentRequestController extends Controller
{

    public function index(Request $request)
    {
        return inertia('Author/PaymentRequests/Index', [
            'data'             => [
                'title'     => __('Payment requests'),
                'dropdowns' => [
                    'statuses' => [
                        ['id' => '', 'name' => __('All')],
                        ['id' => 'paid', 'name' => __('Paid')],
                        ['id' => 'unpaid', 'name' => __('Unpaid')],
                    ],
                ],
            ],
            // ALWAYS included on first visit
            // OPTIONALLY included on partial reloads
            // ONLY evaluated when needed
            'balance'          => fn()          => Bill::where('author_id', auth()->user()->id)->whereNull('paid')->sum('total'),
            'filters'          => $request->only('search'),
            'payment_requests' => Bill::where('author_id', auth()->user()->id)
                ->archiveForAuthor($request->input('search.show_archived'))
                ->when($request->filled('search'), function ($q) use ($request) {
                    return $q->where(function ($subQuery) use ($request) {
                        if ($request->input('search.status') == 'paid') {
                            $subQuery->whereNotNull('paid');
                        } elseif ($request->input('search.status') == 'unpaid') {
                            $subQuery->whereNull('paid');
                        }
                        if ($request->input('search.number')) {
                            $subQuery->where('number', $request->input('search.number'));
                        }
                        return $subQuery;
                    });
                })
                ->orderBy('id', 'desc')
                ->paginate(config('app.pagination.per_page'))
                ->withQueryString(),

        ]);
    }

    public function create(Request $request, Bill $bill)
    {
        $tasks             = $bill->getBillableTasks(auth()->user()->id);
        $user['full_name'] = auth()->user()->full_name;
        $user['profile']   = AuthorProfile::select(['address', 'city', 'state'])->where('user_id', auth()->user()->id)->get()->first();
        $user['country']   = Country::where('code', auth()->user()->country_code)->get()->first();
        $total             = $bill->getTotalBillableAmount($tasks);

        $payout_threshold = $this->getPayoutThresholdAmount();

        return inertia('Author/PaymentRequests/Create', [
            'filters' => $request->only('filters'),
            'tasks'   => $tasks,
            'data'    => [
                'title'                     => __('Request for payment'),
                'total'                     => $total,
                'is_billable'               => ($payout_threshold > $total) ? false : true,
                'author'                    => $user,
                'minimum_threshold_message' => __('There is a minimum payout threshold of :amount that applies to your earnings. If you have earned less than this amount, you will not be able to request a payout now.', ['amount' => format_money($payout_threshold)]),
                'no_billable_work_message'  => __('Sorry, you have no billable work'),
            ],

        ]);
    }

    public function store(Request $request, Bill $bill)
    {
        $tasks = $bill->getBillableTasks(auth()->user()->id);

        $total = $bill->getTotalBillableAmount($tasks);

        if ($this->getPayoutThresholdAmount() > $total) {
            return redirect()->back()->withFail(__('Your payable amount is less than minimum required amount. Please try again later'));
        }

        $request->validate([
            'name'           => 'required',
            'address'        => 'required|max:500',
            'note'           => 'required|max:500',
            'invoice_number' => 'sometimes|unique:bills,author_id',
        ], [
            'invoice_number.unique' => __('The invoice number already exists'),
        ]);

        DB::beginTransaction();

        try {
            $data              = $request->all();
            $data['uuid']      = Str::orderedUuid();
            $data['author_id'] = auth()->user()->id;
            $data['total']     = $total;
            // Generate the bill number
            $data['number'] = NumberGenerator::gen(Bill::class);

            // Finally create the bill
            $bill = Bill::create($data);

            foreach ($tasks as $task) {

                $item = new BillItem([
                    'task_id' => $task->id,
                    'total'   => $task->author_payment_amount,
                ]);

                // Save the billing items
                $bill->items()->save($item);

                // Mark the order as billed by staff
                $task->is_billed = true;
                $task->save();
            }
            // Dispatching Event
            event(new BillReceivedEvent($bill));

            DB::commit();

            return redirect()->back()->withSuccess(__('Successfully sent'));
        } catch (\Exception $e) {

            DB::rollback();
        }

        return redirect()->back()->withFail(__('Your payment request was not successful. Please try again'));
    }

    public function show(Bill $paymentRequest)
    {
        $paymentRequest->load(['items', 'items.task' => function ($q) {
            $q->select(['id', 'uuid', 'title', 'number', 'service_id']);
        }, 'items.task.service' => function ($q) {
            $q->select(['id', 'name']);
        }]);

        return inertia('Author/PaymentRequests/Show', [
            'data'           => [
                'title'              => __('Payment Request') . ' ' . $paymentRequest->number,
                'previous_link_text' => __('back to ') . ' ' . __('Payment Requests'),
                'company'            => Setting::get(['company_name', 'company_address']),
                'allow'              => [
                    'archiving'   => ($paymentRequest->paid && !$paymentRequest->is_archived_for_author) ? true : false,
                    'unarchiving' => ($paymentRequest->is_archived_for_author) ? true : false,
                ],
            ],
            'paymentRequest' => $paymentRequest,

        ]);
    }

    public function archive(Bill $paymentRequest)
    {
        if (!$paymentRequest->paid) {
            return redirect()->back();
        }

        $paymentRequest->is_archived_for_author = true;
        $paymentRequest->save();

        return redirect()->back()->withSuccess(__('Archived'));
    }

    public function unarchive(Bill $paymentRequest)
    {
        $paymentRequest->is_archived_for_author = null;
        $paymentRequest->save();

        return redirect()->back()->withSuccess(__('Unarchived'));
    }

    private function getPayoutThresholdAmount()
    {
        return settings('payout_amount_threshold');
    }

}
