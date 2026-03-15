<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentRejectedNotification extends Notification
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
            ->subject("⚠️ Pago no confirmado — {$this->invoice->invoice_number}")
            ->greeting("Hola, {$this->invoice->tenant->business_name}")
            ->line("No pudimos confirmar tu pago. Motivo: {$this->invoice->admin_notes}")
            ->line('Por favor, verifica los datos y reporta el pago nuevamente.')
            ->action('Reportar pago', url('/dashboard/billing'))
            ->salutation('Equipo SYNTIweb');
    }
}
