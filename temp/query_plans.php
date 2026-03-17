<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$plans = \DB::select('SELECT id, slug, blueprint, name, price_usd, products_limit, services_limit, show_dollar_rate, analytics_level, seo_level, whatsapp_numbers, show_faq, show_about_section FROM plans ORDER BY blueprint, price_usd ASC');

if (empty($plans)) {
    echo "✗ Tabla plans vacía o no existe\n";
} else {
    echo json_encode($plans, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
}
