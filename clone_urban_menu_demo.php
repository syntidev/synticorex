<?php
declare(strict_types=1);

/**
 * SCRIPT: Clonar Urban Menu Demo (Tenant 18)
 * 
 * Uso: php artisan tinker --execute="require 'clone_urban_menu_demo.php';"
 * 
 * Configura estos parámetros antes de ejecutar:
 */

// ============================================================
// ⚙️ CONFIGURACIÓN — CAMBIAR ESTOS VALORES PARA NUEVO DEMO
// ============================================================

$config = [
    'business_name'    => 'Urban Menu Clone',  // ← CAMBIAR
    'slogan'           => 'Lo criollo, lo urbano, lo delicioso',
    'subdomain'        => 'urbanmenu2',         // ← CAMBIAR (único)
    'city'             => 'Caracas',
    'country'          => 'VE',
    'whatsapp_sales'   => '+584141234567',      // ← CAMBIAR (opcional)
    'address'          => 'Av. Las Mercedes, Caracas',
    'segment'          => 'Comida Rápida Venezolana',
    
    'source_tenant_id' => 18, // Urban Menu original
    'plan_id'          => 13,  // food-crecimiento (100 productos)
    'blueprint'        => 'food',
    'user_id'          => 1,   // Owner
];

// ============================================================
// INICIO DEL SCRIPT
// ============================================================

echo "🔄 Iniciando clonación de Urban Menu Demo...\n";
echo "   Fuente: Tenant 18 (Urban Menu)\n";
echo "   Nuevo: {$config['business_name']}\n\n";

try {
    // 1️⃣  Crear nuevo tenant
    echo "1️⃣ Creando tenant base...\n";
    
    $newTenant = DB::table('tenants')->insertGetId([
        'user_id'           => $config['user_id'],
        'business_name'     => $config['business_name'],
        'plan_id'           => $config['plan_id'],
        'subdomain'         => $config['subdomain'],
        'business_segment'  => $config['segment'],
        'slogan'            => $config['slogan'],
        'whatsapp_sales'    => $config['whatsapp_sales'],
        'whatsapp_active'   => 'sales',
        'is_demo'           => 1,
        'demo_product'      => 'food',
        'address'           => $config['address'],
        'city'              => $config['city'],
        'country'           => $config['country'],
        'is_open'           => 1,
        'currency_display'  => 'both',
        'color_palette_id'  => 1,
        'status'            => 'active',
        'submission_status' => 'accepted',
        'created_at'        => now(),
        'updated_at'        => now(),
        'settings'          => json_encode([
            'engine_settings' => [
                'currency' => [
                    'display' => [
                        'symbols' => [
                            'bolivares'  => 'Bs.',
                            'reference'  => 'REF',
                        ],
                        'show_euro'        => false,
                        'has_toggle'       => true,
                        'hide_price'       => false,
                        'show_bolivares'   => true,
                        'show_reference'   => true,
                        'saved_display_mode' => 'both_toggle',
                    ],
                    'auto_update' => true,
                ],
                'features' => [
                    'show_hours_indicator' => true,
                ],
                'template' => 'food',
            ],
        ]),
    ]);
    
    echo "   ✓ Tenant creado: ID {$newTenant}\n\n";
    
    // 2️⃣ Clonar productos (28 del tenant 18)
    echo "2️⃣ Clonando 28 productos...\n";
    
    $sourceProduts = DB::table('products')
        ->where('tenant_id', $config['source_tenant_id'])
        ->get();
    
    $prodCount = 0;
    foreach ($sourceProduts as $p) {
        DB::table('products')->insert([
            'tenant_id'          => $newTenant,
            'name'               => $p->name,
            'price_usd'          => $p->price_usd,
            'price_bs'           => $p->price_bs,
            'compare_price_usd'  => $p->compare_price_usd,
            'description'        => $p->description,
            'image_url'          => $p->image_url,
            'image_filename'     => $p->image_filename,
            'category_name'      => $p->category_name,
            'subcategory_name'   => $p->subcategory_name,
            'is_active'          => $p->is_active,
            'is_featured'        => $p->is_featured,
            'badge'              => $p->badge,
            'position'           => $p->position,
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);
        $prodCount++;
    }
    
    echo "   ✓ {$prodCount} productos clonados\n\n";
    
    // 3️⃣ Clonar customización visual
    echo "3️⃣ Clonando customización...\n";
    
    $sourceCustm = DB::table('tenant_customization')
        ->where('tenant_id', $config['source_tenant_id'])
        ->first();
    
    if ($sourceCustm) {
        DB::table('tenant_customization')->insert([
            'tenant_id'            => $newTenant,
            'logo_filename'        => $sourceCustm->logo_filename,
            'hero_main_filename'   => $sourceCustm->hero_main_filename,
            'hero_secondary_filename' => $sourceCustm->hero_secondary_filename,
            'hero_tertiary_filename'  => $sourceCustm->hero_tertiary_filename,
            'hero_image_4_filename'   => $sourceCustm->hero_image_4_filename,
            'hero_image_5_filename'   => $sourceCustm->hero_image_5_filename,
            'hero_layout'          => $sourceCustm->hero_layout,
            'social_networks'      => $sourceCustm->social_networks,
            'payment_methods'      => $sourceCustm->payment_methods,
            'theme_slug'           => $sourceCustm->theme_slug,
            'created_at'           => now(),
            'updated_at'           => now(),
        ]);
        
        echo "   ✓ Customización clonada (logo, hero, theme: {$sourceCustm->theme_slug})\n\n";
    }
    
    // 4️⃣ Crear directorios para archivos
    echo "4️⃣ Preparando directorios...\n";
    
    $tenantStoragePath = storage_path("app/tenants/{$newTenant}");
    if (!is_dir($tenantStoragePath)) {
        mkdir($tenantStoragePath, 0755, true);
        echo "   ✓ Directorio creado: {$tenantStoragePath}\n";
    }
    
    // Copiar logo si existe
    if ($sourceCustm?->logo_filename) {
        $sourceLogoPath = storage_path("app/tenants/{$config['source_tenant_id']}/{$sourceCustm->logo_filename}");
        if (file_exists($sourceLogoPath)) {
            $destLogoPath = storage_path("app/tenants/{$newTenant}/{$sourceCustm->logo_filename}");
            copy($sourceLogoPath, $destLogoPath);
            echo "   ✓ Logo copiado: {$sourceCustm->logo_filename}\n";
        }
    }
    
    echo "\n";
    
    // 5️⃣ Resumen final
    echo "✅ CLONACIÓN COMPLETADA!\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "📋 Nuevo Tenant Demo:\n";
    echo "   ID: {$newTenant}\n";
    echo "   Nombre: {$config['business_name']}\n";
    echo "   Subdomain: {$config['subdomain']}\n";
    echo "   Plan: food-crecimiento (100 productos)\n";
    echo "   Productos: {$prodCount}\n";
    echo "   Estado: active\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "\n🌐 Acceso:\n";
    echo "   http://{$config['subdomain']}.synticorex.local\n";
    echo "   o whatsapp: {$config['whatsapp_sales']}\n";
    
} catch (Throwable $e) {
    echo "❌ ERROR: {$e->getMessage()}\n";
    echo "Stack: {$e->getTraceAsString()}\n";
}
