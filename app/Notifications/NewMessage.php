<?php

namespace App\Notifications;

use App\Enums\UserType;
use App\Models\Messages\MessageThread;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMessage extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(private MessageThread $messageThread)
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
            ->subject(__('app.email_subjects.new_message'))
            ->greeting(__('Hi') . ', ' . $notifiable->first_name)
            ->line(__('app.notifications.new_message'))
            ->action(__('View Message'), $this->getUrl($notifiable))
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
            'message' => __('app.email_subjects.new_message'),
            'url'     => $this->getUrl($notifiable),

        ];
    }

    private function getUrl($notifiable)
    {
        if (isset($notifiable->type) && $notifiable->type == UserType::AUTHOR) {
            return route('author.messageThreads.show', $this->messageThread->uuid);
        } else {
            return route('admin.messageThreads.show', $this->messageThread->uuid);
        }
    }
}
