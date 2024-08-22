<?php

namespace App\Events;

use App\Models\Payments\PendingForApprovalPayment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewOfflinePaymentApproveRequestEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public PendingForApprovalPayment $payment)
    {
        //
    }

}
