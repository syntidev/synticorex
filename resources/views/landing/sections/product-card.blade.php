{{-- Product Card — Diseño Limpio (Preline 4.1.2 + Tailwind v4) --}}
{{-- Ref: imagen con zoom hover, badges texto en pie de foto, info limpia sin card --}}
<div class="group">

    @php
        $isPlan3 = isset($plan) && (int) $plan->id === 3;
        $galleryImages = $product->relationLoaded('galleryImages') ? $product->galleryImages : collect();
        $hasGallery = $isPlan3 && $galleryImages->isNotEmpty();
        $allImages = collect();
        $waNumber = preg_replace('/\D/', '', $tenant->getActiveWhatsapp() ?? '');
        $waBase   = $waNumber ? 'https://wa.me/' . $waNumber : '#';
        $waProductMsg = 'Hola, vi tu vitrina y me interesa: ' . $product->name
                      . ($product->price_usd ? ' — REF ' . number_format($product->price_usd, 2) : '')
                      . '. ¿Está disponible?';

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

    {{-- IMAGEN CON ZOOM --}}
    <div class="relative mb-2 h-96 overflow-hidden rounded-lg bg-surface shadow-lg lg:mb-3">

        {{-- Chip is_featured — top-left, dorado con estrella --}}
        @if($product->is_featured)
            <span class="absolute top-3 left-3 z-30 inline-flex items-center gap-1 rounded-lg bg-amber-400 px-2.5 py-1.5 text-xs font-bold text-amber-900 shadow-md">
                <span class="iconify tabler--star-filled size-3.5" aria-hidden="true"></span>
                Especial
            </span>
        @endif

        {{-- Slider (Plan 3 con galería) --}}
        @if($hasGallery && $allImages->count() > 1)
            <div class="product-slider relative size-full" id="{{ $sliderId }}">
                @foreach($allImages as $idx => $imageUrl)
                    <div class="product-slide absolute inset-0 transition-opacity duration-500 {{ $idx === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}" data-slide="{{ $idx }}">
                        <img src="{{ $imageUrl }}" class="h-full w-full object-cover object-center transition duration-200 group-hover:scale-110" alt="{{ $product->name }}"
                             onerror="this.style.display='none'; this.parentElement.style.display='none';">
                    </div>
                @endforeach

                {{-- Arrows (hover) --}}
                <button type="button" onclick="changeSlide('{{ $sliderId }}', -1)"
                    class="absolute left-2 top-1/2 -translate-y-1/2 z-20 w-8 h-8 bg-white/80 hover:bg-white rounded-full flex items-center justify-center shadow-lg transition-all opacity-0 group-hover:opacity-100 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button type="button" onclick="changeSlide('{{ $sliderId }}', 1)"
                    class="absolute right-2 top-1/2 -translate-y-1/2 z-20 w-8 h-8 bg-white/80 hover:bg-white rounded-full flex items-center justify-center shadow-lg transition-all opacity-0 group-hover:opacity-100 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>

                {{-- Dots --}}
                <div class="absolute bottom-2 left-1/2 -translate-x-1/2 z-20 flex gap-1.5">
                    @foreach($allImages as $idx => $imageUrl)
                        <button type="button" onclick="goToSlide('{{ $sliderId }}', {{ $idx }})"
                            class="slider-dot w-2 h-2 rounded-full transition-all cursor-pointer {{ $idx === 0 ? 'bg-primary scale-110' : 'bg-white/60 hover:bg-white/80' }}" data-dot="{{ $idx }}"></button>
                    @endforeach
                </div>
            </div>
        {{-- Imagen única --}}
        @else
            @if($product->image_filename)
                <img src="{{ asset('storage/tenants/' . ($tenant->id ?? '') . '/' . $product->image_filename) }}"
                     class="h-full w-full object-cover object-center transition duration-200 group-hover:scale-110"
                     alt="{{ $product->name }}"
                     onerror="this.style.display='none'; this.parentElement.style.display='none';">
            @elseif($product->image_url)
                <img src="{{ $product->image_url }}"
                     class="h-full w-full object-cover object-center transition duration-200 group-hover:scale-110"
                     alt="{{ $product->name }}"
                     loading="lazy">
            @else
                <div class="size-full bg-muted flex items-center justify-center">
                    <svg class="w-16 h-16 text-foreground/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            @endif
        @endif

        {{-- Badges en pie de foto — colores sólidos + regla de exclusión promo/descuento --}}
        @php
            $hasDiscount = $product->compare_price_usd && $product->compare_price_usd > $product->price_usd;
            $badgeLower  = $product->badge ? strtolower($product->badge) : null;
            // Si badge es 'promo' y hay descuento activo, el descuento ya lo comunica — suprimir redundancia
            $showBadge   = $badgeLower && !($badgeLower === 'promo' && $hasDiscount);
            $badgeConfig = $showBadge ? match($badgeLower) {
                'popular'   => ['icon' => 'tabler--star-filled', 'bg' => 'bg-amber-500',   'text' => 'text-white', 'label' => 'Popular'],
                'nuevo'     => ['icon' => 'tabler--sparkles',    'bg' => 'bg-emerald-500', 'text' => 'text-white', 'label' => 'Nuevo'],
                'promo'     => ['icon' => 'tabler--tag',         'bg' => 'bg-orange-500',  'text' => 'text-white', 'label' => 'Promo'],
                'destacado' => ['icon' => 'tabler--bolt',        'bg' => 'bg-violet-600',  'text' => 'text-white', 'label' => 'Recomendado'],
                default     => ['icon' => 'tabler--star',        'bg' => 'bg-primary',     'text' => 'text-primary-foreground', 'label' => $product->badge]
            } : null;
        @endphp
        @if($badgeConfig || $hasDiscount)
            <div class="absolute left-2 bottom-2 flex gap-1.5 z-20">
                @if($hasDiscount)
                    <span class="inline-flex items-center gap-1 rounded-lg bg-red-500 px-2.5 py-1.5 text-xs font-bold text-white shadow-sm">
                        <span class="iconify tabler--rosette-discount size-3.5" aria-hidden="true"></span>
                        Oferta
                    </span>
                @endif
                @if($badgeConfig)
                    <span class="inline-flex items-center gap-1 rounded-lg {{ $badgeConfig['bg'] }} {{ $badgeConfig['text'] }} px-2.5 py-1.5 text-xs font-bold shadow-sm">
                        <span class="iconify {{ $badgeConfig['icon'] }} size-3.5" aria-hidden="true"></span>
                        {{ $badgeConfig['label'] }}
                    </span>
                @endif
            </div>
        @endif

    </div>{{-- /imagen --}}

    {{-- INFO: Nombre+Descripción izquierda — Precio derecha --}}
    <div class="flex items-start justify-between gap-2 px-2">
        <div class="flex flex-col">
            <h3 class="text-lg font-bold text-foreground transition duration-100 hover:text-foreground/60 lg:text-xl line-clamp-2">
                {{ Str::limit($product->name, 40) }}
            </h3>
            @if($product->description)
                <span class="text-foreground/50 text-sm line-clamp-1">{{ Str::limit($product->description, 60) }}</span>
            @endif
        </div>

        <div class="flex flex-col items-end shrink-0">
            @if($product->price_usd && !($hidePrice ?? false))
                <span class="font-bold text-foreground/70 lg:text-lg" data-price-usd="{{ $product->price_usd }}">
                    <span class="text-[11px] font-medium opacity-40 mr-0.5">REF</span>{{ number_format($product->price_usd, 2) }}
                </span>
                @if($product->compare_price_usd && $product->compare_price_usd > $product->price_usd)
                    <span class="text-sm text-red-500/70 line-through" data-price-usd="{{ $product->compare_price_usd }}">
                        <span class="text-[10px] font-medium opacity-40 mr-0.5">REF</span>{{ number_format($product->compare_price_usd, 2) }}
                    </span>
                @endif
            @endif
        </div>
    </div>

    {{-- CTA WhatsApp (aparece en hover) --}}
    <div class="px-2 mt-3 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
        @if($hidePrice ?? false)
            <div class="flex gap-2">
                <a href="{{ $waBase }}?text={{ urlencode($waProductMsg) }}"
                   target="_blank" rel="noopener noreferrer"
                   onclick="if(!window.__tenantIsOpen){event.preventDefault();showClosedToast(this.href);}"
                   class="py-2 px-3 flex-1 inline-flex justify-center items-center gap-x-2 text-sm font-medium text-nowrap rounded-xl bg-primary border border-primary-line text-primary-foreground hover:bg-primary-hover shadow-sm focus:outline-hidden focus:bg-primary-focus transition cursor-pointer">
                    Más info
                </a>
                <button type="button"
                        onclick="shareProductCard('{{ addslashes($product->name) }}', null)"
                        class="p-2 rounded-xl border border-line-2 text-muted-foreground-1 hover:bg-surface focus:outline-hidden transition shrink-0 cursor-pointer"
                        title="Compartir">
                    <span class="iconify tabler--share size-4" aria-hidden="true"></span>
                </button>
            </div>
        @elseif($product->price_usd)
            <div class="flex gap-2">
                <a href="{{ $waBase . '?text=' . urlencode($waProductMsg) }}"
                   target="_blank" rel="noopener noreferrer"
                   onclick="if(!window.__tenantIsOpen){event.preventDefault();showClosedToast(this.href);}"
                   class="py-2 px-3 flex-1 inline-flex justify-center items-center gap-x-2 text-sm font-medium text-nowrap rounded-xl bg-primary border border-primary-line text-primary-foreground hover:bg-primary-hover shadow-sm focus:outline-hidden focus:bg-primary-focus transition cursor-pointer">
                    <span class="iconify tabler--brand-whatsapp size-4"></span>
                    Pedir por WhatsApp
                </a>
                <button type="button"
                        onclick="shareProductCard('{{ addslashes($product->name) }}', {{ $product->price_usd }})"
                        class="p-2 rounded-xl border border-line-2 text-muted-foreground-1 hover:bg-surface focus:outline-hidden transition shrink-0 cursor-pointer"
                        title="Compartir">
                    <span class="iconify tabler--share size-4" aria-hidden="true"></span>
                </button>
            </div>
        @else
            <div class="flex gap-2">
                <a href="{{ $waBase . '?text=' . urlencode($waProductMsg) }}"
                   target="_blank" rel="noopener noreferrer"
                   onclick="if(!window.__tenantIsOpen){event.preventDefault();showClosedToast(this.href);}"
                   class="py-2 px-3 flex-1 inline-flex justify-center items-center gap-x-2 text-sm font-medium text-nowrap rounded-xl bg-primary border border-primary-line text-primary-foreground hover:bg-primary-hover shadow-sm focus:outline-hidden focus:bg-primary-focus transition cursor-pointer">
                    <span class="iconify tabler--brand-whatsapp size-4"></span>
                    WhatsApp
                </a>
                <a href="{{ $waBase . '?text=' . urlencode($waProductMsg) }}"
                   target="_blank" rel="noopener noreferrer"
                   onclick="if(!window.__tenantIsOpen){event.preventDefault();showClosedToast(this.href);}"
                   class="py-2 px-3 flex-1 inline-flex justify-center items-center gap-x-2 text-sm font-medium text-nowrap rounded-xl border border-line-2 text-foreground hover:bg-surface focus:outline-hidden transition cursor-pointer">
                    Más info
                </a>
                <button type="button"
                        onclick="shareProductCard('{{ addslashes($product->name) }}', null)"
                        class="p-2 rounded-xl border border-line-2 text-muted-foreground-1 hover:bg-surface focus:outline-hidden transition shrink-0 cursor-pointer"
                        title="Compartir">
                    <span class="iconify tabler--share size-4" aria-hidden="true"></span>
                </button>
            </div>
        @endif
    </div>

</div>{{-- /group --}}

<div id="zoom-overlay" class="hidden fixed inset-0 z-[200] bg-black/80 flex items-center justify-center p-4" onclick="closeZoom()">
    <button onclick="closeZoom()" class="absolute top-4 right-4 text-white/80 hover:text-white">
        <span class="iconify tabler--x size-8"></span>
    </button>
    <img id="zoom-img" src="" class="max-w-full max-h-[90vh] rounded-2xl shadow-2xl object-contain" onclick="event.stopPropagation()">
</div>

@once
<script>
function openZoom(src) {
    document.getElementById('zoom-overlay').classList.remove('hidden');
    document.getElementById('zoom-img').src = src;
}
function closeZoom() {
    document.getElementById('zoom-overlay').classList.add('hidden');
}
function shareProductCard(name, price) {
    const url = window.location.href;
    const business = {{ Js::from($tenant->business_name ?? '') }};
    const text = name + (price ? ' \u2014 REF ' + Number(price).toFixed(2) : '') + ' en ' + business;
    if (navigator.share) {
        navigator.share({ title: name, text: text, url: url }).catch(function() {});
    } else {
        window.open('https://wa.me/?text=' + encodeURIComponent(text + '\n' + url), '_blank');
    }
}
</script>
@endonce
