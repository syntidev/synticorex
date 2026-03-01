<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use App\Models\Plan;
use App\Models\Tenant;
use App\Models\Product;
use App\Models\Service;
use App\Models\TenantCustomization;
use App\Services\ProductImageGeneratorService;
use App\Services\ServiceImageGeneratorService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Remove stale products/services from previous seeder runs.
     */
    private function cleanStale(int $tenantId, array $productNames, array $serviceNames): void
    {
        Product::where('tenant_id', $tenantId)
            ->whereNotIn('name', $productNames)
            ->delete();

        Service::where('tenant_id', $tenantId)
            ->whereNotIn('name', $serviceNames)
            ->delete();
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or retrieve test user
        $user = User::firstOrCreate(
            ['email' => 'admin@demo.local'],
            [
                'name' => 'Admin Demo',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        // Get plans
        $plans = Plan::all();
        if ($plans->isEmpty()) {
            $this->command->warn('No plans found. Create plans first.');
            return;
        }

        // Initialize service image generator
        $serviceGenerator = new ServiceImageGeneratorService();

        // ═══════════════════════════════════════════════════════════════════════════════
        // TENANT 1: TechStart Venezuela (Plan 1 - Oportunidad)
        // ═══════════════════════════════════════════════════════════════════════════════
        $techStart = Tenant::updateOrCreate(
            ['subdomain' => 'techstart'],
            [
                'user_id' => $user->id,
                'plan_id' => $plans->first()?->id,
                'business_name' => 'TechStart Venezuela',
                'business_segment' => 'Tecnología',
                'description' => 'Consultoría tecnológica para pequeñas y medianas empresas venezolanas',
                'slogan' => 'Soluciones digitales para tu empresa',
                'email' => 'info@techstart.ve',
                'phone' => '+58 212 1234567',
                'whatsapp_sales' => '+58 412 555 0101',
                'whatsapp_support' => '+58 412 555 0101',
                'address' => 'Avenida Libertador 456, Piso 3',
                'city' => 'Caracas',
                'country' => 'Venezuela',
                'domain_verified' => true,
                'status' => 'active',
                'edit_pin' => Hash::make('1234'),
                'base_domain' => 'synticorex.test',
                'is_open' => true,
                'business_hours' => json_encode([
                    'monday' => ['open' => '09:00', 'close' => '18:00'],
                    'tuesday' => ['open' => '09:00', 'close' => '18:00'],
                    'wednesday' => ['open' => '09:00', 'close' => '18:00'],
                    'thursday' => ['open' => '09:00', 'close' => '18:00'],
                    'friday' => ['open' => '09:00', 'close' => '18:00'],
                    'saturday' => ['open' => '10:00', 'close' => '14:00'],
                    'sunday' => null,
                ]),
                'settings' => [
                    'engine_settings' => [
                        'currency' => [
                            'auto_update' => true,
                            'exchange_rate' => 36.50,
                            'euro_rate' => 495.60,
                            'source' => 'dolarapi',
                            'display' => [
                                'saved_display_mode' => 'both_toggle',
                                'show_reference' => true,
                                'show_bolivares' => true,
                                'show_euro' => false,
                                'hide_price' => false,
                                'has_toggle' => true,
                                'symbols' => ['reference' => 'REF', 'bolivares' => 'Bs.'],
                            ],
                        ],
                    ],
                ],
            ]
        );

        // Products for TechStart (6 products)
        $techStartProducts = [
            ['name' => 'Soporte Técnico Básico', 'desc' => 'Asistencia remota y presencial para tu equipo', 'price' => 49.00, 'position' => 1, 'image_url' => 'https://images.unsplash.com/photo-1531482615713-2afd69097998?w=600&h=400&fit=crop'],
            ['name' => 'Mantenimiento Mensual', 'desc' => 'Mantenimiento preventivo de PC, laptops y redes', 'price' => 89.00, 'position' => 2, 'image_url' => 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?w=600&h=400&fit=crop'],
            ['name' => 'Instalación de Software', 'desc' => 'Instalación de licencias y configuración profesional', 'price' => 35.00, 'position' => 3, 'image_url' => 'https://images.unsplash.com/photo-1629654297299-c8506221ca97?w=600&h=400&fit=crop'],
            ['name' => 'Backup en la Nube', 'desc' => 'Respaldo automático diario de tus datos importantes', 'price' => 29.00, 'position' => 4, 'image_url' => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?w=600&h=400&fit=crop'],
            ['name' => 'Capacitación Digital', 'desc' => 'Entrenamiento en Office, email y redes sociales', 'price' => 65.00, 'position' => 5, 'image_url' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=600&h=400&fit=crop'],
            ['name' => 'Auditoría Digital', 'desc' => 'Diagnóstico completo de tu infraestructura tecnológica', 'price' => 120.00, 'position' => 6, 'image_url' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&h=400&fit=crop'],
        ];

        foreach ($techStartProducts as $prod) {
            Product::updateOrCreate(
                ['tenant_id' => $techStart->id, 'name' => $prod['name']],
                [
                    'description' => $prod['desc'],
                    'price_usd' => $prod['price'],
                    'image_url' => $prod['image_url'] ?? null,
                    'position' => $prod['position'],
                    'is_active' => true,
                ]
            );
        }

        // Services for TechStart (3 services)
        $techStartServices = [
            ['name' => 'Diagnóstico Gratuito', 'desc' => 'Evaluamos tu situación tecnológica sin costo', 'position' => 1],
            ['name' => 'Soporte Remoto 24/7', 'desc' => 'Te ayudamos donde estés, cuando lo necesites', 'position' => 2],
            ['name' => 'Instalación y Configuración', 'desc' => 'Todo listo para que solo te preocupes de tu negocio', 'position' => 3],
        ];

        foreach ($techStartServices as $svc) {
            Service::updateOrCreate(
                ['tenant_id' => $techStart->id, 'name' => $svc['name']],
                [
                    'description' => $svc['desc'],
                    'position' => $svc['position'],
                    'image_filename' => $serviceGenerator->generateServiceImage($techStart->id, $svc['name'], 'Tecnología'),
                    'is_active' => true,
                ]
            );
        }

        // Cleanup stale data from previous seeds
        $this->cleanStale(
            $techStart->id,
            array_column($techStartProducts, 'name'),
            array_column($techStartServices, 'name')
        );

        // ═══════════════════════════════════════════════════════════════════════════════
        // TENANT 2: Boutique Eleganza (Plan 2 - Crecimiento)
        // ═══════════════════════════════════════════════════════════════════════════════
        $retailCo = Tenant::updateOrCreate(
            ['subdomain' => 'retailco'],
            [
                'user_id' => $user->id,
                'plan_id' => $plans->count() > 1 ? $plans->skip(1)->first()?->id : $plans->first()?->id,
                'business_name' => 'Boutique Eleganza',
                'business_segment' => 'Comercio',
                'description' => 'Moda y accesorios para la mujer venezolana moderna',
                'slogan' => 'Tu estilo, tu identidad',
                'email' => 'ventas@eleganza.ve',
                'phone' => '+58 414 2223334',
                'whatsapp_sales' => '+58 414 555 0202',
                'whatsapp_support' => '+58 414 555 0202',
                'address' => 'Centro Comercial Las Mercedes, Local 201',
                'city' => 'Valencia',

                'country' => 'Venezuela',
                'domain_verified' => true,
                'status' => 'active',
                'edit_pin' => Hash::make('1234'),
                'base_domain' => 'synticorex.test',
                'is_open' => true,
                'business_hours' => json_encode([
                    'monday' => ['open' => '10:00', 'close' => '20:00'],
                    'tuesday' => ['open' => '10:00', 'close' => '20:00'],
                    'wednesday' => ['open' => '10:00', 'close' => '20:00'],
                    'thursday' => ['open' => '10:00', 'close' => '20:00'],
                    'friday' => ['open' => '10:00', 'close' => '21:00'],
                    'saturday' => ['open' => '09:00', 'close' => '21:00'],
                    'sunday' => ['open' => '11:00', 'close' => '19:00'],
                ]),
                'settings' => [
                    'engine_settings' => [
                        'currency' => [
                            'auto_update' => true,
                            'exchange_rate' => 36.50,
                            'euro_rate' => 495.60,
                            'source' => 'dolarapi',
                            'display' => [
                                'saved_display_mode' => 'both_toggle',
                                'show_reference' => true,
                                'show_bolivares' => true,
                                'show_euro' => false,
                                'hide_price' => false,
                                'has_toggle' => true,
                                'symbols' => ['reference' => 'REF', 'bolivares' => 'Bs.'],
                            ],
                        ],
                    ],
                ],
            ]
        );

        // Products for RetailCo (12 products)
        $retailCoProducts = [
            ['name' => 'Vestido Casual', 'price' => 35.00, 'position' => 1, 'image_url' => 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=600&h=400&fit=crop'],
            ['name' => 'Blusa de Gasa', 'price' => 22.00, 'position' => 2, 'image_url' => 'https://images.unsplash.com/photo-1564257631407-4deb1f99d992?w=600&h=400&fit=crop'],
            ['name' => 'Jeans Premium', 'price' => 45.00, 'position' => 3, 'image_url' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=600&h=400&fit=crop'],
            ['name' => 'Zapatos de Tacón', 'price' => 55.00, 'position' => 4, 'image_url' => 'https://images.unsplash.com/photo-1543163521-1bf539c55dd2?w=600&h=400&fit=crop'],
            ['name' => 'Cartera de Cuero', 'price' => 65.00, 'position' => 5, 'image_url' => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=600&h=400&fit=crop'],
            ['name' => 'Lentes de Sol', 'price' => 28.00, 'position' => 6, 'image_url' => 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=600&h=400&fit=crop'],
            ['name' => 'Vestido de Noche', 'price' => 89.00, 'position' => 7, 'image_url' => 'https://images.unsplash.com/photo-1566174053879-31528523f8ae?w=600&h=400&fit=crop'],
            ['name' => 'Blazer Ejecutivo', 'price' => 75.00, 'position' => 8, 'image_url' => 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=600&h=400&fit=crop'],
            ['name' => 'Falda Midi', 'price' => 38.00, 'position' => 9, 'image_url' => 'https://images.unsplash.com/photo-1583496661160-fb5886a0aaaa?w=600&h=400&fit=crop'],
            ['name' => 'Sandalias Planas', 'price' => 32.00, 'position' => 10, 'image_url' => 'https://images.unsplash.com/photo-1603487742131-4160ec999306?w=600&h=400&fit=crop'],
            ['name' => 'Collar Dorado', 'price' => 18.00, 'position' => 11, 'image_url' => 'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=600&h=400&fit=crop'],
            ['name' => 'Pañuelo de Seda', 'price' => 15.00, 'position' => 12, 'image_url' => 'https://images.unsplash.com/photo-1601924994987-69e26d50dc26?w=600&h=400&fit=crop'],
        ];

        foreach ($retailCoProducts as $prod) {
            Product::updateOrCreate(
                ['tenant_id' => $retailCo->id, 'name' => $prod['name']],
                [
                    'description' => $prod['name'] . ' de alta calidad con estilo venezolano moderno.',
                    'price_usd' => $prod['price'],
                    'image_url' => $prod['image_url'] ?? null,
                    'position' => $prod['position'],
                    'is_active' => true,
                ]
            );
        }

        // Services for RetailCo (3 services)
        $retailCoServices = [
            ['name' => 'Asesoría de Imagen', 'desc' => 'Te ayudamos a encontrar tu estilo personal', 'position' => 1],
            ['name' => 'Apartado con 30%', 'desc' => 'Reserva tu prenda favorita con descuento especial', 'position' => 2],
            ['name' => 'Envío a Domicilio', 'desc' => 'Entrega rápida en Caracas y Valencia', 'position' => 3],
        ];

        foreach ($retailCoServices as $svc) {
            Service::updateOrCreate(
                ['tenant_id' => $retailCo->id, 'name' => $svc['name']],
                [
                    'description' => $svc['desc'],
                    'position' => $svc['position'],
                    'image_filename' => $serviceGenerator->generateServiceImage($retailCo->id, $svc['name'], 'Comercio'),
                    'is_active' => true,
                ]
            );
        }

        // Cleanup stale data from previous seeds
        $this->cleanStale(
            $retailCo->id,
            array_column($retailCoProducts, 'name'),
            array_column($retailCoServices, 'name')
        );

        // ═══════════════════════════════════════════════════════════════════════════════
        // TENANT 3: ServicePro Empresarial (Plan 3 - Visión)
        // ═══════════════════════════════════════════════════════════════════════════════
        $servicePro = Tenant::updateOrCreate(
            ['subdomain' => 'servicepro'],
            [
                'user_id' => $user->id,
                'plan_id' => $plans->count() > 2 ? $plans->skip(2)->first()?->id : $plans->first()?->id,
                'business_name' => 'ServicePro Empresarial',
                'business_segment' => 'Consultoría',
                'description' => 'Optimizamos tu negocio, maximizamos tus resultados - Servicios empresariales a medida',
                'slogan' => 'Optimizamos tu negocio, maximizamos tus resultados',
                'email' => 'contacto@servicepro.ve',
                'phone' => '+58 261 555 0303',
                'whatsapp_sales' => '+58 416 555 0303',
                'whatsapp_support' => '+58 416 555 0303',
                'address' => 'Centro Comercial Maracaibo, Piso 12',
                'city' => 'Maracaibo',
                'country' => 'Venezuela',
                'domain_verified' => true,
                'status' => 'active',
                'edit_pin' => Hash::make('1234'),
                'base_domain' => 'synticorex.test',
                'is_open' => true,
                'business_hours' => json_encode([
                    'monday' => ['open' => '08:00', 'close' => '18:00'],
                    'tuesday' => ['open' => '08:00', 'close' => '18:00'],
                    'wednesday' => ['open' => '08:00', 'close' => '18:00'],
                    'thursday' => ['open' => '08:00', 'close' => '18:00'],
                    'friday' => ['open' => '08:00', 'close' => '17:00'],
                    'saturday' => null,
                    'sunday' => null,
                ]),
                'settings' => [
                    'engine_settings' => [
                        'currency' => [
                            'auto_update' => true,
                            'exchange_rate' => 36.50,
                            'euro_rate' => 495.60,
                            'source' => 'dolarapi',
                            'display' => [
                                'saved_display_mode' => 'both_toggle',
                                'show_reference' => true,
                                'show_bolivares' => true,
                                'show_euro' => false,
                                'hide_price' => false,
                                'has_toggle' => true,
                                'symbols' => ['reference' => 'REF', 'bolivares' => 'Bs.'],
                            ],
                        ],
                    ],
                ],
            ]
        );

        // Products for ServicePro (18 products, $165-$599)
        $serviceProProducts = [
            ['name' => 'Consultoría Estratégica', 'price' => 299.00, 'position' => 1, 'image_url' => 'https://images.unsplash.com/photo-1553877522-43269d4ea984?w=600&h=400&fit=crop'],
            ['name' => 'Auditoría Financiera', 'price' => 450.00, 'position' => 2, 'image_url' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=600&h=400&fit=crop'],
            ['name' => 'Plan Marketing', 'price' => 199.00, 'position' => 3, 'image_url' => 'https://images.unsplash.com/photo-1533750349088-cd871a92f312?w=600&h=400&fit=crop'],
            ['name' => 'Gestión RRHH', 'price' => 249.00, 'position' => 4, 'image_url' => 'https://images.unsplash.com/photo-1521737711867-e3b97375f902?w=600&h=400&fit=crop'],
            ['name' => 'Asesoría Legal', 'price' => 350.00, 'position' => 5, 'image_url' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?w=600&h=400&fit=crop'],
            ['name' => 'Contabilidad', 'price' => 180.00, 'position' => 6, 'image_url' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=600&h=400&fit=crop'],
            ['name' => 'Plan Expansión', 'price' => 599.00, 'position' => 7, 'image_url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=600&h=400&fit=crop'],
            ['name' => 'Estudio Mercado', 'price' => 380.00, 'position' => 8, 'image_url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=600&h=400&fit=crop'],
            ['name' => 'Optimización Procesos', 'price' => 275.00, 'position' => 9, 'image_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&h=400&fit=crop'],
            ['name' => 'Capacitación Gerencial', 'price' => 220.00, 'position' => 10, 'image_url' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=600&h=400&fit=crop'],
            ['name' => 'Gestión Logística', 'price' => 195.00, 'position' => 11, 'image_url' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=600&h=400&fit=crop'],
            ['name' => 'Asesoría Tributaria', 'price' => 165.00, 'position' => 12, 'image_url' => 'https://images.unsplash.com/photo-1450101499163-c8848e968838?w=600&h=400&fit=crop'],
            ['name' => 'Plan Ventas', 'price' => 240.00, 'position' => 13, 'image_url' => 'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?w=600&h=400&fit=crop'],
            ['name' => 'Imagen Corporativa', 'price' => 320.00, 'position' => 14, 'image_url' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=600&h=400&fit=crop'],
            ['name' => 'Transformación Digital', 'price' => 499.00, 'position' => 15, 'image_url' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=600&h=400&fit=crop'],
            ['name' => 'Gestión Contratos', 'price' => 285.00, 'position' => 16, 'image_url' => 'https://images.unsplash.com/photo-1450101499163-c8848e968838?w=600&h=400&fit=crop'],
            ['name' => 'Auditoría Procesos', 'price' => 410.00, 'position' => 17, 'image_url' => 'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?w=600&h=400&fit=crop'],
            ['name' => 'Consultoría Exportación', 'price' => 520.00, 'position' => 18, 'image_url' => 'https://images.unsplash.com/photo-1494412574643-ff11b0a5eb95?w=600&h=400&fit=crop'],
        ];

        foreach ($serviceProProducts as $prod) {
            Product::updateOrCreate(
                ['tenant_id' => $servicePro->id, 'name' => $prod['name']],
                [
                    'description' => $prod['name'] . ' - Solución profesional para el crecimiento de tu empresa.',
                    'price_usd' => $prod['price'],
                    'image_url' => $prod['image_url'] ?? null,
                    'position' => $prod['position'],
                    'is_active' => true,
                ]
            );
        }

        // Services for ServicePro (4 services)
        $serviceProServices = [
            ['name' => 'Diagnóstico Empresarial', 'desc' => 'Evaluación integral de tu negocio, mapa de oportunidades', 'position' => 1],
            ['name' => 'Plan Estratégico', 'desc' => 'Diseño de estrategia de 3-5 años con métricas claras', 'position' => 2],
            ['name' => 'Implementación', 'desc' => 'Ejecución de plan con seguimiento semanal personalizado', 'position' => 3],
            ['name' => 'Seguimiento Mensual', 'desc' => 'Reuniones mensuales para ajustes y resultados medibles', 'position' => 4],
        ];

        foreach ($serviceProServices as $svc) {
            Service::updateOrCreate(
                ['tenant_id' => $servicePro->id, 'name' => $svc['name']],
                [
                    'description' => $svc['desc'],
                    'position' => $svc['position'],
                    'image_filename' => $serviceGenerator->generateServiceImage($servicePro->id, $svc['name'], 'Consultoría'),
                    'is_active' => true,
                ]
            );
        }

        // Cleanup stale data from previous seeds
        $this->cleanStale(
            $servicePro->id,
            array_column($serviceProProducts, 'name'),
            array_column($serviceProServices, 'name')
        );

        // ═══════════════════════════════════════════════════════════════════════════════
        // TENANT 4: Vitrina Demo (Plan 1 - template SYNTIcat)
        // ═══════════════════════════════════════════════════════════════════════════════
        $vitrina = Tenant::updateOrCreate(
            ['subdomain' => 'vitrina'],
            [
                'user_id'          => $user->id,
                'plan_id'          => $plans->first()?->id,
                'business_name'    => 'Tienda Demo',
                'business_segment' => 'Comercio',
                'description'      => 'Catálogo de productos demo para SYNTIcat',
                'slogan'           => 'Todo lo que necesitas, al mejor precio',
                'email'            => 'demo@vitrina.local',
                'phone'            => '+58 412 000 0001',
                'whatsapp_sales'   => '+584120000001',
                'whatsapp_support' => '+584120000001',
                'address'          => 'Av. Principal 123',
                'city'             => 'Caracas',
                'country'          => 'Venezuela',
                'domain_verified'  => true,
                'status'           => 'active',
                'base_domain'      => 'synticorex.test',
                'is_open'          => true,
                'edit_pin'         => Hash::make('1234'),
                'settings'         => [
                    'engine_settings' => [
                        'template' => 'synticat',
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

        // Products for Vitrina (6 demo products)
        $vitrinaProducts = [
            ['name' => 'Camiseta Básica',    'price' => 12.00, 'position' => 1, 'image_url' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=600&h=400&fit=crop'],
            ['name' => 'Pantalón Casual',    'price' => 25.00, 'position' => 2, 'image_url' => 'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=600&h=400&fit=crop'],
            ['name' => 'Zapatos Deportivos', 'price' => 45.00, 'position' => 3, 'image_url' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&h=400&fit=crop'],
            ['name' => 'Bolso de Mano',      'price' => 30.00, 'position' => 4, 'image_url' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=600&h=400&fit=crop'],
            ['name' => 'Gorra Bordada',      'price' => 10.00, 'position' => 5, 'image_url' => 'https://images.unsplash.com/photo-1588850561407-ed78c334e67a?w=600&h=400&fit=crop'],
            ['name' => 'Reloj Casual',       'price' => 55.00, 'position' => 6, 'image_url' => 'https://images.unsplash.com/photo-1524592094714-0f0654e20314?w=600&h=400&fit=crop'],
        ];

        foreach ($vitrinaProducts as $prod) {
            Product::updateOrCreate(
                ['tenant_id' => $vitrina->id, 'name' => $prod['name']],
                [
                    'description' => $prod['name'] . ' — producto demo para SYNTIcat.',
                    'price_usd'   => $prod['price'],
                    'image_url'   => $prod['image_url'] ?? null,
                    'position'    => $prod['position'],
                    'is_active'   => true,
                ]
            );
        }

        // Cleanup stale vitrina products
        Product::where('tenant_id', $vitrina->id)
            ->whereNotIn('name', array_column($vitrinaProducts, 'name'))
            ->delete();

        // ═════════════════════════════════════════════════════════════════════════════════
        // Summary
        // ═════════════════════════════════════════════════════════════════════════════════
        $this->command->info("\n✅ Demo Data Seed Completed Successfully!");
        $this->command->info("\n📊 Tenants Created:");
        $this->command->info("   ✓ TechStart Venezuela (Plan 1) - 6 products, 3 services");
        $this->command->info("   ✓ Boutique Eleganza (Plan 2) - 12 products, 3 services");
        $this->command->info("   ✓ ServicePro Empresarial (Plan 3) - 18 products, 4 services");
        $this->command->info("   ✓ Vitrina Demo (Plan 1, SYNTIcat) - 6 products");
        $this->command->info("\n🔑 Test Credentials:");
        $this->command->info("   Email: {$user->email}");
        $this->command->info("   Password: password123");
        $this->command->info("   PIN: 1234");
        $this->command->info("\n🌐 Access URLs:");
        $this->command->info("   http://techstart.synticorex.test");
        $this->command->info("   http://retailco.synticorex.test");
        $this->command->info("   http://servicepro.synticorex.test");
        $this->command->info("   http://vitrina.synticorex.test  ← SYNTIcat template");
    }
}
