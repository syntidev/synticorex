<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('plans')->where('slug', 'food-basico')->update(['name' => 'Oportunidad']);
        DB::table('plans')->where('slug', 'food-semestral')->update(['name' => 'Crecimiento']);
        DB::table('plans')->where('slug', 'food-anual')->update(['name' => 'Visión']);

        DB::table('plans')->where('slug', 'cat-basico')->update(['name' => 'Oportunidad']);
        DB::table('plans')->where('slug', 'cat-semestral')->update(['name' => 'Crecimiento']);
        DB::table('plans')->where('slug', 'cat-anual')->update(['name' => 'Visión']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('plans')->where('slug', 'food-basico')->update(['name' => 'Básico']);
        DB::table('plans')->where('slug', 'food-semestral')->update(['name' => 'Semestral']);
        DB::table('plans')->where('slug', 'food-anual')->update(['name' => 'Anual']);

        DB::table('plans')->where('slug', 'cat-basico')->update(['name' => 'Básico']);
        DB::table('plans')->where('slug', 'cat-semestral')->update(['name' => 'Semestral']);
        DB::table('plans')->where('slug', 'cat-anual')->update(['name' => 'Anual']);
    }
};
