<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$c = \App\Models\TenantCustomization::where('tenant_id', 18)->first();
$c->hero_main_filename = 'https://images.unsplash.com/photo-1565299507177-b0ac66763828?w=800&auto=format&fit=crop';
$c->save();
echo "OK: " . $c->hero_main_filename . "\n";
