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
        Schema::table('tenants', function (Blueprint $table) {
            // Relación con users (añadir después de id)
            $table->foreignId('user_id')
                ->after('id')
                ->constrained('users')
                ->cascadeOnDelete();
            
            // Identificación de dominios (después de plan_id)
            $table->string('subdomain', 100)->nullable()->unique()->after('plan_id');
            $table->string('base_domain', 100)->nullable()->after('subdomain');
            $table->string('custom_domain')->nullable()->unique()->after('base_domain');
            $table->boolean('domain_verified')->default(false)->after('custom_domain');
            
            // Renombrar 'nombre' a 'business_name' (se hace fuera de esta función)
            // Info básica del negocio
            $table->string('business_segment', 50)->nullable()->after('domain_verified');
            $table->text('slogan')->nullable()->after('business_segment');
            $table->text('description')->nullable()->after('slogan');
            
            // Contacto
            $table->string('phone', 20)->nullable()->after('description');
            $table->string('whatsapp_sales', 20)->nullable()->after('phone');
            $table->string('whatsapp_support', 20)->nullable()->after('whatsapp_sales');
            $table->string('email')->nullable()->after('whatsapp_support');
            $table->text('address')->nullable()->after('email');
            $table->string('city', 100)->nullable()->after('address');
            $table->string('country', 100)->default('Venezuela')->after('city');
            
            // Horarios
            $table->json('business_hours')->nullable()->after('country');
            $table->boolean('is_open')->default(true)->after('business_hours');
            
            // Configuración
            $table->string('edit_pin')->after('is_open'); // Hash del PIN de 4 dígitos
            $table->string('currency_display', 10)->default('both')->after('edit_pin'); // 'usd', 'bs', 'both'
            $table->unsignedTinyInteger('color_palette_id')->default(1)->after('currency_display');
            
            // SEO
            $table->string('meta_title')->nullable()->after('color_palette_id');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->text('meta_keywords')->nullable()->after('meta_description');
            
            // Status (renombrar 'activo' a 'status')
            $table->string('status', 20)->default('active')->after('meta_keywords'); // 'active', 'suspended', 'cancelled'
            $table->timestamp('trial_ends_at')->nullable()->after('status');
            $table->timestamp('subscription_ends_at')->nullable()->after('trial_ends_at');
            
            // Índices adicionales
            $table->index(['subdomain', 'base_domain']);
            $table->index('status');
        });
        
        // Renombrar columnas existentes
        Schema::table('tenants', function (Blueprint $table) {
            $table->renameColumn('nombre', 'business_name');
        });
        
        // Eliminar columnas que no están en el schema
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['slug', 'visits_count', 'template', 'activo', 'dominio']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restaurar columnas eliminadas
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('slug', 64)->unique();
            $table->unsignedInteger('visits_count')->default(0);
            $table->string('template', 64)->nullable();
            $table->boolean('activo')->default(true);
            $table->string('dominio', 255)->nullable();
        });
        
        // Restaurar nombre de columna
        Schema::table('tenants', function (Blueprint $table) {
            $table->renameColumn('business_name', 'nombre');
        });
        
        // Eliminar columnas añadidas
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex(['subdomain', 'base_domain']);
            $table->dropIndex(['status']);
            
            $table->dropColumn([
                'user_id',
                'subdomain',
                'base_domain',
                'custom_domain',
                'domain_verified',
                'business_segment',
                'slogan',
                'description',
                'phone',
                'whatsapp_sales',
                'whatsapp_support',
                'email',
                'address',
                'city',
                'country',
                'business_hours',
                'is_open',
                'edit_pin',
                'currency_display',
                'color_palette_id',
                'meta_title',
                'meta_description',
                'meta_keywords',
                'status',
                'trial_ends_at',
                'subscription_ends_at',
            ]);
        });
    }
};
