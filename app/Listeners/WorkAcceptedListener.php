<?php

namespace App\Listeners;

use App\Events\WorkAcceptedEvent;
use App\Notifications\DeliveryComplete;
use App\Notifications\TaskCompleted;
use App\Services\LogActivity;
use Illuminate\Support\Facades\Notification;

class WorkAcceptedListener
{
    /**
     * Handle the event.
     *
     * @param  WorkAcceptedEvent  $event
     * @return void
     */
    public function handle(WorkAcceptedEvent $event)
    {
        LogActivity::customerAcceptedWork($event->task);

        Notification::send($event->task->author, new DeliveryComplete($event->task));

        send_notification_to_company(new TaskCompleted($event->task));

    }
}
