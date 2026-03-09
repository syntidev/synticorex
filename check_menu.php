<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Storage;

// Load Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Check file existence
echo "File exists: ";
echo Storage::exists('tenants/7/menu/menu.json') ? "EXISTE" : "NO EXISTE";
echo "\n\n";

// Decode and count items
try {
    $data = json_decode(Storage::get('tenants/7/menu/menu.json'), true);
    if ($data && isset($data['categories'][0]['items'])) {
        echo "Ítems cat-1: " . count($data['categories'][0]['items']) . "\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Full path
echo "Full path: " . storage_path('app/tenants/7/menu/menu.json') . "\n";
