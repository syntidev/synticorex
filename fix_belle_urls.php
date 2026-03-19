<?php
use Illuminate\Support\Facades\DB;

$fixes = [
    'Set Nail Art Completo'          => 'https://images.unsplash.com/photo-1604654894610-df63bc536371?w=600&q=80',
    'Esmalte Gel UV Semi-permanente' => 'https://images.unsplash.com/photo-1519014816548-bf5fe059798b?w=600&q=80',
    'Mascarilla Keratina Pro'        => 'https://images.unsplash.com/photo-1527799820374-dcf8d9d4a388?w=600&q=80',
    'Aceite de Argán Marroquí'       => 'https://images.unsplash.com/photo-1617897903246-719242758050?w=600&q=80',
    'Contorno y Bronzer Duo'         => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=600&q=80',
    'Hidratante FPS 50 Oil-Free'     => 'https://images.unsplash.com/photo-1571781926291-c477ebfd024b?w=600&q=80',
    'Retinol Noche 0.5%'             => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=600&q=80',
    'Body Mist Fresh Vanilla'        => 'https://images.unsplash.com/photo-1587017539504-67cfbddac569?w=600&q=80',
];

$tenantId = DB::table('tenants')->where('subdomain','bellestore')->value('id');

foreach ($fixes as $name => $url) {
    $updated = DB::table('products')
        ->where('tenant_id', $tenantId)
        ->where('name', $name)
        ->update(['image_url' => $url]);
    echo ($updated ? '✓' : '✗') . " {$name}\n";
}

echo "\nDone\n";
