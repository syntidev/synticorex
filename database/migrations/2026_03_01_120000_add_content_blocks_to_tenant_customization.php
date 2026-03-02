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
        Schema::table('tenant_customization', function (Blueprint $table) {
            // content_blocks: almacena bloques de contenido libre por sección
            // Ejemplo: {"hero": {"title": "...", "subtitle": "..."}, "about": {"text": "..."}}
            $table->json('content_blocks')->nullable()->after('visual_effects');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenant_customization', function (Blueprint $table) {
            $table->dropColumn('content_blocks');
        });
    }
};
