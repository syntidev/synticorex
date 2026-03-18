<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$c = \App\Models\TenantCustomization::where('tenant_id', 18)->first();
$c->hero_main_filename = 'https://images.unsplash.com/photo-1612392062631-94390b4b4b41?w=800';
$c->hero_secondary_filename = 'https://images.unsplash.com/photo-1565299507177-b0ac66763828?w=800';
$c->hero_tertiary_filename = 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=800';
$c->hero_image_4_filename = 'https://images.unsplash.com/photo-1571091718767-18b5b1457add?w=800';
$c->hero_image_5_filename = 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=800';
$c->save();
echo "OK\n";
