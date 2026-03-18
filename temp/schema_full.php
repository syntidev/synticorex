<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Columnas exactas de tenants
echo "TENANT COLUMNS:\n";
echo implode(', ', \Illuminate\Support\Facades\Schema::getColumnListing('tenants')) . "\n\n";

// Columnas exactas de tenant_customization
echo "TENANT_CUSTOMIZATION COLUMNS:\n";
echo implode(', ', \Illuminate\Support\Facades\Schema::getColumnListing('tenant_customization')) . "\n\n";

// Ver un tenant existente completo para ver formato de campos JSON
echo "TENANT #3 CON CUSTOMIZATION:\n";
$t = \App\Models\Tenant::with('customization')->find(3);
echo json_encode($t->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
