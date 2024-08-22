<?php

namespace App\Listeners;

use App\Events\WorkSubmittedEvent;
use App\Notifications\WorkSubmitted;
use App\Services\LogActivity;
use Illuminate\Support\Facades\Notification;

class WorkSubmittedListener
{
    /**
     * Handle the event.
     *
     * @param  WorkSubmittedEvent  $event
     * @return void
     */
    public function handle(WorkSubmittedEvent $event)
    {
        LogActivity::authorSubmittedWorkForCustomerReview($event->task);

        Notification::send($event->task->customer, new WorkSubmitted($event->task));

        send_notification_to_company(new WorkSubmitted($event->task));

    }
}
