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
        Schema::create('ai_docs', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('product', 20)->index(); // shared|studio|food|cat
            $table->text('content');
            $table->string('source_file')->nullable();
            $table->timestamps();
        });

        // FULLTEXT index nativo MySQL — sin librerías externas
        DB::statement('ALTER TABLE ai_docs ADD FULLTEXT INDEX ft_search (title, content)');

        Schema::create('ai_chat_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product', 20)->nullable();
            $table->text('question');
            $table->text('answer');
            $table->tinyInteger('helpful')->nullable(); // 1=útil, 0=no útil
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_chat_logs');
        Schema::dropIfExists('ai_docs');
    }
};
