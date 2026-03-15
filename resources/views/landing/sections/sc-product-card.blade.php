{{-- SYNTIcat — Card de producto reutilizable --}}
@php
    $img = $productImg($product);
    $maxImages = match($tenant->plan->slug ?? 'cat-basico') {
        'cat-anual' => 6,
        'cat-semestral' => 3,
        default => 1,
    };
    $galleryImages = collect($product->galleryImages ?? [])
        ->sortBy('position')
        ->map(fn($g) => asset('storage/tenants/' . $tenant->id . '/' . $g->filename));
    $pmImages = collect([$img])
        ->merge($galleryImages)
        ->filter()
        ->unique()
        ->take($maxImages)
        ->values();
    $hasDiscount = $product->compare_price_usd && $product->compare_price_usd > $product->price_usd;
    $badgeLower  = $product->badge ? strtolower($product->badge) : null;
    $showBadge   = $badgeLower && !($badgeLower === 'promo' && $hasDiscount);
    $badgeCfg    = $showBadge ? match($badgeLower) {
        'popular'   => ['icon' => 'tabler--star-filled',   'bg' => 'bg-amber-500',   'text' => 'text-white', 'label' => 'Popular'],
        'nuevo'     => ['icon' => 'tabler--sparkles',      'bg' => 'bg-emerald-500', 'text' => 'text-white', 'label' => 'Nuevo'],
        'promo'     => ['icon' => 'tabler--tag',           'bg' => 'bg-orange-500',  'text' => 'text-white', 'label' => 'Promo'],
        'destacado' => ['icon' => 'tabler--bolt',          'bg' => 'bg-violet-600',  'text' => 'text-white', 'label' => 'Recomendado'],
        default     => ['icon' => 'tabler--star',          'bg' => 'bg-primary',     'text' => 'text-primary-foreground', 'label' => $product->badge]
    } : null;
    $isHidden = $cardHidden ?? false;
@endphp

<div class="sc-product-card group relative flex flex-col bg-background rounded-2xl border border-foreground/5 overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5 {{ $isHidden ? 'sc-card-hidden' : '' }}"
     data-category="{{ $product->category_name ?? '' }}"
     data-subcategory="{{ $product->subcategory_name ?? '' }}"
     data-name="{{ strtolower($product->name) }}"
     data-search="{{ mb_strtolower(trim(($product->name ?? '') . ' ' . ($product->description ?? '') . ' ' . ($product->category_name ?? '') . ' ' . ($product->subcategory_name ?? ''))) }}"
     @if($isHidden) style="display:none" @endif>

    <div class="sc-card-accent" data-cat="{{ $product->category_name ?? '' }}"></div>

    {{-- IMAGEN 1:1 --}}
    <div class="relative aspect-square overflow-hidden bg-surface m-2.5 rounded-xl cursor-pointer"
         onclick='openPM({{ $product->id }}, @json($product->name), {{ $product->price_usd ?? 0 }}, @json($img), @json($product->description ?? ""), {{ $product->compare_price_usd ?? 0 }}, {{ $product->is_featured ? "true" : "false" }}, @json($product->variants ?? []), @json($pmImages))'>

        @if($img)
            <img src="{{ $img }}" data-src="{{ $img }}" alt="{{ $product->name }}"
                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 sc-lazy"
                 loading="lazy" onerror="this.style.display='none';">
        @else
            <div class="w-full h-full flex flex-col items-center justify-center gap-2">
                <span class="iconify tabler--photo size-10 text-foreground/15"></span>
                <span class="text-[10px] font-medium text-foreground/25 uppercase tracking-widest">Sin imagen</span>
            </div>
        @endif

        @if($product->is_featured ?? false)
            <span class="sc-badge absolute top-2 left-2 z-10 bg-amber-400 text-amber-900 shadow-sm">
                <span class="iconify tabler--star-filled size-3" aria-hidden="true"></span>
                Especial
            </span>
        @endif

        @if($hasDiscount || $badgeCfg)
            <div class="absolute bottom-2 left-2 z-10 flex flex-wrap gap-1">
                @if($hasDiscount)
                    <span class="sc-badge bg-red-500 text-white shadow-sm">
                        <span class="iconify tabler--rosette-discount size-3" aria-hidden="true"></span>
                        Oferta
                    </span>
                @endif
                @if($badgeCfg)
                    <span class="sc-badge {{ $badgeCfg['bg'] }} {{ $badgeCfg['text'] }} shadow-sm">
                        <span class="iconify {{ $badgeCfg['icon'] }} size-3" aria-hidden="true"></span>
                        {{ $badgeCfg['label'] }}
                    </span>
                @endif
            </div>
        @endif

    </div>

    {{-- INFO + CTA --}}
    <div class="flex flex-col flex-1 px-3 pb-3 pt-1 gap-1">
        <h3 class="font-bold text-sm leading-snug line-clamp-2 group-hover:text-primary transition-colors duration-200">
            {{ Str::limit($product->name, 45) }}
        </h3>

        @if($product->category_name)
        <p class="text-[10px] text-foreground/35 font-semibold uppercase tracking-wider">
            {{ $product->category_name }}@if($product->subcategory_name) / {{ $product->subcategory_name }}@endif
        </p>
        @endif

        @if($product->description)
            <p class="text-[11px] text-foreground/45 leading-snug line-clamp-1 font-normal">
                {{ Str::limit($product->description, 55) }}
            </p>
        @endif

        <div class="flex items-end justify-between gap-2 mt-auto pt-2">

            @if(!$hidePrice)
            <div class="flex flex-col min-w-0">
                <span class="text-base font-black tracking-tight leading-none" data-price-usd="{{ $product->price_usd ?? 0 }}">
                    <span class="text-[10px] font-medium opacity-40 mr-0.5 align-middle">{{ $currencySymbol }}</span>0.00
                </span>
                @if($hasDiscount)
                    <span class="text-[11px] text-red-400/80 line-through leading-none mt-0.5" data-price-usd="{{ $product->compare_price_usd }}">
                        <span class="text-[9px] opacity-70 mr-0.5">{{ $currencySymbol }}</span>0.00
                    </span>
                @endif
            </div>
            @endif

            <div id="qty-row-{{ $product->id }}" class="flex items-center gap-1 bg-surface rounded-full px-1.5 py-1" style="display:none!important">
                <button class="size-5 rounded-full bg-background flex items-center justify-center text-xs font-bold leading-none"
                        onclick="changeQty({{ $product->id }}, -1)">−</button>
                <span class="text-xs font-black min-w-[14px] text-center" id="qty-val-{{ $product->id }}">1</span>
                <button class="size-5 rounded-full bg-primary text-primary-foreground flex items-center justify-center text-xs font-bold leading-none"
                        onclick="changeQty({{ $product->id }}, 1)">+</button>
            </div>

            <button onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price_usd ?? 0 }}, '{{ $img }}')"
                    class="sc-add-btn bg-primary text-primary-foreground shrink-0"
                    title="Agregar al pedido">
                <svg aria-hidden="true" focusable="false" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
            </button>

        </div>
    </div>

</div>
