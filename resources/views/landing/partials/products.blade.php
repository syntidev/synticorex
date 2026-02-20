{{-- Products Section - FlyonUI + Synti Design System --}}
<section id="productos" class="synti-section-gray">
    <div class="synti-container">
        
        {{-- Section Header --}}
        <div class="synti-section-header">
            <h2 class="synti-section-title">Nuestros Productos</h2>
            <p class="synti-section-subtitle">Explora nuestra selección de productos</p>
        </div>
        
        {{-- Currency Mode Logic --}}
        @php
            $featuredProducts = $products->where('is_featured', true);
            $regularProducts = $products->where('is_featured', false);
            
            $savedMode = $tenant->settings['engine_settings']['currency']['display']['saved_display_mode'] ?? 'reference_only';
            $showReference = in_array($savedMode, ['reference_only', 'both_toggle']);
            $showBolivares = in_array($savedMode, ['bolivares_only', 'both_toggle']);
            $hidePrice = $savedMode === 'hidden';
        @endphp
        
        {{-- Featured Products --}}
        @if($featuredProducts->count() > 0)
            <div class="mb-12 md:mb-16">
                <div class="flex items-center gap-3 mb-6">
                    <span class="text-2xl">⭐</span>
                    <h3 class="text-xl md:text-2xl font-semibold text-neutral-800">Destacados</h3>
                    <span class="badge badge-warning badge-soft text-xs font-medium">
                        {{ $featuredProducts->count() }} productos
                    </span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($featuredProducts as $product)
                        @include('landing.partials.product-card', [
                            'product'        => $product,
                            'featured'       => true,
                            'showReference'  => $showReference,
                            'showBolivares'  => $showBolivares,
                            'hidePrice'      => $hidePrice,
                        ])
                    @endforeach
                </div>
            </div>
            <div class="divider my-8"></div>
        @endif
        
        {{-- Regular Products Grid --}}
        @if($regularProducts->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($regularProducts as $product)
                    @include('landing.partials.product-card', [
                        'product'        => $product,
                        'featured'       => false,
                        'showReference'  => $showReference,
                        'showBolivares'  => $showBolivares,
                        'hidePrice'      => $hidePrice,
                    ])
                @endforeach
            </div>
        @endif
        
    </div>
</section>
