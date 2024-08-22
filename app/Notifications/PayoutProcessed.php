<?php

namespace App\Notifications;

use App\Models\Billing\Bill;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PayoutProcessed extends Notification implements ShouldQueue
{
    use Queueable;

    public $bill;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Bill $bill)
    {
        $this->bill = $bill;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $total = format_money($this->bill->total);

        return (new MailMessage)
            ->subject(__('app.notifications.payout_processed_line_1'))
            ->greeting(__('Hi') . ' ' . $this->bill->from->full_name . ',')
            ->line(__('app.notifications.payout_processed_line_1'))
            ->line(__('app.notifications.payout_processed_line_2', ['amount' => $total, 'bill_number' => $this->bill->number]))
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
            //
        ];
    }
}
