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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->cascadeOnDelete();
            
            // Info del servicio
            $table->string('name');
            $table->text('description')->nullable();
            
            // Visual
            $table->string('icon_name', 50)->nullable(); // 'scissors', 'wrench', 'burger', etc.
            $table->string('image_filename')->nullable(); // service_01.webp (Plan CRECIMIENTO+)
            $table->string('overlay_text', 100)->nullable(); // Texto sobre imagen (Plan VISIÓN)
            
            // Link
            $table->string('cta_text', 50)->default('Más información');
            $table->text('cta_link')->nullable(); // URL personalizada o default WhatsApp
            
            // Organización
            $table->unsignedTinyInteger('position')->default(0);
            $table->boolean('is_active')->default(true);
            
            // Timestamps
            $table->timestamps();
            
            // Índices
            $table->index(['tenant_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
