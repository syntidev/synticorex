<?php

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
        Schema::table('tenant_customization', function (Blueprint $table) {
            // Renombrar hero actual
            $table->renameColumn('hero_filename', 'hero_main_filename');
            
            // Agregar nuevos campos para múltiples hero images
            $table->string('hero_secondary_filename')->nullable()->after('hero_main_filename');
            $table->string('hero_tertiary_filename')->nullable()->after('hero_secondary_filename');
            
            // Layout del hero (fullscreen, split, gradient, cards)
            $table->enum('hero_layout', ['fullscreen', 'split', 'gradient', 'cards'])
                  ->default('fullscreen')
                  ->after('hero_tertiary_filename');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenant_customization', function (Blueprint $table) {
            // Revertir renombrado
            $table->renameColumn('hero_main_filename', 'hero_filename');
            
            // Eliminar nuevos campos
            $table->dropColumn([
                'hero_secondary_filename', 
                'hero_tertiary_filename', 
                'hero_layout'
            ]);
        });
    }
};
