{{-- ═══════════════════════════════════════════════════════════════════════════════
     SYNTIfood — Storefront Menú Digital + Pedido WhatsApp
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

    $categories  = $menu ?? [];
    $plan        = $tenant->plan ?? null;
    $isPlanAnual = $plan && (in_array($plan->slug ?? '', ['food-anual']) || ($plan->id ?? 0) >= 3);

    $customerFields = $customization->customer_required_fields
        ?? ($tenant->settings['food_settings']['customer_fields'] ?? ['name']);
    $needsName = in_array('name', (array) $customerFields);
@endphp

@push('styles')
<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .sf-drawer-overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);backdrop-filter:blur(2px);-webkit-backdrop-filter:blur(2px);z-index:200;opacity:0;pointer-events:none;transition:opacity .3s ease}
    .sf-drawer-overlay.open{opacity:1;pointer-events:auto}
    .sf-drawer{position:fixed;right:0;top:0;bottom:0;width:min(420px,95vw);z-index:201;transform:translateX(105%);transition:transform .4s cubic-bezier(.4,0,.2,1);display:flex;flex-direction:column;box-shadow:-20px 0 60px rgba(0,0,0,.12);border-top-left-radius:2rem;border-bottom-left-radius:2rem}
    .sf-drawer.open{transform:translateX(0)}
    @keyframes bump{0%,100%{transform:scale(1)}50%{transform:scale(1.3)}}
    .bump{animation:bump .25s ease-out}
    .sf-modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);backdrop-filter:blur(2px);-webkit-backdrop-filter:blur(2px);z-index:300;display:none;align-items:center;justify-content:center;padding:16px}
    .sf-modal{max-width:420px;width:100%;background:var(--background,#fff);border-radius:24px;box-shadow:0 30px 80px rgba(0,0,0,.25);border:1px solid rgba(0,0,0,.05)}
    .sf-field{position:relative}
    .sf-field input{width:100%;padding:1.35rem 1rem .55rem;border-radius:1rem;border:1.5px solid rgba(0,0,0,.08);background:var(--surface,#f3f4f6);font-weight:700;font-size:.875rem;outline:none;transition:border-color .2s,background .2s,box-shadow .2s;color:inherit}
    .sf-field input:focus{border-color:var(--primary,#570DF8);background:var(--background,#fff);box-shadow:0 0 0 3px color-mix(in oklch,var(--primary,#570DF8) 15%,transparent)}
    .sf-field label{position:absolute;left:1rem;top:50%;transform:translateY(-50%);font-size:.825rem;font-weight:700;color:rgba(0,0,0,.35);pointer-events:none;transition:all .18s cubic-bezier(.4,0,.2,1)}
    .sf-field input:focus+label,.sf-field input:not(:placeholder-shown)+label{top:.6rem;transform:none;font-size:.65rem;letter-spacing:.05em;color:var(--primary,#570DF8)}
    .sf-field-error{border-color:#ef4444!important;background:#fff5f5!important}
    .sf-field-error:focus{box-shadow:0 0 0 3px rgba(239,68,68,.15)!important}
</style>
@endpush

@section('content')

{{-- 1. HEADER --}}
<header class="sticky top-0 z-[100] w-full bg-background/90 backdrop-blur-2xl" style="border-bottom:1px solid rgba(0,0,0,.07);">
    <div class="mx-auto max-w-3xl px-4 flex items-center justify-between h-16">
        <a href="#" class="flex items-center gap-2.5 min-w-0">
            @if(!empty($customization->logo_filename))
                <img src="{{ asset('storage/tenants/' . $tenant->id . '/' . $customization->logo_filename) }}"
                     alt="{{ $tenant->business_name }}"
                     class="size-10 rounded-xl object-cover shrink-0"
                     onerror="this.style.display='none';">
            @else
                <div class="size-10 bg-primary rounded-xl flex items-center justify-center shadow-lg shadow-primary/20 shrink-0">
                    <span class="text-primary-foreground font-black text-lg">{{ mb_substr($tenant->business_name, 0, 1) }}</span>
                </div>
            @endif
            <div class="min-w-0">
                <span class="text-base font-black tracking-tighter truncate block">{{ $tenant->business_name }}</span>
                @if($tenant->is_open ?? false)
                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-green-600">
                        <span class="size-1.5 rounded-full bg-green-500"></span> Abierto
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-red-500">
                        <span class="size-1.5 rounded-full bg-red-500"></span> Cerrado
                    </span>
                @endif
            </div>
        </a>

        <div class="flex items-center gap-2.5">
            @if(str_contains($savedDisplayMode, 'toggle'))
            <div class="flex bg-surface/50 p-1 rounded-xl border border-foreground/5 backdrop-blur-md">
                <button class="sf-curr-btn px-3 py-1.5 text-xs font-bold rounded-lg transition-all" data-currency="ref" onclick="setCurrency('ref')">{{ $currencySymbol }}</button>
                <button class="sf-curr-btn px-3 py-1.5 text-xs font-bold rounded-lg transition-all" data-currency="bs" onclick="setCurrency('bs')">Bs</button>
            </div>
            @endif

            @if($waClean)
            <a href="https://wa.me/{{ $waClean }}" target="_blank"
               class="hidden sm:flex text-sm py-1.5 px-3 rounded-2xl font-bold text-white transition-colors gap-1.5 border-none"
               style="background:#25D366;">
                <span class="iconify tabler--brand-whatsapp size-4"></span>
                Contactar
            </a>
            @endif

            @if($isPlanAnual)
            <button onclick="toggleDrawer()" class="relative group p-2 rounded-full transition-colors bg-primary text-primary-foreground hover:bg-primary/90 shadow-xl shadow-primary/20">
                <span class="iconify tabler--shopping-bag size-6"></span>
                <span id="sf-cart-count" class="absolute -top-1 -right-1 size-5 bg-error text-white text-[10px] font-black rounded-full flex items-center justify-center border-2 border-background" style="display:none">0</span>
            </button>
            @endif
        </div>
    </div>
</header>

{{-- 2. HERO MINIMAL --}}
@if($tenant->slogan || $dollarRate)
<section class="bg-background border-b border-foreground/5">
    <div class="mx-auto max-w-3xl px-4 py-6">
        @if($tenant->slogan)
            <p class="text-lg font-bold text-foreground leading-snug">{{ $tenant->slogan }}</p>
        @endif
        @if($dollarRate)
            <p class="text-xs text-foreground/40 font-medium mt-1">
                Tasa BCV: Bs. {{ number_format((float) $dollarRate, 2, ',', '.') }}
            </p>
        @endif
    </div>
</section>
@endif

{{-- 3. CATEGORIES + ITEMS --}}
<main class="mx-auto max-w-3xl px-4 py-6 space-y-4 pb-32">
    @forelse($categories as $catIdx => $cat)
        @if(!empty($cat['activo']))
        <div x-data="{ open: {{ $catIdx === 0 ? 'true' : 'false' }} }" class="rounded-2xl border border-foreground/5 bg-surface/30 overflow-hidden">
            {{-- Category header --}}
            <button @click="open = !open" class="w-full flex items-center gap-3 p-4 text-left hover:bg-surface/60 transition-colors">
                @if(!empty($cat['foto']))
                    <img src="{{ asset('storage/tenants/' . $tenant->id . '/menu/' . $cat['foto']) }}"
                         alt="{{ $cat['nombre'] }}"
                         class="size-14 rounded-xl object-cover shrink-0"
                         loading="lazy"
                         onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                    <div class="size-14 rounded-xl bg-surface items-center justify-center shrink-0 hidden">
                        <span class="iconify tabler--bowl-chopsticks size-6 text-foreground/20"></span>
                    </div>
                @else
                    <div class="size-14 rounded-xl bg-surface flex items-center justify-center shrink-0">
                        <span class="iconify tabler--bowl-chopsticks size-6 text-foreground/20"></span>
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    <h3 class="text-base font-black tracking-tight text-foreground truncate">{{ $cat['nombre'] }}</h3>
                    <p class="text-xs text-foreground/40 font-medium mt-0.5">{{ count($cat['items'] ?? []) }} platos</p>
                </div>
                <span class="iconify size-5 text-foreground/30 transition-transform duration-200 shrink-0"
                      :class="open ? 'tabler--chevron-up' : 'tabler--chevron-down'"></span>
            </button>

            {{-- Items --}}
            <div x-show="open" x-collapse>
                <div class="border-t border-foreground/5 divide-y divide-foreground/5">
                    @foreach($cat['items'] ?? [] as $item)
                        @if(!empty($item['activo']))
                        @php $itemId = $item['id'] ?? ''; @endphp
                        <div class="flex items-center gap-3 px-4 py-3">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-foreground truncate">{{ $item['nombre'] }}</p>
                                @if(!empty($item['descripcion']))
                                    <p class="text-xs text-foreground/40 line-clamp-1">{{ $item['descripcion'] }}</p>
                                @endif
                            </div>

                            @if(!$hidePrice)
                            <p class="text-sm font-black tracking-tight text-foreground whitespace-nowrap" data-price-usd="{{ $item['precio'] ?? 0 }}">
                                {{ $currencySymbol }} 0.00
                            </p>
                            @endif

                            @if($isPlanAnual)
                            {{-- Add button --}}
                            <button id="sf-add-{{ $itemId }}"
                                    onclick="addToCart('{{ addslashes($itemId) }}', '{{ addslashes($item['nombre'] ?? '') }}', {{ $item['precio'] ?? 0 }})"
                                    class="size-9 rounded-full bg-primary/10 text-primary flex items-center justify-center shrink-0 hover:bg-primary hover:text-primary-foreground transition-colors">
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                            </button>
                            {{-- Qty controls (hidden until added) --}}
                            <div id="qty-row-{{ $itemId }}" class="flex items-center gap-1 bg-surface rounded-full px-2 py-1" style="display:none!important">
                                <button class="size-6 rounded-full bg-background flex items-center justify-center text-xs font-bold" onclick="changeQty('{{ addslashes($itemId) }}', -1)">−</button>
                                <span class="text-xs font-black min-w-[14px] text-center" id="qty-val-{{ $itemId }}">1</span>
                                <button class="size-6 rounded-full bg-primary text-primary-foreground flex items-center justify-center text-xs font-bold" onclick="changeQty('{{ addslashes($itemId) }}', 1)">+</button>
                            </div>
                            @endif
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    @empty
        <div class="text-center py-16">
            <div class="size-16 rounded-2xl bg-surface flex items-center justify-center mx-auto mb-4">
                <span class="iconify tabler--bowl-chopsticks size-8 text-foreground/20"></span>
            </div>
            <p class="font-bold text-foreground/30">Menú en construcción</p>
            <p class="text-xs text-foreground/20 mt-1">Pronto habrá platos disponibles</p>
        </div>
    @endforelse
</main>

{{-- 4. DRAWER PEDIDO --}}
@if($isPlanAnual)
<div class="sf-drawer-overlay" id="sf-overlay" onclick="toggleDrawer()"></div>
<aside class="sf-drawer bg-background" id="sf-drawer">
    {{-- Header --}}
    <div class="px-7 pt-7 pb-5 flex items-center justify-between border-b border-foreground/5">
        <div>
            <h3 class="text-2xl font-black tracking-tighter">Mi Pedido</h3>
            <p class="text-[10px] text-foreground/30 font-black uppercase tracking-[.25em] mt-0.5">Resumen</p>
        </div>
        <button onclick="toggleDrawer()" class="p-2 rounded-full text-sm transition-colors text-foreground/80 hover:bg-surface bg-surface/80">
            <span class="iconify tabler--x size-5"></span>
        </button>
    </div>

    {{-- Empty state --}}
    <div id="sf-empty" class="flex-1 flex flex-col items-center justify-center text-center py-14 px-7">
        <div class="size-20 rounded-3xl bg-surface flex items-center justify-center mb-4">
            <span class="iconify tabler--bowl-chopsticks size-10 text-foreground/20"></span>
        </div>
        <p class="font-bold text-foreground/30">Tu pedido está vacío</p>
        <p class="text-xs text-foreground/20 mt-1">Explora el menú y agrega platos</p>
    </div>

    {{-- Items list --}}
    <div class="flex-1 overflow-y-auto px-7 no-scrollbar" id="sf-drawer-body" style="display:none"></div>

    {{-- Footer --}}
    <div class="p-7 space-y-4 border-t border-foreground/5" id="sf-drawer-footer" style="display:none">
        <div class="bg-surface/60 p-5 rounded-[1.5rem] border border-foreground/5">
            <div class="flex justify-between items-center text-foreground/40 text-xs font-black uppercase tracking-widest mb-1">
                <span>Total</span>
                <span id="sf-total-label">{{ $currencySymbol }}</span>
            </div>
            <div class="text-4xl font-black tracking-tighter" id="sf-total">0.00</div>
        </div>

        @if($waClean)
        <button onclick="sendWhatsApp()" class="flex items-center justify-center w-full h-14 rounded-[1.5rem] border-none font-black text-base gap-2.5 shadow-xl text-white transition-colors"
                style="background:#25D366;">
            <span class="iconify tabler--brand-whatsapp size-6"></span>
            Enviar por WhatsApp
        </button>
        @endif
    </div>
</aside>

{{-- Floating bar --}}
<div id="sf-floating-bar" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[190] w-[calc(100%-2rem)] max-w-md" style="display:none">
    <button onclick="toggleDrawer()"
            class="w-full flex items-center justify-between bg-primary text-primary-foreground px-5 py-4 rounded-2xl font-bold text-sm shadow-2xl shadow-primary/30 hover:opacity-95 transition-opacity">
        <span class="flex items-center gap-2">
            <span class="iconify tabler--shopping-bag size-5"></span>
            Ver pedido (<span id="sf-float-count">0</span> ítems)
        </span>
        <span class="iconify tabler--arrow-right size-5"></span>
    </button>
</div>
@endif

{{-- MODAL Datos Cliente --}}
@if($isPlanAnual && $needsName)
<div id="sf-data-modal" class="sf-modal-overlay">
    <div class="sf-modal p-6 space-y-5">
        <div class="flex items-start gap-3">
            <div class="size-10 rounded-2xl bg-primary/10 flex items-center justify-center text-primary shrink-0">
                <svg aria-hidden="true" focusable="false" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="7" r="4"></circle>
                    <path d="M5.5 21a6.5 6.5 0 0 1 13 0"></path>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-lg font-black">Antes de enviar</p>
                <p class="text-sm text-foreground/60">Déjanos tu nombre para personalizar tu pedido.</p>
            </div>
            <button onclick="closeDataModal()" class="p-2 rounded-full text-sm text-foreground/80 hover:bg-surface transition-colors">
                <svg aria-hidden="true" focusable="false" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="sf-field">
            <input type="text" id="sf-customer-name" placeholder=" " autocomplete="given-name" inputmode="text">
            <label for="sf-customer-name">¿Cómo te llamas?</label>
        </div>

        <div class="flex gap-3">
            <button onclick="confirmDataAndSend()" class="py-2 px-4 rounded-xl font-medium transition-colors bg-primary text-primary-foreground hover:bg-primary/90 flex-1 font-black">Enviar por WhatsApp</button>
            <button onclick="closeDataModal()" class="py-2 px-4 rounded-xl font-medium transition-colors text-foreground/80 hover:bg-surface">Cancelar</button>
        </div>
    </div>
</div>
@endif

{{-- 5. FOOTER --}}
<footer class="bg-surface/50 border-t border-foreground/5 py-14">
    <div class="mx-auto max-w-3xl px-4 text-center">
        @if(!empty($customization->logo_filename))
            <img src="{{ asset('storage/tenants/' . $tenant->id . '/' . $customization->logo_filename) }}"
                 alt="{{ $tenant->business_name }}"
                 class="size-14 rounded-2xl object-cover mx-auto mb-5 border border-foreground/5 shadow-sm"
                 onerror="this.style.display='none';">
        @else
            <div class="size-14 bg-background rounded-2xl mx-auto mb-5 flex items-center justify-center border border-foreground/5 shadow-sm">
                <span class="iconify tabler--bowl-chopsticks size-7 text-primary"></span>
            </div>
        @endif

        <h2 class="text-2xl font-black tracking-tighter mb-1">{{ $tenant->business_name }}</h2>
        @if($tenant->description)
            <p class="text-foreground/40 text-sm max-w-md mx-auto">{{ Str::limit($tenant->description, 160) }}</p>
        @endif

        @if($waClean)
        <a href="https://wa.me/{{ $waClean }}" target="_blank"
           class="inline-flex items-center gap-2 text-sm py-1.5 px-3 rounded-2xl font-bold text-white transition-colors mt-6 border-none"
           style="background:#25D366;">
            <span class="iconify tabler--brand-whatsapp size-4"></span>
            Escribir por WhatsApp
        </a>
        @endif

        <div class="mt-10 pt-8 border-t border-foreground/5 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-xs font-bold text-foreground/20 uppercase tracking-[0.25em]">© {{ date('Y') }} {{ $tenant->business_name }}</p>
            <p class="text-xs text-foreground/20">Sitio creado con <span class="text-primary font-bold">SYNTIweb</span></p>
        </div>
    </div>
</footer>

@endsection

@push('scripts')
<script>
(function(){
    'use strict';
    const CURRENCY_MODE   = @json($savedDisplayMode);
    const CURRENCY_SYMBOL = @json($currencySymbol);
    const EXCHANGE_RATE   = @json($dollarRate);
    const EURO_RATE       = @json($euroRate);
    let currentCurrency   = (CURRENCY_MODE === 'bolivares_only') ? 'Bs.' : (CURRENCY_MODE === 'euro_toggle' ? '€' : CURRENCY_SYMBOL);
    var cart = {};

    function formatPrice(usdPrice, isPlain) {
        isPlain = isPlain || false;
        const val = parseFloat(usdPrice) || 0;
        let rate = (currentCurrency === 'Bs.') ? (CURRENCY_MODE === 'euro_toggle' ? EURO_RATE : EXCHANGE_RATE) : 1;
        let formatted = (val * rate).toLocaleString('es-VE', {minimumFractionDigits:2});
        if (isPlain) return currentCurrency + ' ' + formatted;
        return '<span class="text-xs opacity-40 mr-1">' + currentCurrency + '</span>' + formatted;
    }

    window.setCurrency = function(mode) {
        currentCurrency = (mode === 'bs') ? 'Bs.' : (mode === 'eur' ? '€' : CURRENCY_SYMBOL);
        document.querySelectorAll('[data-price-usd]').forEach(function(el) { el.innerHTML = formatPrice(el.getAttribute('data-price-usd')); });
        document.querySelectorAll('.sf-curr-btn').forEach(function(btn) {
            var active = (btn.dataset.currency === 'ref' && currentCurrency === CURRENCY_SYMBOL) || (btn.dataset.currency === 'bs' && currentCurrency === 'Bs.');
            btn.className = 'sf-curr-btn px-3 py-1.5 text-xs font-bold rounded-lg transition-all ' + (active ? 'bg-background shadow-lg text-primary' : 'text-foreground/40');
        });
        renderDrawer();
    };

    window.addToCart = function(id, name, price) {
        var isNew = !cart[id];
        if (cart[id]) { cart[id].qty++; } else { cart[id] = { name: name, price: parseFloat(price) || 0, qty: 1 }; }
        updateBadge();
        renderDrawer();
        var addBtn = document.getElementById('sf-add-' + id);
        if (addBtn) addBtn.style.display = 'none';
        var qr = document.getElementById('qty-row-' + id);
        if (qr) qr.style.cssText = 'display:flex!important';
        var qv = document.getElementById('qty-val-' + id);
        if (qv) qv.textContent = cart[id].qty;
        if (isNew && Object.keys(cart).length === 1) toggleDrawer();
    };

    window.changeQty = function(id, delta) {
        if (!cart[id]) return;
        cart[id].qty += delta;
        if (cart[id].qty <= 0) {
            delete cart[id];
            var qr = document.getElementById('qty-row-' + id);
            if (qr) qr.style.cssText = 'display:none!important';
            var addBtn = document.getElementById('sf-add-' + id);
            if (addBtn) addBtn.style.display = '';
        } else {
            var qv = document.getElementById('qty-val-' + id);
            if (qv) qv.textContent = cart[id].qty;
        }
        updateBadge();
        renderDrawer();
    };

    function updateBadge() {
        var total = Object.values(cart).reduce(function(a, b) { return a + b.qty; }, 0);
        var b = document.getElementById('sf-cart-count');
        if (b) {
            b.style.display = total > 0 ? 'flex' : 'none';
            b.textContent = total;
            b.classList.remove('bump'); void b.offsetWidth; b.classList.add('bump');
        }
        var fb = document.getElementById('sf-floating-bar');
        if (fb) fb.style.display = total > 0 ? '' : 'none';
        var fc = document.getElementById('sf-float-count');
        if (fc) fc.textContent = total;
    }

    window.toggleDrawer = function() {
        var overlay = document.getElementById('sf-overlay');
        var drawer  = document.getElementById('sf-drawer');
        if (!overlay || !drawer) return;
        overlay.classList.toggle('open');
        drawer.classList.toggle('open');
        document.body.style.overflow = drawer.classList.contains('open') ? 'hidden' : '';
    };

    function renderDrawer() {
        var body   = document.getElementById('sf-drawer-body');
        var footer = document.getElementById('sf-drawer-footer');
        var empty  = document.getElementById('sf-empty');
        if (!body || !footer || !empty) return;

        body.innerHTML = '';
        var keys = Object.keys(cart);

        if (keys.length === 0) {
            empty.style.display  = 'flex';
            body.style.display   = 'none';
            footer.style.display = 'none';
            return;
        }

        empty.style.display  = 'none';
        body.style.display   = 'block';
        footer.style.display = 'block';

        var totalUsd = 0;
        keys.forEach(function(id) {
            var item = cart[id];
            totalUsd += item.price * item.qty;
            var div = document.createElement('div');
            div.className = 'flex items-center gap-3 py-4 border-b border-foreground/5 last:border-0';
            div.innerHTML = '<div style="flex:1;min-width:0">'
                + '<p style="font-weight:800;font-size:.875rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;margin-bottom:2px">' + item.name + '</p>'
                + '<p style="font-size:.75rem;font-weight:700;opacity:.45">' + item.qty + ' × ' + formatPrice(item.price, true) + '</p>'
                + '</div>'
                + '<div style="display:flex;align-items:center;gap:6px">'
                + '<button onclick="changeQty(\'' + id + '\', -1)" style="width:28px;height:28px;border-radius:50%;border:none;cursor:pointer;background:var(--surface,#f3f4f6);font-weight:900;font-size:.8rem;line-height:1">−</button>'
                + '<span style="font-size:.8rem;font-weight:900;min-width:14px;text-align:center">' + item.qty + '</span>'
                + '<button onclick="changeQty(\'' + id + '\', 1)" style="width:28px;height:28px;border-radius:50%;border:none;cursor:pointer;background:var(--primary);color:var(--primary-foreground);font-weight:900;font-size:.8rem;line-height:1">+</button>'
                + '</div>';
            body.appendChild(div);
        });
        document.getElementById('sf-total').innerHTML = formatPrice(totalUsd);
        document.getElementById('sf-total-label').textContent = currentCurrency;
    }

    window.closeDataModal = function() {
        var modal = document.getElementById('sf-data-modal');
        if (modal) modal.style.display = 'none';
    };

    function openDataModal() {
        var modal = document.getElementById('sf-data-modal');
        if (modal) modal.style.display = 'flex';
    }

    function buildAndSend(name) {
        var waNumber = @json($waClean);
        if (!waNumber) return;
        var businessName = @json($tenant->business_name);
        var greeting = name
            ? '🍽 ¡Hola! Soy *' + name + '* y vengo de la web de *' + businessName + '*'
            : '🍽 ¡Hola! Les escribo desde la web de *' + businessName + '*';
        var totalUsd = Object.values(cart).reduce(function(a, b) { return a + (b.price * b.qty); }, 0);
        var msg = greeting + '\n\n*Mi pedido:*\n';
        msg += Object.values(cart).map(function(i) { return '• ' + i.name + ' ×' + i.qty + ' (' + formatPrice(i.price * i.qty, true) + ')'; }).join('\n');
        msg += '\n\n*Total: ' + formatPrice(totalUsd, true) + '*';
        window.open('https://wa.me/' + waNumber + '?text=' + encodeURIComponent(msg), '_blank');
        cart = {};
        updateBadge();
        renderDrawer();
        closeDataModal();
    }

    window.sendWhatsApp = function() {
        @if(!$needsName)
            buildAndSend('');
            return;
        @endif

        var nameEl = document.getElementById('sf-customer-name');
        var name   = nameEl ? nameEl.value.trim() : '';

        if (nameEl && !name) {
            openDataModal();
            return;
        }

        if (name) localStorage.setItem('sf_customer_name', name);
        buildAndSend(name);
    };

    window.confirmDataAndSend = function() {
        var nameEl = document.getElementById('sf-customer-name');
        var name   = nameEl ? nameEl.value.trim() : '';

        @if($needsName)
        if (nameEl && !name) {
            nameEl.classList.add('sf-field-error');
            nameEl.focus();
            return;
        }
        @endif

        if (name) localStorage.setItem('sf_customer_name', name);
        closeDataModal();
        buildAndSend(name);
    };

    document.addEventListener('DOMContentLoaded', function() {
        setCurrency(currentCurrency === 'Bs.' ? 'bs' : 'ref');

        var savedName = localStorage.getItem('sf_customer_name');
        if (savedName) {
            var el = document.getElementById('sf-customer-name');
            if (el) el.value = savedName;
        }

        var nameEl = document.getElementById('sf-customer-name');
        if (nameEl) nameEl.addEventListener('input', function() { nameEl.classList.remove('sf-field-error'); });
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
        }).catch(function() {});
    }
    track('pageview');
    document.addEventListener('click', function(e) {
        if (e.target.closest('a[href*="wa.me"]')) track('click_whatsapp');
        if (e.target.closest('a[href^="tel:"]')) track('click_call');
    });
    setInterval(function() { track('time_on_page'); }, 30000);
})();
</script>
@endpush
