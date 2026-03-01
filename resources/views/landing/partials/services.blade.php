{{-- Services Section: Arquitectura de Lujo --}}
@php
    $defaultVisibleServices = 3;
    $servicePlanLimit       = (int) ($plan->services_limit ?? 3);
    $displayServices        = $services->take($servicePlanLimit);
    $visibleServices        = $displayServices->take($defaultVisibleServices);
    $hiddenServices         = $displayServices->slice($defaultVisibleServices);
    $hasMoreServices        = $servicePlanLimit > $defaultVisibleServices && $hiddenServices->count() > 0;
@endphp
<section id="servicios" class="relative py-32 overflow-hidden bg-base-200">

    <div class="container mx-auto px-8 relative z-10">
        
        {{-- Encabezado con clase --}}
        <div class="text-center mb-24">
            <h2 class="text-base-content text-4xl md:text-6xl font-black tracking-tighter mb-6">
                Nuestros <span class="text-primary italic">Servicios</span>
            </h2>
            <div class="w-24 h-1.5 bg-primary mx-auto rounded-full"></div>
        </div>

        <div id="service-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
            {{-- Primeros 3 — siempre visibles --}}
            @foreach($visibleServices as $service)
                <article class="group relative bg-base-content/5 border border-base-content/10 rounded-[2.5rem] p-8 transition-all duration-500 hover:bg-base-content/10 hover:border-primary/30 hover:-translate-y-2 hover:shadow-glow-primary">
                    
                    {{-- Icon or Image --}}
                    <div class="relative w-20 h-20 mb-8 rounded-2xl bg-primary/10 flex items-center justify-center overflow-hidden group-hover:bg-primary/20 transition-colors">
                        @if($service->image_filename)
                            <img 
                                src="{{ asset('storage/tenants/' . $tenant->id . '/services/' . $service->image_filename) }}" 
                                alt="{{ $service->name }}"
                                class="w-full h-full object-cover"
                            >
                        @else
                            <iconify-icon icon="tabler:{{ $service->icon_name ?? 'cog' }}" class="text-primary" width="40" height="40"></iconify-icon>
                        @endif
                    </div>
                    
                    {{-- Información del Servicio --}}
                    <div class="space-y-4">
                        <h3 class="text-2xl font-bold text-base-content tracking-tight group-hover:text-primary transition-colors">
                            {{ $service->name }}
                        </h3>
                        
                        <p class="text-base-content/60 text-sm leading-relaxed line-clamp-3">
                            {{ $service->description ?? 'Soluciones personalizadas diseñadas para elevar el estándar de tu negocio.' }}
                        </p>
                        
                        {{-- Botón de Acción Refinado --}}
                        <div class="pt-6">
                            @php
                                $ctaLink = $service->cta_link ?? ($tenant->whatsapp_sales 
                                    ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $tenant->whatsapp_sales) . '?text=' . urlencode('Hola! Me interesa el servicio: ' . $service->name)
                                    : '#');
                            @endphp
                            
                            <a href="{{ $ctaLink }}" 
                               class="inline-flex items-center gap-2 text-primary font-black uppercase text-xs tracking-widest group/btn">
                                <span>{{ $service->cta_text ?? 'Saber más' }}</span>
                                <iconify-icon icon="tabler:arrow-right" class="transition-transform group-hover/btn:translate-x-2" width="16" height="16"></iconify-icon>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach

            {{-- Servicios adicionales (Plan 2: hasta 6, Plan 3: hasta 9) — ocultos por defecto --}}
            @if($hasMoreServices)
                @foreach($hiddenServices as $service)
                    <div class="service-extra hidden">
                        <article class="group relative bg-base-content/5 border border-base-content/10 rounded-[2.5rem] p-8 transition-all duration-500 hover:bg-base-content/10 hover:border-primary/30 hover:-translate-y-2 hover:shadow-glow-primary">
                            <div class="relative w-20 h-20 mb-8 rounded-2xl bg-primary/10 flex items-center justify-center overflow-hidden group-hover:bg-primary/20 transition-colors">
                                @if($service->image_filename)
                                    <img 
                                        src="{{ asset('storage/tenants/' . $tenant->id . '/services/' . $service->image_filename) }}" 
                                        alt="{{ $service->name }}"
                                        class="w-full h-full object-cover"
                                    >
                                @else
                                    <iconify-icon icon="tabler:{{ $service->icon_name ?? 'cog' }}" class="text-primary" width="40" height="40"></iconify-icon>
                                @endif
                            </div>
                            <div class="space-y-4">
                                <h3 class="text-2xl font-bold text-base-content tracking-tight group-hover:text-primary transition-colors">
                                    {{ $service->name }}
                                </h3>
                                <p class="text-base-content/60 text-sm leading-relaxed line-clamp-3">
                                    {{ $service->description ?? 'Soluciones personalizadas diseñadas para elevar el estándar de tu negocio.' }}
                                </p>
                                <div class="pt-6">
                                    @php
                                        $ctaLink = $service->cta_link ?? ($tenant->whatsapp_sales 
                                            ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $tenant->whatsapp_sales) . '?text=' . urlencode('Hola! Me interesa el servicio: ' . $service->name)
                                            : '#');
                                    @endphp
                                    <a href="{{ $ctaLink }}" 
                                       class="inline-flex items-center gap-2 text-primary font-black uppercase text-xs tracking-widest group/btn">
                                        <span>{{ $service->cta_text ?? 'Saber más' }}</span>
                                        <iconify-icon icon="tabler:arrow-right" class="transition-transform group-hover/btn:translate-x-2" width="16" height="16"></iconify-icon>
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            @endif
        </div>

        {{-- Botón "Ver más" servicios — carga 3 en 3 --}}
        @if($hasMoreServices)
            @php $firstServiceBatch = min(3, $hiddenServices->count()); @endphp
            <div id="load-more-services-container" class="mt-20 text-center">
                <button
                    onclick="loadMoreServices(this)"
                    class="group inline-flex items-center gap-4 px-12 py-5 bg-primary text-white font-black rounded-2xl shadow-2xl shadow-primary/40 active:scale-95 transition-all">
                    <span class="btn-label uppercase tracking-[0.3em] text-[10px]">Ver {{ $firstServiceBatch }} servicio{{ $firstServiceBatch > 1 ? 's' : '' }} más</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                    </svg>
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