<?php

namespace App\Http\Controllers\Author;

use App\Enums\BidRequestStatusType;
use App\Http\Controllers\Controller;
use App\Models\Author\AuthorProfile;
use App\Models\Business\AuthorLevel;
use App\Models\Business\Service;
use App\Models\ProjectManagement\Bid;
use App\Models\ProjectManagement\BidRequest;
use Illuminate\Http\Request;

class BidRequestController extends Controller
{
    public function index(Request $request)
    {
        $profile = $this->getAuthorProfile();


        $author_level_ids = AuthorLevel::select('id')->where('numeric_value', '<=', $profile->authorLevel->numeric_value)->get()->pluck('id');

        $services = array_filter([$profile->service_id_1, $profile->service_id_2, $profile->service_id_3]);

        $sort = $this->getSortBy($request);

        return inertia('Author/BidRequests/Index', [
            'data'         => function () use ($services) {
                // ALWAYS included on first visit
                // OPTIONALLY included on partial reloads
                // ONLY evaluated when needed
                return [
                    'title'        => __('Bid Requests'),
                    'applied_bids' => Bid::withWhereHas('bidRequest', function ($q) {
                        $q->where('bid_request_status_id', BidRequestStatusType::OPEN);
                    })->where('author_id', auth()->user()->id)->get(),
                    'dropdowns'    => [
                        'services'        => Service::select(['id', 'name'])
                            ->whereIn('id', $services)->get()
                            ->prepend(['id' => '', 'name' => __('All')]),
                        'sort_by_options' => BidRequest::sortingOptions(),
                    ],
                ];
            },
            'filters'      => $request->only('filters'),
            'bid_requests' => BidRequest::where('bid_request_status_id', BidRequestStatusType::OPEN)
                ->withWhereHas('task', function ($q) use ($author_level_ids, $services, $request) {

                    $q->select(['id', 'author_level_id', 'title', 'service_id'])
                        ->withWhereHas('service', function ($serviceQuery) use ($services, $request) {

                            if ($request->filled('search') && $request->filled('search.service')) {

                                $serviceQuery->where('id', $request->input('search.service'));
                            } else {
                                $serviceQuery->whereIn('id', $services);
                            }
                        })
                        ->where(function ($subQuery) use ($author_level_ids) {
                            return $subQuery->whereNull('author_level_id')
                                ->orWhereIn('author_level_id', $author_level_ids);
                        });

                })
                ->whereDoesntHave('bids', function ($q) {
                    $q->where('author_id', auth()->user()->id);
                })
                ->orderBy($sort['by'], $sort['type'])
                ->paginate(config('app.pagination.per_page'))
                ->withQueryString(),
        ]);
    }

    public function show(BidRequest $bidRequest)
    {       
        $bid = Bid::where('bid_request_id', $bidRequest->id)->where('author_id', auth()->user()->id)->get()->first();

        return inertia('Author/BidRequests/Show', [
            'bid_request' => $bidRequest->load('task', 'status'),
            'task'        => $bidRequest->task,
            'data'        => [
                'title'  => __('Bid Request') . ' ' . $bidRequest->number,
                'briefs' => $bidRequest->task->details->getFields(),
                'bid'    => $bid,
                'allow_bidding' => (!$bid && $bidRequest->bid_request_status_id == BidRequestStatusType::OPEN) ? true : null,
                'allow_withdraw_bid' => ($bid && $bidRequest->bid_request_status_id != BidRequestStatusType::HIRED) ? true : null,
            ],
        ]);
    }

    private function getAuthorProfile()
    {
        return AuthorProfile::select(['id', 'author_level_id', 'service_id_1', 'service_id_2', 'service_id_3'])
            ->with(['authorLevel'])->where('user_id', auth()->user()->id)
            ->get()
            ->first();
    }

    private function getSortBy(Request $request): array
    {
        $sort_by   = 'id';
        $sort_type = 'DESC';

        if ($request->filled('search') && $request->filled('search.sort_by')) {
            switch ($request->input('search.sort_by')) {
                case 'budget_high_to_low':
                    $sort_by   = 'budget';
                    $sort_type = 'DESC';
                    break;
                case 'budget_low_to_high':
                    $sort_by   = 'budget';
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

}
