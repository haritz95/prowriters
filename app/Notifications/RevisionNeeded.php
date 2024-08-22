<?php

namespace App\Notifications;

use App\Models\ProjectManagement\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RevisionNeeded extends Notification implements ShouldQueue
{
    use Queueable;

    public $task;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
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
            ->subject(__('app.email_subjects.revision_request', ['number' => $this->task->number]))
            ->line(__('app.notifications.revision_request', ['number' => $this->task->number]))
            ->action(__('View task'), route('author.tasks.show', $this->task->id))
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
            'message' => __('app.email_subjects.revision_request', ['number' => $this->task->number]),
            'url'     => route('author.tasks.show', $this->task->id),

        ];
    }
}
