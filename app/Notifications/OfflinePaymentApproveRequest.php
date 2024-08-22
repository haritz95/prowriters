<?php

namespace App\Notifications;

use App\Models\Payments\PendingForApprovalPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OfflinePaymentApproveRequest extends Notification implements ShouldQueue
{
    use Queueable;

    protected $payment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(PendingForApprovalPayment $payment)
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
            ->subject(__('app.email_subjects.payment_pending_for_approval', ['number' => $this->payment->number]))
            ->line(__('app.notifications.payment_pending_for_approval', ['amount' => format_money($this->payment->amount)]))
            ->action(__('View Payment'), route('admin.payments.pendingApprovals.show', $this->payment->uuid))
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
            'message' => __('app.email_subjects.payment_pending_for_approval', ['number' => $this->payment->number]),
            'url'     => route('admin.payments.pendingApprovals.show', $this->payment->uuid),
        ];
    }
}
