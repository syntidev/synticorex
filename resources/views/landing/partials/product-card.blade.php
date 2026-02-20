{{-- Product Card - FlyonUI + Synti Design System --}}

<article class="card bg-base-100 shadow-synti hover:shadow-synti-lg transition-all duration-300 hover:-translate-y-1 overflow-hidden {{ $featured ? 'ring-2 ring-yellow-400' : '' }}">
    
    {{-- Product Image --}}
    <figure class="relative aspect-square bg-neutral-100">
        @if($product->image_filename)
            <img 
                src="{{ asset('storage/tenants/' . $tenant->id . '/products/' . $product->image_filename) }}" 
                alt="{{ $product->name }}"
                class="w-full h-full object-cover"
                loading="lazy"
            >
        @else
            <div class="w-full h-full flex items-center justify-center bg-neutral-200">
                <svg class="w-16 h-16 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        @endif
        
        {{-- Product Type Badge --}}
        @if($product->badge)
            <div class="absolute top-3 left-3">
                @if($product->badge === 'hot')
                    <span class="badge badge-error badge-soft text-xs font-bold">🔥 HOT</span>
                @elseif($product->badge === 'new')
                    <span class="badge badge-success badge-soft text-xs font-bold">✨ NUEVO</span>
                @elseif($product->badge === 'promo')
                    <span class="badge badge-warning badge-soft text-xs font-bold">💰 PROMO</span>
                @endif
            </div>
        @endif
        
        {{-- Featured Star --}}
        @if($featured)
            <div class="absolute top-3 right-3">
                <span class="badge badge-warning text-xs">⭐ Destacado</span>
            </div>
        @endif
    </figure>
    
    {{-- Product Info --}}
    <div class="card-body p-4">
        <h3 class="card-title text-base md:text-lg text-neutral-900 line-clamp-2">{{ $product->name }}</h3>
        
        @if($product->description)
            <p class="text-sm text-neutral-600 line-clamp-2 flex-grow">{{ $product->description }}</p>
        @endif
        
        {{-- Price --}}
        @if(!$hidePrice && $product->price_usd && (float)$product->price_usd > 0)
            <div class="mt-2">
                @if($showBolivares && !$showReference)
                    <span 
                        class="text-xl md:text-2xl font-bold text-primary-600"
                        data-price-usd="{{ $product->price_usd }}"
                    >
                        Bs. {{ number_format((float)$product->price_usd * $dollarRate, 2) }}
                    </span>
                @elseif($showReference && !$showBolivares)
                    <span 
                        class="text-xl md:text-2xl font-bold text-primary-600"
                        data-price-usd="{{ $product->price_usd }}"
                    >
                        {{ $currencySettings['symbols']['reference'] ?? 'REF' }} {{ number_format((float)$product->price_usd, 2) }}
                    </span>
                @else
                    {{-- Both / Toggle --}}
                    <span 
                        class="text-xl md:text-2xl font-bold text-primary-600 cursor-pointer"
                        data-price-usd="{{ $product->price_usd }}"
                        title="Click para cambiar divisa"
                    >
                        {{ $currencySettings['symbols']['reference'] ?? 'REF' }} {{ number_format((float)$product->price_usd, 2) }}
                    </span>
                @endif
            </div>
        @endif
        
        {{-- WhatsApp CTA Button --}}
        @if($tenant->whatsapp_sales)
            @php
                $hasPrice = !$hidePrice && $product->price_usd && (float)$product->price_usd > 0;
                $buttonLabel = $hasPrice ? 'Pedir' : 'Más Info';
                $whatsappMessage = $hasPrice 
                    ? 'Hola! Quiero pedir: ' . $product->name . ' - $' . number_format((float)$product->price_usd, 2)
                    : 'Hola! Quisiera más información sobre: ' . $product->name;
            @endphp
            <div class="card-actions mt-3">
                <a 
                    href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tenant->whatsapp_sales) }}?text={{ urlencode($whatsappMessage) }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="btn btn-success btn-soft btn-sm w-full gap-2"
                >
                    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    {{ $buttonLabel }}
                </a>
            </div>
        @endif
    </div>
    
</article>
