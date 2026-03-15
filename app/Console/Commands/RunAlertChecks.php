<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\CompanySetting;
use App\Models\Invoice;
use App\Models\Tenant;
use App\Notifications\AlertNotification;
use App\Services\HealthCheckService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class RunAlertChecks extends Command
{
    protected $signature = 'alerts:check';

    protected $description = 'Ejecuta checks de alerta y envía notificaciones por email';

    public function handle(HealthCheckService $healthCheck): int
    {
        $this->checkStorage();
        $this->checkBcvApi($healthCheck);
        $this->checkExpiringTenants();
        $this->checkFailedJobs();
        $this->checkStalePayments();

        $this->info('Alert checks completados.');

        return self::SUCCESS;
    }

    private function sendAlert(string $type, string $message): void
    {
        $cacheKey = "alert_sent:{$type}";
        if (Cache::has($cacheKey)) {
            return;
        }

        $email = CompanySetting::current()->email_support;
        if (!$email) {
            $this->warn("No hay email_support configurado en CompanySetting.");
            return;
        }

        Notification::route('mail', $email)->notify(new AlertNotification($type, $message));
        Cache::put($cacheKey, true, now()->addHours(6));

        $this->line("Alerta enviada: [{$type}] {$message}");
    }

    private function checkStorage(): void
    {
        $root = PHP_OS_FAMILY === 'Windows' ? 'C:\\' : '/';
        $total = disk_total_space($root);
        $free = disk_free_space($root);

        if ($total <= 0) {
            return;
        }

        $used = ($total - $free) / $total * 100;

        if ($used >= 80) {
            $this->sendAlert('storage_high', round($used, 1) . '% disco usado');
        }
    }

    private function checkBcvApi(HealthCheckService $healthCheck): void
    {
        $checks = $healthCheck->checkAll();
        $bcv = collect($checks)->firstWhere('key', 'bcv_api');

        if ($bcv && $bcv['status'] === 'error') {
            $this->sendAlert('bcv_down', 'API tasa BCV no responde');
        }
    }

    private function checkExpiringTenants(): void
    {
        $tenants = Tenant::where('status', 'active')
            ->whereBetween('subscription_ends_at', [now(), now()->addDays(3)])
            ->get();

        foreach ($tenants as $tenant) {
            $days = (int) now()->diffInDays($tenant->subscription_ends_at, false);
            $this->sendAlert(
                "tenant_expiring:{$tenant->id}",
                "{$tenant->business_name} vence en {$days} días",
            );
        }
    }

    private function checkFailedJobs(): void
    {
        $count = DB::table('failed_jobs')->count();

        if ($count > 10) {
            $this->sendAlert('failed_jobs', "{$count} jobs fallidos en cola");
        }
    }

    private function checkStalePayments(): void
    {
        $count = Invoice::where('status', 'pending_review')
            ->where('created_at', '<', now()->subHours(48))
            ->count();

        if ($count > 0) {
            $this->sendAlert('payment_stale', "{$count} pagos llevan más de 48h sin revisar");
        }
    }
}
