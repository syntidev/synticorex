<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DollarRatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('dollar_rates')->insert([
            [
                'rate' => 36.50,
                'source' => 'BCV',
                'effective_from' => Carbon::now()->startOfDay(),
                'effective_until' => null,
                'is_active' => true,
                'created_at' => now(),
            ],
        ]);
    }
}
