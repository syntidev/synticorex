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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->cascadeOnDelete();
            
            // Info de factura
            $table->string('invoice_number', 50)->unique(); // SYNTI-2026-00001
            $table->decimal('amount_usd', 10, 2);
            $table->string('currency', 10)->default('USD');
            
            // Pago
            $table->string('payment_method', 50)->nullable(); // 'zelle', 'transferencia', 'efectivo'
            $table->string('payment_reference', 100)->nullable();
            $table->timestamp('payment_date')->nullable();
            
            // PDF
            $table->string('pdf_filename')->nullable();
            
            // Status
            $table->string('status', 20)->default('pending'); // 'pending', 'paid', 'cancelled'
            
            // Periodo cubierto
            $table->date('period_start');
            $table->date('period_end');
            
            // Timestamps
            $table->timestamps();
            
            // Índices
            $table->index(['tenant_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
