<?php

namespace App\Notifications;

use App\Models\Payments\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentApproved extends Notification implements ShouldQueue
{
    use Queueable;

    protected $payment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
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
            ->subject(__('app.email_subjects.customer_payment_approved'))
            ->greeting(__('Hi') . ', ' . $notifiable->first_name)
            ->line(__('app.notifications.customer_payment_approved', ['amount' => format_money($this->payment->amount), 'payment_method' => $this->payment->method, 'reference_number' => $this->payment->reference]))
            ->action(__('View Receipt'), route('customer.payments.show', $this->payment->uuid))
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
            'message' => __('app.notifications.customer_payment_approved', ['amount' => format_money($this->payment->amount), 'payment_method' => $this->payment->method, 'reference_number' => $this->payment->reference]),
            'url'     => route('customer.payments.show', $this->payment->uuid),

        ];
    }
}
