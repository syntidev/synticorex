<?php
use App\Models\Tenant;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Plan;
use Illuminate\Support\Facades\DB;

// ── 1. Buscar plan cat-vision ─────────────────────────────
$plan = Plan::where('slug', 'cat-vision')->first();
if (!$plan) { echo "ERROR: plan cat-vision no encontrado\n"; return; }

// ── 2. Crear tenant Belle Store ───────────────────────────
$existing = Tenant::where('subdomain', 'bellestore')->first();
if ($existing) {
    echo "Tenant ya existe (ID {$existing->id}), actualizando...\n";
    $tenant = $existing;
} else {
    $tenant = Tenant::create([
        'business_name'     => 'Belle Store',
        'subdomain'         => 'bellestore',
        'business_segment'  => 'Belleza & Cosméticos',
        'slogan'            => 'Tu belleza, nuestra pasión',
        'phone'             => '+584121234567',
        'city'              => 'Caracas',
        'country'           => 'Venezuela',
        'user_id'           => 1,
        'edit_pin'          => '123456',
        'plan_id'           => $plan->id,
        'status'            => 'active',
        'settings'          => json_encode([
            'engine_settings' => [
                'currency' => ['exchange_rate' => 36.50]
            ]
        ]),
    ]);
    echo "Tenant creado: ID {$tenant->id}\n";
}

// ── 3. Customization ──────────────────────────────────────
DB::table('tenant_customization')->updateOrInsert(
    ['tenant_id' => $tenant->id],
    [
        'hero_main_filename'      => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=1200&q=80',
        'hero_secondary_filename' => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=1200&q=80',
        'hero_tertiary_filename'  => 'https://images.unsplash.com/photo-1607748862156-7c548e7e98f4?w=1200&q=80',
        'about_text'              => 'Somos tu tienda de confianza para productos de belleza y cuidado personal. Marcas internacionales a precios accesibles, entrega a domicilio en Caracas.',
        'payment_methods'         => json_encode([
            'global'   => ['pagoMovil', 'zelle', 'cash', 'zinli'],
            'currency' => ['usd'],
        ]),
        'social_networks' => json_encode([
            'instagram' => '@bellestore.ve',
            'tiktok'    => '@bellestore.ve',
        ]),
        'updated_at' => now(),
        'created_at' => now(),
    ]
);

// ── 4. Categorías ─────────────────────────────────────────
$cats = ['Maquillaje', 'Skincare', 'Fragancias', 'Cabello', 'Uñas'];
$catIds = [];
foreach ($cats as $catName) {
    $id = DB::table('cat_categories')->insertGetId([
        'tenant_id'  => $tenant->id,
        'name'       => $catName,
        'parent_id'  => null,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    $catIds[$catName] = $id;
}
echo "Categorías creadas: " . implode(', ', $cats) . "\n";

// ── 5. Productos ──────────────────────────────────────────
$products = [
    // MAQUILLAJE
    ['name' => 'Base de Maquillaje Mate', 'cat' => 'Maquillaje', 'price' => 18.00, 'badge' => 'popular',
     'desc' => 'Cobertura total, acabado mate de larga duración. Disponible en 12 tonos.',
     'img'  => 'https://images.unsplash.com/photo-1631214499916-cdb7af90e3ea?w=600&q=80',
     'featured' => true],

    ['name' => 'Labial Líquido Mate', 'cat' => 'Maquillaje', 'price' => 9.50, 'badge' => 'popular',
     'desc' => 'Pigmentación intensa, fórmula hidratante. 20 tonos disponibles.',
     'img'  => 'https://images.unsplash.com/photo-1586495777744-4e6232e2c1f3?w=600&q=80',
     'featured' => true],

    ['name' => 'Paleta de Sombras Sunset', 'cat' => 'Maquillaje', 'price' => 24.00, 'badge' => 'nuevo',
     'desc' => '18 tonos cálidos altamente pigmentados. Acabados matte, shimmer y glitter.',
     'img'  => 'https://images.unsplash.com/photo-1512496015851-a90fb38ba796?w=600&q=80',
     'featured' => false],

    ['name' => 'Máscara de Pestañas Volumen', 'cat' => 'Maquillaje', 'price' => 12.00, 'badge' => 'popular',
     'desc' => 'Fórmula buildable para pestañas voluminosas y alargadas. Resistente al agua.',
     'img'  => 'https://images.unsplash.com/photo-1583241800698-e8ab01830a66?w=600&q=80',
     'featured' => false],

    ['name' => 'Contorno y Bronzer Duo', 'cat' => 'Maquillaje', 'price' => 19.00, 'badge' => 'promo',
     'desc' => 'Define y broncea en un solo paso. Fórmula en polvo ultra suave.',
     'img'  => 'https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?w=600&q=80',
     'featured' => false],

    // SKINCARE
    ['name' => 'Sérum Vitamina C 20%', 'cat' => 'Skincare', 'price' => 28.00, 'badge' => 'popular',
     'desc' => 'Ilumina, unifica el tono y reduce manchas. Fórmula estabilizada con ácido ferúlico.',
     'img'  => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=600&q=80',
     'featured' => true],

    ['name' => 'Hidratante FPS 50 Oil-Free', 'cat' => 'Skincare', 'price' => 22.00, 'badge' => 'nuevo',
     'desc' => 'Hidratación ligera con protección solar. Ideal para pieles mixtas y grasas.',
     'img'  => 'https://images.unsplash.com/photo-1556228578-8c89e6adf883?w=600&q=80',
     'featured' => false],

    ['name' => 'Retinol Noche 0.5%', 'cat' => 'Skincare', 'price' => 32.00, 'badge' => 'destacado',
     'desc' => 'Reduce líneas de expresión y estimula la renovación celular mientras duermes.',
     'img'  => 'https://images.unsplash.com/photo-1611080626919-7cf5a9dbab12?w=600&q=80',
     'featured' => true],

    ['name' => 'Limpiador Facial Micellar', 'cat' => 'Skincare', 'price' => 14.00, 'badge' => null,
     'desc' => 'Elimina maquillaje y suciedad sin resecar. Fórmula suave para todo tipo de piel.',
     'img'  => 'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?w=600&q=80',
     'featured' => false],

    // FRAGANCIAS
    ['name' => 'Perfume Rose Gold 100ml', 'cat' => 'Fragancias', 'price' => 45.00, 'badge' => 'popular',
     'desc' => 'Notas florales de rosa, jazmín y sándalo. Larga duración 8+ horas.',
     'img'  => 'https://images.unsplash.com/photo-1541643600914-78b084683702?w=600&q=80',
     'featured' => true],

    ['name' => 'Body Mist Fresh Vanilla', 'cat' => 'Fragancias', 'price' => 15.00, 'badge' => 'promo',
     'desc' => 'Fragancia fresca y dulce para el cuerpo. 250ml. Perfecta para uso diario.',
     'img'  => 'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?w=600&q=80',
     'featured' => false],

    // CABELLO
    ['name' => 'Mascarilla Keratina Pro', 'cat' => 'Cabello', 'price' => 20.00, 'badge' => 'popular',
     'desc' => 'Tratamiento intensivo para cabello dañado. Alisa, hidrata y aporta brillo.',
     'img'  => 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=600&q=80',
     'featured' => false],

    ['name' => 'Aceite de Argán Marroquí', 'cat' => 'Cabello', 'price' => 17.00, 'badge' => 'nuevo',
     'desc' => 'Serum de argán puro para controlar el frizz y dar brillo espejado.',
     'img'  => 'https://images.unsplash.com/photo-1526045612212-70caf35c14df?w=600&q=80',
     'featured' => false],

    // UÑAS
    ['name' => 'Set Nail Art Completo', 'cat' => 'Uñas', 'price' => 35.00, 'badge' => 'nuevo',
     'desc' => '24 esmaltes + base + top coat + herramientas. Todo para hacer uñas en casa.',
     'img'  => 'https://images.unsplash.com/photo-1604654894610-df63bc536371?w=600&q=80',
     'featured' => true],

    ['name' => 'Esmalte Gel UV Semi-permanente', 'cat' => 'Uñas', 'price' => 8.00, 'badge' => 'popular',
     'desc' => 'Duración 3 semanas. 50+ colores disponibles. Requiere lámpara UV.',
     'img'  => 'https://images.unsplash.com/photo-1604654894610-df63bc536371?w=600&q=80',
     'featured' => false],
];

$pos = 1;
foreach ($products as $pd) {
    $product = Product::create([
        'tenant_id'     => $tenant->id,
        'name'          => $pd['name'],
        'description'   => $pd['desc'],
        'price_usd'     => $pd['price'],
        'category_name' => $pd['cat'],
        'badge'         => $pd['badge'],
        'is_active'     => true,
        'is_featured'   => $pd['featured'],
        'image_url'     => $pd['img'],
        'position'      => $pos++,
    ]);
    echo "✓ {$product->name}\n";
}

echo "\n✅ Belle Store lista — tenant_id: {$tenant->id}\n";
echo "URL: http://127.0.0.1:8000/bellestore\n";