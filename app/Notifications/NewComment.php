<?php

namespace App\Notifications;

use App\Models\ProjectManagement\Task;
use App\Models\ProjectManagement\TaskMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewComment extends Notification implements ShouldQueue
{
    use Queueable;

    public $comment;
    public $task_page_link;
    public $task;
    public $name;
    public $notification_message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TaskMessage $comment, Task $task)
    {
        $this->comment = $comment;
        $this->task    = $task;
        //$this->task_page_link       = get_task_comment_page_link_by_user_type($this->comment->user->type, $this->comment, $this->task->uuid);
        $this->name                 = $this->comment->user->full_name;
        $this->notification_message = __('app.notifications.new_comment_on_task', ['author' => $this->name, 'task' => $this->task->number]);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $user_type = null;
        if (isset($notifiable->type)) {
            $user_type = $notifiable->type;
        }

        $this->task_page_link = get_task_comment_page_link_by_user_type($user_type, $this->comment, $this->task->uuid);

        if (isset($notifiable->id)) {
            return ['mail', 'database'];
        } else {
            return ['mail'];
        }
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())->subject(__('app.email_subjects.new_task_comment', ['number' => $this->task->number]))
            ->line($this->notification_message)
            ->line("\n")
            ->action(__('View Message'), $this->task_page_link)
            ->line(__('Thank you!'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => $this->notification_message,
            'url'     => $this->task_page_link,

        ];
    }
}
