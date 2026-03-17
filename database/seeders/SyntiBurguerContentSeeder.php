<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Tenant;
use App\Services\MenuService;
use Illuminate\Database\Seeder;

class SyntiBurguerContentSeeder extends Seeder
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

        // En este esquema no existe whatsapp_number; se usa whatsapp_sales como número principal.
        $tenant->update([
            'whatsapp_sales' => '580000000000',
            'whatsapp_support' => null,
            'whatsapp_active' => 'sales',
        ]);

        $catalog = [
            'Arepas' => [
                ['name' => 'Reina Pepiada', 'price' => 4.50],
                ['name' => 'Dominó', 'price' => 3.50],
                ['name' => 'Pabellón', 'price' => 5.00],
                ['name' => 'Pelúa', 'price' => 4.00],
                ['name' => 'Sifrina', 'price' => 4.50],
                ['name' => 'Catira', 'price' => 4.00],
            ],
            'Hamburguesas' => [
                ['name' => 'Clásica', 'price' => 5.50],
                ['name' => 'BBQ Criolla', 'price' => 6.50],
                ['name' => 'Doble Queso', 'price' => 7.00],
                ['name' => 'La Patrona', 'price' => 8.00],
            ],
            'Hot Dogs' => [
                ['name' => 'Clásico', 'price' => 3.50],
                ['name' => 'Con Papas', 'price' => 4.50],
                ['name' => 'Perro Gourmet', 'price' => 5.50],
                ['name' => 'Perro Loco', 'price' => 5.00],
            ],
            'Pizza' => [
                ['name' => 'Margarita', 'price' => 8.00],
                ['name' => 'Pepperoni', 'price' => 9.50],
                ['name' => 'Hawaiana', 'price' => 9.00],
                ['name' => '4 Quesos', 'price' => 10.00],
            ],
            'Pepitos' => [
                ['name' => 'Pepito Pollo', 'price' => 5.00],
                ['name' => 'Pepito Carne', 'price' => 5.50],
                ['name' => 'Pepito Mixto', 'price' => 6.00],
                ['name' => 'Pepito Especial', 'price' => 6.50],
            ],
            'Postres' => [
                ['name' => 'Tres Leches', 'price' => 3.50],
                ['name' => 'Quesillo', 'price' => 3.00],
                ['name' => 'Torta de Chocolate', 'price' => 3.50],
                ['name' => 'Galletas', 'price' => 2.00],
            ],
            'Bebidas Frías' => [
                ['name' => 'Jugo Natural', 'price' => 2.50],
                ['name' => 'Refresco', 'price' => 2.00],
                ['name' => 'Batido de Fresa', 'price' => 3.50],
                ['name' => 'Batido de Cambur', 'price' => 3.50],
                ['name' => 'Limonada', 'price' => 2.50],
                ['name' => 'Malta', 'price' => 2.00],
            ],
            'Bebidas Calientes' => [
                ['name' => 'Café Negro', 'price' => 1.50],
                ['name' => 'Café con Leche', 'price' => 2.00],
                ['name' => 'Chocolate Caliente', 'price' => 2.50],
                ['name' => 'Té', 'price' => 1.50],
            ],
            'Licores' => [
                ['name' => 'Polar', 'price' => 2.50],
                ['name' => 'Solera', 'price' => 2.50],
                ['name' => 'Regional', 'price' => 2.50],
                ['name' => 'Bicha', 'price' => 3.00],
            ],
        ];

        $menuService = new MenuService();

        foreach ($catalog as $categoryName => $items) {
            $category = $this->updateOrCreateCategory($menuService, (int) $tenant->id, $categoryName);
            $categoryId = (string) ($category['id'] ?? '');

            if ($categoryId === '') {
                continue;
            }

            foreach ($items as $item) {
                $this->updateOrCreateItem(
                    $menuService,
                    (int) $tenant->id,
                    $categoryId,
                    (string) $item['name'],
                    (float) $item['price']
                );
            }

            // Mantener exactamente los ítems definidos para cada categoría.
            $expected = array_map(
                fn (array $item): string => $this->normalizeLabel((string) $item['name']),
                $items
            );

            $updatedCategory = $menuService->getCategory((int) $tenant->id, $categoryId);
            if ($updatedCategory === null) {
                continue;
            }

            $seen = [];
            foreach ($updatedCategory['items'] ?? [] as $existingItem) {
                $existingName = (string) ($existingItem['nombre'] ?? '');
                $existingId = (string) ($existingItem['id'] ?? '');

                if ($existingId === '') {
                    continue;
                }

                $normalized = $this->normalizeLabel($existingName);
                if (! in_array($normalized, $expected, true)) {
                    $menuService->deleteItem((int) $tenant->id, $categoryId, $existingId);
                    continue;
                }

                if (in_array($normalized, $seen, true)) {
                    $menuService->deleteItem((int) $tenant->id, $categoryId, $existingId);
                    continue;
                }

                $seen[] = $normalized;
            }
        }

        $finalMenu = $menuService->getCategories((int) $tenant->id);
        $counts = [];

        foreach ($finalMenu as $category) {
            $name = (string) ($category['nombre'] ?? '');
            if ($name === '') {
                continue;
            }

            $counts[$name] = count($category['items'] ?? []);
        }

        $this->command?->info('SYNTI Burguer seeded: ' . json_encode($counts, JSON_UNESCAPED_UNICODE));
    }

    /**
     * @return array<string, mixed>
     */
    private function updateOrCreateCategory(MenuService $menuService, int $tenantId, string $categoryName): array
    {
        $existing = $this->findCategoryByName($menuService, $tenantId, $categoryName);

        if ($existing !== null) {
            $catId = (string) ($existing['id'] ?? '');

            if ($catId !== '') {
                $updated = $menuService->updateCategory($tenantId, $catId, [
                    'nombre' => $categoryName,
                    'activo' => true,
                ]);

                if ($updated !== null) {
                    return $updated;
                }
            }

            return $existing;
        }

        return $menuService->createCategory($tenantId, [
            'nombre' => $categoryName,
            'activo' => true,
        ]);
    }

    private function updateOrCreateItem(
        MenuService $menuService,
        int $tenantId,
        string $categoryId,
        string $itemName,
        float $price
    ): void {
        $category = $menuService->getCategory($tenantId, $categoryId);

        if ($category === null) {
            return;
        }

        $existingItemId = '';

        foreach ($category['items'] ?? [] as $item) {
            if ($this->normalizeLabel((string) ($item['nombre'] ?? '')) === $this->normalizeLabel($itemName)) {
                $existingItemId = (string) ($item['id'] ?? '');
                break;
            }
        }

        if ($existingItemId !== '') {
            $menuService->updateItem($tenantId, $categoryId, $existingItemId, [
                'nombre' => $itemName,
                'precio' => $price,
                'activo' => true,
            ]);
            return;
        }

        $menuService->createItem($tenantId, $categoryId, [
            'nombre' => $itemName,
            'precio' => $price,
            'activo' => true,
        ]);
    }

    /**
     * @return array<string, mixed>|null
     */
    private function findCategoryByName(MenuService $menuService, int $tenantId, string $categoryName): ?array
    {
        $expected = $this->normalizeLabel($categoryName);

        foreach ($menuService->getCategories($tenantId) as $category) {
            $existingName = (string) ($category['nombre'] ?? '');

            if ($this->normalizeLabel($existingName) === $expected) {
                return $category;
            }
        }

        return null;
    }

    private function normalizeLabel(string $value): string
    {
        $normalized = mb_strtolower(trim($value));

        return strtr($normalized, [
            'á' => 'a',
            'é' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ú' => 'u',
            'ñ' => 'n',
        ]);
    }
}
