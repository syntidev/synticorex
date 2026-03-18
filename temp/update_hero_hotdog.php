<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$c = \App\Models\TenantCustomization::where('tenant_id', 18)->first();
$c->hero_main_filename = 'https://images.unsplash.com/photo-1555939594-58d7cb561e1a?w=800';
$c->save();
echo "Hero image updated OK\n";
