{{-- Product Card — Preline 4.1.2 + Tailwind v4 --}}
<div class="group flex flex-col">

    {{-- IMAGEN / SLIDER --}}
    <div class="relative">
    <div class="aspect-4/4 overflow-hidden rounded-2xl bg-surface">
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
                     class="size-full object-cover rounded-2xl cursor-zoom-in"
                     onclick="openZoom(this.src)"
                     alt="{{ $product->name }}"
                     onerror="this.style.display='none'; this.parentElement.style.display='none';">
            @elseif($product->image_url)
                <img src="{{ $product->image_url }}"
                     class="size-full object-cover rounded-2xl"
                     alt="{{ $product->name }}"
                     loading="lazy">
            @else
                <div class="size-full bg-gradient-to-br from-base-300 to-base-200 flex items-center justify-center rounded-2xl">
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
        <div class="pt-4">

            {{-- Badge con iconos y colores distintivos --}}
            @if($product->badge)
                <div class="mb-2">
                    @php
                        $badgeLower = strtolower($product->badge);
                        $badgeConfig = match($badgeLower) {
                            'hot'       => ['icon' => 'tabler--flame',       'bgColor' => 'bg-red-600',    'textColor' => 'text-white',      'label' => 'Hot'],
                            'new'       => ['icon' => 'tabler--sparkles',    'bgColor' => 'bg-emerald-600','textColor' => 'text-white',      'label' => 'New'],
                            'promo'     => ['icon' => 'tabler--gift',        'bgColor' => 'bg-amber-500',  'textColor' => 'text-white',      'label' => 'Promo'],
                            'destacado' => ['icon' => 'tabler--star-filled', 'bgColor' => 'bg-yellow-400', 'textColor' => 'text-yellow-900', 'label' => 'Destacado'],
                            default     => ['icon' => 'tabler--star',        'bgColor' => 'bg-primary',    'textColor' => 'text-white',      'label' => $product->badge]
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
                <p class="mt-2 font-semibold text-foreground"
                   data-price-usd="{{ $product->price_usd }}">
                    <span class="text-xs font-medium opacity-50 mr-0.5">REF</span>{{ number_format($product->price_usd, 2) }}
                </p>
            @endif

        </div>
    </div>{{-- /relative --}}

    {{-- Botón anclado al fondo del card (mt-auto) --}}
    <div class="mt-auto pt-4">
        @if($hidePrice ?? false)
            {{-- Precio oculto → "Más info" + compartir --}}
            <div class="flex gap-2">
                <a href="#"
                   class="py-2 px-3 flex-1 inline-flex justify-center items-center gap-x-2 text-sm font-medium text-nowrap rounded-xl bg-primary border border-primary-line text-primary-foreground hover:bg-primary-hover focus:outline-hidden focus:bg-primary-focus transition disabled:opacity-50 disabled:pointer-events-none"
                   onclick="return false;">
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
                <a href="#"
                   class="py-2 px-3 flex-1 inline-flex justify-center items-center gap-x-2 text-sm font-medium text-nowrap rounded-xl bg-primary border border-primary-line text-primary-foreground hover:bg-primary-hover focus:outline-hidden focus:bg-primary-focus transition disabled:opacity-50 disabled:pointer-events-none"
                   onclick="return false;">
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
                <a href="#"
                   class="py-2 px-3 flex-1 inline-flex justify-center items-center gap-x-2 text-sm font-medium text-nowrap rounded-xl bg-primary border border-primary-line text-primary-foreground hover:bg-primary-hover focus:outline-hidden focus:bg-primary-focus transition disabled:opacity-50 disabled:pointer-events-none"
                   onclick="return false;">
                    <span class="iconify tabler--brand-whatsapp size-4"></span>
                    WhatsApp
                </a>
                <a href="#"
                   class="py-2 px-3 flex-1 inline-flex justify-center items-center gap-x-2 text-sm font-medium text-nowrap rounded-xl border border-line-2 text-foreground hover:bg-surface focus:outline-hidden transition disabled:opacity-50 disabled:pointer-events-none"
                   onclick="return false;">
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
