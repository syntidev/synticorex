<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Support\Facades\Storage;

class TenantBootstrapFood
{
    public static function bootstrap(Tenant $tenant): void
    {
        $base = "tenants/{$tenant->id}/menu";

        Storage::disk('local')->makeDirectory($base);
        Storage::disk('local')->makeDirectory("{$base}/fotos");

        if (!Storage::disk('local')->exists("{$base}/menu.json")) {
            Storage::disk('local')->put("{$base}/menu.json", json_encode([
                'tenant_id'  => $tenant->id,
                'blueprint'  => 'food',
                'categories' => [],
                'created_at' => now()->toISOString(),
                'updated_at' => now()->toISOString(),
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
    }

    public static function addInitialCategory(Tenant $tenant, string $categoryName, array $items): void
    {
        $path = "tenants/{$tenant->id}/menu/menu.json";
        $menu = json_decode(Storage::disk('local')->get($path), true);

        $filteredItems = array_values(array_filter(
            array_map(fn($item) => [
                'name'   => trim($item['name'] ?? ''),
                'price'  => (float) ($item['price'] ?? 0),
                'active' => true,
            ], $items),
            fn($item) => $item['name'] !== ''
        ));

        $menu['categories'][] = [
            'id'     => 1,
            'name'   => $categoryName,
            'active' => true,
            'items'  => $filteredItems,
        ];
        $menu['updated_at'] = now()->toISOString();

        Storage::disk('local')->put($path, json_encode($menu, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
