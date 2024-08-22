<?php

namespace App\Listeners;

use App\Events\PaymentDisapprovedEvent;
use App\Models\User;
use App\Notifications\PaymentDisapproved;
use App\Services\LogActivity;
use Illuminate\Support\Facades\Notification;

class PaymentDisapprovedListener
{
    /**
     * Handle the event.
     *
     * @param  PaymentDisapprovedEvent  $event
     * @return void
     */
    public function handle(PaymentDisapprovedEvent $event)
    {
        // Log user's activity
        LogActivity::offlinePaymentDisapproved($event->data);

        // Send notification to the client
        $paymentFrom = User::find($event->data['user_id']);
        Notification::send($paymentFrom, new PaymentDisapproved($event->data));

    }
}
