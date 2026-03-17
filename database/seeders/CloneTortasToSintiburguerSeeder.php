<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\TenantCustomization;
use Illuminate\Database\Seeder;

class CloneTortasToSintiburguerSeeder extends Seeder
{
    public function run(): void
    {
        $sintiburguerTenant = Tenant::query()
            ->where('subdomain', 'sintiburguer')
            ->where('is_demo', true)
            ->first();

        if (! $sintiburguerTenant) {
            $this->command?->error("Tenant demo 'sintiburguer' no encontrado.");

            return;
        }

        // Exact visual_effects from tortas (tenant_id=9)
        $visualEffects = json_encode([
            'sections_order' => [
                ['name' => 'products', 'order' => 0, 'visible' => false],
                ['name' => 'payment_methods', 'order' => 1, 'visible' => false],
                ['name' => 'contact', 'order' => 2, 'visible' => false],
            ],
            'sections_config' => [
                'contact' => ['visible' => false],
                'products' => ['visible' => false],
                'payment_methods' => ['visible' => false],
            ],
        ]);

        TenantCustomization::query()->updateOrCreate(
            ['tenant_id' => $sintiburguerTenant->id],
            [
                'hero_layout' => 'gradient',
                'theme_slug' => 'food-callejero',
                'social_networks' => json_encode([
                    'instagram' => ['handle' => '@syntiburguer'],
                    'tiktok' => ['handle' => '@syntiburguer'],
                ]),
                'payment_methods' => json_encode([
                    'pagoMovil' => [
                        'enabled' => true,
                        'number' => '0000000001',
                        'banco' => 'Banco Demo',
                        'cedula' => 'V-00000000',
                    ],
                    'efectivoUsd' => ['enabled' => true],
                ]),
                'visual_effects' => $visualEffects,
                'content_blocks' => json_encode([
                    'hero' => [
                        'title' => 'SYNTI Burguer',
                        'subtitle' => 'La calle tiene sabor',
                        'cta_text' => 'Ver menú',
                    ],
                ]),
            ]
        );

        $this->command?->info("✓ Configuración visual de tortas clonada a sintiburguer.");
    }
}
