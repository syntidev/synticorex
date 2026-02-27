<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Plan;
use App\Models\Product;

// Bootstrap Laravel (mínimo para test)
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== VALIDACIÓN DE LÍMITES DE PLAN (STRESS TEST) ===\n\n";

try {
    // 1. Buscar plan OPORTUNIDAD
    $plan = Plan::where('slug', 'oportunidad')->first();
    if (!$plan) {
        throw new Exception("Plan OPORTUNIDAD no encontrado. Ejecuta seeders primero.");
    }
    echo "Plan encontrado: {$plan->name} (límite: {$plan->products_limit} productos)\n";

    // 2. Crear usuario y tenant de prueba
    $user = User::firstOrCreate(
        ['email' => 'stress@test.com'],
        ['name' => 'Stress Test User', 'password' => bcrypt('password')]
    );

    $tenant = Tenant::firstOrCreate(
        ['subdomain' => 'stress-test'],
        [
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'business_name' => 'Stress Test Tenant',
            'edit_pin' => '1234',
            'status' => 'active'
        ]
    );

    echo "Tenant de prueba: {$tenant->subdomain} (ID: {$tenant->id})\n";

    // 3. Limpiar productos existentes del tenant
    Product::where('tenant_id', $tenant->id)->delete();
    echo "Productos existentes eliminados.\n";

    // 4. Crear 6 productos (límite permitido)
    echo "\n--- Creando 6 productos (límite permitido) ---\n";
    for ($i = 1; $i <= 6; $i++) {
        $product = Product::create([
            'tenant_id' => $tenant->id,
            'name' => "Producto {$i}",
            'description' => "Descripción del producto {$i}",
            'price' => 100 * $i,
            'active' => true,
            'featured' => false,
            'position' => $i,
        ]);
        echo "✅ Producto {$i} creado (ID: {$product->id})\n";
    }

    $currentCount = Product::where('tenant_id', $tenant->id)->count();
    echo "Total actual: {$currentCount} productos\n";

    // 5. Intentar crear el 7mo producto (debería fallar) - simular llamada al ProductController
    echo "\n--- Intentando crear 7mo producto (debería fallar) ---\n";
    try {
        // Simular petición al ProductController como lo haría el dashboard
        $request = new Request([
            'name' => "Producto 7 (debería fallar)",
            'description' => "Este producto no debería crearse",
            'price_usd' => 700,
            'active' => true,
            'featured' => false,
            'position' => 7,
        ]);

        // Usar el ProductController directamente
        $controller = new \App\Http\Controllers\ProductController();
        $response = $controller->store($request, $tenant->id);

        if ($response->getStatusCode() === 422) {
            echo "✅ SISTEMA BLOQUEÓ CORRECTAMENTE: " . json_decode($response->getContent())->message . "\n";
        } else {
            echo "❌ ERROR: El 7mo producto fue creado - EL SISTEMA NO BLOQUEÓ\n";
            echo "Respuesta: " . $response->getContent() . "\n";
        }
    } catch (Exception $e) {
        echo "✅ SISTEMA BLOQUEÓ CORRECTAMENTE: " . $e->getMessage() . "\n";
    }

    // 6. Verificación final
    $finalCount = Product::where('tenant_id', $tenant->id)->count();
    echo "\n--- Verificación final ---\n";
    echo "Productos después del intento: {$finalCount}\n";

    if ($finalCount === 6) {
        echo "✅ STRESS TEST PASÓ: El sistema impidió exceder el límite del plan\n";
    } else {
        echo "❌ STRESS TEST FALLÓ: El sistema permitió exceder el límite\n";
    }

    // 7. Limpieza
    Product::where('tenant_id', $tenant->id)->delete();
    echo "\n🧹 Productos de prueba eliminados.\n";

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== FIN DE VALIDACIÓN ===\n";
