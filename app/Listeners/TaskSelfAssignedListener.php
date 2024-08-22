<?php

namespace App\Listeners;

use App\Events\TaskSelfAssignedEvent;
use App\Notifications\SelfAssignedTask;

class TaskSelfAssignedListener
{

    /**
     * Handle the event.
     *
     * @param  \App\Events\TaskSelfAssignedEvent  $event
     * @return void
     */
    public function handle(TaskSelfAssignedEvent $event)
    {
        send_notification_to_company((new SelfAssignedTask($event->task)));

        send_notification_to_admins((new SelfAssignedTask($event->task)));
    }
}
