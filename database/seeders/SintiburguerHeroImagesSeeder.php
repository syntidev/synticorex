<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\TenantCustomization;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;

class SintiburguerHeroImagesSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::query()
            ->where('subdomain', 'sintiburguer')
            ->where('is_demo', true)
            ->first();

        if (! $tenant) {
            $this->command?->error("Tenant demo 'sintiburguer' no encontrado.");

            return;
        }

        $tenantStoragePath = storage_path("app/tenants/{$tenant->id}");

        // Create tenant storage directory if not exists
        if (! File::exists($tenantStoragePath)) {
            File::makeDirectory($tenantStoragePath, 0755, true);
            $this->command?->info("✓ Directorio creado: {$tenantStoragePath}");
        }

        // Images to download
        $images = [
            'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=1200' => 'hero_slot_1.webp',
            'https://images.unsplash.com/photo-1586190848861-99aa4a171e90?w=800' => 'hero_slot_2.webp',
            'https://images.unsplash.com/photo-1574071318508-1cdbab80d002?w=800' => 'hero_slot_3.webp',
        ];

        $downloadedFiles = [];

        foreach ($images as $url => $filename) {
            try {
                $response = Http::timeout(30)->get($url);

                if ($response->successful()) {
                    $filePath = "{$tenantStoragePath}/{$filename}";
                    File::put($filePath, $response->body());
                    $downloadedFiles[$filename] = true;
                    $this->command?->info("✓ Descargado: {$filename}");
                } else {
                    $this->command?->warn("✗ Error descargando {$filename}: HTTP {$response->status()}");
                }
            } catch (\Exception $e) {
                $this->command?->warn("✗ Error descargando {$filename}: {$e->getMessage()}");
            }
        }

        // Update tenant_customization with downloaded filenames
        if (! empty($downloadedFiles)) {
            TenantCustomization::query()->updateOrCreate(
                ['tenant_id' => $tenant->id],
                [
                    'hero_main_filename' => $downloadedFiles['hero_slot_1.webp'] ?? null ? 'hero_slot_1.webp' : null,
                    'hero_secondary_filename' => $downloadedFiles['hero_slot_2.webp'] ?? null ? 'hero_slot_2.webp' : null,
                    'hero_tertiary_filename' => $downloadedFiles['hero_slot_3.webp'] ?? null ? 'hero_slot_3.webp' : null,
                ]
            );

            $this->command?->info("✓ Hero filenames actualizados en tenant_customization para tenant_id={$tenant->id}");
        }
    }
}
