<?php

namespace App\Events;

use App\Models\Billing\Bill;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BillPaidEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $bill;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Bill $bill)
    {
        $this->bill = $bill;
    }
}
