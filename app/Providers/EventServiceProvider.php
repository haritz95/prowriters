<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class                                  => [
            SendEmailVerificationNotification::class,
        ],
        'Illuminate\Auth\Events\Login'                     => [
            'App\Listeners\LogSuccessfulLogin',
        ],
        'App\Events\NewOrderEvent'                         => [
            'App\Listeners\NewOrderListener',
        ],
        'App\Events\NewBidRequestEvent'                    => [
            'App\Listeners\NewBidRequestListener',
        ],
        'App\Events\NewBidEvent'                           => [
            'App\Listeners\NewBidListener',
        ],
        'App\Events\BidApprovedEvent'                      => [
            'App\Listeners\BidApprovedListener',
        ],
        'App\Events\TaskAssignedEvent'                     => [
            'App\Listeners\TaskAssignedListener',
        ],
        'App\Events\TaskEditorAssignedEvent'               => [
            'App\Listeners\TaskEditorAssignedListener',
        ],
        'App\Events\TaskSelfAssignedEvent'                 => [
            'App\Listeners\TaskSelfAssignedListener',
        ],
        'App\Events\NewCommentEvent'                       => [
            'App\Listeners\NewCommentListener',
        ],
        'App\Events\NewPrivateCommentEvent'                => [
            'App\Listeners\NewPrivateCommentEventListener',
        ],
        'App\Events\NewMessageEvent'                => [
            'App\Listeners\NewMessageEventListener',
        ],
        'App\Events\NewAnnouncementEvent'                => [
            'App\Listeners\NewAnnouncementEventListener',
        ],
        'App\Events\BillReceivedEvent'                     => [
            'App\Listeners\BillReceivedListener',
        ],
        'App\Events\BillPaidEvent'                         => [
            'App\Listeners\BillPaidListener',
        ],
        'App\Events\StartedWorkingEvent'                   => [
            'App\Listeners\StartedWorkingListener',
        ],
        'App\Events\WorkAcceptedEvent'                     => [
            'App\Listeners\WorkAcceptedListener',
        ],
        'App\Events\RequestedForRevisionEvent'             => [
            'App\Listeners\RequestedForRevisionListener',
        ],
        'App\Events\TaskStatusChangedEvent'                => [
            'App\Listeners\TaskStatusChangedListener',
        ],
        'Illuminate\Notifications\Events\NotificationSent' => [
            'App\Listeners\NotificationSentListener',
        ],
        'App\Events\PaymentApprovedEvent'                  => [
            'App\Listeners\PaymentApprovedListener',
        ],
        'App\Events\PaymentDisapprovedEvent'               => [
            'App\Listeners\PaymentDisapprovedListener',
        ],
        'App\Events\WorkSubmittedEvent'                    => [
            'App\Listeners\WorkSubmittedListener',
        ],
        'App\Events\WorkSubmittedForQAEvent'               => [
            'App\Listeners\WorkSubmittedForQAListener',
        ],
        'App\Events\QARejectedEvent'                       => [
            'App\Listeners\QARejectedListener',
        ],
        'App\Events\QAApprovedEvent'                       => [
            'App\Listeners\QAApprovedListener',
        ],
        'App\Events\NewOfflinePaymentApproveRequestEvent'  => [
            'App\Listeners\NewOfflinePaymentApproveRequestListener',
        ],
        'App\Events\AuthorApplicationReceivedEvent'        => [
            'App\Listeners\AuthorApplicationReceivedListener',
        ],
        'App\Events\AuthorApplicationApprovedEvent'        => [
            'App\Listeners\AuthorApplicationApprovedListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
