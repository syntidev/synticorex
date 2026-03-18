<?php declare(strict_types=1);

require_once __DIR__ . '/../bootstrap/app.php';

use Illuminate\Support\Facades\Route;

echo "=== BUSCANDO RUTA CATALOG ===\n\n";

// Verificar si ruta nombrada existe
$namedRoute = app('router')->getRoutes()->getByName('tenant.catalog');
if ($namedRoute) {
    echo "✅ Ruta nombrada 'tenant.catalog' EXISTE\n";
    echo "   Controller: " . $namedRoute->getActionName() . "\n";
    echo "   URI: " . $namedRoute->uri() . "\n\n";
} else {
    echo "❌ Ruta nombrada 'tenant.catalog' NO EXISTE\n\n";
}

// Buscar rutas que contengan 'catalog'
echo "=== RUTAS CON 'catalog' EN URI O ACTION ===\n";
$catalogRoutes = collect(app('router')->getRoutes()->getRoutes())
    ->filter(function ($route) {
        $uri = $route->uri();
        $action = $route->getActionName();
        return str_contains($uri, 'catalog', true) || str_contains($action, 'catalog', true);
    });

if ($catalogRoutes->count() > 0) {
    $catalogRoutes->each(function ($route) {
        echo "   • " . $route->uri() . "\n";
        echo "     → " . $route->getActionName() . "\n";
    });
} else {
    echo "   (ninguna encontrada)\n";
}

echo "\n=== RUTAS CON TENANT ({subdomain}) ===\n";
$tenantRoutes = collect(app('router')->getRoutes()->getRoutes())
    ->filter(fn($r) => str_contains($r->uri(), '{subdomain}', true))
    ->take(10);

if ($tenantRoutes->count() > 0) {
    $tenantRoutes->each(function ($route) {
        echo "   • " . $route->uri() . "\n";
        echo "     → " . $route->getActionName() . "\n";
    });
}
