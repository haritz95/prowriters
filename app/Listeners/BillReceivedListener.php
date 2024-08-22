<?php

namespace App\Listeners;

use App\Events\BillReceivedEvent;
use App\Notifications\NewBill;

class BillReceivedListener
{
    /**
     * Handle the event.
     *
     * @param  BillReceivedEvent  $event
     * @return void
     */
    public function handle(BillReceivedEvent $event)
    {
        send_notification_to_admins(new NewBill($event->bill));
        send_notification_to_company(new NewBill($event->bill));
    }
}
