<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class InvoiceApproved extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private Invoice $invoice, private User $user, private string $type = "regisseur")
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'invoice_approved',
            'user_id' => $this->user->id,
            'invoice_id' => $this->invoice->id,
            'taxpayer_id' => $this->invoice->taxpayer_id,
            'amount' => $this->invoice->amount,
            'is_read' => false,
        ];
    }
}
