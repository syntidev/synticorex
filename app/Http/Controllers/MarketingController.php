<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Contracts\View\View;

class MarketingController extends Controller
{
    /**
     * Display the SYNTIweb marketing landing page.
     */
    public function index(): View
    {
        $plans = [
            [
                'name' => 'OPORTUNIDAD',
                'price' => 99,
                'period' => 'año',
                'items' => 6,
                'services' => 3,
                'tagline' => 'Para validar. Cero riesgo.',
                'cta' => 'Empezar gratis',
                'highlight' => false,
                'features' => [
                    'Landing profesional',
                    'Apareces en Google',
                    'WhatsApp integrado',
                    'Se ve bien en celular',
                    'Horarios y ubicación',
                    'Código QR para compartir',
                ],
            ],
            [
                'name' => 'CRECIMIENTO',
                'price' => 149,
                'period' => 'año',
                'items' => 12,
                'services' => 6,
                'tagline' => 'Para crecer. Tu competencia está aquí.',
                'cta' => 'Crecer ahora',
                'highlight' => true,
                'features' => [
                    'Todo de OPORTUNIDAD +',
                    'Testimonios de clientes',
                    'Sección Acerca de',
                    '17 temas visuales',
                    'Redes sociales conectadas',
                    'Más productos y servicios',
                ],
            ],
            [
                'name' => 'VISIÓN',
                'price' => 199,
                'period' => 'año',
                'items' => 18,
                'services' => 9,
                'tagline' => 'Para dominar. Eres líder local.',
                'cta' => 'Dominar mi zona',
                'highlight' => false,
                'features' => [
                    'Todo de CRECIMIENTO +',
                    'Preguntas frecuentes',
                    'Sucursales múltiples',
                    'Galería de fotos por producto',
                    'Colores personalizados ilimitados',
                    'Herramientas nuevas conforme creces',
                ],
            ],
        ];

        $segments = [
            [
                'key' => 'FOOD_BEVERAGE',
                'name' => 'Restaurante',
                'icon' => 'tools-kitchen-2',
                'color' => 'orange',
                'features' => [
                    'Menú con fotos y precios',
                    'Pedidos por WhatsApp',
                    'Horarios y ubicación',
                    'Google sabe que eres restaurante',
                ],
            ],
            [
                'key' => 'ON_DEMAND',
                'name' => 'Mecánico / Técnico',
                'icon' => 'tool',
                'color' => 'blue',
                'features' => [
                    'Trabajos realizados con fotos',
                    'Zona de cobertura',
                    'Disponibilidad en tiempo real',
                    'Clientes te encuentran buscando',
                ],
            ],
            [
                'key' => 'PROFESSIONAL_SERVICES',
                'name' => 'Abogado / Consultor',
                'icon' => 'briefcase',
                'color' => 'indigo',
                'features' => [
                    'Servicios y especialidades',
                    'Experiencia y trayectoria',
                    'Consultas por WhatsApp',
                    'Credibilidad profesional',
                ],
            ],
            [
                'key' => 'HEALTH_WELLNESS',
                'name' => 'Peluquería / Spa',
                'icon' => 'scissors',
                'color' => 'pink',
                'features' => [
                    'Galería de trabajos',
                    'Lista de servicios con precios',
                    'Horarios de atención',
                    'Clientes agendan por WhatsApp',
                ],
            ],
            [
                'key' => 'RETAIL',
                'name' => 'Tienda / Comercio',
                'icon' => 'shopping-bag',
                'color' => 'emerald',
                'features' => [
                    'Catálogo de productos',
                    'Precios actualizados',
                    'Promociones y ofertas',
                    'Ventas por WhatsApp',
                ],
            ],
        ];

        $stats = [
            ['value' => '95%', 'label' => 'de negocios en Venezuela sin presencia digital', 'description' => 'Y tu competencia sí está', 'icon' => 'chart-pie'],
            ['value' => '3x', 'label' => 'más clientes con landing propia', 'description' => 'Comparado con solo redes', 'icon' => 'users'],
            ['value' => '5min', 'label' => 'para tener tu sitio listo', 'description' => 'Sin conocimientos técnicos', 'icon' => 'clock'],
            ['value' => '24/7', 'label' => 'tu negocio visible siempre', 'description' => 'Incluso mientras duermes', 'icon' => 'world'],
        ];

        return view('marketing.index', compact('plans', 'segments', 'stats'));
    }

    public function planes(): View
    {
        $studioPlans = Plan::where('blueprint', 'studio')->orderBy('price_usd')->get();
        $foodPlans   = Plan::where('blueprint', 'food')->orderBy('price_usd')->get();
        $catPlans    = Plan::where('blueprint', 'cat')->orderBy('price_usd')->get();

        return view('marketing.planes', compact('studioPlans', 'foodPlans', 'catPlans'));
    }

    public function studio(): View
    {
        return view('marketing.studio');
    }

    public function food(): View
    {
        return view('marketing.food');
    }

    public function cat(): View
    {
        $plans = Plan::where('blueprint', 'cat')->orderBy('id')->get();

        return view('marketing.cat', compact('plans'));
    }
}
