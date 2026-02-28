<?php

declare(strict_types=1);

return [
    'FOOD_BEVERAGE' => [
        'label' => 'Alimentos (Restaurante, Pizzería, Café)',
        'icon' => 'utensils',
        'schema_type' => 'Restaurant',
        'fields' => [
            'info' => [
                'nombre', 'telefono_whatsapp', 'direccion', 'ciudad',
                'logo', 'hero_image', 'descripcion', 'horarios',
            ],
            'items' => [
                'nombre', 'precio', 'foto', 'descripcion', 'categoria',
            ],
        ],
        'landing_sections' => [
            'hero', 'products', 'services', 'contact',
            'payment_methods', 'testimonials', 'cta',
        ],
        'item_label' => 'Menú',
        'item_singular' => 'Plato',
        'feature_limits' => [
            1 => ['max_items' => 6, 'features' => ['basic_schema']],
            2 => ['max_items' => 12, 'features' => ['menu_schema', 'delivery_button', 'testimonios']],
            3 => ['max_items' => 20, 'features' => ['menu_schema', 'delivery_button', 'reservations', 'faq_schema', 'testimonios']],
        ],
    ],

    'RETAIL' => [
        'label' => 'Comercio (Tienda, E-commerce)',
        'icon' => 'shopping-bag',
        'schema_type' => 'Store',
        'fields' => [
            'info' => [
                'nombre', 'telefono_whatsapp', 'direccion', 'ciudad',
                'logo', 'hero_image', 'descripcion', 'horarios',
            ],
            'items' => [
                'nombre', 'precio', 'sku', 'foto', 'descripcion', 'stock', 'categoria',
            ],
        ],
        'landing_sections' => [
            'hero', 'products', 'services', 'contact',
            'payment_methods', 'faq', 'cta',
        ],
        'item_label' => 'Productos',
        'item_singular' => 'Producto',
        'feature_limits' => [
            1 => ['max_items' => 6, 'features' => ['basic_schema']],
            2 => ['max_items' => 12, 'features' => ['product_schema', 'categories', 'carrito_whatsapp', 'testimonios']],
            3 => ['max_items' => 20, 'features' => ['product_schema', 'ratings', 'payment_gateway', 'discounts', 'testimonios']],
        ],
    ],

    'HEALTH_WELLNESS' => [
        'label' => 'Salud & Belleza (Peluquería, Spa, Gimnasio)',
        'icon' => 'heart',
        'schema_type' => 'HealthAndBeautyBusiness',
        'fields' => [
            'info' => [
                'nombre', 'telefono_whatsapp', 'direccion', 'ciudad',
                'logo', 'hero_image', 'descripcion', 'horarios', 'profesional_responsable',
            ],
            'items' => [
                'nombre', 'duracion', 'precio', 'foto', 'descripcion', 'requiere_cita',
            ],
        ],
        'landing_sections' => [
            'hero', 'services', 'contact',
            'testimonials', 'cta',
        ],
        'item_label' => 'Servicios',
        'item_singular' => 'Servicio',
        'feature_limits' => [
            1 => ['max_items' => 6, 'features' => ['basic_schema']],
            2 => ['max_items' => 12, 'features' => ['service_schema', 'profesionales', 'testimonios']],
            3 => ['max_items' => 20, 'features' => ['service_schema', 'booking_system', 'faq_schema', 'credencial_verificacion', 'testimonios']],
        ],
    ],

    'PROFESSIONAL_SERVICES' => [
        'label' => 'Servicios Profesionales (Abogado, Consultor, Contador)',
        'icon' => 'briefcase',
        'schema_type' => 'ProfessionalService',
        'fields' => [
            'info' => [
                'nombre', 'telefono_whatsapp', 'email', 'direccion', 'ciudad',
                'logo', 'hero_image', 'descripcion', 'anos_experiencia', 'profesional_responsable',
            ],
            'items' => [
                'nombre', 'precio', 'descripcion', 'duracion_estimada',
            ],
        ],
        'landing_sections' => [
            'hero', 'about', 'services',
            'testimonials', 'faq', 'contact', 'cta',
        ],
        'item_label' => 'Servicios',
        'item_singular' => 'Servicio',
        'feature_limits' => [
            1 => ['max_items' => 6, 'features' => ['basic_schema']],
            2 => ['max_items' => 12, 'features' => ['service_schema', 'team', 'testimonios']],
            3 => ['max_items' => 20, 'features' => ['service_schema', 'portafolio', 'faq_schema', 'lead_magnet', 'calendly', 'testimonios']],
        ],
    ],

    'ON_DEMAND' => [
        'label' => 'Servicios Técnicos (Mecánico, Plomero, Electricista, Carpintero)',
        'icon' => 'wrench',
        'schema_type' => 'LocalBusiness',
        'fields' => [
            'info' => [
                'nombre', 'telefono_whatsapp', 'zona_cobertura', 'especialidad',
                'logo', 'foto_trabajo_destacado', 'anos_experiencia', 'descripcion', 'horarios',
            ],
            'items' => [
                'tipo_servicio', 'descripcion', 'precio_base', 'tiempo_estimado', 'foto_trabajo',
            ],
        ],
        'landing_sections' => [
            'hero', 'services', 'about',
            'testimonials', 'contact', 'cta',
        ],
        'item_label' => 'Trabajos',
        'item_singular' => 'Trabajo',
        'feature_limits' => [
            1 => ['max_items' => 6, 'features' => ['basic_schema']],
            2 => ['max_items' => 12, 'features' => ['service_schema', 'portfolio', 'testimonios']],
            3 => ['max_items' => 20, 'features' => ['service_schema', 'portfolio', 'faq_schema', 'booking', 'urgencias_247', 'testimonios']],
        ],
    ],
];
