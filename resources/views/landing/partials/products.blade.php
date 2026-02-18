{{-- Products Section Partial --}}
<section id="productos" class="py-16 px-4">
    <div class="container mx-auto">
        <h2 class="text-3xl font-bold text-center mb-12" style="color: var(--color-primary);">
            Nuestros Productos
        </h2>
        
        {{-- Featured Products First --}}
        @php
            $featuredProducts = $products->where('is_featured', true);
            $regularProducts = $products->where('is_featured', false);
        @endphp
        
        @if($featuredProducts->count() > 0)
            <div class="mb-12">
                <h3 class="text-xl font-semibold mb-6 text-center">⭐ Destacados</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($featuredProducts as $product)
                        @include('landing.partials.product-card', ['product' => $product, 'featured' => true])
                    @endforeach
                </div>
            </div>
        @endif
        
        {{-- Regular Products Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($regularProducts as $product)
                @include('landing.partials.product-card', ['product' => $product, 'featured' => false])
            @endforeach
        </div>
    </div>
</section>
