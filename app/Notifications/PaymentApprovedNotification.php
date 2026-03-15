<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Invoice $invoice,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject("✅ Pago confirmado — {$this->invoice->invoice_number}")
            ->greeting("¡Hola, {$this->invoice->tenant->business_name}!")
            ->line("Tu pago ha sido confirmado. Tu plan está activo hasta {$this->invoice->period_end->format('d/m/Y')}.")
            ->line("Número de factura: {$this->invoice->invoice_number}")
            ->action('Ver mi panel', url('/dashboard'))
            ->salutation('Equipo SYNTIweb');
    }
}
