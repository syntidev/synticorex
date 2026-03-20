<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tenant_customization')) {
            return;
        }

        $customizations = DB::table('tenant_customization')
            ->whereRaw("visual_effects::jsonb ? 'sections_order'")
            ->get();

        foreach ($customizations as $customization) {
            $visualEffects = json_decode($customization->visual_effects, true) ?? [];
            $sectionsOrder = $visualEffects['sections_order'] ?? [];

            if (empty($sectionsOrder)) {
                continue;
            }

            $correctOrder = [
                'products', 'services', 'contact', 'payment_methods', 'cta',
                'about', 'testimonials', 'faq', 'branches'
            ];

            $newSectionsOrder = [];
            foreach ($correctOrder as $index => $sectionName) {
                $oldSection = collect($sectionsOrder)->firstWhere('name', $sectionName);
                $newSectionsOrder[] = [
                    'name' => $sectionName,
                    'visible' => $oldSection['visible'] ?? true,
                    'order' => $index,
                ];
            }

            $visualEffects['sections_order'] = $newSectionsOrder;
            DB::table('tenant_customization')
                ->where('id', $customization->id)
                ->update(['visual_effects' => json_encode($visualEffects)]);
        }
    }

    public function down(): void
    {
        //
    }
};