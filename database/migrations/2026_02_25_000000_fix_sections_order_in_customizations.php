<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Corrige el orden de las secciones en visual_effects para tenants existentes.
     * El nuevo orden debe ser: products, services, contact, payment_methods, cta, about, testimonials, faq, branches
     */
    public function up(): void
    {
        // Obtener todos los tenant_customization que tienen sections_order
        $customizations = DB::table('tenant_customization')
            ->whereRaw("visual_effects::jsonb ? 'sections_order'")
            ->get();

        foreach ($customizations as $customization) {
            $visualEffects = json_decode($customization->visual_effects, true) ?? [];
            $sectionsOrder = $visualEffects['sections_order'] ?? [];

            if (empty($sectionsOrder)) {
                continue;
            }

            // Crear nuevo orden correcto
            $correctOrder = [
                'products', 'services', 'contact', 'payment_methods', 'cta',
                'about', 'testimonials', 'faq', 'branches'
            ];

            // Reconstruir sections_order con el orden correcto
            $newSectionsOrder = [];
            foreach ($correctOrder as $index => $sectionName) {
                // Buscar en el orden antiguo para preservar visibilidad y settings
                $oldSection = collect($sectionsOrder)->firstWhere('name', $sectionName);

                $newSectionsOrder[] = [
                    'name' => $sectionName,
                    'visible' => $oldSection['visible'] ?? true,
                    'order' => $index,
                ];
            }

            // Actualizar visual_effects con el nuevo orden
            $visualEffects['sections_order'] = $newSectionsOrder;

            DB::table('tenant_customization')
                ->where('id', $customization->id)
                ->update(['visual_effects' => json_encode($visualEffects)]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No es reversible, pero dejamos el placeholder
    }
};
