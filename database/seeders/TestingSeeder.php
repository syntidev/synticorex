<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Plan;
use App\Models\Tenant;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class TestingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or retrieve test user
        $user = User::firstOrCreate(
            ['email' => 'admin@testing.local'],
            [
                'name' => 'Admin Testing',
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

        // Create 3 test tenants (one per plan)
        $tenantsData = [
            [
                'business_name' => 'TechStart Venezuela',
                'plan' => $plans->first(),
                'subdomain' => 'techstart',
                'business_segment' => 'Tecnología',
                'description' => 'Soluciones tecnológicas para empresas venezolanas',
                'slogan' => 'Innovación Digital para Tu Negocio',
                'email' => 'hola@techstart.local',
                'phone' => '+58 212 1234567',
                'whatsapp_sales' => '+58 412 1234567',
                'whatsapp_support' => '+58 412 7654321',
                'address' => 'Avenida Principal 123, Piso 4',
                'city' => 'Caracas',
                'country' => 'Venezuela',
            ],
            [
                'business_name' => 'RetailCo Marketplace',
                'plan' => $plans->count() > 1 ? $plans->skip(1)->first() : $plans->first(),
                'subdomain' => 'retailco',
                'business_segment' => 'Comercio',
                'description' => 'Plataforma de venta online y punto de venta para retailers',
                'slogan' => 'Todo lo que tu negocio necesita',
                'email' => 'contacto@retailco.local',
                'phone' => '+58 414 2223334',
                'whatsapp_sales' => '+58 414 2223334',
                'whatsapp_support' => '+58 414 5556667',
                'address' => 'Centro Comercial Las Mercedes, Local 201',
                'city' => 'Valencia',
                'country' => 'Venezuela',
            ],
            [
                'business_name' => 'ServicePro Consulting',
                'plan' => $plans->count() > 2 ? $plans->skip(2)->first() : $plans->first(),
                'subdomain' => 'servicepro',
                'business_segment' => 'Consultoría',
                'description' => 'Servicios integrales de consultoría empresarial',
                'slogan' => 'Crecimiento Empresarial Garantizado',
                'email' => 'info@servicepro.local',
                'phone' => '+58 261 3334445',
                'whatsapp_sales' => '+58 416 3334445',
                'whatsapp_support' => '+58 416 5558889',
                'address' => 'Torre Empresarial Av. Miranda, Piso 12',
                'city' => 'Maracaibo',
                'country' => 'Venezuela',
            ],
        ];

        $tenants = [];
        foreach ($tenantsData as $tenantData) {
            $plan = $tenantData['plan'];
            unset($tenantData['plan']);

            $tenant = Tenant::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                ...$tenantData,
                'domain_verified' => true,
                'status' => 'active',
                'edit_pin' => Hash::make('1234'),
                'base_domain' => 'syntiweb.local',
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
                            'source' => 'dolarapi',
                            'last_update' => now()->toDateString(),
                            'display' => [
                                'mode' => 'toggle',
                                'default_currency' => 'REF',
                                'show_conversion_button' => true,
                                'symbols' => [
                                    'reference' => 'REF',
                                    'bolivares' => 'Bs.',
                                ],
                                'decimals' => 2,
                                'rounding' => false,
                            ],
                        ],
                    ],
                ],
            ]);

            $tenants[] = $tenant;

            // Create products for this tenant
            $this->createProducts($tenant);

            // Create services for this tenant
            $this->createServices($tenant);

            $this->command->info("✓ Tenant '{$tenant->business_name}' created with products and services");
        }

        $this->command->info("\n✅ Testing seed completed successfully!");
        $this->command->info("📊 Created: 1 user, 3 tenants, 15 products, 12 services");
        $this->command->info("\n🔑 Test Credentials:");
        $this->command->info("   Email: {$user->email}");
        $this->command->info("   Password: password123");
    }

    /**
     * Create products for a tenant
     */
    private function createProducts(Tenant $tenant): void
    {
        $productsData = [
            [
                'name' => 'Licencia Profesional Anual',
                'description' => 'Licencia de software profesional con soporte anual completo',
                'price_usd' => 299.99,
                'position' => 1,
                'badge' => 'hot',
            ],
            [
                'name' => 'Consultoría Inicial',
                'description' => 'Sesión de consultoría o diagnóstico para optimizar procesos',
                'price_usd' => 150.00,
                'position' => 2,
            ],
            [
                'name' => 'Pack Premium 3 Meses',
                'description' => 'Acceso premium a todas las funcionalidades durante 3 meses',
                'price_usd' => 599.99,
                'position' => 3,
                'badge' => 'new',
            ],
            [
                'name' => 'Setup e Implementación',
                'description' => 'Instalación, configuración e implementación del sistema',
                'price_usd' => 499.00,
                'position' => 4,
            ],
            [
                'name' => 'Soporte Técnico 24/7',
                'description' => 'Soporte técnico prioritario 24/7 durante 1 mes',
                'price_usd' => 199.99,
                'position' => 5,
                'badge' => 'promo',
            ],
        ];

        foreach ($productsData as $productData) {
            Product::create([
                'tenant_id' => $tenant->id,
                ...$productData,
                'is_active' => true,
                'is_featured' => rand(0, 1) === 1,
            ]);
        }
    }

    /**
     * Create services for a tenant
     */
    private function createServices(Tenant $tenant): void
    {
        $servicesData = [
            [
                'name' => 'Asesoramiento Empresarial',
                'description' => 'Asesoramiento personalizado para optimizar operaciones y rentabilidad',
                'icon_name' => 'briefcase',
                'cta_text' => 'Solicitar Asesoramiento',
                'position' => 1,
            ],
            [
                'name' => 'Capacitación Integral',
                'description' => 'Capacitación completa en el uso del sistema y mejores prácticas',
                'icon_name' => 'academic-cap',
                'cta_text' => 'Inscribirse Ahora',
                'position' => 2,
            ],
            [
                'name' => 'Integración de Sistemas',
                'description' => 'Integración con sistemas existentes ERPs, CRMs y bases de datos',
                'icon_name' => 'puzzle',
                'cta_text' => 'Iniciar Integración',
                'position' => 3,
            ],
            [
                'name' => 'Mantenimiento Mensual',
                'description' => 'Soporte, actualizaciones y optimización continua del sistema',
                'icon_name' => 'cog',
                'cta_text' => 'Contratar Mantenimiento',
                'position' => 4,
            ],
        ];

        foreach ($servicesData as $serviceData) {
            Service::create([
                'tenant_id' => $tenant->id,
                ...$serviceData,
                'is_active' => true,
            ]);
        }
    }
}
