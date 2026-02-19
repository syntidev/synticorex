{{-- Services Section Partial --}}
<section id="servicios" class="py-16 px-4" style="background-color: var(--color-section-bg-alt);">
    <div class="container mx-auto">
        <h2 class="text-3xl font-bold text-center mb-12" style="color: var(--color-primary);">
            Nuestros Servicios
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($services as $service)
                <article class="bg-white rounded-xl shadow-lg overflow-hidden transition-transform hover:scale-[1.02]">
                    {{-- Service Image or Icon --}}
                    <div class="relative aspect-video bg-gray-100">
                        @if($service->image_filename)
                            <img 
                                src="{{ asset('storage/tenants/' . $tenant->id . '/services/' . $service->image_filename) }}" 
                                alt="{{ $service->name }}"
                                class="w-full h-full object-cover"
                                loading="lazy"
                            >
                            @if($service->overlay_text)
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                    <span class="text-white text-xl font-bold text-center px-4">
                                        {{ $service->overlay_text }}
                                    </span>
                                </div>
                            @endif
                        @else
                            <div class="w-full h-full flex items-center justify-center" style="background-color: var(--color-primary);">
                                @if($service->icon_name)
                                    <span class="text-6xl text-white">
                                        @switch($service->icon_name)
                                            @case('scissors')
                                                ✂️
                                                @break
                                            @case('wrench')
                                                🔧
                                                @break
                                            @case('burger')
                                                🍔
                                                @break
                                            @case('pizza')
                                                🍕
                                                @break
                                            @case('car')
                                                🚗
                                                @break
                                            @case('home')
                                                🏠
                                                @break
                                            @case('heart')
                                                ❤️
                                                @break
                                            @case('star')
                                                ⭐
                                                @break
                                            @default
                                                🔹
                                        @endswitch
                                    </span>
                                @else
                                    <svg class="w-16 h-16 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                @endif
                            </div>
                        @endif
                    </div>
                    
                    {{-- Service Info --}}
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $service->name }}</h3>
                        
                        @if($service->description)
                            <p class="text-gray-600 mb-4">{{ $service->description }}</p>
                        @endif
                        
                        {{-- CTA Button --}}
                        @php
                            $ctaLink = $service->cta_link ?? ($tenant->whatsapp_sales 
                                ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $tenant->whatsapp_sales) . '?text=' . urlencode('Hola! Me interesa el servicio: ' . $service->name)
                                : '#');
                        @endphp
                        
                        <a 
                            href="{{ $ctaLink }}"
                            target="{{ $service->cta_link ? '_blank' : '_self' }}"
                            class="inline-flex items-center px-6 py-3 rounded-lg font-medium transition-colors w-full justify-center"
                            style="background-color: var(--color-button-bg); color: var(--color-button-text);"
                        >
                            {{ $service->cta_text ?? 'Más información' }}
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
