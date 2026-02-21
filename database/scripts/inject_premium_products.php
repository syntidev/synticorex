<?php

/**
 * Script de Inyección "Clean Slate" - SYNTIWeb Premium Products
 * 
 * PASO 1: Limpia todos los productos existentes
 * PASO 2: Genera 15 productos premium/gourmet por tenant
 * PASO 3: Validación automática
 * 
 * Uso: php artisan tinker < database/scripts/inject_premium_products.php
 * O ejecutar manualmente línea por línea en Tinker
 */

use App\Models\Product;
use App\Models\Tenant;
use Illuminate\Support\Str;

echo "🚀 INICIANDO INYECCIÓN CLEAN SLATE\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// ═══════════════════════════════════════════════════════════════
// PASO 1: LIMPIEZA TOTAL
// ═══════════════════════════════════════════════════════════════

echo "📊 PASO 1: LIMPIEZA DE DATOS\n";
echo "────────────────────────────────────────────────────────────────\n";

$countBefore = Product::count();
echo "⚠️  Productos actuales: {$countBefore}\n";

Product::truncate();

$countAfter = Product::count();
echo "✅ Productos eliminados: " . ($countBefore - $countAfter) . "\n";
echo "✅ Base de datos limpia: {$countAfter} productos restantes\n\n";

// ═══════════════════════════════════════════════════════════════
// PASO 2: INYECCIÓN DE PRODUCTOS PREMIUM/GOURMET
// ═══════════════════════════════════════════════════════════════

echo "🍽️  PASO 2: INYECCIÓN DE PRODUCTOS PREMIUM\n";
echo "────────────────────────────────────────────────────────────────\n";

// Catálogo de nombres premium/gourmet elegantes
$premiumNames = [
    // Línea Signature (Featured)
    'Reserva Especial Signature',
    'Edición Limitada Premium',
    'Colección Exclusiva Gourmet',
    
    // Línea Artesanal
    'Artesanal Selection',
    'Crafted Experience',
    'Curated Delicacies',
    'Heritage Collection',
    'Masterpiece Edition',
    
    // Línea Elegante
    'Elegance Supreme',
    'Royal Reserve',
    'Grand Selection',
    'Prestige Collection',
    
    // Línea Moderna
    'Contemporary Fusion',
    'Urban Gourmet',
    'Cosmopolitan Blend',
];

// Descripciones premium
$premiumDescriptions = [
    'Elaborado con ingredientes selectos y técnicas artesanales. Una experiencia sensorial única que refleja tradición y excelencia.',
    'Selección cuidadosa de los mejores elementos, combinados para crear una propuesta gastronómica excepcional.',
    'Creado por expertos con pasión por la perfección. Cada detalle ha sido pensado para deleitar los sentidos más exigentes.',
    'Una fusión magistral de sabores auténticos y presentación impecable. El equilibrio perfecto entre tradición e innovación.',
    'Producto premium diseñado para paladares refinados. Calidad superior garantizada en cada presentación.',
    'Experiencia gourmet inigualable. Ingredientes de origen certificado y proceso de elaboración meticuloso.',
    'La elección perfecta para quienes buscan lo mejor. Distinción y sabor en cada bocado.',
    'Colección exclusiva con carácter único. Un homenaje a la alta gastronomía y la elegancia.',
];

// Badges disponibles: 'hot', 'new', 'promo', null
$badges = ['hot', 'new', 'promo', null, null]; // Más probabilidad de no tener badge

// URLs de imágenes placeholder premium (Unsplash gourmet/food)
$imageUrls = [
    'https://images.unsplash.com/photo-1555939594-58d7cb561ad1',
    'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38',
    'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe',
    'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445',
    'https://images.unsplash.com/photo-1565958011703-44f9829ba187',
    'https://images.unsplash.com/photo-1571091718767-18b5b1457add',
    'https://images.unsplash.com/photo-1565299585323-38d6b0865b47',
    'https://images.unsplash.com/photo-1546069901-ba9599a7e63c',
    'https://images.unsplash.com/photo-1563379926898-05f4575a45d8',
    'https://images.unsplash.com/photo-1559847844-5315695dadae',
    'https://images.unsplash.com/photo-1592417817038-d13fd7342605',
    'https://images.unsplash.com/photo-1555507036-ab1f4038808a',
    'https://images.unsplash.com/photo-1587334207346-d5c654f5c82e',
    'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d',
    'https://images.unsplash.com/photo-1599975464400-1e22c7e0fb0a',
];

$tenants = Tenant::all();

foreach ($tenants as $tenant) {
    echo "\n📦 Generando productos para: {$tenant->business_name} (ID: {$tenant->id})\n";
    echo "   " . str_repeat("─", 60) . "\n";
    
    $featuredCount = 0;
    
    for ($i = 0; $i < 15; $i++) {
        // Asignar is_featured a los primeros 3 productos (1 de cada 5)
        $isFeatured = $featuredCount < 3 && $i % 5 === 0;
        if ($isFeatured) {
            $featuredCount++;
        }
        
        // Precios variados con decimales (rango: $8.99 a $49.99)
        $priceUsd = round(rand(899, 4999) / 100, 2);
        
        // Badge aleatorio
        $badge = $badges[array_rand($badges)];
        
        // Nombre del catálogo
        $name = $premiumNames[$i];
        
        // Descripción aleatoria
        $description = $premiumDescriptions[array_rand($premiumDescriptions)];
        
        // Imagen aleatoria
        $imageUrl = $imageUrls[array_rand($imageUrls)] . '?w=400&h=300&fit=crop';
        
        $product = Product::create([
            'tenant_id' => $tenant->id,
            'name' => $name,
            'description' => $description,
            'price_usd' => $priceUsd,
            'price_bs' => null, // NULL: El sistema calculará con tasa global del tenant
            'image_filename' => $imageUrl, // Usamos URL como placeholder
            'position' => $i + 1,
            'is_active' => true,
            'is_featured' => $isFeatured,
            'badge' => $badge,
        ]);
        
        $statusIcon = $isFeatured ? '⭐' : '  ';
        $badgeText = $badge ? "[$badge]" : '';
        echo "   {$statusIcon} {$product->name} - \${$priceUsd} {$badgeText}\n";
    }
    
    echo "   ✅ 15 productos generados exitosamente\n";
}

// ═══════════════════════════════════════════════════════════════
// PASO 3: VALIDACIÓN
// ═══════════════════════════════════════════════════════════════

echo "\n\n📋 PASO 3: VALIDACIÓN FINAL\n";
echo "────────────────────────────────────────────────────────────────\n";

$totalProducts = Product::count();
$totalFeatured = Product::where('is_featured', true)->count();
$totalHot = Product::where('badge', 'hot')->count();
$totalNew = Product::where('badge', 'new')->count();
$totalPromo = Product::where('badge', 'promo')->count();

echo "✅ Total productos inyectados: {$totalProducts}\n";
echo "✅ Productos destacados (featured): {$totalFeatured}\n";
echo "✅ Badges HOT: {$totalHot}\n";
echo "✅ Badges NEW: {$totalNew}\n";
echo "✅ Badges PROMO: {$totalPromo}\n\n";

echo "📊 Distribución por Tenant:\n";
foreach ($tenants as $tenant) {
    $count = Product::where('tenant_id', $tenant->id)->count();
    $featured = Product::where('tenant_id', $tenant->id)->where('is_featured', true)->count();
    echo "   • {$tenant->business_name}: {$count} productos ({$featured} featured)\n";
}

echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "🎉 INYECCIÓN CLEAN SLATE COMPLETADA EXITOSAMENTE\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "\n";
echo "✅ Los archivos están listos para que el frontend renderice.\n";
echo "✅ Conversión USD→Bs se calculará con la tasa global del tenant.\n";
echo "✅ 3 productos featured por tenant.\n";
echo "✅ Badges distribuidos: hot, new, promo.\n\n";

echo "🚀 Puedes probar el frontend en:\n";
foreach ($tenants as $tenant) {
    echo "   → http://localhost:8000/{$tenant->slug}\n";
}

echo "\n";
