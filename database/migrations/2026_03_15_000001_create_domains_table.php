<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained()->nullOnDelete();
            $table->string('domain', 255)->unique();
            $table->string('tld', 20);
            $table->enum('type', ['platform', 'addon', 'external']);
            $table->enum('managed_by', ['platform', 'client']);
            $table->string('registrar', 100)->nullable();
            $table->string('registrar_account', 100)->nullable();
            $table->string('registrar_login', 255)->nullable();
            $table->text('auth_code')->nullable();
            $table->date('registered_at')->nullable();
            $table->date('expires_at')->nullable();
            $table->date('last_renewed_at')->nullable();
            $table->boolean('auto_renew')->default(false);
            $table->boolean('transfer_lock')->default(true);
            $table->decimal('cost_price', 8, 2)->nullable();
            $table->decimal('sale_price', 8, 2)->nullable();
            $table->enum('billing_cycle', ['monthly', 'annual'])->nullable();
            $table->enum('dns_status', ['ok', 'failing', 'pending', 'unknown'])->default('unknown');
            $table->timestamp('dns_verified_at')->nullable();
            $table->string('dns_expected_ip', 45)->nullable();
            $table->json('nameservers')->nullable();
            $table->enum('status', ['active', 'expiring_soon', 'expired', 'grace_period', 'redemption', 'cancelled', 'transferred'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('tenant_id');
            $table->index(['type', 'status']);
            $table->index('expires_at');
            $table->index('dns_status');
            $table->index(['managed_by', 'expires_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};
