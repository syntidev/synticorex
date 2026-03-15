<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SuspendExpiredTenants extends Command
{
    protected $signature = 'tenants:suspend-expired {--dry-run : Preview changes without persisting them}';

    protected $description = 'Suspend active tenants whose subscription has expired (active → frozen)';

    public function handle(): int
    {
        $isDryRun = $this->option('dry-run');
        $now = Carbon::now();

        if ($isDryRun) {
            $this->warn('🔍 DRY RUN — no changes will be persisted.');
        }

        $expired = Tenant::where('status', 'active')
            ->whereNotNull('subscription_ends_at')
            ->where('subscription_ends_at', '<=', $now)
            ->get();

        if ($expired->isEmpty()) {
            $this->info('✅ No active tenants with expired subscriptions.');

            return self::SUCCESS;
        }

        $this->info("❄️ Tenants to suspend: {$expired->count()}");

        foreach ($expired as $tenant) {
            $this->line("   — [{$tenant->id}] {$tenant->business_name} (expired: {$tenant->subscription_ends_at})");

            if (!$isDryRun) {
                $tenant->update(['status' => 'frozen']);

                Log::info('Tenant suspended: subscription expired', [
                    'tenant_id' => $tenant->id,
                    'business_name' => $tenant->business_name,
                    'subscription_ends_at' => $tenant->subscription_ends_at,
                ]);
            }
        }

        $this->info("✅ Done. {$expired->count()} tenant(s) " . ($isDryRun ? 'would be' : '') . ' suspended.');

        return self::SUCCESS;
    }
}
