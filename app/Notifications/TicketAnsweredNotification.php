<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\SupportTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketAnsweredNotification extends Notification
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
        return (new MailMessage())
            ->subject("💬 Respuesta a tu ticket — {$this->ticket->subject}")
            ->line($this->ticket->admin_reply)
            ->action('Ver mi panel', url('/dashboard'));
    }
}
