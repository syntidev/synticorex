<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Canal de pago SYNTIweb (cómo pagó el cliente A SYNTIweb)
            $table->string('payment_channel', 30)->nullable()->after('payment_method');
            // pago_movil, paypal, zinli

            // Comprobante de pago subido por el cliente
            $table->string('receipt_path')->nullable()->after('pdf_filename');

            // Admin review
            $table->text('admin_notes')->nullable()->after('status');
            $table->timestamp('reviewed_at')->nullable()->after('admin_notes');
            $table->foreignId('reviewed_by')->nullable()->after('reviewed_at')
                ->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn([
                'payment_channel',
                'receipt_path',
                'admin_notes',
                'reviewed_at',
                'reviewed_by',
            ]);
        });
    }
};
