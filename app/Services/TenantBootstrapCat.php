<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Support\Facades\Storage;

class TenantBootstrapCat
{
    public static function bootstrap(Tenant $tenant): void
    {
        $base = "tenants/{$tenant->id}/products";

        Storage::disk('local')->makeDirectory($base);

        if (!Storage::disk('local')->exists("{$base}/catalog.json")) {
            Storage::disk('local')->put("{$base}/catalog.json", json_encode([
                'tenant_id'  => $tenant->id,
                'blueprint'  => 'cat',
                'products'   => [],
                'created_at' => now()->toISOString(),
                'updated_at' => now()->toISOString(),
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
    }

    public static function addInitialProduct(Tenant $tenant, string $name, float $price, string $description = ''): void
    {
        $path = "tenants/{$tenant->id}/products/catalog.json";
        $catalog = json_decode(Storage::disk('local')->get($path), true);

        $catalog['products'][] = [
            'id'          => 'p001',
            'title'       => $name,
            'description' => $description,
            'price'       => $price,
            'currency'    => 'USD',
            'badges'      => [],
            'images'      => [],
            'active'      => true,
            'variants'    => [
                'type'    => 'none',
                'sizes'   => [],
                'colors'  => [],
                'options' => [],
            ],
        ];
        $catalog['updated_at'] = now()->toISOString();

        Storage::disk('local')->put($path, json_encode($catalog, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
