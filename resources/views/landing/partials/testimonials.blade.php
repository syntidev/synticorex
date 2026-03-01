{{--
    Testimonials Section — FlyonUI Carousel oficial
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
@endphp

<section id="testimonios" class="py-8 sm:py-16 lg:py-24">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div id="multi-slide"
      data-carousel='{ "loadingClasses": "opacity-0", "slidesQty": { "xs": 1, "md": 2 } }'
      class="relative flex w-full gap-12 max-lg:flex-col md:gap-16 lg:items-center lg:gap-24">
      <div>
        <div class="space-y-4">
          <p class="text-primary text-sm font-medium uppercase">Lo que dicen</p>
          <h2 class="text-base-content text-2xl font-semibold md:text-3xl lg:text-4xl">
            Testimonios de <span class="text-primary italic">Clientes</span>
          </h2>
        </div>
        <div class="mt-10 flex gap-4">
          <button class="btn btn-square btn-sm carousel-prev btn-primary carousel-disabled:opacity-100 carousel-disabled:btn-outline" disabled>
            <span class="icon-[tabler--arrow-left] size-5"></span>
          </button>
          <button class="btn btn-square btn-sm carousel-next btn-primary carousel-disabled:opacity-100 carousel-disabled:btn-outline">
            <span class="icon-[tabler--arrow-right] size-5"></span>
          </button>
        </div>
      </div>
      <div class="carousel rounded-box">
        <div class="carousel-body gap-6 opacity-0">
          @forelse($testimonials as $t)
          <div class="carousel-slide">
            <div class="card card-border hover:border-primary transition-border h-full shadow-none duration-300">
              <div class="card-body gap-5">
                <div class="flex items-center gap-3">
                  <div class="avatar">
                    <div class="size-10 rounded-full">
                      <img src="{{ $t['avatar_url'] ?? 'https://cdn.flyonui.com/fy-assets/avatar/avatar-17.png' }}"
                           alt="{{ $t['name'] }}">
                    </div>
                  </div>
                  <div>
                    <h4 class="text-base-content font-medium">{{ $t['name'] }}</h4>
                    <p class="text-base-content/80 text-sm">
                      {{ $t['title'] ?? '' }}
                      @if(!empty($t['company']))
                        en <span class="text-base-content font-semibold">{{ $t['company'] }}</span>
                      @endif
                    </p>
                  </div>
                </div>
                <div class="flex gap-1">
                  @php $rating = min(5, max(1, (int)($t['rating'] ?? 5))); @endphp
                  @for($i = 1; $i <= 5; $i++)
                    <span class="icon-[tabler--star-filled] text-warning size-6 shrink-0"
                          @if($i > $rating) style="opacity:0.3" @endif></span>
                  @endfor
                </div>
                <p class="text-base-content/80">{{ $t['text'] }}</p>
              </div>
            </div>
          </div>
          @empty
          <div class="carousel-slide">
            <div class="card card-border h-full shadow-none">
              <div class="card-body gap-5">
                <div class="flex items-center gap-3">
                  <div class="avatar"><div class="size-10 rounded-full">
                    <img src="https://cdn.flyonui.com/fy-assets/avatar/avatar-17.png" alt="Cliente">
                  </div></div>
                  <div>
                    <h4 class="text-base-content font-medium">María González</h4>
                    <p class="text-base-content/80 text-sm">Cliente satisfecha</p>
                  </div>
                </div>
                <div class="flex gap-1">
                  @for($i = 1; $i <= 5; $i++)
                    <span class="icon-[tabler--star-filled] text-warning size-6 shrink-0"></span>
                  @endfor
                </div>
                <p class="text-base-content/80">
                  "Excelente servicio, superaron todas mis expectativas.
                  100% recomendados para cualquier negocio venezolano."
                </p>
              </div>
            </div>
          </div>
          <div class="carousel-slide">
            <div class="card card-border h-full shadow-none">
              <div class="card-body gap-5">
                <div class="flex items-center gap-3">
                  <div class="avatar"><div class="size-10 rounded-full">
                    <img src="https://cdn.flyonui.com/fy-assets/avatar/avatar-5.png" alt="Cliente">
                  </div></div>
                  <div>
                    <h4 class="text-base-content font-medium">Carlos Rodríguez</h4>
                    <p class="text-base-content/80 text-sm">Emprendedor</p>
                  </div>
                </div>
                <div class="flex gap-1">
                  @for($i = 1; $i <= 5; $i++)
                    <span class="icon-[tabler--star-filled] text-warning size-6 shrink-0"></span>
                  @endfor
                </div>
                <p class="text-base-content/80">
                  "La mejor inversión para mi negocio.
                  Profesionales, rápidos y con resultados reales."
                </p>
              </div>
            </div>
          </div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</section>
