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
                'description' => 'Soluciones digitales para tu empresa - Consultoría tecnológica accesible',
                'slogan' => 'Soluciones digitales para tu empresa',
                'email' => 'contacto@techstart.local',
                'phone' => '+58 212 555 0001',
                'whatsapp_sales' => '+58 412 555 0001',
                'whatsapp_support' => '+58 412 555 0001',
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

        // Products for TechStart (6 products, $49-$299)
        $techStartProducts = [
            ['name' => 'Software de Gestión', 'price' => 49.00, 'position' => 1],
            ['name' => 'Soporte Técnico Premium', 'price' => 79.00, 'position' => 2],
            ['name' => 'Capacitación en Línea', 'price' => 99.00, 'position' => 3],
            ['name' => 'Auditoría Digital Completa', 'price' => 199.00, 'position' => 4],
            ['name' => 'Soluciones Cloud Backup', 'price' => 149.00, 'position' => 5],
            ['name' => 'VPN Empresarial', 'price' => 299.00, 'position' => 6],
        ];

        foreach ($techStartProducts as $i => $prod) {
            Product::updateOrCreate(
                ['tenant_id' => $techStart->id, 'name' => $prod['name']],
                [
                    'description' => ucfirst($prod['name']) . ' para empresas venezolanas. Solución confiable y accesible.',
                    'price_usd' => $prod['price'],
                    'position' => $prod['position'],
                    'is_active' => true,
                ]
            );
        }

        // Services for TechStart (3 services)
        $techStartServices = [
            ['name' => 'Diagnóstico Tecnológico', 'position' => 1],
            ['name' => 'Implementación System', 'position' => 2],
            ['name' => 'Mantenimiento Continuo', 'position' => 3],
        ];

        $serviceGenerator = new ServiceImageGeneratorService();
        foreach ($techStartServices as $svc) {
            Service::updateOrCreate(
                ['tenant_id' => $techStart->id, 'name' => $svc['name']],
                [
                    'description' => $svc['name'] . ' para transformación digital en Venezuela.',
                    'position' => $svc['position'],
                    'image_filename' => $serviceGenerator->generateServiceImage($techStart->id, $svc['name'], 'Tecnología'),
                    'is_active' => true,
                ]
            );
        }

        // ═══════════════════════════════════════════════════════════════════════════════
        // TENANT 2: RetailCo Boutique (Plan 2 - Crecimiento)
        // ═══════════════════════════════════════════════════════════════════════════════
        $retailCo = Tenant::updateOrCreate(
            ['subdomain' => 'retailco'],
            [
                'user_id' => $user->id,
                'plan_id' => $plans->count() > 1 ? $plans->skip(1)->first()?->id : $plans->first()?->id,
                'business_name' => 'RetailCo Boutique',
                'business_segment' => 'Comercio',
                'description' => 'Tu estilo, tu identidad - Moda y accesorios de calidad para la mujer moderna',
                'slogan' => 'Tu estilo, tu identidad',
                'email' => 'ventas@retailco.local',
                'phone' => '+58 414 555 0002',
                'whatsapp_sales' => '+58 414 555 0002',
                'whatsapp_support' => '+58 414 555 0002',
                'address' => 'Centro Comercial Marina Plaza, Local 45',
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

        // Products for RetailCo (12 products, $15-$120)
        $retailCoProducts = [
            ['name' => 'Blusas y Camisetas', 'price' => 25.00, 'position' => 1],
            ['name' => 'Pantalones y Jeans', 'price' => 45.00, 'position' => 2],
            ['name' => 'Vestidos Casuales', 'price' => 55.00, 'position' => 3],
            ['name' => 'Zapatos Deportivos', 'price' => 60.00, 'position' => 4],
            ['name' => 'Zapatos Formales', 'price' => 75.00, 'position' => 5],
            ['name' => 'Carteras y Bolsos', 'price' => 50.00, 'position' => 6],
            ['name' => 'Cinturones de Cuero', 'price' => 30.00, 'position' => 7],
            ['name' => 'Accesorios de Moda', 'price' => 15.00, 'position' => 8],
            ['name' => 'Prendas de Abrigo', 'price' => 80.00, 'position' => 9],
            ['name' => 'Conjuntos y Outfits', 'price' => 120.00, 'position' => 10],
            ['name' => 'Colecciones Exclusivas', 'price' => 95.00, 'position' => 11],
            ['name' => 'Accesorios Joyería', 'price' => 35.00, 'position' => 12],
        ];

        foreach ($retailCoProducts as $prod) {
            Product::updateOrCreate(
                ['tenant_id' => $retailCo->id, 'name' => $prod['name']],
                [
                    'description' => $prod['name'] . ' - Calidad premium con estilo venezolano.',
                    'price_usd' => $prod['price'],
                    'position' => $prod['position'],
                    'is_active' => true,
                ]
            );
        }

        // Services for RetailCo (3 services - Plan 2 limit is 6)
        $retailCoServices = [
            ['name' => 'Asesoría de Imagen', 'position' => 1],
            ['name' => 'Envíos Rápidos', 'position' => 2],
            ['name' => 'Servicio de Apartado', 'position' => 3],
        ];

        foreach ($retailCoServices as $svc) {
            Service::updateOrCreate(
                ['tenant_id' => $retailCo->id, 'name' => $svc['name']],
                [
                    'description' => $svc['name'] . ' disponible para nuestras clientes en toda Venezuela.',
                    'position' => $svc['position'],
                    'image_filename' => $serviceGenerator->generateServiceImage($retailCo->id, $svc['name'], 'Comercio'),
                    'is_active' => true,
                ]
            );
        }

        // ═══════════════════════════════════════════════════════════════════════════════
        // TENANT 3: ServicePro Soluciones (Plan 3 - Visión)
        // ═══════════════════════════════════════════════════════════════════════════════
        $servicePro = Tenant::updateOrCreate(
            ['subdomain' => 'servicepro'],
            [
                'user_id' => $user->id,
                'plan_id' => $plans->count() > 2 ? $plans->skip(2)->first()?->id : $plans->first()?->id,
                'business_name' => 'ServicePro Soluciones',
                'business_segment' => 'Consultoría',
                'description' => 'Optimizamos tu negocio - Servicios empresariales integrales para crecer con confianza',
                'slogan' => 'Optimizamos tu negocio',
                'email' => 'info@servicepro.local',
                'phone' => '+58 261 555 0003',
                'whatsapp_sales' => '+58 416 555 0003',
                'whatsapp_support' => '+58 416 555 0003',
                'address' => 'Torre Empresarial Metropolitana, Piso 15',
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

        // Products for ServicePro (18 products, $99-$999)
        $serviceProProducts = [
            ['name' => 'Consultoría General', 'price' => 99.00, 'position' => 1],
            ['name' => 'Auditoría Externa', 'price' => 299.00, 'position' => 2],
            ['name' => 'Gestión de RRHH', 'price' => 249.00, 'position' => 3],
            ['name' => 'Asesoría Legal', 'price' => 349.00, 'position' => 4],
            ['name' => 'Servicios Contables', 'price' => 199.00, 'position' => 5],
            ['name' => 'Estrategia de Marketing', 'price' => 399.00, 'position' => 6],
            ['name' => 'Logística Empresarial', 'price' => 449.00, 'position' => 7],
            ['name' => 'Análisis Financiero', 'price' => 299.00, 'position' => 8],
            ['name' => 'Reestructuración Org.', 'price' => 599.00, 'position' => 9],
            ['name' => 'Capacitación Ejecutiva', 'price' => 199.00, 'position' => 10],
            ['name' => 'Diagnóstico Empresarial', 'price' => 399.00, 'position' => 11],
            ['name' => 'Optimización Procesos', 'price' => 499.00, 'position' => 12],
            ['name' => 'Gestión de Proyectos', 'price' => 299.00, 'position' => 13],
            ['name' => 'Transformación Digital', 'price' => 799.00, 'position' => 14],
            ['name' => 'Sistema de Gestión', 'price' => 649.00, 'position' => 15],
            ['name' => 'Branding Corporativo', 'price' => 549.00, 'position' => 16],
            ['name' => 'Análisis Competitivo', 'price' => 349.00, 'position' => 17],
            ['name' => 'Plan Estratégico 3 años', 'price' => 999.00, 'position' => 18],
        ];

        foreach ($serviceProProducts as $prod) {
            Product::updateOrCreate(
                ['tenant_id' => $servicePro->id, 'name' => $prod['name']],
                [
                    'description' => $prod['name'] . ' - Solución integral para empresas orientadas al crecimiento.',
                    'price_usd' => $prod['price'],
                    'position' => $prod['position'],
                    'is_active' => true,
                ]
            );
        }

        // Services for ServicePro (9 services - Plan 3 allows up to 9)
        $serviceProServices = [
            ['name' => 'Diagnóstico Estratégico', 'position' => 1],
            ['name' => 'Diseño de Estrategia', 'position' => 2],
            ['name' => 'Plan de Implementación', 'position' => 3],
            ['name' => 'Soporte Continuo', 'position' => 4],
            ['name' => 'Gestión de Cambio', 'position' => 5],
            ['name' => 'Capacitación de Equipos', 'position' => 6],
            ['name' => 'Auditoría de Cumplimiento', 'position' => 7],
            ['name' => 'Evaluación Periódica', 'position' => 8],
            ['name' => 'Consultoría Especializada', 'position' => 9],
        ];

        foreach ($serviceProServices as $svc) {
            Service::updateOrCreate(
                ['tenant_id' => $servicePro->id, 'name' => $svc['name']],
                [
                    'description' => $svc['name'] . ' - Enfoque profesional y resultados comprobados.',
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
        $this->command->info("   ✓ RetailCo Boutique (Plan 2) - 12 products, 3 services");
        $this->command->info("   ✓ ServicePro Soluciones (Plan 3) - 18 products, 9 services");
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
