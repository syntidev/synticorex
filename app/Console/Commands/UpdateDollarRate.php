<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\DollarRateService;
use Illuminate\Console\Command;

class UpdateDollarRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dollar:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and propagate current dollar rate from DolarAPI';

    /**
     * The dollar rate service instance.
     */
    private DollarRateService $dollarRateService;

    /**
     * Create a new command instance.
     */
    public function __construct(DollarRateService $dollarRateService)
    {
        parent::__construct();
        $this->dollarRateService = $dollarRateService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $exitCode = Command::SUCCESS;

        // ── USD ──────────────────────────────────────────────────
        $this->info('🔄 Fetching USD rate...');
        $usd = $this->dollarRateService->fetchAndPropagate();

        if ($usd['success']) {
            $this->info("✅ USD updated: Bs. {$usd['rate']}");
            $this->info("📊 Propagated to {$usd['updated_tenants']} tenants");
        } else {
            $this->error("❌ USD failed: {$usd['message']}");
            $exitCode = Command::FAILURE;
        }

        // ── EUR ──────────────────────────────────────────────────
        $this->info('🔄 Fetching EUR rate...');
        $eur = $this->dollarRateService->fetchAndStoreEuro();

        if ($eur['success']) {
            $this->info("✅ EUR updated: Bs. {$eur['rate']} (source: {$eur['source']})");
            $propagated = $this->dollarRateService->propagateEuroRateToTenants($eur['rate']);
            $this->info("📊 EUR propagated to {$propagated['updated_count']} tenants");
        } else {
            $this->error("❌ EUR failed: {$eur['message']}");
            $exitCode = Command::FAILURE;
        }

        return $exitCode;
    }
}
