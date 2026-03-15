{{-- ═══════════════════════════════════════════════════════════════════════════════
     SYNTIcat — Catálogo eCommerce + Carrito WhatsApp (REDISEÑO APP MODERNA)
     Preline 4.1.2 + Tailwind v4
═══════════════════════════════════════════════════════════════════════════════ --}}
@extends('landing.base')

@php
    $savedDisplayMode = $savedDisplayMode ?? $displayMode ?? 'reference_only';
    $currencySymbol   = $currencySettings['symbols']['reference'] ?? 'REF';
    $dollarRate       = $dollarRate ?? 36.50;
    $euroRate         = $euroRate ?? 495.00;
    $hidePrice        = $hidePrice ?? false;

    $wa = $tenant->getActiveWhatsapp() ?? null;
    $waClean = $wa ? preg_replace('/[^0-9]/', '', $wa) : '';

    $payMethods      = ($customization->payment_methods ?? []);
    $globalEnabled   = $payMethods['global'] ?? [];
    $currencyEnabled = $payMethods['currency'] ?? [];
    $planSlug = (string) ($tenant->plan->slug ?? 'cat-basico');
    if ($planSlug === 'cat-basico') {
        $globalEnabled   = ['pagoMovil', 'cash'];
        $currencyEnabled = [];
    }
    $allPayMeta = [
        // Nacionales (11 métodos)
        'pagoMovil'  => ['label' => 'Pago Móvil',    'icon' => 'device-mobile'],
        'cash'       => ['label' => 'Efectivo',       'icon' => 'cash'],
        'puntoventa' => ['label' => 'Punto de Venta', 'icon' => 'credit-card'],
        'biopago'    => ['label' => 'Biopago',        'icon' => 'fingerprint'],
        'cashea'     => ['label' => 'Cashea',         'icon' => 'wallet'],
        'krece'      => ['label' => 'Krece',          'icon' => 'trending-up'],
        'wepa'       => ['label' => 'Wepa',           'icon' => 'shopping-cart'],
        'lysto'      => ['label' => 'Lysto',          'icon' => 'calendar-dollar'],
        'chollo'     => ['label' => 'Chollo',         'icon' => 'discount-2'],
        'wally'      => ['label' => 'Wally',          'icon' => 'send-2'],
        'kontigo'    => ['label' => 'Kontigo',        'icon' => 'file-invoice'],
        // Internacionales (7 métodos)
        'zelle'      => ['label' => 'Zelle',          'icon' => 'bolt'],
        'paypal'     => ['label' => 'PayPal',         'icon' => 'brand-paypal'],
        'zinli'      => ['label' => 'Zinli',          'icon' => 'wallet-2'],
        'airtm'      => ['label' => 'AirTM',          'icon' => 'exchange'],
        'reserve'    => ['label' => 'Reserve (RSV)',  'icon' => 'shield-dollar'],
        'binancepay' => ['label' => 'Binance Pay',    'icon' => 'coins'],
        'usdt'       => ['label' => 'USDT',           'icon' => 'currency-dollar'],
    ];
    $allCurrencyMeta = [
        'usd' => ['label' => 'Dólares USD', 'icon' => 'currency-dollar'],
        'eur' => ['label' => 'Euros',        'icon' => 'currency-euro'],
    ];
    $visibleMethods    = array_filter($allPayMeta,      fn($k) => in_array($k, $globalEnabled),   ARRAY_FILTER_USE_KEY);
    $visibleCurrencies = array_filter($allCurrencyMeta, fn($k) => in_array($k, $currencyEnabled), ARRAY_FILTER_USE_KEY);
    $visiblePay        = array_merge($visibleMethods, $visibleCurrencies);

    $heroFilename = $customization->hero_main_filename ?? $customization->hero_filename ?? null;
    $heroUrl = $heroFilename ? asset('storage/tenants/' . $tenant->id . '/' . $heroFilename) : null;

    $showcase = $products->where('featured', true)->take(3)->values();
    if ($showcase->count() < 3) {
        $showcase = $products->take(3)->values();
    }

    $productImg = fn($p) => $p->image_filename
        ? asset('storage/tenants/' . $tenant->id . '/' . $p->image_filename)
        : ($p->image_url ?? null);

    $customerFields = $customization->customer_required_fields
        ?? ($tenant->settings['cat_settings']['customer_fields'] ?? ['name', 'phone', 'location']);
    $needsName     = in_array('name',     (array) $customerFields);
    $needsPhone    = true;
    $needsLocation = in_array('location', (array) $customerFields);
    $showCart      = $tenant->plan && $tenant->plan->slug !== 'cat-basico';
@endphp

@push('styles')
<style>
    html, body { overflow-x: hidden; }
    .no-scrollbar::-webkit-scrollbar{display:none}
    .no-scrollbar{-ms-overflow-style:none;scrollbar-width:none}
    .sc-drawer-overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);backdrop-filter:blur(2px);-webkit-backdrop-filter:blur(2px);z-index:200;opacity:0;pointer-events:none;transition:opacity .3s ease}
    .sc-drawer-overlay.open{opacity:1;pointer-events:auto}
    .sc-drawer{position:fixed;right:0;top:0;bottom:0;width:min(420px,95vw);z-index:201;transform:translateX(105%);transition:transform .4s cubic-bezier(.4,0,.2,1);display:flex;flex-direction:column;box-shadow:-20px 0 60px rgba(0,0,0,.12);border-top-left-radius:2rem;border-bottom-left-radius:2rem}
    .sc-drawer.open{transform:translateX(0)}
    @keyframes bump{0%,100%{transform:scale(1)}50%{transform:scale(1.3)}}
    .bump{animation:bump .25s ease-out}
    /* ── Category chips ── */
    .sc-cat-chip{display:inline-flex;align-items:center;gap:5px;padding:6px 12px;border-radius:8px;font-size:11px;font-weight:600;white-space:nowrap;flex-shrink:0;cursor:pointer;border:1px solid rgba(var(--foreground-rgb,0 0 0)/.10);background:var(--surface);color:var(--foreground);transition:all .18s}
    .sc-cat-chip.active,.sc-cat-chip[data-active="true"]{background:var(--primary);color:var(--primary-foreground);border-color:var(--primary)}
    /* ── Card editorial ── */
    .sc-product-card{position:relative;background:var(--background);border-radius:14px;overflow:hidden;border:1px solid rgba(var(--foreground-rgb,0 0 0)/.06);transition:transform .2s,box-shadow .2s;display:flex;flex-direction:column}
    .sc-product-card:hover{transform:translateY(-2px);box-shadow:0 12px 32px rgba(0,0,0,.1)}
    .sc-card-accent{position:absolute;top:0;left:0;right:0;height:2.5px;background:var(--accent-cat,var(--primary));z-index:2;transform:scaleX(0);transform-origin:left;transition:transform .35s cubic-bezier(.4,0,.2,1)}
    .sc-product-card:hover .sc-card-accent{transform:scaleX(1)}
    .sc-add-btn{width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;border:none;cursor:pointer;flex-shrink:0;transition:transform .2s,box-shadow .2s;box-shadow:0 3px 12px rgba(0,0,0,.2)}
    .sc-add-btn:hover{transform:scale(1.1)}
    .sc-badge{display:inline-flex;align-items:center;gap:3px;padding:3px 7px;border-radius:5px;font-size:9px;font-weight:700;letter-spacing:.05em;line-height:1.4}
    @media(hover:none){.sc-card-accent{transform:scaleX(1)}}
    .sc-modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);backdrop-filter:blur(2px);-webkit-backdrop-filter:blur(2px);z-index:300;display:none;align-items:center;justify-content:center;padding:16px;}
    .sc-modal{max-width:420px;width:100%;background:var(--background,#fff);border-radius:24px;box-shadow:0 30px 80px rgba(0,0,0,.25);border:1px solid rgba(0,0,0,.05);}
    .sc-field{position:relative}
    .sc-field input{width:100%;padding:1.35rem 1rem .55rem;border-radius:1rem;border:1.5px solid rgba(0,0,0,.08);background:var(--surface,#f3f4f6);font-weight:700;font-size:.875rem;outline:none;transition:border-color .2s,background .2s,box-shadow .2s;color:inherit}
    .sc-field input:focus{border-color:var(--primary,#570DF8);background:var(--background,#fff);box-shadow:0 0 0 3px color-mix(in oklch,var(--primary,#570DF8) 15%,transparent)}
    .sc-field label{position:absolute;left:1rem;top:50%;transform:translateY(-50%);font-size:.825rem;font-weight:700;color:rgba(0,0,0,.35);pointer-events:none;transition:all .18s cubic-bezier(.4,0,.2,1)}
    .sc-field input:focus+label,.sc-field input:not(:placeholder-shown)+label{top:.6rem;transform:none;font-size:.65rem;letter-spacing:.05em;color:var(--primary,#570DF8)}
    .sc-field-error{border-color:#ef4444!important;background:#fff5f5!important}
    .sc-field-error:focus{box-shadow:0 0 0 3px rgba(239,68,68,.15)!important}
    /* ── Modal producto (bottom sheet) ── */
    .sc-pm-overlay{position:fixed;inset:0;z-index:310;background:rgba(0,0,0,.55);backdrop-filter:blur(4px);display:none;align-items:flex-end;justify-content:center}
    .sc-pm-overlay.open{display:flex}
    .sc-pm-sheet{background:var(--background);width:100%;max-width:460px;border-radius:28px 28px 0 0;max-height:86vh;overflow-y:auto;overflow-x:hidden;position:relative;padding-bottom:env(safe-area-inset-bottom,16px);scrollbar-width:none}
    .sc-pm-sheet::-webkit-scrollbar{width:0;height:0}
    @media(min-width:640px){.sc-pm-sheet{border-radius:28px;margin:24px;max-height:88vh}}
    .sc-pm-img-wrap{position:relative;overflow:hidden;border-radius:20px;margin:10px 10px 0;background:var(--surface)}
    .sc-pm-img{width:100%;aspect-ratio:4/3;object-fit:cover;display:block}
    .sc-pm-close{position:absolute;top:10px;right:10px;z-index:20;width:32px;height:32px;border-radius:50%;background:rgba(0,0,0,.45);border:none;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#fff}
    @media(max-width:639px){
        .sc-pm-sheet{max-height:92dvh;border-radius:20px 20px 0 0}
        .sc-pm-img-wrap{margin:8px 8px 0}
        .sc-pm-img{aspect-ratio:16/10}
    }

    @media(max-width:480px){
        .sc-mobile-tight{font-size:1.125rem}
    }
    /* ── Bottom nav (mobile only) ── */
    .sc-bottom-nav{display:none}
    @media(max-width:767px){.sc-bottom-nav{display:block}}
    @media(max-width:767px){body{padding-bottom:72px}}
</style>
@endpush

@section('content')

{{-- 1. NAVBAR APP STYLE --}}
<header class="sticky top-0 z-[100] w-full bg-background/90 backdrop-blur-2xl" style="border-bottom:1px solid rgba(0,0,0,.07);">
    <div class="mx-auto max-w-[1280px] px-4 sm:px-8 py-2.5 flex items-center justify-between gap-3">
        {{-- Izquierda: Logo + nombre --}}
        <a href="#" class="flex items-center gap-2.5 min-w-0 shrink-0">
            @if(!empty($customization->logo_filename))
                <img src="{{ asset('storage/tenants/' . $tenant->id . '/' . $customization->logo_filename) }}"
                     alt="{{ $tenant->business_name }}"
                     class="size-10 rounded-full object-cover shrink-0"
                     onerror="this.style.display='none';">
            @else
                <div class="size-10 bg-primary rounded-full flex items-center justify-center shadow-lg shadow-primary/20 shrink-0">
                    <span class="iconify tabler--bag size-5 text-primary-foreground"></span>
                </div>
            @endif
            <span class="text-base sm:text-lg font-black tracking-tighter truncate max-w-[120px] sm:max-w-[220px]">{{ $tenant->business_name }}</span>
        </a>

        {{-- Centro: búsqueda expandible (hidden by default) --}}
        <div id="sc-search-wrap" class="flex-1 items-center gap-2 max-w-sm mx-auto" style="display:none">
            <input id="sc-search-input" type="text" placeholder="Buscar producto..."
                   class="flex-1 text-sm py-1.5 px-3 rounded-lg border border-foreground/10 bg-background focus:outline-none focus:ring-2 focus:ring-primary/20"
                   oninput="searchProducts(this.value)">
            <button onclick="scCloseSearch()" class="size-7 flex items-center justify-center rounded-lg text-foreground/40 hover:text-foreground hover:bg-surface/50 transition-colors cursor-pointer">
                <span class="iconify tabler--x size-4"></span>
            </button>
        </div>

        {{-- Derecha: search + carrito --}}
        <div class="flex items-center gap-2 shrink-0">
            <span id="sc-products-count" class="hidden xl:inline text-xs text-foreground/40 font-medium">{{ $products->count() }} producto{{ $products->count() !== 1 ? 's' : '' }}</span>

            <button id="sc-search-btn" onclick="scToggleSearch()" class="size-10 flex items-center justify-center rounded-full text-foreground/50 hover:text-foreground hover:bg-surface/50 transition-colors cursor-pointer">
                <span class="iconify tabler--search size-5"></span>
            </button>

            <button onclick="toggleDrawer()" id="sc-cart-trigger" class="hidden md:flex relative group p-2 rounded-full transition-colors bg-primary text-primary-foreground hover:bg-primary/90 shadow-xl shadow-primary/20 cursor-pointer">
                <svg aria-hidden="true" focusable="false" class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="19" cy="21" r="1"></circle>
                    <path d="M5 4H7L9 17H19L21 8H8"></path>
                </svg>
                <span id="sc-cart-count" class="absolute -top-1 -right-1 size-5 bg-error text-white text-[10px] font-black rounded-full flex items-center justify-center border-2 border-background" style="display:none">0</span>
            </button>
        </div>
    </div>
</header>

{{-- HERO SLIDER CAT --}}
@php
    $scHeroSlots = match(true) {
        in_array($planSlug, ['cat-anual', 'cat-semestral']) => [
            $customization->hero_main_filename      ?? null,
            $customization->hero_secondary_filename ?? null,
            $customization->hero_tertiary_filename  ?? null,
        ],
        default => [
            $customization->hero_main_filename ?? null,
        ],
    };
    $scHeroImages = array_values(array_filter($scHeroSlots));
    $scHeroFallback = $showcase->first() ? $productImg($showcase->first()) : null;
@endphp

@if(count($scHeroImages) > 0 || $scHeroFallback)
<div id="sc-hero-slider" class="relative w-full overflow-hidden bg-surface"
     style="aspect-ratio:16/9; max-height:320px;">

    @if(count($scHeroImages) > 0)
        @foreach($scHeroImages as $hIdx => $hImg)
        <div class="sc-hero-slide absolute inset-0 transition-opacity duration-700"
             style="opacity:{{ $hIdx === 0 ? '1' : '0' }}">
            <img src="{{ asset('storage/tenants/' . $tenant->id . '/' . $hImg) }}"
                 alt="{{ $tenant->business_name }}"
                 class="w-full h-full object-cover"
                 loading="{{ $hIdx === 0 ? 'eager' : 'lazy' }}">
        </div>
        @endforeach
    @elseif($scHeroFallback)
        <div class="sc-hero-slide absolute inset-0" style="opacity:1">
            <img src="{{ $scHeroFallback }}" alt="{{ $tenant->business_name }}"
                 class="w-full h-full object-cover" loading="eager">
        </div>
    @endif

    {{-- Overlay + info producto destacado --}}
    @if($showcase->first())
    @php $s0 = $showcase->first(); @endphp
    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 right-0 p-4 pointer-events-none">
        @if($s0->badge)
        <span class="inline-block text-[9px] font-black uppercase tracking-widest bg-primary text-primary-foreground px-2 py-1 rounded-md mb-2">
            {{ $s0->badge }}
        </span>
        @endif
        <div class="text-white font-black text-lg leading-tight drop-shadow-lg">{{ $s0->name }}</div>
        @if(!$hidePrice && $s0->price_usd)
        <div class="text-white/75 text-sm mt-0.5" data-price-usd="{{ $s0->price_usd }}">
            <span class="text-[10px] opacity-60 mr-0.5">{{ $currencySymbol }}</span>0.00
        </div>
        @endif
    </div>
    @endif

    {{-- Dots solo si hay más de 1 imagen --}}
    @if(count($scHeroImages) > 1)
    <div class="absolute bottom-3 right-4 flex gap-1.5 z-10">
        @foreach($scHeroImages as $hIdx => $hImg)
        <button class="sc-hero-dot size-1.5 rounded-full transition-all {{ $hIdx === 0 ? 'bg-white' : 'bg-white/40' }}"
                data-slide="{{ $hIdx }}"
                onclick="scHeroGoTo({{ $hIdx }})"></button>
        @endforeach
    </div>
    @endif

</div>

@if(count($scHeroImages) > 1)
<script>
(function(){
    var slides  = document.querySelectorAll('.sc-hero-slide');
    var dots    = document.querySelectorAll('.sc-hero-dot');
    var current = 0;
    var total   = slides.length;
    var timer   = null;

    window.scHeroGoTo = function(idx) {
        slides[current].style.opacity = '0';
        if(dots[current]) dots[current].classList.replace('bg-white','bg-white/40');
        current = idx;
        slides[current].style.opacity = '1';
        if(dots[current]) dots[current].classList.replace('bg-white/40','bg-white');
        resetTimer();
    };

    function next() { scHeroGoTo((current + 1) % total); }

    function resetTimer() {
        clearInterval(timer);
        timer = setInterval(next, 4000);
    }

    resetTimer();
})();
</script>
@endif

{{-- Indicador Abierto/Cerrado bajo el hero --}}
@if($showHoursIndicator ?? false)
<div class="flex justify-center py-2 bg-background">
    <span class="inline-flex items-center gap-1.5 rounded-full {{ ($isOpen ?? false) ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }} px-3 py-1 text-[11px] font-bold">
        <span class="size-1.5 rounded-full {{ ($isOpen ?? false) ? 'bg-green-500' : 'bg-red-500' }} animate-pulse"></span>
        {{ ($isOpen ?? false) ? 'ABIERTO' : 'CERRADO' }}
    </span>
</div>
@endif
@endif

{{-- 3. CATEGORY PILLS — Sticky under hero --}}
@php
    $catCategories = $catCategories ?? [];
    $catIcons = [
        'ropa' => 'tabler--hanger', 'ropa dama' => 'tabler--hanger', 'damas' => 'tabler--hanger',
        'ropa caballero' => 'tabler--shirt', 'caballeros' => 'tabler--shirt',
        'calzado' => 'tabler--shoe',
        'accesorios' => 'tabler--watch',
        'electronico' => 'tabler--device-mobile', 'electrónico' => 'tabler--device-mobile',
        'audifonos' => 'tabler--headphones', 'audífonos' => 'tabler--headphones',
        'celulares' => 'tabler--device-mobile-phone',
        'comida' => 'tabler--tools-kitchen-2',
        'bebidas' => 'tabler--cup',
        'hogar' => 'tabler--home',
        'deporte' => 'tabler--run',
        'joyeria' => 'tabler--diamond', 'joyería' => 'tabler--diamond',
        'belleza' => 'tabler--sparkles',
        'juguetes' => 'tabler--building-carousel',
        'mascotas' => 'tabler--paw',
        'libros' => 'tabler--book',
    ];
    $resolveIcon = function($name) use ($catIcons) {
        $lower = strtolower(trim($name));
        foreach ($catIcons as $key => $icon) {
            if (str_contains($lower, $key)) return $icon;
        }
        return 'tabler--tag';
    };
@endphp
<div class="sticky top-[56px] z-[90] bg-background/95 backdrop-blur-xl" style="border-bottom:1px solid rgba(0,0,0,.06)">
    <div x-data="{ openSub: null }" class="mx-auto max-w-[1280px]">
        {{-- Chips row --}}
        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar px-4 py-2.5">
            <button onclick="filterCategory('all')" data-cat="all" class="sc-cat-chip active" data-active="true">
                <span class="iconify tabler--layout-grid size-3.5"></span>
                <span>Todos</span>
            </button>
            @foreach($catCategories as $cat)
                @php
                    $hasSubs = !empty($cat['subcategories']);
                    $chipIcon = $resolveIcon($cat['name']);
                @endphp
                @if($hasSubs)
                <button @click="openSub = (openSub === '{{ $cat['name'] }}') ? null : '{{ $cat['name'] }}'; filterCategory('{{ $cat['name'] }}')"
                        data-cat="{{ $cat['name'] }}" class="sc-cat-chip cursor-pointer">
                    <span class="iconify {{ $chipIcon }} size-3.5"></span>
                    <span>{{ $cat['name'] }}</span>
                    <span class="iconify tabler--chevron-down size-3 transition-transform" :class="openSub === '{{ $cat['name'] }}' && 'rotate-180'"></span>
                </button>
                @else
                <button onclick="filterCategory('{{ $cat['name'] }}')" data-cat="{{ $cat['name'] }}" class="sc-cat-chip cursor-pointer">
                    <span class="iconify {{ $chipIcon }} size-3.5"></span>
                    <span>{{ $cat['name'] }}</span>
                </button>
                @endif
            @endforeach
        </div>
        {{-- Subchips row (shown when a category with subs is active) --}}
        @foreach($catCategories as $cat)
            @if(!empty($cat['subcategories']))
            <div x-show="openSub === '{{ $cat['name'] }}'" x-transition.opacity class="flex items-center gap-1.5 overflow-x-auto no-scrollbar px-4 pb-2">
                @foreach($cat['subcategories'] as $sub)
                <button onclick="filterCategory('{{ $cat['name'] }}', '{{ $sub['name'] }}')"
                        class="sc-cat-chip text-[10px] py-1 px-2.5 bg-surface/60 cursor-pointer" style="font-size:10px">
                    <span>{{ $sub['name'] }}</span>
                </button>
                @endforeach
            </div>
            @endif
        @endforeach
    </div>
</div>

{{-- Currency toggle (moved from header) --}}
@if(str_contains($savedDisplayMode, 'toggle'))
<div class="flex justify-center py-2 bg-background">
    <div class="flex bg-surface/50 p-1 rounded-xl border border-foreground/5 backdrop-blur-md">
        <button class="sc-curr-btn px-4 py-1.5 text-xs font-bold rounded-lg transition-all" data-currency="ref" onclick="setCurrency('ref')">{{ $currencySymbol }}</button>
        <button class="sc-curr-btn px-4 py-1.5 text-xs font-bold rounded-lg transition-all" data-currency="bs" onclick="setCurrency('bs')">Bs</button>
    </div>
</div>
@endif

{{-- 4. PRODUCT GRID — Lazy load + Paginación --}}
<section class="pb-16 lg:pb-24" id="productos">
    <div class="mx-auto max-w-[1280px] px-5 sm:px-8">
        {{-- Mensaje sin resultados --}}
        <div id="sc-no-results" class="hidden py-16 text-center">
            <span class="iconify tabler--mood-empty size-12 text-foreground/15 mx-auto mb-3"></span>
            <p class="text-foreground/40 font-bold">No se encontraron productos</p>
            <p class="text-xs text-foreground/25 mt-1">Prueba otra búsqueda o categoría</p>
        </div>

        <div id="sc-products-grid" class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4 px-4">
            @foreach($products as $product)
            @php
                $img = $productImg($product);
                $maxImages = match($tenant->plan->slug ?? 'cat-basico') {
                    'cat-anual' => 6,
                    'cat-semestral' => 3,
                    default => 1,
                };
                $galleryImages = collect($product->galleryImages ?? [])
                    ->sortBy('position')
                    ->map(fn($g) => asset('storage/tenants/' . $tenant->id . '/' . $g->filename));
                $pmImages = collect([$img])
                    ->merge($galleryImages)
                    ->filter()
                    ->unique()
                    ->take($maxImages)
                    ->values();
                $hasDiscount = $product->compare_price_usd && $product->compare_price_usd > $product->price_usd;
                $badgeLower  = $product->badge ? strtolower($product->badge) : null;
                $showBadge   = $badgeLower && !($badgeLower === 'promo' && $hasDiscount);
                $badgeCfg    = $showBadge ? match($badgeLower) {
                    'popular'   => ['icon' => 'tabler--star-filled',   'bg' => 'bg-amber-500',   'text' => 'text-white', 'label' => 'Popular'],
                    'nuevo'     => ['icon' => 'tabler--sparkles',      'bg' => 'bg-emerald-500', 'text' => 'text-white', 'label' => 'Nuevo'],
                    'promo'     => ['icon' => 'tabler--tag',           'bg' => 'bg-orange-500',  'text' => 'text-white', 'label' => 'Promo'],
                    'destacado' => ['icon' => 'tabler--bolt',          'bg' => 'bg-violet-600',  'text' => 'text-white', 'label' => 'Recomendado'],
                    default     => ['icon' => 'tabler--star',          'bg' => 'bg-primary',     'text' => 'text-primary-foreground', 'label' => $product->badge]
                } : null;
            @endphp

            <div class="sc-product-card group relative flex flex-col bg-background rounded-2xl border border-foreground/5 overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5"
                 data-category="{{ $product->category_name ?? '' }}"
                 data-subcategory="{{ $product->subcategory_name ?? '' }}"
                 data-name="{{ strtolower($product->name) }}"
                  data-search="{{ mb_strtolower(trim(($product->name ?? '') . ' ' . ($product->description ?? '') . ' ' . ($product->category_name ?? '') . ' ' . ($product->subcategory_name ?? ''))) }}"
                 style="{{ $loop->index >= 12 ? 'display:none' : '' }}"
                 data-index="{{ $loop->index }}">

                <div class="sc-card-accent" data-cat="{{ $product->category_name ?? '' }}"></div>

                {{-- ── IMAGEN 1:1 ── --}}
                <div class="relative aspect-square overflow-hidden bg-surface m-2.5 rounded-xl cursor-pointer"
                     onclick='openPM({{ $product->id }}, @json($product->name), {{ $product->price_usd ?? 0 }}, @json($img), @json($product->description ?? ""), {{ $product->compare_price_usd ?? 0 }}, {{ $product->is_featured ? "true" : "false" }}, @json($product->variants ?? []), @json($pmImages))'>

                    @if($img)
                        <img data-src="{{ $img }}" alt="{{ $product->name }}"
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 sc-lazy"
                             {{ $loop->index < 6 ? 'src=' . $img : '' }}
                             loading="lazy" onerror="this.style.display='none';">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center gap-2">
                            <span class="iconify tabler--photo size-10 text-foreground/15"></span>
                            <span class="text-[10px] font-medium text-foreground/25 uppercase tracking-widest">Sin imagen</span>
                        </div>
                    @endif

                    @if($product->is_featured ?? false)
                        <span class="sc-badge absolute top-2 left-2 z-10 bg-amber-400 text-amber-900 shadow-sm">
                            <span class="iconify tabler--star-filled size-3" aria-hidden="true"></span>
                            Especial
                        </span>
                    @endif

                    @if($hasDiscount || $badgeCfg)
                        <div class="absolute bottom-2 left-2 z-10 flex flex-wrap gap-1">
                            @if($hasDiscount)
                                <span class="sc-badge bg-red-500 text-white shadow-sm">
                                    <span class="iconify tabler--rosette-discount size-3" aria-hidden="true"></span>
                                    Oferta
                                </span>
                            @endif
                            @if($badgeCfg)
                                <span class="sc-badge {{ $badgeCfg['bg'] }} {{ $badgeCfg['text'] }} shadow-sm">
                                    <span class="iconify {{ $badgeCfg['icon'] }} size-3" aria-hidden="true"></span>
                                    {{ $badgeCfg['label'] }}
                                </span>
                            @endif
                        </div>
                    @endif

                </div>

                {{-- ── INFO + CTA ── --}}
                <div class="flex flex-col flex-1 px-3 pb-3 pt-1 gap-1">
                    <h3 class="font-bold text-sm leading-snug line-clamp-2 group-hover:text-primary transition-colors duration-200">
                        {{ Str::limit($product->name, 45) }}
                    </h3>

                    @if($product->category_name)
                    <p class="text-[10px] text-foreground/35 font-semibold uppercase tracking-wider">
                        {{ $product->category_name }}@if($product->subcategory_name) / {{ $product->subcategory_name }}@endif
                    </p>
                    @endif

                    @if($product->description)
                        <p class="text-[11px] text-foreground/45 leading-snug line-clamp-1 font-normal">
                            {{ Str::limit($product->description, 55) }}
                        </p>
                    @endif

                    <div class="flex items-end justify-between gap-2 mt-auto pt-2">

                        @if(!$hidePrice)
                        <div class="flex flex-col min-w-0">
                            <span class="text-base font-black tracking-tight leading-none" data-price-usd="{{ $product->price_usd ?? 0 }}">
                                <span class="text-[10px] font-medium opacity-40 mr-0.5 align-middle">{{ $currencySymbol }}</span>0.00
                            </span>
                            @if($hasDiscount)
                                <span class="text-[11px] text-red-400/80 line-through leading-none mt-0.5" data-price-usd="{{ $product->compare_price_usd }}">
                                    <span class="text-[9px] opacity-70 mr-0.5">{{ $currencySymbol }}</span>0.00
                                </span>
                            @endif
                        </div>
                        @endif

                        <div id="qty-row-{{ $product->id }}" class="flex items-center gap-1 bg-surface rounded-full px-1.5 py-1" style="display:none!important">
                            <button class="size-5 rounded-full bg-background flex items-center justify-center text-xs font-bold leading-none"
                                    onclick="changeQty({{ $product->id }}, -1)">−</button>
                            <span class="text-xs font-black min-w-[14px] text-center" id="qty-val-{{ $product->id }}">1</span>
                            <button class="size-5 rounded-full bg-primary text-primary-foreground flex items-center justify-center text-xs font-bold leading-none"
                                    onclick="changeQty({{ $product->id }}, 1)">+</button>
                        </div>

                        <button onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price_usd ?? 0 }}, '{{ $img }}')"
                                class="sc-add-btn bg-primary text-primary-foreground shrink-0"
                                title="Agregar al pedido">
                            <svg aria-hidden="true" focusable="false" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 5v14M5 12h14"/>
                            </svg>
                        </button>

                    </div>
                </div>

            </div>
            @endforeach
        </div>

        {{-- Botón cargar más --}}
        @if($products->count() > 12)
        <div id="sc-load-more-wrap" class="text-center mt-8">
            <button onclick="loadMoreProducts()" id="sc-load-more-btn"
                    class="inline-flex items-center gap-2 py-3 px-8 rounded-2xl bg-surface hover:bg-surface/80 text-sm font-bold text-foreground/60 transition-colors cursor-pointer border border-foreground/8">
                <span class="iconify tabler--arrow-down size-4"></span>
                Ver más productos
                <span id="sc-remaining-count" class="text-xs opacity-50"></span>
            </button>
        </div>
        @endif
    </div>
</section>


{{-- MODAL DE PRODUCTO --}}
<div id="sc-pm-overlay" class="sc-pm-overlay" onclick="if(event.target===this)closePM()">
    <div class="sc-pm-sheet" id="sc-pm-sheet">

        {{-- Imagen con botón cerrar encima --}}
        <div class="sc-pm-img-wrap">
            <button onclick="closePM()" class="sc-pm-close" aria-label="Cerrar">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
            <img id="sc-pm-img" src="" alt="" class="sc-pm-img" onerror="this.style.display='none'">
            {{-- Badge especial si aplica --}}
            <div id="sc-pm-badge-wrap" class="absolute top-3 left-3 z-10"></div>
        </div>

        {{-- Info del producto --}}
        <div class="px-5 pt-4 pb-2">
            <div class="flex items-start justify-between gap-3">
                <h2 id="sc-pm-name" class="text-xl font-black leading-tight flex-1"></h2>
                <div class="text-right shrink-0">
                    <div id="sc-pm-price" class="font-bold text-foreground/80 text-lg"></div>
                    <div id="sc-pm-compare" class="text-sm text-red-400 line-through hidden"></div>
                </div>
            </div>
            <p id="sc-pm-desc" class="text-sm text-foreground/50 mt-2 leading-relaxed hidden"></p>
        </div>

        {{-- Galería del producto (Plan Semestral/Anual) --}}
        <div id="sc-pm-thumbs" class="px-5 pt-1 pb-2 flex gap-2 overflow-x-auto no-scrollbar"></div>

        {{-- Variantes dinámicas (renderizadas por JS) --}}
        <div id="sc-pm-variants" class="px-5 pt-2 pb-0 space-y-3"></div>

        @if($showCart)
        {{-- Agregar al carrito --}}
        <div class="px-5 pb-3 pt-2" id="sc-pm-add-wrap">
            <p id="sc-pm-variant-error" class="text-xs font-semibold text-red-500 mb-2" style="display:none">Selecciona una opción antes de continuar</p>
            <button onclick="pmAddToCart()"
                    class="w-full h-12 rounded-2xl bg-foreground text-background font-black text-sm inline-flex items-center justify-center gap-2 cursor-pointer hover:bg-foreground/85 transition-colors">
                <svg aria-hidden="true" focusable="false" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                Agregar al carrito
            </button>
        </div>
        @endif

        {{-- Botones CTA --}}
        <div class="px-5 pb-5 pt-3 flex gap-3">
            <a id="sc-pm-wa-btn" href="#" target="_blank" rel="noopener noreferrer"
               class="flex-1 h-12 rounded-2xl bg-primary text-primary-foreground font-black text-sm inline-flex items-center justify-center gap-2 shadow-lg">
                <svg class="size-5 fill-current" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                Pedir por WhatsApp
            </a>
            <button onclick="sharePM()" title="Compartir"
                    class="hidden sm:flex h-12 w-12 rounded-2xl border border-foreground/12 text-foreground/60 hover:bg-surface transition items-center justify-center shrink-0">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
            </button>
        </div>

    </div>
</div>

{{-- 5. DRAWER CARRITO --}}
<div class="sc-drawer-overlay" id="sc-overlay" onclick="toggleDrawer()"></div>
<aside class="sc-drawer bg-background" id="sc-drawer">
    {{-- Header --}}
    <div class="px-7 pt-7 pb-5 flex items-center justify-between border-b border-foreground/5">
        <div>
            <h3 class="text-2xl font-black tracking-tighter">Mi Pedido</h3>
            <p class="text-[10px] text-foreground/30 font-black uppercase tracking-[.25em] mt-0.5">Shopping Bag</p>
        </div>
        <button onclick="toggleDrawer()" class="p-2 rounded-full text-sm transition-colors text-foreground/80 hover:bg-surface bg-surface/80">
            <span class="iconify tabler--x size-5"></span>
        </button>
    </div>

    {{-- Empty state --}}
    <div id="sc-empty" class="flex-1 flex flex-col items-center justify-center text-center py-14 px-7">
        <div class="size-20 rounded-3xl bg-surface flex items-center justify-center mb-4">
            <span class="iconify tabler--shopping-bag size-10 text-foreground/20"></span>
        </div>
        <p class="font-bold text-foreground/30">Tu carrito está vacío</p>
        <p class="text-xs text-foreground/20 mt-1">Explora y agrega productos</p>
    </div>

    {{-- Lista de items --}}
    <div class="flex-1 overflow-y-auto px-7 no-scrollbar" id="sc-drawer-body" style="display:none"></div>

    {{-- Footer --}}
    <div class="p-7 space-y-4 border-t border-foreground/5" id="sc-drawer-footer" style="display:none">

        @if(count($visiblePay) > 0)
        <div class="flex items-center gap-2 flex-wrap">
            @foreach($visiblePay as $key => $pm)
                @php
                $pmIcon = match($key) {
                    'pagoMovil'  => 'iconify tabler--device-mobile',
                    'cash'       => 'iconify tabler--cash',
                    'puntoventa' => 'iconify tabler--credit-card',
                    'biopago'    => 'iconify tabler--fingerprint',
                    'cashea'     => 'iconify tabler--wallet',
                    'krece'      => 'iconify tabler--trending-up',
                    'wepa'       => 'iconify tabler--shopping-cart',
                    'lysto'      => 'iconify tabler--calendar-dollar',
                    'chollo'     => 'iconify tabler--discount-2',
                    'wally'      => 'iconify tabler--send-2',
                    'kontigo'    => 'iconify tabler--file-invoice',
                    'zelle'      => 'iconify tabler--bolt',
                    'paypal'     => 'iconify tabler--brand-paypal',
                    'zinli'      => 'iconify tabler--moneybag',
                    'airtm'      => 'iconify tabler--exchange',
                    'reserve'    => 'iconify tabler--shield-dollar',
                    'binancepay' => 'iconify tabler--currency-bitcoin',
                    'usdt'       => 'iconify tabler--coin',
                    'usd'        => 'iconify tabler--currency-dollar',
                    'eur'        => 'iconify tabler--currency-euro',
                    default      => 'iconify tabler--cash',
                };
                @endphp
                <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-foreground/50 bg-surface rounded-xl px-2.5 py-1.5">
                    <span class="{{ $pmIcon }} size-3.5 shrink-0"></span>
                    {{ $pm['label'] }}
                </span>
            @endforeach
        </div>
        @endif

        <div class="bg-surface/60 p-5 rounded-[1.5rem] border border-foreground/5">
            <div class="flex justify-between items-center text-foreground/40 text-xs font-black uppercase tracking-widest mb-1">
                <span>Subtotal</span>
                <span id="sc-total-label">{{ $currencySymbol }}</span>
            </div>
            <div class="text-4xl font-black tracking-tighter" id="sc-total">0.00</div>
        </div>

        @if($waClean)
        <button onclick="sendWhatsApp()" class="flex items-center justify-center w-full h-14 rounded-[1.5rem] border-none font-black text-base gap-2.5 shadow-xl text-white transition-colors"
                style="background:#25D366;">
            <span class="iconify tabler--brand-whatsapp size-6"></span>
            Finalizar por WhatsApp
        </button>
        @else
        <button class="flex items-center justify-center w-full h-14 rounded-[1.5rem] bg-primary text-primary-foreground hover:bg-primary/90 font-black text-base transition-colors">
            Ir al resumen
        </button>
        @endif
    </div>
</aside>

{{-- MODAL Datos Cliente --}}
@if($needsName || $needsPhone || $needsLocation)
<div id="sc-data-modal" class="sc-modal-overlay">
    <div class="sc-modal p-6 space-y-5">
        <div class="flex items-start gap-3">
            <div class="size-10 rounded-2xl bg-primary/10 flex items-center justify-center text-primary shrink-0">
                <svg aria-hidden="true" focusable="false" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="7" r="4"></circle>
                    <path d="M5.5 21a6.5 6.5 0 0 1 13 0"></path>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-lg font-black">Antes de enviar</p>
                <p class="text-sm text-foreground/60">Déjanos tus datos para personalizar tu pedido.</p>
            </div>
            <button onclick="closeDataModal()" class="p-2 rounded-full text-sm text-foreground/80 hover:bg-surface transition-colors">
                <svg aria-hidden="true" focusable="false" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        @if($needsName)
        <div class="sc-field">
            <input type="text" id="sc-customer-name" placeholder=" " autocomplete="given-name" inputmode="text">
            <label for="sc-customer-name">Nombre</label>
        </div>
        @endif
        @if($needsPhone)
        <div class="sc-field">
            <input type="tel" id="sc-customer-phone" placeholder=" " autocomplete="tel" inputmode="numeric" maxlength="13">
            <label for="sc-customer-phone">WhatsApp (58XXXXXXXXXX)</label>
        </div>
        @endif
        @if($needsLocation)
        <div class="sc-field">
            <input type="text" id="sc-customer-location" placeholder=" " autocomplete="off" inputmode="text">
            <label for="sc-customer-location">Referencia / Sector</label>
        </div>
        @endif

        <div class="flex gap-3">
            <button onclick="confirmDataAndSend()" class="py-2 px-4 rounded-xl font-medium transition-colors bg-primary text-primary-foreground hover:bg-primary/90 flex-1 font-black">Enviar por WhatsApp</button>
            <button onclick="closeDataModal()" class="py-2 px-4 rounded-xl font-medium transition-colors text-foreground/80 hover:bg-surface">Cancelar</button>
        </div>
    </div>
</div>
@endif

{{-- MODAL Tienda Cerrada (confirmación) --}}
<div id="sc-closed-modal" class="sc-modal-overlay">
    <div class="sc-modal p-6 space-y-5">
        <div class="flex items-start gap-3">
            <div class="size-10 rounded-2xl bg-red-100 flex items-center justify-center text-red-600 shrink-0">
                <svg aria-hidden="true" focusable="false" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-lg font-black text-red-700">Tienda cerrada</p>
                <p class="text-sm text-foreground/60">Igual puedes enviar tu mensaje. Te responderemos cuando abramos.</p>
            </div>
        </div>
        <div class="flex gap-3">
            <button id="sc-closed-send-btn" class="py-3 px-4 rounded-xl font-black transition-colors bg-primary text-primary-foreground hover:bg-primary/90 flex-1 cursor-pointer">Enviar de todas formas</button>
            <button onclick="closeClosedModal()" class="py-3 px-4 rounded-xl font-medium transition-colors text-foreground/80 hover:bg-surface cursor-pointer">Cancelar</button>
        </div>
    </div>
</div>

{{-- MODAL Pedido Confirmado --}}
<div id="sc-confirm-modal" class="sc-modal-overlay">
    <div class="sc-modal p-6 space-y-5">
        <div class="flex items-start gap-3">
            <div class="size-10 rounded-2xl bg-green-100 flex items-center justify-center text-green-600 shrink-0">
                <svg aria-hidden="true" focusable="false" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-lg font-black">Pedido enviado</p>
                <p class="text-sm text-foreground/60">Tu pedido fue registrado exitosamente.</p>
            </div>
        </div>
        <div class="text-center py-2">
            <p class="text-sm text-foreground/50 mb-1">Tu número de pedido:</p>
            <p id="sc-confirm-order-id" class="text-3xl font-black tracking-tight text-primary"></p>
            <p class="text-xs text-foreground/40 mt-2">Guárdalo para hacer seguimiento</p>
        </div>
        <div class="flex flex-col gap-3">
            <button id="sc-confirm-wa-btn" class="py-3 px-4 rounded-xl font-black transition-colors bg-primary text-primary-foreground hover:bg-primary/90 flex items-center justify-center gap-2 cursor-pointer">
                <svg aria-hidden="true" focusable="false" class="size-4" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M11.999 2C6.477 2 2 6.477 2 12c0 1.89.525 3.657 1.438 5.168L2 22l4.985-1.407A9.96 9.96 0 0 0 12 22c5.523 0 10-4.477 10-10S17.522 2 11.999 2zm.001 18a7.96 7.96 0 0 1-4.08-1.124l-.292-.174-3.01.849.854-2.935-.19-.301A7.96 7.96 0 0 1 4 12c0-4.411 3.589-8 8-8s8 3.589 8 8-3.588 8-7.999 8z"/></svg>
                Abrir WhatsApp
            </button>
            <button onclick="closeConfirmModal()" class="py-3 px-4 rounded-xl font-medium transition-colors text-foreground/80 hover:bg-surface cursor-pointer">Cerrar</button>
        </div>
    </div>
</div>

{{-- 6. FOOTER (shared component — same as Studio) --}}
    @include('landing.sections.footer', ['sConfig' => $customization->getSectionConfig('footer')])

{{-- 7. BOTTOM NAVIGATION — App nav mobile --}}
<nav class="sc-bottom-nav fixed bottom-0 left-0 right-0 z-[150] bg-background/95 backdrop-blur-xl"
     style="border-top:1px solid rgba(0,0,0,.07); padding-bottom: env(safe-area-inset-bottom, 0px)">
    <div class="flex items-center justify-around py-2 max-w-lg mx-auto">
        <button onclick="scrollToTop()" class="sc-nav-btn flex flex-col items-center gap-1 py-1 px-3 rounded-xl transition-colors text-primary cursor-pointer">
            <span class="iconify tabler--home size-5"></span>
            <span class="text-[10px] font-semibold">Inicio</span>
        </button>
        <button onclick="scToggleSearch(); scrollToProducts()" class="sc-nav-btn flex flex-col items-center gap-1 py-1 px-3 rounded-xl transition-colors text-foreground/40 hover:text-foreground cursor-pointer">
            <span class="iconify tabler--search size-5"></span>
            <span class="text-[10px] font-semibold">Buscar</span>
        </button>
        @if($showCart)
        <button onclick="toggleDrawer()" class="sc-nav-btn relative flex flex-col items-center gap-1 py-1 px-3 rounded-xl transition-colors text-foreground/40 hover:text-foreground cursor-pointer">
            <span class="iconify tabler--shopping-bag size-5"></span>
            <span id="sc-bottom-cart-badge" class="absolute top-0 right-2 size-4 bg-red-500 text-white text-[9px] font-black rounded-full flex items-center justify-center" style="display:none">0</span>
            <span class="text-[10px] font-semibold">Carrito</span>
        </button>
        @endif
        @if($waClean)
        <a href="https://wa.me/{{ $waClean }}" target="_blank"
           class="sc-nav-btn flex flex-col items-center gap-1 py-1 px-3 rounded-xl transition-colors text-foreground/40 hover:text-foreground cursor-pointer">
            <span class="iconify tabler--brand-whatsapp size-5"></span>
            <span class="text-[10px] font-semibold">Contacto</span>
        </a>
        @endif
        <button onclick="scrollToFooter()" class="sc-nav-btn flex flex-col items-center gap-1 py-1 px-3 rounded-xl transition-colors text-foreground/40 hover:text-foreground cursor-pointer">
            <span class="iconify tabler--info-circle size-5"></span>
            <span class="text-[10px] font-semibold">Info</span>
        </button>
    </div>
</nav>

@if(str_contains($savedDisplayMode, 'toggle'))
{{-- Pill flotante toggle moneda --}}
<div id="sc-currency-pill"
     class="fixed z-[140] flex items-center gap-1 rounded-xl border border-foreground/10 bg-background/95 backdrop-blur-xl shadow-lg overflow-hidden"
     style="bottom: 76px; right: 16px;">
    <button onclick="setCurrency('ref')"
            data-currency="ref"
            class="sc-curr-btn px-3 py-2 text-[11px] font-black tracking-wide transition-all">
        {{ $currencySymbol }}
    </button>
    <div class="w-px h-4 bg-foreground/10"></div>
    <button onclick="setCurrency('bs')"
            data-currency="bs"
            class="sc-curr-btn px-3 py-2 text-[11px] font-black tracking-wide transition-all">
        Bs
    </button>
    @if(in_array($savedDisplayMode, ['toggle_usd', 'toggle_all']))
    <div class="w-px h-4 bg-foreground/10"></div>
    <button onclick="setCurrency('usd')"
            data-currency="usd"
            class="sc-curr-btn px-3 py-2 text-[11px] font-black tracking-wide transition-all">
        $
    </button>
    @endif
</div>

<script>
(function() {
    var pill = document.getElementById('sc-currency-pill');
    if (!pill) return;
    function syncPill(active) {
        pill.querySelectorAll('.sc-curr-btn').forEach(function(btn) {
            var isActive = btn.getAttribute('data-currency') === active;
            btn.style.background    = isActive ? 'var(--primary)' : 'transparent';
            btn.style.color         = isActive ? 'var(--primary-foreground)' : 'var(--foreground)';
            btn.style.borderRadius  = isActive ? '10px' : '';
        });
    }
    var stored = localStorage.getItem('sc_currency') || 'ref';
    syncPill(stored);
    var orig = window.setCurrency;
    window.setCurrency = function(mode) {
        if (orig) orig(mode);
        syncPill(mode);
        localStorage.setItem('sc_currency', mode);
    };
    // Ajuste dinámico: sube el pill cuando el bottom nav está visible
    function adjustPill() {
        var nav = document.querySelector('.sc-bottom-nav');
        if (!nav) return;
        var navH = nav.offsetHeight;
        pill.style.bottom = (navH + 8) + 'px';
    }
    adjustPill();
    window.addEventListener('resize', adjustPill);
})();
</script>
@endif

@endsection

@push('scripts')
<script>
(function(){
    'use strict';
    const CURRENCY_MODE = @json($savedDisplayMode);
    const CURRENCY_SYMBOL = @json($currencySymbol);
    const EXCHANGE_RATE = @json($dollarRate);
    const EURO_RATE = @json($euroRate);
    let currentCurrency = (CURRENCY_MODE === 'bolivares_only') ? 'Bs.' : (CURRENCY_MODE === 'euro_toggle' ? '€' : CURRENCY_SYMBOL);
    var cart = {};

    function formatPrice(usdPrice, isPlain = false) {
        const val = parseFloat(usdPrice) || 0;
        let rate = (currentCurrency === 'Bs.') ? (CURRENCY_MODE === 'euro_toggle' ? EURO_RATE : EXCHANGE_RATE) : 1;
        let formatted = (val * rate).toLocaleString('es-VE', {minimumFractionDigits:2});
        if(isPlain) return currentCurrency + ' ' + formatted;
        return '<span class="text-xs opacity-40 mr-1">' + currentCurrency + '</span>' + formatted;
    }

    window.setCurrency = function(mode) {
        currentCurrency = (mode === 'bs') ? 'Bs.' : (mode === 'eur' ? '€' : CURRENCY_SYMBOL);
        document.querySelectorAll('[data-price-usd]').forEach(el => el.innerHTML = formatPrice(el.getAttribute('data-price-usd')));
        document.querySelectorAll('.sc-curr-btn').forEach(btn => {
            let active = (btn.dataset.currency === 'ref' && currentCurrency === CURRENCY_SYMBOL) || (btn.dataset.currency === 'bs' && currentCurrency === 'Bs.');
            btn.className = `sc-curr-btn px-4 py-1.5 text-xs font-bold rounded-lg transition-all ${active ? 'bg-background shadow-lg text-primary' : 'text-foreground/40'}`;
        });
        renderDrawer();
    };

    var _scPage = 1;
    var _scPerPage = 12;
    var _scActiveCat = 'all';
    var _scActiveSub = '';
    var _scSearchRaw = '';
    var _scSearchTerm = '';
    var _scSearchAnchored = false;
    var _scIndex = [];

    function normalizeSearchText(value) {
        var text = String(value || '').toLowerCase();
        if (typeof text.normalize === 'function') {
            text = text.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
        }
        return text
            .replace(/[^a-z0-9\s]/g, ' ')
            .replace(/\s+/g, ' ')
            .trim();
    }

    window.scToggleSearch = function() {
        var wrap = document.getElementById('sc-search-wrap');
        var btn = document.getElementById('sc-search-btn');
        if (!wrap || !btn) return;

        var isOpen = wrap.style.display === 'flex';
        if (isOpen) {
            wrap.style.display = 'none';
            btn.classList.remove('text-foreground');
            btn.classList.add('text-foreground/50');
            return;
        }

        wrap.style.display = 'flex';
        btn.classList.remove('text-foreground/50');
        btn.classList.add('text-foreground');
        var input = document.getElementById('sc-search-input');
        if (input) setTimeout(function() { input.focus(); }, 10);
    };

    window.scCloseSearch = function() {
        var input = document.getElementById('sc-search-input');
        if (input) input.value = '';
        searchProducts('');
        scToggleSearch();
    };

    function buildSearchIndex() {
        _scIndex = Array.from(document.querySelectorAll('#sc-products-grid .sc-product-card')).map(function(card) {
            var rawText = String(card.textContent || '').toLowerCase();
            return {
                el: card,
                category: card.dataset.category || '',
                subcategory: card.dataset.subcategory || '',
                raw: rawText,
                norm: normalizeSearchText(rawText),
            };
        });
    }

    function getFilteredCards() {
        if (!_scIndex.length) buildSearchIndex();
        return _scIndex.filter(function(item) {
            var matchCat = _scActiveCat === 'all' || item.category === _scActiveCat;
            var matchSub = !_scActiveSub || item.subcategory === _scActiveSub;
            var matchSearch = !_scSearchTerm
                || item.norm.indexOf(_scSearchTerm) !== -1
                || item.raw.indexOf(_scSearchRaw) !== -1;
            return matchCat && matchSub && matchSearch;
        }).map(function(item) {
            return item.el;
        });
    }

    function renderVisibleCards() {
        var all = Array.from(document.querySelectorAll('#sc-products-grid .sc-product-card'));
        var filtered = getFilteredCards();
        var limit = _scPage * _scPerPage;
        var visibleCount = 0;
        var hasActiveSearchOrFilter = !!_scSearchTerm || _scActiveCat !== 'all' || !!_scActiveSub;

        var heroShowcase = document.getElementById('sc-hero-showcase');
        if (heroShowcase) {
            heroShowcase.style.display = hasActiveSearchOrFilter ? 'none' : '';
        }

        all.forEach(function(card) {
            card.style.display = 'none';
        });

        filtered.forEach(function(card, i) {
            if (i < limit) {
                card.style.display = '';
                visibleCount++;
                // Lazy load images
                var imgs = card.querySelectorAll('img.sc-lazy[data-src]');
                imgs.forEach(function(img) {
                    if (!img.src || img.src === window.location.href) {
                        img.src = img.dataset.src;
                    }
                });
            }
        });

        // Update count
        var countText = filtered.length + ' producto' + (filtered.length !== 1 ? 's' : '');
        var countEl = document.getElementById('sc-products-count');
        if (countEl) countEl.textContent = countText;

        // No results
        var noRes = document.getElementById('sc-no-results');
        if (noRes) noRes.classList.toggle('hidden', filtered.length > 0);

        // Si hay búsqueda/filtro activo, mantener foco visual en el grid de resultados.
        if (hasActiveSearchOrFilter && window.scrollY > 180) {
            var productos = document.getElementById('productos');
            if (productos) {
                productos.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        // Load more button
        var lmWrap = document.getElementById('sc-load-more-wrap');
        if (lmWrap) {
            var remaining = filtered.length - limit;
            lmWrap.style.display = remaining > 0 ? '' : 'none';
            var remEl = document.getElementById('sc-remaining-count');
            if (remEl) remEl.textContent = '(' + remaining + ')';
        }
    }

    window.filterCategory = function(catId, subName) {
        _scActiveCat = catId || 'all';
        _scActiveSub = subName || '';
        _scPage = 1;

        // Update chip active states
        document.querySelectorAll('.sc-cat-chip').forEach(function(btn) {
            var active = String(btn.dataset.cat) === String(_scActiveCat);
            if (active) {
                btn.classList.add('active');
                btn.setAttribute('data-active', 'true');
            } else {
                btn.classList.remove('active');
                btn.removeAttribute('data-active');
            }
        });

        renderVisibleCards();
    };

    window.searchProducts = function(term) {
        _scSearchRaw = String(term || '').toLowerCase().trim();
        _scSearchTerm = normalizeSearchText(term);
        // Reindexar por seguridad ante cambios dinámicos de cards.
        buildSearchIndex();

        // Evita el efecto de "subida" tecla a tecla: al iniciar búsqueda, anclamos una vez al catálogo.
        if (_scSearchRaw && !_scSearchAnchored) {
            var productos = document.getElementById('productos');
            if (productos && window.scrollY > (productos.offsetTop + 120)) {
                productos.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
            _scSearchAnchored = true;
        }
        if (!_scSearchRaw) {
            _scSearchAnchored = false;
        }

        // Si se escribe en búsqueda, buscar globalmente para no perder resultados por filtro activo.
        if (_scSearchTerm) {
            _scActiveCat = 'all';
            _scActiveSub = '';
            document.querySelectorAll('.sc-cat-chip').forEach(function(btn) {
                var active = String(btn.dataset.cat) === 'all';
                btn.classList.toggle('active', active);
                if (active) btn.setAttribute('data-active', 'true');
                else btn.removeAttribute('data-active');
            });
        }
        _scPage = 1;
        renderVisibleCards();
    };

    window.loadMoreProducts = function() {
        _scPage++;
        renderVisibleCards();
    };

    window.addToCart = function(id, name, price, img, variantLabel) {
        if (!window.__tenantIsOpen) showClosedToast();
        const safeVariantKey = (variantLabel || '')
            .toLowerCase()
            .replace(/\s+/g, '_')
            .replace(/[^a-z0-9_:\-]/g, '');
        const key = safeVariantKey ? `${id}::${safeVariantKey}` : String(id);
        const isNew = !cart[key];
        if (cart[key]) cart[key].qty++;
        else cart[key] = {
            id: String(id),
            name,
            price: parseFloat(price) || 0,
            qty: 1,
            img,
            variant: variantLabel || null,
        };
        updateBadge();
        renderDrawer();

        // Only reflect inline qty controls for non-variant items.
        if (!variantLabel) {
            const qr = document.getElementById('qty-row-' + id);
            if (qr) qr.style.cssText = 'display:flex!important';
            const qv = document.getElementById('qty-val-' + id);
            if (qv) qv.textContent = cart[key].qty;
        }

        if (isNew && Object.keys(cart).length === 1) toggleDrawer();
    };

    window.changeQty = function(id, delta) {
        if (!cart[id]) return;
        cart[id].qty += delta;
        if (cart[id].qty <= 0) {
            delete cart[id];
            const qr = document.getElementById('qty-row-' + id);
            if (qr) qr.style.cssText = 'display:none!important';
        } else {
            const qv = document.getElementById('qty-val-' + id);
            if (qv) qv.textContent = cart[id].qty;
        }
        updateBadge();
        renderDrawer();
    };

    function updateBadge() {
        let total = Object.values(cart).reduce((a, b) => a + b.qty, 0);
        const b = document.getElementById('sc-cart-count');
        b.style.display = total > 0 ? 'flex' : 'none';
        b.textContent = total;
        b.classList.remove('bump'); void b.offsetWidth; b.classList.add('bump');
        var bottomBadge = document.getElementById('sc-bottom-cart-badge');
        if(bottomBadge) {
            if(total > 0) { bottomBadge.style.display='flex'; bottomBadge.textContent=total; }
            else { bottomBadge.style.display='none'; }
        }
    }

    window.toggleDrawer = function() {
        document.getElementById('sc-overlay').classList.toggle('open');
        document.getElementById('sc-drawer').classList.toggle('open');
        document.body.style.overflow = document.getElementById('sc-drawer').classList.contains('open') ? 'hidden' : '';
    };

    function renderDrawer() {
        const body   = document.getElementById('sc-drawer-body');
        const footer = document.getElementById('sc-drawer-footer');
        const empty  = document.getElementById('sc-empty');

        body.innerHTML = '';
        const keys = Object.keys(cart);

        if (keys.length === 0) {
            empty.style.display = 'flex';
            body.style.display  = 'none';
            footer.style.display = 'none';
            return;
        }

        empty.style.display  = 'none';
        body.style.display   = 'block';
        footer.style.display = 'block';

        let totalUsd = 0;
        keys.forEach(id => {
            const item = cart[id];
            totalUsd += item.price * item.qty;
            const div = document.createElement('div');
            div.className = 'flex items-center gap-4 py-4 border-b border-foreground/5 last:border-0';
            div.innerHTML = `
                <div style="width:56px;height:56px;border-radius:14px;overflow:hidden;background:var(--surface,#e5e7eb);flex-shrink:0">
                    <img style="width:100%;height:100%;object-fit:cover" src="${item.img || ''}">
                </div>
                <div style="flex:1;min-width:0">
                    <p style="font-weight:800;font-size:.875rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;margin-bottom:2px">${item.name}</p>
                    ${item.variant ? `<p style="font-size:.68rem;opacity:.55;margin-bottom:2px">${item.variant}</p>` : ''}
                    <p style="font-size:.75rem;font-weight:700;opacity:.45">${item.qty} × ${formatPrice(item.price, true)}</p>
                </div>
                <button onclick="changeQty('${id}', -1)"
                        style="width:32px;height:32px;border-radius:10px;border:none;cursor:pointer;background:transparent;color:#ef4444;font-size:1.25rem;font-weight:900;line-height:1;flex-shrink:0"
                        title="Quitar">×</button>
            `;
            body.appendChild(div);
        });
        document.getElementById('sc-total').innerHTML = formatPrice(totalUsd);
        document.getElementById('sc-total-label').textContent = currentCurrency;
    }

    function openDataModal() {
        const modal = document.getElementById('sc-data-modal');
        if (modal) modal.style.display = 'flex';
    }
    function closeDataModal() {
        const modal = document.getElementById('sc-data-modal');
        if (modal) modal.style.display = 'none';
    }

    async function buildAndSend(name, phone, loc) {
        const subdomain = @json($tenant->subdomain);
        const isPlanAnual = @json($tenant->plan->slug ?? '');
        const cartItems = Object.values(cart).map(i => ({
            title: i.name,
            qty: i.qty,
            price: i.price,
            variant: i.variant ?? null
        }));

        if (isPlanAnual === 'cat-anual') {
            try {
                const res = await fetch(`/${subdomain}/checkout`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ name, phone, location: loc, items: cartItems })
                });
                const data = await res.json();
                if (data.success && data.whatsapp_url) {
                    var confirmModal = document.getElementById('sc-confirm-modal');
                    var orderIdEl   = document.getElementById('sc-confirm-order-id');
                    var waBtn       = document.getElementById('sc-confirm-wa-btn');
                    if (orderIdEl) orderIdEl.textContent = data.order_id ?? '';
                    if (waBtn) waBtn.onclick = function() { window.open(data.whatsapp_url, '_blank'); };
                    closeDataModal();
                    if (confirmModal) confirmModal.style.display = 'flex';
                    return;
                }
            } catch(e) {
                console.error('Checkout error:', e);
            }
        }

        // Fallback planes Básico y Semestral — mensaje sin SC-XXXX
        const waNumber = @json($waClean);
        if (!waNumber) return;
        let businessName = @json($tenant->business_name);
        let greeting = name
            ? `¡Hola! Soy *${name}* y vengo de la web de *${businessName}* 🛍️`
            : `¡Hola! Les escribo desde la web de *${businessName}* 🛍️`;
        let totalUsd = Object.values(cart).reduce((a,b) => a + (b.price * b.qty), 0);
        let msg = greeting + '\n\n*Mi pedido:*\n';
        msg += Object.values(cart).map(i => {
            const variantText = i.variant ? ` (${i.variant})` : '';
            return `• ${i.name}${variantText} ×${i.qty} (${formatPrice(i.price * i.qty, true)})`;
        }).join('\n');
        msg += '\n\n*Total: ' + formatPrice(totalUsd, true) + '*';
        if (phone) msg += `\n\n*WhatsApp:* ${phone}`;
        if (loc) msg += `\n\n📍 *Mi referencia:* ${loc}`;
        window.open(`https://wa.me/${waNumber}?text=${encodeURIComponent(msg)}`, '_blank');
    }

    function openClosedModal(onConfirm) {
        var modal = document.getElementById('sc-closed-modal');
        if (!modal) { onConfirm(); return; }
        modal.style.display = 'flex';
        var btn = document.getElementById('sc-closed-send-btn');
        var handler = function() {
            btn.removeEventListener('click', handler);
            closeClosedModal();
            onConfirm();
        };
        btn.addEventListener('click', handler);
    }
    window.closeClosedModal = function() {
        var modal = document.getElementById('sc-closed-modal');
        if (modal) modal.style.display = 'none';
    };

    window.closeConfirmModal = function() {
        var modal = document.getElementById('sc-confirm-modal');
        if (modal) modal.style.display = 'none';
        cart = {};
        renderDrawer();
        var drawer = document.getElementById('sc-drawer');
        var overlay = document.getElementById('sc-overlay');
        if (drawer) drawer.classList.remove('open');
        if (overlay) overlay.classList.remove('open');
        document.body.style.overflow = '';
    };

    window.sendWhatsApp = function() {
        var _doSend = function() {
            @if(!$needsName && !$needsPhone && !$needsLocation)
                buildAndSend('', '', '');
                return;
            @endif

            const nameEl  = document.getElementById('sc-customer-name');
            const phoneEl = document.getElementById('sc-customer-phone');
            const locEl   = document.getElementById('sc-customer-location');
            const name    = nameEl ? nameEl.value.trim() : '';
            const phone   = phoneEl ? phoneEl.value.trim().replace(/\D/g, '') : '';
            const loc     = locEl  ? locEl.value.trim()  : '';

            if ((nameEl && !name) || (phoneEl && !phone) || (locEl && !loc)) {
                openDataModal();
                return;
            }

            if (phoneEl && !/^58(412|414|416|422|424|426)\d{7}$/.test(phone)) {
                phoneEl.classList.add('sc-field-error');
                phoneEl.focus();
                return;
            }

            if (name) localStorage.setItem('sc_customer_name', name);
            if (phone) localStorage.setItem('sc_customer_phone', phone);
            buildAndSend(name, phone, loc);
        };

        if (!window.__tenantIsOpen) {
            openClosedModal(_doSend);
            return;
        }
        _doSend();
    };

    window.confirmDataAndSend = function() {
        const nameEl  = document.getElementById('sc-customer-name');
        const phoneEl = document.getElementById('sc-customer-phone');
        const locEl   = document.getElementById('sc-customer-location');
        const name    = nameEl ? nameEl.value.trim() : '';
        const phone   = phoneEl ? phoneEl.value.trim().replace(/\D/g, '') : '';
        const loc     = locEl  ? locEl.value.trim()  : '';

        @if($needsName)
        if (nameEl && !name) {
            nameEl.classList.add('sc-field-error');
            nameEl.focus();
            return;
        }
        @endif

        @if($needsPhone)
        if (phoneEl && !/^58(412|414|416|422|424|426)\d{7}$/.test(phone)) {
            phoneEl.classList.add('sc-field-error');
            phoneEl.focus();
            return;
        }
        @endif

        if (name) localStorage.setItem('sc_customer_name', name);
        if (phone) localStorage.setItem('sc_customer_phone', phone);
        closeDataModal();
        buildAndSend(name, phone, loc);
    };

    document.addEventListener('DOMContentLoaded', () => {
        setCurrency('ref');
        renderVisibleCards();

        window.addEventListener('resize', function() {
            var wrap = document.getElementById('sc-search-wrap');
            if (!wrap) return;
            // Close search on resize if open
            if (window.innerWidth >= 768 && wrap.style.display === 'flex') {
                // Keep open on desktop — no action needed
            }
        });

        var searchInput = document.getElementById('sc-search-input');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                searchProducts(searchInput.value);
            });
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    searchProducts(searchInput.value);
                }
            });
        }

        buildSearchIndex();

        const savedName = localStorage.getItem('sc_customer_name');
        if (savedName) {
            const el = document.getElementById('sc-customer-name');
            if (el) el.value = savedName;
        }

        const savedPhone = localStorage.getItem('sc_customer_phone');
        if (savedPhone) {
            const el = document.getElementById('sc-customer-phone');
            if (el) el.value = savedPhone;
        }

        ['sc-customer-name', 'sc-customer-phone', 'sc-customer-location'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('input', () => el.classList.remove('sc-field-error'));
        });
    });
})();
</script>



{{-- Acento por categoría --}}
<script>
// ── Modal de Producto ──
var _pmCurrent = {};
var _pmSelections = {};

function openPM(id, name, price, img, desc, comparePrice, isFeatured, variants, images) {
    _pmSelections = {};
    _pmCurrent = {
        id: id,
        name: name,
        price: price,
        img: img,
        desc: desc,
        comparePrice: comparePrice,
        variants: variants || [],
        images: (images && images.length ? [images[0]] : (img ? [img] : [])),
        currentImageIndex: 0
    };

    function setPMImage(index) {
        var imgs = _pmCurrent.images || [];
        if (!imgs.length) {
            var noImgEl = document.getElementById('sc-pm-img');
            if (noImgEl) {
                noImgEl.src = '';
                noImgEl.style.display = 'none';
            }
            return;
        }
        _pmCurrent.currentImageIndex = index;
        var pmImgEl = document.getElementById('sc-pm-img');
        pmImgEl.src = imgs[index];
        pmImgEl.style.display = 'block';

        var thumbs = document.querySelectorAll('.sc-pm-thumb');
        thumbs.forEach(function(t, i) {
            t.style.opacity = (i === index) ? '1' : '.55';
            t.style.borderColor = (i === index) ? 'var(--primary)' : 'rgba(0,0,0,.08)';
        });
    }

    // Imagen
    var thumbsWrap = document.getElementById('sc-pm-thumbs');
    if (thumbsWrap) {
        thumbsWrap.innerHTML = '';
        thumbsWrap.style.display = 'none';
    }
    setPMImage(0);

    // Nombre
    document.getElementById('sc-pm-name').textContent = name;

    // Precio — usando el formateador del catálogo si existe
    var priceEl = document.getElementById('sc-pm-price');
    if (price && price > 0) {
        priceEl.setAttribute('data-price-usd', price);
        priceEl.innerHTML = (typeof formatPrice === 'function')
            ? formatPrice(price)
            : '<span style="font-size:10px;opacity:.4;margin-right:2px">REF</span>' + Number(price).toFixed(2);
        priceEl.style.display = '';
    } else {
        priceEl.style.display = 'none';
    }

    // Precio tachado
    var compareEl = document.getElementById('sc-pm-compare');
    if (comparePrice && comparePrice > price) {
        compareEl.setAttribute('data-price-usd', comparePrice);
        compareEl.innerHTML = (typeof formatPrice === 'function')
            ? formatPrice(comparePrice)
            : 'REF ' + Number(comparePrice).toFixed(2);
        compareEl.classList.remove('hidden');
    } else {
        compareEl.classList.add('hidden');
    }

    // Descripción
    var descEl = document.getElementById('sc-pm-desc');
    if (desc && desc.trim()) {
        descEl.textContent = desc;
        descEl.classList.remove('hidden');
    } else {
        descEl.classList.add('hidden');
    }

    // Badge especial
    var badgeWrap = document.getElementById('sc-pm-badge-wrap');
    badgeWrap.innerHTML = isFeatured
        ? '<span style="display:inline-flex;align-items:center;gap:4px;background:#fbbf24;color:#78350f;padding:5px 10px;border-radius:8px;font-size:11px;font-weight:700;">★ Especial</span>'
        : '';

    // Botón WhatsApp
    var waBtn = document.getElementById('sc-pm-wa-btn');
    var waNumber = '{{ preg_replace("/\D/", "", $tenant->getActiveWhatsapp() ?? "") }}';
    var msg = 'Hola, vi tu catálogo y me interesa: ' + name
            + (price > 0 ? ' — REF ' + Number(price).toFixed(2) : '')
            + '. ¿Está disponible?';
    waBtn.href = waNumber
        ? 'https://wa.me/' + waNumber + '?text=' + encodeURIComponent(msg)
        : '#';

    // Variantes
    var variantsEl = document.getElementById('sc-pm-variants');
    var variantError = document.getElementById('sc-pm-variant-error');
    if (variantsEl) {
        variantsEl.innerHTML = '';
        var pmVariants = _pmCurrent.variants || [];
        if (pmVariants.length) {
            pmVariants.forEach(function(v) {
                var groupEl = document.createElement('div');
                var label = document.createElement('p');
                label.className = 'text-xs font-black text-foreground/50 uppercase tracking-widest mb-1.5';
                label.textContent = v.name;
                groupEl.appendChild(label);
                var chipsEl = document.createElement('div');
                chipsEl.className = 'flex flex-wrap gap-2';
                (v.options || []).forEach(function(opt) {
                    var chip = document.createElement('button');
                    chip.textContent = opt;
                    chip.className = 'sc-pm-chip px-3 py-1.5 rounded-full border border-foreground/15 text-sm font-semibold text-foreground/70 hover:border-primary hover:text-primary transition-colors cursor-pointer';
                    chip.dataset.group = v.name;
                    chip.dataset.value = opt;
                    chip.onclick = function() {
                        chipsEl.querySelectorAll('.sc-pm-chip').forEach(function(c) {
                            c.classList.remove('bg-primary', 'text-primary-foreground', 'border-primary');
                            c.classList.add('border-foreground/15', 'text-foreground/70');
                        });
                        chip.classList.add('bg-primary', 'text-primary-foreground', 'border-primary');
                        chip.classList.remove('border-foreground/15', 'text-foreground/70');
                        _pmSelections[v.name] = opt;
                        if (variantError) variantError.style.display = 'none';
                    };
                    chipsEl.appendChild(chip);
                });
                groupEl.appendChild(chipsEl);
                variantsEl.appendChild(groupEl);
            });
        }
    }
    if (variantError) variantError.style.display = 'none';

    // Abrir overlay
    document.getElementById('sc-pm-overlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function pmAddToCart() {
    var variants = _pmCurrent.variants || [];
    var errorEl = document.getElementById('sc-pm-variant-error');
    if (variants.length > 0) {
        var missing = variants.find(function(v) { return !_pmSelections[v.name]; });
        if (missing) {
            if (errorEl) errorEl.style.display = '';
            return;
        }
    }
    var variantStr = Object.keys(_pmSelections).map(function(k) { return k + ': ' + _pmSelections[k]; }).join(' / ');
    addToCart(_pmCurrent.id, _pmCurrent.name, _pmCurrent.price, _pmCurrent.img, variantStr || null);
    closePM();
}

function closePM() {
    document.getElementById('sc-pm-overlay').classList.remove('open');
    document.body.style.overflow = '';
}

function sharePM() {
    var d = _pmCurrent;
    var url = window.location.href;
    var text = d.name + (d.price > 0 ? ' — REF ' + Number(d.price).toFixed(2) : '') + ' en {{ $tenant->business_name ?? "" }}';
    if (navigator.share) {
        navigator.share({ title: d.name, text: text, url: url }).catch(function(){});
    } else {
        window.open('https://wa.me/?text=' + encodeURIComponent(text + '\n' + url), '_blank');
    }
}

// Cerrar con Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closePM();
});

// Lazy load observer
(function() {
    if (!('IntersectionObserver' in window)) return;
    var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (!entry.isIntersecting) return;
            var img = entry.target;
            if (img.dataset.src && !img.src.includes(img.dataset.src)) {
                img.src = img.dataset.src;
            }
            observer.unobserve(img);
        });
    }, { rootMargin: '200px' });
    document.querySelectorAll('img.sc-lazy[data-src]').forEach(function(img) {
        observer.observe(img);
    });
})();
</script>

<script>
function heroNav(catId) {
    // 1. Activar filtro de categoría
    var chips = document.querySelectorAll('.sc-cat-chip');
    chips.forEach(function(p) {
        p.classList.remove('active');
        p.removeAttribute('data-active');
    });
    var targetChip = document.querySelector('.sc-cat-chip[data-cat="' + catId + '"]');
    if (targetChip) {
        targetChip.classList.add('active');
        targetChip.setAttribute('data-active', 'true');
    } else {
        var allChip = document.querySelector('.sc-cat-chip[data-cat="all"]');
        if (allChip) { allChip.classList.add('active'); allChip.setAttribute('data-active','true'); }
    }

    // 2. Filtrar productos
    filterCategory(catId);

    // 3. Scroll suave al catálogo
    setTimeout(function() {
        var target = document.getElementById('productos');
        if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }, 180);
}
</script>

<script>
(function() {
    // Paleta de acentos — rota por category_id asignando un color distinto a cada categoría
    const PALETTE = ['#E8440A','#2563EB','#059669','#7C3AED','#DB2777','#D97706','#0891B2','#DC2626'];
    const catMap = {};
    let idx = 0;
    document.querySelectorAll('.sc-card-accent[data-cat]').forEach(function(el) {
        const cat = el.getAttribute('data-cat');
        if (!cat) return;
        if (!(cat in catMap)) { catMap[cat] = PALETTE[idx % PALETTE.length]; idx++; }
        el.closest('.sc-product-card').style.setProperty('--accent-cat', catMap[cat]);
    });
})();
</script>

{{-- SyntiTrack --}}
<script>
function scrollToTop() { window.scrollTo({top:0,behavior:'smooth'}); }
function scrollToProducts() {
    var el = document.getElementById('productos');
    if(el) el.scrollIntoView({behavior:'smooth'});
}
function scrollToFooter() {
    var el = document.querySelector('footer');
    if(el) el.scrollIntoView({behavior:'smooth'});
}
</script>
<script>
(function() {
    const TENANT_ID = {{ $tenant->id }};
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    function track(eventType) {
        fetch('/api/analytics/track', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ tenant_id: TENANT_ID, event_type: eventType })
        }).catch(() => {});
    }
    track('pageview');
    document.addEventListener('click', function(e) {
        if (e.target.closest('a[href*="wa.me"]')) track('click_whatsapp');
        if (e.target.closest('a[href^="tel:"]')) track('click_call');
    });
    setInterval(() => track('time_on_page'), 30000);
})();
</script>
@endpush