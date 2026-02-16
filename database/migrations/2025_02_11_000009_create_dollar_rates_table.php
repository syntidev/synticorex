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
        Schema::create('dollar_rates', function (Blueprint $table) {
            $table->id();
            
            // Tasa
            $table->decimal('rate', 10, 2); // Ej: 36.50
            $table->string('source', 50)->default('BCV'); // 'BCV', 'manual'
            
            // Validez
            $table->timestamp('effective_from');
            $table->timestamp('effective_until')->nullable();
            $table->boolean('is_active')->default(true);
            
            // Timestamp
            $table->timestamp('created_at')->useCurrent();
            
            // Índices
            $table->index(['is_active', 'effective_from']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dollar_rates');
    }
};
