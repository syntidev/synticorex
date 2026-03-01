{{-- Path: resources/views/landing/partials/products.blade.php --}}
@php
    $defaultVisible = 6;                                   // Always show 6 by default
    $planLimit      = (int) ($plan->products_limit ?? 6);  // Absolute cap per plan
    $displayProducts = $products->take($planLimit);         // Cap at plan limit
    $visible         = $displayProducts->take($defaultVisible);
    $hidden          = $displayProducts->slice($defaultVisible);
    $hasMore         = $planLimit > $defaultVisible && $hidden->count() > 0;
@endphp

<section id="productos" class="py-40 bg-base-100"> {{-- Aumentamos padding de sección --}}
    <div class="container mx-auto px-8 md:px-16">
        
        <div class="text-center mb-32"> {{-- Más espacio bajo el título principal --}}
            <h2 class="text-4xl md:text-6xl font-black text-base-content mb-8 tracking-tighter">
                Nuestros <span class="text-primary italic">Productos</span>
            </h2>
            <div class="w-24 h-2 bg-primary mx-auto rounded-full"></div>
        </div>

        {{-- GRID CON GAP DE SEGURIDAD (gap-y-32 para evitar el efecto siamés) --}}
        <div id="product-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-16 md:gap-y-32 items-stretch mb-32">
            {{-- Primeros 6 — siempre visibles --}}
            @foreach($visible as $product)
                @include('landing.partials.product-card', ['product' => $product, 'tenant' => $tenant, 'plan' => $plan])
            @endforeach

            {{-- Productos adicionales (Plan 2: hasta 12, Plan 3: hasta 18) — ocultos por defecto --}}
            @if($hasMore)
                @foreach($hidden as $product)
                    <div class="product-extra hidden">
                        @include('landing.partials.product-card', ['product' => $product, 'tenant' => $tenant, 'plan' => $plan])
                    </div>
                @endforeach
            @endif
        </div>

        {{-- Botón "Ver más" — solo si hay productos extra (Plan 2/3), carga 3 en 3 --}}
        @if($hasMore)
            @php $firstBatch = min(3, $hidden->count()); @endphp
            <div id="load-more-products-container" class="mt-20 text-center">
                <button
                    id="btn-load-more-products"
                    onclick="loadMoreProducts(this)"
                    class="group inline-flex items-center gap-4 px-12 py-5 bg-primary text-white font-black rounded-2xl shadow-2xl shadow-primary/40 active:scale-95 transition-all">
                    <span class="btn-label uppercase tracking-[0.3em] text-[10px]">Ver {{ $firstBatch }} producto{{ $firstBatch > 1 ? 's' : '' }} más</span>
                    <svg class="w-4 h-4 btn-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            </div>

            @push('scripts')
            <script>
                function loadMoreProducts(btn) {
                    const hiddenItems = Array.from(
                        document.querySelectorAll('#product-grid .product-extra.hidden')
                    );
                    if (!hiddenItems.length) return;

                    const batch = hiddenItems.slice(0, 3);
                    batch.forEach(el => {
                        el.classList.remove('hidden');
                        el.style.opacity = '0';
                        el.style.transform = 'translateY(20px)';
                        requestAnimationFrame(() => {
                            requestAnimationFrame(() => {
                                el.style.transition = 'opacity 300ms ease, transform 300ms ease';
                                el.style.opacity = '1';
                                el.style.transform = 'translateY(0)';
                            });
                        });
                    });

                    const remaining = hiddenItems.length - batch.length;
                    if (remaining === 0) {
                        document.getElementById('load-more-products-container').style.display = 'none';
                    } else {
                        const next = Math.min(3, remaining);
                        btn.querySelector('.btn-label').textContent =
                            'Ver ' + next + ' producto' + (next > 1 ? 's' : '') + ' más';
                    }
                }
            </script>
            @endpush
        @endif
    </div>
</section>

{{-- Product Slider JS (Plan 3 / VISIÓN) --}}
@if(isset($plan) && (int) $plan->id === 3)
@push('scripts')
<script>
    // Track current slide per slider
    const sliderState = {};

    function getSlides(sliderId) {
        return document.querySelectorAll(`#${sliderId} .product-slide`);
    }

    function getDots(sliderId) {
        return document.querySelectorAll(`#${sliderId} .slider-dot`);
    }

    function goToSlide(sliderId, index) {
        const slides = getSlides(sliderId);
        const dots = getDots(sliderId);
        if (slides.length === 0) return;

        // Wrap around
        if (index < 0) index = slides.length - 1;
        if (index >= slides.length) index = 0;

        slides.forEach((slide, i) => {
            slide.classList.toggle('opacity-100', i === index);
            slide.classList.toggle('opacity-0', i !== index);
            slide.classList.toggle('z-10', i === index);
            slide.classList.toggle('z-0', i !== index);
        });

        dots.forEach((dot, i) => {
            dot.classList.toggle('bg-primary', i === index);
            dot.classList.toggle('scale-110', i === index);
            dot.classList.toggle('bg-white/70', i !== index);
        });

        sliderState[sliderId] = index;
    }

    function changeSlide(sliderId, direction) {
        const current = sliderState[sliderId] || 0;
        goToSlide(sliderId, current + direction);
    }
</script>
@endpush
@endif