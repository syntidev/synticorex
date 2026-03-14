<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('category_name', 80)->nullable()->after('badge');
            $table->string('subcategory_name', 80)->nullable()->after('category_name');

            $table->index(['tenant_id', 'category_name'], 'products_tenant_category_idx');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_tenant_category_idx');
            $table->dropColumn(['category_name', 'subcategory_name']);
        });
    }
};
