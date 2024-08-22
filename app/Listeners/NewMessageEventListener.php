<?php

namespace App\Listeners;

use App\Events\NewMessageEvent;
use App\Notifications\NewMessage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NewMessageEventListener
{


    /**
     * Handle the event.
     *
     * @param  \App\Events\NewMessageEvent  $event
     * @return void
     */
    public function handle(NewMessageEvent $event)
    {
        Notification::send($event->users, new NewMessage($event->messageThread));
    }
}
