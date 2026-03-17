<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\TenantCustomization;
use Illuminate\Database\Seeder;

class SintiburguerHeroSeeder extends Seeder
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

        $contentBlocks = json_encode([
            'hero' => [
                'title' => 'SYNTI Burguer',
                'subtitle' => 'La calle tiene sabor',
                'cta_text' => 'Ver menú',
                'bg_image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=1200',
                'image_2' => 'https://images.unsplash.com/photo-1586190848861-99aa4a171e90?w=800',
                'image_3' => 'https://images.unsplash.com/photo-1574071318508-1cdbab80d002?w=800',
            ],
        ]);

        TenantCustomization::query()->updateOrCreate(
            ['tenant_id' => $tenant->id],
            [
                'hero_main_filename' => null,
                'hero_secondary_filename' => null,
                'hero_tertiary_filename' => null,
                'hero_image_4_filename' => null,
                'hero_image_5_filename' => null,
                'content_blocks' => $contentBlocks,
            ]
        );

        $this->command?->info("✓ Hero images de Unsplash agregadas a sintiburguer.");
    }
}
