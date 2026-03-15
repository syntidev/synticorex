<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Domain;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DomainExpiringNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly Domain $domain,
        public readonly int $daysLeft
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $urgency = $this->daysLeft <= 7 ? '🚨 URGENTE: ' : '⚠️ Aviso: ';

        return (new MailMessage)
            ->subject("{$urgency}Dominio {$this->domain->domain} vence en {$this->daysLeft} días")
            ->line("El dominio **{$this->domain->domain}** vence el {$this->domain->expires_at->format('d/m/Y')}.")
            ->line("Quedan **{$this->daysLeft} días** para renovarlo.")
            ->action('Ver en panel admin', url('/admin/domains/' . $this->domain->id));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'domain'     => $this->domain->domain,
            'days_left'  => $this->daysLeft,
            'expires_at' => $this->domain->expires_at?->toDateString(),
            'type'       => 'domain_expiring',
        ];
    }
}
