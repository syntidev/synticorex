<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_category_id')->nullable()
                  ->constrained()->nullOnDelete();
            $table->string('slug', 255)->unique();
            $table->string('title', 255);
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->string('image_url', 500)->nullable();
            $table->string('author', 100)->default('Equipo SYNTIweb');
            $table->string('avatar_url', 500)->nullable();
            $table->string('read_time', 20)->default('5 min');
            $table->boolean('featured')->default(false);
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->json('tags')->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->date('published_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'published_at']);
            $table->index('featured');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
