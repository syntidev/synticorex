{{-- Path: resources/views/landing/partials/product-card.blade.php --}}
<article class="group bg-white rounded-[3.5rem] p-10 shadow-sm border border-gray-100 flex flex-col h-full transition-all duration-500 hover:shadow-2xl">
    
    {{-- Imagen / Slider --}}
    <div class="relative aspect-square overflow-hidden rounded-[2.5rem] bg-gray-50 mb-12">
        @php
            $isPlan3 = isset($plan) && (int) $plan->id === 3;
            $galleryImages = $product->relationLoaded('galleryImages') ? $product->galleryImages : collect();
            $hasGallery = $isPlan3 && $galleryImages->isNotEmpty();
            $allImages = collect();
            
            if ($hasGallery && isset($tenant)) {
                // Main image first
                if ($product->image_filename) {
                    $allImages->push(asset('storage/tenants/' . $tenant->id . '/' . $product->image_filename));
                }
                // Gallery images
                foreach ($galleryImages as $gi) {
                    $allImages->push(asset('storage/tenants/' . $tenant->id . '/' . $gi->image_filename));
                }
            }
            $sliderId = 'slider-' . ($product->id ?? uniqid());
        @endphp

        @if($hasGallery && $allImages->count() > 1)
            {{-- CSS-only Slider for Plan 3 with multiple images --}}
            <div class="product-slider relative w-full h-full" id="{{ $sliderId }}">
                @foreach($allImages as $idx => $imageUrl)
                    <div class="product-slide absolute inset-0 transition-opacity duration-500 {{ $idx === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}" data-slide="{{ $idx }}">
                        <img src="{{ $imageUrl }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
                    </div>
                @endforeach

                {{-- Navigation Arrows --}}
                <button type="button" onclick="changeSlide('{{ $sliderId }}', -1)" 
                    class="absolute left-3 top-1/2 -translate-y-1/2 z-20 w-10 h-10 bg-white/80 hover:bg-white rounded-full flex items-center justify-center shadow-lg transition-all opacity-0 group-hover:opacity-100">
                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button type="button" onclick="changeSlide('{{ $sliderId }}', 1)" 
                    class="absolute right-3 top-1/2 -translate-y-1/2 z-20 w-10 h-10 bg-white/80 hover:bg-white rounded-full flex items-center justify-center shadow-lg transition-all opacity-0 group-hover:opacity-100">
                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>

                {{-- Dots --}}
                <div class="absolute bottom-3 left-1/2 -translate-x-1/2 z-20 flex gap-2">
                    @foreach($allImages as $idx => $imageUrl)
                        <button type="button" onclick="goToSlide('{{ $sliderId }}', {{ $idx }})" 
                            class="slider-dot w-2.5 h-2.5 rounded-full transition-all {{ $idx === 0 ? 'bg-primary scale-110' : 'bg-white/70' }}" data-dot="{{ $idx }}"></button>
                    @endforeach
                </div>
            </div>
        @else
            {{-- Single image (Plans 1, 2, or Plan 3 without gallery) --}}
            @if($product->image_filename)
                <img src="{{ asset('storage/tenants/' . ($tenant->id ?? '') . '/' . $product->image_filename) }}" 
                     class="w-full h-full object-cover"
                     alt="{{ $product->name }}">
            @endif
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