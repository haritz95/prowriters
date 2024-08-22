<?php

namespace App\Notifications;

use App\Models\ProjectManagement\BidRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBidSubmitted extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(private BidRequest $bidRequest)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if (isset($notifiable->id)) {
            return ['mail', 'database'];
        } else {
            return ['mail'];
        }
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(__('app.notifications.new_bid_submitted_subject'))
            ->greeting(__('Hi') . ', ' . $notifiable->first_name)
            ->line(__('app.notifications.new_bid_submitted_message'))
            ->action(__('View Bid Request'), route('customer.bidRequests.show', $this->bidRequest->uuid))
            ->line(__('Thank you!'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => __('app.notifications.new_bid_submitted_message'),
            'url'     => route('customer.bidRequests.show', $this->bidRequest->uuid),

        ];
    }
}
