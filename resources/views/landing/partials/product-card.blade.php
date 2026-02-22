{{-- Path: resources/views/landing/partials/product-card.blade.php --}}
<article class="group bg-white rounded-[3.5rem] p-10 shadow-sm border border-gray-100 flex flex-col h-full transition-all duration-500 hover:shadow-2xl">
    
    {{-- Imagen con margen inferior generoso --}}
    <div class="relative aspect-square overflow-hidden rounded-[2.5rem] bg-gray-50 mb-12">
        @if($product->image_filename)
            <img src="{{ asset('storage/products/' . $product->image_filename) }}" class="w-full h-full object-cover">
        @endif
    </div>

    {{-- Área de texto con GAP interno de 30px (py-8) --}}
    <div class="px-2 flex flex-col flex-grow py-4">
        <h3 class="text-2xl font-black text-gray-900 mb-6 leading-tight group-hover:text-primary">
            {{ $product->name }}
        </h3>
        
        <p class="text-gray-500 text-sm mb-12 line-clamp-2 leading-relaxed">
            {{ $product->description }}
        </p>
        
        <div class="mt-auto pt-10 border-t border-gray-100 flex items-center justify-between">
            <div class="flex flex-col">
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Precio REF</span>
                <span class="text-3xl font-black text-gray-900 tracking-tighter">
                    ${{ number_format($product->price_usd, 2) }}
                </span>
            </div>
            
            <a href="#" class="w-16 h-16 bg-primary text-white rounded-2xl flex items-center justify-center shadow-lg shadow-primary/20">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.588-5.946 0-6.556 5.332-11.887 11.887-11.887 3.18 0 6.171 1.242 8.425 3.496 2.257 2.253 3.496 5.244 3.496 8.425 0 6.557-5.331 11.89-11.887 11.89-2.018 0-4.003-.513-5.753-1.487l-6.267 1.672zm6.208-3.766l.348.206c1.517.896 3.268 1.369 5.068 1.369 5.451 0 9.887-4.436 9.887-9.889 0-2.641-1.03-5.123-2.9-6.992-1.868-1.87-4.35-2.903-6.993-2.903-5.452 0-9.889 4.437-9.889 9.889 0 1.883.53 3.722 1.534 5.312l.226.358-1.001 3.655 3.743-.984z"/></svg>
            </a>
        </div>
    </div>
</article>