{{-- Services Section — Preline 4.1.2 + Tailwind v4
     Variante: cards | Ícono OR imagen en cabecera, botón WhatsApp B2H --}}
@php
    $defaultVisibleServices = 3;
    $servicePlanLimit       = (int) ($plan->services_limit ?? 3);
    $displayServices        = $services->take($servicePlanLimit);
    $visibleServices        = $displayServices->take($defaultVisibleServices);
    $hiddenServices         = $displayServices->slice($defaultVisibleServices);
    $hasMoreServices        = $servicePlanLimit > $defaultVisibleServices && $hiddenServices->count() > 0;
@endphp
<section id="services" class="bg-background py-10 sm:py-14 lg:py-20">

    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">

        {{-- Section Header --}}
        <div class="mb-12 text-center sm:mb-16 lg:mb-24">
            <h2 class="text-foreground text-2xl font-semibold md:text-3xl lg:text-4xl">
                {!! $customization->getSectionTitle('services', 'Nuestros <span class="text-primary italic">Servicios</span>') !!}
            </h2>
            <div class="w-16 h-1 bg-primary mx-auto mt-4 rounded-full"></div>
            @if($customization->getSectionSubtitle('services'))
            <p class="text-foreground/80 text-xl mt-4">{{ $customization->getSectionSubtitle('services') }}</p>
            @else
            <p class="text-foreground/80 text-xl mt-4">Soluciones diseñadas para hacer tu experiencia única, con calidad y dedicación.</p>
            @endif
        </div>

        {{-- Service Grid --}}
        <div id="service-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

            {{-- Todos los servicios: primeros 3 visibles, el resto con .service-extra.hidden --}}
            @foreach($displayServices as $loopIndex => $service)
                @php
                    $isExtra  = $loopIndex >= $defaultVisibleServices;
                    $waPhone  = $tenant->getActiveWhatsapp() ? preg_replace('/[^0-9]/', '', $tenant->getActiveWhatsapp()) : null;
                    $waMsg    = '¡Hola! Me gustaría obtener más información sobre el servicio: ' . $service->name . '. ¿Podrían ayudarme?';
                    $ctaLink  = $service->cta_link
                        ?? ($waPhone ? 'https://wa.me/' . $waPhone . '?text=' . urlencode($waMsg) : '#');
                    $ctaText  = $service->cta_text ?? 'Quiero más información';
                    $iconName = $service->icon_name ?? 'star';
                @endphp

                <div class="{{ $isExtra ? 'service-extra hidden' : '' }}">
                    <div class="group flex flex-col h-full bg-card border border-card-line shadow-2xs rounded-xl">

                        {{-- Cabecera: imagen O ícono sobre fondo brand --}}
                        <div class="h-52 flex flex-col justify-center items-center bg-primary rounded-t-xl overflow-hidden">
                            @if($service->image_filename)
                                <img src="{{ asset('storage/tenants/' . $tenant->id . '/' . $service->image_filename) }}"
                                     alt="{{ $service->name }}"
                                     class="size-full object-cover"
                                     onerror="this.style.display='none'; this.parentElement.classList.add('items-center','justify-center');">
                            @else
                                <span class="iconify tabler--{{ $iconName }} size-20 text-white/90"></span>
                            @endif
                        </div>

                        {{-- Contenido --}}
                        <div class="p-4 md:p-6">
                            <span class="block mb-1 text-xs font-semibold uppercase text-primary">Servicio</span>
                            <h3 class="text-xl font-semibold text-foreground group-hover:text-primary transition-colors">
                                {{ $service->name }}
                            </h3>
                            <p class="mt-3 text-muted-foreground-1 line-clamp-3">
                                {{ $service->description ?? 'Soluciones personalizadas diseñadas para elevar el estándar de tu negocio.' }}
                            </p>
                        </div>

                        {{-- CTA — WhatsApp B2H, anclado al fondo --}}
                        <div class="mt-auto flex border-t border-line-2">
                            <a href="{{ $ctaLink }}"
                               {{ $waPhone ? 'target="_blank" rel="noopener noreferrer"' : '' }}
                               class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-b-xl bg-layer text-foreground shadow-2xs hover:bg-muted-hover focus:outline-hidden focus:bg-muted-focus disabled:opacity-50 disabled:pointer-events-none transition-colors">
                                <span class="iconify tabler--brand-whatsapp size-4 text-success"></span>
                                {{ $ctaText }}
                            </a>
                        </div>

                    </div>
                </div>
            @endforeach

        </div>

        {{-- Botón "Ver más" servicios — carga 3 en 3 --}}
        @if($hasMoreServices)
            @php $firstServiceBatch = min(3, $hiddenServices->count()); @endphp
            <div id="load-more-services-container" class="mt-12 text-center">
                <button
                    onclick="loadMoreServices(this)"
                    class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium text-nowrap rounded-xl bg-primary border border-primary-line text-primary-foreground hover:bg-primary-hover focus:outline-hidden focus:bg-primary-focus transition disabled:opacity-50 disabled:pointer-events-none">
                    <span class="btn-label">Ver {{ $firstServiceBatch }} servicio{{ $firstServiceBatch > 1 ? 's' : '' }} más</span>
                    <span class="iconify tabler--chevron-down size-5"></span>
                </button>
            </div>

            @push('scripts')
            <script>
                function loadMoreServices(btn) {
                    const hiddenItems = Array.from(
                        document.querySelectorAll('#service-grid .service-extra.hidden')
                    );
                    if (!hiddenItems.length) return;

                    const batch = hiddenItems.slice(0, 3);
                    batch.forEach(el => {
                        el.classList.remove('hidden');
                        el.style.opacity = '0';
                        el.style.transform = 'translateY(20px)';
                        requestAnimationFrame(() => {
                            requestAnimationFrame(() => {
                                el.style.transition = 'opacity 300ms ease, transform 300ms ease';
                                el.style.opacity = '1';
                                el.style.transform = 'translateY(0)';
                            });
                        });
                    });

                    const remaining = hiddenItems.length - batch.length;
                    if (remaining === 0) {
                        document.getElementById('load-more-services-container').style.display = 'none';
                    } else {
                        const next = Math.min(3, remaining);
                        btn.querySelector('.btn-label').textContent =
                            'Ver ' + next + ' servicio' + (next > 1 ? 's' : '') + ' más';
                    }
                }
            </script>
            @endpush
        @endif
    </div>
</section>