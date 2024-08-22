<?php

namespace App\Listeners;

use App\Events\RequestedForRevisionEvent;
use App\Notifications\ClientRequestedForRevision;
use App\Notifications\RevisionNeeded;
use App\Services\LogActivity;
use Illuminate\Support\Facades\Notification;

class RequestedForRevisionListener
{
    /**
     * Handle the event.
     *
     * @param  RequestedForRevisionEvent  $event
     * @return void
     */
    public function handle(RequestedForRevisionEvent $event)
    {

        LogActivity::customerRequestedForRevision($event->task);

        Notification::send($event->task->author, new RevisionNeeded($event->task));
        send_notification_to_admins(new ClientRequestedForRevision($event->task));
        send_notification_to_company(new ClientRequestedForRevision($event->task));

    }
}
