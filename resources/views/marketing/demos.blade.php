@extends('marketing.layout')

@push('seo')
<title>Demos en vivo — Tiendas online, restaurantes y negocios digitales | SYNTIweb</title>
<meta name="description" content="7 demos en vivo de negocios reales en Venezuela: tiendas online, restaurantes con menú digital, clínicas, gimnasios y más. Construidos con SYNTIweb. Crea tu ecommerce hoy.">
<meta name="keywords" content="tienda online, ecommerce Venezuela, carrito de compra, WhatsApp business, sitio web negocio, emprendedor digital, catálogo digital, menú restaurante, landing page">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
<meta name="author" content="SYNTIweb">
<meta name="language" content="es">
<meta property="og:title"       content="Demos en vivo — Tiendas online y negocios digitales | SYNTIweb">
<meta property="og:description" content="Explora 7 demos en vivo de negocios venezolanos. Tienda online, menú digital, catálogo WhatsApp y más.">
<meta property="og:url"         content="{{ url()->current() }}">
<meta property="og:image"       content="{{ asset('brand/syntiweb-og.png') }}">
<meta property="og:type"        content="website">
<meta property="og:site_name"   content="SYNTIweb">
<meta property="og:locale"      content="es_VE">
<meta name="twitter:card"       content="summary_large_image">
<meta name="twitter:title"      content="Demos en vivo | SYNTIweb">
<meta name="twitter:description" content="7 demos de tiendas online, restaurantes y negocios digitales.">
<meta name="twitter:image"      content="{{ asset('brand/syntiweb-og.png') }}">
<link rel="canonical" href="{{ url()->current() }}">
@endpush

@push('head')
<style>
    /* ── Arc Gallery animations ─────────────────────────────────── */
    @keyframes sw-arc-up {
        from { opacity: 0; transform: translateY(18px) scale(0.95); }
        to   { opacity: 1; transform: translateY(0)    scale(1);    }
    }
    @keyframes sw-hero-in {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0);    }
    }

    .sw-arc-card     { opacity: 0; animation: sw-arc-up  0.65s ease-out forwards; }
    .sw-arc-headline { opacity: 0; animation: sw-hero-in 0.80s ease-out 650ms forwards; }

    /* ── Arc card rotation + hover via CSS custom property ─────── */
    .sw-arc-inner {
        display: block;
        width: 100%; height: 100%;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        ring-width: 1px;
        background: #fff;
        transition: transform 0.2s ease;
        transform: rotate(var(--arc-rot, 0deg));
        will-change: transform;
    }
    .sw-arc-inner:hover { transform: rotate(var(--arc-rot, 0deg)) scale(1.07); }
    .sw-arc-inner img   { display: block; width: 100%; height: 100%; object-fit: cover; }

    /* ── Masonry 12-col grid (Tailwind 4 doesn't scan arbitrary col counts) ─── */
    /* DEPRECATED - using standard Tailwind grid sm:grid-cols-12 instead */

    /* ── Normalize card sizes: same height for row 1/3, consistent for row 2 -- */
    .demo-card {
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .dg-c6 .demo-card { min-height: 340px; }
    .dg-md-c4 .demo-card { min-height: 280px; }
</style>
@endpush

@section('content')

@php
/**
 * Demo card styling is now handled via inline styles in the HTML.
 * This keeps the template cleaner and easier to maintain.
 */
@endphp

{{-- ══════════════════════════════════════════════════════════════ --}}
{{-- 1. ARC GALLERY HERO                                           --}}
{{-- ══════════════════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-[#F8FAFF] flex flex-col">

    {{-- Arc container — height is recalculated by JS on load / resize --}}
    <div id="sw-arc-container" class="relative mx-auto w-full" style="height:576px">

        @foreach($demos as $i => $demo)
        {{-- Each card is positioned absolutely by JS (layoutArc) --}}
        <div class="sw-arc-card absolute" data-arc-index="{{ $i }}"
             style="animation-delay:{{ $i * 100 }}ms">
            <a href="{{ $demo['url'] }}"
               class="sw-arc-inner"
               title="{{ $demo['name'] }}">
                {{-- REEMPLAZAR con screenshot real del tenant demo cuando esté disponible --}}
                <img src="{{ $demo['arc_image'] }}"
                     alt="{{ $demo['name'] }}"
                     loading="lazy" draggable="false">
            </a>
        </div>
        @endforeach

    </div>

    {{-- Central copy — pulled up over the arc bottom with negative margin --}}
    <div class="relative z-10 flex items-center justify-center px-6 -mt-32 md:-mt-44 lg:-mt-52 pb-16 sm:pb-24">
        <div class="sw-arc-headline text-center max-w-2xl px-4">

            {{-- Pill badge --}}
            <div class="inline-flex items-center gap-2 mb-5 px-4 py-1.5 rounded-full"
                 style="background:rgba(74,128,228,0.10)">
                <iconify-icon icon="tabler:sparkles" width="14" style="color:#4A80E4"></iconify-icon>
                <span class="text-xs font-bold uppercase tracking-wider" style="color:#4A80E4">
                    {{ count($demos) }} demos en vivo
                </span>
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight"
                style="color:#1a1a1a">
                Ve tu negocio<br>
                <span class="mkt-gradient-text">vivo hoy</span>
            </h1>

            <p class="mt-5 text-lg text-slate-500 max-w-lg mx-auto leading-relaxed">
                Negocios reales de Venezuela, construidos con SYNTIweb.<br class="hidden sm:block">
                Explóralos, elige el tuyo y empieza sin pagar.
            </p>

            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a href="#demos-grid"
                   class="inline-flex items-center gap-2 px-6 py-3 rounded-full text-sm font-bold text-white transition-all duration-200"
                   style="background:#4A80E4;box-shadow:0 4px 16px rgba(74,128,228,0.35)">
                    <iconify-icon icon="tabler:layout-grid" width="16"></iconify-icon>
                    Ver los {{ count($demos) }} demos
                </a>
                <a href="{{ route('register') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 rounded-full text-sm font-semibold text-slate-700 border border-slate-200 bg-white hover:bg-slate-50 transition-all duration-200">
                    Crear el mío gratis →
                </a>
            </div>

        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════ --}}
{{-- 2. MASONRY DEMOS GRID                                         --}}
{{-- ══════════════════════════════════════════════════════════════ --}}
<section id="demos-grid" class="bg-white py-10 sm:py-14">
    <div class="max-w-6xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">

        {{-- Section header --}}
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight" style="color:#1a1a1a">
                Elige tu segmento
            </h2>
            <p class="mt-3 text-slate-500 max-w-lg mx-auto text-base leading-relaxed">
                Restaurantes, tiendas, clínicas, gimnasios y más.
                Cada demo es un negocio real con WhatsApp integrado y visible en Google.
            </p>
        </div>

        {{-- Masonry Cards - Preline Reference Design --}}
        <div class="grid sm:grid-cols-12 gap-6">

            {{-- Card 1 --}}
            <div class="sm:self-end col-span-12 sm:col-span-7 md:col-span-8 lg:col-span-5 lg:col-start-3">
                <a class="group relative block rounded-xl overflow-hidden focus:outline-none" href="#">
                    <div class="rounded-xl overflow-hidden">
                        <img class="group-hover:scale-105 group-focus:scale-105 transition-transform duration-500 ease-in-out rounded-xl w-full object-cover" 
                             src="https://images.unsplash.com/photo-1606868306217-dbf5046868d2?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=560&q=80" alt="Demo 1">
                    </div>
                    <div class="absolute bottom-0 start-0 end-0 p-2 sm:p-4">
                        <div class="text-sm font-semibold bg-layer text-layer-foreground rounded-lg p-4 md:text-xl">
                            Donaz
                        </div>
                    </div>
                </a>
            </div>

            {{-- Card 2 --}}
            <div class="sm:self-end col-span-12 sm:col-span-5 md:col-span-4 lg:col-span-3">
                <a class="group relative block rounded-xl overflow-hidden focus:outline-none" href="#">
                    <div class="rounded-xl overflow-hidden">
                        <img class="group-hover:scale-105 group-focus:scale-105 transition-transform duration-500 ease-in-out rounded-xl w-full object-cover" 
                             src="https://images.unsplash.com/photo-1605629921711-2f6b00c6bbf4?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=560&q=80" alt="Demo 2">
                    </div>
                    <div class="absolute bottom-0 start-0 end-0 p-2 sm:p-4">
                        <div class="text-sm font-semibold bg-layer text-layer-foreground rounded-lg p-4 md:text-xl">
                            Belle Store
                        </div>
                    </div>
                </a>
            </div>

            {{-- Card 3 --}}
            <div class="col-span-12 md:col-span-4">
                <a class="group relative block rounded-xl overflow-hidden focus:outline-none" href="#">
                    <div class="rounded-xl overflow-hidden">
                        <img class="group-hover:scale-105 group-focus:scale-105 transition-transform duration-500 ease-in-out rounded-xl w-full object-cover" 
                             src="https://images.unsplash.com/photo-1606836576983-8b458e75221d?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=560&q=80" alt="Demo 3">
                    </div>
                    <div class="absolute bottom-0 start-0 end-0 p-2 sm:p-4">
                        <div class="text-sm font-semibold bg-layer text-layer-foreground rounded-lg p-4 md:text-xl">
                            MediCenter
                        </div>
                    </div>
                </a>
            </div>

            {{-- Card 4 --}}
            <div class="col-span-12 sm:col-span-6 md:col-span-4">
                <a class="group relative block rounded-xl overflow-hidden focus:outline-none" href="#">
                    <div class="rounded-xl overflow-hidden">
                        <img class="group-hover:scale-105 group-focus:scale-105 transition-transform duration-500 ease-in-out rounded-xl w-full object-cover" 
                             src="https://images.unsplash.com/photo-1598929438701-ef29ab0bb61a?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=560&q=80" alt="Demo 4">
                    </div>
                    <div class="absolute bottom-0 start-0 end-0 p-2 sm:p-4">
                        <div class="text-sm font-semibold bg-layer text-layer-foreground rounded-lg p-4 md:text-xl">
                            Gestoría 360
                        </div>
                    </div>
                </a>
            </div>

            {{-- Card 5 --}}
            <div class="col-span-12 sm:col-span-6 md:col-span-4">
                <a class="group relative block rounded-xl overflow-hidden focus:outline-none" href="#">
                    <div class="rounded-xl overflow-hidden">
                        <img class="group-hover:scale-105 group-focus:scale-105 transition-transform duration-500 ease-in-out rounded-xl w-full object-cover" 
                             src="https://images.unsplash.com/photo-1467043153537-a4fba2cd39ef?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=560&q=80" alt="Demo 5">
                    </div>
                    <div class="absolute bottom-0 start-0 end-0 p-2 sm:p-4">
                        <div class="text-sm font-semibold bg-layer text-layer-foreground rounded-lg p-4 md:text-xl">
                            FitZone Pro
                        </div>
                    </div>
                </a>
            </div>

            {{-- Card 6 --}}
            <div class="col-span-12 sm:col-span-6">
                <a class="group relative block rounded-xl overflow-hidden focus:outline-none" href="#">
                    <div class="rounded-xl overflow-hidden">
                        <img class="group-hover:scale-105 group-focus:scale-105 transition-transform duration-500 ease-in-out rounded-xl w-full object-cover" 
                             src="https://images.unsplash.com/photo-1514306688699-36149c7cbbe2?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=560&q=80" alt="Demo 6">
                    </div>
                    <div class="absolute bottom-0 start-0 end-0 p-2 sm:p-4">
                        <div class="text-sm font-semibold bg-layer text-layer-foreground rounded-lg p-4 md:text-xl">
                            Urban Menu
                        </div>
                    </div>
                </a>
            </div>

            {{-- Card 7 --}}
            <div class="col-span-12 sm:col-span-6">
                <a class="group relative block rounded-xl overflow-hidden focus:outline-none" href="#">
                    <div class="rounded-xl overflow-hidden">
                        <img class="group-hover:scale-105 group-focus:scale-105 transition-transform duration-500 ease-in-out rounded-xl w-full object-cover" 
                             src="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=560&q=80" alt="Demo 7">
                    </div>
                    <div class="absolute bottom-0 start-0 end-0 p-2 sm:p-4">
                        <div class="text-sm font-semibold bg-layer text-layer-foreground rounded-lg p-4 md:text-xl">
                            Nova Store
                        </div>
                    </div>
                </a>
            </div>

        </div>

    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════ --}}
{{-- 3. CTA FINAL                                                  --}}
{{-- ══════════════════════════════════════════════════════════════ --}}
<section class="py-20 sm:py-28"
         style="background:linear-gradient(135deg,#0f172a 0%,#1e1b4b 45%,#1e3a5f 100%)">
    <div class="max-w-3xl px-4 sm:px-6 lg:px-8 mx-auto text-center">

        <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
            ¿Ya sabes cuál es el tuyo?
        </h2>
        <p class="mt-4 text-lg text-slate-300 max-w-xl mx-auto leading-relaxed">
            Crea tu sitio en 5 minutos. Sin tarjeta de crédito. Sin conocimientos técnicos.
        </p>

        <div class="mt-8 flex flex-wrap justify-center gap-4">
            <a href="{{ route('register') }}"
               class="inline-flex items-center gap-2 px-8 py-4 rounded-full text-base font-bold text-white transition-all duration-200"
               style="background:#4A80E4;box-shadow:0 4px 24px rgba(74,128,228,0.45)">
                <iconify-icon icon="tabler:rocket" width="18"></iconify-icon>
                Empezar gratis ahora
            </a>
            <a href="{{ route('marketing.planes') }}"
               class="inline-flex items-center gap-2 px-8 py-4 rounded-full text-base font-semibold text-white border border-white/25 hover:bg-white/10 transition-all duration-200">
                Ver planes y precios →
            </a>
        </div>

    </div>
</section>

@endsection

{{-- ══════════════════════════════════════════════════════════════ --}}
{{-- Arc Gallery JS — pure vanilla, no dependencies               --}}
{{-- ══════════════════════════════════════════════════════════════ --}}
@push('scripts')
<script>
(function () {
    'use strict';

    var COUNT       = {{ count($demos) }};
    var START_ANGLE = 20;
    var END_ANGLE   = 160;

    /**
     * Returns radius + cardSize config for current viewport width.
     * Matches the React component's responsive breakpoints verbatim.
     */
    function getConfig() {
        var w = window.innerWidth;
        if (w < 640)  return { radius: 260, cardSize: 80  };
        if (w < 1024) return { radius: 360, cardSize: 100 };
        return                { radius: 480, cardSize: 120 };
    }

    /**
     * Positions each arc card using trigonometry:
     *   x = cos(angle) * radius   → horizontal offset from center
     *   y = sin(angle) * radius   → vertical offset from bottom
     * Cards are absolutely positioned relative to #sw-arc-container.
     */
    function layoutArc() {
        var cfg       = getConfig();
        var radius    = cfg.radius;
        var cardSize  = cfg.cardSize;
        var container = document.getElementById('sw-arc-container');
        if (!container) return;

        var cw   = container.offsetWidth;
        var step = (END_ANGLE - START_ANGLE) / Math.max(COUNT - 1, 1);

        container.style.height = Math.round(radius * 1.2) + 'px';

        Array.prototype.forEach.call(
            container.querySelectorAll('[data-arc-index]'),
            function (card) {
                var i     = parseInt(card.dataset.arcIndex, 10);
                var angle = START_ANGLE + step * i;
                var rad   = angle * Math.PI / 180;
                var x     = Math.cos(rad) * radius;
                var y     = Math.sin(rad) * radius;

                card.style.width  = cardSize + 'px';
                card.style.height = cardSize + 'px';
                card.style.left   = Math.round(cw / 2 + x - cardSize / 2) + 'px';
                card.style.bottom = Math.round(y  - cardSize / 2) + 'px';
                card.style.zIndex = COUNT - i;

                /* Subtle tilt: each card rotates proportionally to its arc angle.
                   Applied via CSS custom property so hover:scale can compound it. */
                var inner = card.querySelector('.sw-arc-inner');
                if (inner) {
                    inner.style.setProperty('--arc-rot', (angle / 4).toFixed(1) + 'deg');
                }
            }
        );
    }

    document.addEventListener('DOMContentLoaded', layoutArc);
    window.addEventListener('resize', layoutArc);
}());
</script>
@endpush
