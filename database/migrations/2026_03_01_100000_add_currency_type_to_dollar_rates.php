<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add currency_type column to dollar_rates table.
     * Existing rows are USD — default ensures backward compatibility.
     */
    public function up(): void
    {
        Schema::table('dollar_rates', function (Blueprint $table) {
            $table->string('currency_type', 3)
                ->default('USD')
                ->after('source')
                ->comment('ISO-4217: USD, EUR');
        });

        // Stamp all existing rows as USD
        DB::table('dollar_rates')->whereNull('currency_type')->update(['currency_type' => 'USD']);
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::table('dollar_rates', function (Blueprint $table) {
            $table->dropColumn('currency_type');
        });
    }
};
