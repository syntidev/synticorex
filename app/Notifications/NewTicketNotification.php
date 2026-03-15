<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\CompanySetting;
use App\Models\SupportTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewTicketNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly SupportTicket $ticket,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $supportEmail = CompanySetting::current()->email_support ?? config('mail.from.address');

        return (new MailMessage())
            ->to($supportEmail)
            ->subject("🎫 Nuevo ticket #{$this->ticket->id} — {$this->ticket->subject}")
            ->line("Negocio: {$this->ticket->tenant->business_name}")
            ->line("Categoría: {$this->ticket->category}")
            ->line("Mensaje: {$this->ticket->message}")
            ->action('Ver ticket', url("/admin/support-tickets/{$this->ticket->id}"));
    }
}
