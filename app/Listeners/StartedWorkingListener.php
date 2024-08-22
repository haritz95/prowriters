<?php

namespace App\Listeners;

use App\Events\StartedWorkingEvent;
use App\Notifications\StartedWorking;
use App\Services\LogActivity;
use Illuminate\Support\Facades\Notification;

class StartedWorkingListener
{
    /**
     * Handle the event.
     *
     * @param  StartedWorkingEvent  $event
     * @return void
     */
    public function handle(StartedWorkingEvent $event)
    {

        // // Log user's activity
        LogActivity::authorStartedWorking($event->task);

        // // Send notification to the followers
        Notification::send($event->task->followers, new StartedWorking($event->task));

        // Send notification to the company notification email
        if ($company_notification_email = company_notification_email()) {
            Notification::route('mail', $company_notification_email)
                ->notify(new StartedWorking($event->task));
        }

    }
}
