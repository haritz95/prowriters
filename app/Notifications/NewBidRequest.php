<?php

namespace App\Notifications;

use App\Models\ProjectManagement\BidRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBidRequest extends Notification implements ShouldQueue
{
    use Queueable;

    public $bidRequest;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(BidRequest $bidRequest)
    {
        $this->bidRequest = $bidRequest;
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
            ->line(__('app.notifications.new_bid_request', ['number' => $this->bidRequest->number]))
            ->action(__('View Bid Request'), route('admin.bidRequests.show', $this->bidRequest->uuid))
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
            'message' => __('New Bid Request') . ' - ' . $this->bidRequest->number,
            'url'     => route('admin.bidRequests.show', $this->bidRequest->uuid),

        ];
    }
}
