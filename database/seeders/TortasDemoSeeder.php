<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Tenant;
use App\Models\TenantCustomization;
use App\Services\TenantBootstrapFood;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TortasDemoSeeder extends Seeder
{
    public function run(): void
    {
        $plan = Plan::where('slug', 'food-anual')->first();

        if (! $plan) {
            $this->command->error('Plan food-anual no encontrado. Ejecuta PlansSeeder primero.');
            return;
        }

        // ── Tenant ─────────────────────────────────────────────────────────
        $tenant = Tenant::updateOrCreate(
            ['subdomain' => 'tortas'],
            [
                'user_id'           => 1,
                'plan_id'           => $plan->id,
                'business_name'     => 'Pastelería Dulce Rosa',
                'business_segment'  => 'Pastelería',
                'description'       => 'Tortas artesanales, postres y café para todos los momentos especiales',
                'slogan'            => 'Endulzamos tu vida',
                'email'             => 'hola@dulcerosa.ve',
                'phone'             => '+58 424 1234567',
                'whatsapp_sales'    => '584241234567',
                'whatsapp_support'  => '584241234567',
                'address'           => 'Av. Bolívar, Local 10, C.C. El Paseo',
                'city'              => 'Barquisimeto',
                'country'           => 'Venezuela',
                'domain_verified'   => true,
                'status'            => 'active',
                'edit_pin'          => Hash::make('1234'),
                'base_domain'       => 'synticorex.test',
                'is_open'           => true,
                'business_hours'    => json_encode([
                    'monday'    => ['open' => '08:00', 'close' => '19:00'],
                    'tuesday'   => ['open' => '08:00', 'close' => '19:00'],
                    'wednesday' => ['open' => '08:00', 'close' => '19:00'],
                    'thursday'  => ['open' => '08:00', 'close' => '19:00'],
                    'friday'    => ['open' => '08:00', 'close' => '20:00'],
                    'saturday'  => ['open' => '08:00', 'close' => '20:00'],
                    'sunday'    => ['open' => '09:00', 'close' => '15:00'],
                ]),
                'settings' => [
                    'engine_settings' => [
                        'template' => 'food',
                        'currency' => [
                            'auto_update'   => true,
                            'exchange_rate' => 36.50,
                            'euro_rate'     => 495.60,
                            'source'        => 'dolarapi',
                            'display'       => [
                                'saved_display_mode' => 'both_toggle',
                                'show_reference'     => true,
                                'show_bolivares'     => true,
                                'show_euro'          => false,
                                'hide_price'         => false,
                                'has_toggle'         => true,
                                'symbols'            => ['reference' => 'REF', 'bolivares' => 'Bs.'],
                            ],
                        ],
                    ],
                ],
            ]
        );

        // ── Customization ──────────────────────────────────────────────────
        TenantCustomization::updateOrCreate(
            ['tenant_id' => $tenant->id],
            [
                'content_blocks' => [
                    'hero' => [
                        'title'    => 'Pastelería Dulce Rosa',
                        'cta_text' => 'Ver menú',
                    ],
                ],
                'hero_layout' => 'gradient',
            ]
        );

        // ── Bootstrap storage ──────────────────────────────────────────────
        TenantBootstrapFood::bootstrap($tenant);

        // ── Menú completo ──────────────────────────────────────────────────
        $menu = [
            'tenant_id'  => $tenant->id,
            'blueprint'  => 'food',
            'categories' => [

                // 1. Tortas y Pasteles
                [
                    'id'     => 'cat-TORP',
                    'nombre' => 'Tortas y Pasteles',
                    'foto'   => null,
                    'activo' => true,
                    'items'  => [
                        ['id' => 'item-T001', 'nombre' => 'Torta de Chocolate', 'precio' => 18.00, 'descripcion' => 'Húmeda, con ganache de chocolate negro y cobertura de crema batida.', 'image_path' => 'https://loremflickr.com/400/300/chocolate,cake/all?lock=1',    'badge' => 'popular', 'is_featured' => true,  'activo' => true],
                        ['id' => 'item-T002', 'nombre' => 'Torta de Fresas',    'precio' => 16.00, 'descripcion' => 'Bizcocho suave con crema pastelera y fresas frescas de temporada.',   'image_path' => 'https://loremflickr.com/400/300/strawberry,cake/all?lock=2',    'badge' => null,      'is_featured' => false, 'activo' => true],
                        ['id' => 'item-T003', 'nombre' => 'Torta Tres Leches',  'precio' => 15.00, 'descripcion' => 'Empapada en tres leches, cubierta con merengue tostado.',              'image_path' => 'https://loremflickr.com/400/300/cream,cake/all?lock=3',         'badge' => 'popular', 'is_featured' => true,  'activo' => true],
                        ['id' => 'item-T004', 'nombre' => 'Torta Red Velvet',   'precio' => 17.00, 'descripcion' => 'Capas de terciopelo rojo con crema de queso Philadelphia.',             'image_path' => 'https://loremflickr.com/400/300/redvelvet,cake/all?lock=4',     'badge' => null,      'is_featured' => false, 'activo' => true],
                        ['id' => 'item-T005', 'nombre' => 'Cheesecake NY',       'precio' => 14.00, 'descripcion' => 'Cremosa base de queso crema, con salsa de frutos rojos.',              'image_path' => 'https://loremflickr.com/400/300/cheesecake/all?lock=5',         'badge' => 'nuevo',   'is_featured' => false, 'activo' => true],
                        ['id' => 'item-T006', 'nombre' => 'Tiramisú',            'precio' => 13.00, 'descripcion' => 'Capas de savoiardi, mascarpone y espresso. Receta italiana auténtica.', 'image_path' => 'https://loremflickr.com/400/300/tiramisu/all?lock=6',          'badge' => null,      'is_featured' => false, 'activo' => true],
                        ['id' => 'item-T007', 'nombre' => 'Torta de Zanahoria', 'precio' => 12.00, 'descripcion' => 'Esponjosa con zanahoria rallada, nueces y cobertura de cream cheese.',  'image_path' => 'https://loremflickr.com/400/300/carrot,cake/all?lock=7',        'badge' => null,      'is_featured' => false, 'activo' => true],
                        ['id' => 'item-T008', 'nombre' => 'Torta Selva Negra',  'precio' => 17.00, 'descripcion' => 'Chocolate, cerezas y crema en el famoso clásico alemán.',               'image_path' => 'https://loremflickr.com/400/300/blackforest,cake/all?lock=8',   'badge' => null,      'is_featured' => false, 'activo' => true],
                    ],
                ],

                // 2. Postres Individuales
                [
                    'id'     => 'cat-POST',
                    'nombre' => 'Postres Individuales',
                    'foto'   => null,
                    'activo' => true,
                    'items'  => [
                        ['id' => 'item-P001', 'nombre' => 'Brownie',               'precio' => 4.50, 'descripcion' => 'Denso y fudgy, con nueces y chispas de chocolate extra.',                 'image_path' => 'https://loremflickr.com/400/300/brownie,chocolate/all?lock=11', 'badge' => 'popular', 'is_featured' => true,  'activo' => true],
                        ['id' => 'item-P002', 'nombre' => 'Crème Brûlée',          'precio' => 7.00, 'descripcion' => 'Natilla de vainilla con costra de azúcar caramelizada al momento.',       'image_path' => 'https://loremflickr.com/400/300/creme-brulee/all?lock=12',      'badge' => null,      'is_featured' => false, 'activo' => true],
                        ['id' => 'item-P003', 'nombre' => 'Mousse de Maracuyá',    'precio' => 5.50, 'descripcion' => 'Ligero y ácido, con coulis tropical y ralladura de limón.',               'image_path' => 'https://loremflickr.com/400/300/mousse,dessert/all?lock=13',    'badge' => 'nuevo',   'is_featured' => false, 'activo' => true],
                        ['id' => 'item-P004', 'nombre' => 'Flan',                  'precio' => 4.00, 'descripcion' => 'Clásico de leche condensada con caramelo dorado.',                        'image_path' => 'https://loremflickr.com/400/300/flan,caramel/all?lock=14',     'badge' => null,      'is_featured' => false, 'activo' => true],
                        ['id' => 'item-P005', 'nombre' => 'Panna Cotta',           'precio' => 5.00, 'descripcion' => 'Cremosa con salsa de frutos del bosque, textura sedosa.',                 'image_path' => 'https://loremflickr.com/400/300/panna-cotta/all?lock=15',       'badge' => null,      'is_featured' => false, 'activo' => true],
                        ['id' => 'item-P006', 'nombre' => 'Profiteroles',          'precio' => 6.00, 'descripcion' => 'Tres bolitas de choux rellenas de crema y bañadas en chocolate caliente.', 'image_path' => 'https://loremflickr.com/400/300/profiteroles/all?lock=16',     'badge' => null,      'is_featured' => false, 'activo' => true],
                        ['id' => 'item-P007', 'nombre' => 'Éclair de Chocolate',   'precio' => 5.00, 'descripcion' => 'Masa choux alargada con crema de chocolate y glaseado brillante.',        'image_path' => 'https://loremflickr.com/400/300/eclair,chocolate/all?lock=17',  'badge' => null,      'is_featured' => false, 'activo' => true],
                        ['id' => 'item-P008', 'nombre' => 'Suspiro',               'precio' => 3.00, 'descripcion' => 'Merengue horneado, crujiente por fuera y suave por dentro.',               'image_path' => 'https://loremflickr.com/400/300/meringue/all?lock=18',          'badge' => null,      'is_featured' => false, 'activo' => true],
                    ],
                ],

                // 3. Sándwiches
                [
                    'id'     => 'cat-SAND',
                    'nombre' => 'Sándwiches',
                    'foto'   => null,
                    'activo' => true,
                    'items'  => [
                        ['id' => 'item-S001', 'nombre' => 'Club Sándwich',          'precio' => 8.00, 'descripcion' => 'Tres pisos con pollo, jamón, lechuga, tomate y mayonesa.',           'image_path' => 'https://loremflickr.com/400/300/club-sandwich/all?lock=21',     'badge' => 'popular', 'is_featured' => true,  'activo' => true],
                        ['id' => 'item-S002', 'nombre' => 'Sándwich Caprese',       'precio' => 7.00, 'descripcion' => 'Mozzarella fresca, tomate, albahaca y vinagreta balsámica.',         'image_path' => 'https://loremflickr.com/400/300/caprese,sandwich/all?lock=22',  'badge' => null,      'is_featured' => false, 'activo' => true],
                        ['id' => 'item-S003', 'nombre' => 'Cubano',                 'precio' => 9.00, 'descripcion' => 'Cerdo asado, jamón, queso suizo, pepinillos y mostaza prensado.',   'image_path' => 'https://loremflickr.com/400/300/cuban-sandwich/all?lock=23',    'badge' => null,      'is_featured' => false, 'activo' => true],
                        ['id' => 'item-S004', 'nombre' => 'BLT',                    'precio' => 6.50, 'descripcion' => 'Tocino crocante, lechuga fresca y tomate en pan tostado.',           'image_path' => 'https://loremflickr.com/400/300/blt,sandwich/all?lock=24',      'badge' => null,      'is_featured' => false, 'activo' => true],
                        ['id' => 'item-S005', 'nombre' => 'Croque Monsieur',        'precio' => 8.50, 'descripcion' => 'Jamón y gruyère gratinado con bechamel sobre pain de mie.',         'image_path' => 'https://loremflickr.com/400/300/croque-monsieur/all?lock=25',   'badge' => 'nuevo',   'is_featured' => false, 'activo' => true],
                        ['id' => 'item-S006', 'nombre' => 'Veggie Wrap',            'precio' => 7.00, 'descripcion' => 'Tortilla integral con hummus, vegetales a la parrilla y queso feta.', 'image_path' => 'https://loremflickr.com/400/300/veggie,wrap/all?lock=26',      'badge' => null,      'is_featured' => false, 'activo' => true],
                        ['id' => 'item-S007', 'nombre' => 'Sándwich Pollo Grill',   'precio' => 8.00, 'descripcion' => 'Pechuga a la plancha con aguacate, rúcula y salsa de yogur.',        'image_path' => 'https://loremflickr.com/400/300/chicken,sandwich/all?lock=27',  'badge' => null,      'is_featured' => false, 'activo' => true],
                        ['id' => 'item-S008', 'nombre' => 'Panini de Jamón',        'precio' => 7.50, 'descripcion' => 'Jamón serrano, rúcula y queso brie en ciabatta prensada.',           'image_path' => 'https://loremflickr.com/400/300/panini/all?lock=28',            'badge' => null,      'is_featured' => false, 'activo' => true],
                    ],
                ],

                // 4. Bebidas Frías
                [
                    'id'     => 'cat-BEBI',
                    'nombre' => 'Bebidas Frías',
                    'foto'   => null,
                    'activo' => true,
                    'items'  => [
                        ['id' => 'item-B001', 'nombre' => 'Limonada Natural',      'precio' => 3.00, 'descripcion' => 'Limón exprimido al momento con agua mineral y hojitas de menta.',     'image_path' => 'https://loremflickr.com/400/300/lemonade/all?lock=31',          'badge' => 'popular', 'is_featured' => true,  'activo' => true],
                        ['id' => 'item-B002', 'nombre' => 'Frappé de Chocolate',   'precio' => 5.50, 'descripcion' => 'Café helado, cacao y leche batidos con hielo y crema montada.',       'image_path' => 'https://loremflickr.com/400/300/frappe,coffee/all?lock=32',     'badge' => null,      'is_featured' => false, 'activo' => true],
                        ['id' => 'item-B003', 'nombre' => 'Smoothie de Fresa',     'precio' => 4.50, 'descripcion' => 'Fresas frescas, yogur griego, plátano y miel natural.',               'image_path' => 'https://loremflickr.com/400/300/smoothie,strawberry/all?lock=33', 'badge' => null,    'is_featured' => false, 'activo' => true],
                        ['id' => 'item-B004', 'nombre' => 'Agua de Coco',          'precio' => 3.50, 'descripcion' => 'Coco natural abierto al momento, refrescante y natural.',             'image_path' => 'https://loremflickr.com/400/300/coconut,drink/all?lock=34',     'badge' => null,      'is_featured' => false, 'activo' => true],
                        ['id' => 'item-B005', 'nombre' => 'Granizado de Mango',    'precio' => 3.50, 'descripcion' => 'Mango batido con hielo pilado, limón y un toque de chamoy.',          'image_path' => 'https://loremflickr.com/400/300/mango,drink/all?lock=35',       'badge' => 'nuevo',   'is_featured' => false, 'activo' => true],
                        ['id' => 'item-B006', 'nombre' => 'Té Helado',             'precio' => 3.00, 'descripcion' => 'Té negro frío con limón, endulzado con stevia. Sin preservantes.',    'image_path' => 'https://loremflickr.com/400/300/iced-tea/all?lock=36',          'badge' => null,      'is_featured' => false, 'activo' => true],
                        ['id' => 'item-B007', 'nombre' => 'Milkshake Vainilla',    'precio' => 5.00, 'descripcion' => 'Helado de vainilla, leche y extracto natural batidos en licuadora.',  'image_path' => 'https://loremflickr.com/400/300/milkshake,vanilla/all?lock=37', 'badge' => null,      'is_featured' => false, 'activo' => true],
                        ['id' => 'item-B008', 'nombre' => 'Jugo de Naranja',       'precio' => 3.00, 'descripcion' => 'Naranja natural exprimida al momento, sin conservantes.',              'image_path' => 'https://loremflickr.com/400/300/orange-juice/all?lock=38',      'badge' => null,      'is_featured' => false, 'activo' => true],
                    ],
                ],

                // 5. Bebidas Calientes
                [
                    'id'     => 'cat-CALI',
                    'nombre' => 'Bebidas Calientes',
                    'foto'   => null,
                    'activo' => true,
                    'items'  => [
                        ['id' => 'item-C001', 'nombre' => 'Espresso',             'precio' => 2.50, 'descripcion' => 'Doble extracción en granos arábica tostado medio. Intenso y equilibrado.', 'image_path' => 'https://loremflickr.com/400/300/espresso/all?lock=41',          'badge' => 'popular', 'is_featured' => true,  'activo' => true],
                        ['id' => 'item-C002', 'nombre' => 'Cappuccino',           'precio' => 3.50, 'descripcion' => 'Espresso con leche vaporizada y espuma sedosa. Ración perfecta 180ml.',    'image_path' => 'https://loremflickr.com/400/300/cappuccino/all?lock=42',         'badge' => 'popular', 'is_featured' => true,  'activo' => true],
                        ['id' => 'item-C003', 'nombre' => 'Latte de Vainilla',    'precio' => 4.00, 'descripcion' => 'Espresso suave con leche y sirope casero de vainilla de Madagascar.',       'image_path' => 'https://loremflickr.com/400/300/latte,coffee/all?lock=43',       'badge' => null,      'is_featured' => false, 'activo' => true],
                        ['id' => 'item-C004', 'nombre' => 'Té Verde',             'precio' => 2.50, 'descripcion' => 'Hoja entera Sencha japonés, infusionado a 75°C. Antioxidante natural.',    'image_path' => 'https://loremflickr.com/400/300/green-tea/all?lock=44',          'badge' => null,      'is_featured' => false, 'activo' => true],
                        ['id' => 'item-C005', 'nombre' => 'Chocolate Caliente',   'precio' => 4.00, 'descripcion' => 'Cacao puro venezolano con leche entera, canela y una pizca de sal.',        'image_path' => 'https://loremflickr.com/400/300/hot-chocolate/all?lock=45',      'badge' => 'nuevo',   'is_featured' => false, 'activo' => true],
                        ['id' => 'item-C006', 'nombre' => 'Americano',            'precio' => 3.00, 'descripcion' => 'Espresso diluido en agua caliente. Suave, largo y reconfortante.',          'image_path' => 'https://loremflickr.com/400/300/americano,coffee/all?lock=46',   'badge' => null,      'is_featured' => false, 'activo' => true],
                        ['id' => 'item-C007', 'nombre' => 'Matcha Latte',         'precio' => 5.00, 'descripcion' => 'Té matcha ceremonial japonés con leche de avena y un toque dulce.',         'image_path' => 'https://loremflickr.com/400/300/matcha,latte/all?lock=47',       'badge' => 'nuevo',   'is_featured' => false, 'activo' => true],
                        ['id' => 'item-C008', 'nombre' => 'Chai',                 'precio' => 4.00, 'descripcion' => 'Mezcla de especias: canela, cardamomo, jengibre y clavo en leche caliente.', 'image_path' => 'https://loremflickr.com/400/300/chai,tea/all?lock=48',          'badge' => null,      'is_featured' => false, 'activo' => true],
                    ],
                ],
            ],
            'created_at' => now()->toISOString(),
            'updated_at' => now()->toISOString(),
        ];

        Storage::disk('local')->put(
            "tenants/{$tenant->id}/menu/menu.json",
            json_encode($menu, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        $total = array_sum(array_map(fn ($c) => count($c['items']), $menu['categories']));
        $this->command->info("✅ Tenant 'tortas' (ID {$tenant->id}) listo — 5 categorías, {$total} ítems.");
    }
}
