<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// URLs confirmed working (visible in hero slider)
$updates = [
    175 => 'https://images.unsplash.com/photo-1571091718767-18b5b1457add?w=600&auto=format&fit=crop', // hotdog/burger — Perro Caraqueño
    176 => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=600&auto=format&fit=crop', // burger — Perro Maracucho
    188 => 'https://images.unsplash.com/photo-1565299507177-b0ac66763828?w=600&auto=format&fit=crop', // food — Patacón Criollo
    201 => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=600&auto=format&fit=crop', // food — Yoyos de Plátano
];

foreach ($updates as $id => $url) {
    \DB::table('products')->where('id', $id)->where('tenant_id', 18)->update(['image_url' => $url]);
    $name = \DB::table('products')->where('id', $id)->value('name');
    echo "OK: {$name}\n";
}
