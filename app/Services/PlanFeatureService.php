<?php

declare(strict_types=1);

namespace App\Services;

class PlanFeatureService
{
    public static function get(string $blueprint): array
    {
        return match ($blueprint) {
            'studio' => self::studio(),
            'food'   => self::food(),
            'cat'    => self::cat(),
            default  => ['plans' => []],
        };
    }

    // ─────────────────────────────────────────────────────────────────────────
    // STUDIO
    // ─────────────────────────────────────────────────────────────────────────
    private static function studio(): array
    {
        $features = [
            ['label' => 'Sitio web vivo con subdominio propio',       'p1' => true,      'p2' => true,       'p3' => true],
            ['label' => 'Productos con imagen y precio',              'p1' => '20',      'p2' => '50',       'p3' => '200'],
            ['label' => 'Servicios con icono Iconify',                'p1' => '3',       'p2' => '6',        'p3' => '9'],
            ['label' => 'Hero slots (imagen principal)',              'p1' => '1',       'p2' => '3',        'p3' => '5'],
            ['label' => 'Temas visuales',                            'p1' => '10',      'p2' => '17',       'p3' => '17 + custom'],
            ['label' => 'Redes sociales',                            'p1' => '1',       'p2' => '7',        'p3' => '7'],
            ['label' => 'WhatsApp directo por producto',             'p1' => true,      'p2' => true,       'p3' => true],
            ['label' => 'Horario y estado abierto/cerrado',          'p1' => true,      'p2' => true,       'p3' => true],
            ['label' => 'BCV automático · EUR · 4 modos display',    'p1' => true,      'p2' => true,       'p3' => true],
            ['label' => 'QR descargable + shortlink',                'p1' => true,      'p2' => true,       'p3' => true],
            ['label' => 'SEO básico automático',                     'p1' => true,      'p2' => true,       'p3' => true],
            ['label' => 'Analytics',                                 'p1' => 'básico',  'p2' => 'avanzado', 'p3' => 'full'],
            ['label' => 'Medios de pago (9 métodos)',                'p1' => false,     'p2' => true,       'p3' => true],
            ['label' => 'Sección About + imagen',                    'p1' => false,     'p2' => true,       'p3' => true],
            ['label' => 'Sección Testimonios',                       'p1' => false,     'p2' => true,       'p3' => true],
            ['label' => 'Widget tasa BCV visible al visitante',      'p1' => false,     'p2' => true,       'p3' => true],
            ['label' => 'Schema.org LocalBusiness',                  'p1' => false,     'p2' => true,       'p3' => true],
            ['label' => 'SEO avanzado (keywords + canonical)',        'p1' => false,     'p2' => true,       'p3' => true],
            ['label' => 'Header-top con mensaje',                    'p1' => false,     'p2' => true,       'p3' => true],
            ['label' => 'Sección FAQ (8 preguntas + Schema)',         'p1' => false,     'p2' => false,      'p3' => true],
            ['label' => 'Sucursales (hasta 3 con WA propio)',        'p1' => false,     'p2' => false,      'p3' => true],
            ['label' => 'Paleta personalizada (4 colores hex)',      'p1' => false,     'p2' => false,      'p3' => true],
            ['label' => 'CTA especial + banner marquee',             'p1' => false,     'p2' => false,      'p3' => true],
            ['label' => 'Reporte analytics PDF email',               'p1' => false,     'p2' => false,      'p3' => true],
            ['label' => 'SYNTiA asistente IA',                       'p1' => true,      'p2' => true,       'p3' => true],
            ['label' => 'Dominio personalizado (add-on $18)',        'p1' => 'add-on',  'p2' => 'add-on',   'p3' => 'add-on'],
        ];

        return [
            'plans' => [
                [
                    'slug'           => 'studio-oportunidad',
                    'name'           => 'Oportunidad',
                    'price'          => 99,
                    'billing'        => '/año',
                    'pill'           => 'Para empezar',
                    'highlighted'    => false,
                    'cta'            => 'Empezar gratis',
                    'price_original' => 149,
                    'savings_label'  => '33% OFF',
                    'per_month'      => '~$8/mes',
                    'promo_header'   => null,
                    'features'       => $features,
                ],
                [
                    'slug'           => 'studio-crecimiento',
                    'name'           => 'Crecimiento',
                    'price'          => 149,
                    'billing'        => '/año',
                    'pill'           => 'El que más vende',
                    'highlighted'    => true,
                    'cta'            => 'Crecer ahora',
                    'price_original' => 199,
                    'savings_label'  => 'Ahorra $50',
                    'per_month'      => '~$12/mes',
                    'promo_header'   => 'EL MÁS VENDIDO ⭐',
                    'features'       => $features,
                ],
                [
                    'slug'           => 'studio-vision',
                    'name'           => 'Visión',
                    'price'          => 199,
                    'billing'        => '/año',
                    'pill'           => 'Para los que dominan',
                    'highlighted'    => false,
                    'cta'            => 'Dominar mi zona',
                    'price_original' => 249,
                    'savings_label'  => '20% OFF',
                    'per_month'      => '~$17/mes',
                    'promo_header'   => null,
                    'features'       => $features,
                ],
            ],
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // FOOD
    // ─────────────────────────────────────────────────────────────────────────
    private static function food(): array
    {
        $features = [
            ['label' => 'Menú digital con QR',                           'p1' => true,      'p2' => true,       'p3' => true],
            ['label' => 'Ítems en lista',                                'p1' => '50',      'p2' => '100',      'p3' => '150'],
            ['label' => 'Fotos de categoría',                            'p1' => '6',       'p2' => '12',       'p3' => '18'],
            ['label' => 'BCV automático · EUR',                          'p1' => true,      'p2' => true,       'p3' => true],
            ['label' => 'Estado abierto/cerrado',                        'p1' => true,      'p2' => true,       'p3' => true],
            ['label' => 'WhatsApp directo',                              'p1' => true,      'p2' => true,       'p3' => true],
            ['label' => 'QR descargable',                                'p1' => true,      'p2' => true,       'p3' => true],
            ['label' => 'Modo sitio / llevar / delivery',                'p1' => true,      'p2' => true,       'p3' => true],
            ['label' => 'Banner restaurante cerrado',                    'p1' => true,      'p2' => true,       'p3' => true],
            ['label' => 'Categorías navegables (sticky)',                'p1' => false,     'p2' => true,       'p3' => true],
            ['label' => 'Badges por ítem (popular/nuevo/promo)',         'p1' => false,     'p2' => true,       'p3' => true],
            ['label' => 'Filtro horario WhatsApp',                       'p1' => false,     'p2' => true,       'p3' => true],
            ['label' => 'Schema Restaurant + Menu Google',               'p1' => false,     'p2' => true,       'p3' => true],
            ['label' => 'SEO keywords + canonical',                      'p1' => false,     'p2' => true,       'p3' => true],
            ['label' => 'Analytics',                                     'p1' => 'básico',  'p2' => 'avanzado', 'p3' => 'full'],
            ['label' => 'Pedido Rápido → WhatsApp',                      'p1' => false,     'p2' => false,      'p3' => true],
            ['label' => 'Carrito flotante con resumen',                  'p1' => false,     'p2' => false,      'p3' => true],
            ['label' => 'Sistema de Comandas (SF-XXXXXX)',               'p1' => false,     'p2' => false,      'p3' => true],
            ['label' => 'Estados: nuevo→preparando→listo',               'p1' => false,     'p2' => false,      'p3' => true],
            ['label' => 'Modalidades sitio/llevar/delivery avanzado',    'p1' => false,     'p2' => false,      'p3' => true],
            ['label' => 'Reporte analytics PDF email',                   'p1' => false,     'p2' => false,      'p3' => true],
            ['label' => 'SYNTiA asistente IA',                           'p1' => true,      'p2' => true,       'p3' => true],
        ];

        return [
            'plans' => [
                [
                    'slug'           => 'food-basico',
                    'name'           => 'Mensual',
                    'price'          => 12,
                    'billing'        => '/mes',
                    'pill'           => 'Pruébalo sin compromiso',
                    'highlighted'    => false,
                    'cta'            => 'Empezar gratis',
                    'price_original' => null,
                    'savings_label'  => null,
                    'per_month'      => '$12/mes',
                    'promo_header'   => null,
                    'features'       => $features,
                ],
                [
                    'slug'           => 'food-semestral',
                    'name'           => 'Semestral',
                    'price'          => 45,
                    'billing'        => '/6 meses',
                    'pill'           => 'Más por menos',
                    'highlighted'    => false,
                    'cta'            => 'Ahorrar con semestral',
                    'price_original' => 72,
                    'savings_label'  => '38% OFF',
                    'per_month'      => '$7.50/mes',
                    'promo_header'   => null,
                    'features'       => $features,
                ],
                [
                    'slug'           => 'food-anual',
                    'name'           => 'Anual',
                    'price'          => 69,
                    'billing'        => '/año',
                    'pill'           => 'El más popular',
                    'highlighted'    => true,
                    'cta'            => 'Máximo ahorro',
                    'price_original' => 144,
                    'savings_label'  => 'Ahorra $75',
                    'per_month'      => '~$5.75/mes',
                    'promo_header'   => 'MÁXIMO AHORRO 🔥',
                    'features'       => $features,
                ],
            ],
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // CAT
    // ─────────────────────────────────────────────────────────────────────────
    private static function cat(): array
    {
        $features = [
            ['label' => 'Catálogo visual con grid',                      'p1' => true,      'p2' => true,       'p3' => true],
            ['label' => 'Productos',                                     'p1' => '50',      'p2' => '150',      'p3' => '250'],
            ['label' => 'Imágenes por producto',                         'p1' => '1',       'p2' => '3',        'p3' => '6'],
            ['label' => 'BCV automático · EUR',                          'p1' => true,      'p2' => true,       'p3' => true],
            ['label' => 'Precio comparativo tachado',                    'p1' => true,      'p2' => true,       'p3' => true],
            ['label' => 'Badges (popular/nuevo/promo/destacado)',        'p1' => true,      'p2' => true,       'p3' => true],
            ['label' => 'Botón WhatsApp por producto',                   'p1' => true,      'p2' => true,       'p3' => true],
            ['label' => 'QR descargable',                                'p1' => true,      'p2' => true,       'p3' => true],
            ['label' => 'Analytics',                                     'p1' => 'básico',  'p2' => 'avanzado', 'p3' => 'full'],
            ['label' => 'Variantes talla + color',                       'p1' => false,     'p2' => true,       'p3' => true],
            ['label' => 'Carrito de compras (drawer)',                   'p1' => false,     'p2' => true,       'p3' => true],
            ['label' => 'Checkout → WhatsApp',                           'p1' => false,     'p2' => true,       'p3' => true],
            ['label' => 'Schema Store + SEO keywords',                   'p1' => false,     'p2' => true,       'p3' => true],
            ['label' => 'Mini Order SC-XXXX rastreable',                 'p1' => false,     'p2' => false,      'p3' => true],
            ['label' => 'Pantalla confirmación post-orden',              'p1' => false,     'p2' => false,      'p3' => true],
            ['label' => 'Sistema Órdenes en dashboard',                  'p1' => false,     'p2' => false,      'p3' => true],
            ['label' => 'Estados: pendiente→confirmado→entregado',       'p1' => false,     'p2' => false,      'p3' => true],
            ['label' => 'Analytics completo catálogo',                   'p1' => false,     'p2' => false,      'p3' => true],
            ['label' => 'Reporte analytics PDF email',                   'p1' => false,     'p2' => false,      'p3' => true],
            ['label' => 'SYNTiA asistente IA',                           'p1' => true,      'p2' => true,       'p3' => true],
            ['label' => 'Dominio personalizado (add-on $18)',            'p1' => 'add-on',  'p2' => 'add-on',   'p3' => 'add-on'],
        ];

        return [
            'plans' => [
                [
                    'slug'           => 'cat-basico',
                    'name'           => 'Mensual',
                    'price'          => 12,
                    'billing'        => '/mes',
                    'pill'           => 'Pruébalo sin compromiso',
                    'highlighted'    => false,
                    'cta'            => 'Empezar gratis',
                    'price_original' => null,
                    'savings_label'  => null,
                    'per_month'      => '$12/mes',
                    'promo_header'   => null,
                    'features'       => $features,
                ],
                [
                    'slug'           => 'cat-semestral',
                    'name'           => 'Semestral',
                    'price'          => 45,
                    'billing'        => '/6 meses',
                    'pill'           => 'Más por menos',
                    'highlighted'    => false,
                    'cta'            => 'Ahorrar con semestral',
                    'price_original' => 72,
                    'savings_label'  => '38% OFF',
                    'per_month'      => '$7.50/mes',
                    'promo_header'   => null,
                    'features'       => $features,
                ],
                [
                    'slug'           => 'cat-anual',
                    'name'           => 'Anual',
                    'price'          => 69,
                    'billing'        => '/año',
                    'pill'           => 'El más popular',
                    'highlighted'    => true,
                    'cta'            => 'Máximo ahorro',
                    'price_original' => 144,
                    'savings_label'  => 'Ahorra $75',
                    'per_month'      => '~$5.75/mes',
                    'promo_header'   => 'MÁXIMO AHORRO 🔥',
                    'features'       => $features,
                ],
            ],
        ];
    }
}
