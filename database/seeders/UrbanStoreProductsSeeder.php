<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class UrbanStoreProductsSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::query()
            ->where('subdomain', 'urbanstore')
            ->where('is_demo', true)
            ->first();

        if (! $tenant) {
            $this->command?->error("Tenant demo 'urbanstore' no encontrado.");

            return;
        }

        $catalog = [
            'Ropa Dama' => [
                ['name' => 'Blusa Floral', 'price_usd' => 18.00],
                ['name' => 'Vestido Casual', 'price_usd' => 25.00],
                ['name' => 'Jogger Dama', 'price_usd' => 20.00],
                ['name' => 'Crop Top', 'price_usd' => 15.00],
                ['name' => 'Conjunto Deportivo', 'price_usd' => 35.00],
                ['name' => 'Jean Skinny', 'price_usd' => 28.00],
            ],
            'Ropa Caballero' => [
                ['name' => 'Camisa Slim', 'price_usd' => 20.00],
                ['name' => 'Camiseta Básica', 'price_usd' => 12.00],
                ['name' => 'Jean Hombre', 'price_usd' => 30.00],
                ['name' => 'Jogger Hombre', 'price_usd' => 22.00],
                ['name' => 'Polo Sport', 'price_usd' => 18.00],
                ['name' => 'Sudadera', 'price_usd' => 28.00],
            ],
            'Calzado Dama' => [
                ['name' => 'Sneaker Blanco', 'price_usd' => 45.00],
                ['name' => 'Sandalia Casual', 'price_usd' => 30.00],
                ['name' => 'Bota Corta', 'price_usd' => 55.00],
                ['name' => 'Plataforma', 'price_usd' => 40.00],
            ],
            'Calzado Caballero' => [
                ['name' => 'Sneaker Urbano', 'price_usd' => 50.00],
                ['name' => 'Bota Táctica', 'price_usd' => 60.00],
                ['name' => 'Mocasín', 'price_usd' => 45.00],
                ['name' => 'Deportivo', 'price_usd' => 48.00],
            ],
            'Accesorios' => [
                ['name' => 'Gorra Snapback', 'price_usd' => 15.00],
                ['name' => 'Cinturón Cuero', 'price_usd' => 18.00],
                ['name' => 'Collar Acero', 'price_usd' => 12.00],
                ['name' => 'Pulsera', 'price_usd' => 8.00],
                ['name' => 'Gafas de Sol', 'price_usd' => 22.00],
            ],
            'Bolsos Dama' => [
                ['name' => 'Cartera Mini', 'price_usd' => 25.00],
                ['name' => 'Tote Bag', 'price_usd' => 35.00],
                ['name' => 'Mochila Urbana', 'price_usd' => 40.00],
                ['name' => 'Clutch Noche', 'price_usd' => 28.00],
            ],
            'Electrónicos' => [
                ['name' => 'Audífonos Bluetooth', 'price_usd' => 35.00],
                ['name' => 'Smartwatch', 'price_usd' => 65.00],
                ['name' => 'Cargador Inalámbrico', 'price_usd' => 20.00],
                ['name' => 'Power Bank', 'price_usd' => 25.00],
                ['name' => 'Funda Premium', 'price_usd' => 12.00],
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
