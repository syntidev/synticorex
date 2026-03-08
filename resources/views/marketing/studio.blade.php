<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SYNTIstudio — Tu negocio en internet esta semana</title>
    <meta name="description" content="Landing profesional para marcas, servicios y negocios locales. Desde $99/año. 15 días gratis.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Geist', ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>
<body class="bg-surface text-foreground antialiased">

{{-- ═══════════════════════════════════════════════════ --}}
{{-- HEADER                                             --}}
{{-- ═══════════════════════════════════════════════════ --}}
<header class="flex flex-wrap md:justify-start md:flex-nowrap z-50 w-full text-sm py-3 md:py-0">
    <nav class="max-w-[85rem] w-full mx-auto px-4 md:px-6 lg:px-8">
        <div class="relative md:flex md:items-center md:justify-between">
            <div class="flex items-center justify-between">
                <a class="flex items-center gap-2 focus:outline-hidden focus:opacity-80" href="{{ url('/') }}">
                    <img src="{{ asset('brand/syntiweb-logo-positive.svg') }}" width="32" height="32" alt="SYNTIweb">
                    <span class="text-lg font-bold tracking-tight">
                        <span class="text-foreground">SYNTI</span><span style="color:#4A80E4">studio</span>
                    </span>
                </a>
            </div>
            <div class="flex items-center gap-x-4 mt-3 md:mt-0">
                <a class="py-3 md:py-6 font-medium text-navbar-nav-foreground hover:text-muted-foreground-1 focus:outline-hidden focus:text-muted-foreground-1" href="{{ url('/planes') }}">
                    Planes
                </a>
                <a class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-primary border border-primary-line text-primary-foreground hover:bg-primary-hover focus:outline-hidden focus:bg-primary-focus disabled:opacity-50 disabled:pointer-events-none" href="{{ route('onboarding.studio') }}">
                    Empezar gratis
                </a>
            </div>
        </div>
    </nav>
</header>

{{-- ═══════════════════════════════════════════════════ --}}
{{-- 1. HERO                                            --}}
{{-- ═══════════════════════════════════════════════════ --}}
<div class="relative">
    <div class="max-w-[85rem] px-4 pt-10 sm:px-6 lg:px-8 lg:pt-20 mx-auto">
        <div class="max-w-2xl mx-auto text-center mb-10">
            <h1 class="text-3xl leading-tight font-bold md:text-4xl md:leading-tight lg:text-5xl lg:leading-tight text-foreground">
                Tu negocio en internet.<br>Esta semana.
            </h1>
            <p class="mt-4 lg:text-lg text-foreground">
                Landing profesional para marcas, servicios y negocios locales.<br class="hidden sm:block">
                Tú nos dices qué vendes. Nosotros hacemos el resto.
            </p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a class="py-3 px-5 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-primary border border-primary-line text-primary-foreground hover:bg-primary-hover focus:outline-hidden focus:bg-primary-focus disabled:opacity-50 disabled:pointer-events-none" href="{{ route('onboarding.studio') }}">
                    Empezar gratis 15 días
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                </a>
                <a class="py-3 px-5 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-layer border border-layer-line text-layer-foreground shadow-2xs hover:bg-layer-hover disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-layer-focus" href="/demo">
                    Ver demo
                </a>
            </div>
        </div>
    </div>

    {{-- Decorative circles --}}
    <div class="absolute top-1/2 start-1/2 -z-1 transform -translate-y-1/2 -translate-x-1/2 w-85 h-85 border border-dashed border-primary-200 rounded-full dark:border-primary-900/60"></div>
    <div class="absolute top-1/2 start-1/2 -z-1 transform -translate-y-1/2 -translate-x-1/2 w-[575px] h-[575px] border border-dashed border-primary-200 rounded-full opacity-80 dark:border-primary-900/60"></div>
    <div class="absolute top-1/2 start-1/2 -z-1 transform -translate-y-1/2 -translate-x-1/2 w-[840px] h-[840px] border border-dashed border-primary-200 rounded-full opacity-60 dark:border-primary-900/60"></div>
</div>

{{-- ═══════════════════════════════════════════════════ --}}
{{-- 2. CARDS DE PLANES                                 --}}
{{-- ═══════════════════════════════════════════════════ --}}
<div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <div class="max-w-2xl mx-auto text-center mb-10">
        <h2 class="text-2xl font-bold md:text-3xl md:leading-tight text-foreground">El plan perfecto para tu negocio</h2>
        <p class="mt-2 lg:text-lg text-foreground">Precios anuales. Sin sorpresas.</p>
    </div>

    <div class="mt-6 md:mt-12 grid sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:items-center max-w-5xl mx-auto">

        {{-- Card: OPORTUNIDAD --}}
        <div class="flex flex-col bg-card border border-card-line text-center rounded-xl p-8">
            <h4 class="font-medium text-lg text-foreground">OPORTUNIDAD</h4>
            <span class="mt-5 font-bold text-5xl text-foreground">$99</span>
            <p class="mt-2 text-sm text-muted-foreground-1">por año &middot; $8.25/mes equiv</p>

            <ul class="mt-7 space-y-2.5 text-sm">
                <li class="flex gap-x-2">
                    <svg class="shrink-0 mt-0.5 size-4 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    <span class="text-foreground">6 productos &middot; 3 servicios</span>
                </li>
                <li class="flex gap-x-2">
                    <svg class="shrink-0 mt-0.5 size-4 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    <span class="text-foreground">10 temas visuales</span>
                </li>
                <li class="flex gap-x-2">
                    <svg class="shrink-0 mt-0.5 size-4 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    <span class="text-foreground">WhatsApp integrado</span>
                </li>
                <li class="flex gap-x-2">
                    <svg class="shrink-0 mt-0.5 size-4 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    <span class="text-foreground">Analytics básico</span>
                </li>
            </ul>

            <a class="mt-5 py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg bg-layer border border-layer-line text-layer-foreground shadow-2xs hover:bg-layer-hover disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-layer-focus" href="{{ route('onboarding.studio') }}">
                Empezar gratis
            </a>
        </div>

        {{-- Card: CRECIMIENTO (Destacado) --}}
        <div class="flex flex-col bg-card border-2 border-primary text-center shadow-xl rounded-xl p-8">
            <p class="mb-3"><span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-lg text-xs uppercase font-semibold bg-primary-100 text-primary-800 dark:bg-primary-500/20 dark:text-primary-200">Más popular</span></p>
            <h4 class="font-medium text-lg text-foreground">CRECIMIENTO</h4>
            <span class="mt-5 font-bold text-5xl text-foreground">$149</span>
            <p class="mt-2 text-sm text-muted-foreground-1">por año &middot; $12.40/mes equiv</p>

            <ul class="mt-7 space-y-2.5 text-sm">
                <li class="flex gap-x-2">
                    <svg class="shrink-0 mt-0.5 size-4 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    <span class="text-foreground">12 productos &middot; 6 servicios</span>
                </li>
                <li class="flex gap-x-2">
                    <svg class="shrink-0 mt-0.5 size-4 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    <span class="text-foreground">17 temas visuales</span>
                </li>
                <li class="flex gap-x-2">
                    <svg class="shrink-0 mt-0.5 size-4 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    <span class="text-foreground">Analytics en tiempo real</span>
                </li>
                <li class="flex gap-x-2">
                    <svg class="shrink-0 mt-0.5 size-4 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    <span class="text-foreground">Soporte WhatsApp Lun-Vie</span>
                </li>
            </ul>

            <a class="mt-5 py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg bg-primary border border-primary-line text-primary-foreground hover:bg-primary-hover focus:outline-hidden focus:bg-primary-focus disabled:opacity-50 disabled:pointer-events-none" href="{{ route('onboarding.studio') }}">
                Crecer ahora
            </a>
        </div>

        {{-- Card: VISIÓN --}}
        <div class="flex flex-col bg-card border border-card-line text-center rounded-xl p-8">
            <h4 class="font-medium text-lg text-foreground">VISIÓN</h4>
            <span class="mt-5 font-bold text-5xl text-foreground">$199</span>
            <p class="mt-2 text-sm text-muted-foreground-1">por año &middot; $16.60/mes equiv</p>

            <ul class="mt-7 space-y-2.5 text-sm">
                <li class="flex gap-x-2">
                    <svg class="shrink-0 mt-0.5 size-4 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    <span class="text-foreground">18 productos &middot; 9 servicios</span>
                </li>
                <li class="flex gap-x-2">
                    <svg class="shrink-0 mt-0.5 size-4 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    <span class="text-foreground">17 temas + colores custom</span>
                </li>
                <li class="flex gap-x-2">
                    <svg class="shrink-0 mt-0.5 size-4 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    <span class="text-foreground">Sucursales + galería</span>
                </li>
                <li class="flex gap-x-2">
                    <svg class="shrink-0 mt-0.5 size-4 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    <span class="text-foreground">Soporte prioritario 24h</span>
                </li>
            </ul>

            <a class="mt-5 py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg bg-layer border border-layer-line text-layer-foreground shadow-2xs hover:bg-layer-hover disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-layer-focus" href="{{ route('onboarding.studio') }}">
                Dominar mi zona
            </a>
        </div>

    </div>
</div>

{{-- ═══════════════════════════════════════════════════ --}}
{{-- 3. TABLA COMPARATIVA                               --}}
{{-- ═══════════════════════════════════════════════════ --}}
<div class="relative">
    <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 md:py-14 lg:py-20 mx-auto">
        <div class="max-w-2xl mx-auto text-center mb-10 lg:mb-14">
            <h2 class="text-2xl font-bold md:text-3xl md:leading-tight text-foreground">Compara los planes</h2>
        </div>

        {{-- Sticky header (desktop) --}}
        <div class="hidden lg:block sticky top-0 start-0 py-2 bg-layer/60 backdrop-blur-md z-10">
            <div class="grid grid-cols-4 gap-6">
                <div>
                    <span class="font-semibold text-lg text-foreground">Características</span>
                </div>
                <div>
                    <span class="font-semibold text-lg text-foreground">OPORTUNIDAD</span>
                    <p class="mt-2 text-sm text-muted-foreground-1">$99 por año</p>
                </div>
                <div>
                    <span class="font-semibold text-lg text-foreground">CRECIMIENTO</span>
                    <p class="mt-2 text-sm text-muted-foreground-1">$149 por año</p>
                </div>
                <div>
                    <span class="font-semibold text-lg text-foreground">VISIÓN</span>
                    <p class="mt-2 text-sm text-muted-foreground-1">$199 por año</p>
                </div>
            </div>
        </div>

        {{-- SECCIÓN: Tu catálogo --}}
        <div class="space-y-4 lg:space-y-0">
            <ul class="grid lg:grid-cols-4 lg:gap-6">
                <li class="lg:py-3">
                    <span class="text-lg font-semibold text-foreground">Tu catálogo</span>
                </li>
                <li class="hidden lg:block py-1.5 lg:py-3"></li>
                <li class="hidden lg:block py-1.5 lg:py-3"></li>
                <li class="hidden lg:block py-1.5 lg:py-3"></li>
            </ul>

            {{-- Productos en catálogo --}}
            <ul class="grid lg:grid-cols-4 lg:gap-6">
                <li class="pb-1.5 lg:py-3">
                    <span class="font-semibold lg:font-normal text-sm text-foreground">Productos en catálogo</span>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">OPORTUNIDAD</span>
                        <span class="text-sm text-foreground">6</span>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">CRECIMIENTO</span>
                        <span class="text-sm text-foreground">12</span>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">VISIÓN</span>
                        <span class="text-sm text-foreground">18</span>
                    </div>
                </li>
            </ul>

            {{-- Servicios --}}
            <ul class="grid lg:grid-cols-4 lg:gap-6">
                <li class="pb-1.5 lg:py-3">
                    <span class="font-semibold lg:font-normal text-sm text-foreground">Servicios</span>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">OPORTUNIDAD</span>
                        <span class="text-sm text-foreground">3</span>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">CRECIMIENTO</span>
                        <span class="text-sm text-foreground">6</span>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">VISIÓN</span>
                        <span class="text-sm text-foreground">9</span>
                    </div>
                </li>
            </ul>

            {{-- Galería 3 fotos por producto --}}
            <ul class="grid lg:grid-cols-4 lg:gap-6">
                <li class="pb-1.5 lg:py-3">
                    <span class="font-semibold lg:font-normal text-sm text-foreground">Galería 3 fotos por producto</span>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">OPORTUNIDAD</span>
                        <svg class="shrink-0 size-5 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">CRECIMIENTO</span>
                        <svg class="shrink-0 size-5 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">VISIÓN</span>
                        <svg class="shrink-0 size-5 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                </li>
            </ul>

            {{-- Temas visuales --}}
            <ul class="grid lg:grid-cols-4 lg:gap-6">
                <li class="pb-1.5 lg:py-3">
                    <span class="font-semibold lg:font-normal text-sm text-foreground">Temas visuales</span>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">OPORTUNIDAD</span>
                        <span class="text-sm text-foreground">10</span>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">CRECIMIENTO</span>
                        <span class="text-sm text-foreground">17</span>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">VISIÓN</span>
                        <span class="text-sm text-foreground">17 + custom</span>
                    </div>
                </li>
            </ul>
        </div>

        {{-- SECCIÓN: Presencia digital --}}
        <div class="mt-6 space-y-4 lg:space-y-0">
            <ul class="grid lg:grid-cols-4 lg:gap-6">
                <li class="lg:py-3">
                    <span class="text-lg font-semibold text-foreground">Presencia digital</span>
                </li>
                <li class="hidden lg:block py-1.5 lg:py-3"></li>
                <li class="hidden lg:block py-1.5 lg:py-3"></li>
                <li class="hidden lg:block py-1.5 lg:py-3"></li>
            </ul>

            {{-- WhatsApp integrado --}}
            <ul class="grid lg:grid-cols-4 lg:gap-6">
                <li class="pb-1.5 lg:py-3">
                    <span class="font-semibold lg:font-normal text-sm text-foreground">WhatsApp integrado</span>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">OPORTUNIDAD</span>
                        <span class="text-sm text-foreground">1</span>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">CRECIMIENTO</span>
                        <span class="text-sm text-foreground">2</span>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">VISIÓN</span>
                        <span class="text-sm text-foreground">2</span>
                    </div>
                </li>
            </ul>

            {{-- Redes sociales --}}
            <ul class="grid lg:grid-cols-4 lg:gap-6">
                <li class="pb-1.5 lg:py-3">
                    <span class="font-semibold lg:font-normal text-sm text-foreground">Redes sociales</span>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">OPORTUNIDAD</span>
                        <span class="text-sm text-foreground">2</span>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">CRECIMIENTO</span>
                        <span class="text-sm text-foreground">Todas</span>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">VISIÓN</span>
                        <span class="text-sm text-foreground">Todas</span>
                    </div>
                </li>
            </ul>

            {{-- Analytics --}}
            <ul class="grid lg:grid-cols-4 lg:gap-6">
                <li class="pb-1.5 lg:py-3">
                    <span class="font-semibold lg:font-normal text-sm text-foreground">Analytics</span>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">OPORTUNIDAD</span>
                        <span class="text-sm text-foreground">Básico</span>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">CRECIMIENTO</span>
                        <span class="text-sm text-foreground">Tiempo real</span>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">VISIÓN</span>
                        <span class="text-sm text-foreground">Avanzado</span>
                    </div>
                </li>
            </ul>

            {{-- Sucursales --}}
            <ul class="grid lg:grid-cols-4 lg:gap-6">
                <li class="pb-1.5 lg:py-3">
                    <span class="font-semibold lg:font-normal text-sm text-foreground">Sucursales</span>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">OPORTUNIDAD</span>
                        <svg class="shrink-0 size-5 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">CRECIMIENTO</span>
                        <svg class="shrink-0 size-5 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">VISIÓN</span>
                        <svg class="shrink-0 size-5 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                </li>
            </ul>
        </div>

        {{-- SECCIÓN: Soporte incluido --}}
        <div class="mt-6 space-y-4 lg:space-y-0">
            <ul class="grid lg:grid-cols-4 lg:gap-6">
                <li class="lg:py-3">
                    <span class="text-lg font-semibold text-foreground">Soporte incluido</span>
                </li>
                <li class="hidden lg:block py-1.5 lg:py-3"></li>
                <li class="hidden lg:block py-1.5 lg:py-3"></li>
                <li class="hidden lg:block py-1.5 lg:py-3"></li>
            </ul>

            {{-- Documentación y guías --}}
            <ul class="grid lg:grid-cols-4 lg:gap-6">
                <li class="pb-1.5 lg:py-3">
                    <span class="font-semibold lg:font-normal text-sm text-foreground">Documentación y guías</span>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">OPORTUNIDAD</span>
                        <svg class="shrink-0 size-5 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">CRECIMIENTO</span>
                        <svg class="shrink-0 size-5 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">VISIÓN</span>
                        <svg class="shrink-0 size-5 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                </li>
            </ul>

            {{-- Ticket por formulario --}}
            <ul class="grid lg:grid-cols-4 lg:gap-6">
                <li class="pb-1.5 lg:py-3">
                    <span class="font-semibold lg:font-normal text-sm text-foreground">Ticket por formulario</span>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">OPORTUNIDAD</span>
                        <svg class="shrink-0 size-5 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">CRECIMIENTO</span>
                        <svg class="shrink-0 size-5 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">VISIÓN</span>
                        <svg class="shrink-0 size-5 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                </li>
            </ul>

            {{-- WhatsApp Lun-Vie 9am-6pm --}}
            <ul class="grid lg:grid-cols-4 lg:gap-6">
                <li class="pb-1.5 lg:py-3">
                    <span class="font-semibold lg:font-normal text-sm text-foreground">WhatsApp Lun-Vie 9am-6pm</span>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">OPORTUNIDAD</span>
                        <svg class="shrink-0 size-5 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">CRECIMIENTO</span>
                        <svg class="shrink-0 size-5 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">VISIÓN</span>
                        <svg class="shrink-0 size-5 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                </li>
            </ul>

            {{-- WhatsApp prioritario 24h --}}
            <ul class="grid lg:grid-cols-4 lg:gap-6">
                <li class="pb-1.5 lg:py-3">
                    <span class="font-semibold lg:font-normal text-sm text-foreground">WhatsApp prioritario 24h</span>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">OPORTUNIDAD</span>
                        <svg class="shrink-0 size-5 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">CRECIMIENTO</span>
                        <svg class="shrink-0 size-5 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">VISIÓN</span>
                        <svg class="shrink-0 size-5 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                </li>
            </ul>
        </div>

        {{-- CTA row --}}
        <div class="hidden lg:block mt-6">
            <div class="grid grid-cols-4 gap-6">
                <div></div>
                <div>
                    <a class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg bg-layer border border-layer-line text-layer-foreground shadow-2xs hover:bg-layer-hover disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-layer-focus" href="{{ route('onboarding.studio') }}">
                        Empezar gratis
                    </a>
                </div>
                <div>
                    <a class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg bg-primary border border-primary-line text-primary-foreground hover:bg-primary-hover focus:outline-hidden focus:bg-primary-focus disabled:opacity-50 disabled:pointer-events-none" href="{{ route('onboarding.studio') }}">
                        Crecer ahora
                    </a>
                </div>
                <div>
                    <a class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg bg-layer border border-layer-line text-layer-foreground shadow-2xs hover:bg-layer-hover disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-layer-focus" href="{{ route('onboarding.studio') }}">
                        Dominar mi zona
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════ --}}
{{-- 4. FAQ                                             --}}
{{-- ═══════════════════════════════════════════════════ --}}
<div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <div class="grid md:grid-cols-5 gap-10">
        <div class="md:col-span-2">
            <div class="max-w-xs">
                <h2 class="text-2xl font-bold md:text-4xl md:leading-tight text-foreground">Preguntas<br>frecuentes</h2>
                <p class="mt-1 hidden md:block text-muted-foreground-2">Respuestas a las dudas más comunes sobre SYNTIstudio.</p>
            </div>
        </div>

        <div class="md:col-span-3">
            <div class="hs-accordion-group divide-y divide-line-2">

                {{-- Q1 --}}
                <div class="hs-accordion pb-3 active" id="hs-studio-faq-one">
                    <button class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full md:text-lg font-semibold text-start text-foreground rounded-lg transition hover:text-muted-foreground-1 focus:outline-hidden" aria-expanded="true" aria-controls="hs-studio-faq-collapse-one">
                        ¿Cuánto tiempo tarda en estar lista mi página?
                        <svg class="hs-accordion-active:hidden block shrink-0 size-5 text-muted-foreground-2 group-hover:text-muted-foreground-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        <svg class="hs-accordion-active:block hidden shrink-0 size-5 text-muted-foreground-2 group-hover:text-muted-foreground-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
                    </button>
                    <div id="hs-studio-faq-collapse-one" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300" role="region" aria-labelledby="hs-studio-faq-one">
                        <p class="text-muted-foreground-2">
                            En 48 horas tu negocio ya está visible en internet.
                        </p>
                    </div>
                </div>

                {{-- Q2 --}}
                <div class="hs-accordion pt-6 pb-3" id="hs-studio-faq-two">
                    <button class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full md:text-lg font-semibold text-start text-foreground rounded-lg transition hover:text-muted-foreground-1 focus:outline-hidden" aria-expanded="false" aria-controls="hs-studio-faq-collapse-two">
                        ¿Necesito saber de tecnología?
                        <svg class="hs-accordion-active:hidden block shrink-0 size-5 text-muted-foreground-2 group-hover:text-muted-foreground-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        <svg class="hs-accordion-active:block hidden shrink-0 size-5 text-muted-foreground-2 group-hover:text-muted-foreground-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
                    </button>
                    <div id="hs-studio-faq-collapse-two" class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300" role="region" aria-labelledby="hs-studio-faq-two">
                        <p class="text-muted-foreground-2">
                            No. Tú nos envías la información de tu negocio y nosotros hacemos todo el trabajo técnico.
                        </p>
                    </div>
                </div>

                {{-- Q3 --}}
                <div class="hs-accordion pt-6 pb-3" id="hs-studio-faq-three">
                    <button class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full md:text-lg font-semibold text-start text-foreground rounded-lg transition hover:text-muted-foreground-1 focus:outline-hidden" aria-expanded="false" aria-controls="hs-studio-faq-collapse-three">
                        ¿Qué pasa si quiero cambiar de plan?
                        <svg class="hs-accordion-active:hidden block shrink-0 size-5 text-muted-foreground-2 group-hover:text-muted-foreground-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        <svg class="hs-accordion-active:block hidden shrink-0 size-5 text-muted-foreground-2 group-hover:text-muted-foreground-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
                    </button>
                    <div id="hs-studio-faq-collapse-three" class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300" role="region" aria-labelledby="hs-studio-faq-three">
                        <p class="text-muted-foreground-2">
                            Puedes subir de plan en cualquier momento. Solo pagas la diferencia proporcional.
                        </p>
                    </div>
                </div>

                {{-- Q4 --}}
                <div class="hs-accordion pt-6 pb-3" id="hs-studio-faq-four">
                    <button class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full md:text-lg font-semibold text-start text-foreground rounded-lg transition hover:text-muted-foreground-1 focus:outline-hidden" aria-expanded="false" aria-controls="hs-studio-faq-collapse-four">
                        ¿El precio incluye el dominio?
                        <svg class="hs-accordion-active:hidden block shrink-0 size-5 text-muted-foreground-2 group-hover:text-muted-foreground-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        <svg class="hs-accordion-active:block hidden shrink-0 size-5 text-muted-foreground-2 group-hover:text-muted-foreground-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
                    </button>
                    <div id="hs-studio-faq-collapse-four" class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300" role="region" aria-labelledby="hs-studio-faq-four">
                        <p class="text-muted-foreground-2">
                            Incluye tu subdominio en punto.vip u oficio.vip. Dominio .com propio disponible como add-on (+$18/año).
                        </p>
                    </div>
                </div>

                {{-- Q5 --}}
                <div class="hs-accordion pt-6 pb-3" id="hs-studio-faq-five">
                    <button class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full md:text-lg font-semibold text-start text-foreground rounded-lg transition hover:text-muted-foreground-1 focus:outline-hidden" aria-expanded="false" aria-controls="hs-studio-faq-collapse-five">
                        ¿Hay contrato o permanencia?
                        <svg class="hs-accordion-active:hidden block shrink-0 size-5 text-muted-foreground-2 group-hover:text-muted-foreground-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        <svg class="hs-accordion-active:block hidden shrink-0 size-5 text-muted-foreground-2 group-hover:text-muted-foreground-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
                    </button>
                    <div id="hs-studio-faq-collapse-five" class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300" role="region" aria-labelledby="hs-studio-faq-five">
                        <p class="text-muted-foreground-2">
                            No. El pago es anual pero no hay penalización. El trial de 15 días es sin compromiso.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════ --}}
{{-- 5. CTA FINAL                                      --}}
{{-- ═══════════════════════════════════════════════════ --}}
<div class="bg-neutral-900">
    <div class="max-w-[85rem] px-4 py-16 sm:px-6 lg:px-8 lg:py-24 mx-auto text-center">
        <h2 class="text-2xl font-bold md:text-3xl md:leading-tight text-white">
            Tu negocio está esperando. Empieza hoy.
        </h2>
        <p class="mt-3 text-neutral-400">
            15 días gratis. Sin tarjeta. Sin tecnicismos.
        </p>
        <div class="mt-8">
            <a class="py-3 px-6 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-primary border border-primary-line text-primary-foreground hover:bg-primary-hover focus:outline-hidden focus:bg-primary-focus disabled:opacity-50 disabled:pointer-events-none" href="{{ route('onboarding.studio') }}">
                Crear mi página ahora
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════ --}}
{{-- FOOTER                                             --}}
{{-- ═══════════════════════════════════════════════════ --}}
<footer class="mt-auto w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <a class="flex items-center gap-2 font-semibold text-xl text-foreground focus:outline-hidden focus:opacity-80" href="{{ url('/') }}">
                <img src="{{ asset('brand/syntiweb-logo-positive.svg') }}" width="24" height="24" alt="SYNTIweb">
                SYNTIweb
            </a>
            <p class="mt-1 text-xs text-muted-foreground-2">&copy; {{ date('Y') }} SYNTIweb. Todos los derechos reservados.</p>
        </div>
        <div class="space-x-4 text-sm">
            <a class="inline-flex gap-x-2 text-muted-foreground-2 hover:text-foreground focus:outline-hidden focus:text-foreground" href="{{ url('/planes') }}">Planes</a>
            <a class="inline-flex gap-x-2 text-muted-foreground-2 hover:text-foreground focus:outline-hidden focus:text-foreground" href="/demo">Demo</a>
        </div>
    </div>
</footer>

</body>
</html>
