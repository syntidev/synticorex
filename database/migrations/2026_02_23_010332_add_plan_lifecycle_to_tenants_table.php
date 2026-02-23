<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add plan lifecycle columns to tenants.
     *
     * Status flow:
     *   active  → frozen (subscription_ends_at passed)
     *   frozen  → archived (30-day grace period after subscription_ends_at expired)
     *
     * No data is deleted automatically at any stage.
     */
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            // When the current annual plan period was activated/renewed.
            // subscription_ends_at = plan_activated_at + 365 days
            $table->timestamp('plan_activated_at')
                ->nullable()
                ->after('subscription_ends_at')
                ->comment('When the current annual plan period started');

            // Index on (status, subscription_ends_at) for efficient expiry queries
            $table->index(['status', 'subscription_ends_at'], 'idx_tenant_status_expiry');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropIndex('idx_tenant_status_expiry');
            $table->dropColumn('plan_activated_at');
        });
    }
};
