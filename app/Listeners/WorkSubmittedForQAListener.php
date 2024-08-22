<?php

namespace App\Listeners;

use App\Events\WorkSubmittedForQAEvent;
use App\Notifications\WorkSubmittedForQA;
use App\Services\LogActivity;

class WorkSubmittedForQAListener
{

    /**
     * Handle the event.
     *
     * @param  \App\Events\WorkSubmittedForQAEvent  $event
     * @return void
     */
    public function handle(WorkSubmittedForQAEvent $event)
    {
        LogActivity::authorSubmittedWorkForQA($event->task);
        send_notification_to_admins(new WorkSubmittedForQA($event->task));
        send_notification_to_company(new WorkSubmittedForQA($event->task));
    }
}
