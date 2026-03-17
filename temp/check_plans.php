<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

try {
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    echo "✓ Bootstrap iniciado\n";

    // Verificar conexión a BD
    $db = \Illuminate\Support\Facades\DB::connection();
    $db->getPdo();
    echo "✓ Conexión a BD OK\n";

    // Ejecutar la query
    $results = \Illuminate\Support\Facades\DB::table('plans')
        ->select('id', 'slug', 'blueprint', 'name', 'price_usd', 'products_limit', 'services_limit', 'show_dollar_rate', 'analytics_level', 'seo_level', 'whatsapp_numbers', 'show_faq', 'show_about_section')
        ->orderBy('blueprint')
        ->orderBy('price_usd')
        ->get();

    echo "\n=== PLANES EN BD ===\n";
    if ($results->isEmpty()) {
        echo "✗ Tabla plans vacía. Ejecuta: php artisan db:seed --class=PlansSeeder\n";
    } else {
        echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
    }
} catch (\Throwable $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
