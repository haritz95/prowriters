<?php

namespace App\Listeners;

use App\Events\NewBidRequestEvent;
use App\Models\ProjectManagement\Task;
use App\Notifications\NewBidRequest;
use Illuminate\Support\Facades\Notification;

class NewBidRequestListener
{

    /**
     * Handle the event.
     *
     * @param  \App\Events\NewBidRequestEvent  $event
     * @return void
     */
    public function handle(NewBidRequestEvent $event)
    {
        // author_level_id

        

        // Send notification to the Authors
        //$authors = Task::getEligibleAuthors($event->bidRequest->task_id);
        //Notification::send($authors, new NewOrder($event->invoice));

        // Send notification to the Admins
        send_notification_to_admins(new NewBidRequest($event->bidRequest));

        // Send Notification Email to Company
        send_notification_to_company(new NewBidRequest($event->bidRequest));
    }
}
