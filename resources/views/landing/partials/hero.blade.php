{{-- Hero Section - SYNTI Design System --}}
<section 
    class="relative min-h-screen flex items-center justify-center overflow-hidden {{ !$customization?->hero_filename ? 'bg-gradient-to-br from-primary-500 to-primary-700' : '' }}"
    @if($customization?->hero_filename)
        style="background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.5), rgba(0,0,0,0.6)), url('{{ asset('storage/tenants/' . $tenant->id . '/' . $customization->hero_filename) }}'); background-size: cover; background-position: center; background-attachment: fixed;"
    @endif
>
    
    {{-- Hero Content --}}
    <div class="relative z-10 text-center px-4 py-20 max-w-5xl mx-auto">
        
        {{-- Logo --}}
        @if($customization?->logo_filename)
            <div class="mb-8 flex justify-center">
                <img 
                    src="{{ asset('storage/tenants/' . $tenant->id . '/' . $customization->logo_filename) }}" 
                    alt="{{ $tenant->business_name }}"
                    class="w-24 h-24 md:w-32 md:h-32 rounded-full object-cover shadow-xl"
                    style="box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.3);"
                >
            </div>
        @endif
        
        {{-- Status Badge --}}
        <div class="mb-6 flex justify-center">
            @if($tenant->is_open)
                <span class="synti-badge-success inline-flex items-center gap-2 text-sm md:text-base px-4 py-2">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    Abierto Ahora
                </span>
            @else
                <span class="synti-badge-danger inline-flex items-center gap-2 text-sm md:text-base px-4 py-2">
                    <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                    Cerrado
                </span>
            @endif
        </div>
        
        {{-- Business Name --}}
        <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-white mb-6 leading-tight" style="font-family: var(--synti-font-display);">
            {{ $tenant->business_name }}
        </h1>
        
        {{-- Tagline --}}
        @if($tenant->slogan)
            <p class="text-xl md:text-2xl text-white/90 mb-12 max-w-3xl mx-auto leading-relaxed" style="font-family: var(--synti-font-body);">
                {{ $tenant->slogan }}
            </p>
        @endif
        
        {{-- Call to Action Buttons --}}
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            
            {{-- Primary CTA: Ver Productos --}}
            <a 
                href="#productos"
                class="synti-btn-primary text-base md:text-lg px-8 py-4 min-w-50"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                Ver Productos
            </a>
            
            {{-- Secondary CTA: WhatsApp --}}
            @if($tenant->whatsapp_sales)
                <a 
                    href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tenant->whatsapp_sales) }}?text={{ urlencode('¡Hola! Vengo desde tu página web') }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="synti-btn-success text-base md:text-lg px-8 py-4 min-w-50"
                >
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    Escribinos
                </a>
            @endif
            
        </div>
        
    </div>
    
    {{-- Scroll Indicator --}}
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-10 animate-bounce">
        <svg class="w-6 h-6 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
        </svg>
    </div>
    
</section>
