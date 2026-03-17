<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class SyntiBurguerProductsSeeder extends Seeder
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

        $catalog = [
            'Arepas' => [
                ['name' => 'Reina Pepiada', 'price_usd' => 4.50],
                ['name' => 'Dominó', 'price_usd' => 3.50],
                ['name' => 'Pabellón', 'price_usd' => 5.00],
                ['name' => 'Pelúa', 'price_usd' => 4.00],
                ['name' => 'Sifrina', 'price_usd' => 4.50],
                ['name' => 'Catira', 'price_usd' => 4.00],
            ],
            'Hamburguesas' => [
                ['name' => 'Clásica', 'price_usd' => 5.50],
                ['name' => 'BBQ Criolla', 'price_usd' => 6.50],
                ['name' => 'Doble Queso', 'price_usd' => 7.00],
                ['name' => 'La Patrona', 'price_usd' => 8.00],
            ],
            'Hot Dogs' => [
                ['name' => 'Clásico', 'price_usd' => 3.50],
                ['name' => 'Con Papas', 'price_usd' => 4.50],
                ['name' => 'Perro Gourmet', 'price_usd' => 5.50],
                ['name' => 'Perro Loco', 'price_usd' => 5.00],
            ],
            'Pizza' => [
                ['name' => 'Margarita', 'price_usd' => 8.00],
                ['name' => 'Pepperoni', 'price_usd' => 9.50],
                ['name' => 'Hawaiana', 'price_usd' => 9.00],
                ['name' => '4 Quesos', 'price_usd' => 10.00],
            ],
            'Pepitos' => [
                ['name' => 'Pepito Pollo', 'price_usd' => 5.00],
                ['name' => 'Pepito Carne', 'price_usd' => 5.50],
                ['name' => 'Pepito Mixto', 'price_usd' => 6.00],
                ['name' => 'Pepito Especial', 'price_usd' => 6.50],
            ],
            'Postres' => [
                ['name' => 'Tres Leches', 'price_usd' => 3.50],
                ['name' => 'Quesillo', 'price_usd' => 3.00],
                ['name' => 'Torta de Chocolate', 'price_usd' => 3.50],
                ['name' => 'Galletas', 'price_usd' => 2.00],
            ],
            'Bebidas Frías' => [
                ['name' => 'Jugo Natural', 'price_usd' => 2.50],
                ['name' => 'Refresco', 'price_usd' => 2.00],
                ['name' => 'Batido de Fresa', 'price_usd' => 3.50],
                ['name' => 'Batido de Cambur', 'price_usd' => 3.50],
                ['name' => 'Limonada', 'price_usd' => 2.50],
                ['name' => 'Malta', 'price_usd' => 2.00],
            ],
            'Bebidas Calientes' => [
                ['name' => 'Café Negro', 'price_usd' => 1.50],
                ['name' => 'Café con Leche', 'price_usd' => 2.00],
                ['name' => 'Chocolate Caliente', 'price_usd' => 2.50],
                ['name' => 'Té', 'price_usd' => 1.50],
            ],
            'Licores' => [
                ['name' => 'Polar', 'price_usd' => 2.50],
                ['name' => 'Solera', 'price_usd' => 2.50],
                ['name' => 'Regional', 'price_usd' => 2.50],
                ['name' => 'Bicha', 'price_usd' => 3.00],
            ],
        ];

        foreach ($catalog as $categoryName => $products) {
            foreach ($products as $index => $product) {
                Product::query()->updateOrCreate(
                    [
                        'tenant_id' => $tenant->id,
                        'name' => $product['name'],
                    ],
                    [
                        'category_name' => $categoryName,
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