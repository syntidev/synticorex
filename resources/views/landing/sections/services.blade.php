{{-- Services Section: Arquitectura de Lujo --}}
@php
    $defaultVisibleServices = 3;
    $servicePlanLimit       = (int) ($plan->services_limit ?? 3);
    $displayServices        = $services->take($servicePlanLimit);
    $visibleServices        = $displayServices->take($defaultVisibleServices);
    $hiddenServices         = $displayServices->slice($defaultVisibleServices);
    $hasMoreServices        = $servicePlanLimit > $defaultVisibleServices && $hiddenServices->count() > 0;
@endphp
<section id="services" class="bg-surface py-8 sm:py-16 lg:py-24">

    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">

        {{-- Section Header --}}
        <div class="mb-12 text-center sm:mb-16 lg:mb-24">
            <h2 class="text-foreground text-2xl font-semibold md:text-3xl lg:text-4xl">
                Nuestros <span class="text-primary italic">Servicios</span>
            </h2>
            <div class="w-16 h-1 bg-primary mx-auto mt-4 rounded-full"></div>
            <p class="text-foreground/80 text-xl mt-4">Soluciones diseñadas para hacer tu experiencia única, con calidad y dedicación.</p>
        </div>

        {{-- Service Grid --}}
        <div id="service-grid" class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            {{-- Primeros 3 — siempre visibles --}}
            @foreach($visibleServices as $service)
                <div class="bg-background border border-border rounded-xl shadow-sm hover:border-primary transition-all duration-300">
                    @if($service->image_filename)
                        <figure>
                            <img src="{{ asset('storage/tenants/' . $tenant->id . '/services/' . $service->image_filename) }}"
                                 alt="{{ $service->name }}"
                                 onerror="this.style.display='none'; this.parentElement.style.display='none';">
                        </figure>
                    @endif
                    <div class="flex flex-col p-4 gap-3">
                        @if(!$service->image_filename)
                            <div class="mb-2">
                                <span class="icon-[tabler--{{ $service->icon_name ?? 'star' }}] text-primary size-10"></span>
                            </div>
                        @endif
                        <h5 class="text-xl font-semibold text-foreground">{{ $service->name }}</h5>
                        <p class="text-foreground/80 mb-5 line-clamp-3">
                            {{ $service->description ?? 'Soluciones personalizadas diseñadas para elevar el estándar de tu negocio.' }}
                        </p>
                        <div class="flex gap-2 mt-4">
                            @php
                                $ctaLink = $service->cta_link ?? ($tenant->getActiveWhatsapp()
                                    ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $tenant->getActiveWhatsapp()) . '?text=' . urlencode('Hola! Me interesa el servicio: ' . $service->name)
                                    : '#');
                            @endphp
                            <a href="{{ $ctaLink }}" class="inline-flex items-center py-2 px-4 rounded-lg font-medium transition-colors border border-border text-foreground/80 hover:bg-surface">
                                {{ $service->cta_text ?? 'Más información' }}
                                <span class="icon-[tabler--arrow-right] size-5 rtl:rotate-180"></span>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Servicios adicionales (Plan 2: hasta 6, Plan 3: hasta 9) — ocultos por defecto --}}
            @if($hasMoreServices)
                @foreach($hiddenServices as $service)
                    <div class="service-extra hidden">
                        <div class="bg-background border border-border rounded-xl shadow-sm hover:border-primary transition-all duration-300">
                            @if($service->image_filename)
                                <figure>
                                    <img src="{{ asset('storage/tenants/' . $tenant->id . '/services/' . $service->image_filename) }}"
                                         alt="{{ $service->name }}"
                                         onerror="this.style.display='none'; this.parentElement.style.display='none';">
                                </figure>
                            @endif
                            <div class="flex flex-col p-4 gap-3">
                                @if(!$service->image_filename)
                                    <div class="mb-2">
                                        <span class="icon-[tabler--{{ $service->icon_name ?? 'star' }}] text-primary size-10"></span>
                                    </div>
                                @endif
                                <h5 class="text-xl font-semibold text-foreground">{{ $service->name }}</h5>
                                <p class="text-foreground/80 mb-5 line-clamp-3">
                                    {{ $service->description ?? 'Soluciones personalizadas diseñadas para elevar el estándar de tu negocio.' }}
                                </p>
                                <div class="flex gap-2 mt-4">
                                    @php
                                        $ctaLink = $service->cta_link ?? ($tenant->getActiveWhatsapp()
                                            ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $tenant->getActiveWhatsapp()) . '?text=' . urlencode('Hola! Me interesa el servicio: ' . $service->name)
                                            : '#');
                                    @endphp
                                    <a href="{{ $ctaLink }}" class="inline-flex items-center py-2 px-4 rounded-lg font-medium transition-colors border border-border text-foreground/80 hover:bg-surface">
                                        {{ $service->cta_text ?? 'Más información' }}
                                        <span class="icon-[tabler--arrow-right] size-5 rtl:rotate-180"></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        {{-- Botón "Ver más" servicios — carga 3 en 3 --}}
        @if($hasMoreServices)
            @php $firstServiceBatch = min(3, $hiddenServices->count()); @endphp
            <div id="load-more-services-container" class="mt-12 text-center">
                <button
                    onclick="loadMoreServices(this)"
                    class="inline-flex items-center py-2 px-4 rounded-lg font-medium transition-colors bg-primary text-white hover:bg-primary/90">
                    <span class="btn-label">Ver {{ $firstServiceBatch }} servicio{{ $firstServiceBatch > 1 ? 's' : '' }} más</span>
                    <span class="icon-[tabler--chevron-down] size-5"></span>
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