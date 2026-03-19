<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

// Limpiar si existe
$old = DB::table('tenants')->where('subdomain','donaz')->first();
if ($old) {
    DB::table('tenant_customization')->where('tenant_id',$old->id)->delete();
    DB::table('tenants')->where('id',$old->id)->delete();
    Storage::disk('local')->delete("tenants/{$old->id}/menu/menu.json");
    echo "Tenant anterior eliminado\n";
}

// Crear tenant
$tenantId = DB::table('tenants')->insertGetId([
    'user_id'          => 1,
    'business_name'    => 'Donaz',
    'subdomain'        => 'donaz',
    'business_segment' => 'Donutería & Bebidas',
    'slogan'           => 'La zona de la dona. La número uno.',
    'phone'            => '+584241234567',
    'whatsapp_sales'   => '+584241234567',
    'whatsapp_active'  => 'sales',
    'address'          => 'C.C. Sambil, Local 42',
    'city'             => 'Caracas',
    'country'          => 'Venezuela',
    'plan_id'          => 13,
    'edit_pin'         => '123456',
    'is_demo'          => 1,
    'demo_product'     => 'food',
    'is_open'          => 1,
    'currency_display' => 'both',
    'status'           => 'active',
    'settings'         => json_encode([
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
            'features' => ['show_hours_indicator' => true],
            'template' => 'food',
        ],
    ]),
    'created_at' => now(),
    'updated_at' => now(),
]);
echo "Tenant Donaz creado: ID {$tenantId}\n";

// Customización
DB::table('tenant_customization')->insert([
    'tenant_id'               => $tenantId,
    'hero_main_filename'      => 'https://images.unsplash.com/photo-1551024601-bec78aea704b?w=1200&q=80',
    'hero_secondary_filename' => 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=1200&q=80',
    'hero_tertiary_filename'  => 'https://images.unsplash.com/photo-1582716401301-b2407dc7563d?w=1200&q=80',
    'hero_image_4_filename'   => 'https://images.unsplash.com/photo-1508737027454-e6454ef45afd?w=1200&q=80',
    'hero_image_5_filename'   => 'https://images.unsplash.com/photo-1556913396-7a3c459ef68e?w=1200&q=80',
    'hero_layout'             => 'fullscreen',
    'theme_slug'              => 'default',
    'social_networks'         => json_encode(['instagram'=>'@donaz.one','tiktok'=>'@donaz.one','facebook'=>'donaz.one']),
    'payment_methods'         => json_encode(['global'=>['pagoMovil','cash','zelle','zinli'],'currency'=>['usd']]),
    'created_at'              => now(),
    'updated_at'              => now(),
]);

// Menú como archivo JSON en storage (igual que MenuService)
$menu = [
    'categories' => [
        [
            'id'=>'cat-1','nombre'=>'Donas Clásicas','activo'=>true,
            'items'=>[
                ['id'=>'i-01','nombre'=>'Glaseada Original','precio'=>2.50,'badge'=>'popular','is_featured'=>true,'activo'=>true,
                 'descripcion'=>'La clásica de todas las clásicas. Masa suave con glaseado brillante.',
                 'image_path'=>'https://images.unsplash.com/photo-1551024601-bec78aea704b?w=600&q=80'],
                ['id'=>'i-02','nombre'=>'Chocolate Intenso','precio'=>3.00,'badge'=>'popular','is_featured'=>true,'activo'=>true,
                 'descripcion'=>'Cubierta de ganache de chocolate negro belga. Irresistible.',
                 'image_path'=>'https://images.unsplash.com/photo-1508737027454-e6454ef45afd?w=600&q=80'],
                ['id'=>'i-03','nombre'=>'Fresa con Sprinkles','precio'=>3.00,'badge'=>'nuevo','is_featured'=>false,'activo'=>true,
                 'descripcion'=>'Glaseado rosa de fresa natural con confites de colores.',
                 'image_path'=>'https://images.unsplash.com/photo-1556913396-7a3c459ef68e?w=600&q=80'],
                ['id'=>'i-04','nombre'=>'Maple con Tocineta','precio'=>3.50,'badge'=>'destacado','is_featured'=>true,'activo'=>true,
                 'descripcion'=>'El combo perfecto: glaseado de maple y tocineta crujiente.',
                 'image_path'=>'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=600&q=80'],
                ['id'=>'i-05','nombre'=>'Vainilla Clásica','precio'=>2.50,'badge'=>null,'is_featured'=>false,'activo'=>true,
                 'descripcion'=>'Suave, esponjosa, con glaseado de vainilla bourbon.',
                 'image_path'=>'https://images.unsplash.com/photo-1551024601-bec78aea704b?w=600&q=80'],
                ['id'=>'i-06','nombre'=>'Glazed Canela','precio'=>2.75,'badge'=>'promo','is_featured'=>false,'activo'=>true,
                 'descripcion'=>'Glaseado de azúcar con toque de canela.',
                 'image_path'=>'https://images.unsplash.com/photo-1551024601-bec78aea704b?w=600&q=80'],
            ]
        ],
        [
            'id'=>'cat-2','nombre'=>'Donas Rellenas','activo'=>true,
            'items'=>[
                ['id'=>'i-07','nombre'=>'Rellena de Nutella','precio'=>4.00,'badge'=>'popular','is_featured'=>true,'activo'=>true,
                 'descripcion'=>'Masa brioche rellena al tope de Nutella. Sin hueco, puro relleno.',
                 'image_path'=>'https://images.unsplash.com/photo-1582716401301-b2407dc7563d?w=600&q=80'],
                ['id'=>'i-08','nombre'=>'Crema Pastelera Limón','precio'=>3.75,'badge'=>'nuevo','is_featured'=>false,'activo'=>true,
                 'descripcion'=>'Rellena de crema pastelera de limón con azúcar glass.',
                 'image_path'=>'https://images.unsplash.com/photo-1582716401301-b2407dc7563d?w=600&q=80'],
                ['id'=>'i-09','nombre'=>'Dulce de Leche','precio'=>4.00,'badge'=>'popular','is_featured'=>true,'activo'=>true,
                 'descripcion'=>'Rellena con dulce de leche artesanal venezolano.',
                 'image_path'=>'https://images.unsplash.com/photo-1582716401301-b2407dc7563d?w=600&q=80'],
                ['id'=>'i-10','nombre'=>'Frambuesa y Crema','precio'=>4.25,'badge'=>'destacado','is_featured'=>false,'activo'=>true,
                 'descripcion'=>'Mermelada de frambuesa + crema chantilly.',
                 'image_path'=>'https://images.unsplash.com/photo-1582716401301-b2407dc7563d?w=600&q=80'],
            ]
        ],
        [
            'id'=>'cat-3','nombre'=>'Donas Largas','activo'=>true,
            'items'=>[
                ['id'=>'i-11','nombre'=>'Long John Chocolate','precio'=>3.50,'badge'=>'popular','is_featured'=>true,'activo'=>true,
                 'descripcion'=>'Dona larga con cobertura de chocolate y relleno de crema.',
                 'image_path'=>'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=600&q=80'],
                ['id'=>'i-12','nombre'=>'Long John Vainilla','precio'=>3.25,'badge'=>null,'is_featured'=>false,'activo'=>true,
                 'descripcion'=>'Clásica dona larga con glaseado de vainilla suave.',
                 'image_path'=>'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=600&q=80'],
                ['id'=>'i-13','nombre'=>'Long John Arcoíris','precio'=>3.75,'badge'=>'nuevo','is_featured'=>false,'activo'=>true,
                 'descripcion'=>'Glaseado multicolor con sprinkles. La favorita de los niños.',
                 'image_path'=>'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=600&q=80'],
            ]
        ],
        [
            'id'=>'cat-4','nombre'=>'Bebidas','activo'=>true,
            'items'=>[
                ['id'=>'i-14','nombre'=>'Café Americano','precio'=>2.00,'badge'=>null,'is_featured'=>false,'activo'=>true,
                 'descripcion'=>'Espresso doble con agua caliente.',
                 'image_path'=>'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=600&q=80'],
                ['id'=>'i-15','nombre'=>'Cappuccino','precio'=>2.75,'badge'=>'popular','is_featured'=>false,'activo'=>true,
                 'descripcion'=>'Espresso + leche vaporizada + espuma.',
                 'image_path'=>'https://images.unsplash.com/photo-1572442388796-11668a67e53d?w=600&q=80'],
                ['id'=>'i-16','nombre'=>'Chocolate Caliente','precio'=>2.50,'badge'=>'popular','is_featured'=>true,'activo'=>true,
                 'descripcion'=>'Chocolate artesanal venezolano con leche entera.',
                 'image_path'=>'https://images.unsplash.com/photo-1542990253-0d0f5be5f0ed?w=600&q=80'],
                ['id'=>'i-17','nombre'=>'Limonada Fría','precio'=>2.25,'badge'=>null,'is_featured'=>false,'activo'=>true,
                 'descripcion'=>'Limonada natural con menta fresca.',
                 'image_path'=>'https://images.unsplash.com/photo-1621263764928-df1444c5e859?w=600&q=80'],
                ['id'=>'i-18','nombre'=>'Frappé de Caramelo','precio'=>3.50,'badge'=>'nuevo','is_featured'=>false,'activo'=>true,
                 'descripcion'=>'Café frío blended con caramelo, leche y crema batida.',
                 'image_path'=>'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=600&q=80'],
            ]
        ],
    ]
];

$path = "tenants/{$tenantId}/menu/menu.json";
Storage::disk('local')->put($path, json_encode($menu, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Menú guardado en storage: {$path}\n";

echo "\n✅ Donaz lista — tenant_id: {$tenantId}\n";
echo "   4 categorías · 18 items\n";
echo "   URL: http://127.0.0.1:8000/donaz\n";
echo "   PIN: 123456\n";
