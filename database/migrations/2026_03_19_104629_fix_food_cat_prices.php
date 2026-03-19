<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('plans')->where('slug', 'food-oportunidad')->update(['price_usd' => 12.00]);
        DB::table('plans')->where('slug', 'food-crecimiento')->update(['price_usd' => 45.00]);
        DB::table('plans')->where('slug', 'cat-oportunidad')->update(['price_usd' => 12.00]);
        DB::table('plans')->where('slug', 'cat-crecimiento')->update(['price_usd' => 45.00]);
    }

    public function down(): void
    {
        DB::table('plans')->where('slug', 'food-oportunidad')->update(['price_usd' => 9.00]);
        DB::table('plans')->where('slug', 'food-crecimiento')->update(['price_usd' => 39.00]);
        DB::table('plans')->where('slug', 'cat-oportunidad')->update(['price_usd' => 9.00]);
        DB::table('plans')->where('slug', 'cat-crecimiento')->update(['price_usd' => 39.00]);
    }
};
