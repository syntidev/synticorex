<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$updates = [
    ['slug' => 'cat-crecimiento', 'query' => "UPDATE plans SET analytics_level = 'medium' WHERE slug = 'cat-crecimiento'"],
    ['slug' => 'cat-vision', 'query' => "UPDATE plans SET products_limit = 250, analytics_level = 'advanced' WHERE slug = 'cat-vision'"],
    ['slug' => 'food-crecimiento', 'query' => "UPDATE plans SET analytics_level = 'medium' WHERE slug = 'food-crecimiento'"],
    ['slug' => 'studio-crecimiento', 'query' => "UPDATE plans SET show_faq = 1 WHERE slug = 'studio-crecimiento'"],
    ['slug' => 'studio-vision', 'query' => "UPDATE plans SET products_limit = 200 WHERE slug = 'studio-vision'"],
];

foreach ($updates as $item) {
    $rows = \Illuminate\Support\Facades\DB::update($item['query']);
    echo "✓ {$item['slug']}: {$rows} fila(s) actualizada(s)\n";
}

echo "\n=== VERIFICACIÓN POST-UPDATE ===\n";
$results = \Illuminate\Support\Facades\DB::table('plans')
    ->select('slug', 'products_limit', 'analytics_level', 'show_faq')
    ->whereIn('slug', ['cat-crecimiento','cat-vision','food-crecimiento','studio-crecimiento','studio-vision'])
    ->orderBy('slug')
    ->get();

echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
