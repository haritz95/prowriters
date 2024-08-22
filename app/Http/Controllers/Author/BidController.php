<?php

namespace App\Http\Controllers\Author;

use App\Events\NewBidEvent;
use App\Http\Controllers\Controller;
use App\Models\ProjectManagement\Bid;
use App\Models\ProjectManagement\BidRequest;
use App\Rules\MoneyFormat;
use Illuminate\Http\Request;

class BidController extends Controller
{

    public function create(BidRequest $bidRequest)
    {
        $bidRequest->load(['task.service']);

        return inertia()->modal('Author/BidRequests/Create', [
            'data' => [
                'title'                    => __('Bid for this job'),
                'platform_commission_rate' => $bidRequest->task->service->commission_from_bid,
                'bidRequest_uuid'          => $bidRequest->uuid,
            ],
        ])->baseRoute('author.bidRequests.show', $bidRequest->uuid);
    }

    public function store(Request $request, BidRequest $bidRequest)
    {
        if ($bidRequest->is_closed) {
            return redirect()->back()->withFail(__('The client is not accepting bids anymore'));
        }

        $request->validate([
            'total'               => ['required', new MoneyFormat],
            'duration_days'       => ['integer'],
            'number_of_revisions' => ['integer'],
        ], [], [
            'total' => __('Bidding amount'),
        ]);

        $bidRequest->load(['task.service']);

        $commission_rate = ($bidRequest->task->service->commission_from_bid) ? $bidRequest->task->service->commission_from_bid : 0;

        $commission = ($request->total * $commission_rate) / 100;

        Bid::create([
            'total'                    => $request->total,
            'author_payment_amount'    => $request->total - $commission,
            'platform_commission_rate' => $commission_rate,
            'bid_request_id'           => $bidRequest->id,
            'author_id'                => auth()->user()->id,
            'duration_days'            => $request->duration_days,
            'number_of_revisions'      => $request->number_of_revisions,
        ]);

        //Dispatching Event
        event(new NewBidEvent($bidRequest));

        return redirect()->route('author.bidRequests.show', $bidRequest->uuid)->withSuccess(__('Successfully submitted'));
    }

    public function destroy(Bid $bid)
    {
        $bid_request_uuid = $bid->bidRequest->uuid;
        $bid->delete();

        return redirect()->route('author.bidRequests.show', $bid_request_uuid)->withSuccess(__('Successfully withdrawn'));
    }

}
