<?php

namespace App\Listeners;

use App\Events\TaskEditorAssignedEvent;
use App\Notifications\TaskEditorAssigned;

class TaskEditorAssignedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\TaskEditorAssignedEvent  $event
     * @return void
     */
    public function handle(TaskEditorAssignedEvent $event)
    {
        $task    = $event->task;
        $changes = $task->getChanges();

        if (isset($changes['editor_id']) && $task->editor_id) {
            $event->task->editor->notify((new TaskEditorAssigned($event->task)));
        }
    }
}
