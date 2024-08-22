<?php

namespace App\Listeners;

use App\Events\QARejectedEvent;
use App\Notifications\QARejected;
use App\Services\LogActivity;
use Illuminate\Support\Facades\Notification;

class QARejectedListener
{

    /**
     * Handle the event.
     *
     * @param  \App\Events\QARejectedEvent  $event
     * @return void
     */
    public function handle(QARejectedEvent $event)
    {
        LogActivity::editorRejectedSubmittedWork($event->task);

        Notification::send($event->task->author, new QARejected($event->task));

        send_notification_to_company(new QARejected($event->task));
    }
}
