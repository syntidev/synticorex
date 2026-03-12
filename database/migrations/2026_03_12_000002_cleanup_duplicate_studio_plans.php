<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

return new class extends Migration
{
    /**
     * Canonical studio plans must remain on ids 1/2/3 because existing
     * business logic, constants and palette gating depend on those ids.
     * The newer studio-* rows are duplicates and can be removed safely.
     *
     * @var array<int, int>
     */
    private array $duplicateToCanonical = [
        28 => 1,
        29 => 2,
        30 => 3,
    ];

    public function up(): void
    {
        $duplicatePlans = DB::table('plans')
            ->whereIn('id', array_keys($this->duplicateToCanonical))
            ->orderBy('id')
            ->get();

        if ($duplicatePlans->isEmpty()) {
            return;
        }

        $canonicalPlans = DB::table('plans')
            ->whereIn('id', array_values($this->duplicateToCanonical))
            ->orderBy('id')
            ->get();

        if ($canonicalPlans->count() !== count($this->duplicateToCanonical)) {
            throw new RuntimeException('Studio canonical plans 1/2/3 are required before duplicate cleanup.');
        }

        $affectedTenants = DB::table('tenants')
            ->whereIn('plan_id', array_keys($this->duplicateToCanonical))
            ->orderBy('id')
            ->get();

        $this->writeBackup([
            'created_at' => gmdate('c'),
            'duplicate_to_canonical' => $this->duplicateToCanonical,
            'plans' => $duplicatePlans,
            'canonical_plans' => $canonicalPlans,
            'affected_tenants' => $affectedTenants,
        ]);

        DB::transaction(function (): void {
            foreach ($this->duplicateToCanonical as $duplicateId => $canonicalId) {
                DB::table('tenants')
                    ->where('plan_id', $duplicateId)
                    ->update(['plan_id' => $canonicalId]);
            }

            DB::table('plans')
                ->whereIn('id', array_keys($this->duplicateToCanonical))
                ->delete();
        });
    }

    public function down(): void
    {
        $existingIds = DB::table('plans')
            ->whereIn('id', array_keys($this->duplicateToCanonical))
            ->pluck('id')
            ->all();

        if (count($existingIds) === count($this->duplicateToCanonical)) {
            return;
        }

        $canonicalPlans = DB::table('plans')
            ->whereIn('id', array_values($this->duplicateToCanonical))
            ->get()
            ->keyBy('id');

        DB::transaction(function () use ($canonicalPlans, $existingIds): void {
            foreach ($this->duplicateToCanonical as $duplicateId => $canonicalId) {
                if (in_array($duplicateId, $existingIds, true)) {
                    continue;
                }

                $canonical = $canonicalPlans->get($canonicalId);
                if ($canonical === null) {
                    throw new RuntimeException("Canonical plan {$canonicalId} not found while restoring studio duplicates.");
                }

                DB::table('plans')->insert([
                    'id' => $duplicateId,
                    'slug' => match ($duplicateId) {
                        28 => 'studio-oportunidad',
                        29 => 'studio-crecimiento',
                        30 => 'studio-vision',
                    },
                    'blueprint' => 'studio',
                    'name' => $canonical->name,
                    'price_usd' => $canonical->price_usd,
                    'products_limit' => $canonical->products_limit,
                    'services_limit' => $canonical->services_limit,
                    'images_limit' => $canonical->images_limit,
                    'color_palettes' => $canonical->color_palettes,
                    'social_networks_limit' => $canonical->social_networks_limit,
                    'show_dollar_rate' => $canonical->show_dollar_rate,
                    'show_header_top' => $canonical->show_header_top,
                    'show_about_section' => $canonical->show_about_section,
                    'show_payment_methods' => $canonical->show_payment_methods,
                    'show_faq' => $canonical->show_faq,
                    'show_cta_special' => $canonical->show_cta_special,
                    'analytics_level' => $canonical->analytics_level,
                    'seo_level' => $canonical->seo_level,
                    'whatsapp_numbers' => $canonical->whatsapp_numbers,
                    'whatsapp_hour_filter' => $canonical->whatsapp_hour_filter,
                    'created_at' => $canonical->created_at,
                    'updated_at' => $canonical->updated_at,
                ]);
            }
        });
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function writeBackup(array $payload): void
    {
        $directory = storage_path('app/backups/plans');
        File::ensureDirectoryExists($directory);

        $path = $directory . DIRECTORY_SEPARATOR . 'studio-duplicate-plans-' . gmdate('Ymd-His') . '.json';
        $json = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        if ($json === false) {
            throw new RuntimeException('Unable to encode studio duplicate plan backup JSON.');
        }

        File::put($path, $json . PHP_EOL);
    }
};