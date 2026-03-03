{{-- Path: resources/views/landing/partials/products.blade.php --}}
@php
    $defaultVisible = 6;                                   // Always show 6 by default
    $planLimit      = (int) ($plan->products_limit ?? 6);  // Absolute cap per plan
    $displayProducts = $products->take($planLimit);         // Cap at plan limit
    $visible         = $displayProducts->take($defaultVisible);
    $hidden          = $displayProducts->slice($defaultVisible);
    $hasMore         = $planLimit > $defaultVisible && $hidden->count() > 0;
@endphp

<section id="products" class="bg-base-100 py-8 sm:py-16 lg:py-24">
    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">

        {{-- Section Header (FlyonUI pattern) --}}
        <div class="mb-12 text-center sm:mb-16 lg:mb-24">
            <h2 class="text-base-content text-2xl font-semibold md:text-3xl lg:text-4xl">
                Nuestros <span class="text-primary italic">Productos</span>
            </h2>
            <div class="w-16 h-1 bg-primary mx-auto mt-4 rounded-full"></div>
            <p class="text-base-content/80 text-xl mt-4">Descubrí lo mejor que tenemos para ofrecerte, seleccionado con calidad y dedicación.</p>
        </div>

        {{-- Product Grid --}}
        <div id="product-grid" class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
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
            <div id="load-more-products-container" class="mt-12 text-center">
                <button
                    id="btn-load-more-products"
                    onclick="loadMoreProducts(this)"
                    class="inline-flex items-center py-2 px-4 rounded-lg font-medium transition-colors bg-blue-600 text-white hover:bg-blue-700">
                    <span class="btn-label">Ver {{ $firstBatch }} producto{{ $firstBatch > 1 ? 's' : '' }} más</span>
                    <span class="icon-[tabler--chevron-down] size-5"></span>
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