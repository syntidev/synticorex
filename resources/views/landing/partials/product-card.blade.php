{{-- Path: resources/views/landing/partials/product-card.blade.php --}}
@php
    $isFeatured = $featured ?? ($product->is_featured ?? false);
@endphp

<article class="group bg-white rounded-[3rem] p-8 shadow-sm hover:shadow-2xl transition-all duration-500 border border-gray-100 flex flex-col h-full {{ $isFeatured ? 'ring-2 ring-primary/20' : '' }}">
    
    {{-- Imagen con respiro interno --}}
    <div class="relative aspect-square overflow-hidden rounded-[2.2rem] bg-gray-50 mb-10">
        @if($product->image_filename)
            <img src="{{ asset('storage/tenants/' . $tenant->id . '/products/' . $product->image_filename) }}" 
                 alt="{{ $product->name }}" 
                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
        @endif
    </div>

    <div class="px-2 flex flex-col flex-grow">
        <h3 class="text-2xl font-black text-gray-900 mb-4 group-hover:text-primary transition-colors leading-tight">
            {{ $product->name }}
        </h3>
        
        <p class="text-gray-500 text-sm mb-12 line-clamp-2 leading-relaxed">
            {{ $product->description }}
        </p>
        
        <div class="mt-auto pt-8 border-t border-gray-50 flex items-center justify-between">
            <div class="flex flex-col">
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Precio REF</span>
                <span class="text-3xl font-black text-gray-900 tracking-tighter">
                    ${{ number_format((float)$product->price_usd, 2) }}
                </span>
            </div>
            
            {{-- BOTÓN DE VENTA: Usando el color de marca (Primary) --}}
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tenant->whatsapp_sales) }}" 
               target="_blank"
               class="flex items-center justify-center w-16 h-16 bg-primary text-white rounded-2xl hover:opacity-90 transition-all shadow-xl shadow-primary/20 active:scale-90">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.588-5.946 0-6.556 5.332-11.887 11.887-11.887 3.18 0 6.171 1.242 8.425 3.496 2.257 2.253 3.496 5.244 3.496 8.425 0 6.557-5.331 11.89-11.887 11.89-2.018 0-4.003-.513-5.753-1.487l-6.267 1.672zm6.208-3.766l.348.206c1.517.896 3.268 1.369 5.068 1.369 5.451 0 9.887-4.436 9.887-9.889 0-2.641-1.03-5.123-2.9-6.992-1.868-1.87-4.35-2.903-6.993-2.903-5.452 0-9.889 4.437-9.889 9.889 0 1.883.53 3.722 1.534 5.312l.226.358-1.001 3.655 3.743-.984z"/>
                </svg>
            </a>
        </div>
    </div>
</article>