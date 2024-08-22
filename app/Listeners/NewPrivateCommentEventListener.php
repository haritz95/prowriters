<?php

namespace App\Listeners;

use App\Notifications\NewComment;
use App\Events\NewPrivateCommentEvent;

class NewPrivateCommentEventListener
{

    /**
     * Handle the event.
     *
     * @param  \App\Events\NewPrivateCommentEvent  $event
     * @return void
     */
    public function handle(NewPrivateCommentEvent $event)
    {  
        $task = $event->message->task;
        $notifiable = new NewComment($event->message,  $task);
        
        send_notification_to_task_followers($notifiable, $task, [$event->message->user_id, $task->customer_id]);     

        send_notification_to_company($notifiable);
    }
}
