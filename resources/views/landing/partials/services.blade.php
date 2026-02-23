{{-- Services Section: Arquitectura de Lujo --}}
<section id="servicios" class="relative py-32 overflow-hidden bg-surface-950">
    
    {{-- CAPA ATMOSFÉRICA: Luz radial de marca --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[700px] bg-primary/10 blur-[130px] rounded-full pointer-events-none"></div>

    <div class="container mx-auto px-8 relative z-10">
        
        {{-- Encabezado con clase --}}
        <div class="text-center mb-24">
            <h2 class="text-white text-4xl md:text-6xl font-black tracking-tighter mb-6">
                Nuestros <span class="text-primary italic">Servicios</span>
            </h2>
            <div class="w-24 h-1.5 bg-primary mx-auto rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
            @foreach($services as $service)
                <article class="group relative bg-white/5 border border-white/10 rounded-[2.5rem] p-8 transition-all duration-500 hover:bg-white/10 hover:border-primary/30 hover:-translate-y-2 hover:shadow-glow-primary">
                    
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
                        <h3 class="text-2xl font-bold text-white tracking-tight group-hover:text-primary transition-colors">
                            {{ $service->name }}
                        </h3>
                        
                        <p class="text-gray-400 text-sm leading-relaxed line-clamp-3">
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
        </div>
    </div>
</section>