<?php

namespace App\Listeners;

use App\Events\NewBidEvent;
use App\Notifications\NewBidSubmitted;
use Illuminate\Support\Facades\Notification;

class NewBidListener
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\NewBidEvent  $event
     * @return void
     */
    public function handle(NewBidEvent $event)
    {
        Notification::send($event->bidRequest->task->customer, new NewBidSubmitted($event->bidRequest));
    }
}
