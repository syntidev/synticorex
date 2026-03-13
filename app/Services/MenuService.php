<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class MenuService
{
    private const DISK = 'local';

    /**
     * Plan limits: [plan_id => [max_items, max_photos]]
     */
    private const PLAN_LIMITS = [
        1 => ['items' => 50,  'photos' => 6],
        2 => ['items' => 100, 'photos' => 12],
        3 => ['items' => 150, 'photos' => 18],
    ];

    public static function limits(int $planId): array
    {
        return self::PLAN_LIMITS[$planId] ?? self::PLAN_LIMITS[1];
    }

    // ─── Read operations ─────────────────────────────────────────

    public function getMenu(int $tenantId): array
    {
        $path = $this->menuPath($tenantId);

        if (!Storage::disk(self::DISK)->exists($path)) {
            return ['categories' => []];
        }

        $content = Storage::disk(self::DISK)->get($path);
        $data = json_decode($content, true);

        if (!is_array($data)) {
            return ['categories' => []];
        }

        // Normalize legacy English keys to canonical Spanish schema
        if (isset($data['categories'])) {
            $data['categories'] = array_map([$this, 'normalizeCategory'], $data['categories']);
        }

        return $data;
    }

    public function getCategories(int $tenantId): array
    {
        return $this->getMenu($tenantId)['categories'] ?? [];
    }

    public function getCategory(int $tenantId, string $catId): ?array
    {
        $categories = $this->getCategories($tenantId);

        foreach ($categories as $cat) {
            if (($cat['id'] ?? '') === $catId) {
                return $cat;
            }
        }

        return null;
    }

    // ─── Category mutations ──────────────────────────────────────

    public function createCategory(int $tenantId, array $data): array
    {
        $menu = $this->getMenu($tenantId);
        $category = [
            'id'     => $this->generateId('cat'),
            'nombre' => $data['nombre'],
            'foto'   => $data['foto'] ?? null,
            'items'  => [],
            'activo' => true,
        ];

        $menu['categories'][] = $category;
        $this->persist($tenantId, $menu);

        return $category;
    }

    public function updateCategory(int $tenantId, string $catId, array $data): ?array
    {
        $menu = $this->getMenu($tenantId);
        $updated = null;

        foreach ($menu['categories'] as &$cat) {
            if (($cat['id'] ?? '') !== $catId) {
                continue;
            }
            if (isset($data['nombre'])) {
                $cat['nombre'] = $data['nombre'];
            }
            if (array_key_exists('foto', $data)) {
                $cat['foto'] = $data['foto'];
            }
            if (isset($data['activo'])) {
                $cat['activo'] = (bool) $data['activo'];
            }
            $updated = $cat;
            break;
        }
        unset($cat);

        if ($updated) {
            $this->persist($tenantId, $menu);
        }

        return $updated;
    }

    public function deleteCategory(int $tenantId, string $catId): bool
    {
        $menu = $this->getMenu($tenantId);
        $original = count($menu['categories']);

        $menu['categories'] = array_values(
            array_filter($menu['categories'], fn(array $c) => ($c['id'] ?? '') !== $catId)
        );

        if (count($menu['categories']) === $original) {
            return false;
        }

        $this->persist($tenantId, $menu);
        return true;
    }

    // ─── Item mutations ──────────────────────────────────────────

    public function createItem(int $tenantId, string $catId, array $data): ?array
    {
        $menu = $this->getMenu($tenantId);
        $item = [
            'id'          => $this->generateId('item'),
            'nombre'      => $data['nombre'],
            'precio'      => (float) ($data['precio'] ?? 0),
            'descripcion' => $data['descripcion'] ?? null,
            'image_path'  => $data['image_path'] ?? null,
            'badge'       => $data['badge'] ?? null,
            'is_featured' => (bool) ($data['is_featured'] ?? false),
            'activo'      => true,
        ];

        foreach ($menu['categories'] as &$cat) {
            if (($cat['id'] ?? '') !== $catId) {
                continue;
            }
            $cat['items'][] = $item;
            $this->persist($tenantId, $menu);
            return $item;
        }
        unset($cat);

        return null;
    }

    public function updateItem(int $tenantId, string $catId, string $itemId, array $data): ?array
    {
        $menu = $this->getMenu($tenantId);
        $updated = null;

        foreach ($menu['categories'] as &$cat) {
            if (($cat['id'] ?? '') !== $catId) {
                continue;
            }
            foreach ($cat['items'] as &$item) {
                if (($item['id'] ?? '') !== $itemId) {
                    continue;
                }
                if (isset($data['nombre'])) {
                    $item['nombre'] = $data['nombre'];
                }
                if (isset($data['precio'])) {
                    $item['precio'] = (float) $data['precio'];
                }
                if (array_key_exists('descripcion', $data)) {
                    $item['descripcion'] = $data['descripcion'];
                }
                if (array_key_exists('image_path', $data)) {
                    $item['image_path'] = $data['image_path'];
                }
                if (array_key_exists('badge', $data)) {
                    $item['badge'] = $data['badge'];
                }
                if (array_key_exists('is_featured', $data)) {
                    $item['is_featured'] = (bool) $data['is_featured'];
                }
                if (isset($data['activo'])) {
                    $item['activo'] = (bool) $data['activo'];
                }
                $updated = $item;
                break 2;
            }
            unset($item);
        }
        unset($cat);

        if ($updated) {
            $this->persist($tenantId, $menu);
        }

        return $updated;
    }

    public function deleteItem(int $tenantId, string $catId, string $itemId): bool
    {
        $menu = $this->getMenu($tenantId);

        foreach ($menu['categories'] as &$cat) {
            if (($cat['id'] ?? '') !== $catId) {
                continue;
            }
            $original = count($cat['items']);
            $cat['items'] = array_values(
                array_filter($cat['items'], fn(array $i) => ($i['id'] ?? '') !== $itemId)
            );

            if (count($cat['items']) < $original) {
                $this->persist($tenantId, $menu);
                return true;
            }

            return false;
        }
        unset($cat);

        return false;
    }

    // ─── Aggregates ──────────────────────────────────────────────

    public function countItems(int $tenantId): int
    {
        $total = 0;
        foreach ($this->getCategories($tenantId) as $cat) {
            $total += count($cat['items'] ?? []);
        }
        return $total;
    }

    public function rebuild(int $tenantId): array
    {
        $menu = $this->getMenu($tenantId);
        $this->persist($tenantId, $menu);
        return $menu;
    }

    // ─── Schema normalization ────────────────────────────────────

    private function normalizeCategory(array $cat): array
    {
        $items = array_map([$this, 'normalizeItem'], $cat['items'] ?? []);

        // Featured items first, then preserve original order
        usort($items, static fn(array $a, array $b): int =>
            (int) ($b['is_featured'] ?? false) <=> (int) ($a['is_featured'] ?? false)
        );

        return [
            'id'     => isset($cat['id']) ? (string) $cat['id'] : null,
            'nombre' => $cat['nombre'] ?? $cat['name'] ?? '',
            'foto'   => $cat['foto'] ?? null,
            'activo' => $cat['activo'] ?? $cat['active'] ?? true,
            'items'  => $items,
        ];
    }

    private function normalizeItem(array $item): array
    {
        return [
            'id'          => isset($item['id']) ? (string) $item['id'] : null,
            'nombre'      => $item['nombre'] ?? $item['name'] ?? '',
            'precio'      => (float) ($item['precio'] ?? $item['price'] ?? 0),
            'descripcion' => $item['descripcion'] ?? $item['description'] ?? null,
            'image_path'  => $item['image_path'] ?? null,
            'badge'       => $item['badge'] ?? null,
            'is_featured' => (bool) ($item['is_featured'] ?? false),
            'activo'      => $item['activo'] ?? $item['active'] ?? true,
        ];
    }

    // ─── Internals ───────────────────────────────────────────────

    private function menuPath(int $tenantId): string
    {
        return "tenants/{$tenantId}/menu/menu.json";
    }

    private function persist(int $tenantId, array $menu): void
    {
        Storage::disk(self::DISK)->put(
            $this->menuPath($tenantId),
            json_encode($menu, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

    private function generateId(string $prefix): string
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $random = '';
        for ($i = 0; $i < 4; $i++) {
            $random .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $prefix . '-' . $random;
    }
}
