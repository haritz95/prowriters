<?php

namespace App\Notifications;

use App\Models\Author\Applicant;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAuthorApplication extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public Applicant $applicant)
    {

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
            ->line(__('app.notifications.new_author_application', ['number' => $this->applicant->number]))
            ->action(__('View Candidate'), route('admin.applicants.show', $this->applicant->uuid))
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
            'message' => __('app.notifications.new_author_application', ['number' => $this->applicant->number]),
            'url'     => route('admin.applicants.show', $this->applicant->uuid),

        ];
    }
}
