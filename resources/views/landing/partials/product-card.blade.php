{{-- Product Card Partial --}}
<article class="bg-white rounded-xl shadow-lg overflow-hidden transition-transform hover:scale-[1.02] {{ $featured ? 'ring-2 ring-yellow-400' : '' }}">
    {{-- Product Image --}}
    <div class="relative aspect-square bg-gray-100">
        @if($product->image_filename)
            <img 
                src="{{ asset('storage/tenants/' . $tenant->id . '/products/' . $product->image_filename) }}" 
                alt="{{ $product->name }}"
                class="w-full h-full object-cover"
                loading="lazy"
            >
        @else
            <div class="w-full h-full flex items-center justify-center bg-gray-200">
                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        @endif
        
        {{-- Badge --}}
        @if($product->badge)
            <span class="absolute top-3 left-3 px-3 py-1 rounded-full text-xs font-bold text-white
                {{ $product->badge === 'hot' ? 'bg-red-500' : '' }}
                {{ $product->badge === 'new' ? 'bg-green-500' : '' }}
                {{ $product->badge === 'promo' ? 'bg-orange-500' : '' }}
            ">
                {{ $product->badge === 'hot' ? '🔥 HOT' : '' }}
                {{ $product->badge === 'new' ? '✨ NUEVO' : '' }}
                {{ $product->badge === 'promo' ? '💰 PROMO' : '' }}
            </span>
        @endif
        
        {{-- Featured Star --}}
        @if($featured)
            <span class="absolute top-3 right-3 text-2xl">⭐</span>
        @endif
    </div>
    
    {{-- Product Info --}}
    <div class="p-4">
        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $product->name }}</h3>
        
        @if($product->description)
            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $product->description }}</p>
        @endif
        
        {{-- Price --}}
        <div class="flex items-center justify-between">
            <span 
                class="text-xl font-bold"
                style="color: var(--color-primary);"
                data-price-usd="{{ $product->price_usd }}"
            >
                {{-- Initial price (will be updated by JS) --}}
                {{ $currencySettings['symbols']['reference'] }} {{ number_format((float)$product->price_usd, 2) }}
            </span>
            
            {{-- WhatsApp Order Button --}}
            @if($tenant->whatsapp_sales)
                <a 
                    href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tenant->whatsapp_sales) }}?text={{ urlencode('Hola! Me interesa: ' . $product->name) }}"
                    target="_blank"
                    class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                    style="background-color: var(--color-button-bg); color: var(--color-button-text);"
                >
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347"/>
                    </svg>
                    Pedir
                </a>
            @endif
        </div>
    </div>
</article>
