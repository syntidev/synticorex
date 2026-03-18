<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Columnas correctas
echo "TENANT COLUMNS:\n";
print_r(\Illuminate\Support\Facades\Schema::getColumnListing('tenants'));

echo "\nTENANT_CUSTOMIZATION COLUMNS (singular):\n";
print_r(\Illuminate\Support\Facades\Schema::getColumnListing('tenant_customization'));

// Estructura real de tenants
echo "\nTENANTS EXISTENTES:\n";
\App\Models\Tenant::select('id','subdomain','business_name','status')
    ->get()->each(fn($t) => print($t->id.' | '.$t->subdomain.' | '.$t->business_name.' | '.$t->status."\n"));

// Planes disponibles
echo "\nPLANES:\n";
\App\Models\Plan::select('id','name','slug')->get()
    ->each(fn($p) => print($p->id.' | '.$p->slug.' | '.$p->name."\n"));

// Customizations
echo "\nCUSTOMIZATIONS:\n";
\DB::table('tenant_customization')->select('tenant_id')->get()
    ->each(fn($c) => print("tenant_id: ".$c->tenant_id."\n"));



