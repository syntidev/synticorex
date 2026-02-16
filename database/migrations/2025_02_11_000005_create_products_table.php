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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->cascadeOnDelete();
            
            // Info del producto
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price_usd', 10, 2)->nullable();
            $table->decimal('price_bs', 15, 2)->nullable(); // Calculado automáticamente
            
            // Imagen
            $table->string('image_filename')->nullable(); // product_01.webp, product_02.webp, etc.
            
            // Organización
            $table->unsignedTinyInteger('position')->default(0); // Orden de visualización
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false); // Para "destacados"
            
            // Badges
            $table->string('badge', 20)->nullable(); // 'hot', 'new', 'promo', null
            
            // Timestamps
            $table->timestamps();
            
            // Índices
            $table->index(['tenant_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
