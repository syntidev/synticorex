{{-- Services Section: Spotlight Variant --}}
<section id="servicios" class="relative py-20" style="background:#ffffff;">

    {{-- FORMAS DE FONDO — relleno semitransparente, como las referencias --}}

    {{-- Círculo grande fondo derecho --}}
    <div style="position:absolute; top:-120px; right:-120px; width:500px; height:500px; border-radius:50%; background:var(--color-primary); opacity:0.06; pointer-events:none; z-index:0;"></div>

    {{-- Círculo mediano superpuesto --}}
    <div style="position:absolute; top:-20px; right:-20px; width:300px; height:300px; border-radius:50%; background:var(--color-primary); opacity:0.05; pointer-events:none; z-index:0;"></div>

    {{-- Forma rombo/diamante fondo izquierdo inferior — imagen 5 --}}
    <div style="position:absolute; left:-80px; bottom:-80px; width:320px; height:320px; background:var(--color-primary); opacity:0.05; transform:rotate(45deg); border-radius:40px; pointer-events:none; z-index:0;"></div>
    <div style="position:absolute; left:-40px; bottom:-40px; width:220px; height:220px; background:var(--color-primary); opacity:0.04; transform:rotate(45deg); border-radius:30px; pointer-events:none; z-index:0;"></div>

    {{-- Contenido --}}
    <div class="container mx-auto px-8 relative" style="z-index:2;">

        <div class="text-center mb-16">
            <h2 class="text-base-content text-2xl font-semibold md:text-3xl lg:text-4xl mb-4">
                {{ $tenant->section_services_title ?? 'Nuestros' }}
                <span class="text-primary italic">{{ $tenant->section_services_highlight ?? 'Servicios' }}</span>
            </h2>
            <div class="w-16 h-1 bg-primary mx-auto rounded-full"></div>
        </div>

        @php
            $count = $services->count();
            $grid = match(true) {
                $count === 1 => 'max-w-sm mx-auto',
                $count === 2 => 'grid grid-cols-2 gap-6 max-w-2xl mx-auto',
                default      => 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6',
            };
        @endphp

        <div class="{{ $grid }}">
            @foreach($services as $service)
                @php
                    $ctaLink = $service->cta_link ?? ($tenant->getActiveWhatsapp()
                        ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $tenant->getActiveWhatsapp())
                          . '?text=' . urlencode('Hola! Me interesa: ' . $service->name)
                        : '#');
                @endphp

                <article style="background:#ffffff; border:1px solid #e5e7eb; border-radius:16px;"
                         class="group p-6 transition-all duration-300 hover:-translate-y-1"
                         onmouseenter="this.style.borderColor='var(--color-primary)'; this.style.boxShadow='0 12px 40px rgba(0,0,0,0.08)';"
                         onmouseleave="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">

                    <div class="w-11 h-11 mb-5 rounded-xl bg-primary/10 flex items-center justify-center">
                        @if($service->image_filename)
                            <img src="{{ asset('storage/tenants/' . $tenant->id . '/services/' . $service->image_filename) }}"
                                 alt="{{ $service->name }}" class="w-full h-full object-cover rounded-xl">
                        @else
                            <iconify-icon icon="tabler:{{ $service->icon_name ?? 'cog' }}"
                                          class="text-primary" width="22" height="22"></iconify-icon>
                        @endif
                    </div>

                    <h3 class="text-xl font-semibold text-base-content mb-2 group-hover:text-primary transition-colors">
                        {{ $service->name }}
                    </h3>

                    <p class="text-sm leading-relaxed line-clamp-3 mb-5" style="color:#6b7280;">
                        {{ $service->description ?? 'Soluciones diseñadas para tu negocio.' }}
                    </p>

                    <a href="{{ $ctaLink }}" target="_blank"
                       class="inline-flex items-center gap-1.5 text-primary text-sm font-semibold group/btn">
                        <span>{{ $service->cta_text ?? 'Saber más' }}</span>
                        <iconify-icon icon="tabler:arrow-right"
                                      class="transition-transform group-hover/btn:translate-x-1"
                                      width="16" height="16"></iconify-icon>
                    </a>

                </article>
            @endforeach
        </div>

    </div>
</section>