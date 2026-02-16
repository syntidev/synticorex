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
        Schema::create('tenant_customization', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')
                ->unique()
                ->constrained('tenants')
                ->cascadeOnDelete();
            
            // Imágenes principales
            $table->string('logo_filename')->nullable();
            $table->string('hero_filename')->nullable();
            
            // Redes sociales (JSON)
            // Ejemplo: {"instagram": "@joseburguer", "facebook": "JoseBurguerOficial", "tiktok": "@joseburguer"}
            $table->json('social_networks')->nullable();
            
            // Medios de pago (JSON)
            // Ejemplo: {"zelle": true, "cashea": true, "pago_movil": true, "binance": false, "efectivo": true}
            $table->json('payment_methods')->nullable();
            
            // FAQ (JSON - solo Plan VISIÓN)
            // Ejemplo: [{"question": "¿Hacen delivery?", "answer": "Sí, hasta 5km"}, ...]
            $table->json('faq_items')->nullable();
            
            // CTA Especial (Plan VISIÓN)
            $table->string('cta_title')->nullable();
            $table->text('cta_subtitle')->nullable();
            $table->string('cta_button_text', 100)->nullable();
            $table->text('cta_button_link')->nullable();
            
            // Efectos visuales (Plan VISIÓN)
            // Ejemplo: {"hero_parallax": true, "fade_in_sections": true, "hover_animations": true}
            $table->json('visual_effects')->nullable();
            
            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_customization');
    }
};
