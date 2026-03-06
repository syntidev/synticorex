<?php

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
                'description' => 'Soluciones tecnológicas inteligentes diseñadas para empresas venezolanas que buscan modernizarse sin complicaciones. Herramientas digitales que crecen con tu negocio.',
                'slogan' => 'Transforma tu negocio con tecnología accesible',
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
                'description' => 'Plataforma omnicanal que unifica ventas online, punto de venta y gestión de inventario. Aumenta ingresos mientras reduces complejidad operacional.',
                'slogan' => 'Vende en línea y en tienda: toda tu operación integrada',
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
                'description' => 'Servicios integrales de consultoría que combinan experiencia sectorial con herramientas analíticas avanzadas. Resultados medibles en 90 días.',
                'slogan' => 'Consultoría empresarial acelerada con data y resultados comprobados',
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

            // Create customization for this tenant with plan-based sections
            $sectionsOrder = [
                ['name' => 'products', 'visible' => true, 'order' => 0],
                ['name' => 'services', 'visible' => true, 'order' => 1],
                ['name' => 'contact', 'visible' => true, 'order' => 2],
                ['name' => 'payment_methods', 'visible' => true, 'order' => 3],
                ['name' => 'cta', 'visible' => true, 'order' => 4],
            ];

            // Add Plan 2+ sections
            if ($plan->id >= 2) {
                $sectionsOrder[] = ['name' => 'about', 'visible' => true, 'order' => 5];
                $sectionsOrder[] = ['name' => 'testimonials', 'visible' => true, 'order' => 6];
            }

            // Add Plan 3 sections
            if ($plan->id >= 3) {
                $sectionsOrder[] = ['name' => 'faq', 'visible' => true, 'order' => 7];
                $sectionsOrder[] = ['name' => 'branches', 'visible' => true, 'order' => 8];
            }

            TenantCustomization::create([
                'tenant_id' => $tenant->id,
                'theme_slug' => 'light',
                'social_networks' => [],
                'payment_methods' => ['credit_card', 'bank_transfer'],
                'faq_items' => [],
                'visual_effects' => [
                    'sections_order' => $sectionsOrder,
                    'sections_config' => [],
                ],
            ]);

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
        // Different products for each tenant based on segment
        $segmentProducts = [
            'Tecnología' => [
                [
                    'name' => 'Paquete Arranque Digital',
                    'description' => 'Implementación completa del sistema con setup básico, capacitación inicial y 3 meses de soporte técnico incluidos. Perfecto para empresas nuevas en transformación digital.',
                    'price_usd' => 599.99,
                    'position' => 1,
                    'badge' => 'hot',
                ],
                [
                    'name' => 'Licencia Profesional Anual',
                    'description' => 'Acceso ilimitado a todas las funcionalidades core con soporte prioritario, actualizaciones automáticas y reportes analíticos avanzados durante 12 meses.',
                    'price_usd' => 299.99,
                    'position' => 2,
                    'badge' => null,
                ],
                [
                    'name' => 'Módulo Integración API',
                    'description' => 'Conecta TechStart con tus sistemas existentes, automatiza flujos de datos y sincroniza información en tiempo real. Reduce errores manuales hasta 90%.',
                    'price_usd' => 449.00,
                    'position' => 3,
                    'badge' => null,
                ],
                [
                    'name' => 'Plan Premium 6 Meses',
                    'description' => 'Acceso completo a funcionalidades avanzadas, consultoría estratégica mensual y soporte dedicado. Ideal para empresas en fase de escalamiento.',
                    'price_usd' => 799.99,
                    'position' => 4,
                    'badge' => 'new',
                ],
                [
                    'name' => 'Soporte Técnico Prioritario 24/7',
                    'description' => 'Equipo técnico disponible 24/7 para resolver incidencias críticas, optimizaciones de rendimiento y consultas de configuración. Garantiza máxima disponibilidad.',
                    'price_usd' => 199.99,
                    'position' => 5,
                    'badge' => 'promo',
                ],
            ],
            'Comercio' => [
                [
                    'name' => 'Suite Omnicanal Completa',
                    'description' => 'Sistema integrado que conecta tienda online, punto de venta físico e inventario centralizado. Vende donde quieran comprar tus clientes con control total.',
                    'price_usd' => 899.99,
                    'position' => 1,
                    'badge' => 'hot',
                ],
                [
                    'name' => 'Catálogo Digital Pro',
                    'description' => 'Herramienta de gestión de productos con fotos de alta calidad, descripciones SEO-optimizadas, variantes y sincronización automática en todos los canales.',
                    'price_usd' => 349.99,
                    'position' => 2,
                    'badge' => null,
                ],
                [
                    'name' => 'Sistema de Pagos Integrado',
                    'description' => 'Procesa pagos seguros en línea y en tienda con múltiples métodos: tarjetas, transferencias, divisas y criptomonedas. Conciliación automática y reportes en tiempo real.',
                    'price_usd' => 499.00,
                    'position' => 3,
                    'badge' => null,
                ],
                [
                    'name' => 'Gestión de Proveedores',
                    'description' => 'Portal para proveedores donde pueden revisar pedidos, entregar información de reabastecimiento y colaborar en optimización de supply chain. Reduce costos de operación.',
                    'price_usd' => 299.99,
                    'position' => 4,
                    'badge' => 'new',
                ],
                [
                    'name' => 'Programa de Fidelización',
                    'description' => 'Sistema de puntos y rewards que incrementa compras recurrentes. Crea promociones automáticas, segmenta clientes y mide ROI de cada campaña.',
                    'price_usd' => 249.99,
                    'position' => 5,
                    'badge' => null,
                ],
            ],
            'Consultoría' => [
                [
                    'name' => 'Auditoría Ejecutiva Completa',
                    'description' => 'Análisis exhaustivo de procesos, estructura organizacional y rentabilidad con deliverables ejecutivos. Identificamos ineficiencias y oportunidades de valor de corto plazo.',
                    'price_usd' => 1999.00,
                    'position' => 1,
                    'badge' => 'hot',
                ],
                [
                    'name' => 'Plan Transformación Digital',
                    'description' => 'Roadmap estratégico de 12 meses para modernizar operaciones incluye tecnología, procesos y talento. Basado en benchmark industria y mejores prácticas globales.',
                    'price_usd' => 2499.00,
                    'position' => 2,
                    'badge' => null,
                ],
                [
                    'name' => 'Implementación de Sistemas',
                    'description' => 'Ejecución integral de proyectos de software: especificación, implementación, validación y go-live. Garantizamos adopción de usuario y ROI medible.',
                    'price_usd' => 3999.00,
                    'position' => 3,
                    'badge' => null,
                ],
                [
                    'name' => 'Programa de Liderazgo Ejecutivo',
                    'description' => 'Coaching y formación para equipos directivos: liderazgo, toma de decisiones y gestión del cambio. Sesiones presenciales y virtuales personalizadas.',
                    'price_usd' => 1499.99,
                    'position' => 4,
                    'badge' => 'new',
                ],
                [
                    'name' => 'Retainer Mensual de Consultoría',
                    'description' => 'Acceso a equipo consultor dedicado para proyectos continuos, análisis ad-hoc y soporte estratégico. Garantiza agilidad en decisiones críticas.',
                    'price_usd' => 2999.00,
                    'position' => 5,
                    'badge' => null,
                ],
            ],
        ];

        $products = $segmentProducts[$tenant->business_segment] ?? $segmentProducts['Tecnología'];
        $imageGenerator = new ProductImageGeneratorService();

        foreach ($products as $productData) {
            // Generate image for product
            $imageFilename = $imageGenerator->generateProductImage(
                $tenant->id,
                $productData['name'],
                $tenant->business_segment
            );

            Product::create([
                'tenant_id' => $tenant->id,
                ...$productData,
                'image_filename' => $imageFilename,
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
        // Different services for each tenant based on segment
        $segmentServices = [
            'Tecnología' => [
                [
                    'name' => 'Diagnóstico Digital Integral',
                    'description' => 'Análisis profundo de procesos actuales y recomendaciones personalizadas para optimizar operaciones. Identificamos oportunidades de automatización y eficiencia.',
                    'icon_name' => 'chart-pie',
                    'cta_text' => 'Solicitar Diagnóstico',
                    'position' => 1,
                ],
                [
                    'name' => 'Implementación Acelerada',
                    'description' => 'Equipo especializado implementa el sistema en tiempo récord con mínima disrupción operacional. Incluye migración de datos, configuración custom y validación completa.',
                    'icon_name' => 'rocket',
                    'cta_text' => 'Iniciar Implementación',
                    'position' => 2,
                ],
                [
                    'name' => 'Capacitación y Certificación',
                    'description' => 'Programas de entrenamiento personalizados para tu equipo con talleres prácticos, documentación en español y certificación de competencia tecnológica.',
                    'icon_name' => 'academic-cap',
                    'cta_text' => 'Inscribirse Ahora',
                    'position' => 3,
                ],
                [
                    'name' => 'Optimización Continua',
                    'description' => 'Sesiones mensuales de revisión, reporte de KPIs, ajustes de configuración y recomendaciones de mejora basadas en datos reales de tu operación.',
                    'icon_name' => 'cog',
                    'cta_text' => 'Contratar Servicio',
                    'position' => 4,
                ],
            ],
            'Comercio' => [
                [
                    'name' => 'Consultoría E-Commerce',
                    'description' => 'Estrategia de venta online personalizada para tu retail: tráfico, conversión, ticket promedio y retención. Benchmarks con competencia local para diferenciación.',
                    'icon_name' => 'shopping-cart',
                    'cta_text' => 'Agendar Consulta',
                    'position' => 1,
                ],
                [
                    'name' => 'Setup Tienda Online',
                    'description' => 'Creación de tienda e-commerce profesional con dominio propio, diseño mobile-first, pasarela de pagos y catálogo inicial. Listo para vender en 2 semanas.',
                    'icon_name' => 'globe-alt',
                    'cta_text' => 'Crear Mi Tienda',
                    'position' => 2,
                ],
                [
                    'name' => 'Capacitación Operacional',
                    'description' => 'Entrenamiento práctico para equipos de ventas, almacén e inventario. Módulos en gestión de pedidos, devoluciones, análisis de ventas y mejores prácticas retail.',
                    'icon_name' => 'academic-cap',
                    'cta_text' => 'Inscribir Equipo',
                    'position' => 3,
                ],
                [
                    'name' => 'Gestión de Crédito Comercial',
                    'description' => 'Módulo de crédito con límites por cliente, facturación automática y seguimiento de pagos. Control de cartera integrado con tus reportes financieros.',
                    'icon_name' => 'currency-dollar',
                    'cta_text' => 'Configurar Crédito',
                    'position' => 4,
                ],
            ],
            'Consultoría' => [
                [
                    'name' => 'Diagnóstico Estratégico',
                    'description' => 'Evaluación de visión, estrategia y ejecución actual. Sesiones de grupo con accionistas, análisis FODA profundo y reporte con recomendaciones priorizadas.',
                    'icon_name' => 'chart-pie',
                    'cta_text' => 'Agendar Sesión',
                    'position' => 1,
                ],
                [
                    'name' => 'Rediseño de Procesos',
                    'description' => 'Mapeo de procesos actuales, identificación de bottlenecks y diseño de procesos optimizados. Incluye documentación SOP y plan de implementación gradual.',
                    'icon_name' => 'arrow-path',
                    'cta_text' => 'Iniciar Rediseño',
                    'position' => 2,
                ],
                [
                    'name' => 'Gestión del Cambio',
                    'description' => 'Estrategia integral para adoptar cambios organizacionales: comunicación, capacitación, resistencia y seguimiento. Asegura éxito en transformaciones complejas.',
                    'icon_name' => 'chart-pie',
                    'cta_text' => 'Planificar Cambios',
                    'position' => 3,
                ],
                [
                    'name' => 'Análisis Financiero y Valoración',
                    'description' => 'Análisis de flujos, márgenes y rentabilidad por unidad de negocio. Proyecciones financieras, análisis de escenarios y valoración empresarial profesional.',
                    'icon_name' => 'currency-dollar',
                    'cta_text' => 'Solicitar Análisis',
                    'position' => 4,
                ],
            ],
        ];

        $services = $segmentServices[$tenant->business_segment] ?? $segmentServices['Tecnología'];
        $imageGenerator = new ServiceImageGeneratorService();

        foreach ($services as $serviceData) {
            // Generate image for service
            $imageFilename = $imageGenerator->generateServiceImage(
                $tenant->id,
                $serviceData['name'],
                $tenant->business_segment
            );

            Service::create([
                'tenant_id' => $tenant->id,
                ...$serviceData,
                'image_filename' => $imageFilename,
                'is_active' => true,
            ]);
        }
    }
}
