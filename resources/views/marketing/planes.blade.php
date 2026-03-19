@extends('marketing.layout')

@push('seo')
<title>Planes y Precios — Sin letra pequeña | SYNTIweb</title>
<meta name="description" content="Elige tu plan SYNTIweb. Landing, menú o catálogo desde $49/año. 15 días de prueba gratis.">
<meta property="og:title" content="Planes y Precios — Sin letra pequeña | SYNTIweb">
<meta property="og:description" content="Elige tu plan SYNTIweb. Landing, menú o catálogo desde $49/año. 15 días de prueba gratis.">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ asset('brand/syntiweb-og.png') }}">
<meta property="og:type" content="website">
@endpush

@section('content')
<style>
    :root {
        --studio: #4A80E4;
        --food:   #f97316;
        --cat:    #10b981;
    }
    .plan-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .plan-card:hover {
        transform: translateY(-4px);
    }
    .plan-card--highlight {
        transform: translateY(-8px);
        position: relative;
        z-index: 2;
    }
    .plan-card--highlight:hover {
        transform: translateY(-12px);
    }
    .btn-primary-studio  { background: var(--studio); color: #fff; box-shadow: 0 4px 16px color-mix(in oklch, var(--studio) 40%, transparent); }
    .btn-primary-food    { background: var(--food);   color: #fff; box-shadow: 0 4px 16px color-mix(in oklch, var(--food)   40%, transparent); }
    .btn-primary-cat     { background: var(--cat);    color: #fff; box-shadow: 0 4px 16px color-mix(in oklch, var(--cat)    40%, transparent); }
    .btn-ghost-studio    { background: color-mix(in oklch, var(--studio) 10%, transparent); color: var(--studio); border: 1.5px solid color-mix(in oklch, var(--studio) 30%, transparent); }
    .btn-ghost-food      { background: color-mix(in oklch, var(--food)   10%, transparent); color: var(--food);   border: 1.5px solid color-mix(in oklch, var(--food)   30%, transparent); }
    .btn-ghost-cat       { background: color-mix(in oklch, var(--cat)    10%, transparent); color: var(--cat);    border: 1.5px solid color-mix(in oklch, var(--cat)    30%, transparent); }
    .tab-active-studio   { background: var(--studio); color: #fff; }
    .tab-active-food     { background: var(--food);   color: #fff; }
    .tab-active-cat      { background: var(--cat);    color: #fff; }
    .check-studio { color: var(--studio); }
    .check-food   { color: var(--food); }
    .check-cat    { color: var(--cat); }
    .badge-studio { background: var(--studio); }
    .badge-food   { background: var(--food); }
    .badge-cat    { background: var(--cat); }
    .ring-studio  { box-shadow: 0 0 0 2px var(--studio), 0 20px 60px color-mix(in oklch, var(--studio) 20%, transparent); }
    .ring-food    { box-shadow: 0 0 0 2px var(--food),   0 20px 60px color-mix(in oklch, var(--food)   20%, transparent); }
    .ring-cat     { box-shadow: 0 0 0 2px var(--cat),    0 20px 60px color-mix(in oklch, var(--cat)    20%, transparent); }
</style>

{{-- Hero --}}
<section class="bg-white pt-16 pb-8 text-center border-b border-slate-100">
    <div class="max-w-2xl mx-auto px-4">
        <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-3">Precios claros · Sin letra pequeña</p>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-900 leading-tight mb-4">
            Elige tu plan
        </h1>
        <p class="text-lg text-slate-500">Para negocios venezolanos que quieren crecer de verdad.</p>
    </div>
</section>

{{-- Tabs + Panels --}}
<section class="bg-slate-50 py-12 lg:py-20" x-data="{ tab: 'studio' }">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Tabs --}}
        <div class="flex justify-center mb-12 px-4">
            <div class="flex gap-1 bg-white border border-slate-200 rounded-full p-1 shadow-sm w-full max-w-sm sm:w-auto sm:max-w-none">
                <button @click="tab='studio'"
                    :class="tab==='studio' ? 'tab-active-studio' : 'text-slate-500 hover:text-slate-800'"
                    class="flex flex-1 sm:flex-none items-center justify-center gap-1.5 px-3 sm:px-4 py-2 rounded-full text-xs sm:text-sm font-semibold transition-all duration-200 whitespace-nowrap">
                    <iconify-icon icon="tabler:layout-dashboard" width="14"></iconify-icon>
                    SYNTIstudio
                </button>
                <button @click="tab='food'"
                    :class="tab==='food' ? 'tab-active-food' : 'text-slate-500 hover:text-slate-800'"
                    class="flex flex-1 sm:flex-none items-center justify-center gap-1.5 px-3 sm:px-4 py-2 rounded-full text-xs sm:text-sm font-semibold transition-all duration-200 whitespace-nowrap">
                    <iconify-icon icon="tabler:tools-kitchen-2" width="14"></iconify-icon>
                    SYNTIfood
                </button>
                <button @click="tab='cat'"
                    :class="tab==='cat' ? 'tab-active-cat' : 'text-slate-500 hover:text-slate-800'"
                    class="flex flex-1 sm:flex-none items-center justify-center gap-1.5 px-3 sm:px-4 py-2 rounded-full text-xs sm:text-sm font-semibold transition-all duration-200 whitespace-nowrap">
                    <iconify-icon icon="tabler:shopping-bag" width="14"></iconify-icon>
                    SYNTIcat
                </button>
            </div>
        </div>

        {{-- ═══ PANEL STUDIO ═══ --}}
        <div x-show="tab==='studio'"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0">

            <div class="text-center mb-10">
                <h2 class="text-2xl font-bold text-slate-800">Tu web completa. Tu negocio en Google.</h2>
                <p class="mt-2 text-slate-500 text-sm">Lo que una agencia cobra $180–$500 estático, aquí lo tienes vivo.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-6 items-end max-w-5xl mx-auto">
                @foreach($studioPlans as $plan)
                @php
                    $isPopular = str_contains($plan->slug, 'crecimiento');
                    $tagline = match(true) {
                        str_contains($plan->slug, 'oportunidad') => 'Para validar. Cero riesgo.',
                        str_contains($plan->slug, 'crecimiento') => 'Para crecer. Tu competencia está aquí.',
                        str_contains($plan->slug, 'vision')      => 'Para dominar. Eres líder local.',
                        default => '',
                    };
                    $features = match(true) {
                        str_contains($plan->slug, 'oportunidad') => ['Landing profesional completa','Apareces en Google (SEO)','WhatsApp integrado por producto','Visible en celular y desktop','Horarios y ubicación en mapa','QR permanente incluido','BCV automático ($ / REF)','20 productos · 3 servicios','10 paletas de color'],
                        str_contains($plan->slug, 'crecimiento') => ['Todo de Oportunidad +','Sección Acerca de + imagen','Testimonios de clientes','Analytics en tiempo real','Redes sociales conectadas','Header con mensaje promocional','50 productos · 6 servicios','17 paletas de color'],
                        str_contains($plan->slug, 'vision')      => ['Todo de Crecimiento +','Preguntas frecuentes (FAQ)','Sucursales múltiples (hasta 3)','Colores personalizados ilimitados','Productos ilimitados · 9 servicios','SEO profundo + schema.org','Reporte analytics PDF email','Dominio personalizado (add-on)'],
                        default => [],
                    };
                @endphp
                <div class="plan-card bg-white rounded-2xl p-6 lg:p-8 flex flex-col
                    {{ $isPopular ? 'plan-card--highlight ring-studio shadow-2xl' : 'border border-slate-200 shadow-sm' }}">

                    @if($isPopular)
                    <div class="text-center mb-4">
                        <span class="inline-flex items-center gap-1 badge-studio text-white text-xs font-bold px-3 py-1 rounded-full">
                            El que más se vende ⭐
                        </span>
                    </div>
                    @endif

                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-1">{{ $plan->name }}</p>
                    <div class="flex items-baseline gap-1 mb-1">
                        <span class="text-5xl font-extrabold check-studio">${{ intval($plan->price_usd) }}</span>
                        <span class="text-slate-400 text-sm">/año</span>
                    </div>
                    <p class="text-sm text-slate-500 mb-6">{{ $tagline }}</p>

                    <ul class="space-y-2.5 flex-1 mb-8">
                        @foreach($features as $feat)
                        <li class="flex items-start gap-2 text-sm text-slate-700">
                            <iconify-icon icon="tabler:check" class="check-studio mt-0.5 shrink-0" width="16"></iconify-icon>
                            {{ $feat }}
                        </li>
                        @endforeach
                    </ul>

                    <a href="{{ route('onboarding.studio', ['plan_id' => $plan->id]) }}"
                       class="block w-full text-center py-3 px-4 rounded-xl font-bold text-sm transition-all hover:-translate-y-0.5
                       {{ $isPopular ? 'btn-primary-studio' : 'btn-ghost-studio' }}">
                        {{ $isPopular ? 'Crecer ahora' : ($plan->name === 'VISIÓN' || str_contains($plan->slug,'vision') ? 'Dominar mi zona' : 'Empezar gratis') }}
                    </a>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ═══ PANEL FOOD ═══ --}}
        <div x-show="tab==='food'"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0">

            <div class="text-center mb-10">
                <h2 class="text-2xl font-bold text-slate-800">Tu menú digital. Pedidos por WhatsApp.</h2>
                <p class="mt-2 text-slate-500 text-sm">Sin imprimir cartas. Sin apps complicadas.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-6 items-end max-w-5xl mx-auto">
                @foreach($foodPlans as $plan)
                @php
                    $isPopular = str_contains($plan->slug, 'vision');
                    $tagline = match(true) {
                        str_contains($plan->slug, 'oportunidad') => 'Para empezar. Tu carta digital en minutos.',
                        str_contains($plan->slug, 'crecimiento') => 'Para crecer. Más ítems, más categorías.',
                        str_contains($plan->slug, 'vision')      => 'Para dominar. Pedidos directos por WhatsApp.',
                        default => '',
                    };
                    $features = match(true) {
                        str_contains($plan->slug, 'oportunidad') => ['Menú digital activo 24/7','Lista de platos con precio','Enlace y QR para compartir','Hasta 50 ítems · 6 fotos de categoría','BCV automático ($ / REF / ambos)','WhatsApp directo'],
                        str_contains($plan->slug, 'crecimiento') => ['Todo de Oportunidad +','Categorías navegables (sticky)','Badges por ítem (nuevo/promo)','Hasta 100 ítems · 12 fotos','Analytics avanzado','SEO y Schema Restaurant'],
                        str_contains($plan->slug, 'vision')      => ['Todo de Crecimiento +','Pedido Rápido → WhatsApp','Carrito flotante con resumen','Sistema de Comandas SF-XXXX','Hasta 150 ítems · 18 fotos','Analytics completo + reporte PDF'],
                        default => [],
                    };
                @endphp
                <div class="plan-card bg-white rounded-2xl p-6 lg:p-8 flex flex-col
                    {{ $isPopular ? 'plan-card--highlight ring-food shadow-2xl' : 'border border-slate-200 shadow-sm' }}">

                    @if($isPopular)
                    <div class="text-center mb-4">
                        <span class="inline-flex items-center gap-1 badge-food text-white text-xs font-bold px-3 py-1 rounded-full">
                            El más popular ⭐
                        </span>
                    </div>
                    @endif

                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-1">{{ $plan->name }}</p>
                    <div class="flex items-baseline gap-1 mb-1">
                        <span class="text-5xl font-extrabold check-food">${{ intval($plan->price_usd) }}</span>
                        <span class="text-slate-400 text-sm">/{{ str_contains($plan->slug,'basico') ? 'mes' : (str_contains($plan->slug,'semestral') ? '6 meses' : 'año') }}</span>
                    </div>
                    <p class="text-sm text-slate-500 mb-6">{{ $tagline }}</p>

                    <ul class="space-y-2.5 flex-1 mb-8">
                        @foreach($features as $feat)
                        <li class="flex items-start gap-2 text-sm text-slate-700">
                            <iconify-icon icon="tabler:check" class="check-food mt-0.5 shrink-0" width="16"></iconify-icon>
                            {{ $feat }}
                        </li>
                        @endforeach
                    </ul>

                    <a href="{{ route('onboarding.food', ['plan_id' => $plan->id]) }}"
                       class="block w-full text-center py-3 px-4 rounded-xl font-bold text-sm transition-all hover:-translate-y-0.5
                       {{ $isPopular ? 'btn-primary-food' : 'btn-ghost-food' }}">
                        {{ $isPopular ? 'Máximo ahorro' : (str_contains($plan->slug,'semestral') ? 'Abonar semestral' : 'Empezar gratis') }}
                    </a>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ═══ PANEL CAT ═══ --}}
        <div x-show="tab==='cat'"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0">

            <div class="text-center mb-10">
                <h2 class="text-2xl font-bold text-slate-800">Tu catálogo con carrito. Ventas por WhatsApp.</h2>
                <p class="mt-2 text-slate-500 text-sm">Para tiendas, boutiques y proveedores venezolanos.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-6 items-end max-w-5xl mx-auto">
                @foreach($catPlans as $plan)
                @php
                    $isPopular = str_contains($plan->slug, 'vision');
                    $tagline = match(true) {
                        str_contains($plan->slug, 'oportunidad') => 'Para empezar. Tu catálogo en minutos.',
                        str_contains($plan->slug, 'crecimiento') => 'Para crecer. Carrito básico incluido.',
                        str_contains($plan->slug, 'vision')      => 'Para dominar. Mini Order rastreable.',
                        default => '',
                    };
                    $features = match(true) {
                        str_contains($plan->slug, 'oportunidad') => ['Catálogo visual con grid','Hasta 20 productos · 1 foto c/u','Botón WhatsApp por producto','BCV automático','QR permanente','Enlace directo al catálogo'],
                        str_contains($plan->slug, 'crecimiento') => ['Todo de Oportunidad +','Carrito básico incluido','Hasta 100 productos · 3 fotos c/u','Variantes: talla + color','Checkout directo WhatsApp','Analytics básico'],
                        str_contains($plan->slug, 'vision')      => ['Todo de Crecimiento +','Mini Order SC-XXXX rastreable','Productos ilimitados · 6 fotos c/u','Todas las variantes + opciones','Precio tachado (oferta)','Analytics completo + BCV'],
                        default => [],
                    };
                @endphp
                <div class="plan-card bg-white rounded-2xl p-6 lg:p-8 flex flex-col
                    {{ $isPopular ? 'plan-card--highlight ring-cat shadow-2xl' : 'border border-slate-200 shadow-sm' }}">

                    @if($isPopular)
                    <div class="text-center mb-4">
                        <span class="inline-flex items-center gap-1 badge-cat text-white text-xs font-bold px-3 py-1 rounded-full">
                            El más popular ⭐
                        </span>
                    </div>
                    @endif

                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-1">{{ $plan->name }}</p>
                    <div class="flex items-baseline gap-1 mb-1">
                        <span class="text-5xl font-extrabold check-cat">${{ intval($plan->price_usd) }}</span>
                        <span class="text-slate-400 text-sm">/{{ str_contains($plan->slug,'basico') ? 'mes' : (str_contains($plan->slug,'semestral') ? '6 meses' : 'año') }}</span>
                    </div>
                    <p class="text-sm text-slate-500 mb-6">{{ $tagline }}</p>

                    <ul class="space-y-2.5 flex-1 mb-8">
                        @foreach($features as $feat)
                        <li class="flex items-start gap-2 text-sm text-slate-700">
                            <iconify-icon icon="tabler:check" class="check-cat mt-0.5 shrink-0" width="16"></iconify-icon>
                            {{ $feat }}
                        </li>
                        @endforeach
                    </ul>

                    <a href="{{ route('onboarding.cat', ['plan_id' => $plan->id]) }}"
                       class="block w-full text-center py-3 px-4 rounded-xl font-bold text-sm transition-all hover:-translate-y-0.5
                       {{ $isPopular ? 'btn-primary-cat' : 'btn-ghost-cat' }}">
                        {{ $isPopular ? 'Máximo ahorro' : (str_contains($plan->slug,'semestral') ? 'Abonar semestral' : 'Empezar gratis') }}
                    </a>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</section>

{{-- Footer CTA --}}
<section class="py-12 bg-white border-t border-slate-100">
    <div class="max-w-xl mx-auto px-4 text-center">
        <p class="text-lg font-semibold text-slate-800 mb-2">¿No sabes cuál elegir?</p>
        <p class="text-slate-500 text-sm mb-6">Te ayudamos a encontrar el plan perfecto para tu negocio.</p>
        <a href="https://wa.me/584243244788?text={{ urlencode('Hola, necesito ayuda para elegir mi plan SYNTIweb') }}"
           target="_blank" rel="noopener noreferrer"
           class="inline-flex items-center gap-2 py-3 px-6 rounded-xl font-semibold text-white transition-all hover:-translate-y-0.5"
           style="background:#25d366;box-shadow:0 4px 14px 0 rgba(37,211,102,0.35)">
            <iconify-icon icon="tabler:brand-whatsapp" width="20"></iconify-icon>
            Escríbenos por WhatsApp
        </a>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Alpine ya inicializado por el layout
</script>
@endpush