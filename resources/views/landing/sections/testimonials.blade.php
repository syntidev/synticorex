{{--
    Testimonials Section — Preline 4.1.2 / Tailwind v4
    Slider controlado — datos dinámicos desde $tenant->settings
--}}
@php
    $testimonials = collect(data_get($tenant->settings, 'business_info.testimonials', []))
        ->filter(fn($t) => !empty($t['name']) && !empty($t['text']))
        ->values();
@endphp

@if($testimonials->isNotEmpty())
<section id="testimonials" class="relative py-10 sm:py-16 lg:py-24 bg-background overflow-hidden">
  {{-- Decorativo --}}
  <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
    <div class="absolute -bottom-32 -left-32 size-96 rounded-full opacity-[0.04] blur-3xl"
         style="background:var(--color-primary)"></div>
  </div>
  <div class="max-w-[85rem] px-4 sm:px-6 lg:px-8 mx-auto">

    {{-- Encabezado --}}
    <div class="text-center mb-12 space-y-3">
      <p class="text-primary text-sm font-medium uppercase tracking-wide">
        {{ $customization->getContentBlock('testimonials', 'eyebrow') ?: 'Lo que dicen' }}
      </p>
      <h2 class="text-foreground text-2xl font-semibold md:text-3xl lg:text-4xl"
          style="text-shadow: 0 4px 24px color-mix(in oklch, var(--color-foreground) 15%, transparent), 0 1px 4px color-mix(in oklch, var(--color-foreground) 8%, transparent);">
        {!! $customization->getSectionTitle('testimonials', 'Testimonios de <span class="text-primary italic">Clientes</span>') !!}
      </h2>
      <div class="w-16 h-0.5 mx-auto mt-4 rounded-full"
           style="background:var(--color-primary);box-shadow:0 0 12px 2px color-mix(in oklch,var(--color-primary) 60%,transparent)"></div>
    </div>

    {{-- Slider container --}}
    <div class="relative group">
      
      {{-- Track con overflow hidden --}}
      <div class="overflow-hidden rounded-2xl">
        <div id="testimonials-track" class="flex gap-6 transition-transform duration-500 ease-out">
          {{-- Duplicar para efecto infinito --}}
          @foreach([0, 1] as $_)
            @foreach($testimonials as $testim)
            @php
                $rating   = max(1, min(5, (int)($testim['rating'] ?? 5)));
                $nameParts = array_slice(explode(' ', trim($testim['name'])), 0, 2);
                $initials  = implode('+', array_map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)), $nameParts));
            @endphp
            <div class="flex flex-col w-72 sm:w-80 lg:w-96 shrink-0 bg-card rounded-2xl border border-card-line shadow-sm hover:shadow-md transition-all duration-200 p-6 gap-4">

              {{-- Comillas + texto --}}
              <div class="relative">
                <span class="text-primary/20 text-6xl font-serif leading-none absolute -top-1 -left-1 select-none" aria-hidden="true">“</span>
                <p class="text-foreground/80 text-sm leading-relaxed pt-5 line-clamp-4">{{ $testim['text'] }}</p>
              </div>

              {{-- Estrellas --}}
              <div class="flex gap-0.5">
                @for($s = 1; $s <= 5; $s++)
                  <span class="iconify {{ $s <= $rating ? 'tabler--star-filled text-yellow-400' : 'tabler--star text-gray-300' }} size-4" aria-hidden="true"></span>
                @endfor
              </div>

              {{-- Avatar + datos --}}
              <div class="flex items-center gap-3 mt-auto pt-3 border-t border-card-line">
                <div class="size-10 rounded-full bg-primary/10 flex items-center justify-center shrink-0" aria-hidden="true">
                    <span class="text-primary font-semibold text-sm">{{ str_replace('+', '', $initials) }}</span>
                </div>
                <div>
                  <p class="text-sm font-semibold text-foreground leading-tight">{{ $testim['name'] }}</p>
                  @if(!empty($testim['title']))
                  <p class="text-xs text-muted-foreground-1">{{ $testim['title'] }}</p>
                  @endif
                </div>
              </div>

            </div>
            @endforeach
          @endforeach
        </div>
      </div>

      {{-- Botón previo --}}
      <button type="button" onclick="slideTestimonials(-1)"
              class="absolute -left-4 top-1/2 -translate-y-1/2 z-10 w-10 h-10 bg-primary/90 hover:bg-primary text-white rounded-full flex items-center justify-center shadow-lg transition-all opacity-0 group-hover:opacity-100 -translate-x-16 group-hover:translate-x-0">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      </button>

      {{-- Botón siguiente --}}
      <button type="button" onclick="slideTestimonials(1)"
              class="absolute -right-4 top-1/2 -translate-y-1/2 z-10 w-10 h-10 bg-primary/90 hover:bg-primary text-white rounded-full flex items-center justify-center shadow-lg transition-all opacity-0 group-hover:opacity-100 translate-x-16 group-hover:translate-x-0">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      </button>

    </div>

  </div>
</section>

<script>
let testimonialsIndex = 0;
const originalTestimonialsCount = {{ $testimonials->count() }};
const trackEl = document.getElementById('testimonials-track');

function slideTestimonials(direction) {
  testimonialsIndex += direction;
  updateTestimonialsSlide(direction !== 0);
}

function updateTestimonialsSlide(isAnimated = true) {
  if (!trackEl) return;
  
  const cardWidth = trackEl.children[0]?.offsetWidth || 320;
  const gap = 24;
  const offset = -(testimonialsIndex * (cardWidth + gap));
  
  // Mostrar transición suave
  if (isAnimated) {
    trackEl.classList.remove('no-transition');
    trackEl.style.transform = `translateX(${offset}px)`;
  } else {
    // Sin transición para el jump infinito
    trackEl.classList.add('no-transition');
    trackEl.style.transform = `translateX(${offset}px)`;
    // Forzar recalc
    void trackEl.offsetHeight;
    trackEl.classList.remove('no-transition');
  }
  
  // Detectar cuando se alcanza el final del primer set duplicado
  setTimeout(() => {
    if (testimonialsIndex >= originalTestimonialsCount) {
      // Saltamos al inicio sin transición
      testimonialsIndex = 0;
      updateTestimonialsSlide(false);
    } else if (testimonialsIndex < 0) {
      // Saltamos al final sin transición
      testimonialsIndex = originalTestimonialsCount - 1;
      updateTestimonialsSlide(false);
    }
  }, 500); // Esperar a que termine la transición
}

// Agregar estilo dinámico para removar transición
const style = document.createElement('style');
style.textContent = `
  #testimonials-track.no-transition {
    transition: none !important;
  }
`;
document.head.appendChild(style);
</script>
@endif
