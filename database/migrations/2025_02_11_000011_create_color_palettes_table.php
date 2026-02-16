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
        Schema::create('color_palettes', function (Blueprint $table) {
            $table->tinyIncrements('id');
            
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            
            // Colores (hex)
            $table->string('primary_color', 7); // #FF0000
            $table->string('secondary_color', 7); // #FFFF00
            $table->string('accent_color', 7)->nullable();
            $table->string('background_color', 7)->default('#FFFFFF');
            $table->string('text_color', 7)->default('#000000');
            
            // Disponibilidad
            $table->unsignedTinyInteger('min_plan_id')->default(1); // Desde qué plan está disponible
            
            // Categoría (para organizar)
            $table->string('category', 50)->nullable(); // 'clasico', 'marca', 'segmento'
            
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('color_palettes');
    }
};
