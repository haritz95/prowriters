<?php

namespace App\Listeners;

use App\Events\AuthorApplicationApprovedEvent;
use App\Mail\ApplicationApproved;
use Illuminate\Support\Facades\Mail;

class AuthorApplicationApprovedListener
{

    /**
     * Handle the event.
     *
     * @param  \App\Events\AuthorApplicationApprovedEvent  $event
     * @return void
     */
    public function handle(AuthorApplicationApprovedEvent $event)
    {
        $user = $event->user;

        $mail = Mail::to($user->email);

        if ($user->language) {
            $mail->locale($user->language);
        }

        $mail->send(new ApplicationApproved($user, $event->password));
    }
}
