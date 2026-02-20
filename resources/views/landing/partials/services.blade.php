{{-- Services Section - FlyonUI + Synti Design System --}}
<section id="servicios" class="synti-section-white">
    <div class="synti-container">
        
        {{-- Section Header --}}
        <div class="synti-section-header">
            <h2 class="synti-section-title">Nuestros Servicios</h2>
            <p class="synti-section-subtitle">Lo que podemos hacer por ti</p>
        </div>
        
        {{-- Services Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            @foreach($services as $service)
                <article class="card bg-base-100 shadow-synti hover:shadow-synti-lg transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                    
                    {{-- Service Image / Icon --}}
                    <figure class="relative aspect-video bg-neutral-100">
                        @if($service->image_filename)
                            <img 
                                src="{{ asset('storage/tenants/' . $tenant->id . '/services/' . $service->image_filename) }}" 
                                alt="{{ $service->name }}"
                                class="w-full h-full object-cover"
                                loading="lazy"
                            >
                            @if($service->overlay_text)
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                    <span class="text-white text-lg md:text-xl font-bold text-center px-4">
                                        {{ $service->overlay_text }}
                                    </span>
                                </div>
                            @endif
                        @else
                            <div class="w-full h-full flex items-center justify-center" style="background-color: var(--synti-primary-500);">
                                @if($service->icon_name)
                                    <span class="text-6xl">
                                        @switch($service->icon_name)
                                            @case('scissors') ✂️ @break
                                            @case('wrench')   🔧 @break
                                            @case('burger')   🍔 @break
                                            @case('pizza')    🍕 @break
                                            @case('car')      🚗 @break
                                            @case('home')     🏠 @break
                                            @case('heart')    ❤️ @break
                                            @case('star')     ⭐ @break
                                            @default          🔹
                                        @endswitch
                                    </span>
                                @else
                                    <svg class="w-16 h-16 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                @endif
                            </div>
                        @endif
                    </figure>
                    
                    {{-- Service Info --}}
                    <div class="card-body p-6">
                        <h3 class="card-title text-lg md:text-xl text-neutral-900">{{ $service->name }}</h3>
                        
                        @if($service->description)
                            <p class="text-sm md:text-base text-neutral-600 flex-grow">{{ $service->description }}</p>
                        @endif
                        
                        {{-- CTA Button --}}
                        @php
                            $ctaLink = $service->cta_link ?? ($tenant->whatsapp_sales 
                                ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $tenant->whatsapp_sales) . '?text=' . urlencode('Hola! Me interesa el servicio: ' . $service->name)
                                : '#');
                            $ctaTarget = $service->cta_link ? '_blank' : '_self';
                        @endphp
                        <div class="card-actions mt-4">
                            <a 
                                href="{{ $ctaLink }}"
                                target="{{ $ctaTarget }}"
                                rel="{{ $service->cta_link ? 'noopener noreferrer' : '' }}"
                                class="btn btn-primary btn-soft w-full gap-2"
                            >
                                {{ $service->cta_text ?? 'Más información' }}
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                </article>
            @endforeach
        </div>
        
    </div>
</section>
