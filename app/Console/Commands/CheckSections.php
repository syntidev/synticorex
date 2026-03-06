<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;

class CheckSections extends Command
{
    protected $signature = 'check:sections {plan=2}';
    protected $description = 'Check sections order for a specific plan';

    public function handle(): void
    {
        $planId = (int)$this->argument('plan');
        $tenant = Tenant::where('plan_id', $planId)->with('customization')->first();

        if (!$tenant) {
            $this->error("No tenant found for plan $planId");
            return;
        }

        if (!$tenant->customization) {
            $this->error("No customization found for tenant {$tenant->business_name}");
            return;
        }

        $sections = data_get($tenant->customization->visual_effects, 'sections_order', []);
        $names = array_column($sections, 'name');

        $this->info("Plan {$planId} Tenant: {$tenant->business_name}");
        $this->info("Sections: " . implode(', ', $names));
        $this->line("Has testimonials: " . (in_array('testimonials', $names) ? '✓ YES' : '✗ NO'));
        
        $this->table(
            ['Section', 'Visible', 'Order'],
            array_map(fn($s) => [$s['name'], $s['visible'] ? 'Yes' : 'No', $s['order']], $sections)
        );
    }
}
