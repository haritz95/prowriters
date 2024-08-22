<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BidRequestStatusType;
use App\Enums\ServiceType;
use App\Enums\SpacingType;
use App\Enums\UnitType;
use App\Enums\WorkType;
use App\Http\Controllers\Controller;
use App\Models\ProjectManagement\Bid;
use App\Models\ProjectManagement\BidRequest;
use App\Models\ProjectManagement\BidRequestStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BidRequestController extends Controller
{
    public function index(Request $request)
    {
        return inertia('Admin/BidRequests/Index', [
            'data'         => function () {
                // ALWAYS included on first visit
                // OPTIONALLY included on partial reloads
                // ONLY evaluated when needed
                return [
                    'title'     => __('Bid Requests'),
                    'dropdowns' => [
                        'statuses' => BidRequestStatus::orderBy('id', 'ASC')->get()->prepend(['id' => '', 'name' => __('All')]),
                    ],
                ];
            },
            'filters'      => $request->only('search'),
            'bid_requests' => BidRequest::with(['status', 'task' => function ($q) {
                $q->select(['id', 'service_id', 'customer_id'])
                    ->with(['service' => function ($serviceQuery) {
                        $serviceQuery->select(['id', 'name']);
                    }, 'customer' => function ($customerQuery) {
                        $customerQuery->select(['id', 'uuid', 'first_name', 'last_name']);
                    }]);
            }])
                ->when($request->filled('search.status'), function ($query) use ($request) {
                    return $query->where('bid_request_status_id', $request->input('search.status'));
                })
                ->when($request->filled('search.number'), function ($query) use ($request) {
                    return $query->where('number', $request->input('search.number'));
                })
                ->orderBy('id', 'DESC')
                ->paginate(config('app.pagination.per_page'))
                ->withQueryString(),
        ]);
    }

    public function show(Request $request, BidRequest $bidRequest)
    {
        $sort = $this->getSortBy($request);

        return inertia('Admin/BidRequests/Show', [
            'data' => function () use ($bidRequest) {
                // ALWAYS included on first visit
                // OPTIONALLY included on partial reloads
                // ONLY evaluated when needed
                $bidRequest->load(['status', 'task' => function ($q) {
                    $q->select(['id', 'author_id', 'customer_id']);
                }, 'task.customer' => function ($q) {
                    $q->select(['id', 'uuid', 'first_name', 'last_name']);
                }]);

                return [
                    'title'       => __('Bid Requests'),
                    'bid_request' => $bidRequest,
                    'dropdowns'   => [
                        'sort_by_options' => BidRequest::sortingOptions(),
                    ],
                ];
            },
            'filters' => $request->only('filters'),
            'bids'    => Bid::with([
                'author' => function ($query) {
                    $query->select(['id', 'uuid', "code", "photo", "first_name", "last_name"]);
                },
            ])
                ->where('bid_request_id', $bidRequest->id)
                ->orderBy($sort['by'], $sort['type'])
                ->paginate(config('app.pagination.per_page'))
                ->withQueryString(),
        ]);
    }

    public function destroy(BidRequest $bidRequest)
    {
        if ($bidRequest->bid_request_status_id == BidRequestStatusType::HIRED) {
            return redirect()->back()->withFail(__('A author has been already selected.') . ' ' . __('The bid request cannot be deleted'));
        }

        DB::beginTransaction();

        try {
            Bid::where('bid_request_id', $bidRequest->id)->delete();
            $bidRequest->task->delete();
            $bidRequest->delete();

            DB::commit();
            $done = true;

        } catch (\Exception$e) {
            DB::rollback();
            $done = null;
        }

        if ($done) {
            return redirect()->route('admin.bidRequests.index')->withSuccess(__('Successfully deleted'));
        } else {
            return redirect()->back()->withFail(__('Please try again later'));
        }
    }

    // public function brief(BidRequest $bidRequest)
    // {
    //     $bidRequest->load(['task', 'status']);

    //     $bidRequest->load(['status', 'task', 'task.customer' => function ($q) {
    //         $q->select(['id', 'uuid', 'first_name', 'last_name']);
    //     }]);

    //     return inertia('Admin/BidRequests/TaskInformation', [
    //         'task'        => $bidRequest->task->withDetails(),
    //         'bid_request' => $bidRequest,
    //         'data'        => [
    //             'service_types' => ServiceType::getForFrontEnd(),
    //             'work_types'    => WorkType::get(),
    //             'spacing_types' => SpacingType::get(),
    //             'unit_types'    => UnitType::get(),

    //         ],
    //     ]);
    // }

    public function brief(BidRequest $bidRequest)
    {
        return inertia('Admin/BidRequests/TaskInformation', [
            'bid_request' => $bidRequest->load(['status', 'task', 'task.details', 'task.customer' => function ($q) {
                $q->select(['id', 'uuid', 'first_name', 'last_name']);
            }]),
            'task'        => $bidRequest->task,
            'data'        => [
                'briefs' => $bidRequest->task->details->getFields(),
            ],
        ]);
    }

    public function authorProfile(BidRequest $bidRequest, $user)
    {
        $author = $this->getAuthorProfileAsAdminView($user);

        if (!$author) {
            return redirect()->back();
        }

        return inertia()->modal('Admin/BidRequests/AuthorProfile', [
            'data' => [
                'title'      => __('Bidder Profile'),
                'author' => $author,
            ],
        ])->baseRoute('admin.bidRequests.show', $bidRequest->uuid);
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

    private function getAuthorProfileAsAdminView($uuid)
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
