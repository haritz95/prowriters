<?php

namespace App\Listeners;

use App\Events\BidApprovedEvent;
use App\Notifications\BidApproved;
use Illuminate\Support\Facades\Notification;

class BidApprovedListener
{

    /**
     * Handle the event.
     *
     * @param  \App\Events\BidApprovedEvent  $event
     * @return void
     */
    public function handle(BidApprovedEvent $event)
    {
        Notification::send($event->task->author, new BidApproved($event->task));
    }
}
