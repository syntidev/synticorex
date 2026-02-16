<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Llamar a los seeders de SYNTIweb
        $this->call([
            PlansSeeder::class,
            ColorPalettesSeeder::class,
            DollarRatesSeeder::class,
        ]);
    }
}