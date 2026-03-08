{{-- ═══════════════════════════════════════════════════════════════════════════════
     SYNTIcat — Catálogo eCommerce + Carrito WhatsApp (REDISEÑO APP MODERNA)
     Preline 4.1.2 + Tailwind v4
═══════════════════════════════════════════════════════════════════════════════ --}}
@php
    $savedDisplayMode = $savedDisplayMode ?? $displayMode ?? 'reference_only';
    $currencySymbol   = $currencySettings['symbols']['reference'] ?? 'REF';
    $dollarRate       = $dollarRate ?? 36.50;
    $euroRate         = $euroRate ?? 495.00;
    $hidePrice        = $hidePrice ?? false;

    $wa = $tenant->getActiveWhatsapp() ?? null;
    $waClean = $wa ? preg_replace('/[^0-9]/', '', $wa) : '';

    $planSlug  = $tenant->plan->slug ?? 'cat-basico';
    $hasCart    = in_array($planSlug, ['cat-semestral', 'cat-anual']);
    $hasMiniOrder = $planSlug === 'cat-anual';

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

    // values() garantiza índices 0,1,2 al iterar colecciones filtradas
    $showcase = $products->where('featured', true)->take(3)->values();
    if ($showcase->count() < 3) {
        $showcase = $products->take(3)->values();
    }

    // Helper: URL de imagen almacenada o fallback a image_url
    $productImg = fn($p) => $p->image_filename
        ? asset('storage/tenants/' . $tenant->id . '/' . $p->image_filename)
        : ($p->image_url ?? null);

    // Campos requeridos al cliente (configurable por tenant o defecto B2H)
    $customerFields = $customization->customer_required_fields
        ?? ($tenant->settings['cat_settings']['customer_fields'] ?? ['name', 'location']);
    $needsName     = in_array('name',     (array) $customerFields);
    $needsLocation = in_array('location', (array) $customerFields);
@endphp
<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $meta['title'] ?? $tenant->business_name }}</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    /* ── Drawer overlay ── */
    .sc-drawer-overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);backdrop-filter:blur(2px);-webkit-backdrop-filter:blur(2px);z-index:200;opacity:0;pointer-events:none;transition:opacity .3s ease}
    .sc-drawer-overlay.open{opacity:1;pointer-events:auto}
    /* ── Drawer panel ── */
    .sc-drawer{position:fixed;right:0;top:0;bottom:0;width:min(420px,95vw);z-index:201;transform:translateX(105%);transition:transform .4s cubic-bezier(.4,0,.2,1);display:flex;flex-direction:column;box-shadow:-20px 0 60px rgba(0,0,0,.12);border-top-left-radius:2rem;border-bottom-left-radius:2rem;}
    .sc-drawer.open{transform:translateX(0)}
    /* ── Badge anim ── */
    @keyframes bump{0%,100%{transform:scale(1)}50%{transform:scale(1.3)}}
    .bump{animation:bump .25s ease-out}
    /* ── Bento hover ── */
    .bento-item{transition:transform .4s cubic-bezier(.4,0,.2,1),box-shadow .4s ease}
    .bento-item:hover{transform:scale(0.985);box-shadow:0 30px 60px -12px rgba(0,0,0,.25)}
    /* ── Product card: botón flotante + ── */
    .sc-add-btn{position:absolute;bottom:12px;right:12px;z-index:10;width:44px;height:44px;border-radius:50%;display:flex;align-items:center;justify-content:center;border:none;cursor:pointer;opacity:0;transform:translateY(6px) scale(.85);transition:opacity .2s ease,transform .2s ease;box-shadow:0 4px 16px rgba(0,0,0,.22);}
    .sc-product-card:hover .sc-add-btn{opacity:1;transform:translateY(0) scale(1);}
    @media (hover:none){.sc-add-btn{opacity:.92;transform:none;}}
    /* ── Modal simple para datos cliente ── */
    .sc-modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);backdrop-filter:blur(2px);-webkit-backdrop-filter:blur(2px);z-index:300;display:none;align-items:center;justify-content:center;padding:16px;}
    .sc-modal{max-width:420px;width:100%;background:var(--background, #ffffff);border-radius:24px;box-shadow:0 30px 80px rgba(0,0,0,.25);border:1px solid rgba(0,0,0,.05);}
    /* ── Customer form: floating label inputs ── */
    .sc-field{position:relative}
    .sc-field input{width:100%;padding:1.35rem 1rem .55rem;border-radius:1rem;border:1.5px solid rgba(0,0,0,.08);background:var(--surface, #f3f4f6);font-weight:700;font-size:.875rem;outline:none;transition:border-color .2s,background .2s,box-shadow .2s;color:inherit}
    .sc-field input:focus{border-color:#4A80E4;background:var(--background, #ffffff);box-shadow:0 0 0 3px color-mix(in oklch,#4A80E4 15%,transparent)}
    .sc-field label{position:absolute;left:1rem;top:50%;transform:translateY(-50%);font-size:.825rem;font-weight:700;color:rgba(0,0,0,.35);pointer-events:none;transition:all .18s cubic-bezier(.4,0,.2,1)}
    .sc-field input:focus+label,.sc-field input:not(:placeholder-shown)+label{top:.6rem;transform:none;font-size:.65rem;letter-spacing:.05em;color:#4A80E4}
    .sc-field-error{border-color:#ef4444!important;background:#fff5f5!important}
    .sc-field-error:focus{box-shadow:0 0 0 3px rgba(239,68,68,.15)!important}
</style>
</head>
<body class="min-h-screen bg-white dark:bg-neutral-900 text-gray-900 dark:text-neutral-100 antialiased flex flex-col font-sans">

{{-- 1. NAVBAR APP STYLE --}}
<header class="sticky top-0 z-[100] w-full bg-white/90 dark:bg-neutral-900/90 backdrop-blur-2xl" style="border-bottom:1px solid rgba(0,0,0,.07);">
    <div class="mx-auto max-w-[1280px] px-5 sm:px-8 flex items-center justify-between h-16">
        <a href="#" class="flex items-center gap-3 min-w-0">
            @if(!empty($customization->logo_filename))
                <img src="{{ asset('storage/tenants/' . $tenant->id . '/' . $customization->logo_filename) }}"
                     alt="{{ $tenant->business_name }}"
                     class="size-10 rounded-xl object-cover shrink-0"
                     onerror="this.style.display='none';">
            @else
                <div class="size-10 bg-[#4A80E4] rounded-xl flex items-center justify-center shadow-lg shadow-[#4A80E4]/20 shrink-0">
                    <span class="iconify tabler--bag size-6 text-white"></span>
                </div>
            @endif
            <span class="text-xl font-black tracking-tighter truncate">{{ $tenant->business_name }}</span>
        </a>

        <div class="flex items-center gap-3">
            @if(str_contains($savedDisplayMode, 'toggle'))
            <div class="hidden md:flex bg-gray-100/50 dark:bg-neutral-800/50 p-1 rounded-xl border border-gray-100 dark:border-neutral-800 backdrop-blur-md">
                <button class="sc-curr-btn px-4 py-1.5 text-xs font-bold rounded-lg transition-all" data-currency="ref" onclick="setCurrency('ref')">{{ $currencySymbol }}</button>
                <button class="sc-curr-btn px-4 py-1.5 text-xs font-bold rounded-lg transition-all" data-currency="bs" onclick="setCurrency('bs')">Bs</button>
            </div>
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

            @if($hasCart)
            <button onclick="toggleDrawer()" id="sc-cart-trigger" class="relative group p-2 rounded-full transition-colors bg-[#4A80E4] text-white hover:bg-[#4A80E4]/90 shadow-xl shadow-[#4A80E4]/20">
                <svg aria-hidden="true" focusable="false" class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="19" cy="21" r="1"></circle>
                    <path d="M5 4H7L9 17H19L21 8H8"></path>
                </svg>
                <span id="sc-cart-count" class="absolute -top-1 -right-1 size-5 bg-red-500 text-white text-[10px] font-black rounded-full flex items-center justify-center border-2 border-white dark:border-neutral-900" style="display:none">0</span>
            </button>
            @endif
        </div>
    </div>
</header>

{{-- 2. HERO BENTO GRID --}}
@if($showcase->count() >= 1)
<section class="py-8 lg:py-12 bg-white dark:bg-neutral-900">
    <div class="mx-auto max-w-[1280px] px-5 sm:px-8">
        <div class="mb-10 max-w-2xl">
            @if($tenant->slogan)
                <p class="text-[#4A80E4] text-xs font-black uppercase tracking-[.2em] mb-2">{{ $tenant->slogan }}</p>
            @endif
            <h1 class="text-4xl lg:text-6xl font-black tracking-tight leading-[1]">{{ $tenant->business_name }}</h1>
            @if($tenant->description)
                <p class="mt-3 text-gray-500 dark:text-neutral-400 text-base font-medium">{{ Str::limit($tenant->description, 120) }}</p>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 h-auto md:h-[500px]">
            @if($showcase->count() >= 3)
                @php $s0 = $showcase->get(0); $s0img = $productImg($s0); @endphp
                {{-- Principal --}}
                <div class="md:col-span-7 relative group overflow-hidden rounded-[2.5rem] bg-gray-100 dark:bg-neutral-800 bento-item cursor-pointer"
                     @if($hasCart) onclick="addToCart({{ $s0->id }}, '{{ addslashes($s0->name) }}', {{ $s0->price_usd ?? 0 }}, '{{ $s0img }}')" @elseif($waClean) onclick="window.open('https://wa.me/{{ $waClean }}?text={{ urlencode('Hola! Me interesa: ' . $s0->name) }}','_blank')" @endif>
                    @if($s0img)
                        <img src="{{ $s0img }}" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110"
                             alt="{{ $s0->name }}" onerror="this.style.display='none';">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent p-8 flex flex-col justify-end">
                        <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-blue-100 text-blue-700 mb-3 uppercase font-black tracking-widest">Destacado</span>
                        <h2 class="text-3xl font-bold text-white leading-tight">{{ $s0->name }}</h2>
                        @if(!$hidePrice)
                        <p class="text-white/70 font-bold text-xl mt-2" data-price-usd="{{ $s0->price_usd ?? 0 }}">{{ $currencySymbol }} 0.00</p>
                        @endif
                    </div>
                </div>
                {{-- Secundarios --}}
                <div class="md:col-span-5 grid grid-rows-2 gap-4">
                    @foreach($showcase->slice(1) as $item)
                    @php $simg = $productImg($item); @endphp
                    <div class="relative group overflow-hidden rounded-[2rem] bg-gray-100 dark:bg-neutral-800 bento-item cursor-pointer"
                         @if($hasCart) onclick="addToCart({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->price_usd ?? 0 }}, '{{ $simg }}')" @elseif($waClean) onclick="window.open('https://wa.me/{{ $waClean }}?text={{ urlencode('Hola! Me interesa: ') }}{{ urlencode($item->name) }}','_blank')" @endif>
                        @if($simg)
                            <img src="{{ $simg }}" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110"
                                 alt="{{ $item->name }}" onerror="this.style.display='none';">
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent p-6 flex flex-col justify-end">
                            <h3 class="text-lg font-bold text-white leading-tight">{{ $item->name }}</h3>
                            @if(!$hidePrice)
                            <p class="text-white/70 text-sm font-bold mt-1" data-price-usd="{{ $item->price_usd ?? 0 }}">{{ $currencySymbol }} 0.00</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>
@endif

{{-- 3. CATEGORY PILLS (STICKY) --}}
<nav class="mt-10 mb-10">
    <div class="mx-auto max-w-[1280px] px-5 sm:px-8">
        <div class="bg-white/95 dark:bg-neutral-900/95 backdrop-blur-xl shadow-sm border border-gray-200 dark:border-neutral-700 rounded-full py-2.5 px-3 flex items-center gap-3 overflow-x-auto no-scrollbar">
        <button onclick="filterCategory('all')" data-cat="all"
                class="sc-cat-pill text-sm py-1.5 px-6 rounded-2xl font-medium transition-colors bg-blue-600 text-white hover:bg-blue-700 shrink-0">Todos</button>
        @if(isset($categories))
            @foreach($categories as $cat)
                <button onclick="filterCategory('{{ $cat->id }}')" data-cat="{{ $cat->id }}"
                        class="sc-cat-pill text-sm py-1.5 px-6 rounded-2xl font-medium transition-colors text-gray-700 hover:bg-gray-100 border border-gray-200 dark:border-neutral-700 whitespace-nowrap shrink-0">{{ $cat->name }}</button>
            @endforeach
        @endif
        </div>
    </div>
</nav>

{{-- 4. PRODUCT GRID APP STYLE --}}
<section class="py-12 lg:py-20" id="productos">
    <div class="mx-auto max-w-[1280px] px-5 sm:px-8">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8">
            @foreach($products as $product)
            @php $img = $productImg($product); @endphp
            <div class="sc-product-card group flex flex-col bg-white dark:bg-neutral-900 rounded-[1.5rem] shadow-sm border border-gray-100 dark:border-neutral-800 overflow-hidden" data-category="{{ $product->category_id ?? 'all' }}">
                <div class="relative aspect-[4/5] rounded-[1.35rem] overflow-hidden bg-gray-100 dark:bg-neutral-800 m-3 transition-all duration-300 group-hover:shadow-2xl group-hover:-translate-y-1">
                    @if($img)
                        <img src="{{ $img }}" alt="{{ $product->name }}"
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                             loading="lazy" onerror="this.style.display='none';">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center gap-2 text-gray-300 dark:text-neutral-600">
                            <span class="iconify tabler--photo-off size-10"></span>
                        </div>
                    @endif

                    {{-- Floating + button --}}
                    @if($hasCart)
                    <button onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price_usd ?? 0 }}, '{{ $img }}')"
                            class="sc-add-btn bg-[#4A80E4] text-white">
                        <svg aria-hidden="true" focusable="false" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5v14M5 12h14" />
                        </svg>
                    </button>
                    @elseif($waClean)
                    <a href="https://wa.me/{{ $waClean }}?text={{ urlencode('Hola! Me interesa: ' . $product->name) }}" target="_blank"
                       class="sc-add-btn bg-[#25D366] text-white">
                        <svg aria-hidden="true" focusable="false" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16.7 14.3c-.3-.2-1.7-.8-1.9-.9-.3-.1-.5-.2-.7.2s-.8.9-1 .9-.5 0-.9-.4c-.5-.4-1-1-1.2-1.3-.1-.3 0-.5.1-.6.2-.2.3-.3.5-.5.2-.2.2-.3.3-.5.1-.3 0-.5 0-.6s-.7-1.8-1-2.4c-.3-.6-.5-.6-.7-.6h-.6c-.2 0-.6.1-.9.4-.3.3-1.1 1-1.1 2.4s1.1 2.7 1.3 2.9c.2.2 2.1 3.2 5.1 4.3.7.3 1.2.4 1.6.5.7.1 1.4.1 1.9.1.6 0 1.7-.7 1.9-1.3.2-.6.2-1.2.2-1.3 0-.1-.3-.2-.6-.4z"></path>
                            <path d="M12 3a9 9 0 0 0-9 9 8.9 8.9 0 0 0 1.2 4.5L3 21l4.7-1.2A9 9 0 1 0 12 3Z"></path>
                        </svg>
                    </a>
                    @endif

                    @if($product->badge)
                        <span class="absolute top-3 left-3 inline-flex items-center py-0.5 px-3 rounded-full text-xs font-medium bg-white/20 backdrop-blur-md text-white border-none uppercase text-[10px] font-black">{{ $product->badge }}</span>
                    @endif
                </div>

                <div class="px-1">
                    <h3 class="font-bold text-sm leading-tight mb-1 group-hover:text-[#4A80E4] transition-colors line-clamp-2">{{ $product->name }}</h3>
                    @if(!$hidePrice)
                    <div class="flex items-center justify-between mt-1">
                        <p class="text-lg font-black tracking-tight" data-price-usd="{{ $product->price_usd ?? 0 }}">{{ $currencySymbol }} 0.00</p>
                        {{-- Qty Control (shown when item is in cart) --}}
                        @if($hasCart)
                        <div id="qty-row-{{ $product->id }}" class="flex items-center gap-1 bg-gray-100 dark:bg-neutral-800 rounded-full px-2 py-1" style="display:none!important">
                            <button class="size-6 rounded-full bg-white dark:bg-neutral-900 flex items-center justify-center text-xs font-bold" onclick="changeQty({{ $product->id }}, -1)">−</button>
                            <span class="text-xs font-black min-w-[14px] text-center" id="qty-val-{{ $product->id }}">1</span>
                            <button class="size-6 rounded-full bg-[#4A80E4] text-white flex items-center justify-center text-xs font-bold" onclick="changeQty({{ $product->id }}, 1)">+</button>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- 5. DRAWER CARRITO (solo planes con carrito) --}}
@if($hasCart)
<div class="sc-drawer-overlay" id="sc-overlay" onclick="toggleDrawer()"></div>
<aside class="sc-drawer bg-white dark:bg-neutral-900" id="sc-drawer">
    {{-- Header --}}
    <div class="px-7 pt-7 pb-5 flex items-center justify-between border-b border-gray-100 dark:border-neutral-800">
        <div>
            <h3 class="text-2xl font-black tracking-tighter">Mi Pedido</h3>
            <p class="text-[10px] text-gray-400 dark:text-neutral-500 font-black uppercase tracking-[.25em] mt-0.5">Shopping Bag</p>
        </div>
        <button onclick="toggleDrawer()" class="p-2 rounded-full text-sm transition-colors text-gray-700 hover:bg-gray-100 bg-gray-100/80 dark:bg-neutral-800/80">
            <span class="iconify tabler--x size-5"></span>
        </button>
    </div>

    {{-- Empty state — FUERA de sc-drawer-body para que innerHTML='' no lo destruya --}}
    <div id="sc-empty" class="flex-1 flex flex-col items-center justify-center text-center py-14 px-7">
        <div class="size-20 rounded-3xl bg-gray-100 dark:bg-neutral-800 flex items-center justify-center mb-4">
            <span class="iconify tabler--shopping-bag size-10 text-gray-300 dark:text-neutral-600"></span>
        </div>
        <p class="font-bold text-gray-400 dark:text-neutral-500">Tu carrito está vacío</p>
        <p class="text-xs text-gray-300 dark:text-neutral-600 mt-1">Explora y agrega productos</p>
    </div>

    {{-- Lista de items — innerHTML se limpia de forma segura --}}
    <div class="flex-1 overflow-y-auto px-7 no-scrollbar" id="sc-drawer-body" style="display:none"></div>

    {{-- Footer --}}
    <div class="p-7 space-y-4 border-t border-gray-100 dark:border-neutral-800" id="sc-drawer-footer" style="display:none">

        @if(count($visiblePay) > 0)
        <div class="flex items-center gap-2 flex-wrap">
            @foreach($visiblePay as $key => $pm)
                {{-- Clase de icono escrita estáticamente para que Tailwind JIT la escanee --}}
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
                <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-gray-500 dark:text-neutral-400 bg-gray-100 dark:bg-neutral-800 rounded-xl px-2.5 py-1.5">
                    <span class="{{ $pmIcon }} size-3.5 shrink-0"></span>
                    {{ $pm['label'] }}
                </span>
            @endforeach
        </div>
        @endif

        <div class="bg-gray-100/60 dark:bg-neutral-800/60 p-5 rounded-[1.5rem] border border-gray-100 dark:border-neutral-800">
            <div class="flex justify-between items-center text-gray-400 dark:text-neutral-500 text-xs font-black uppercase tracking-widest mb-1">
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
        <button class="flex items-center justify-center w-full h-14 rounded-[1.5rem] bg-blue-600 text-white hover:bg-blue-700 font-black text-base transition-colors">
            Ir al resumen
        </button>
        @endif
    </div>
</aside>
@endif

{{-- MODAL Datos Cliente --}}
@if($needsName || $needsLocation)
<div id="sc-data-modal" class="sc-modal-overlay">
    <div class="sc-modal p-6 space-y-5">
        <div class="flex items-start gap-3">
            <div class="size-10 rounded-2xl bg-[#4A80E4]/10 flex items-center justify-center text-[#4A80E4] shrink-0">
                <svg aria-hidden="true" focusable="false" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="7" r="4"></circle>
                    <path d="M5.5 21a6.5 6.5 0 0 1 13 0"></path>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-lg font-black">Antes de enviar</p>
                <p class="text-sm text-gray-500 dark:text-neutral-400">Déjanos tus datos para personalizar tu pedido.</p>
            </div>
            <button onclick="closeDataModal()" class="p-2 rounded-full text-sm text-gray-700 hover:bg-gray-100 transition-colors">
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
            <button onclick="confirmDataAndSend()" class="py-2 px-4 rounded-xl font-medium transition-colors bg-blue-600 text-white hover:bg-blue-700 flex-1 font-black">Enviar por WhatsApp</button>
            <button onclick="closeDataModal()" class="py-2 px-4 rounded-xl font-medium transition-colors text-gray-700 hover:bg-gray-100">Cancelar</button>
        </div>
    </div>
</div>
@endif

{{-- 6. FOOTER --}}
<footer class="bg-gray-100/50 dark:bg-neutral-800/50 border-t border-gray-100 dark:border-neutral-800 py-14">
    <div class="mx-auto max-w-[1280px] px-5 sm:px-8 text-center">
        {{-- Logo / Icon --}}
        @if(!empty($customization->logo_filename))
            <img src="{{ asset('storage/tenants/' . $tenant->id . '/' . $customization->logo_filename) }}"
                 alt="{{ $tenant->business_name }}"
                 class="size-14 rounded-2xl object-cover mx-auto mb-5 border border-gray-100 dark:border-neutral-800 shadow-sm"
                 onerror="this.style.display='none';">
        @else
            <div class="size-14 bg-white dark:bg-neutral-900 rounded-2xl mx-auto mb-5 flex items-center justify-center border border-gray-100 dark:border-neutral-800 shadow-sm">
                <span class="iconify tabler--layout-grid size-7 text-[#4A80E4]"></span>
            </div>
        @endif

        <h2 class="text-2xl font-black tracking-tighter mb-1">{{ $tenant->business_name }}</h2>
        @if($tenant->description)
            <p class="text-gray-400 dark:text-neutral-500 text-sm max-w-md mx-auto">{{ Str::limit($tenant->description, 160) }}</p>
        @endif

        @if($waClean)
        <a href="https://wa.me/{{ $waClean }}" target="_blank"
           class="inline-flex items-center gap-2 text-sm py-1.5 px-3 rounded-2xl font-medium transition-colors mt-6 border-none font-bold text-white"
           style="background:#25D366;">
            <span class="iconify tabler--brand-whatsapp size-4"></span>
            Escribir por WhatsApp
        </a>
        @endif

        <div class="mt-10 pt-8 border-t border-gray-100 dark:border-neutral-800 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-xs font-bold text-gray-300 dark:text-neutral-600 uppercase tracking-[0.25em]">© {{ date('Y') }} {{ $tenant->business_name }}</p>
            <p class="text-xs text-gray-300 dark:text-neutral-600">Sitio creado con <span class="text-[#4A80E4] font-bold">SYNTIweb</span></p>
        </div>
    </div>
</footer>

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
            btn.className = `sc-curr-btn px-4 py-1.5 text-xs font-bold rounded-lg transition-all ${active ? 'bg-white dark:bg-neutral-900 shadow-lg text-[#4A80E4]' : 'text-gray-400 dark:text-neutral-500'}`;
        });
        @if($hasCart) renderDrawer(); @endif
    };

    window.filterCategory = function(catId) {
        document.querySelectorAll('.sc-cat-pill').forEach(btn => {
            const active = String(btn.dataset.cat) === String(catId);
            if (active) {
                btn.className = 'sc-cat-pill text-sm py-1.5 px-6 rounded-2xl font-medium bg-[#4A80E4] text-white hover:bg-[#4A80E4]/90 shrink-0';
            } else {
                btn.className = 'sc-cat-pill text-sm py-1.5 px-6 rounded-2xl font-medium text-gray-700 hover:bg-gray-100 border border-gray-200 dark:border-neutral-700 whitespace-nowrap shrink-0';
            }
        });
        document.querySelectorAll('.sc-product-card').forEach(card => {
            const match = catId === 'all' || String(card.dataset.category) === String(catId);
            card.style.display = match ? '' : 'none';
        });
    };

    @if($hasCart)
    window.addToCart = function(id, name, price, img) {
        const isNew = !cart[id];
        if (cart[id]) cart[id].qty++; else cart[id] = { name, price: parseFloat(price) || 0, qty: 1, img };
        updateBadge();
        renderDrawer();
        const qr = document.getElementById('qty-row-' + id);
        if (qr) qr.style.cssText = 'display:flex!important';
        const qv = document.getElementById('qty-val-' + id);
        if (qv) qv.textContent = cart[id].qty;
        // Auto-abrir drawer al primer item
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

        // Limpia SOLO items (sc-empty está fuera de body, es seguro)
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
            div.className = 'flex items-center gap-4 py-4 border-b border-gray-100 dark:border-neutral-800 last:border-0';
            // Botón eliminar: ×  (sin clase de icono dinámica en JS)
            div.innerHTML = `
                <div style="width:56px;height:56px;border-radius:14px;overflow:hidden;background:var(--surface, #f3f4f6);flex-shrink:0">
                    <img style="width:100%;height:100%;object-fit:cover" src="${item.img || ''}">
                </div>
                <div style="flex:1;min-width:0">
                    <p style="font-weight:800;font-size:.875rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;margin-bottom:2px">${item.name}</p>
                    <p style="font-size:.75rem;font-weight:700;opacity:.45">${item.qty} × ${formatPrice(item.price, true)}</p>
                </div>
                <button onclick="changeQty(${id}, -1)"
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

    @if(!$hasMiniOrder)
    {{-- Plan cat-semestral: buildAndSend() directo sin backend --}}
    function buildAndSend(name, loc) {
        let waNumber = @json($waClean);
        if (!waNumber) return;
        let businessName = @json($tenant->business_name);

        let greeting = name
            ? `¡Hola! Soy *${name}* y vengo de la web de *${businessName}* 🛍️`
            : `¡Hola! Les escribo desde la web de *${businessName}* 🛍️`;

        let totalUsd = Object.values(cart).reduce((a,b) => a + (b.price * b.qty), 0);
        let msg = greeting + '\n\n';
        msg += '*Mi pedido:*\n';
        msg += Object.values(cart).map(i => `• ${i.name} ×${i.qty} (${formatPrice(i.price * i.qty, true)})`).join('\n');
        msg += '\n\n*Total: ' + formatPrice(totalUsd, true) + '*';
        if (loc) msg += `\n\n📍 *Mi referencia:* ${loc}`;
        msg += '\n\n#PedidoWeb';

        window.open(`https://wa.me/${waNumber}?text=${encodeURIComponent(msg)}`, '_blank');
    }

    window.sendWhatsApp = function() {
        // Si no se requieren datos, enviar directo
        @if(!$needsName && !$needsLocation)
            buildAndSend('', '');
            return;
        @endif

        const nameEl  = document.getElementById('sc-customer-name');
        const locEl   = document.getElementById('sc-customer-location');
        const name    = nameEl ? nameEl.value.trim() : '';
        const loc     = locEl  ? locEl.value.trim()  : '';

        // Si falta algún requerido, abrir modal y no enviar
        if ((nameEl && !name) || (locEl && !loc)) {
            openDataModal();
            return;
        }

        if (name) localStorage.setItem('sc_customer_name', name);
        buildAndSend(name, loc);
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
    @else
    {{-- Plan cat-anual: POST al endpoint /checkout, recibe SC-XXXX, redirige a wa.me --}}
    function _showToast(msg) {
        if (typeof showToast === 'function') { showToast(msg); } else { alert(msg); }
    }

    function _collectCustomerData() {
        const nameEl = document.getElementById('sc-customer-name');
        const locEl  = document.getElementById('sc-customer-location');
        return {
            name: nameEl ? nameEl.value.trim() : '',
            loc:  locEl  ? locEl.value.trim()  : '',
            nameEl, locEl
        };
    }

    async function _doCheckout(name, loc) {
        const items = Object.values(cart).map(i => ({
            title:   i.name,
            qty:     i.qty,
            price:   i.price,
            variant: null
        }));

        const payload = { name, location: loc, items };

        const response = await fetch('/{{ $tenant->subdomain }}/checkout', {
            method:  'POST',
            headers: {
                'Content-Type':     'application/json',
                'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept':           'application/json'
            },
            body: JSON.stringify(payload)
        });

        const data = await response.json();

        if (data.success) {
            if (name) localStorage.setItem('sc_customer_name', name);
            window.open(data.whatsapp_url, '_blank');
        } else if (data.error === 'plan_requerido') {
            _showToast('Mejora tu plan para continuar');
        } else {
            _showToast(data.message || 'Ocurrió un error. Inténtalo de nuevo.');
        }
    }

    window.sendWhatsApp = async function() {
        @if(!$needsName && !$needsLocation)
            await _doCheckout('', '');
            return;
        @endif

        const { name, loc, nameEl, locEl } = _collectCustomerData();

        if ((nameEl && !name) || (locEl && !loc)) {
            openDataModal();
            return;
        }

        await _doCheckout(name, loc);
    };

    window.confirmDataAndSend = async function() {
        const { name, loc, nameEl } = _collectCustomerData();

        @if($needsName)
        if (nameEl && !name) {
            nameEl.classList.add('sc-field-error');
            nameEl.focus();
            return;
        }
        @endif

        closeDataModal();
        await _doCheckout(name, loc);
    };
    @endif
    @endif

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
</body>
</html>