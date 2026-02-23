<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Update plan pricing and limits to the new commercial model.
     *
     * OPORTUNIDAD:  $49 → $99   |  6 prods (same)  |  3 srvs (same)
     * CRECIMIENTO:  $89 → $149  |  18 → 12 prods   |  6 srvs (same)
     * VISIÓN:       $159 → $199 |  40 → 18 prods   |  15 → 9 srvs
     */
    public function up(): void
    {
        // OPORTUNIDAD
        DB::table('plans')->where('slug', 'oportunidad')->update([
            'price_usd'      => 99.00,
            'products_limit' => 6,
            'services_limit' => 3,
            'images_limit'   => 6,   // 1 foto por producto
        ]);

        // CRECIMIENTO
        DB::table('plans')->where('slug', 'crecimiento')->update([
            'price_usd'      => 149.00,
            'products_limit' => 12,
            'services_limit' => 6,
            'images_limit'   => 12,  // 1 foto por producto
        ]);

        // VISIÓN
        DB::table('plans')->where('slug', 'vision')->update([
            'price_usd'      => 199.00,
            'products_limit' => 18,
            'services_limit' => 9,
            'images_limit'   => 54,  // slider 3 fotos × 18 productos
        ]);
    }

    /**
     * Revert to previous pricing.
     */
    public function down(): void
    {
        DB::table('plans')->where('slug', 'oportunidad')->update([
            'price_usd' => 49.00, 'products_limit' => 6, 'services_limit' => 3, 'images_limit' => 8,
        ]);
        DB::table('plans')->where('slug', 'crecimiento')->update([
            'price_usd' => 89.00, 'products_limit' => 18, 'services_limit' => 6, 'images_limit' => 26,
        ]);
        DB::table('plans')->where('slug', 'vision')->update([
            'price_usd' => 159.00, 'products_limit' => 40, 'services_limit' => 15, 'images_limit' => 57,
        ]);
    }
};
