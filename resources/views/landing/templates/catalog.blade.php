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
    if ($tenant->plan_id === 1) {
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
        ?? ($tenant->settings['cat_settings']['customer_fields'] ?? ['name', 'location']);
    $needsName     = in_array('name',     (array) $customerFields);
    $needsLocation = in_array('location', (array) $customerFields);
    $showCart      = $tenant->plan && $tenant->plan->slug !== 'cat-basico';
@endphp

@push('styles')
<style>
    .no-scrollbar::-webkit-scrollbar{display:none}
    .no-scrollbar{-ms-overflow-style:none;scrollbar-width:none}
    .sc-drawer-overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);backdrop-filter:blur(2px);-webkit-backdrop-filter:blur(2px);z-index:200;opacity:0;pointer-events:none;transition:opacity .3s ease}
    .sc-drawer-overlay.open{opacity:1;pointer-events:auto}
    .sc-drawer{position:fixed;right:0;top:0;bottom:0;width:min(420px,95vw);z-index:201;transform:translateX(105%);transition:transform .4s cubic-bezier(.4,0,.2,1);display:flex;flex-direction:column;box-shadow:-20px 0 60px rgba(0,0,0,.12);border-top-left-radius:2rem;border-bottom-left-radius:2rem}
    .sc-drawer.open{transform:translateX(0)}
    @keyframes bump{0%,100%{transform:scale(1)}50%{transform:scale(1.3)}}
    .bump{animation:bump .25s ease-out}
    /* ── Hero editorial bento ── */
    .sc-bento-cell{position:relative;overflow:hidden;cursor:pointer;background:var(--surface)}
    .sc-bento-cell.sc-flash{animation:sc-flash-anim .35s ease-out}
    @keyframes sc-flash-anim{0%{opacity:1}40%{opacity:.6}100%{opacity:1}}
    .sc-bento-cell img{width:100%;height:100%;object-fit:cover;transition:transform .5s ease;display:block}
    .sc-bento-cell:hover img{transform:scale(1.04)}
    .sc-bento-main{aspect-ratio:16/9}@media(min-width:768px){.sc-bento-main{aspect-ratio:21/9}}
    .sc-bento-sec{aspect-ratio:1/1}
    .sc-cell-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(10,10,20,.78) 0%,rgba(10,10,20,0) 55%)}
    .sc-cell-info{position:absolute;bottom:0;left:0;right:0;padding:14px}
    .sc-cell-tag{display:inline-block;background:var(--primary);color:#fff;font-size:9px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;padding:3px 7px;border-radius:3px;margin-bottom:5px}
    .sc-cell-name{font-size:18px;font-weight:700;color:#fff;line-height:1.1;letter-spacing:-.3px}
    .sc-bento-main .sc-cell-name{font-size:22px}
    .sc-cell-price{font-size:13px;font-weight:300;color:rgba(255,255,255,.8);margin-top:3px}
    .sc-wa-pill{position:absolute;bottom:12px;right:12px;background:rgba(255,255,255,.14);border:1px solid rgba(255,255,255,.28);border-radius:20px;padding:5px 12px;color:#fff;font-size:11px;font-weight:500;display:flex;align-items:center;gap:5px;cursor:pointer}
    /* ── Pills de categoría ── */
    .sc-cat-pill{flex-shrink:0;padding:6px 16px;border-radius:20px;font-size:12px;font-weight:400;cursor:pointer;border:1px solid rgba(var(--foreground-rgb,0 0 0)/.12);background:transparent;color:var(--foreground);opacity:.6;transition:all .2s;white-space:nowrap}
    .sc-cat-pill.active,.sc-cat-pill[data-active="true"]{background:var(--foreground);color:var(--background);border-color:var(--foreground);font-weight:500;opacity:1}
    /* ── Card editorial rediseño ── */
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
    .sc-pm-sheet{background:var(--background);width:100%;max-width:460px;border-radius:28px 28px 0 0;max-height:90vh;overflow-y:auto;position:relative;padding-bottom:env(safe-area-inset-bottom,16px)}
    @media(min-width:640px){.sc-pm-sheet{border-radius:28px;margin:24px;max-height:88vh}}
    .sc-pm-img-wrap{position:relative;overflow:hidden;border-radius:20px;margin:10px 10px 0;background:var(--surface)}
    .sc-pm-img{width:100%;aspect-ratio:4/3;object-fit:cover;display:block}
    .sc-pm-close{position:absolute;top:10px;right:10px;z-index:20;width:32px;height:32px;border-radius:50%;background:rgba(0,0,0,.45);border:none;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#fff}
</style>
@endpush

@section('content')

{{-- 1. NAVBAR APP STYLE --}}
<header class="sticky top-0 z-[100] w-full bg-background/90 backdrop-blur-2xl" style="border-bottom:1px solid rgba(0,0,0,.07);">
    <div class="mx-auto max-w-[1280px] px-5 sm:px-8 flex items-center justify-between h-16">
        <a href="#" class="flex items-center gap-3 min-w-0">
            @if(!empty($customization->logo_filename))
                <img src="{{ asset('storage/tenants/' . $tenant->id . '/' . $customization->logo_filename) }}"
                     alt="{{ $tenant->business_name }}"
                     class="size-10 rounded-xl object-cover shrink-0"
                     onerror="this.style.display='none';">
            @else
                <div class="size-10 bg-primary rounded-xl flex items-center justify-center shadow-lg shadow-primary/20 shrink-0">
                    <span class="iconify tabler--bag size-6 text-primary-foreground"></span>
                </div>
            @endif
            <span class="text-xl font-black tracking-tighter truncate">{{ $tenant->business_name }}</span>
        </a>

        <div class="flex items-center gap-3">
            @if(str_contains($savedDisplayMode, 'toggle'))
            <div class="hidden md:flex bg-surface/50 p-1 rounded-xl border border-foreground/5 backdrop-blur-md">
                <button class="sc-curr-btn px-4 py-1.5 text-xs font-bold rounded-lg transition-all" data-currency="ref" onclick="setCurrency('ref')">{{ $currencySymbol }}</button>
                <button class="sc-curr-btn px-4 py-1.5 text-xs font-bold rounded-lg transition-all" data-currency="bs" onclick="setCurrency('bs')">Bs</button>
            </div>
            @endif

            {{-- Indicador Abierto/Cerrado --}}
            @if($showHoursIndicator ?? false)
            <span class="hidden sm:inline-flex items-center gap-1.5 rounded-full {{ ($isOpen ?? false) ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }} px-3 py-1 text-xs font-bold">
                <span class="h-1.5 w-1.5 rounded-full {{ ($isOpen ?? false) ? 'bg-green-500' : 'bg-red-500' }} animate-pulse"></span>
                {{ ($isOpen ?? false) ? 'ABIERTO' : 'CERRADO' }}
            </span>
            @endif

            @if($waClean)
            <a href="https://wa.me/{{ $waClean }}" target="_blank"
               class="hidden sm:flex text-sm py-1.5 px-3 rounded-2xl font-medium transition-colors gap-1.5 border-none font-bold text-white"
               style="background:#25D366;">
                <svg aria-hidden="true" focusable="false" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16.7 14.3c-.3-.2-1.7-.8-1.9-.9-.3-.1-.5-.2-.7.2s-.8.9-1 .9-.5 0-.9-.4c-.5-.4-1-1-1.2-1.3-.1-.3 0-.5.1-.6.2-.2.3-.3.5-.5.2-.2.2-.3.3-.5.1-.3 0-.5 0-.6s-.7-1.8-1-2.4c-.3-.6-.5-.6-.7-.6h-.6c-.2 0-.6.1-.9.4-.3.3-1.1 1-1.1 2.4s1.1 2.7 1.3 2.9c.2.2 2.1 3.2 5.1 4.3.7.3 1.2.4 1.6.5.7.1 1.4.1 1.9.1.6 0 1.7-.7 1.9-1.3.2-.6.2-1.2.2-1.3 0-.1-.3-.2-.6-.4z"></path>
                    <path d="M12 3a9 9 0 0 0-9 9 8.9 8.9 0 0 0 1.2 4.5L3 21l4.7-1.2A9 9 0 1 0 12 3Z"></path>
                </svg>
                Contactar
            </a>
            @endif

            <button onclick="toggleDrawer()" id="sc-cart-trigger" class="relative group p-2 rounded-full transition-colors bg-primary text-primary-foreground hover:bg-primary/90 shadow-xl shadow-primary/20">
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

{{-- 2. HERO EDITORIAL BENTO --}}
@if($showcase->count() >= 1)
<section class="bg-background">

    {{-- Label destacados --}}
    <div class="mx-auto max-w-[1280px] flex items-center gap-3 px-4 sm:px-8 pt-6 pb-3">
        @if($tenant->slogan)
            <p class="text-[10px] font-black uppercase tracking-[.2em] text-primary shrink-0">{{ $tenant->slogan }}</p>
        @else
            <p class="text-[10px] font-bold uppercase tracking-[.2em] text-foreground/40 shrink-0">Destacados</p>
        @endif
        <div class="flex-1 h-px bg-foreground/10"></div>
    </div>

    {{-- Bento grid —  móvil: 2 cols, desktop: 12 cols --}}
    <div class="mx-auto max-w-[1280px] px-4 sm:px-8 pb-2">
        <div class="grid grid-cols-2 gap-4">

            @php $s0 = $showcase->get(0); $s0img = $productImg($s0); @endphp

            {{-- CELDA PRINCIPAL — siempre full-width --}}
            <div class="sc-bento-cell sc-bento-main col-span-2 rounded-2xl"
                 onclick="heroNav('all')">
                @if($s0img)
                    <img src="{{ $s0img }}" alt="{{ $s0->name }}" onerror="this.style.display='none'">
                @else
                    <div class="w-full h-full min-h-[200px] md:min-h-[420px] flex items-center justify-center bg-surface">
                        <span class="iconify tabler--photo size-12 text-foreground/15"></span>
                    </div>
                @endif
                <div class="sc-cell-overlay"></div>
                <div class="sc-cell-info">
                    <div class="sc-cell-tag">Destacado</div>
                    <div class="sc-cell-name">{{ $s0->name }}</div>
                    @if(!$hidePrice && $s0->price_usd)
                        <div class="sc-cell-price" data-price-usd="{{ $s0->price_usd }}">
                            <span class="text-[10px] opacity-60 mr-0.5">{{ $currencySymbol }}</span>0.00
                        </div>
                    @endif
                </div>
                {{-- WA pill solo visible en la principal --}}
                @if($waClean)
                <div class="sc-wa-pill">
                    <svg class="size-3.5 fill-white" viewBox="0 0 24 24"><path d="M13 5l7 7-7 7M5 12h14" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/></svg>
                    Ver colección
                </div>
                @endif
            </div>

            {{-- CELDAS SECUNDARIAS (2 y 3) --}}
            @foreach($showcase->slice(1, 2) as $item)
            @php $simg = $productImg($item); @endphp
            <div class="sc-bento-cell sc-bento-sec rounded-2xl"
                 onclick="heroNav('{{ $item->category_id ?? 'all' }}')">
                @if($simg)
                    <img src="{{ $simg }}" alt="{{ $item->name }}" onerror="this.style.display='none'">
                @else
                    <div class="w-full h-full min-h-[140px] flex items-center justify-center bg-surface">
                        <span class="iconify tabler--photo size-8 text-foreground/15"></span>
                    </div>
                @endif
                <div class="sc-cell-overlay"></div>
                <div class="sc-cell-info">
                    @if($item->badge)<div class="sc-cell-tag">{{ $item->badge }}</div>@endif
                    <div class="sc-cell-name">{{ $item->name }}</div>
                    @if(!$hidePrice && $item->price_usd)
                        <div class="sc-cell-price" data-price-usd="{{ $item->price_usd }}">
                            <span class="text-[10px] opacity-60 mr-0.5">{{ $currencySymbol }}</span>0.00
                        </div>
                    @endif
                </div>
            </div>
            @endforeach

        </div>
    </div>
</section>
@endif

{{-- 3. SEPARADOR + CATÁLOGO HEADER + CATEGORY PILLS --}}
{{-- Separador editorial --}}
<div class="mx-auto max-w-[1280px] px-4 sm:px-8 pt-10 pb-2">
    <div class="flex items-center gap-4">
        <div class="flex-1 h-px bg-foreground/10"></div>
        <p class="text-[10px] font-black uppercase tracking-[.25em] text-foreground/30 shrink-0">Nuestros productos</p>
        <div class="flex-1 h-px bg-foreground/10"></div>
    </div>
</div>

<div class="mx-auto max-w-[1280px] px-4 sm:px-8 pt-4">
    <div class="flex items-baseline justify-between mb-4">
        <h2 class="text-2xl font-black tracking-tight">Catálogo</h2>
        <span class="text-xs text-foreground/40 font-medium">{{ $products->count() }} producto{{ $products->count() !== 1 ? 's' : '' }}</span>
    </div>
    <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-4">
        <button onclick="filterCategory('all')" data-cat="all" class="sc-cat-pill active"
                data-active="true">Todos</button>
        @if(isset($categories))
            @foreach($categories as $cat)
                <button onclick="filterCategory('{{ $cat->id }}')" data-cat="{{ $cat->id }}"
                        class="sc-cat-pill">{{ $cat->name }}</button>
            @endforeach
        @endif
    </div>
</div>

{{-- 4. PRODUCT GRID — Diseño Editorial --}}
<section class="pb-16 lg:pb-24" id="productos">
    <div class="mx-auto max-w-[1280px] px-5 sm:px-8">
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 sm:gap-6">
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
                 data-category="{{ $product->category_id ?? 'all' }}">

                {{-- Línea de acento por categoría (aparece en hover) --}}
                <div class="sc-card-accent" data-cat="{{ $product->category_id ?? '' }}"></div>

                {{-- ── IMAGEN 1:1 ── --}}
                <div class="relative aspect-[3/4] overflow-hidden bg-surface m-2.5 rounded-xl cursor-pointer"
                     onclick='openPM({{ $product->id }}, @json($product->name), {{ $product->price_usd ?? 0 }}, @json($img), @json($product->description ?? ""), {{ $product->compare_price_usd ?? 0 }}, {{ $product->is_featured ? "true" : "false" }}, @json($product->variants ?? []), @json($pmImages))'>

                    @if($img)
                        <img src="{{ $img }}" alt="{{ $product->name }}"
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                             loading="lazy" onerror="this.style.display='none';">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center gap-2">
                            <span class="iconify tabler--photo size-10 text-foreground/15"></span>
                            <span class="text-[10px] font-medium text-foreground/25 uppercase tracking-widest">Sin imagen</span>
                        </div>
                    @endif

                    {{-- Chip is_featured — estrella dorada top-left --}}
                    @if($product->is_featured ?? false)
                        <span class="sc-badge absolute top-2 left-2 z-10 bg-amber-400 text-amber-900 shadow-sm">
                            <span class="iconify tabler--star-filled size-3" aria-hidden="true"></span>
                            Especial
                        </span>
                    @endif

                    {{-- Badges bottom-left: Oferta + badge semántico --}}
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

                </div>{{-- /imagen --}}

                {{-- ── INFO + CTA ── --}}
                <div class="flex flex-col flex-1 px-3 pb-3 pt-1 gap-1">

                    {{-- Nombre --}}
                    <h3 class="font-bold text-sm leading-snug line-clamp-2 group-hover:text-primary transition-colors duration-200">
                        {{ Str::limit($product->name, 45) }}
                    </h3>

                    {{-- Descripción corta (si existe) --}}
                    @if($product->description)
                        <p class="text-[11px] text-foreground/45 leading-snug line-clamp-1 font-normal">
                            {{ Str::limit($product->description, 55) }}
                        </p>
                    @endif

                    {{-- Precio + botón add (mt-auto empuja al fondo) --}}
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

                        {{-- Qty control (visible cuando está en carrito) --}}
                        <div id="qty-row-{{ $product->id }}" class="flex items-center gap-1 bg-surface rounded-full px-1.5 py-1" style="display:none!important">
                            <button class="size-5 rounded-full bg-background flex items-center justify-center text-xs font-bold leading-none"
                                    onclick="changeQty({{ $product->id }}, -1)">−</button>
                            <span class="text-xs font-black min-w-[14px] text-center" id="qty-val-{{ $product->id }}">1</span>
                            <button class="size-5 rounded-full bg-primary text-primary-foreground flex items-center justify-center text-xs font-bold leading-none"
                                    onclick="changeQty({{ $product->id }}, 1)">+</button>
                        </div>

                        {{-- Botón + agregar al carrito --}}
                        <button onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price_usd ?? 0 }}, '{{ $img }}')"
                                class="sc-add-btn bg-primary text-primary-foreground shrink-0"
                                title="Agregar al pedido">
                            <svg aria-hidden="true" focusable="false" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 5v14M5 12h14"/>
                            </svg>
                        </button>

                    </div>{{-- /precio+cta --}}

                </div>{{-- /info --}}

            </div>{{-- /sc-product-card --}}
            @endforeach
        </div>
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
                    class="h-12 w-12 rounded-2xl border border-foreground/12 text-foreground/60 hover:bg-surface transition flex items-center justify-center shrink-0">
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
@if($needsName || $needsLocation)
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
            <label for="sc-customer-name">👤 ¿Cómo te llamas?</label>
        </div>
        @endif
        @if($needsLocation)
        <div class="sc-field">
            <input type="text" id="sc-customer-location" placeholder=" " autocomplete="off" inputmode="text">
            <label for="sc-customer-location">📍 Referencia / Sector</label>
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

    window.filterCategory = function(catId) {
        document.querySelectorAll('.sc-cat-pill').forEach(btn => {
            const active = String(btn.dataset.cat) === String(catId);
            if (active) {
                btn.className = 'sc-cat-pill text-sm py-1.5 px-6 rounded-2xl font-medium transition-colors bg-primary text-primary-foreground hover:bg-primary/90 shrink-0';
            } else {
                btn.className = 'sc-cat-pill text-sm py-1.5 px-6 rounded-2xl font-medium transition-colors text-foreground/80 hover:bg-surface border border-foreground/10 whitespace-nowrap shrink-0';
            }
        });
        document.querySelectorAll('.sc-product-card').forEach(card => {
            const match = catId === 'all' || String(card.dataset.category) === String(catId);
            card.style.display = match ? '' : 'none';
        });
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

    async function buildAndSend(name, loc) {
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
                    body: JSON.stringify({ name, location: loc, items: cartItems })
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
            @if(!$needsName && !$needsLocation)
                buildAndSend('', '');
                return;
            @endif

            const nameEl  = document.getElementById('sc-customer-name');
            const locEl   = document.getElementById('sc-customer-location');
            const name    = nameEl ? nameEl.value.trim() : '';
            const loc     = locEl  ? locEl.value.trim()  : '';

            if ((nameEl && !name) || (locEl && !loc)) {
                openDataModal();
                return;
            }

            if (name) localStorage.setItem('sc_customer_name', name);
            buildAndSend(name, loc);
        };

        if (!window.__tenantIsOpen) {
            openClosedModal(_doSend);
            return;
        }
        _doSend();
    };

    window.confirmDataAndSend = function() {
        const nameEl  = document.getElementById('sc-customer-name');
        const locEl   = document.getElementById('sc-customer-location');
        const name    = nameEl ? nameEl.value.trim() : '';
        const loc     = locEl  ? locEl.value.trim()  : '';

        @if($needsName)
        if (nameEl && !name) {
            nameEl.classList.add('sc-field-error');
            nameEl.focus();
            return;
        }
        @endif

        if (name) localStorage.setItem('sc_customer_name', name);
        closeDataModal();
        buildAndSend(name, loc);
    };

    document.addEventListener('DOMContentLoaded', () => {
        setCurrency('ref');

        const savedName = localStorage.getItem('sc_customer_name');
        if (savedName) {
            const el = document.getElementById('sc-customer-name');
            if (el) el.value = savedName;
        }

        ['sc-customer-name', 'sc-customer-location'].forEach(id => {
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
        images: (images && images.length ? images : (img ? [img] : [])),
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
        (_pmCurrent.images || []).forEach(function(src, index) {
            var tBtn = document.createElement('button');
            tBtn.type = 'button';
            tBtn.className = 'sc-pm-thumb shrink-0 size-14 rounded-xl overflow-hidden border-2 transition-all cursor-pointer';
            tBtn.style.borderColor = 'rgba(0,0,0,.08)';
            tBtn.style.opacity = '.55';
            tBtn.innerHTML = '<img src="' + src + '" alt="Thumb" class="w-full h-full object-cover">';
            tBtn.onclick = function() { setPMImage(index); };
            thumbsWrap.appendChild(tBtn);
        });
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
</script>

<script>
function heroNav(catId) {
    // 1. Flash visual en la celda tocada
    event.currentTarget.classList.add('sc-flash');
    setTimeout(function() {
        event.currentTarget && event.currentTarget.classList.remove('sc-flash');
    }, 400);

    // 2. Activar filtro de categoría
    var pills = document.querySelectorAll('.sc-cat-pill');
    pills.forEach(function(p) {
        p.classList.remove('active');
        p.removeAttribute('data-active');
    });
    var targetPill = document.querySelector('.sc-cat-pill[data-cat="' + catId + '"]');
    if (targetPill) {
        targetPill.classList.add('active');
        targetPill.setAttribute('data-active', 'true');
    } else {
        var allPill = document.querySelector('.sc-cat-pill[data-cat="all"]');
        if (allPill) { allPill.classList.add('active'); allPill.setAttribute('data-active','true'); }
    }

    // 3. Filtrar productos
    filterCategory(catId);

    // 4. Scroll suave al catálogo con un pequeño delay para que se vea el flash primero
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