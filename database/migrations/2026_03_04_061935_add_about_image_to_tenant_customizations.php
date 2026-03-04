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
            $table->string('about_image_filename')->nullable()->after('hero_main_filename');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenant_customization', function (Blueprint $table) {
            $table->dropColumn('about_image_filename');
        });
    }
};
