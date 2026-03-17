<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class GenerateSintiburguerMenuJson extends Seeder
{
    public function run(): void
    {
        $tenantId = 13;

        // Leer todos los products activos
        $products = Product::query()
            ->where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->orderBy('category_name')
            ->orderBy('name')
            ->get();

        if ($products->isEmpty()) {
            $this->command?->warn("No products found for tenant {$tenantId}");
            return;
        }

        // Agrupar por category_name
        $grouped = $products->groupBy('category_name');

        $categories = [];
        $categoryCounter = 1;
        $itemCounter = 1;

        foreach ($grouped as $categoryName => $categoryProducts) {
            $catId = 'cat_' . str_pad((string) $categoryCounter, 3, '0', STR_PAD_LEFT);

            $items = [];
            foreach ($categoryProducts as $product) {
                $itemId = 'item_' . str_pad((string) $itemCounter, 3, '0', STR_PAD_LEFT);

                $items[] = [
                    'id' => $itemId,
                    'nombre' => $product->name,
                    'precio' => (float) ($product->price_usd ?? 0),
                    'activo' => true,
                    'descripcion' => null,
                ];

                $itemCounter++;
            }

            $categories[] = [
                'id' => $catId,
                'nombre' => $categoryName ?? 'Sin Categoría',
                'foto' => null,
                'activo' => true,
                'items' => $items,
            ];

            $categoryCounter++;
        }

        $menuData = [
            'categories' => $categories,
        ];

        // Crear directorio si no existe (Laravel 12: local disk = storage/app/private/)
        $directory = storage_path("app/private/tenants/{$tenantId}/menu");
        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Guardar JSON
        $jsonPath = "{$directory}/menu.json";
        File::put($jsonPath, json_encode($menuData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $this->command?->info("✓ menu.json generado exitosamente en: {$jsonPath}");
        $this->command?->info("  Categorías: " . count($categories));
        $this->command?->info("  Items totales: " . ($itemCounter - 1));
    }
}
