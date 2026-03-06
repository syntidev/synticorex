<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix sections_order for Plan 2+ tenants that have incomplete sections
        // This ensures testimonials, about, faq, and branches are available when plan allows

        // Get all tenants with plan_id >= 2
        $tenants = DB::table('tenants')
            ->where('plan_id', '>=', 2)
            ->select('id', 'plan_id')
            ->get();

        foreach ($tenants as $tenant) {
            $customization = DB::table('tenant_customizations')
                ->where('tenant_id', $tenant->id)
                ->first();

            if (!$customization) {
                continue;
            }

            $visualEffects = json_decode($customization->visual_effects ?? '{}', true);
            $sectionsOrder = $visualEffects['sections_order'] ?? [];

            // Build the complete sections_order based on plan
            $completeSections = [
                ['name' => 'products', 'visible' => true, 'order' => 0],
                ['name' => 'services', 'visible' => true, 'order' => 1],
                ['name' => 'contact', 'visible' => true, 'order' => 2],
                ['name' => 'payment_methods', 'visible' => true, 'order' => 3],
                ['name' => 'cta', 'visible' => true, 'order' => 4],
            ];

            // Add Plan 2+ sections
            if ($tenant->plan_id >= 2) {
                $completeSections[] = ['name' => 'about', 'visible' => true, 'order' => 5];
                $completeSections[] = ['name' => 'testimonials', 'visible' => true, 'order' => 6];
            }

            // Add Plan 3 sections
            if ($tenant->plan_id >= 3) {
                $completeSections[] = ['name' => 'faq', 'visible' => true, 'order' => 7];
                $completeSections[] = ['name' => 'branches', 'visible' => true, 'order' => 8];
            }

            // Only update if sections_order was incomplete (or missing some key sections for the plan)
            $existingSectionNames = array_column($sectionsOrder, 'name');
            $completeSectionNames = array_column($completeSections, 'name');

            if (count(array_diff($completeSectionNames, $existingSectionNames)) > 0) {
                $visualEffects['sections_order'] = $completeSections;

                DB::table('tenant_customizations')
                    ->where('tenant_id', $tenant->id)
                    ->update([
                        'visual_effects' => json_encode($visualEffects),
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration only adds missing sections, so reverting would be complex.
        // It's safer to keep the changes. Delete this migration file manually if needed.
    }
};
