<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Plan;
use App\Models\Product;
use App\Models\Service;
use App\Models\Tenant;
use App\Models\TenantCustomization;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;

class FixDemoTenants extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:fix-demos {--dry-run : Ver cambios sin aplicarlos}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corrige inconsistencias en tenants demo (idempotente)';

    /**
     * Sections order per plan (only content sections; hero/footer always present).
     *
     * @var array<int, array<int, string>>
     */
    private array $sectionsByPlan = [
        1 => ['products', 'services'],
        2 => ['header-top', 'about', 'products', 'services', 'payment-methods'],
        3 => ['header-top', 'about', 'products', 'services', 'payment-methods', 'faq'],
    ];

    /**
     * Overlay texts assigned by service index (0-based).
     *
     * @var array<int, string>
     */
    private array $overlayTexts = [
        'Servicio Premium',
        'Solución Especializada',
        'Atención Personalizada',
        'Consultoría Experta',
        'Soporte Integral',
        'Experiencia Exclusiva',
    ];

    // -------------------------------------------------------------------------

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $isDryRun = (bool) $this->option('dry-run');

        if ($isDryRun) {
            $this->warn('🔍 DRY RUN — no se aplicará ningún cambio en la base de datos.');
            $this->newLine();
        }

        $this->info('🔧 Iniciando corrección de tenants demo...');
        $this->newLine();

        $this->fixTechstartProducts($isDryRun);
        $this->normalizeSectionsOrder($isDryRun);
        $this->fixServiceproOverlay($isDryRun);
        $this->fixRetailcoAnalytics($isDryRun);

        $this->newLine();
        $this->info('✅ Correcciones completadas.');

        return self::SUCCESS;
    }

    // ── 1. Reducir productos Techstart ────────────────────────────────────────

    private function fixTechstartProducts(bool $isDryRun): void
    {
        $this->line('<fg=cyan>── Productos Techstart (tenant_id=1)</>');

        $tenant = Tenant::find(1);
        if (! $tenant) {
            $this->warn('   ⚠️  Tenant techstart (id=1) no encontrado. Saltando.');
            return;
        }

        $limit   = 6;
        $total   = Product::where('tenant_id', 1)->count();

        if ($total <= $limit) {
            $this->line("   ✅ Techstart: ya tiene {$total} productos (≤ {$limit}). Sin cambios.");
            return;
        }

        $excess = $total - $limit;
        $ids    = Product::where('tenant_id', 1)
            ->orderByDesc('id')
            ->limit($excess)
            ->pluck('id')
            ->toArray();

        $this->line("   🗑️  Eliminar {$excess} producto(s): IDs [" . implode(', ', $ids) . ']');

        if (! $isDryRun) {
            Product::whereIn('id', $ids)->delete();
        }

        $this->line("   ✅ Techstart: reducido de {$total} a {$limit} productos.");
    }

    // ── 2. Normalizar sections_order ─────────────────────────────────────────

    private function normalizeSectionsOrder(bool $isDryRun): void
    {
        $this->newLine();
        $this->line('<fg=cyan>── Sections order (todos los tenants demo)</>');

        $tenants = Tenant::whereIn('id', [1, 2, 3])->get();

        if ($tenants->isEmpty()) {
            $this->warn('   ⚠️  No se encontraron tenants demo. Saltando.');
            return;
        }

        foreach ($tenants as $tenant) {
            $planId  = $tenant->plan_id;
            $desiredSections = $this->sectionsByPlan[$planId] ?? $this->sectionsByPlan[1];

            $customization = TenantCustomization::where('tenant_id', $tenant->id)->first();

            if (! $customization) {
                $this->warn("   ⚠️  Sin customization para {$tenant->subdomain} (id={$tenant->id}). Saltando.");
                continue;
            }

            $visualEffects = $customization->visual_effects ?? [];

            // Check if already correct
            $current = $visualEffects['sections_order'] ?? [];
            if ($current === $desiredSections) {
                $this->line("   ✅ {$tenant->subdomain}: sections_order ya es correcto.");
                continue;
            }

            $this->line("   🔄 {$tenant->subdomain} (plan {$planId}): " . json_encode($current) . ' → ' . json_encode($desiredSections));

            // Update sections_order
            $visualEffects['sections_order'] = $desiredSections;

            // Ensure sections_config exists and all desired sections are visible
            if (! isset($visualEffects['sections_config'])) {
                $visualEffects['sections_config'] = [];
            }

            foreach ($desiredSections as $section) {
                $existing = $visualEffects['sections_config'][$section] ?? [];
                $visualEffects['sections_config'][$section] = array_merge($existing, ['visible' => true]);
            }

            if (! $isDryRun) {
                $customization->update(['visual_effects' => $visualEffects]);
            }

            $this->line("   ✅ {$tenant->subdomain}: sections_order normalizado para plan {$planId}.");
        }
    }

    // ── 3. Agregar overlay_text a servicios de Servicepro ────────────────────

    private function fixServiceproOverlay(bool $isDryRun): void
    {
        $this->newLine();
        $this->line('<fg=cyan>── Overlay text Servicepro (tenant_id=3)</>');

        $tenant = Tenant::find(3);
        if (! $tenant) {
            $this->warn('   ⚠️  Tenant servicepro (id=3) no encontrado. Saltando.');
            return;
        }

        $services = Service::where('tenant_id', 3)
            ->whereNull('overlay_text')
            ->orderBy('id')
            ->get();

        if ($services->isEmpty()) {
            $this->line('   ✅ Servicepro: todos los servicios ya tienen overlay_text.');
            return;
        }

        $fixed = 0;

        foreach ($services as $index => $service) {
            $text = $this->overlayTexts[$index % count($this->overlayTexts)];
            $this->line("   🖊️  Servicio [{$service->id}] \"{$service->name}\" → \"{$text}\"");

            if (! $isDryRun) {
                $service->update(['overlay_text' => $text]);
            }

            $fixed++;
        }

        $this->line("   ✅ Servicepro: overlay_text agregado a {$fixed} servicio(s).");
    }

    // ── 4. Corregir analytics_level Plan 2 ───────────────────────────────────

    private function fixRetailcoAnalytics(bool $isDryRun): void
    {
        $this->newLine();
        $this->line('<fg=cyan>── Analytics level Plan 2 (retailco)</>');

        $plan = Plan::find(2);
        if (! $plan) {
            $this->warn('   ⚠️  Plan id=2 no encontrado. Saltando.');
            return;
        }

        if ($plan->analytics_level === 'intermediate') {
            $this->line("   ✅ Retailco: analytics_level ya es 'intermediate'. Sin cambios.");
            return;
        }

        $before = $plan->analytics_level ?? '(null)';
        $this->line("   🔄 Plan 2 analytics_level: '{$before}' → 'intermediate'");

        if (! $isDryRun) {
            try {
                $plan->analytics_level = 'intermediate';
                $plan->save();
            } catch (QueryException $e) {
                // The column is likely an ENUM that doesn't yet include 'intermediate'.
                // A migration is needed: ALTER TABLE plans MODIFY analytics_level ENUM('basic','medium','intermediate','advanced')
                $this->warn("   ⚠️  No se pudo actualizar analytics_level: el valor 'intermediate' no está en el ENUM de la columna.");
                $this->warn("      Ejecuta una migración para agregar 'intermediate' al ENUM antes de volver a correr este comando.");
                return;
            }
        }

        $this->line("   ✅ Retailco: analytics_level corregido de '{$before}' a 'intermediate'.");
    }
}
