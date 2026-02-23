<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckTenantExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:check-expiry {--dry-run : Preview changes without persisting them}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transition tenants through expiry lifecycle: active → frozen → archived';

    /**
     * Execute the console command.
     *
     * Status flow:
     *   active  + subscription_ends_at <= now()          → frozen
     *   frozen  + subscription_ends_at <= now() - 30d   → archived
     *
     * No tenant data is ever deleted by this command.
     */
    public function handle(): int
    {
        $isDryRun = $this->option('dry-run');
        $now      = Carbon::now();

        if ($isDryRun) {
            $this->warn('🔍 DRY RUN — no changes will be persisted.');
        }

        // ── 1. Active → Frozen ───────────────────────────────────────────────
        // Subscription period ended but still within the implicit 30-day grace window.
        $toFreeze = Tenant::where('status', 'active')
            ->whereNotNull('subscription_ends_at')
            ->where('subscription_ends_at', '<=', $now)
            ->get();

        if ($toFreeze->isEmpty()) {
            $this->line('✅ No active tenants to freeze.');
        } else {
            $this->info("❄️ Tenants to freeze: {$toFreeze->count()}");

            foreach ($toFreeze as $tenant) {
                $this->line("   — [{$tenant->id}] {$tenant->business_name} (expired: {$tenant->subscription_ends_at})");

                if (!$isDryRun) {
                    $tenant->update(['status' => 'frozen']);

                    Log::info('Tenant frozen: subscription expired', [
                        'tenant_id'           => $tenant->id,
                        'business_name'       => $tenant->business_name,
                        'subscription_ends_at' => $tenant->subscription_ends_at,
                    ]);
                }
            }
        }

        // ── 2. Frozen → Archived ─────────────────────────────────────────────
        // 30-day grace window has also elapsed. Data is preserved, site goes dark.
        $graceDeadline = $now->copy()->subDays(30);

        $toArchive = Tenant::where('status', 'frozen')
            ->whereNotNull('subscription_ends_at')
            ->where('subscription_ends_at', '<=', $graceDeadline)
            ->get();

        if ($toArchive->isEmpty()) {
            $this->line('✅ No frozen tenants to archive.');
        } else {
            $this->info("📦 Tenants to archive: {$toArchive->count()}");

            foreach ($toArchive as $tenant) {
                $graceEnded = $tenant->subscription_ends_at->addDays(30)->toDateString();
                $this->line("   — [{$tenant->id}] {$tenant->business_name} (grace ended: {$graceEnded})");

                if (!$isDryRun) {
                    $tenant->update(['status' => 'archived']);

                    Log::warning('Tenant archived: grace period expired', [
                        'tenant_id'           => $tenant->id,
                        'business_name'       => $tenant->business_name,
                        'subscription_ends_at' => $tenant->subscription_ends_at,
                        'grace_ended_at'      => $graceEnded,
                    ]);
                }
            }
        }

        $this->info('✅ Expiry check complete.');

        return Command::SUCCESS;
    }
}
