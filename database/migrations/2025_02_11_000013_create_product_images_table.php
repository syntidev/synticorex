<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabla de imágenes adicionales por producto.
     * Exclusivo Plan 3 (VISIÓN): hasta 2 imágenes extra por producto.
     * La imagen principal sigue en products.image_filename.
     * Total máximo con imagen principal: 3 fotos por producto.
     */
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            $table->string('image_filename');                    // e.g. product_01_gallery_1.webp
            $table->unsignedTinyInteger('position')->default(0); // 0 = segunda foto, 1 = tercera foto
            $table->timestamps();

            // Índice compuesto para queries ordenadas
            $table->index(['product_id', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
