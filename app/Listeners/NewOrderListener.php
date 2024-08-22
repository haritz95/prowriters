<?php

namespace App\Listeners;

use App\Events\NewOrderEvent;
use App\Mail\OrderSummary;
use App\Models\User;
use App\Notifications\NewOrder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class NewOrderListener
{
    /**
     * Handle the event.
     *
     * @param  NewOrderEvent  $event
     * @return void
     */
    public function handle(NewOrderEvent $event)
    {

        // Send Order Summary to Customer
        Mail::to($event->invoice->customer)->send(new OrderSummary($event->invoice));

        // Send notification to the Admins
        Notification::send(User::admins()->get(), new NewOrder($event->invoice));

        // Send Notification Email to Company
        send_notification_to_company(new NewOrder($event->invoice));

        //NotifyWritersAboutNewOrder::dispatch($order);
    }
}
