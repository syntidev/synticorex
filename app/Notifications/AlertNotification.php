<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AlertNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $type,
        private readonly string $message,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject("⚠️ Alerta SYNTIweb: {$this->type}")
            ->line($this->message)
            ->line('Fecha: ' . now()->format('d/m/Y H:i'))
            ->action('Ir al panel', url('/admin'));
    }
}
