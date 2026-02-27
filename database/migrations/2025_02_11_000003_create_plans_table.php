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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 32)->unique()->index();
            $table->string('name', 64);
            $table->decimal('price_usd', 10, 2)->unsigned();
            
            // Límites de productos y servicios
            $table->unsignedSmallInteger('products_limit')->default(6);
            $table->unsignedSmallInteger('services_limit')->default(3);
            $table->unsignedTinyInteger('images_limit')->default(15);
            $table->unsignedTinyInteger('color_palettes')->default(5);
            $table->unsignedTinyInteger('social_networks_limit')->nullable(); // ✅ NULLABLE
            
            // Features booleanas
            $table->boolean('show_dollar_rate')->default(false);
            $table->boolean('show_header_top')->default(false);
            $table->boolean('show_about_section')->default(false);
            $table->boolean('show_payment_methods')->default(false);
            $table->boolean('show_faq')->default(false);
            $table->boolean('show_cta_special')->default(false);
            
            // Niveles de analytics y SEO
            $table->enum('analytics_level', ['basic', 'medium', 'advanced'])->default('basic');
            $table->enum('seo_level', ['basic', 'medium', 'advanced'])->default('basic');
            
            // Límites de WhatsApp
            $table->unsignedTinyInteger('whatsapp_numbers')->default(1);
            $table->boolean('whatsapp_hour_filter')->default(false);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};