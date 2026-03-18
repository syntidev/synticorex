<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$c = \App\Models\TenantCustomization::where('tenant_id', 18)->first();
echo "hero_main_filename:      " . $c->hero_main_filename . "\n";
echo "hero_secondary_filename: " . $c->hero_secondary_filename . "\n";
echo "hero_tertiary_filename:  " . $c->hero_tertiary_filename . "\n";
echo "hero_image_4_filename:   " . $c->hero_image_4_filename . "\n";
echo "hero_image_5_filename:   " . $c->hero_image_5_filename . "\n";
