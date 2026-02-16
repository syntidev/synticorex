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
        Schema::create('analytics_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->cascadeOnDelete();
            
            // Tipo de evento
            $table->string('event_type', 50); // 'page_view', 'whatsapp_click', 'product_click', 'service_click'
            
            // Referencia (si aplica)
            $table->string('reference_type', 50)->nullable(); // 'product', 'service', null
            $table->unsignedBigInteger('reference_id')->nullable();
            
            // Contexto
            $table->string('user_ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('referer')->nullable();
            
            // Timestamp del evento
            $table->date('event_date');
            $table->unsignedTinyInteger('event_hour'); // 0-23
            $table->timestamp('created_at')->useCurrent();
            
            // Índices
            $table->index(['tenant_id', 'event_date']);
            $table->index(['tenant_id', 'event_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics_events');
    }
};
