<?php

namespace App\Listeners;

use App\Events\TaskAssignedEvent;
use App\Notifications\TaskAssigned;
use App\Services\LogActivity;

class TaskAssignedListener
{
    /**
     * Handle the event.
     *
     * @param  TaskAssignedEvent  $event
     * @return void
     */
    public function handle(TaskAssignedEvent $event)
    {

        $task    = $event->task;
        $changes = $task->getChanges();

        if (isset($changes['author_id']) && $task->author_id) {
            $event->task->author->notify((new TaskAssigned($event->task)));
            LogActivity::AdminAssignedTaskToAuthor($event->task);
        }

        if (isset($changes['author_payment_amount']) && $task->author_payment_amount) {

            LogActivity::AdminUpdatedTaskPaymentAmount($task);
        }       

    }
}
