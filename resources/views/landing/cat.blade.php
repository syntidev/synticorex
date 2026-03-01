{{-- ═══════════════════════════════════════════════════════════════════════════════
     SYNTIcat — Catálogo eCommerce + Carrito WhatsApp
     Template independiente, NO hereda de base.blade.php
     FlyonUI 2.4.1 + Tailwind v4 classes
═══════════════════════════════════════════════════════════════════════════════ --}}
@php
    $customPalette = $tenant->settings['engine_settings']['visual']['custom_palette'] ?? null;
    $effectiveTheme = $customPalette ? 'custom' : ($themeSlug ?? 'light');

    // Currency
    $savedDisplayMode = $savedDisplayMode ?? $displayMode ?? 'reference_only';
    $currencySymbol   = $currencySettings['symbols']['reference'] ?? 'REF';
    $dollarRate       = $dollarRate ?? 36.50;
    $euroRate         = $euroRate ?? 495.00;
    $hidePrice        = $hidePrice ?? false;

    // WhatsApp
    $wa = $tenant->whatsapp_sales ?? $tenant->whatsapp ?? null;
    $waClean = $wa ? preg_replace('/[^0-9]/', '', $wa) : '';

    // Payment methods
    $payMethods      = ($customization->payment_methods ?? []);
    $globalEnabled   = $payMethods['global'] ?? [];
    $currencyEnabled = $payMethods['currency'] ?? [];
    if ($tenant->plan_id === 1) {
        $globalEnabled   = ['pagoMovil', 'cash'];
        $currencyEnabled = [];
    }
    $allPayMeta = [
        'pagoMovil'  => ['label' => 'Pago Móvil',    'icon' => 'device-mobile'],
        'cash'       => ['label' => 'Efectivo',       'icon' => 'cash'],
        'puntoventa' => ['label' => 'Punto de Venta', 'icon' => 'credit-card'],
        'biopago'    => ['label' => 'Biopago',        'icon' => 'fingerprint'],
        'cashea'     => ['label' => 'Cashea',         'icon' => 'shopping-cart-dollar'],
        'krece'      => ['label' => 'Krece',          'icon' => 'trending-up'],
        'wepa'       => ['label' => 'Wepa',           'icon' => 'device-mobile-dollar'],
        'lysto'      => ['label' => 'Lysto',          'icon' => 'calendar-dollar'],
        'chollo'     => ['label' => 'Chollo',         'icon' => 'tag'],
        'zelle'      => ['label' => 'Zelle',          'icon' => 'bolt'],
        'zinli'      => ['label' => 'Zinli',          'icon' => 'wallet'],
        'paypal'     => ['label' => 'PayPal',         'icon' => 'brand-paypal'],
    ];
    $allCurrencyMeta = [
        'usd' => ['label' => 'Dólares USD', 'icon' => 'currency-dollar'],
        'eur' => ['label' => 'Euros',        'icon' => 'currency-euro'],
    ];
    $visibleMethods    = array_filter($allPayMeta,      fn($k) => in_array($k, $globalEnabled),   ARRAY_FILTER_USE_KEY);
    $visibleCurrencies = array_filter($allCurrencyMeta, fn($k) => in_array($k, $currencyEnabled), ARRAY_FILTER_USE_KEY);
    $visiblePay        = array_merge($visibleMethods, $visibleCurrencies);

    // Hero image
    $heroFilename = $customization->hero_main_filename ?? $customization->hero_filename ?? null;
    $heroUrl = $heroFilename ? asset('storage/tenants/' . $tenant->id . '/' . $heroFilename) : null;

    // Logo
    $logoFilename = $customization->logo_filename ?? null;
    $logoUrl = $logoFilename ? asset('storage/tenants/' . $tenant->id . '/' . $logoFilename) : null;

    // Showcase products (first 3 for hero grid)
    $showcase = $products->take(3);
@endphp
<!DOCTYPE html>
<html data-theme="{{ $effectiveTheme }}" lang="es" class="scroll-smooth">
@if($customPalette)
<style>
[data-theme="custom"]{
    --color-primary:{{ $customPalette['primary'] ?? '#570DF8' }};
    --p:{{ $customPalette['primary'] ?? '#570DF8' }};
    --color-secondary:{{ $customPalette['secondary'] ?? '#F000B9' }};
    --s:{{ $customPalette['secondary'] ?? '#F000B9' }};
    --color-accent:{{ $customPalette['accent'] ?? '#1DCDBC' }};
    --a:{{ $customPalette['accent'] ?? '#1DCDBC' }};
    --color-base-100:{{ $customPalette['base'] ?? '#FFFFFF' }};
    --b1:{{ $customPalette['base'] ?? '#FFFFFF' }};
    --bc:#1f2937;
}
</style>
@endif
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $meta['title'] ?? $tenant->business_name }}</title>
<meta name="description" content="{{ $meta['description'] ?? $tenant->description }}">
<link rel="canonical" href="{{ $meta['canonical'] ?? url('/' . $tenant->subdomain) }}">
<meta property="og:title" content="{{ $meta['og_title'] ?? $tenant->business_name }}">
<meta property="og:description" content="{{ $meta['og_description'] ?? $tenant->description }}">
<meta property="og:type" content="website">
@vite(['resources/css/app.css', 'resources/js/app.js'])
<link rel="preload" href="https://api.iconify.design/tabler.css" as="style">
<style>
/* ═══ SYNTIcat — Drawer + Cart animations ═══ */
.sc-drawer-overlay{position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:200;opacity:0;pointer-events:none;transition:opacity .25s}
.sc-drawer-overlay.open{opacity:1;pointer-events:auto}
.sc-drawer{position:fixed;right:0;top:0;bottom:0;width:min(380px,92vw);z-index:201;transform:translateX(100%);transition:transform .3s cubic-bezier(.4,0,.2,1);display:flex;flex-direction:column;box-shadow:-4px 0 24px rgba(0,0,0,.1)}
.sc-drawer.open{transform:translateX(0)}
@keyframes bump{0%,100%{transform:scale(1)}50%{transform:scale(1.3)}}
.bump{animation:bump .3s}
</style>
</head>
<body class="min-h-screen bg-base-100 text-base-content antialiased flex flex-col">

{{-- ═══════════════════════════════════════════════════════════════════════════
  1. NAVBAR — FlyonUI sticky
═══════════════════════════════════════════════════════════════════════════ --}}
<header class="sticky top-0 z-[100] w-full border-b border-base-content/10 bg-base-100/95 backdrop-blur-md">
    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8 flex items-center justify-between py-3">
        {{-- Brand --}}
        <a href="#" class="flex items-center gap-2.5 no-underline">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="{{ $tenant->business_name }}" class="h-9 w-9 object-contain rounded-lg">
            @else
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-primary text-white font-bold text-sm shadow-sm">
                    {{ mb_substr($tenant->business_name, 0, 1) }}
                </div>
            @endif
            <span class="text-base font-bold tracking-tight text-base-content">{{ $tenant->business_name }}</span>
        </a>

        <div class="flex items-center gap-3">
            {{-- Currency toggle --}}
            @if($savedDisplayMode === 'both_toggle')
            <div class="flex items-center bg-base-200 p-0.5 rounded-lg border border-base-content/10">
                <button class="sc-curr-btn px-2.5 py-1 text-[10px] font-black rounded-md transition-all bg-base-100 shadow-sm text-primary" data-currency="ref" onclick="setCurrency('ref')">{{ $currencySymbol }}</button>
                <button class="sc-curr-btn px-2.5 py-1 text-[10px] font-black rounded-md transition-all text-base-content/40" data-currency="bs" onclick="setCurrency('bs')">Bs.</button>
            </div>
            @elseif($savedDisplayMode === 'euro_toggle')
            <div class="flex items-center bg-base-200 p-0.5 rounded-lg border border-base-content/10">
                <button class="sc-curr-btn px-2.5 py-1 text-[10px] font-black rounded-md transition-all bg-base-100 shadow-sm text-primary" data-currency="eur" onclick="setCurrency('eur')">€</button>
                <button class="sc-curr-btn px-2.5 py-1 text-[10px] font-black rounded-md transition-all text-base-content/40" data-currency="bs" onclick="setCurrency('bs')">Bs.</button>
            </div>
            @endif

            {{-- Cart button --}}
            <button onclick="toggleDrawer()" id="sc-cart-trigger"
                    class="relative flex items-center gap-1.5 px-3 py-1.5 rounded-lg font-bold text-xs border border-primary/30 bg-primary/5 text-primary hover:bg-primary/10 transition-colors cursor-pointer">
                <span class="icon-[tabler--shopping-cart] size-4"></span>
                <span class="hidden sm:inline">Pedido</span>
                <span class="absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] rounded-full bg-primary text-white text-[10px] font-black flex items-center justify-center px-1 transition-transform"
                      id="sc-cart-count" style="display:none">0</span>
            </button>
        </div>
    </div>
</header>

{{-- ═══════════════════════════════════════════════════════════════════════════
  2. HERO — Asymmetric grid showcase (first 3 products)
═══════════════════════════════════════════════════════════════════════════ --}}
<section class="bg-base-200 py-8 sm:py-12 lg:py-16">
    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">

        {{-- Title area --}}
        <div class="mb-6 sm:mb-8">
            <h1 class="text-base-content text-2xl font-bold md:text-3xl lg:text-4xl tracking-tight">{{ $tenant->business_name }}</h1>
            @if($tenant->slogan)
                <p class="text-base-content/60 mt-1.5 text-sm sm:text-base">{{ $tenant->slogan }}</p>
            @elseif($tenant->description)
                <p class="text-base-content/60 mt-1.5 text-sm sm:text-base">{{ Str::limit($tenant->description, 120) }}</p>
            @endif
        </div>

        @if($showcase->count() >= 3)
        {{-- Asymmetric grid: 1 large left + 2 stacked right --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5 lg:gap-5">
            @php $hero1 = $showcase[0]; $hero2 = $showcase[1]; $hero3 = $showcase[2]; @endphp

            {{-- Large card (spans 3 cols on lg) --}}
            <div class="lg:col-span-3 group relative overflow-hidden rounded-2xl bg-base-100 border border-base-content/10 cursor-pointer"
                 onclick="addToCart({{ $hero1->id }}, '{{ addslashes($hero1->name) }}', {{ $hero1->price_usd ?? 0 }}, '{{ $hero1->image_filename ? asset('storage/tenants/' . $tenant->id . '/' . $hero1->image_filename) : ($hero1->image_url ?? '') }}')">
                <div class="aspect-[4/3] sm:aspect-[16/10] overflow-hidden">
                    @if($hero1->image_filename)
                        <img src="{{ asset('storage/tenants/' . $tenant->id . '/' . $hero1->image_filename) }}"
                             alt="{{ $hero1->name }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @elseif($hero1->image_url)
                        <img src="{{ $hero1->image_url }}"
                             alt="{{ $hero1->name }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-primary/10 to-secondary/10 flex items-center justify-center">
                            <span class="icon-[tabler--shopping-bag] size-16 text-primary/20"></span>
                        </div>
                    @endif
                </div>
                <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent p-4 sm:p-6">
                    @if($hero1->badge)
                        <span class="badge badge-accent badge-sm mb-2">{{ ucfirst($hero1->badge) }}</span>
                    @endif
                    <h3 class="text-white text-lg sm:text-xl font-bold">{{ $hero1->name }}</h3>
                    @if($hero1->price_usd && !$hidePrice)
                        <p class="text-white/90 text-sm font-bold mt-1" data-price-usd="{{ $hero1->price_usd }}">
                            <span class="text-xs opacity-60 mr-0.5">{{ $currencySymbol }}</span>{{ number_format((float)$hero1->price_usd, 2) }}
                        </p>
                    @endif
                </div>
            </div>

            {{-- Right column: 2 stacked cards (spans 2 cols on lg) --}}
            <div class="lg:col-span-2 grid grid-cols-2 sm:grid-cols-1 gap-4 lg:gap-5">
                @foreach([$hero2, $hero3] as $heroProduct)
                <div class="group relative overflow-hidden rounded-2xl bg-base-100 border border-base-content/10 cursor-pointer"
                     onclick="addToCart({{ $heroProduct->id }}, '{{ addslashes($heroProduct->name) }}', {{ $heroProduct->price_usd ?? 0 }}, '{{ $heroProduct->image_filename ? asset('storage/tenants/' . $tenant->id . '/' . $heroProduct->image_filename) : ($heroProduct->image_url ?? '') }}')">
                    <div class="aspect-[4/3] overflow-hidden">
                        @if($heroProduct->image_filename)
                            <img src="{{ asset('storage/tenants/' . $tenant->id . '/' . $heroProduct->image_filename) }}"
                                 alt="{{ $heroProduct->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @elseif($heroProduct->image_url)
                            <img src="{{ $heroProduct->image_url }}"
                                 alt="{{ $heroProduct->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-primary/10 to-secondary/10 flex items-center justify-center">
                                <span class="icon-[tabler--shopping-bag] size-10 text-primary/20"></span>
                            </div>
                        @endif
                    </div>
                    <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/70 to-transparent p-3 sm:p-4">
                        @if($heroProduct->badge)
                            <span class="badge badge-accent badge-sm mb-1">{{ ucfirst($heroProduct->badge) }}</span>
                        @endif
                        <h3 class="text-white text-sm sm:text-base font-bold">{{ $heroProduct->name }}</h3>
                        @if($heroProduct->price_usd && !$hidePrice)
                            <p class="text-white/90 text-xs font-bold mt-0.5" data-price-usd="{{ $heroProduct->price_usd }}">
                                <span class="text-[10px] opacity-60 mr-0.5">{{ $currencySymbol }}</span>{{ number_format((float)$heroProduct->price_usd, 2) }}
                            </p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @elseif($heroUrl)
        {{-- Fallback: hero image if less than 3 products --}}
        <div class="relative rounded-2xl overflow-hidden aspect-[21/9]">
            <img src="{{ $heroUrl }}" alt="{{ $tenant->business_name }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
        </div>
        @endif

    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════════════
  3. TODOS LOS PRODUCTOS — Grid 2/4 col
═══════════════════════════════════════════════════════════════════════════ --}}
<section class="py-8 sm:py-16 lg:py-24" id="productos">
    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">

        {{-- Section header --}}
        <div class="mb-8 sm:mb-12 flex items-end justify-between">
            <div>
                <h2 class="text-base-content text-xl font-semibold sm:text-2xl md:text-3xl">Todos los productos</h2>
                <p class="text-base-content/60 text-sm mt-1">{{ $products->count() }} producto{{ $products->count() !== 1 ? 's' : '' }} disponibles</p>
            </div>
            @if($wa)
            <a href="https://wa.me/{{ $waClean }}" target="_blank" rel="noopener noreferrer"
               class="btn btn-sm btn-outline btn-primary hidden sm:inline-flex">
                <span class="icon-[tabler--brand-whatsapp] size-4"></span>
                Consultar
            </a>
            @endif
        </div>

        {{-- Product Grid --}}
        <div class="grid grid-cols-2 gap-3 sm:gap-4 md:grid-cols-3 lg:grid-cols-4 lg:gap-5">
            @foreach($products as $product)
            @php
                $productImgSrc = null;
                if ($product->image_filename) {
                    $productImgSrc = asset('storage/tenants/' . $tenant->id . '/' . $product->image_filename);
                } elseif ($product->image_url) {
                    $productImgSrc = $product->image_url;
                }
            @endphp
            <div class="card card-border shadow-none hover:shadow-md transition-shadow duration-300 bg-base-100 group" data-product-id="{{ $product->id }}">
                {{-- Image --}}
                <figure class="relative overflow-hidden">
                    @if($productImgSrc)
                        <img src="{{ $productImgSrc }}"
                             alt="{{ $product->name }}"
                             class="aspect-square w-full object-cover group-hover:scale-105 transition-transform duration-500"
                             loading="lazy">
                    @else
                        <div class="aspect-square w-full bg-gradient-to-br from-base-200 to-base-300 flex items-center justify-center">
                            <span class="icon-[tabler--shopping-bag] size-10 text-base-content/15"></span>
                        </div>
                    @endif
                    @if($product->badge)
                        <span class="badge badge-accent badge-sm absolute top-2 left-2 shadow-sm">{{ ucfirst($product->badge) }}</span>
                    @endif
                </figure>

                {{-- Body --}}
                <div class="card-body gap-1.5 p-3 sm:p-4">
                    <h3 class="card-title text-sm font-semibold leading-tight line-clamp-2">{{ $product->name }}</h3>

                    @if($product->price_usd && !$hidePrice)
                        <p class="text-accent font-bold text-sm" data-price-usd="{{ $product->price_usd }}">
                            <span class="text-[10px] font-medium opacity-50 mr-0.5">{{ $currencySymbol }}</span>{{ number_format((float)$product->price_usd, 2) }}
                        </p>
                    @elseif($hidePrice)
                        <p class="text-base-content/40 text-xs italic">Consultar precio</p>
                    @endif

                    {{-- Actions --}}
                    <div class="card-actions mt-1">
                        <button class="btn btn-primary btn-sm btn-block gap-1.5"
                                onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price_usd ?? 0 }}, '{{ $productImgSrc ?? '' }}')">
                            <span class="icon-[tabler--shopping-cart-plus] size-4"></span>
                            Pedir
                        </button>
                    </div>

                    {{-- Qty controls (appear after first add) --}}
                    <div class="flex items-center justify-center gap-3 mt-1" id="qty-row-{{ $product->id }}" style="display:none">
                        <button class="btn btn-xs btn-square btn-outline" onclick="changeQty({{ $product->id }}, -1)">−</button>
                        <span class="text-sm font-bold min-w-[18px] text-center" id="qty-val-{{ $product->id }}">1</span>
                        <button class="btn btn-xs btn-square btn-outline" onclick="changeQty({{ $product->id }}, 1)">+</button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════════════
  4. CARRITO WHATSAPP (Drawer lateral) — Lógica intacta
═══════════════════════════════════════════════════════════════════════════ --}}
<div class="sc-drawer-overlay" id="sc-overlay" onclick="toggleDrawer()"></div>
<aside class="sc-drawer bg-base-100" id="sc-drawer">
    <div class="px-4 py-3 border-b border-base-content/10 flex items-center justify-between">
        <h3 class="text-base font-bold flex items-center gap-2">
            <span class="icon-[tabler--shopping-cart] size-5 text-primary"></span>
            Tu Pedido
        </h3>
        <button onclick="toggleDrawer()" class="btn btn-xs btn-square btn-ghost">
            <span class="icon-[tabler--x] size-4"></span>
        </button>
    </div>
    <div class="flex-1 overflow-y-auto px-4 py-3" id="sc-drawer-body">
        <div class="text-center py-12 text-base-content/30 text-sm" id="sc-empty">
            <span class="icon-[tabler--shopping-cart-off] size-10 mb-3 block mx-auto opacity-40"></span>
            Tu carrito está vacío.<br>Agrega productos para armar tu pedido.
        </div>
    </div>
    <div class="px-4 py-3 border-t border-base-content/10" id="sc-drawer-footer" style="display:none">
        <div class="flex justify-between items-center mb-3">
            <span class="text-sm font-medium text-base-content/60">Total:</span>
            <span class="text-lg font-black text-base-content" id="sc-total">REF 0.00</span>
        </div>
        <button onclick="sendWhatsApp()"
                class="btn btn-block bg-[#25D366] hover:bg-[#20BD5A] text-white border-none font-bold gap-2">
            <span class="icon-[tabler--brand-whatsapp] size-5"></span>
            Enviar pedido por WhatsApp
        </button>
    </div>
</aside>

{{-- ═══════════════════════════════════════════════════════════════════════════
  5. MEDIOS DE PAGO
═══════════════════════════════════════════════════════════════════════════ --}}
@if(!empty($visiblePay))
<section class="py-8 sm:py-16 lg:py-24 bg-base-200/50" id="pagos">
    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-base-content text-xl font-semibold sm:text-2xl mb-6">Medios de Pago</h2>
        <div class="flex flex-wrap justify-center gap-2 sm:gap-3">
            @foreach($visiblePay as $pm)
                <span class="inline-flex items-center gap-1.5 px-3 py-2 rounded-full bg-base-100 border border-base-content/10 text-xs sm:text-sm font-medium text-base-content hover:border-primary/30 transition-colors">
                    <span class="icon-[tabler--{{ $pm['icon'] }}] size-4 text-primary"></span>
                    {{ $pm['label'] }}
                </span>
            @endforeach
        </div>
        <p class="text-xs text-base-content/40 mt-4">Información de medios de pago que aceptamos. Nuestro sitio web no es pasarela de pago.</p>
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════════════════════════════════
  6. FOOTER
═══════════════════════════════════════════════════════════════════════════ --}}
<footer class="mt-auto border-t border-base-content/10 py-6 sm:py-8">
    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8 text-center space-y-2">
        <p class="text-sm font-medium text-base-content/50">
            {{ $tenant->business_name }}
            @if($tenant->city) · {{ $tenant->city }} @endif
            @if($wa)
                · <a href="https://wa.me/{{ $waClean }}" target="_blank" rel="noopener noreferrer" class="text-primary hover:underline">WhatsApp</a>
            @endif
        </p>
        <p class="text-[10px] uppercase tracking-[0.4em] text-base-content/25 font-semibold">Powered by <strong>SYNTIcat</strong></p>
    </div>
</footer>

{{-- ═══════════════════════════════════════════════════════════════════════════
  JAVASCRIPT — Carrito + Currency
═══════════════════════════════════════════════════════════════════════════ --}}
<script>
(function(){
    'use strict';

    /* ─── Currency System ───────────────────────────────────────────── */
    const CURRENCY_MODE   = @json($savedDisplayMode);
    const CURRENCY_SYMBOL = @json($currencySymbol);
    const EXCHANGE_RATE   = @json($dollarRate);
    const EURO_RATE       = @json($euroRate);
    const HIDE_PRICE      = @json($hidePrice);
    let currentCurrency   = CURRENCY_SYMBOL;

    // Init currency based on mode
    if (CURRENCY_MODE === 'bolivares_only')       currentCurrency = 'Bs.';
    else if (CURRENCY_MODE === 'euro_toggle')      currentCurrency = '€';
    else                                           currentCurrency = CURRENCY_SYMBOL;

    function formatPrice(usdPrice) {
        const val = parseFloat(usdPrice);
        if (isNaN(val)) return '';
        if (currentCurrency === 'Bs.') {
            const rate = CURRENCY_MODE === 'euro_toggle' ? EURO_RATE : EXCHANGE_RATE;
            return '<span class="text-[10px] font-medium opacity-50 mr-0.5">Bs.</span>' + (val * rate).toLocaleString('es-VE', {minimumFractionDigits:2, maximumFractionDigits:2});
        }
        if (currentCurrency === '€') {
            return '<span class="text-[10px] font-medium opacity-50 mr-0.5">€</span>' + val.toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2});
        }
        return '<span class="text-[10px] font-medium opacity-50 mr-0.5">' + CURRENCY_SYMBOL + '</span>' + val.toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2});
    }

    function formatPricePlain(usdPrice) {
        const val = parseFloat(usdPrice);
        if (isNaN(val)) return '0.00';
        if (currentCurrency === 'Bs.') {
            const rate = CURRENCY_MODE === 'euro_toggle' ? EURO_RATE : EXCHANGE_RATE;
            return 'Bs. ' + (val * rate).toLocaleString('es-VE', {minimumFractionDigits:2, maximumFractionDigits:2});
        }
        if (currentCurrency === '€') return '€' + val.toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2});
        return CURRENCY_SYMBOL + ' ' + val.toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2});
    }

    function renderAllPrices() {
        document.querySelectorAll('[data-price-usd]').forEach(function(el) {
            el.innerHTML = formatPrice(el.getAttribute('data-price-usd'));
        });
    }

    function updateToggleButtons() {
        document.querySelectorAll('.sc-curr-btn').forEach(function(btn) {
            var c = btn.getAttribute('data-currency');
            var isActive = false;
            if (c === 'ref' && currentCurrency === CURRENCY_SYMBOL) isActive = true;
            if (c === 'bs'  && currentCurrency === 'Bs.')          isActive = true;
            if (c === 'eur' && currentCurrency === '€')            isActive = true;
            btn.classList.toggle('bg-base-100', isActive);
            btn.classList.toggle('shadow-sm', isActive);
            btn.classList.toggle('text-primary', isActive);
            btn.classList.toggle('text-base-content/40', !isActive);
        });
    }

    window.setCurrency = function(mode) {
        if      (mode === 'bs')  currentCurrency = 'Bs.';
        else if (mode === 'eur') currentCurrency = '€';
        else                     currentCurrency = CURRENCY_SYMBOL;
        renderAllPrices();
        updateToggleButtons();
        renderDrawer();
    };

    /* ─── Cart State ────────────────────────────────────────────────── */
    var cart = {};  // { productId: { name, price, qty, img } }

    window.addToCart = function(id, name, price, img) {
        if (cart[id]) {
            cart[id].qty++;
        } else {
            cart[id] = { name: name, price: price, qty: 1, img: img };
        }
        updateQtyDisplay(id);
        updateBadge();
        renderDrawer();

        // Show qty row
        var qr = document.getElementById('qty-row-' + id);
        if (qr) qr.style.display = 'flex';
    };

    window.changeQty = function(id, delta) {
        if (!cart[id]) return;
        cart[id].qty += delta;
        if (cart[id].qty <= 0) {
            delete cart[id];
            var qr = document.getElementById('qty-row-' + id);
            if (qr) qr.style.display = 'none';
        }
        updateQtyDisplay(id);
        updateBadge();
        renderDrawer();
    };

    function removeFromCart(id) {
        delete cart[id];
        var qr = document.getElementById('qty-row-' + id);
        if (qr) qr.style.display = 'none';
        updateQtyDisplay(id);
        updateBadge();
        renderDrawer();
    }

    function updateQtyDisplay(id) {
        var el = document.getElementById('qty-val-' + id);
        if (el && cart[id]) el.textContent = cart[id].qty;
    }

    function updateBadge() {
        var total = 0;
        for (var k in cart) total += cart[k].qty;
        var badge = document.getElementById('sc-cart-count');
        if (total > 0) {
            badge.textContent = total;
            badge.style.display = 'flex';
            badge.classList.remove('bump');
            void badge.offsetWidth; // force reflow
            badge.classList.add('bump');
        } else {
            badge.style.display = 'none';
        }
    }

    /* ─── Drawer ────────────────────────────────────────────────────── */
    window.toggleDrawer = function() {
        var overlay = document.getElementById('sc-overlay');
        var drawer  = document.getElementById('sc-drawer');
        var isOpen  = drawer.classList.contains('open');
        overlay.classList.toggle('open', !isOpen);
        drawer.classList.toggle('open', !isOpen);
        document.body.style.overflow = isOpen ? '' : 'hidden';
    };

    function renderDrawer() {
        var body   = document.getElementById('sc-drawer-body');
        var footer = document.getElementById('sc-drawer-footer');
        var empty  = document.getElementById('sc-empty');
        var keys   = Object.keys(cart);

        if (keys.length === 0) {
            empty.style.display = 'block';
            footer.style.display = 'none';
            body.querySelectorAll('.sc-drawer-item').forEach(function(el) { el.remove(); });
            return;
        }

        empty.style.display = 'none';
        footer.style.display = 'block';

        // Rebuild items
        body.querySelectorAll('.sc-drawer-item').forEach(function(el) { el.remove(); });
        var totalUsd = 0;

        keys.forEach(function(id) {
            var item = cart[id];
            totalUsd += item.price * item.qty;

            var div = document.createElement('div');
            div.className = 'sc-drawer-item flex items-center gap-3 py-2.5 border-b border-base-content/5';
            div.innerHTML =
                (item.img ? '<img class="w-11 h-11 rounded-lg object-cover flex-shrink-0 bg-base-200" src="' + item.img + '" alt="">' : '<div class="w-11 h-11 rounded-lg bg-base-200 flex-shrink-0"></div>') +
                '<div class="flex-1 min-w-0">' +
                    '<div class="text-sm font-semibold truncate">' + item.name + '</div>' +
                    '<div class="text-xs text-base-content/50 mt-0.5">' + item.qty + ' × ' + formatPricePlain(item.price) + '</div>' +
                '</div>' +
                '<button class="sc-drawer-item-rm btn btn-xs btn-square btn-ghost text-base-content/40 hover:text-error hover:bg-error/10" data-rm-id="' + id + '" title="Quitar"><span class="icon-[tabler--x] size-3.5"></span></button>';
            body.appendChild(div);
        });

        // Bind remove buttons
        body.querySelectorAll('.sc-drawer-item-rm').forEach(function(btn) {
            btn.onclick = function() { removeFromCart(parseInt(this.getAttribute('data-rm-id'))); };
        });

        // Total
        document.getElementById('sc-total').innerHTML = formatPrice(totalUsd);
    }

    /* ─── Send WhatsApp ─────────────────────────────────────────────── */
    window.sendWhatsApp = function() {
        var waNumber = @json($waClean);
        if (!waNumber) { alert('WhatsApp no configurado'); return; }

        var businessName = @json($tenant->business_name);
        var keys = Object.keys(cart);
        if (keys.length === 0) { alert('Agrega productos al pedido'); return; }

        var lines = [];
        var totalUsd = 0;

        keys.forEach(function(id) {
            var item = cart[id];
            var lineTotal = item.price * item.qty;
            totalUsd += lineTotal;
            lines.push(item.name + ' x' + item.qty + ' ' + formatPricePlain(lineTotal));
        });

        var msg = '*PEDIDO ' + businessName + '*\n\n';
        msg += lines.join('\n');
        msg += '\n\n*Total: ' + formatPricePlain(totalUsd) + '*';
        msg += '\n\n_Enviado desde SYNTIcat_';

        window.open('https://wa.me/' + waNumber + '?text=' + encodeURIComponent(msg), '_blank');
    };

    /* ─── Init ──────────────────────────────────────────────────────── */
    document.addEventListener('DOMContentLoaded', function() {
        renderAllPrices();
        updateToggleButtons();
    });
})();
</script>

</body>
</html>
