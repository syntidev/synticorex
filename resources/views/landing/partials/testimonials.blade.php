{{--
    Testimonials Section — Plan 2+
    Datos: $tenant->settings['business_info']['testimonials']
    Array de { name, title, text, rating (1-5) } — máx 5 items
    Plan 2: muestra solo si hay datos reales
    Plan 3: siempre muestra (placeholder elegante si vacío)
--}}
@php
    $testimonials = collect(data_get($tenant->settings, 'business_info.testimonials', []))
        ->filter(fn($t) => !empty($t['name']) && !empty($t['text']))
        ->take(5)
        ->values();

    $isVision = $tenant->isVision();

    // Plan 2: solo si hay datos — Plan 3: siempre
    if ($testimonials->isEmpty() && !$isVision) return;

    // Plan 3 placeholder cuando no hay testimonios reales
    if ($testimonials->isEmpty() && $isVision) {
        $testimonials = collect([
            ['name' => 'Cliente Satisfecho', 'title' => 'Comprador frecuente', 'text' => 'Excelente atención y productos de primera calidad. Siempre cumplen con lo prometido.', 'rating' => 5],
            ['name' => 'María G.', 'title' => 'Clienta habitual', 'text' => 'Servicio rápido, precios justos y un trato muy profesional. Los recomiendo totalmente.', 'rating' => 5],
            ['name' => 'Carlos R.', 'title' => 'Nuevo cliente', 'text' => 'Fue mi primera compra y quedé muy contento. Sin duda volveré a comprar.', 'rating' => 4],
        ]);
    }
@endphp

<section id="testimonials" class="py-8 sm:py-16 lg:py-24 bg-base-100">
    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
        <div
            id="testimonials-carousel"
            data-carousel='{ "loadingClasses": "opacity-0", "slidesQty": { "xs": 1, "md": 2 } }'
            class="relative flex w-full gap-12 max-lg:flex-col md:gap-16 lg:items-center lg:gap-24"
        >
            {{-- Encabezado + controles --}}
            <div class="lg:w-64 flex-shrink-0">
                <div class="space-y-3 mb-8">
                    <p class="text-primary text-xs font-black uppercase tracking-[0.2em]">Lo que dicen</p>
                    <h2 class="text-base-content text-2xl font-black md:text-3xl leading-tight">
                        Testimonios de<br><span class="text-primary italic">Clientes</span>
                    </h2>
                </div>
                <div class="flex gap-3">
                    <button class="btn btn-square btn-sm carousel-prev btn-primary carousel-disabled:opacity-30 carousel-disabled:btn-outline hover:text-white" disabled>
                        <span class="icon-[tabler--arrow-left] size-4"></span>
                    </button>
                    <button class="btn btn-square btn-sm carousel-next btn-primary carousel-disabled:opacity-30 carousel-disabled:btn-outline hover:text-white">
                        <span class="icon-[tabler--arrow-right] size-4"></span>
                    </button>
                </div>
            </div>

            {{-- Carousel --}}
            <div class="carousel rounded-box flex-1 min-w-0">
                <div class="carousel-body gap-5 opacity-0">
                    @foreach($testimonials as $t)
                    <div class="carousel-slide">
                        <div class="card card-border hover:border-primary transition-all h-full shadow-none duration-300">
                            <div class="card-body gap-4 p-5">

                                {{-- Avatar + nombre --}}
                                <div class="flex items-center gap-3">
                                    <div class="avatar placeholder">
                                        <div class="bg-primary/10 text-primary rounded-full size-10">
                                            <span class="text-sm font-black">{{ strtoupper(substr($t['name'], 0, 1)) }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-base-content font-bold text-sm">{{ $t['name'] }}</h4>
                                        @if(!empty($t['title']))
                                            <p class="text-base-content/60 text-xs">{{ $t['title'] }}</p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Estrellas --}}
                                @php $rating = min(5, max(1, (int)($t['rating'] ?? 5))); @endphp
                                <div class="flex gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $rating)
                                            <span class="icon-[tabler--star-filled] text-warning size-4 shrink-0"></span>
                                        @else
                                            <span class="icon-[tabler--star] text-base-content/20 size-4 shrink-0"></span>
                                        @endif
                                    @endfor
                                </div>

                                {{-- Texto --}}
                                <p class="text-base-content/70 text-sm leading-relaxed">
                                    "{{ $t['text'] }}"
                                </p>

                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</section>
