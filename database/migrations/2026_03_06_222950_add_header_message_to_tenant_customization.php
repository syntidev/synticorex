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
            $table->string('header_message', 255)->nullable()->after('about_image_filename');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenant_customization', function (Blueprint $table) {
            $table->dropColumn('header_message');
        });
    }
};
