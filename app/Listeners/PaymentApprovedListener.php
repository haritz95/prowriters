<?php

namespace App\Listeners;

use App\Events\PaymentApprovedEvent;
use App\Notifications\PaymentApproved;
use App\Services\LogActivity;
use Illuminate\Support\Facades\Notification;

class PaymentApprovedListener
{
    /**
     * Handle the event.
     *
     * @param  PaymentApprovedEvent  $event
     * @return void
     */
    public function handle(PaymentApprovedEvent $event)
    {
        // Log user's activity
        LogActivity::offlinePaymentApproved($event->payment);

        Notification::send($event->payment->from, new PaymentApproved($event->payment));
    }
}
