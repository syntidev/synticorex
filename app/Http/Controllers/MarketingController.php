<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Plan;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

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
        $studioPlans  = Plan::where('blueprint', 'studio')->orderBy('price_usd')->get();
        $foodPlans    = Plan::where('blueprint', 'food')->orderBy('price_usd')->get();
        $catPlans     = Plan::where('blueprint', 'cat')->orderBy('price_usd')->get();

        return view('marketing.planes', compact('studioPlans', 'foodPlans', 'catPlans'));
    }

    public function studio(): View
    {
        $planData = \App\Services\PlanFeatureService::get('studio');
        return view('marketing.studio', compact('planData'));
    }

    public function food(): View
    {
        $planData = \App\Services\PlanFeatureService::get('food');
        return view('marketing.food', compact('planData'));
    }

    public function cat(): View
    {
        $planData = \App\Services\PlanFeatureService::get('cat');
        return view('marketing.cat', compact('planData'));
    }

    public function terms(): View
    {
        return view('marketing.terms');
    }

    public function privacy(): View
    {
        return view('marketing.privacy');
    }

    public function about(): View
    {
        return view('marketing.about');
    }

    public function contacto(): View
    {
        return view('marketing.contacto');
    }

    public function demos(): View
    {
        $demos = [
            [
                'name'        => 'Donaz',
                'slug'        => 'donaz',
                'tagline'     => 'Plataforma de donaciones y ONGs',
                'product'     => 'SYNTIstudio',
                'product_color' => 'blue',
                'url'         => '#', // Reemplazar con subdominio real del tenant demo
                'image'       => 'https://images.unsplash.com/photo-1532629345422-7515f3d16bb6?w=800&auto=format&fit=crop&q=80',
                'arc_image'   => 'https://images.unsplash.com/photo-1532629345422-7515f3d16bb6?w=400&auto=format&fit=crop&q=80',
            ],
            [
                'name'        => 'Belle Store',
                'slug'        => 'belle-store',
                'tagline'     => 'Tienda de belleza y cosméticos',
                'product'     => 'SYNTIcat',
                'product_color' => 'pink',
                'url'         => '#',
                'image'       => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=800&auto=format&fit=crop&q=80',
                'arc_image'   => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=400&auto=format&fit=crop&q=80',
            ],
            [
                'name'        => 'MediCenter',
                'slug'        => 'medicenter',
                'tagline'     => 'Centro médico y salud',
                'product'     => 'SYNTIstudio',
                'product_color' => 'cyan',
                'url'         => '#',
                'image'       => 'https://images.unsplash.com/photo-1631217868264-e5b90bb7e133?w=800&auto=format&fit=crop&q=80',
                'arc_image'   => 'https://images.unsplash.com/photo-1631217868264-e5b90bb7e133?w=400&auto=format&fit=crop&q=80',
            ],
            [
                'name'        => 'Gestoría 360',
                'slug'        => 'gestoria-360',
                'tagline'     => 'Servicios legales y consultoría',
                'product'     => 'SYNTIstudio',
                'product_color' => 'indigo',
                'url'         => '#',
                'image'       => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=800&auto=format&fit=crop&q=80',
                'arc_image'   => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=400&auto=format&fit=crop&q=80',
            ],
            [
                'name'        => 'FitZone Pro',
                'slug'        => 'fitzone-pro',
                'tagline'     => 'Gym y entrenamiento funcional',
                'product'     => 'SYNTIstudio',
                'product_color' => 'orange',
                'url'         => '#',
                'image'       => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=800&auto=format&fit=crop&q=80',
                'arc_image'   => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=400&auto=format&fit=crop&q=80',
            ],
            [
                'name'        => 'Urban Menu',
                'slug'        => 'urban-menu',
                'tagline'     => 'Restaurante con menú digital',
                'product'     => 'SYNTIfood',
                'product_color' => 'amber',
                'url'         => '#',
                'image'       => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=800&auto=format&fit=crop&q=80',
                'arc_image'   => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=400&auto=format&fit=crop&q=80',
            ],
            [
                'name'        => 'Nova Store',
                'slug'        => 'nova-store',
                'tagline'     => 'Catálogo con carrito WhatsApp',
                'product'     => 'SYNTIcat',
                'product_color' => 'emerald',
                'url'         => '#',
                'image'       => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=800&auto=format&fit=crop&q=80',
                'arc_image'   => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=400&auto=format&fit=crop&q=80',
            ],
        ];

        return view('marketing.demos', compact('demos'));
    }

    public function blog(Request $request): View
    {
        $categories = BlogCategory::orderBy('sort_order')->get();
        $currentCat = $request->query('cat');

        $query = BlogPost::published()->with('category')->latest('published_at');

        if ($currentCat) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $currentCat));
        }

        $featured = BlogPost::published()->featured()->latest('published_at')->first();
        $posts    = $query->paginate(12)->withQueryString();

        return view('marketing.blog.index', compact('posts', 'categories', 'featured', 'currentCat'));
    }

    public function blogPost(string $slug): View
    {
        $post = BlogPost::published()
            ->with('category')
            ->where('slug', $slug)
            ->firstOrFail();

        $post->increment('views');

        $related = BlogPost::published()
            ->with('category')
            ->where('id', '!=', $post->id)
            ->where('blog_category_id', $post->blog_category_id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('marketing.blog.show', compact('post', 'related'));
    }
}
