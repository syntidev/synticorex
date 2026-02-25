<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Make all color / style fields nullable so FlyonUI slug-based themes
     * can be stored without specifying explicit hex values.
     */
    public function up(): void
    {
        Schema::table('color_palettes', function (Blueprint $table) {
            // Core color fields (originally NOT NULL)
            $table->string('primary_color', 7)->nullable()->default(null)->change();
            $table->string('secondary_color', 7)->nullable()->default(null)->change();
            $table->string('background_color', 7)->nullable()->default(null)->change();
            $table->string('text_color', 7)->nullable()->default(null)->change();

            // Extended fields added by 2026_02_18 migration (NOT NULL, no default)
            $table->string('text_muted', 20)->nullable()->default(null)->change();
            $table->string('background_alt', 20)->nullable()->default(null)->change();
            $table->string('button_bg', 20)->nullable()->default(null)->change();
            $table->string('button_text', 20)->nullable()->default(null)->change();
            $table->string('button_hover_bg', 20)->nullable()->default(null)->change();
            $table->string('link_color', 20)->nullable()->default(null)->change();
            $table->string('link_hover', 20)->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('color_palettes', function (Blueprint $table) {
            $table->string('primary_color', 7)->nullable(false)->default('')->change();
            $table->string('secondary_color', 7)->nullable(false)->default('')->change();
            $table->string('background_color', 7)->nullable(false)->default('#FFFFFF')->change();
            $table->string('text_color', 7)->nullable(false)->default('#000000')->change();
            $table->string('text_muted', 20)->nullable(false)->default('')->change();
            $table->string('background_alt', 20)->nullable(false)->default('')->change();
            $table->string('button_bg', 20)->nullable(false)->default('')->change();
            $table->string('button_text', 20)->nullable(false)->default('')->change();
            $table->string('button_hover_bg', 20)->nullable(false)->default('')->change();
            $table->string('link_color', 20)->nullable(false)->default('')->change();
            $table->string('link_hover', 20)->nullable(false)->default('')->change();
        });
    }
};
