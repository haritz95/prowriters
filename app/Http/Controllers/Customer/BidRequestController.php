<?php

namespace App\Http\Controllers\Customer;

use App\Enums\BidRequestStatusType;
use App\Enums\InvoiceItemType;
use App\Enums\ServiceType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBidRequest;
use App\Models\Attachment;
use App\Models\Business\Service;
use App\Models\ProjectManagement\Bid;
use App\Models\ProjectManagement\BidRequest;
use App\Models\ProjectManagement\Task;
use App\Models\User;
use App\Services\CartService;
use App\Services\ProjectManagement\TaskCreateService;
use App\Services\ProjectManagement\TaskDropdownService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BidRequestController extends Controller
{
    public function servicesList(Request $request)
    {
        return inertia('Customer/Tasks/Services', [
            'data' => [
                'title'      => __('Bid Request') . ' > ' . __('Select a service'),
                'services'   => Service::active()->whereNull('not_available_for_bidding')->get(),
                'route_name' => 'customer.bidRequests.create',
            ],
        ]);
    }

    public function index()
    {
        return inertia('Customer/BidRequests/Index', [
            'data'         => [
                'title' => __('Bid Requests'),
            ],
            'bid_requests' => BidRequest::with(['status', 'task' => function ($q) {
                return $q->select(['id', 'service_id', 'title'])
                    ->with(['service' => function ($serviceQuery) {
                        $serviceQuery->select(['id', 'name']);
                    }]);
            }])
                ->withCount('bids')
                ->whereHas('task', function ($q) {
                    return $q->where('customer_id', auth()->user()->id);
                })
            // ->where('bid_request_status_id', '<>', BidRequestStatusType::HIRED)
                ->orderBy('id', 'DESC')
                ->paginate(config('app.pagination.per_page'))
                ->withQueryString(),
        ]);
    }

    public function create(Service $service, TaskDropdownService $taskDropdownService)
    {
        if ($service->not_available_for_bidding) {
            $route_name = (auth()->check()) ? 'customer.bidRequests.services' : 'public.bidRequests.services' ;
            return redirect()->route($route_name);
        }

        $dropdowns                = $taskDropdownService->get($service, true);
        $attachment_configuration = Attachment::configForCreateTask($service);
        $fields                   = $dropdowns['fields'];
        unset($dropdowns['fields']);
        $fields['budget'] = null;

        return inertia('Customer/BidRequests/Create', [
            'data' => [
                'dropdowns'       => $dropdowns,
                'title'           => __('Request for Bid') . ' - ' . $service->name,
                'service'         => $service,
                'service_types'   => ServiceType::getForFrontEnd(),
                'existing_record' => $fields,
                'urls'            => [
                    'request_for_bid' => route('customer.bidRequests.store', $service->slug),
                ],
                'config'          => $attachment_configuration,
            ],

        ]);
    }

    public function store(StoreBidRequest $request, Service $service)
    {
        DB::beginTransaction();

        try {
            $data                   = $request->validated();
            $data['service_id']     = $service->id;
            $data['customer_id']    = auth()->user()->id;
            $data['is_bid_request'] = true;

            $task = (new TaskCreateService())($data);

            $bidRequest = $task->bidRequest()->create([
                'budget'                => $request->budget,
                'bid_request_status_id' => BidRequestStatusType::OPEN,
            ]);

            $done = true;
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            $done = null;
        }

        if ($done) {
            return redirect()->route('customer.bidRequests.show', $bidRequest->uuid)->withSuccess(__('Successfully created'));
        } else {
            return redirect()->back()->withFail(__('Please try again later'));
        }
    }

    public function show(Request $request, BidRequest $bidRequest)
    {
        $sort     = $this->getSortBy($request);
        $bids     = [];
        $task_url = null;

        if ($bidRequest->bid_request_status_id == BidRequestStatusType::HIRED) {

            $bidRequest->load('task');
            $task_url = route('customer.tasks.show', $bidRequest->task->uuid);

        } else {

            $bids = Bid::with([
                'author' => function ($query) {
                    $query->select(['id', 'uuid', "code", "photo"]);
                },
            ])
                ->where('bid_request_id', $bidRequest->id)
                ->orderBy($sort['by'], $sort['type'])
                ->paginate(config('app.pagination.per_page'))
                ->withQueryString()
            ;
        }

        return inertia('Customer/BidRequests/Show', [
            'data' => function () use ($bidRequest, $task_url) {
                // ALWAYS included on first visit
                // OPTIONALLY included on partial reloads
                // ONLY evaluated when needed
                $bidRequest->load('status');

                return [
                    'title'       => __('Bid Requests'),
                    'bid_request' => $bidRequest,
                    'dropdowns'   => [
                        'sort_by_options' => BidRequest::sortingOptions(),
                    ],
                    'is_hired'    => ($bidRequest->bid_request_status_id == BidRequestStatusType::HIRED) ? true : false,
                    'task_url'    => $task_url,
                ];
            },
            'filters' => $request->only('filters'),
            'bids'    => $bids,
        ]);
    }

    public function destroy(BidRequest $bidRequest)
    {
        DB::beginTransaction();

        try {
            Bid::where('bid_request_id', $bidRequest->id)->delete();
            $bidRequest->task->delete();
            $bidRequest->delete();

            DB::commit();
            $done = true;

        } catch (\Exception $e) {
            DB::rollback();
            $done = null;
        }

        if ($done) {
            return redirect()->route('customer.bidRequests.index')->withSuccess(__('Successfully deleted'));
        } else {
            return redirect()->back()->withFail(__('Please try again later'));
        }
    }

    public function brief(BidRequest $bidRequest)
    {
        return inertia('Customer/BidRequests/TaskInformation', [
            'bid_request' => $bidRequest->load(['status', 'task', 'task.details']),
            'task'        => $bidRequest->task,
            'data'        => [
                'briefs' => $bidRequest->task->details->getFields(),
            ],
        ]);
    }

    public function authorProfile(BidRequest $bidRequest, $user)
    {
        $author = $this->getAuthorProfileAsCustomerView($user);

        if (!$author) {
            return redirect()->back();
        }

        return inertia()->modal('Customer/BidRequests/AuthorProfile', [
            'data' => [
                'title'  => __('Bidder Profile'),
                'author' => $author,
            ],
        ])->baseRoute('customer.bidRequests.show', $bidRequest->uuid);
    }

    public function acceptBid(BidRequest $bidRequest, Bid $bid, CartService $cartService)
    {
        $task = Task::with(['service' => function ($q) {
            $q->select(['id', 'name']);
        }])->where('id', $bidRequest->task_id)
            ->select(['id', 'service_id', 'title', 'uuid'])->get()->first();

        $token = $cartService->saveCartForBidPayment(auth()->user()->id, $bid->total, [
            [
                'type'      => InvoiceItemType::EXISTING_TASK,
                'bid_id'    => $bid->id,
                'task_id'   => $task->id,
                'task_uuid' => $task->uuid,
                'name'      => $task->service->name,
                'title'     => $task->title,
                'price'     => $bid->total,
                'quantity'  => 1,
                'sub_total' => $bid->total,
            ],
        ]);

        return redirect()->route('choose_payment_method', ['token' => $token]);
    }

    private function getSortBy(Request $request): array
    {
        $sort_by   = 'id';
        $sort_type = 'DESC';

        if ($request->filled('search') && $request->filled('search.sort_by')) {
            switch ($request->input('search.sort_by')) {
                case 'budget_high_to_low':
                    $sort_by   = 'total';
                    $sort_type = 'DESC';
                    break;
                case 'budget_low_to_high':
                    $sort_by   = 'total';
                    $sort_type = 'ASC';
                    break;
                default:
                    $sort_by   = 'id';
                    $sort_type = 'DESC';
                    break;
            }
        }
        return ['by' => $sort_by, 'type' => $sort_type];
    }

    private function getAuthorProfileAsCustomerView($uuid)
    {
        $number_of_reviews = 5;
        return User::where('uuid', $uuid)
            ->select(['id', 'uuid', 'code', 'photo'])
            ->with(['authorRatings' => function ($q) use ($number_of_reviews) {
                $q->select('ratings.id', 'ratings.user_id', 'comment', 'ratings.number')
                    ->whereNull('hide_from_customer')->orderBy('id', "DESC")->limit($number_of_reviews);
            }, 'authorProfile' => function ($q) {
                $q->select('id', "user_id", "author_level_id", "education_level_id", "bio");
            }

                , 'authorProfile.authorLevel' => function ($q) {
                    $q->select('id', "name");
                },
                'authorProfile.educationLevel'])
            ->withCount('completedTasks')
            ->withAvg('authorRatings', 'number')
            ->get()->first();
    }

}
