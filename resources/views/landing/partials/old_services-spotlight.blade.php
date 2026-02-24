{{-- Services Section: Spotlight Variant (Compact & Clean) --}}
<section id="servicios" class="relative py-20 overflow-hidden bg-base-100">
    
    <div class="container mx-auto px-8 relative z-10">
        
        {{-- Section Header --}}
        <div class="text-center mb-16">
            <h2 class="text-base-content text-4xl md:text-5xl font-black tracking-tight mb-4">
                {{ $tenant->section_services_title ?? 'Nuestros' }} 
                <span class="text-primary italic">{{ $tenant->section_services_highlight ?? 'Servicios' }}</span>
            </h2>
            <div class="w-16 h-1 bg-primary mx-auto rounded-full"></div>
        </div>

        {{-- Dynamic Grid Based on Service Count --}}
        @php
            $serviceCount = $services->count();
            
            // Determine grid classes based on count
            if ($serviceCount === 1) {
                $gridClasses = 'max-w-sm mx-auto';
            } elseif ($serviceCount === 2) {
                $gridClasses = 'grid grid-cols-2 gap-8 max-w-2xl mx-auto';
            } else {
                $gridClasses = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8';
            }
        @endphp

        <div class="{{ $gridClasses }}">
            @foreach($services as $service)
                <article class="group relative bg-base-100 border border-base-content/8 rounded-2xl p-6 transition-all duration-300 hover:border-primary/30 hover:-translate-y-1 hover:shadow-xl hover:shadow-primary/5">
                    
                    {{-- Icon or Image (Compact) --}}
                    <div class="relative w-12 h-12 mb-6 rounded-xl bg-primary/15 flex items-center justify-center overflow-hidden group-hover:bg-primary/25 transition-colors">
                        @if($service->image_filename)
                            <img 
                                src="{{ asset('storage/tenants/' . $tenant->id . '/services/' . $service->image_filename) }}" 
                                alt="{{ $service->name }}"
                                class="w-full h-full object-cover"
                            >
                        @else
                            <iconify-icon 
                                icon="tabler:{{ $service->icon_name ?? 'cog' }}" 
                                class="text-primary" 
                                width="24" 
                                height="24">
                            </iconify-icon>
                        @endif
                    </div>
                    
                    {{-- Service Information --}}
                    <div class="space-y-3">
                        <h3 class="text-xl font-bold text-base-content tracking-tight group-hover:text-primary transition-colors">
                            {{ $service->name }}
                        </h3>
                        
                        <p class="text-base-content/60 text-sm leading-relaxed line-clamp-3">
                            {{ $service->description ?? 'Soluciones personalizadas diseñadas para elevar el estándar de tu negocio.' }}
                        </p>
                        
                        {{-- CTA Button --}}
                        <div class="pt-4">
                            @php
                                $ctaLink = $service->cta_link ?? ($tenant->whatsapp_sales 
                                    ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $tenant->whatsapp_sales) . '?text=' . urlencode('Hola! Me interesa el servicio: ' . $service->name)
                                    : '#');
                            @endphp
                            
                            <a href="{{ $ctaLink }}" 
                               target="_blank"
                               rel="noopener noreferrer"
                               class="inline-flex items-center gap-2 text-primary font-semibold text-sm tracking-wide group/btn">
                                <span>{{ $service->cta_text ?? 'Saber más' }}</span>
                                <iconify-icon 
                                    icon="tabler:arrow-right" 
                                    class="transition-transform group-hover/btn:translate-x-1" 
                                    width="16" 
                                    height="16">
                                </iconify-icon>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
