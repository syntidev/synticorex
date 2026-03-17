<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class DonazProductsSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::query()
            ->where('subdomain', 'donaz')
            ->where('is_demo', true)
            ->first();

        if (! $tenant) {
            $this->command?->error("Tenant demo 'donaz' no encontrado.");

            return;
        }

        $catalog = [
            'Donas' => [
                ['name' => 'Glaseada', 'price_usd' => 2.50],
                ['name' => 'Chocolate', 'price_usd' => 2.50],
                ['name' => 'Fresa', 'price_usd' => 2.50],
                ['name' => 'Oreo', 'price_usd' => 3.00],
                ['name' => 'Canela', 'price_usd' => 2.00],
                ['name' => 'Arcoíris', 'price_usd' => 3.00],
                ['name' => 'Rellena Crema', 'price_usd' => 3.50],
                ['name' => 'Rellena Mermelada', 'price_usd' => 3.00],
            ],
            'Tortas' => [
                ['name' => 'Tres Leches', 'price_usd' => 4.50],
                ['name' => 'Chocolate', 'price_usd' => 4.00],
                ['name' => 'Red Velvet', 'price_usd' => 5.00],
                ['name' => 'Zanahoria', 'price_usd' => 4.00],
            ],
            'Quesillo' => [
                ['name' => 'Clásico', 'price_usd' => 3.00],
                ['name' => 'Coco', 'price_usd' => 3.50],
                ['name' => 'Chocolate', 'price_usd' => 3.50],
            ],
            'Galletas' => [
                ['name' => 'Chispas de Chocolate', 'price_usd' => 2.00],
                ['name' => 'Mantequilla', 'price_usd' => 1.50],
                ['name' => 'Oreo Casera', 'price_usd' => 2.50],
                ['name' => 'Avena y Pasas', 'price_usd' => 2.00],
            ],
            'Bebidas' => [
                ['name' => 'Café Negro', 'price_usd' => 1.50],
                ['name' => 'Café con Leche', 'price_usd' => 2.00],
                ['name' => 'Chocolate Caliente', 'price_usd' => 2.50],
                ['name' => 'Té', 'price_usd' => 1.50],
                ['name' => 'Limonada', 'price_usd' => 2.50],
            ],
        ];

        foreach ($catalog as $categoryName => $products) {
            foreach ($products as $index => $product) {
                Product::query()->updateOrCreate(
                    [
                        'tenant_id' => $tenant->id,
                        'category_name' => $categoryName,
                        'name' => $product['name'],
                    ],
                    [
                        'price_usd' => $product['price_usd'],
                        'position' => $index + 1,
                        'is_active' => true,
                    ]
                );
            }
        }

        $counts = Product::query()
            ->where('tenant_id', $tenant->id)
            ->selectRaw('category_name, COUNT(*) as items')
            ->groupBy('category_name')
            ->pluck('items', 'category_name')
            ->toArray();

        $this->command?->info(json_encode($counts, JSON_UNESCAPED_UNICODE));
    }
}
