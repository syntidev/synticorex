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
            $table->text('about_text')->nullable()->after('hero_layout');
        });
    }

    public function down(): void
    {
        Schema::table('tenant_customization', function (Blueprint $table) {
            $table->dropColumn('about_text');
        });
    }
};
