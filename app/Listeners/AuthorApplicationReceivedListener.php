<?php

namespace App\Listeners;

use App\Events\AuthorApplicationReceivedEvent;
use App\Notifications\NewAuthorApplication;

class AuthorApplicationReceivedListener
{

    /**
     * Handle the event.
     *
     * @param  \App\Events\AuthorApplicationReceivedEvent  $event
     * @return void
     */
    public function handle(AuthorApplicationReceivedEvent $event)
    {
        send_notification_to_admins(new NewAuthorApplication($event->applicant));

        // Send Notification Email to Company
        send_notification_to_company(new NewAuthorApplication($event->applicant));
    }
}
