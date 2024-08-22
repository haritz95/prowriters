<?php

namespace App\Notifications;

use App\Enums\UserType;
use App\Models\ProjectManagement\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StartedWorking extends Notification implements ShouldQueue
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
        $data = $this->toArray($notifiable);

        return (new MailMessage)
            ->subject($this->task->number . ' - ' . __('In Progress'))
            ->line($data['message'])
            ->action(__('View task'), $data['url'])
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
        if (isset($notifiable->id)) {
            $user_type = $notifiable->type;
        } else {
            $user_type = UserType::ADMIN;
        }

        return [
            'message' => __('app.notifications.task_in_progress', ['number' => $this->task->number]),
            'url'     => get_task_page_link_by_user_type($user_type, $this->task->uuid),
        ];
    }

}
