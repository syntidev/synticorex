<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$cols = \DB::getSchemaBuilder()->getColumnListing('products');
echo implode(', ', $cols) . "\n\n";

$products = \DB::table('products')
    ->where('tenant_id', 18)
    ->where(function($q) {
        $q->where('name', 'like', '%Perro%')
          ->orWhere('name', 'like', '%Patac%')
          ->orWhere('name', 'like', '%Yoyo%')
          ->orWhere('name', 'like', '%Plat%');
    })
    ->get();

foreach ($products as $p) {
    echo "ID:{$p->id} | {$p->name}\n";
    echo json_encode((array)$p) . "\n\n";
}
