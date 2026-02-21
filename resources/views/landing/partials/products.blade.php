{{-- Path: resources/views/landing/partials/products.blade.php --}}
<section id="productos" class="py-32 bg-gray-50/30"> 
    <div class="container mx-auto px-10 md:px-20">
        
        <div class="text-center mb-24">
            <h2 class="text-5xl md:text-7xl font-black text-gray-900 mb-8 tracking-tighter">
                Nuestro <span class="text-primary italic">Catálogo</span>
            </h2>
            <div class="w-24 h-2 bg-primary mx-auto rounded-full mb-6"></div>
        </div>

        {{-- GRID CON ESPACIADO (3 Columnas con Gap-16) --}}
        <div id="product-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-16 md:gap-y-24 items-stretch">
            @foreach($products as $index => $product)
                <div class="product-item transition-all duration-700 {{ $index >= 9 ? 'hidden opacity-0 translate-y-10' : '' }}">
                    @include('landing.partials.product-card', [
                        'product' => $product,
                        'featured' => $product->is_featured ?? false
                    ])
                </div>
            @endforeach
        </div>

        {{-- BOTÓN CON MARGEN DE SEGURIDAD (Color Primary, No Negro) --}}
        @if($products->count() > 9)
            <div id="load-more-container" class="mt-40 text-center pb-20"> 
                <button onclick="loadMoreProducts()" 
                    class="group relative inline-flex items-center gap-6 px-16 py-8 bg-primary text-white font-black rounded-[2.5rem] hover:opacity-90 transition-all shadow-2xl shadow-primary/30 active:scale-95">
                    <span class="uppercase tracking-[0.4em] text-sm">Explorar más artículos</span>
                    <div class="bg-white/20 p-2 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </button>
            </div>
        @endif
    </div>
</section>