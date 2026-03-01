{{-- ═══════════════════════════════════════════════════════════════════════════════
     SYNTIcat — Catálogo mínimo + Carrito WhatsApp
     Template independiente, NO hereda de base.blade.php
     CSS/JS inline — un solo request
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
        'pagoMovil'  => ['label' => 'Pago Móvil',    'icon' => '📱'],
        'cash'       => ['label' => 'Efectivo',       'icon' => '💵'],
        'puntoventa' => ['label' => 'Punto de Venta', 'icon' => '💳'],
        'biopago'    => ['label' => 'Biopago',        'icon' => '🖐️'],
        'cashea'     => ['label' => 'Cashea',         'icon' => '🛒'],
        'krece'      => ['label' => 'Krece',          'icon' => '📈'],
        'wepa'       => ['label' => 'Wepa',           'icon' => '📲'],
        'lysto'      => ['label' => 'Lysto',          'icon' => '📅'],
        'chollo'     => ['label' => 'Chollo',         'icon' => '🏷️'],
        'zelle'      => ['label' => 'Zelle',          'icon' => '⚡'],
        'zinli'      => ['label' => 'Zinli',          'icon' => '👛'],
        'paypal'     => ['label' => 'PayPal',         'icon' => '🅿️'],
    ];
    $allCurrencyMeta = [
        'usd' => ['label' => 'Dólares USD', 'icon' => '💲'],
        'eur' => ['label' => 'Euros',        'icon' => '💶'],
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
@endphp
<!DOCTYPE html>
<html data-theme="{{ $effectiveTheme }}" lang="es">
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
<style>
/* ═══ SYNTIcat inline styles ═══ */
*{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:system-ui,-apple-system,sans-serif;min-height:100vh;display:flex;flex-direction:column}

/* Navbar */
.sc-nav{position:sticky;top:0;z-index:100;backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border-bottom:1px solid oklch(var(--bc)/.08)}
.sc-nav-inner{max-width:1200px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;padding:.75rem 1rem;gap:.75rem}
.sc-nav-brand{display:flex;align-items:center;gap:.6rem;text-decoration:none;color:oklch(var(--bc));font-weight:800;font-size:1.1rem}
.sc-nav-brand img{height:36px;width:36px;object-fit:contain;border-radius:8px}
.sc-nav-brand-icon{height:36px;width:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-weight:900;font-size:1rem;color:#fff;background:oklch(var(--p))}
.sc-nav-actions{display:flex;align-items:center;gap:.5rem}

/* Currency toggle pills */
.sc-curr-toggle{display:flex;border-radius:10px;overflow:hidden;border:1px solid oklch(var(--bc)/.1);background:oklch(var(--b1))}
.sc-curr-btn{padding:4px 10px;font-size:11px;font-weight:800;cursor:pointer;border:none;background:transparent;color:oklch(var(--bc)/.4);transition:all .2s}
.sc-curr-btn.active{background:oklch(var(--p));color:#fff;box-shadow:0 1px 4px oklch(var(--p)/.25)}

/* Cart badge */
.sc-cart-btn{position:relative;display:flex;align-items:center;gap:.35rem;padding:6px 14px;border-radius:10px;font-weight:700;font-size:.8rem;cursor:pointer;border:1px solid oklch(var(--p)/.3);background:oklch(var(--p)/.08);color:oklch(var(--p));transition:all .2s}
.sc-cart-btn:hover{background:oklch(var(--p)/.15)}
.sc-cart-badge{position:absolute;top:-6px;right:-6px;min-width:18px;height:18px;border-radius:9px;background:oklch(var(--p));color:#fff;font-size:10px;font-weight:800;display:flex;align-items:center;justify-content:center;padding:0 4px;transition:transform .2s}
.sc-cart-badge.bump{animation:bump .3s}
@keyframes bump{0%,100%{transform:scale(1)}50%{transform:scale(1.3)}}

/* Hero */
.sc-hero{position:relative;min-height:280px;display:flex;align-items:center;justify-content:center;text-align:center;padding:3rem 1rem;overflow:hidden}
.sc-hero-bg{position:absolute;inset:0;background-size:cover;background-position:center;filter:brightness(.45)}
.sc-hero-bg.sc-hero-gradient{filter:none}
.sc-hero-content{position:relative;z-index:2;max-width:600px}
.sc-hero h1{color:#fff;font-size:clamp(1.6rem,5vw,2.6rem);font-weight:900;letter-spacing:-.02em;line-height:1.15;text-shadow:0 2px 12px rgba(0,0,0,.4)}
.sc-hero p{color:rgba(255,255,255,.85);margin-top:.6rem;font-size:clamp(.9rem,2.5vw,1.15rem);font-weight:500}

/* Product grid */
.sc-grid-section{max-width:1200px;margin:0 auto;padding:2rem 1rem 3rem}
.sc-grid-title{text-align:center;font-size:1.6rem;font-weight:800;color:oklch(var(--bc));margin-bottom:1.5rem;letter-spacing:-.02em}
.sc-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:.75rem}
@media(min-width:640px){.sc-grid{grid-template-columns:repeat(3,1fr);gap:1rem}}
@media(min-width:1024px){.sc-grid{grid-template-columns:repeat(4,1fr);gap:1.1rem}}

/* Product card */
.sc-card{border-radius:12px;overflow:hidden;border:1px solid oklch(var(--bc)/.07);background:oklch(var(--b1));transition:box-shadow .2s,transform .15s}
.sc-card:hover{box-shadow:0 8px 24px oklch(var(--bc)/.08);transform:translateY(-2px)}
.sc-card-img{aspect-ratio:1/1;width:100%;object-fit:cover;background:oklch(var(--bc)/.05);display:block}
.sc-card-placeholder{aspect-ratio:1/1;width:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;background:linear-gradient(135deg,oklch(var(--p)/.08),oklch(var(--s,var(--p))/.12));gap:.35rem}
.sc-card-placeholder svg{width:36px;height:36px;color:oklch(var(--p)/.35)}
.sc-card-placeholder span{font-size:.65rem;font-weight:700;color:oklch(var(--p)/.4);letter-spacing:.04em;text-transform:uppercase}
.sc-card-body{padding:.65rem .75rem .75rem}
.sc-card-name{font-size:.82rem;font-weight:700;color:oklch(var(--bc));line-height:1.25;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.sc-card-price{font-size:.9rem;font-weight:800;color:oklch(var(--bc));margin-top:.25rem}
.sc-card-price .sym{font-size:.65rem;opacity:.5;font-weight:600;margin-right:2px}
.sc-card-actions{display:flex;gap:.4rem;margin-top:.5rem}
.sc-btn-pedir{flex:1;display:flex;align-items:center;justify-content:center;gap:.3rem;padding:6px 0;border-radius:8px;font-weight:700;font-size:.72rem;cursor:pointer;border:none;background:oklch(var(--p));color:#fff;transition:opacity .2s}
.sc-btn-pedir:hover{opacity:.85}
.sc-btn-pedir svg{width:14px;height:14px;flex-shrink:0}
.sc-btn-qty{width:28px;height:28px;border-radius:6px;border:1px solid oklch(var(--bc)/.12);background:oklch(var(--b1));display:flex;align-items:center;justify-content:center;cursor:pointer;font-weight:800;font-size:.85rem;color:oklch(var(--bc)/.6);transition:all .15s}
.sc-btn-qty:hover{border-color:oklch(var(--p)/.4);color:oklch(var(--p))}
.sc-card-qty-row{display:flex;align-items:center;justify-content:center;gap:.5rem;margin-top:.4rem}
.sc-card-qty-val{font-size:.8rem;font-weight:700;min-width:18px;text-align:center;color:oklch(var(--bc))}

/* Floating cart drawer */
.sc-drawer-overlay{position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:200;opacity:0;pointer-events:none;transition:opacity .25s}
.sc-drawer-overlay.open{opacity:1;pointer-events:auto}
.sc-drawer{position:fixed;right:0;top:0;bottom:0;width:min(380px,92vw);background:oklch(var(--b1));z-index:201;transform:translateX(100%);transition:transform .3s cubic-bezier(.4,0,.2,1);display:flex;flex-direction:column;box-shadow:-4px 0 24px rgba(0,0,0,.1)}
.sc-drawer.open{transform:translateX(0)}
.sc-drawer-header{padding:.85rem 1rem;border-bottom:1px solid oklch(var(--bc)/.08);display:flex;align-items:center;justify-content:space-between}
.sc-drawer-header h3{font-size:1.05rem;font-weight:800;color:oklch(var(--bc))}
.sc-drawer-close{width:32px;height:32px;border-radius:8px;border:none;background:oklch(var(--bc)/.06);cursor:pointer;font-size:1.1rem;display:flex;align-items:center;justify-content:center;color:oklch(var(--bc)/.5);transition:all .15s}
.sc-drawer-close:hover{background:oklch(var(--bc)/.12);color:oklch(var(--bc))}
.sc-drawer-body{flex:1;overflow-y:auto;padding:.75rem 1rem}
.sc-drawer-empty{text-align:center;padding:3rem 1rem;color:oklch(var(--bc)/.35);font-size:.9rem}
.sc-drawer-item{display:flex;align-items:center;gap:.65rem;padding:.6rem 0;border-bottom:1px solid oklch(var(--bc)/.05)}
.sc-drawer-item-img{width:44px;height:44px;border-radius:8px;object-fit:cover;flex-shrink:0;background:oklch(var(--bc)/.05)}
.sc-drawer-item-info{flex:1;min-width:0}
.sc-drawer-item-name{font-size:.8rem;font-weight:700;color:oklch(var(--bc));white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.sc-drawer-item-meta{font-size:.72rem;color:oklch(var(--bc)/.5);margin-top:1px}
.sc-drawer-item-rm{width:24px;height:24px;border-radius:6px;border:none;background:oklch(var(--bc)/.06);cursor:pointer;font-size:.8rem;color:oklch(var(--bc)/.4);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.sc-drawer-item-rm:hover{background:#fee2e2;color:#ef4444}
.sc-drawer-footer{padding:.85rem 1rem;border-top:1px solid oklch(var(--bc)/.08)}
.sc-drawer-total{display:flex;justify-content:space-between;align-items:center;margin-bottom:.65rem}
.sc-drawer-total-label{font-size:.85rem;font-weight:600;color:oklch(var(--bc)/.6)}
.sc-drawer-total-val{font-size:1.15rem;font-weight:900;color:oklch(var(--bc))}
.sc-btn-wa{width:100%;display:flex;align-items:center;justify-content:center;gap:.5rem;padding:12px;border-radius:10px;border:none;background:#25D366;color:#fff;font-weight:800;font-size:.9rem;cursor:pointer;transition:opacity .2s}
.sc-btn-wa:hover{opacity:.88}
.sc-btn-wa svg{width:20px;height:20px;flex-shrink:0}

/* Payment section */
.sc-pay-section{padding:2rem 1rem;text-align:center;background:oklch(var(--bc)/.03)}
.sc-pay-title{font-size:1.2rem;font-weight:800;color:oklch(var(--bc));margin-bottom:1rem}
.sc-pay-pills{display:flex;flex-wrap:wrap;justify-content:center;gap:.4rem}
.sc-pay-pill{display:inline-flex;align-items:center;gap:.3rem;padding:5px 12px;border-radius:20px;border:1px solid oklch(var(--bc)/.08);background:oklch(var(--b1));font-size:.78rem;font-weight:600;color:oklch(var(--bc)/.7)}
.sc-pay-note{font-size:.7rem;color:oklch(var(--bc)/.35);margin-top:.75rem}

/* Footer */
.sc-footer{margin-top:auto;padding:1.5rem 1rem;border-top:1px solid oklch(var(--bc)/.06);text-align:center}
.sc-footer-biz{font-size:.85rem;font-weight:700;color:oklch(var(--bc)/.6)}
.sc-footer-biz a{color:oklch(var(--p));text-decoration:none}
.sc-footer-powered{font-size:.68rem;color:oklch(var(--bc)/.3);margin-top:.4rem;font-weight:600;letter-spacing:.03em}
</style>
</head>
<body>

{{-- ═══════════════════════════════════════════════════════════════════════════
  1. NAVBAR
═══════════════════════════════════════════════════════════════════════════ --}}
<nav class="sc-nav" style="background:oklch(var(--b1)/.92)">
    <div class="sc-nav-inner">
        {{-- Brand --}}
        <a href="#" class="sc-nav-brand">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="{{ $tenant->business_name }}">
            @else
                <span class="sc-nav-brand-icon">{{ mb_substr($tenant->business_name, 0, 1) }}</span>
            @endif
            <span>{{ $tenant->business_name }}</span>
        </a>

        <div class="sc-nav-actions">
            {{-- Currency toggle --}}
            @if($savedDisplayMode === 'both_toggle')
            <div class="sc-curr-toggle">
                <button class="sc-curr-btn active" data-currency="ref" onclick="setCurrency('ref')">{{ $currencySymbol }}</button>
                <button class="sc-curr-btn" data-currency="bs" onclick="setCurrency('bs')">Bs.</button>
            </div>
            @elseif($savedDisplayMode === 'euro_toggle')
            <div class="sc-curr-toggle">
                <button class="sc-curr-btn active" data-currency="eur" onclick="setCurrency('eur')">€</button>
                <button class="sc-curr-btn" data-currency="bs" onclick="setCurrency('bs')">Bs.</button>
            </div>
            @endif

            {{-- Cart button --}}
            <button class="sc-cart-btn" onclick="toggleDrawer()" id="sc-cart-trigger">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                <span>Pedido</span>
                <span class="sc-cart-badge" id="sc-cart-count" style="display:none">0</span>
            </button>
        </div>
    </div>
</nav>

{{-- ═══════════════════════════════════════════════════════════════════════════
  2. HERO
═══════════════════════════════════════════════════════════════════════════ --}}
<section class="sc-hero">
    @if($heroUrl)
        <div class="sc-hero-bg" style="background-image:url('{{ $heroUrl }}')"></div>
    @else
        <div class="sc-hero-bg sc-hero-gradient" style="background:linear-gradient(135deg,oklch(var(--p)),oklch(var(--s,var(--p))/.7));"></div>
    @endif
    <div class="sc-hero-content">
        <h1>{{ $tenant->business_name }}</h1>
        @if($tenant->slogan)
            <p>{{ $tenant->slogan }}</p>
        @elseif($tenant->description)
            <p>{{ Str::limit($tenant->description, 120) }}</p>
        @endif
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════════════
  3. GRID PRODUCTOS
═══════════════════════════════════════════════════════════════════════════ --}}
<section class="sc-grid-section" id="productos">
    <h2 class="sc-grid-title">Nuestros Productos</h2>
    <div class="sc-grid">
        @foreach($products as $product)
        <div class="sc-card" data-product-id="{{ $product->id }}">
            {{-- Image --}}
            @if($product->image_filename)
                <img class="sc-card-img"
                     src="{{ asset('storage/tenants/' . $tenant->id . '/' . $product->image_filename) }}"
                     alt="{{ $product->name }}"
                     loading="lazy">
            @else
                <div class="sc-card-placeholder">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/></svg>
                    <span>{{ Str::limit($product->name, 18) }}</span>
                </div>
            @endif

            <div class="sc-card-body">
                <div class="sc-card-name">{{ $product->name }}</div>

                @if($product->price_usd && !$hidePrice)
                    <div class="sc-card-price" data-price-usd="{{ $product->price_usd }}">
                        <span class="sym">{{ $currencySymbol }}</span>{{ number_format((float)$product->price_usd, 2) }}
                    </div>
                @elseif($hidePrice)
                    <div class="sc-card-price" style="font-size:.75rem;opacity:.5">Consultar precio</div>
                @endif

                {{-- Add to cart --}}
                <div class="sc-card-actions">
                    <button class="sc-btn-pedir"
                            onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price_usd ?? 0 }}, '{{ $product->image_filename ? asset('storage/tenants/' . $tenant->id . '/' . $product->image_filename) : '' }}')">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.588-5.946 0-6.556 5.332-11.887 11.887-11.887 3.18 0 6.171 1.242 8.425 3.496 2.257 2.253 3.496 5.244 3.496 8.425 0 6.557-5.331 11.89-11.887 11.89-2.018 0-4.003-.513-5.753-1.487l-6.267 1.672zm6.208-3.766l.348.206c1.517.896 3.268 1.369 5.068 1.369 5.451 0 9.887-4.436 9.887-9.889 0-2.641-1.03-5.123-2.9-6.992-1.868-1.87-4.35-2.903-6.993-2.903-5.452 0-9.889 4.437-9.889 9.889 0 1.883.53 3.722 1.534 5.312l.226.358-1.001 3.655 3.743-.984z"/></svg>
                        Pedir
                    </button>
                </div>

                {{-- Qty controls (hidden until added) --}}
                <div class="sc-card-qty-row" id="qty-row-{{ $product->id }}" style="display:none">
                    <button class="sc-btn-qty" onclick="changeQty({{ $product->id }}, -1)">−</button>
                    <span class="sc-card-qty-val" id="qty-val-{{ $product->id }}">1</span>
                    <button class="sc-btn-qty" onclick="changeQty({{ $product->id }}, 1)">+</button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════════════
  4. CARRITO WHATSAPP (Drawer lateral)
═══════════════════════════════════════════════════════════════════════════ --}}
<div class="sc-drawer-overlay" id="sc-overlay" onclick="toggleDrawer()"></div>
<aside class="sc-drawer" id="sc-drawer">
    <div class="sc-drawer-header">
        <h3>🛒 Tu Pedido</h3>
        <button class="sc-drawer-close" onclick="toggleDrawer()">✕</button>
    </div>
    <div class="sc-drawer-body" id="sc-drawer-body">
        <div class="sc-drawer-empty" id="sc-empty">Tu carrito está vacío.<br>Agrega productos para armar tu pedido.</div>
    </div>
    <div class="sc-drawer-footer" id="sc-drawer-footer" style="display:none">
        <div class="sc-drawer-total">
            <span class="sc-drawer-total-label">Total:</span>
            <span class="sc-drawer-total-val" id="sc-total">REF 0.00</span>
        </div>
        <button class="sc-btn-wa" onclick="sendWhatsApp()">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.588-5.946 0-6.556 5.332-11.887 11.887-11.887 3.18 0 6.171 1.242 8.425 3.496 2.257 2.253 3.496 5.244 3.496 8.425 0 6.557-5.331 11.89-11.887 11.89-2.018 0-4.003-.513-5.753-1.487l-6.267 1.672zm6.208-3.766l.348.206c1.517.896 3.268 1.369 5.068 1.369 5.451 0 9.887-4.436 9.887-9.889 0-2.641-1.03-5.123-2.9-6.992-1.868-1.87-4.35-2.903-6.993-2.903-5.452 0-9.889 4.437-9.889 9.889 0 1.883.53 3.722 1.534 5.312l.226.358-1.001 3.655 3.743-.984z"/></svg>
            Enviar pedido por WhatsApp
        </button>
    </div>
</aside>

{{-- ═══════════════════════════════════════════════════════════════════════════
  5. MEDIOS DE PAGO
═══════════════════════════════════════════════════════════════════════════ --}}
@if(!empty($visiblePay))
<section class="sc-pay-section" id="pagos">
    <h2 class="sc-pay-title">Medios de Pago</h2>
    <div class="sc-pay-pills">
        @foreach($visiblePay as $pm)
            <span class="sc-pay-pill">{{ $pm['icon'] }} {{ $pm['label'] }}</span>
        @endforeach
    </div>
    <p class="sc-pay-note">Información de medios de pago que aceptamos. Nuestro sitio web no es pasarela de pago.</p>
</section>
@endif

{{-- ═══════════════════════════════════════════════════════════════════════════
  6. FOOTER
═══════════════════════════════════════════════════════════════════════════ --}}
<footer class="sc-footer">
    <div class="sc-footer-biz">
        {{ $tenant->business_name }}
        @if($tenant->city) · {{ $tenant->city }} @endif
        @if($wa) · <a href="https://wa.me/{{ $waClean }}" target="_blank" rel="noopener">WhatsApp</a> @endif
    </div>
    <div class="sc-footer-powered">Powered by <strong>SYNTIcat</strong></div>
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
            return '<span class="sym">Bs.</span>' + (val * rate).toLocaleString('es-VE', {minimumFractionDigits:2, maximumFractionDigits:2});
        }
        if (currentCurrency === '€') {
            return '<span class="sym">€</span>' + val.toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2});
        }
        return '<span class="sym">' + CURRENCY_SYMBOL + '</span>' + val.toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2});
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
            btn.classList.toggle('active', isActive);
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
            div.className = 'sc-drawer-item';
            div.innerHTML =
                (item.img ? '<img class="sc-drawer-item-img" src="' + item.img + '" alt="">' : '<div class="sc-drawer-item-img"></div>') +
                '<div class="sc-drawer-item-info">' +
                    '<div class="sc-drawer-item-name">' + item.name + '</div>' +
                    '<div class="sc-drawer-item-meta">' + item.qty + ' × ' + formatPricePlain(item.price) + '</div>' +
                '</div>' +
                '<button class="sc-drawer-item-rm" data-rm-id="' + id + '" title="Quitar">✕</button>';
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
