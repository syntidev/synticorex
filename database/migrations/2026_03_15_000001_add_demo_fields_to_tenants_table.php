<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->boolean('is_demo')->default(false)->after('whatsapp_active');
            $table->enum('demo_product', ['studio', 'food', 'cat'])->nullable()->after('is_demo');
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['demo_product', 'is_demo']);
        });
    }
};
