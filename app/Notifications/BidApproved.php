<?php

namespace App\Notifications;

use App\Models\ProjectManagement\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BidApproved extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(private Task $task)
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
            ->subject(__('app.notifications.bid_approved_subject'))
            ->greeting(__('Hi') . ', ' . $notifiable->first_name)
            ->line(__('app.notifications.bid_approved_message'))
            ->action(__('View Task'), route('author.tasks.show', $this->task->uuid))
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
            'message' => __('app.notifications.bid_approved_subject'),
            'url'     => route('author.tasks.show', $this->task->uuid),

        ];
    }
}
