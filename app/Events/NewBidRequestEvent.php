<?php

namespace App\Events;

use App\Models\ProjectManagement\BidRequest;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewBidRequestEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $bidRequest;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(BidRequest $bidRequest)
    {
        $this->bidRequest = $bidRequest;
    }

}
