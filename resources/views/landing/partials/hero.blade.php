{{-- Path: resources/views/landing/partials/hero.blade.php
     Dispatcher de layouts hero.
     Variant leído de $sConfig['variant'] (pasado desde base.blade.php).
     Jerarquía de color:
       primary  → badge negocio, título accent, CTA principal (WA)
       secondary → CTA secundario (outline), tagline badge
       accent    → disponible para badges de plan/precio
--}}
@php $heroLayout = $sConfig['variant'] ?? 'default'; @endphp

@switch($heroLayout)
    @case('fullscreen')
        @include('landing.partials.hero-fullscreen-v2')
        @break

    @case('split')
        @include('landing.partials.hero-split-v2')
        @break

    @case('gradient')
        @include('landing.partials.hero-gradient-v2')
        @break

    @default
<section id="home" class="relative bg-base-100 pt-32 pb-16 lg:pt-40 lg:pb-20 flex items-center overflow-visible" style="isolation: isolate;">
    <x-ui.decorative-background />

    <div class="max-w-7xl mx-auto px-10 lg:px-16 relative z-10 w-full">
        <div class="flex flex-col lg:flex-row items-center justify-center gap-12 lg:gap-20">

            {{-- COLUMNA TEXTO --}}
            <div class="w-full lg:w-1/2 relative py-10">

                {{-- Geometría decorativa — solo desktop para evitar overflow móvil --}}
                <div class="hidden lg:block" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 600px; height: 600px; pointer-events: none; z-index: 0;">
                    <div style="position: absolute; inset: 0; background-image: linear-gradient(to right, oklch(var(--p) / 0.1) 1px, transparent 1px), linear-gradient(to bottom, oklch(var(--p) / 0.1) 1px, transparent 1px); background-size: 40px 40px; mask-image: radial-gradient(circle, black, transparent 80%); -webkit-mask-image: radial-gradient(circle, black, transparent 80%); transform: perspective(1000px) rotateX(15deg);"></div>
                    <div style="position: absolute; inset: 15%; border: 2px solid oklch(var(--p) / 0.2); border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; transform: rotate(-6deg);"></div>
                </div>

                <div class="relative z-10 space-y-8">

                    {{-- Badge del negocio (primary) --}}
                    <div class="inline-flex items-center gap-2 rounded-full bg-primary/10 px-4 py-1.5 text-[10px] font-bold text-primary uppercase tracking-[0.2em]">
                        <span class="iconify tabler--building-store size-3.5"></span>
                        {{ $tenant->business_name }}
                    </div>

                    {{-- Tagline badge (secondary — Plan 2+) --}}
                    @if($tenant->isAtLeastCrecimiento() && $tenant->tagline)
                        <div>
                            <span class="badge badge-secondary badge-lg">{{ $tenant->tagline }}</span>
                        </div>
                    @endif

                    {{-- Título Principal --}}
                    <h1 class="text-5xl lg:text-7xl font-black text-base-content leading-tight tracking-tighter">
                        @if($tenant->slogan)
                            {!! nl2br(e($tenant->slogan)) !!}
                        @else
                            Bienvenido a <br>
                            <span class="text-primary italic">{{ $tenant->business_name }}</span>
                        @endif
                    </h1>

                    {{-- Descripción --}}
                    <p class="text-lg text-base-content/70 max-w-sm leading-relaxed">
                        {{ Str::limit($customization->about_text ?? $tenant->description ?? 'Calidad y compromiso en cada producto y servicio que ofrecemos.', 180) }}
                    </p>

                    {{-- CTAs --}}
                    <div class="flex flex-wrap items-center gap-4 pt-4">

                        {{-- CTA principal: WhatsApp → btn-primary --}}
                        @if($tenant->whatsapp_sales)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tenant->whatsapp_sales) }}?text={{ urlencode('Hola ' . $tenant->business_name . ', me gustaría obtener más información') }}"
                               target="_blank"
                               class="btn btn-primary btn-lg px-10 rounded-2xl shadow-xl shadow-primary/20">
                                <span class="iconify tabler--brand-whatsapp size-5"></span>
                                Contáctanos
                            </a>
                        @elseif($tenant->phone)
                            <a href="tel:{{ $tenant->phone }}"
                               class="btn btn-primary btn-lg px-10 rounded-2xl shadow-xl shadow-primary/20">
                                <span class="iconify tabler--phone size-5"></span>
                                Llamar Ahora
                            </a>
                        @else
                            <a href="#contacto"
                               class="btn btn-primary btn-lg px-10 rounded-2xl shadow-xl shadow-primary/20">
                                Conocer Más
                                <span class="iconify tabler--arrow-down size-5"></span>
                            </a>
                        @endif

                        {{-- CTA secundario: ver productos → btn-outline btn-secondary --}}
                        <a href="#productos" class="btn btn-outline btn-secondary btn-lg px-8 rounded-2xl">
                            Ver Productos
                            <span class="iconify tabler--arrow-down size-5"></span>
                        </a>

                    </div>
                </div>
            </div>

            {{-- COLUMNA IMAGEN --}}
            <div class="w-full lg:w-1/2 flex justify-center lg:justify-end">
                <div class="relative w-full max-w-[600px] aspect-[4/3] overflow-hidden rounded-[2.5rem] shadow-2xl border-[6px] border-base-100 bg-base-200">
                    @if($customization->getHeroMainUrl())
                        <img src="{{ $customization->getHeroMainUrl() }}"
                             alt="{{ $tenant->business_name }}"
                             class="w-full h-full object-cover">
                    @else
                        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=800"
                             alt="{{ $tenant->business_name }}"
                             class="w-full h-full object-cover">
                    @endif
                </div>

                {{-- Segunda imagen — Plan 2+ (Crecimiento / Visión) --}}
                @if($tenant->isAtLeastCrecimiento() && $customization->getHeroSecondaryUrl())
                    <div class="hidden lg:block absolute -bottom-8 -left-8 w-52 h-52 rounded-3xl overflow-hidden shadow-2xl border-4 border-base-100 z-20">
                        <img src="{{ $customization->getHeroSecondaryUrl() }}"
                             alt="{{ $tenant->business_name }}"
                             class="w-full h-full object-cover">
                    </div>
                @endif

                {{-- Tercera imagen — solo Plan 3 (Visión) --}}
                @if((int)$tenant->plan_id === 3 && $customization->getHeroTertiaryUrl())
                    <div class="hidden lg:block absolute -top-6 -right-6 w-40 h-40 rounded-3xl overflow-hidden shadow-xl border-4 border-base-100 z-20">
                        <img src="{{ $customization->getHeroTertiaryUrl() }}"
                             alt="{{ $tenant->business_name }}"
                             class="w-full h-full object-cover">
                    </div>
                @endif
            </div>

        </div>
    </div>
</section>
    @break
@endswitch