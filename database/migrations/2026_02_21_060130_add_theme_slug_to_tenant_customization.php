<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Transición de ColorPalette custom a temas oficiales FlyonUI.
     * Agregamos theme_slug para usar los 32 temas oficiales con data-theme.
     */
    public function up(): void
    {
        Schema::table('tenant_customization', function (Blueprint $table) {
            // Tema oficial FlyonUI (32 opciones)
            // light, dark, cupcake, bumblebee, emerald, corporate, synthwave, retro, 
            // cyberpunk, valentine, halloween, garden, forest, aqua, lofi, pastel, 
            // fantasy, wireframe, black, luxury, dracula, cmyk, autumn, business, 
            // acid, lemonade, night, coffee, winter, dim, nord, sunset
            $table->string('theme_slug', 50)->default('light');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenant_customization', function (Blueprint $table) {
            $table->dropColumn('theme_slug');
        });
    }
};
