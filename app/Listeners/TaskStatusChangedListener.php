<?php

namespace App\Listeners;

use App\Events\TaskStatusChangedEvent;

class TaskStatusChangedListener
{
    /**
     * Handle the event.
     *
     * @param  OrderStatusChangedEvent  $event
     * @return void
     */
    public function handle(TaskStatusChangedEvent $event)
    {
        $task = $event->task;
        $previousStatus = $event->previousStatus;
        $newStatus = $event->newStatus;

        $state = 'from ' . $previousStatus . ' to ' . $newStatus;
      
    }
}
