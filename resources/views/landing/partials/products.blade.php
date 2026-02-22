{{-- Path: resources/views/landing/partials/products.blade.php --}}
@php
    $limit = 6; // Límite estricto del Plan Oportunidad
@endphp

<section id="productos" class="py-40 bg-gray-50/30"> {{-- Aumentamos padding de sección --}}
    <div class="container mx-auto px-8 md:px-16">
        
        <div class="text-center mb-32"> {{-- Más espacio bajo el título principal --}}
            <h2 class="text-4xl md:text-6xl font-black text-gray-900 mb-8 tracking-tighter">
                Nuestros <span class="text-primary italic">Productos</span>
            </h2>
            <div class="w-24 h-2 bg-primary mx-auto rounded-full"></div>
        </div>

        {{-- GRID CON GAP DE SEGURIDAD (gap-y-32 para evitar el efecto siamés) --}}
        <div id="product-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-16 md:gap-y-32 items-stretch mb-32">
            @foreach($products->take($limit) as $product)
                @include('landing.partials.product-card', ['product' => $product])
            @endforeach
        </div>

        {{-- EL BOTÓN SOLO EXISTE SI HAY MÁS DE 6 PRODUCTOS --}}
        @if($products->count() > 6)
            <div id="load-more-container" class="mt-20 text-center"> 
                <button class="group inline-flex items-center gap-4 px-12 py-5 bg-primary text-white font-black rounded-2xl shadow-2xl shadow-primary/40 active:scale-95 transition-all">
                    <span class="uppercase tracking-[0.3em] text-[10px]">Ver Catálogo Completo</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            </div>
        @endif
    </div>
</section>