<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$results = \Illuminate\Support\Facades\DB::table('plans')
    ->select('slug', 'products_limit', 'analytics_level', 'show_faq')
    ->whereIn('slug', ['cat-crecimiento','cat-vision','food-crecimiento','studio-crecimiento','studio-vision'])
    ->orderBy('slug')
    ->get();

echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
