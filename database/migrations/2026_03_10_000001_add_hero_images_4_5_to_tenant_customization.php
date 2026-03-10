<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenant_customization', function (Blueprint $table) {
            $table->string('hero_image_4_filename')->nullable()->after('hero_tertiary_filename');
            $table->string('hero_image_5_filename')->nullable()->after('hero_image_4_filename');
        });
    }

    public function down(): void
    {
        Schema::table('tenant_customization', function (Blueprint $table) {
            $table->dropColumn(['hero_image_4_filename', 'hero_image_5_filename']);
        });
    }
};
