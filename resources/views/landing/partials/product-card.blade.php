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
                        <img src="{{ $imageUrl }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
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
                     alt="{{ $product->name }}">
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
                <span class="badge badge-sm {{
                    $product->badge === 'HOT' ? 'badge-error' :
                    ($product->badge === 'NEW' ? 'badge-success' : 'badge-warning')
                }}">
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
                <div class="text-xl font-black text-base-content"
                     data-price-usd="{{ $product->price_usd }}"
                     data-rate="{{ $dollarRate ?? 1 }}">
                    <div class="price-display">
                        <span class="text-xs font-medium opacity-50 mr-1">REF</span>{{ number_format($product->price_usd, 2) }}
                    </div>
                    <div class="price-display-bs hidden">
                        <span class="text-xs font-medium opacity-50 mr-1">Bs.</span>{{ number_format($product->price_usd * ($dollarRate ?? 1), 2) }}
                    </div>
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
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.588-5.946 0-6.556 5.332-11.887 11.887-11.887 3.18 0 6.171 1.242 8.425 3.496 2.257 2.253 3.496 5.244 3.496 8.425 0 6.557-5.331 11.89-11.887 11.89-2.018 0-4.003-.513-5.753-1.487l-6.267 1.672zm6.208-3.766l.348.206c1.517.896 3.268 1.369 5.068 1.369 5.451 0 9.887-4.436 9.887-9.889 0-2.641-1.03-5.123-2.9-6.992-1.868-1.87-4.35-2.903-6.993-2.903-5.452 0-9.889 4.437-9.889 9.889 0 1.883.53 3.722 1.534 5.312l.226.358-1.001 3.655 3.743-.984z"/></svg>
                    Escribir
                </a>
            @else
                {{-- Sin precio → WhatsApp + Más info --}}
                <a href="#" class="btn btn-primary btn-sm flex-1" onclick="return false;">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.588-5.946 0-6.556 5.332-11.887 11.887-11.887 3.18 0 6.171 1.242 8.425 3.496 2.257 2.253 3.496 5.244 3.496 8.425 0 6.557-5.331 11.89-11.887 11.89-2.018 0-4.003-.513-5.753-1.487l-6.267 1.672zm6.208-3.766l.348.206c1.517.896 3.268 1.369 5.068 1.369 5.451 0 9.887-4.436 9.887-9.889 0-2.641-1.03-5.123-2.9-6.992-1.868-1.87-4.35-2.903-6.993-2.903-5.452 0-9.889 4.437-9.889 9.889 0 1.883.53 3.722 1.534 5.312l.226.358-1.001 3.655 3.743-.984z"/></svg>
                    WhatsApp
                </a>
                <a href="#" class="btn btn-secondary btn-soft btn-sm flex-1" onclick="return false;">
                    Más info
                </a>
            @endif
        </div>
    </div>

</div>
