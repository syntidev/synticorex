<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\TenantCustomization;
use Illuminate\Database\Seeder;

class DemosCustomizationSeeder extends Seeder
{
    public function run(): void
    {
        $demosData = [
            'sintiburguer' => [
                'tenants' => [
                    'slogan' => 'La calle tiene sabor',
                    'city' => 'Caracas',
                    'address' => 'Av. Libertador, Local 4, Caracas',
                    'currency_display' => 'both',
                    'business_hours' => json_encode([
                        'monday' => ['open' => '18:00', 'close' => '02:00'],
                        'tuesday' => ['open' => '18:00', 'close' => '02:00'],
                        'wednesday' => ['open' => '18:00', 'close' => '02:00'],
                        'thursday' => ['open' => '18:00', 'close' => '02:00'],
                        'friday' => ['open' => '17:00', 'close' => '03:00'],
                        'saturday' => ['open' => '17:00', 'close' => '03:00'],
                        'sunday' => ['closed' => true],
                    ]),
                ],
                'customization' => [
                    'hero_layout' => 'fullscreen',
                    'theme_slug' => 'food-callejero',
                    'about_text' => 'Somos la parada obligatoria de Caracas. Arepas, hamburguesas, pizzas y más — preparadas al momento con los mejores ingredientes.',
                    'cta_title' => '¿Tienes hambre?',
                    'cta_subtitle' => 'Escríbenos y te preparamos tu pedido al momento.',
                    'cta_button_text' => 'Hacer pedido',
                    'payment_methods' => json_encode(['Pago Móvil', 'Efectivo USD', 'Zelle']),
                ],
            ],
            'donaz' => [
                'tenants' => [
                    'slogan' => 'Dulce que enamora',
                    'city' => 'Valencia',
                    'address' => 'C.C. Metrópolis, Local 12, Valencia',
                    'currency_display' => 'usd',
                    'business_hours' => json_encode([
                        'monday' => ['open' => '08:00', 'close' => '20:00'],
                        'tuesday' => ['open' => '08:00', 'close' => '20:00'],
                        'wednesday' => ['open' => '08:00', 'close' => '20:00'],
                        'thursday' => ['open' => '08:00', 'close' => '20:00'],
                        'friday' => ['open' => '08:00', 'close' => '21:00'],
                        'saturday' => ['open' => '09:00', 'close' => '21:00'],
                        'sunday' => ['open' => '10:00', 'close' => '18:00'],
                    ]),
                ],
                'customization' => [
                    'hero_layout' => 'split',
                    'theme_slug' => 'pasteleria-rosa',
                    'about_text' => 'Repostería artesanal hecha con amor en Valencia. Donas, tortas y postres venezolanos que conquistan desde el primer bocado.',
                    'cta_title' => 'Endúlzate hoy',
                    'cta_subtitle' => 'Pedidos por encargo con 24 horas de anticipación.',
                    'cta_button_text' => 'Pedir ahora',
                    'payment_methods' => json_encode(['Pago Móvil', 'Efectivo USD', 'PayPal']),
                ],
            ],
            'urbanstore' => [
                'tenants' => [
                    'slogan' => 'Moda urbana para ti',
                    'city' => 'Maracaibo',
                    'address' => 'C.C. Lago Mall, Local 45, Maracaibo',
                    'currency_display' => 'eur',
                    'business_hours' => json_encode([
                        'monday' => ['open' => '09:00', 'close' => '18:00'],
                        'tuesday' => ['open' => '09:00', 'close' => '18:00'],
                        'wednesday' => ['open' => '09:00', 'close' => '18:00'],
                        'thursday' => ['open' => '09:00', 'close' => '18:00'],
                        'friday' => ['open' => '09:00', 'close' => '19:00'],
                        'saturday' => ['open' => '10:00', 'close' => '19:00'],
                        'sunday' => ['closed' => true],
                    ]),
                ],
                'customization' => [
                    'hero_layout' => 'split',
                    'theme_slug' => 'urbano-oscuro',
                    'about_text' => 'Tienda de moda urbana con las últimas tendencias en ropa, calzado y accesorios. Enviamos a todo el país.',
                    'cta_title' => 'Tu estilo, tu identidad',
                    'cta_subtitle' => 'Explora la colección y encuentra tu próximo look favorito.',
                    'cta_button_text' => 'Ver catálogo',
                    'payment_methods' => json_encode(['Zelle', 'PayPal', 'Efectivo USD']),
                ],
            ],
            'tecnofix' => [
                'tenants' => [
                    'slogan' => 'Tu equipo en buenas manos',
                    'city' => 'Caracas',
                    'address' => 'Av. Francisco de Miranda, Edificio Centro, PB, Caracas',
                    'currency_display' => 'usd',
                    'business_hours' => json_encode([
                        'monday' => ['open' => '08:00', 'close' => '17:00'],
                        'tuesday' => ['open' => '08:00', 'close' => '17:00'],
                        'wednesday' => ['open' => '08:00', 'close' => '17:00'],
                        'thursday' => ['open' => '08:00', 'close' => '17:00'],
                        'friday' => ['open' => '08:00', 'close' => '17:00'],
                        'saturday' => ['open' => '09:00', 'close' => '13:00'],
                        'sunday' => ['closed' => true],
                    ]),
                ],
                'customization' => [
                    'hero_layout' => 'gradient',
                    'theme_slug' => 'tech-azul',
                    'about_text' => 'Centro técnico especializado en reparación y mantenimiento de equipos electrónicos. Más de 5 años devolviendo vida a tus dispositivos.',
                    'cta_title' => '¿Tu equipo tiene fallas?',
                    'cta_subtitle' => 'Diagnóstico gratuito el mismo día. Traemos soluciones, no excusas.',
                    'cta_button_text' => 'Solicitar diagnóstico',
                    'payment_methods' => json_encode(['Pago Móvil', 'Zelle', 'Efectivo USD', 'Transferencia']),
                ],
            ],
        ];

        foreach ($demosData as $subdomain => $data) {
            $tenant = Tenant::query()
                ->where('subdomain', $subdomain)
                ->where('is_demo', true)
                ->first();

            if (! $tenant) {
                $this->command?->warn("Tenant demo '{$subdomain}' no encontrado. Saltando...");

                continue;
            }

            // Update tenants table
            $tenant->update($data['tenants']);

            // Update or create tenant_customization
            TenantCustomization::query()->updateOrCreate(
                ['tenant_id' => $tenant->id],
                $data['customization']
            );

            $this->command?->info("✓ {$subdomain} customizado correctamente.");
        }

        // Verification query
        $verification = \DB::select(
            "SELECT t.subdomain, t.slogan, t.currency_display, tc.theme_slug, tc.cta_title
             FROM tenants t
             JOIN tenant_customization tc ON tc.tenant_id = t.id
             WHERE t.is_demo = 1"
        );

        $this->command?->info("Post-execution verification: " . count($verification) . " filas encontradas.");
        foreach ($verification as $row) {
            $this->command?->info("  • {$row->subdomain} → {$row->theme_slug} [{$row->currency_display}]");
        }
    }
}
