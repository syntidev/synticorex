<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('plans')->where('slug', 'cat-crecimiento')
            ->update(['analytics_level' => 'medium']);

        DB::table('plans')->where('slug', 'cat-vision')
            ->update(['products_limit' => 250, 'analytics_level' => 'advanced']);

        DB::table('plans')->where('slug', 'food-crecimiento')
            ->update(['analytics_level' => 'medium']);

        DB::table('plans')->where('slug', 'studio-crecimiento')
            ->update(['show_faq' => 1]);

        DB::table('plans')->where('slug', 'studio-vision')
            ->update(['products_limit' => 200]);
    }

    public function down(): void
    {
        DB::table('plans')->where('slug', 'cat-crecimiento')
            ->update(['analytics_level' => 'basic']);

        DB::table('plans')->where('slug', 'cat-vision')
            ->update(['products_limit' => null, 'analytics_level' => 'medium']);

        DB::table('plans')->where('slug', 'food-crecimiento')
            ->update(['analytics_level' => 'basic']);

        DB::table('plans')->where('slug', 'studio-crecimiento')
            ->update(['show_faq' => 0]);

        DB::table('plans')->where('slug', 'studio-vision')
            ->update(['products_limit' => 9999]);
    }
};
