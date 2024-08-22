<?php

namespace App\Listeners;

use App\Events\NewOfflinePaymentApproveRequestEvent;
use App\Notifications\OfflinePaymentApproveRequest;

class NewOfflinePaymentApproveRequestListener
{

    /**
     * Handle the event.
     *
     * @param  \App\Events\NewOfflinePaymentApproveRequest  $event
     * @return void
     */
    public function handle(NewOfflinePaymentApproveRequestEvent $event)
    {
        send_notification_to_admins(new OfflinePaymentApproveRequest($event->payment));
        send_notification_to_company(new OfflinePaymentApproveRequest($event->payment));
    }
}
