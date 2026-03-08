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
        Schema::table('plans', function (Blueprint $table) {
            $table->unsignedSmallInteger('products_limit')
                  ->nullable()->change();
            $table->unsignedSmallInteger('images_limit')
                  ->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->unsignedSmallInteger('products_limit')
                  ->nullable(false)->default(6)->change();
            $table->unsignedTinyInteger('images_limit')
                  ->nullable(false)->default(15)->change();
        });
    }
};
