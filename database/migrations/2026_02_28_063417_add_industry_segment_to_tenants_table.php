<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->enum('industry_segment', [
                'FOOD_BEVERAGE',
                'RETAIL',
                'HEALTH_WELLNESS',
                'PROFESSIONAL_SERVICES',
                'ON_DEMAND',
            ])->nullable()->after('status');

            $table->index('industry_segment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropIndex(['industry_segment']);
            $table->dropColumn('industry_segment');
        });
    }
};
