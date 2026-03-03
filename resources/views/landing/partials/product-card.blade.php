{{-- Path: resources/views/landing/partials/product-card.blade.php --}}
{{-- Rediseño con FlyonUI .card - Estructura optimizada para ecommerce --}}
<div class="card sm:max-w-sm bg-base-100 shadow-sm hover:shadow-lg transition-shadow duration-300">

    {{-- IMAGEN / SLIDER (65% del card) --}}
    <figure class="relative overflow-hidden bg-base-200 h-64">
        @php
            $isPlan3 = isset($plan) && (int) $plan->id === 3;
            $galleryImages = $product->relationLoaded('galleryImages') ? $product->galleryImages : collect();
            $hasGallery = $isPlan3 && $galleryImages->isNotEmpty();
            $allImages = collect();

            if ($hasGallery && isset($tenant)) {
                if ($product->image_filename) {
                    $allImages->push(asset('storage/tenants/' . $tenant->id . '/' . $product->image_filename));
                }
                foreach ($galleryImages as $gi) {
                    $allImages->push(asset('storage/tenants/' . $tenant->id . '/' . $gi->image_filename));
                }
            }
            $sliderId = 'slider-' . ($product->id ?? uniqid());
        @endphp

        {{-- Slider (Plan 3 with gallery) --}}
        @if($hasGallery && $allImages->count() > 1)
            <div class="product-slider relative w-full h-full" id="{{ $sliderId }}">
                @foreach($allImages as $idx => $imageUrl)
                    <div class="product-slide absolute inset-0 transition-opacity duration-500 {{ $idx === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}" data-slide="{{ $idx }}">
                        <img src="{{ $imageUrl }}" class="w-full h-full object-cover" alt="{{ $product->name }}"
                             onerror="this.style.display='none'; this.parentElement.style.display='none';">
                    </div>
                @endforeach

                {{-- Arrows (hover only) --}}
                <button type="button" onclick="changeSlide('{{ $sliderId }}', -1)"
                    class="absolute left-2 top-1/2 -translate-y-1/2 z-20 w-8 h-8 bg-white/80 hover:bg-white rounded-full flex items-center justify-center shadow-lg transition-all opacity-0 group-hover:opacity-100">
                    <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button type="button" onclick="changeSlide('{{ $sliderId }}', 1)"
                    class="absolute right-2 top-1/2 -translate-y-1/2 z-20 w-8 h-8 bg-white/80 hover:bg-white rounded-full flex items-center justify-center shadow-lg transition-all opacity-0 group-hover:opacity-100">
                    <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>

                {{-- Dots --}}
                <div class="absolute bottom-2 left-1/2 -translate-x-1/2 z-20 flex gap-1.5">
                    @foreach($allImages as $idx => $imageUrl)
                        <button type="button" onclick="goToSlide('{{ $sliderId }}', {{ $idx }})"
                            class="slider-dot w-2 h-2 rounded-full transition-all {{ $idx === 0 ? 'bg-primary scale-110' : 'bg-white/60 hover:bg-white/80' }}" data-dot="{{ $idx }}"></button>
                    @endforeach
                </div>
            </div>
        {{-- Single image (Plans 1, 2, or Plan 3 without gallery) --}}
        @else
            @if($product->image_filename)
                <img src="{{ asset('storage/tenants/' . ($tenant->id ?? '') . '/' . $product->image_filename) }}"
                     class="w-full h-full object-cover"
                     alt="{{ $product->name }}"
                     onerror="this.style.display='none'; this.parentElement.style.display='none';">
            @elseif($product->image_url)
                <img src="{{ $product->image_url }}"
                     class="w-full h-full object-cover"
                     alt="{{ $product->name }}"
                     loading="lazy">
            @else
                <div class="w-full h-full bg-gradient-to-br from-base-300 to-base-200 flex items-center justify-center">
                    <svg class="w-16 h-16 text-base-content/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            @endif
        @endif
    </figure>

    {{-- CONTENIDO (35% del card) --}}
    <div class="card-body gap-3 p-4">

        {{-- Badge --}}
        @if($product->badge)
            <div class="flex gap-2">
                <span class="badge badge-sm badge-accent">
                    {{ $product->badge }}
                </span>
            </div>
        @endif

        {{-- Título --}}
        <h5 class="card-title text-lg font-bold line-clamp-2 leading-tight">
            {{ $product->name }}
        </h5>

        {{-- Descripción --}}
        @if($product->description)
            <p class="text-sm text-base-content/70 line-clamp-2">
                {{ $product->description }}
            </p>
        @endif

        {{-- Precio: se oculta si el tenant activó "Ocultar precio" --}}
        @if($product->price_usd && !($hidePrice ?? false))
            <div class="pt-2 border-t border-base-200">
                <div class="text-xl font-black text-accent"
                     data-price-usd="{{ $product->price_usd }}">
                    <span class="text-xs font-medium opacity-50 mr-1">REF</span>{{ number_format($product->price_usd, 2) }}
                </div>
            </div>
        @endif

        {{-- Botones --}}
        <div class="card-actions justify-stretch pt-2">
            @if($hidePrice ?? false)
                {{-- Precio oculto → solo "Más info" --}}
                <a href="#" class="btn btn-primary btn-sm flex-1" onclick="return false;">
                    Más info
                </a>
            @elseif($product->price_usd)
                {{-- Con precio → botón WhatsApp --}}
                <a href="#" class="btn btn-primary btn-sm flex-1" onclick="return false;">
                    <span class="iconify tabler--brand-whatsapp size-4"></span>
                    Pedir por WhatsApp
                </a>
            @else
                {{-- Sin precio → WhatsApp + Más info --}}
                <a href="#" class="btn btn-primary btn-sm flex-1" onclick="return false;">
                    <span class="iconify tabler--brand-whatsapp size-4"></span>
                    Pedir por WhatsApp
                </a>
                <a href="#" class="btn btn-secondary btn-soft btn-sm flex-1" onclick="return false;">
                    Más info
                </a>
            @endif
        </div>
    </div>

</div>
