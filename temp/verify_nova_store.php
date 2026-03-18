<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

$c = App\Models\TenantCustomization::where('tenant_id', 17)->first();
echo "hero_main_filename: " . $c->hero_main_filename . PHP_EOL;
echo "hero_secondary_filename: " . $c->hero_secondary_filename . PHP_EOL;
echo "theme_slug: " . $c->theme_slug . PHP_EOL;
