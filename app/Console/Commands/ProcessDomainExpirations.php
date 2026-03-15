<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Domain;
use App\Notifications\DomainExpiringNotification;
use Illuminate\Console\Command;

class ProcessDomainExpirations extends Command
{
    protected $signature = 'domains:process-expirations';

    protected $description = 'Actualiza estados de vencimiento y envía notificaciones';

    private const ALERT_THRESHOLDS = [45, 30, 15, 7, 1];

    public function handle(): void
    {
        Domain::whereNotNull('expires_at')
            ->where('status', '!=', 'cancelled')
            ->each(function (Domain $domain): void {
                $daysLeft  = $domain->daysUntilExpiry();
                $newStatus = match (true) {
                    $daysLeft > 45   => 'active',
                    $daysLeft > 0    => 'expiring_soon',
                    $daysLeft >= -30 => 'expired',
                    $daysLeft >= -60 => 'grace_period',
                    default          => 'redemption',
                };

                if ($domain->status !== $newStatus) {
                    $old = $domain->status;
                    $domain->update(['status' => $newStatus]);
                    $domain->logEvent('status_changed', ['from' => $old, 'to' => $newStatus]);
                }

                if (in_array($daysLeft, self::ALERT_THRESHOLDS, true)) {
                    $user = $domain->tenant?->user;
                    if ($user) {
                        $user->notify(new DomainExpiringNotification($domain, $daysLeft));
                        $domain->logEvent('expiry_alert_sent', ['days_left' => $daysLeft]);
                    }
                }
            });

        $this->info('Vencimientos procesados.');
    }
}
