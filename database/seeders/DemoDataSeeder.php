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
            ['name' => 'Soporte Técnico Básico', 'desc' => 'Asistencia remota y presencial para tu equipo', 'price' => 49.00, 'position' => 1],
            ['name' => 'Mantenimiento Mensual', 'desc' => 'Mantenimiento preventivo de PC, laptops y redes', 'price' => 89.00, 'position' => 2],
            ['name' => 'Instalación de Software', 'desc' => 'Instalación de licencias y configuración profesional', 'price' => 35.00, 'position' => 3],
            ['name' => 'Backup en la Nube', 'desc' => 'Respaldo automático diario de tus datos importantes', 'price' => 29.00, 'position' => 4],
            ['name' => 'Capacitación Digital', 'desc' => 'Entrenamiento en Office, email y redes sociales', 'price' => 65.00, 'position' => 5],
            ['name' => 'Auditoría Digital', 'desc' => 'Diagnóstico completo de tu infraestructura tecnológica', 'price' => 120.00, 'position' => 6],
        ];

        foreach ($techStartProducts as $prod) {
            Product::updateOrCreate(
                ['tenant_id' => $techStart->id, 'name' => $prod['name']],
                [
                    'description' => $prod['desc'],
                    'price_usd' => $prod['price'],
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
            ['name' => 'Vestido Casual', 'price' => 35.00, 'position' => 1],
            ['name' => 'Blusa de Gasa', 'price' => 22.00, 'position' => 2],
            ['name' => 'Jeans Premium', 'price' => 45.00, 'position' => 3],
            ['name' => 'Zapatos de Tacón', 'price' => 55.00, 'position' => 4],
            ['name' => 'Cartera de Cuero', 'price' => 65.00, 'position' => 5],
            ['name' => 'Lentes de Sol', 'price' => 28.00, 'position' => 6],
            ['name' => 'Vestido de Noche', 'price' => 89.00, 'position' => 7],
            ['name' => 'Blazer Ejecutivo', 'price' => 75.00, 'position' => 8],
            ['name' => 'Falda Midi', 'price' => 38.00, 'position' => 9],
            ['name' => 'Sandalias Planas', 'price' => 32.00, 'position' => 10],
            ['name' => 'Collar Dorado', 'price' => 18.00, 'position' => 11],
            ['name' => 'Pañuelo de Seda', 'price' => 15.00, 'position' => 12],
        ];

        foreach ($retailCoProducts as $prod) {
            Product::updateOrCreate(
                ['tenant_id' => $retailCo->id, 'name' => $prod['name']],
                [
                    'description' => $prod['name'] . ' de alta calidad con estilo venezolano moderno.',
                    'price_usd' => $prod['price'],
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
            ['name' => 'Consultoría Estratégica', 'price' => 299.00, 'position' => 1],
            ['name' => 'Auditoría Financiera', 'price' => 450.00, 'position' => 2],
            ['name' => 'Plan Marketing', 'price' => 199.00, 'position' => 3],
            ['name' => 'Gestión RRHH', 'price' => 249.00, 'position' => 4],
            ['name' => 'Asesoría Legal', 'price' => 350.00, 'position' => 5],
            ['name' => 'Contabilidad', 'price' => 180.00, 'position' => 6],
            ['name' => 'Plan Expansión', 'price' => 599.00, 'position' => 7],
            ['name' => 'Estudio Mercado', 'price' => 380.00, 'position' => 8],
            ['name' => 'Optimización Procesos', 'price' => 275.00, 'position' => 9],
            ['name' => 'Capacitación Gerencial', 'price' => 220.00, 'position' => 10],
            ['name' => 'Gestión Logística', 'price' => 195.00, 'position' => 11],
            ['name' => 'Asesoría Tributaria', 'price' => 165.00, 'position' => 12],
            ['name' => 'Plan Ventas', 'price' => 240.00, 'position' => 13],
            ['name' => 'Imagen Corporativa', 'price' => 320.00, 'position' => 14],
            ['name' => 'Transformación Digital', 'price' => 499.00, 'position' => 15],
            ['name' => 'Gestión Contratos', 'price' => 285.00, 'position' => 16],
            ['name' => 'Auditoría Procesos', 'price' => 410.00, 'position' => 17],
            ['name' => 'Consultoría Exportación', 'price' => 520.00, 'position' => 18],
        ];

        foreach ($serviceProProducts as $prod) {
            Product::updateOrCreate(
                ['tenant_id' => $servicePro->id, 'name' => $prod['name']],
                [
                    'description' => $prod['name'] . ' - Solución profesional para el crecimiento de tu empresa.',
                    'price_usd' => $prod['price'],
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

        // ═════════════════════════════════════════════════════════════════════════════════
        // Summary
        // ═════════════════════════════════════════════════════════════════════════════════
        $this->command->info("\n✅ Demo Data Seed Completed Successfully!");
        $this->command->info("\n📊 Tenants Created:");
        $this->command->info("   ✓ TechStart Venezuela (Plan 1) - 6 products, 3 services");
        $this->command->info("   ✓ Boutique Eleganza (Plan 2) - 12 products, 3 services");
        $this->command->info("   ✓ ServicePro Empresarial (Plan 3) - 18 products, 4 services");
        $this->command->info("\n🔑 Test Credentials:");
        $this->command->info("   Email: {$user->email}");
        $this->command->info("   Password: password123");
        $this->command->info("   PIN: 1234");
        $this->command->info("\n🌐 Access URLs:");
        $this->command->info("   http://techstart.synticorex.test");
        $this->command->info("   http://retailco.synticorex.test");
        $this->command->info("   http://servicepro.synticorex.test");
    }
}
