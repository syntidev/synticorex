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
        Schema::table('color_palettes', function (Blueprint $table) {
            $table->string('description', 255)->nullable()->after('category');
            $table->string('text_muted', 20)->after('text_color');
            $table->string('background_alt', 20)->after('background_color');
            $table->string('button_bg', 20)->after('background_alt');
            $table->string('button_text', 20)->after('button_bg');
            $table->string('button_hover_bg', 20)->after('button_text');
            $table->string('link_color', 20)->after('button_hover_bg');
            $table->string('link_hover', 20)->after('link_color');
            $table->string('font_primary', 100)->nullable()->after('link_hover');
            $table->string('font_secondary', 100)->nullable()->after('font_primary');
            $table->text('segment_tags')->nullable()->after('font_secondary');
            $table->string('emotional_effect', 255)->nullable()->after('segment_tags');
            $table->boolean('is_active')->default(true)->after('emotional_effect');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('color_palettes', function (Blueprint $table) {
            $table->dropColumn([
                'description', 'text_muted', 'background_alt',
                'button_bg', 'button_text', 'button_hover_bg',
                'link_color', 'link_hover', 'font_primary', 'font_secondary',
                'segment_tags', 'emotional_effect', 'is_active'
            ]);
        });
    }
};
