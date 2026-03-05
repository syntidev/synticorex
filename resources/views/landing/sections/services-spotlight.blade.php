{{-- Services Section — Preline 4.1.2 + Tailwind v4
     Variante: spotlight | Icono pequeño + texto, sin imagen de cabecera --}}
@php
    $servicePlanLimit = (int) ($plan->services_limit ?? 3);
    $displayServices  = $services->take($servicePlanLimit);
    $count            = $displayServices->count();
    $gridClass        = match(true) {
        $count === 1 => 'max-w-sm mx-auto',
        $count === 2 => 'grid sm:grid-cols-2 gap-6 max-w-2xl mx-auto',
        default      => 'grid sm:grid-cols-2 lg:grid-cols-3 gap-6',
    };
@endphp

<section id="services" class="relative overflow-hidden bg-background py-10 sm:py-14 lg:py-20">

    {{-- Formas decorativas de fondo --}}
    <div class="pointer-events-none absolute inset-0" aria-hidden="true">
        <div class="absolute -top-32 -right-32 size-96 rounded-full bg-primary opacity-5"></div>
        <div class="absolute -bottom-24 -left-16 size-72 rotate-45 rounded-3xl bg-primary opacity-5"></div>
    </div>

    <div class="relative mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">

        {{-- Section Header --}}
        <div class="mb-12 text-center sm:mb-16">
            <h2 class="text-2xl font-semibold text-foreground md:text-3xl lg:text-4xl">
                Nuestros <span class="italic text-primary">Servicios</span>
            </h2>
            <div class="mx-auto mt-4 h-1 w-16 rounded-full bg-primary"></div>
        </div>

        {{-- Grid de servicios --}}
        <div class="{{ $gridClass }}">
            @foreach($displayServices as $service)
                @php
                    $waPhone  = $tenant->getActiveWhatsapp() ? preg_replace('/[^0-9]/', '', $tenant->getActiveWhatsapp()) : null;
                    $waMsg    = '¡Hola! Me gustaría obtener más información sobre el servicio: ' . $service->name . '. ¿Podrían ayudarme?';
                    $ctaLink  = $service->cta_link
                        ?? ($waPhone ? 'https://wa.me/' . $waPhone . '?text=' . urlencode($waMsg) : '#');
                    $ctaText  = $service->cta_text ?? 'Quiero más información';
                    $iconName = $service->icon_name ?? 'cog';
                @endphp

                <div class="group flex flex-col h-full bg-card border border-card-line shadow-2xs rounded-xl p-6 transition-all duration-300 hover:border-primary hover:-translate-y-0.5">

                    {{-- Icono O imagen (pequeño, spotlight) --}}
                    @if($service->image_filename)
                        <div class="mb-5 size-14 overflow-hidden rounded-xl">
                            <img src="{{ asset('storage/tenants/' . $tenant->id . '/' . $service->image_filename) }}"
                                 alt="{{ $service->name }}"
                                 class="size-full object-cover"
                                 onerror="this.style.display='none';">
                        </div>
                    @else
                        <div class="mb-5 flex size-14 items-center justify-center rounded-xl bg-primary/10">
                            <span class="iconify tabler--{{ $iconName }} size-7 text-primary"></span>
                        </div>
                    @endif

                    {{-- Nombre --}}
                    <h3 class="text-xl font-semibold text-foreground group-hover:text-primary transition-colors">
                        {{ $service->name }}
                    </h3>

                    {{-- Descripción --}}
                    <p class="mt-3 text-sm leading-relaxed text-muted-foreground-1 line-clamp-3">
                        {{ $service->description ?? 'Soluciones personalizadas diseñadas para elevar el estándar de tu negocio.' }}
                    </p>

                    {{-- CTA WhatsApp B2H --}}
                    <div class="mt-auto pt-5">
                        <a href="{{ $ctaLink }}"
                           {{ $waPhone ? 'target="_blank" rel="noopener noreferrer"' : '' }}
                           class="inline-flex items-center gap-1.5 text-sm font-semibold text-primary hover:text-primary-hover transition-colors group/btn">
                            <span class="iconify tabler--brand-whatsapp size-4"></span>
                            <span>{{ $ctaText }}</span>
                            <span class="iconify tabler--arrow-right size-4 transition-transform group-hover/btn:translate-x-1"></span>
                        </a>
                    </div>

                </div>
            @endforeach
        </div>

    </div>
</section>