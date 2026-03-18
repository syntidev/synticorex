<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$c = \App\Models\TenantCustomization::where('tenant_id', 18)->first();

// 5 URLs confirmed working (img2-5 were visible in browser)
// Reorder so img1 gets a confirmed URL, all 5 are unique
$c->hero_main_filename      = 'https://images.unsplash.com/photo-1571091718767-18b5b1457add?w=800&auto=format&fit=crop'; // hotdog/burger (was img4)
$c->hero_secondary_filename = 'https://images.unsplash.com/photo-1565299507177-b0ac66763828?w=800&auto=format&fit=crop'; // pizza (was img2)
$c->hero_tertiary_filename  = 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=800&auto=format&fit=crop'; // burger (was img3)
$c->hero_image_4_filename   = 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=800&auto=format&fit=crop'; // cake (was img5)
$c->hero_image_5_filename   = 'https://images.unsplash.com/photo-1550547660-d9450f859349?w=800&auto=format&fit=crop'; // hotdog con mostaza

$c->save();
echo "OK\n";
echo "img1: " . $c->hero_main_filename . "\n";
