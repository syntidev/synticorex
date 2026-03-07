<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->string('blueprint', 20)->default('studio')->after('slug');
        });

        DB::table('plans')->whereIn('slug', ['oportunidad', 'crecimiento', 'vision'])
            ->update(['blueprint' => 'studio']);
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('blueprint');
        });
    }
};
