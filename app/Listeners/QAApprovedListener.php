<?php

namespace App\Listeners;

use App\Events\QAApprovedEvent;
use App\Notifications\QAApproved;
use App\Notifications\WorkSubmitted;
use App\Services\LogActivity;
use Illuminate\Support\Facades\Notification;

class QAApprovedListener
{    

    /**
     * Handle the event.
     *
     * @param  \App\Events\QAApprovedEvent  $event
     * @return void
     */
    public function handle(QAApprovedEvent $event)
    {
        LogActivity::editorApprovedSubmittedWork($event->task);
        
        Notification::send($event->task->customer, new WorkSubmitted($event->task));

        send_notification_to_company(new QAApproved($event->task));
    }
}
