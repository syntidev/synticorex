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
        $this->info('🔄 Fetching dollar rate from DolarAPI...');

        $result = $this->dollarRateService->fetchAndPropagate();

        if ($result['success']) {
            $this->info("✅ Rate updated: Bs. {$result['rate']}");
            $this->info("📊 Propagated to {$result['updated_tenants']} tenants");

            return Command::SUCCESS;
        }

        $this->error("❌ Failed: {$result['message']}");

        return Command::FAILURE;
    }
}
