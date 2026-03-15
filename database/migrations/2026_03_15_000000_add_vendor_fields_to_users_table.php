<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('vendor_profile', ['standard', 'pro'])->nullable()->after('role');
            $table->tinyInteger('vendor_sales_month')->default(0)->after('vendor_profile');
            $table->decimal('vendor_total_earned', 10, 2)->default(0)->after('vendor_sales_month');
            $table->string('pago_movil_phone', 20)->nullable()->after('vendor_total_earned');
            $table->string('pago_movil_cedula', 15)->nullable()->after('pago_movil_phone');
            $table->string('pago_movil_bank', 50)->nullable()->after('pago_movil_cedula');
            $table->string('referral_code', 20)->unique()->nullable()->after('pago_movil_bank');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'vendor_profile',
                'vendor_sales_month',
                'vendor_total_earned',
                'pago_movil_phone',
                'pago_movil_cedula',
                'pago_movil_bank',
                'referral_code',
            ]);
        });
    }
};
