<?php
use Illuminate\Support\Facades\DB;

// ── Limpiar si existe ─────────────────────────────────────
$old = DB::table('tenants')->where('subdomain','donaz')->first();
if ($old) {
    DB::table('menu_items')->whereIn('category_id',
        DB::table('menu_categories')->where('tenant_id',$old->id)->pluck('id')
    )->delete();
    DB::table('menu_categories')->where('tenant_id',$old->id)->delete();
    DB::table('tenant_customization')->where('tenant_id',$old->id)->delete();
    DB::table('tenants')->where('id',$old->id)->delete();
    echo "Tenant anterior eliminado\n";
}

// ── Crear tenant ──────────────────────────────────────────
$tenantId = DB::table('tenants')->insertGetId([
    'user_id'           => 1,
    'business_name'     => 'Donaz',
    'subdomain'         => 'donaz',
    'business_segment'  => 'Donutería & Bebidas',
    'slogan'            => 'La zona de la dona. La número uno.',
    'phone'             => '+584241234567',
    'whatsapp_sales'    => '+584241234567',
    'whatsapp_active'   => 'sales',
    'address'           => 'C.C. Sambil, Local 42',
    'city'              => 'Caracas',
    'country'           => 'Venezuela',
    'plan_id'           => 13,
    'edit_pin'          => '123456',
    'is_demo'           => 1,
    'demo_product'      => 'food',
    'is_open'           => 1,
    'currency_display'  => 'both',
    'status'            => 'active',
    'settings'          => json_encode([
        'engine_settings' => [
            'currency' => [
                'display' => [
                    'symbols'            => ['bolivares'=>'Bs.','reference'=>'REF'],
                    'show_euro'          => false,
                    'has_toggle'         => true,
                    'hide_price'         => false,
                    'show_bolivares'     => true,
                    'show_reference'     => true,
                    'saved_display_mode' => 'both_toggle',
                ],
                'auto_update' => true,
            ],
            'features'  => ['show_hours_indicator' => true],
            'template'  => 'food',
        ],
    ]),
    'created_at' => now(),
    'updated_at' => now(),
]);
echo "Tenant Donaz creado: ID {$tenantId}\n";

// ── Customización ─────────────────────────────────────────
DB::table('tenant_customization')->insert([
    'tenant_id'               => $tenantId,
    'hero_main_filename'      => 'https://images.unsplash.com/photo-1551024601-bec78aea704b?w=1200&q=80',
    'hero_secondary_filename' => 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=1200&q=80',
    'hero_tertiary_filename'  => 'https://images.unsplash.com/photo-1582716401301-b2407dc7563d?w=1200&q=80',
    'hero_image_4_filename'   => 'https://images.unsplash.com/photo-1508737027454-e6454ef45afd?w=1200&q=80',
    'hero_image_5_filename'   => 'https://images.unsplash.com/photo-1556913396-7a3c459ef68e?w=1200&q=80',
    'hero_layout'             => 'fullscreen',
    'theme_slug'              => 'default',
    'social_networks'         => json_encode([
        'instagram' => '@donaz.one',
        'tiktok'    => '@donaz.one',
        'facebook'  => 'donaz.one',
    ]),
    'payment_methods' => json_encode([
        'global'   => ['pagoMovil','cash','zelle','zinli'],
        'currency' => ['usd'],
    ]),
    'created_at' => now(),
    'updated_at' => now(),
]);

// ── Categorías e items ────────────────────────────────────
$cats = [
    [
        'nombre' => 'Donas Clásicas',
        'items'  => [
            ['nombre'=>'Glaseada Original',    'precio'=>2.50,'badge'=>'popular',  'featured'=>1,
             'desc'=>'La clásica de todas las clásicas. Masa suave con glaseado brillante.',
             'img' =>'https://images.unsplash.com/photo-1551024601-bec78aea704b?w=600&q=80'],
            ['nombre'=>'Chocolate Intenso',    'precio'=>3.00,'badge'=>'popular',  'featured'=>1,
             'desc'=>'Cubierta de ganache de chocolate negro belga. Irresistible.',
             'img' =>'https://images.unsplash.com/photo-1508737027454-e6454ef45afd?w=600&q=80'],
            ['nombre'=>'Fresa con Sprinkles',  'precio'=>3.00,'badge'=>'nuevo',    'featured'=>0,
             'desc'=>'Glaseado rosa de fresa natural con confites de colores.',
             'img' =>'https://images.unsplash.com/photo-1556913396-7a3c459ef68e?w=600&q=80'],
            ['nombre'=>'Maple con Tocineta',   'precio'=>3.50,'badge'=>'destacado','featured'=>1,
             'desc'=>'El combo perfecto: glaseado de maple y tocineta crujiente.',
             'img' =>'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=600&q=80'],
            ['nombre'=>'Vainilla Clásica',     'precio'=>2.50,'badge'=>null,       'featured'=>0,
             'desc'=>'Suave, esponjosa, con glaseado de vainilla bourbon.',
             'img' =>'https://images.unsplash.com/photo-1551024601-bec78aea704b?w=600&q=80'],
            ['nombre'=>'Glazed Canela',        'precio'=>2.75,'badge'=>'promo',    'featured'=>0,
             'desc'=>'Glaseado de azúcar con toque de canela. Perfecta para el desayuno.',
             'img' =>'https://images.unsplash.com/photo-1551024601-bec78aea704b?w=600&q=80'],
        ]
    ],
    [
        'nombre' => 'Donas Rellenas',
        'items'  => [
            ['nombre'=>'Rellena de Nutella',     'precio'=>4.00,'badge'=>'popular',  'featured'=>1,
             'desc'=>'Masa brioche rellena al tope de Nutella. Sin hueco, puro relleno.',
             'img' =>'https://images.unsplash.com/photo-1582716401301-b2407dc7563d?w=600&q=80'],
            ['nombre'=>'Crema Pastelera Limón',  'precio'=>3.75,'badge'=>'nuevo',    'featured'=>0,
             'desc'=>'Rellena de crema pastelera de limón con azúcar glass por encima.',
             'img' =>'https://images.unsplash.com/photo-1582716401301-b2407dc7563d?w=600&q=80'],
            ['nombre'=>'Dulce de Leche',         'precio'=>4.00,'badge'=>'popular',  'featured'=>1,
             'desc'=>'Rellena con dulce de leche artesanal venezolano. Un clásico local.',
             'img' =>'https://images.unsplash.com/photo-1582716401301-b2407dc7563d?w=600&q=80'],
            ['nombre'=>'Frambuesa y Crema',      'precio'=>4.25,'badge'=>'destacado','featured'=>0,
             'desc'=>'Mermelada de frambuesa + crema chantilly. Cubierta de azúcar glass.',
             'img' =>'https://images.unsplash.com/photo-1582716401301-b2407dc7563d?w=600&q=80'],
        ]
    ],
    [
        'nombre' => 'Donas Largas',
        'items'  => [
            ['nombre'=>'Long John Chocolate', 'precio'=>3.50,'badge'=>'popular','featured'=>1,
             'desc'=>'Dona larga con cobertura de chocolate y relleno de crema.',
             'img' =>'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=600&q=80'],
            ['nombre'=>'Long John Vainilla',  'precio'=>3.25,'badge'=>null,     'featured'=>0,
             'desc'=>'Clásica dona larga con glaseado de vainilla suave.',
             'img' =>'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=600&q=80'],
            ['nombre'=>'Long John Arcoíris',  'precio'=>3.75,'badge'=>'nuevo',  'featured'=>0,
             'desc'=>'Glaseado multicolor con sprinkles. La favorita de los niños.',
             'img' =>'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=600&q=80'],
        ]
    ],
    [
        'nombre' => 'Bebidas',
        'items'  => [
            ['nombre'=>'Café Americano',      'precio'=>2.00,'badge'=>null,     'featured'=>0,
             'desc'=>'Espresso doble con agua caliente. El compañero perfecto.',
             'img' =>'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=600&q=80'],
            ['nombre'=>'Cappuccino',          'precio'=>2.75,'badge'=>'popular','featured'=>0,
             'desc'=>'Espresso + leche vaporizada + espuma. Arte latte opcional.',
             'img' =>'https://images.unsplash.com/photo-1572442388796-11668a67e53d?w=600&q=80'],
            ['nombre'=>'Chocolate Caliente',  'precio'=>2.50,'badge'=>'popular','featured'=>1,
             'desc'=>'Chocolate artesanal venezolano con leche entera. Cremoso y espeso.',
             'img' =>'https://images.unsplash.com/photo-1542990253-0d0f5be5f0ed?w=600&q=80'],
            ['nombre'=>'Limonada Fría',       'precio'=>2.25,'badge'=>null,     'featured'=>0,
             'desc'=>'Limonada natural con menta fresca. Refrescante y sin colorantes.',
             'img' =>'https://images.unsplash.com/photo-1621263764928-df1444c5e859?w=600&q=80'],
            ['nombre'=>'Frappé de Caramelo',  'precio'=>3.50,'badge'=>'nuevo',  'featured'=>0,
             'desc'=>'Café frío blended con caramelo, leche y crema batida.',
             'img' =>'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=600&q=80'],
        ]
    ],
];

$pos = 1;
$totalItems = 0;
foreach ($cats as $catIdx => $cat) {
    $catId = DB::table('menu_categories')->insertGetId([
        'tenant_id'  => $tenantId,
        'nombre'     => $cat['nombre'],
        'activo'     => 1,
        'position'   => $catIdx + 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    foreach ($cat['items'] as $item) {
        DB::table('menu_items')->insert([
            'tenant_id'   => $tenantId,
            'category_id' => $catId,
            'nombre'      => $item['nombre'],
            'precio'      => $item['precio'],
            'descripcion' => $item['desc'],
            'image_path'  => $item['img'],
            'badge'       => $item['badge'],
            'is_featured' => $item['featured'],
            'activo'      => 1,
            'position'    => $pos++,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
        $totalItems++;
    }
    echo "✓ {$cat['nombre']} (" . count($cat['items']) . " items)\n";
}

echo "\n✅ Donaz lista — tenant_id: {$tenantId}\n";
echo "   4 categorías · {$totalItems} items\n";
echo "   URL: http://127.0.0.1:8000/donaz\n";
echo "   PIN: 123456\n";