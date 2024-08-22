<?php

namespace App\Notifications;

use App\Models\Billing\Bill;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBill extends Notification implements ShouldQueue
{
    use Queueable;

    public $bill;
    public $message;
    public $url;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Bill $bill)
    {
        $this->bill    = $bill;
        $total         = format_money($this->bill->total);
        $author    = $this->bill->from->full_name;
        $this->message = __('app.notifications.new_bill', ['total' => $total, 'author' => $author]);
        $this->url     = route('admin.bills.show', $this->bill->uuid);
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
            ->line($this->message)
            ->action(__('View Bill'), $this->url)
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
            'message' => $this->message,
            'url'     => $this->url,
        ];
    }
}
