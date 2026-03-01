{{--
    Header Top Partial — SYNTIweb
    ─────────────────────────────────────
    Plan requerido : 2 (CRECIMIENTO) o 3 (VISIÓN)
    Guard de plan  : en base.blade.php (@if plan_id >= 2)
    Posición       : fixed top-0 z-[110] — encima del nav principal (z-100)
    Altura         : 40px (h-10)
    Variables      :
      $tenant->settings['business_info']['schedule_display']
      $tenant->settings['contact_info']['phone']
      $tenant->settings['business_info']['delivery_available']
    Fallbacks      : ver @php abajo
--}}

@php
    $schedule = data_get($tenant->settings, 'business_info.schedule_display', 'Lun–Sáb 9:00–18:00');
    $phone    = data_get($tenant->settings, 'contact_info.phone',
                    preg_replace('/[^0-9]/', '', $tenant->whatsapp_number ?? ''));
    $delivery = (bool) data_get($tenant->settings, 'business_info.delivery_available', false);
    $bannerText = data_get($tenant->settings, 'business_info.top_nav_banner', '');

    // Número limpio para tel: y wa.me
    $phoneClean = preg_replace('/[^0-9]/', '', $phone);
    $phoneDisplay = $phone ?: null;
@endphp

<div id="header-top" class="fixed top-0 left-0 right-0 z-[110] h-10 bg-base-200 border-b border-base-content/10 flex items-center transition-transform duration-300" style="opacity: 0; visibility: hidden;">
    <div class="container mx-auto px-4 md:px-6 flex items-center justify-between gap-4 text-xs font-medium">

        {{-- ── Izquierda: Horario ──────────────────────────── --}}
        @if($schedule)
        <div class="flex items-center gap-1.5 text-base-content/60 min-w-0">
            <iconify-icon icon="tabler:clock" width="14" height="14" class="shrink-0 text-primary/70"></iconify-icon>
            <span class="hidden sm:block truncate">{{ $schedule }}</span>
            {{-- Mobile: solo ícono, tooltip nativo --}}
            <span class="sm:hidden" title="{{ $schedule }}"></span>
        </div>
        @endif

        {{-- ── Centro: Banner o Delivery ───────────────────── --}}
        @if($tenant->isVision() && $bannerText)
            <div class="flex items-center gap-1.5 text-primary font-semibold animate-pulse">
                <iconify-icon icon="tabler:campaign" width="14" height="14" class="shrink-0"></iconify-icon>
                <span class="hidden sm:block">{{ $bannerText }}</span>
                <span class="sm:hidden" title="{{ $bannerText }}">📢</span>
            </div>
        @elseif($delivery)
            <div class="hidden md:flex items-center gap-1.5 text-base-content/60">
                <iconify-icon icon="tabler:motorbike" width="14" height="14" class="text-primary/70 shrink-0"></iconify-icon>
                <span>Delivery disponible</span>
            </div>
        @endif

        {{-- ── Derecha: Teléfono ───────────────────────────── --}}
        @if($phone)
        <div class="flex items-center gap-1.5 ml-auto">
            <iconify-icon icon="tabler:phone" width="14" height="14" class="shrink-0 text-primary/70"></iconify-icon>
            {{-- Desktop: número visible con enlace --}}
            <a href="tel:+{{ $phoneClean }}"
               class="hidden sm:block font-medium text-base-content/70 hover:text-primary transition-colors tabular-nums">
                +{{ $phoneClean }}
            </a>
            {{-- Mobile: solo ícono enlazado --}}
            <a href="tel:+{{ $phoneClean }}" class="sm:hidden text-base-content/70 hover:text-primary transition-colors" title="+{{ $phoneClean }}">
                <iconify-icon icon="tabler:phone-call" width="14" height="14"></iconify-icon>
            </a>
        </div>
        @endif

    </div>
</div>

<style>
    /* Header-top transitions */
    #header-top {
        transition: transform 0.3s ease, opacity 0.3s ease;
    }
    
    #main-nav {
        transition: top 0.3s ease;
    }
    
    /* Cuando body tiene clase 'scrolled', ocultar header-top */
    body.scrolled #header-top {
        transform: translateY(-100%);
        opacity: 0;
        pointer-events: none;
    }
    
    /* Cuando body tiene clase 'scrolled', ajustar main-nav */
    body.scrolled #main-nav[data-has-header-top="1"] {
        top: 0 !important;
    }
</style>

<script>
(function() {
    'use strict';
    
    // Early Return Pattern: validaciones iniciales
    const headerTop = document.getElementById('header-top');
    if (!headerTop) return;
    
    const SCROLL_THRESHOLD = 50;
    let ticking = false;
    let lastScrollY = window.scrollY;
    
    function updateScrollState() {
        const scrollY = window.scrollY;
        
        // Early Return: no cambió suficiente
        if (Math.abs(scrollY - lastScrollY) < 5) {
            ticking = false;
            return;
        }
        
        // Toggle clase 'scrolled' en body
        if (scrollY > SCROLL_THRESHOLD) {
            document.body.classList.add('scrolled');
        } else {
            document.body.classList.remove('scrolled');
        }
        
        lastScrollY = scrollY;
        ticking = false;
    }
    
    function onScroll() {
        // Early Return: ya hay un frame pendiente
        if (ticking) return;
        
        ticking = true;
        requestAnimationFrame(updateScrollState);
    }
    
    // Event listener con passive para mejor performance
    window.addEventListener('scroll', onScroll, { passive: true });
    
    // Inicialización: mostrar header-top suavemente
    setTimeout(() => {
        headerTop.style.opacity = '1';
        headerTop.style.visibility = 'visible';
        updateScrollState();
    }, 100);
    
})();
</script>
