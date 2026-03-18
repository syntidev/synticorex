@extends('marketing.layout')

@push('seo')
<title>SYNTIcat — Catálogo visual con WhatsApp | SYNTIweb</title>
<meta name="description" content="Catálogo visual de productos con botón WhatsApp. Para tiendas y emprendedores venezolanos.">
<meta property="og:title" content="SYNTIcat — Catálogo visual con WhatsApp | SYNTIweb">
<meta property="og:description" content="Catálogo visual de productos con botón WhatsApp. Para tiendas y emprendedores venezolanos.">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ asset('brand/syntiweb-og.png') }}">
<meta property="og:type" content="website">
@endpush

@section('content')

{{-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• --}}
{{-- 1. HERO                                            --}}
{{-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• --}}
<div class="relative">
    <div class="max-w-[85rem] px-4 pt-10 sm:px-6 lg:px-8 lg:pt-20 mx-auto">
        <div class="max-w-2xl mx-auto text-center mb-10">
            <h1 class="text-3xl leading-tight font-bold md:text-4xl md:leading-tight lg:text-5xl lg:leading-tight text-foreground">
                Tu catálogo en línea.<br>Tus clientes compran por WhatsApp.
            </h1>
            <p class="mt-4 lg:text-lg text-foreground">
                Catálogo visual con carrito para tiendas, proveedores y comercios.<br class="hidden sm:block">
                El cliente elige, arma su pedido y te escribe directo.
            </p>
            <p class="mt-2 text-sm text-muted-foreground-1">
                Catálogo visual con carrito incluido. Mini Order SC-XXXX rastreable. Desde $5.75/mes.
            </p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a class="py-3 px-5 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-primary border border-primary-line text-primary-foreground hover:bg-primary-hover focus:outline-hidden focus:bg-primary-focus disabled:opacity-50 disabled:pointer-events-none" href="{{ route('onboarding.cat') }}">
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
    <div class="absolute top-1/2 start-1/2 -z-1 transform -translate-y-1/2 -translate-x-1/2 w-[575px] h-[575px] border border-dashed border-primary-200 rounded-full opacity-80 dark:border-primary-900/60 hidden sm:block"></div>
    <div class="absolute top-1/2 start-1/2 -z-1 transform -translate-y-1/2 -translate-x-1/2 w-[840px] h-[840px] border border-dashed border-primary-200 rounded-full opacity-60 dark:border-primary-900/60 hidden sm:block"></div>
</div>

{{-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• --}}
{{-- 2. CARDS DE PLANES                                 --}}
{{-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• --}}
<style>
    .plan-card { transition: transform .2s ease, box-shadow .2s ease; }
    .plan-card:hover { transform: translateY(-4px); }
    .plan-card--highlight { transform: translateY(-8px); position: relative; z-index: 2; }
    .plan-card--highlight:hover { transform: translateY(-12px); }
    .ring-cat  { box-shadow: 0 0 0 2px #10b981, 0 20px 60px color-mix(in oklch, #10b981 20%, transparent); }
    .badge-cat { background: #10b981; }
    .check-cat { color: #10b981; }
    .btn-primary-cat { background: #10b981; color: #fff; box-shadow: 0 4px 16px color-mix(in oklch, #10b981 40%, transparent); }
    .btn-ghost-cat   { background: color-mix(in oklch, #10b981 10%, transparent); color: #10b981; border: 1.5px solid color-mix(in oklch, #10b981 30%, transparent); }
</style>

<div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <div class="max-w-2xl mx-auto text-center mb-10">
        <h2 class="text-2xl font-bold md:text-3xl md:leading-tight text-foreground">El plan perfecto para tu catálogo</h2>
        <p class="mt-2 lg:text-lg text-foreground">Catálogo visual con carrito incluido. Tus clientes escogen, acumulan y te compran por WhatsApp.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-6 items-end max-w-5xl mx-auto">

        @foreach($planData['plans'] as $plan)
        @php $isHighlighted = $plan['highlighted'] ?? false; @endphp
        <div class="plan-card bg-white rounded-2xl p-6 lg:p-8 flex flex-col
            {{ $isHighlighted ? 'plan-card--highlight ring-cat shadow-2xl' : 'border border-slate-200 shadow-sm' }}">

            @if(!empty($plan['pill']))
            <div class="text-center mb-4">
                <span class="inline-flex items-center gap-1 {{ $isHighlighted ? 'badge-cat text-white' : 'bg-slate-100 text-slate-500' }} text-xs font-bold px-3 py-1 rounded-full">
                    {{ $plan['pill'] }}
                </span>
            </div>
            @endif

            <h4 class="font-bold uppercase tracking-widest text-xs text-slate-400 mb-1">{{ $plan['name'] }}</h4>
            <div class="flex items-baseline gap-1 mb-4">
                <span class="text-5xl font-extrabold check-cat">${{ $plan['price'] }}</span>
                <span class="text-slate-400 text-sm">{{ $plan['billing'] }}</span>
            </div>

            <ul class="space-y-2.5 flex-1 mb-8">
                @foreach($plan['features'] as $feature)
                @php $val = $feature['p' . ($loop->parent->index + 1)]; @endphp
                @if($val !== false)
                <li class="flex items-start gap-2 text-sm text-slate-700">
                    <iconify-icon icon="tabler:check" class="check-cat mt-0.5 shrink-0" width="16"></iconify-icon>
                    <span>{{ $feature['label'] }}@if($val !== true) &middot; {{ $val }}@endif</span>
                </li>
                @endif
                @endforeach
            </ul>

            <a href="{{ route('onboarding.' . explode('-', $plan['slug'])[0]) }}"
               class="block w-full text-center py-3 px-4 rounded-xl font-bold text-sm transition-all hover:-translate-y-0.5 {{ $isHighlighted ? 'btn-primary-cat' : 'btn-ghost-cat' }}">
                {{ $plan['cta'] }}
            </a>
        </div>
        @endforeach

    </div>
</div>

{{-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• --}}
{{-- 3. TABLA COMPARATIVA                               --}}
{{-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• --}}
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
                    <span class="font-semibold text-lg text-foreground">{{ strtoupper($planData['plans'][0]['name']) }}</span>
                    <p class="mt-2 text-sm text-muted-foreground-1">${{ $planData['plans'][0]['price'] }}{{ $planData['plans'][0]['billing'] }}</p>
                </div>
                <div>
                    <span class="font-semibold text-lg text-foreground">{{ strtoupper($planData['plans'][1]['name']) }}</span>
                    <p class="mt-2 text-sm text-muted-foreground-1">${{ $planData['plans'][1]['price'] }}{{ $planData['plans'][1]['billing'] }}</p>
                </div>
                <div>
                    <span class="font-semibold text-lg text-foreground">{{ strtoupper($planData['plans'][2]['name']) }}</span>
                    <p class="mt-2 text-sm text-muted-foreground-1">${{ $planData['plans'][2]['price'] }}{{ $planData['plans'][2]['billing'] }}</p>
                </div>
            </div>
        </div>

        {{-- SECCIÁ"N: Tu catálogo --}}
        <div class="space-y-4 lg:space-y-0">
            <ul class="grid lg:grid-cols-4 lg:gap-6">
                <li class="lg:py-3">
                    <span class="text-lg font-semibold text-foreground">Tu catálogo</span>
                </li>
                <li class="hidden lg:block py-1.5 lg:py-3"></li>
                <li class="hidden lg:block py-1.5 lg:py-3"></li>
                <li class="hidden lg:block py-1.5 lg:py-3"></li>
            </ul>

            {{-- Productos --}}
            <ul class="grid lg:grid-cols-4 lg:gap-6">
                <li class="pb-1.5 lg:py-3">
                    <span class="font-semibold lg:font-normal text-sm text-foreground">Productos</span>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">BÁSICO</span>
                        <span class="text-sm text-foreground">20</span>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">SEMESTRAL</span>
                        <span class="text-sm text-foreground">100</span>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">ANUAL</span>
                        <span class="text-sm text-foreground">250</span>
                    </div>
                </li>
            </ul>

            {{-- Fotos por producto --}}
            <ul class="grid lg:grid-cols-4 lg:gap-6">
                <li class="pb-1.5 lg:py-3">
                    <span class="font-semibold lg:font-normal text-sm text-foreground">Fotos por producto</span>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">BÁSICO</span>
                        <span class="text-sm text-foreground">1</span>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">SEMESTRAL</span>
                        <span class="text-sm text-foreground">3</span>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">ANUAL</span>
                        <span class="text-sm text-foreground">6</span>
                    </div>
                </li>
            </ul>

            {{-- Variantes (talla/color) --}}
            <ul class="grid lg:grid-cols-4 lg:gap-6">
                <li class="pb-1.5 lg:py-3">
                    <span class="font-semibold lg:font-normal text-sm text-foreground">Variantes (talla/color)</span>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">BÁSICO</span>
                        <svg class="shrink-0 size-5 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">SEMESTRAL</span>
                        <svg class="shrink-0 size-5 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">ANUAL</span>
                        <svg class="shrink-0 size-5 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                </li>
            </ul>

            {{-- Opciones libres (extras) --}}
            <ul class="grid lg:grid-cols-4 lg:gap-6">
                <li class="pb-1.5 lg:py-3">
                    <span class="font-semibold lg:font-normal text-sm text-foreground">Opciones libres (extras)</span>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">BÁSICO</span>
                        <svg class="shrink-0 size-5 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">SEMESTRAL</span>
                        <svg class="shrink-0 size-5 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">ANUAL</span>
                        <svg class="shrink-0 size-5 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                </li>
            </ul>
        </div>

        {{-- SECCIÁ"N: Ventas por WhatsApp --}}
        <div class="mt-6 space-y-4 lg:space-y-0">
            <ul class="grid lg:grid-cols-4 lg:gap-6">
                <li class="lg:py-3">
                    <span class="text-lg font-semibold text-foreground">Ventas por WhatsApp</span>
                </li>
                <li class="hidden lg:block py-1.5 lg:py-3"></li>
                <li class="hidden lg:block py-1.5 lg:py-3"></li>
                <li class="hidden lg:block py-1.5 lg:py-3"></li>
            </ul>

            {{-- Botón WhatsApp directo --}}
            <ul class="grid lg:grid-cols-4 lg:gap-6">
                <li class="pb-1.5 lg:py-3">
                    <span class="font-semibold lg:font-normal text-sm text-foreground">Botón WhatsApp directo</span>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">BÁSICO</span>
                        <svg class="shrink-0 size-5 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">SEMESTRAL</span>
                        <svg class="shrink-0 size-5 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">ANUAL</span>
                        <svg class="shrink-0 size-5 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                </li>
            </ul>

            {{-- Carrito de compras --}}
            <ul class="grid lg:grid-cols-4 lg:gap-6">
                <li class="pb-1.5 lg:py-3">
                    <span class="font-semibold lg:font-normal text-sm text-foreground">Carrito de compras</span>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">BÁSICO</span>
                        <svg class="shrink-0 size-5 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">SEMESTRAL</span>
                        <svg class="shrink-0 size-5 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">ANUAL</span>
                        <svg class="shrink-0 size-5 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                </li>
            </ul>

            {{-- Mini Order SC-XXXX rastreable --}}
            <ul class="grid lg:grid-cols-4 lg:gap-6">
                <li class="pb-1.5 lg:py-3">
                    <span class="font-semibold lg:font-normal text-sm text-foreground">Mini Order SC-XXXX rastreable</span>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">BÁSICO</span>
                        <svg class="shrink-0 size-5 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">SEMESTRAL</span>
                        <svg class="shrink-0 size-5 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">ANUAL</span>
                        <svg class="shrink-0 size-5 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                </li>
            </ul>

            {{-- Analytics de visitas --}}
            <ul class="grid lg:grid-cols-4 lg:gap-6">
                <li class="pb-1.5 lg:py-3">
                    <span class="font-semibold lg:font-normal text-sm text-foreground">Analytics de visitas</span>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">BÁSICO</span>
                        <svg class="shrink-0 size-5 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">SEMESTRAL</span>
                        <span class="text-sm text-foreground">Básico</span>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">ANUAL</span>
                        <span class="text-sm text-foreground">Completo</span>
                    </div>
                </li>
            </ul>
        </div>

        {{-- SECCIÁ"N: Soporte incluido --}}
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
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">BÁSICO</span>
                        <svg class="shrink-0 size-5 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">SEMESTRAL</span>
                        <svg class="shrink-0 size-5 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">ANUAL</span>
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
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">BÁSICO</span>
                        <svg class="shrink-0 size-5 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">SEMESTRAL</span>
                        <svg class="shrink-0 size-5 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">ANUAL</span>
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
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">BÁSICO</span>
                        <svg class="shrink-0 size-5 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">SEMESTRAL</span>
                        <svg class="shrink-0 size-5 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">ANUAL</span>
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
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">BÁSICO</span>
                        <svg class="shrink-0 size-5 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">SEMESTRAL</span>
                        <svg class="shrink-0 size-5 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
                    </div>
                </li>
                <li class="col-span-1 py-1.5 lg:py-3 border-b border-line-2">
                    <div class="grid grid-cols-2 md:grid-cols-6 lg:block">
                        <span class="lg:hidden md:col-span-2 text-sm text-foreground">ANUAL</span>
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
                    <a class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg bg-layer border border-layer-line text-layer-foreground shadow-2xs hover:bg-layer-hover disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-layer-focus" href="{{ route('onboarding.cat') }}">
                        Empezar gratis
                    </a>
                </div>
                <div>
                    <a class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg bg-primary border border-primary-line text-primary-foreground hover:bg-primary-hover focus:outline-hidden focus:bg-primary-focus disabled:opacity-50 disabled:pointer-events-none" href="{{ route('onboarding.cat') }}">
                        Ahorrar con semestral
                    </a>
                </div>
                <div>
                    <a class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg bg-layer border border-layer-line text-layer-foreground shadow-2xs hover:bg-layer-hover disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-layer-focus" href="{{ route('onboarding.cat') }}">
                        Máximo ahorro
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• --}}
{{-- 4. FAQ                                             --}}
{{-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• --}}
<div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <div class="grid md:grid-cols-5 gap-10">
        <div class="md:col-span-2">
            <div class="max-w-xs">
                <h2 class="text-2xl font-bold md:text-4xl md:leading-tight text-foreground">Preguntas<br>frecuentes</h2>
                <p class="mt-1 hidden md:block text-muted-foreground-2">Respuestas a las dudas más comunes sobre SYNTIcat.</p>
            </div>
        </div>

        <div class="md:col-span-3">
            <div class="hs-accordion-group divide-y divide-line-2">

                {{-- Q1 --}}
                <div class="hs-accordion pb-3 active" id="hs-cat-faq-one">
                    <button class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full md:text-lg font-semibold text-start text-foreground rounded-lg transition hover:text-muted-foreground-1 focus:outline-hidden" aria-expanded="true" aria-controls="hs-cat-faq-collapse-one">
                        ¿Qué es el Mini Order SC-XXXX?
                        <svg class="hs-accordion-active:hidden block shrink-0 size-5 text-muted-foreground-2 group-hover:text-muted-foreground-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        <svg class="hs-accordion-active:block hidden shrink-0 size-5 text-muted-foreground-2 group-hover:text-muted-foreground-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
                    </button>
                    <div id="hs-cat-faq-collapse-one" class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300" role="region" aria-labelledby="hs-cat-faq-one">
                        <p class="text-muted-foreground-2">
                            Cuando el cliente termina su pedido, el sistema genera un código único tipo SC-0042. Ese código llega al WhatsApp del negocio junto con el detalle completo. Sin pasarela, sin cobro en línea.
                        </p>
                    </div>
                </div>

                {{-- Q2 --}}
                <div class="hs-accordion pt-6 pb-3" id="hs-cat-faq-two">
                    <button class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full md:text-lg font-semibold text-start text-foreground rounded-lg transition hover:text-muted-foreground-1 focus:outline-hidden" aria-expanded="false" aria-controls="hs-cat-faq-collapse-two">
                        ¿Necesito pasarela de pago?
                        <svg class="hs-accordion-active:hidden block shrink-0 size-5 text-muted-foreground-2 group-hover:text-muted-foreground-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        <svg class="hs-accordion-active:block hidden shrink-0 size-5 text-muted-foreground-2 group-hover:text-muted-foreground-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
                    </button>
                    <div id="hs-cat-faq-collapse-two" class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300" role="region" aria-labelledby="hs-cat-faq-two">
                        <p class="text-muted-foreground-2">
                            No. El pedido va a WhatsApp y tú cobras como siempre: Pago móvil, transferencia, efectivo o Zelle.
                        </p>
                    </div>
                </div>

                {{-- Q3 --}}
                <div class="hs-accordion pt-6 pb-3" id="hs-cat-faq-three">
                    <button class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full md:text-lg font-semibold text-start text-foreground rounded-lg transition hover:text-muted-foreground-1 focus:outline-hidden" aria-expanded="false" aria-controls="hs-cat-faq-collapse-three">
                        ¿Qué son las variantes?
                        <svg class="hs-accordion-active:hidden block shrink-0 size-5 text-muted-foreground-2 group-hover:text-muted-foreground-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        <svg class="hs-accordion-active:block hidden shrink-0 size-5 text-muted-foreground-2 group-hover:text-muted-foreground-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
                    </button>
                    <div id="hs-cat-faq-collapse-three" class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300" role="region" aria-labelledby="hs-cat-faq-three">
                        <p class="text-muted-foreground-2">
                            Si vendes ropa, puedes tener el mismo producto en tallas S/M/L y colores. El cliente elige antes de agregar al carrito.
                        </p>
                    </div>
                </div>

                {{-- Q4 --}}
                <div class="hs-accordion pt-6 pb-3" id="hs-cat-faq-four">
                    <button class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full md:text-lg font-semibold text-start text-foreground rounded-lg transition hover:text-muted-foreground-1 focus:outline-hidden" aria-expanded="false" aria-controls="hs-cat-faq-collapse-four">
                        ¿Cuántas fotos puede tener cada producto?
                        <svg class="hs-accordion-active:hidden block shrink-0 size-5 text-muted-foreground-2 group-hover:text-muted-foreground-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        <svg class="hs-accordion-active:block hidden shrink-0 size-5 text-muted-foreground-2 group-hover:text-muted-foreground-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
                    </button>
                    <div id="hs-cat-faq-collapse-four" class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300" role="region" aria-labelledby="hs-cat-faq-four">
                        <p class="text-muted-foreground-2">
                            Básico: 1 foto. Semestral: 3 fotos. Anual: hasta 6 fotos con visor tipo galería para que el cliente vea el detalle.
                        </p>
                    </div>
                </div>

                {{-- Q5 --}}
                <div class="hs-accordion pt-6 pb-3" id="hs-cat-faq-five">
                    <button class="hs-accordion-toggle group pb-3 inline-flex items-center justify-between gap-x-3 w-full md:text-lg font-semibold text-start text-foreground rounded-lg transition hover:text-muted-foreground-1 focus:outline-hidden" aria-expanded="false" aria-controls="hs-cat-faq-collapse-five">
                        ¿Funciona para proveedores mayoristas?
                        <svg class="hs-accordion-active:hidden block shrink-0 size-5 text-muted-foreground-2 group-hover:text-muted-foreground-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        <svg class="hs-accordion-active:block hidden shrink-0 size-5 text-muted-foreground-2 group-hover:text-muted-foreground-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
                    </button>
                    <div id="hs-cat-faq-collapse-five" class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300" role="region" aria-labelledby="hs-cat-faq-five">
                        <p class="text-muted-foreground-2">
                            Sí. Muchos proveedores venezolanos usan SYNTIcat para mostrar su lista de precios actualizada sin enviar PDFs por WhatsApp cada semana.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• --}}
{{-- 5. CTA FINAL                                      --}}
{{-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• --}}
<div class="bg-neutral-900">
    <div class="max-w-[85rem] px-4 py-16 sm:px-6 lg:px-8 lg:py-24 mx-auto text-center">
        <h2 class="text-2xl font-bold md:text-3xl md:leading-tight text-white">
            Tu tienda en línea. Esta semana.
        </h2>
        <p class="mt-3 text-neutral-400">
            15 días gratis. Sin tarjeta. Sin tecnicismos.
        </p>
        <div class="mt-8">
            <a class="py-3 px-6 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-primary border border-primary-line text-primary-foreground hover:bg-primary-hover focus:outline-hidden focus:bg-primary-focus disabled:opacity-50 disabled:pointer-events-none" href="{{ route('onboarding.cat') }}">
                Crear mi catálogo ahora
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        </div>
    </div>
</div>

@endsection
