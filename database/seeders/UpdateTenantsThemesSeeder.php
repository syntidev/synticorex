<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\TenantCustomization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateTenantsThemesSeeder extends Seeder
{
    /**
     * Actualizar tenants existentes con temas FlyonUI diversos.
     * 
     * Este seeder asigna temas variados a los tenants para demostrar
     * el sistema de 32 temas oficiales de FlyonUI.
     */
    public function run(): void
    {
        $this->command->info('🎨 Actualizando temas FlyonUI en tenant_customization...');

        // Temas profesionales para diferentes tipos de negocio
        $themesMap = [
            1 => 'corporate',    // TechStart (tech/startup)
            2 => 'business',     // RetailCo (retail/commerce)
            3 => 'nord',         // ServicePro (professional services)
            4 => 'emerald',      // Si hay más tenants
            5 => 'cupcake',
            6 => 'luxury',
            7 => 'synthwave',
            8 => 'cyberpunk',
        ];

        $updated = 0;
        $skipped = 0;

        $customizations = TenantCustomization::all();

        foreach ($customizations as $customization) {
            // Asignar tema basado en ID o usar light por defecto
            $newTheme = $themesMap[$customization->tenant_id] ?? 'light';
            
            // Solo actualizar si es diferente
            if ($customization->theme_slug !== $newTheme) {
                $customization->theme_slug = $newTheme;
                $customization->save();
                
                $this->command->line("  ✓ Tenant #{$customization->tenant_id}: theme_slug = '{$newTheme}'");
                $updated++;
            } else {
                $skipped++;
            }
        }

        $this->command->newLine();
        $this->command->info("✅ Actualización completada:");
        $this->command->line("   • {$updated} tenants actualizados");
        $this->command->line("   • {$skipped} tenants sin cambios");
        
        // Mostrar resumen de temas disponibles
        $this->command->newLine();
        $this->command->info('📋 Temas FlyonUI disponibles (32 total):');
        $this->command->line('   Default: light, dark');
        $this->command->line('   Colorful: cupcake, bumblebee, emerald, valentine, synthwave');
        $this->command->line('   Professional: corporate, business, luxury, nord, autumn');
        $this->command->line('   Dark: dracula, night, coffee, dim, black');
        $this->command->line('   Special: cyberpunk, retro, wireframe, fantasy, aqua, lofi');
    }
}
