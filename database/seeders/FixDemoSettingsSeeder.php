<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;

class FixDemoSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $tenants = Tenant::query()
            ->where('is_demo', true)
            ->where('demo_product', 'food')
            ->get();

        if ($tenants->isEmpty()) {
            $this->command?->warn('No demo food tenants found.');
            return;
        }

        foreach ($tenants as $tenant) {
            $settings = $tenant->settings ?? [];

            $settings['engine_settings'] = array_merge(
                $settings['engine_settings'] ?? [],
                ['template' => 'food']
            );

            $tenant->settings = $settings;
            $tenant->save();

            $this->command?->info("✓ {$tenant->subdomain} (ID:{$tenant->id}) → settings.engine_settings.template = food");
        }
    }
}
