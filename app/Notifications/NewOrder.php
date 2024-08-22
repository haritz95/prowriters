<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Models\Accounting\Invoice;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewOrder extends Notification implements ShouldQueue
{
    use Queueable;

    public $invoice;
    public $url;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;         
        $this->url     = route('admin.invoices.show', $this->invoice->uuid);        
         
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
            ->line(__('app.notifications.new_order', ['number' => $this->invoice->number]))
            ->action(__('View Invoice'), $this->url)
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
            'message' => __('app.notifications.new_order', ['number' => $this->invoice->number]),
            'url'     => $this->url,

        ];
    }
}
