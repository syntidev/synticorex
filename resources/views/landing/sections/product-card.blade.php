{{-- Product Card — Diseño Limpio (Preline 4.1.2 + Tailwind v4) --}}
{{-- Conceptó: imagen con zoom, badges textuales en pie de foto, info sin card --}}
<div class="group">
    {{-- IMAGEN CON ZOOM --}}
    <div class="relative mb-2 overflow-hidden rounded-lg bg-background lg:mb-3">
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

        <div class="aspect-square overflow-hidden rounded-lg bg-surface block h-96">
            {{-- Slider (Plan 3 con galería) --}}
            @if($hasGallery && $allImages->count() > 1)
                <div class="product-slider relative size-full" id="{{ $sliderId }}">
                    @foreach($allImages as $idx => $imageUrl)
                        <div class="product-slide absolute inset-0 transition-opacity duration-500 {{ $idx === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}" data-slide="{{ $idx }}">
                            <img src="{{ $imageUrl }}" class="size-full object-cover rounded-lg group-hover:scale-110 transition duration-200" alt="{{ $product->name }}"
                                 onerror="this.style.display='none'; this.parentElement.style.display='none';">
                        </div>
                    @endforeach

                    {{-- Arrows --}}
                    <button type="button" onclick="changeSlide('{{ $sliderId }}', -1)" class="absolute left-2 top-1/2 -translate-y-1/2 z-20 w-8 h-8 bg-white/80 hover:bg-white rounded-full flex items-center justify-center shadow-lg transition-all opacity-0 group-hover:opacity-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button type="button" onclick="changeSlide('{{ $sliderId }}', 1)" class="absolute right-2 top-1/2 -translate-y-1/2 z-20 w-8 h-8 bg-white/80 hover:bg-white rounded-full flex items-center justify-center shadow-lg transition-all opacity-0 group-hover:opacity-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>

                    {{-- Dots --}}
                    <div class="absolute bottom-2 left-1/2 -translate-x-1/2 z-20 flex gap-1.5">
                        @foreach($allImages as $idx => $imageUrl)
                            <button type="button" onclick="goToSlide('{{ $sliderId }}', {{ $idx }})" class="slider-dot w-2 h-2 rounded-full transition-all {{ $idx === 0 ? 'bg-primary scale-110' : 'bg-white/60 hover:bg-white/80' }}" data-dot="{{ $idx }}"></button>
                        @endforeach
                    </div>
                </div>
            {{-- Single image --}}
            @else
                @if($product->image_filename)
                    <img src="{{ asset('storage/tenants/' . ($tenant->id ?? '') . '/' . $product->image_filename) }}"
                         class="size-full object-cover rounded-lg group-hover:scale-110 transition duration-200"
                         alt="{{ $product->name }}"
                         onerror="this.style.display='none'; this.parentElement.style.display='none';">
                @elseif($product->image_url)
                    <img src="{{ $product->image_url }}"
                         class="size-full object-cover rounded-lg group-hover:scale-110 transition duration-200"
                         alt="{{ $product->name }}"
                         loading="lazy">
                @else
                    <div class="size-full bg-muted flex items-center justify-center rounded-lg">
                        <svg class="w-16 h-16 text-foreground/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif
            @endif
        </div>

        {{-- Badges textuales en pie de foto (sin icons) --}}
        @if($product->discount_percentage || $product->badge)
            <div class="absolute left-0 bottom-2 flex gap-2 z-30">
                @if($product->discount_percentage)
                    <span class="rounded-r-lg bg-red-500 px-3 py-1.5 text-xs font-semibold uppercase tracking-wider text-white">
                        -{{ (int) $product->discount_percentage }}%
                    </span>
                @endif
                @if($product->badge)
                    @php
                        $badgeLower = strtolower($product->badge);
                        $badgeLabel = match($badgeLower) {
                            'popular'   => 'Popular',
                            'nuevo'     => 'Nuevo',
                            'promo'     => 'Promo',
                            'destacado' => 'Destacado',
                            default     => ucfirst($product->badge)
                        };
                    @endphp
                    <span class="rounded-lg bg-white px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-foreground">
                        {{ $badgeLabel }}
                    </span>
                @endif
            </div>
        @endif
    </div>

    {{-- INFO LIMPIA: TÍTULO, MARCA, PRECIO (sin card, solo texto) --}}
    <div class="flex items-start justify-between gap-2 px-0">
        <div class="flex flex-col flex-1">
            <h3 class="text-lg font-bold text-foreground hover:text-primary transition-colors">
                {{ Str::limit($product->name, 40) }}
            </h3>
            @if($product->brand)
                <span class="text-sm text-foreground/60">{{ $product->brand }}</span>
            @endif
        </div>

        <div class="flex flex-col items-end">
            @if($product->price_usd)
                <span class="font-bold text-foreground lg:text-lg">REF {{ number_format($product->price_usd, 2) }}</span>
                @if($product->discount_percentage)
                    <span class="text-xs text-red-500 line-through">REF {{ number_format($product->price_usd / (1 - $product->discount_percentage / 100), 2) }}</span>
                @endif
            @endif
        </div>
    </div>

    {{-- CTA OCULTO (hover) --}}
    <a href="{{ $waBase }}" target="_blank" rel="noopener noreferrer"
       class="inline-flex items-center gap-2 text-sm py-2 px-0 rounded-lg font-medium text-primary hover:text-primary-hover opacity-0 group-hover:opacity-100 transition-opacity duration-200 mt-2">
        <span class="iconify tabler--brand-whatsapp size-4"></span>
        Ver más
    </a>

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

        {{-- Slider (Plan 3 with gallery) --}}
        @if($hasGallery && $allImages->count() > 1)
            <div class="product-slider relative size-full" id="{{ $sliderId }}">
                @foreach($allImages as $idx => $imageUrl)
                    <div class="product-slide absolute inset-0 transition-opacity duration-500 {{ $idx === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}" data-slide="{{ $idx }}">
                        <img src="{{ $imageUrl }}" class="size-full object-cover rounded-2xl" alt="{{ $product->name }}"
                             onerror="this.style.display='none'; this.parentElement.style.display='none';">
                    </div>
                @endforeach

                {{-- Arrows (hover only) --}}
                <button type="button" onclick="changeSlide('{{ $sliderId }}', -1)"
                    class="absolute left-2 top-1/2 -translate-y-1/2 z-20 w-8 h-8 bg-white/80 hover:bg-background rounded-full flex items-center justify-center shadow-lg transition-all opacity-0 group-hover:opacity-100">
                    <svg class="w-4 h-4 text-foreground/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button type="button" onclick="changeSlide('{{ $sliderId }}', 1)"
                    class="absolute right-2 top-1/2 -translate-y-1/2 z-20 w-8 h-8 bg-white/80 hover:bg-background rounded-full flex items-center justify-center shadow-lg transition-all opacity-0 group-hover:opacity-100">
                    <svg class="w-4 h-4 text-foreground/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
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
                     class="size-full object-cover rounded-2xl cursor-zoom-in transition-transform duration-500 group-hover:scale-[1.03]"
                     onclick="openZoom(this.src)"
                     alt="{{ $product->name }}"
                     onerror="this.style.display='none'; this.parentElement.style.display='none';">
            @elseif($product->image_url)
                <img src="{{ $product->image_url }}"
                     class="size-full object-cover rounded-2xl"
                     alt="{{ $product->name }}"
                     loading="lazy">
            @else
                <div class="size-full bg-muted flex items-center justify-center rounded-2xl">
                    <svg class="w-16 h-16 text-foreground/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            @endif
        @endif
    </div>{{-- /aspect-4/4 --}}

        {{-- Chip Destacado — overlay sobre la imagen --}}
        @if($product->is_featured)
            <span class="absolute top-3 left-3 z-30 inline-flex items-center gap-1 py-1 px-2.5 rounded-full text-xs font-bold bg-yellow-400 text-yellow-900 shadow-lg ring-1 ring-yellow-500/40">
                <span class="iconify tabler--star-filled size-3.5 shrink-0"></span>
                Destacado
            </span>
        @endif

        {{-- Título + Precio (bajo la imagen, dentro del relative) --}}
        <div class="px-4 pt-4">

            {{-- Badge con iconos y colores distintivos --}}
            @if($product->badge)
                <div class="mb-2">
                    @php
                        $badgeLower = strtolower($product->badge);
                        $badgeConfig = match($badgeLower) {
                            'popular'   => ['icon' => 'tabler--star-filled', 'bgColor' => 'bg-amber-100',  'textColor' => 'text-amber-700',  'label' => 'Popular'],
                            'nuevo'     => ['icon' => 'tabler--sparkles',    'bgColor' => 'bg-green-100',  'textColor' => 'text-green-700',  'label' => 'Nuevo'],
                            'promo'     => ['icon' => 'tabler--tag',         'bgColor' => 'bg-orange-100', 'textColor' => 'text-orange-700', 'label' => 'Promo'],
                            'destacado' => ['icon' => 'tabler--bolt',        'bgColor' => 'bg-purple-100', 'textColor' => 'text-purple-700', 'label' => 'Recomendado'],
                            default     => ['icon' => 'tabler--star',        'bgColor' => 'bg-primary/10', 'textColor' => 'text-primary',    'label' => $product->badge]
                        };
                    @endphp
                    <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-bold {{ $badgeConfig['bgColor'] }} {{ $badgeConfig['textColor'] }} shadow-md">
                        <span class="iconify {{ $badgeConfig['icon'] }} size-4"></span>
                        {{ $badgeConfig['label'] }}
                    </span>
                </div>
            @endif

            {{-- Nombre --}}
            <h3 class="font-medium md:text-lg text-foreground line-clamp-2 leading-snug">
                {{ $product->name }}
            </h3>

            {{-- Descripción --}}
            @if($product->description)
                <p class="mt-1 text-sm text-muted-foreground-1 line-clamp-2">
                    {{ $product->description }}
                </p>
            @endif

            {{-- Precio: respeta currency toggle + hidePrice --}}
            @if($product->price_usd && !($hidePrice ?? false))
                <p class="mt-2 text-lg font-semibold text-foreground"
                   data-price-usd="{{ $product->price_usd }}">
                    <span class="text-[11px] font-medium opacity-40 mr-0.5">REF</span>{{ number_format($product->price_usd, 2) }}
                </p>
            @endif

        </div>
    </div>{{-- /relative --}}

    {{-- Botón anclado al fondo del card (mt-auto) --}}
    <div class="mt-auto px-4 pt-4 pb-4">
        @if($hidePrice ?? false)
            {{-- Precio oculto → "Más info" + compartir --}}
            <div class="flex gap-2">
                <a href="{{ $waBase }}?text={{ urlencode($waProductMsg) }}"
                   target="_blank" rel="noopener noreferrer"
                   class="py-2 px-3 flex-1 inline-flex justify-center items-center gap-x-2 text-sm font-medium text-nowrap rounded-xl bg-primary border border-primary-line text-primary-foreground hover:bg-primary-hover shadow-sm hover:shadow-[0_4px_12px_color-mix(in_oklch,var(--color-primary)_35%,transparent)] focus:outline-hidden focus:bg-primary-focus transition disabled:opacity-50 disabled:pointer-events-none">
                    Más info
                </a>
                <button type="button"
                        onclick="shareProductCard('{{ addslashes($product->name) }}', null)"
                        class="p-2 rounded-xl border border-line-2 text-muted-foreground-1 hover:bg-surface focus:outline-hidden transition shrink-0"
                        title="Compartir">
                    <span class="iconify tabler--share size-4" aria-hidden="true"></span>
                </button>
            </div>
        @elseif($product->price_usd)
            {{-- Con precio → WhatsApp + compartir --}}
            <div class="flex gap-2">
                <a href="{{ $waBase . '?text=' . urlencode($waProductMsg) }}"
                   target="_blank" rel="noopener noreferrer"
                   class="py-2 px-3 flex-1 inline-flex justify-center items-center gap-x-2 text-sm font-medium text-nowrap rounded-xl bg-primary border border-primary-line text-primary-foreground hover:bg-primary-hover shadow-sm hover:shadow-[0_4px_12px_color-mix(in_oklch,var(--color-primary)_35%,transparent)] focus:outline-hidden focus:bg-primary-focus transition disabled:opacity-50 disabled:pointer-events-none">
                    <span class="iconify tabler--brand-whatsapp size-4"></span>
                    Pedir por WhatsApp
                </a>
                <button type="button"
                        onclick="shareProductCard('{{ addslashes($product->name) }}', {{ $product->price_usd }})"
                        class="p-2 rounded-xl border border-line-2 text-muted-foreground-1 hover:bg-surface focus:outline-hidden transition shrink-0"
                        title="Compartir">
                    <span class="iconify tabler--share size-4" aria-hidden="true"></span>
                </button>
            </div>
        @else
            {{-- Sin precio → WhatsApp + Más info + compartir --}}
            <div class="flex gap-2">
                <a href="{{ $waBase . '?text=' . urlencode($waProductMsg) }}"
                   target="_blank" rel="noopener noreferrer"
                   class="py-2 px-3 flex-1 inline-flex justify-center items-center gap-x-2 text-sm font-medium text-nowrap rounded-xl bg-primary border border-primary-line text-primary-foreground hover:bg-primary-hover shadow-sm hover:shadow-[0_4px_12px_color-mix(in_oklch,var(--color-primary)_35%,transparent)] focus:outline-hidden focus:bg-primary-focus transition disabled:opacity-50 disabled:pointer-events-none">
                    <span class="iconify tabler--brand-whatsapp size-4"></span>
                    WhatsApp
                </a>
                <a href="{{ $waBase . '?text=' . urlencode($waProductMsg) }}"
                   target="_blank" rel="noopener noreferrer"
                   class="py-2 px-3 flex-1 inline-flex justify-center items-center gap-x-2 text-sm font-medium text-nowrap rounded-xl border border-line-2 text-foreground hover:bg-surface focus:outline-hidden transition disabled:opacity-50 disabled:pointer-events-none">
                    Más info
                </a>
                <button type="button"
                        onclick="shareProductCard('{{ addslashes($product->name) }}', null)"
                        class="p-2 rounded-xl border border-line-2 text-muted-foreground-1 hover:bg-surface focus:outline-hidden transition shrink-0"
                        title="Compartir">
                    <span class="iconify tabler--share size-4" aria-hidden="true"></span>
                </button>
            </div>
        @endif
    </div>

</div>{{-- /group flex flex-col --}}

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
