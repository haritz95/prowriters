<?php

namespace App\Listeners;

use App\Events\NewAnnouncementEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NewAnnouncementEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\NewAnnouncementEvent  $event
     * @return void
     */
    public function handle(NewAnnouncementEvent $event)
    {
        //
    }
}
