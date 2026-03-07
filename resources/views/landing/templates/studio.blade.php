{{-- ═══════════════════════════════════════════════════════════════════════════════
     SYNTIweb — Template STUDIO (servicios / profesionales)
     Preline 4.1.2 + Tailwind v4
     Secciones dinámicas ordenables por tenant
═══════════════════════════════════════════════════════════════════════════════ --}}
@extends('landing.base')

@section('content')

    {{-- ══════════════════════════════════════════════
         SECCIONES FIJAS - SIEMPRE VISIBLES
    ══════════════════════════════════════════════ --}}

    @if($tenant->isAtLeastCrecimiento())
        @include('landing.sections.header-top')
    @endif

    @include('landing.sections.header')

    @if($tenant->plan_id === 1)
        @include('landing.sections.hero-split')
    @elseif($tenant->plan_id === 2)
        @include('landing.sections.hero-fullscreen-v2')
    @else
        @include('landing.sections.hero-gradient')
    @endif

    {{-- ══════════════════════════════════════════════
         SECCIONES DINÁMICAS - ORDENABLES
    ══════════════════════════════════════════════ --}}

    @php
        $planId = $plan->id ?? 1;
        $sectionsOrder = $customization->getSectionsOrder();

        $hasContent = [
            'products'        => $products->count() > 0,
            'services'        => $services->count() > 0,
            'faq'             => collect(data_get($tenant->settings, 'business_info.faq', []))
                                   ->filter(fn($f) => !empty($f['question']) && !empty($f['answer']))
                                   ->count() > 0,
            'testimonials'    => collect(data_get($tenant->settings, 'business_info.testimonials', []))
                                   ->filter(fn($t) => !empty($t['text']))
                                   ->count() > 0,
            'branches'        => true,
            'about'           => !empty($customization->about_text) || !empty($customization->about_image_filename),
            'payment_methods' => true,
            'contact'         => true,
            'cta'             => true,
        ];
    @endphp

    <main class="bg-surface">
        @foreach($sectionsOrder as $section)
            @php
                $sectionName = $section['name'];
                $isVisible = $section['visible'] ?? true;
                $canAccess = $customization->canAccessSection($sectionName, $planId);
                $shouldRender = $isVisible && $canAccess && $sectionName !== 'hero';
                $sConfig = $shouldRender ? $customization->getSectionConfig($sectionName) : [];
                $sectionHasContent = $hasContent[$sectionName] ?? true;
            @endphp

            @if($shouldRender)
                @if($sectionHasContent)
                    @switch($sectionName)

                        @case('about')
                            @include('landing.sections.about', ['sConfig' => $sConfig])
                            @break

                        @case('contact')
                            @include('landing.sections.contact', ['sConfig' => $sConfig])
                            @break

                        @case('products')
                            @include('landing.sections.products', ['sConfig' => $sConfig])
                            @break

                        @case('services')
                            @php
                                $servicesView = match($sConfig['variant'] ?? 'cards') {
                                    'spotlight' => 'landing.sections.services-spotlight',
                                    default     => 'landing.sections.services',
                                };
                            @endphp
                            @include($servicesView, ['sConfig' => $sConfig])
                            @break

                        @case('testimonials')
                            @include('landing.sections.testimonials', ['sConfig' => $sConfig])
                            @break

                        @case('faq')
                            @include('landing.sections.faq', ['sConfig' => $sConfig])
                            @break

                        @case('branches')
                            @include('landing.sections.branches', ['sConfig' => $sConfig])
                            @break

                        @case('payment_methods')
                            @include('landing.sections.payment_methods', ['sConfig' => $sConfig])
                            @break

                        @case('cta')
                            @include('landing.sections.cta', ['sConfig' => $sConfig])
                            @break

                    @endswitch
                @else
                    @include('landing.sections._empty-guide', ['section' => $sectionName])
                @endif
            @endif
        @endforeach

    </main>

    {{-- ══════════════════════════════════════════════
         FOOTER FIJO
    ══════════════════════════════════════════════ --}}

    @include('landing.sections.footer', ['sConfig' => $customization->getSectionConfig('footer')])

@endsection

@push('scripts')
<script>
    const CURRENCY_MODE   = @json($savedDisplayMode ?? $displayMode ?? 'reference_only');
    const CURRENCY_SYMBOL = @json($currencySettings['symbols']['reference'] ?? 'REF');
    const EXCHANGE_RATE   = @json($dollarRate ?? 36.50);
    const EURO_RATE       = @json($euroRate ?? 495.00);
    let currentCurrency = CURRENCY_SYMBOL;

    function formatPrice(usdPrice) {
        const val = parseFloat(usdPrice);
        if (currentCurrency === 'Bs.') {
            const rate = CURRENCY_MODE === 'euro_toggle' ? EURO_RATE : EXCHANGE_RATE;
            return `<span class="text-xs font-medium opacity-50 mr-1">Bs.</span>${(val * rate).toLocaleString('es-VE', {minimumFractionDigits: 2})}`;
        }
        if (currentCurrency === '€') {
            return `<span class="text-xs font-medium opacity-50 mr-1">€</span>${val.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
        }
        return `<span class="text-xs font-medium opacity-50 mr-1">${CURRENCY_SYMBOL}</span>${val.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
    }

    function setCurrency(mode) {
        if      (mode === 'bs')  currentCurrency = 'Bs.';
        else if (mode === 'eur') currentCurrency = '€';
        else                     currentCurrency = CURRENCY_SYMBOL;
        renderAllPrices();
        updateToggleButton();
    }

    function toggleCurrency() {
        if (CURRENCY_MODE === 'euro_toggle') {
            currentCurrency = (currentCurrency === 'Bs.') ? '€' : 'Bs.';
        } else {
            currentCurrency = (currentCurrency === 'Bs.') ? CURRENCY_SYMBOL : 'Bs.';
        }
        renderAllPrices();
        updateToggleButton();
    }

    function renderAllPrices() {
        document.querySelectorAll('[data-price-usd]').forEach(el => {
            el.innerHTML = formatPrice(el.getAttribute('data-price-usd'));
        });
    }

    function updateToggleButton() {
        const btn = document.getElementById('currency-toggle-btn');
        if (!btn) return;
        const active   = 'bg-background shadow-sm text-primary';
        const inactive = 'text-foreground/40';
        const btnClass = (isActive) => `px-3 py-1 text-[10px] font-black rounded-lg transition-all ${isActive ? active : inactive}`;

        if (CURRENCY_MODE === 'euro_toggle') {
            const btnEur = btn.querySelector('[data-currency="eur"]');
            const btnBs  = btn.querySelector('[data-currency="bs"]');
            if (btnEur && btnBs) {
                btnEur.className = btnClass(currentCurrency === '€');
                btnBs.className  = btnClass(currentCurrency === 'Bs.');
            }
        } else {
            const btnRef = btn.querySelector('[data-currency="ref"]');
            const btnBs  = btn.querySelector('[data-currency="bs"]');
            if (btnRef && btnBs) {
                btnRef.className = btnClass(currentCurrency !== 'Bs.');
                btnBs.className  = btnClass(currentCurrency === 'Bs.');
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (CURRENCY_MODE === 'bolivares_only')  currentCurrency = 'Bs.';
        else if (CURRENCY_MODE === 'euro_toggle') currentCurrency = '€';
        else                                      currentCurrency = CURRENCY_SYMBOL;
        renderAllPrices();
        updateToggleButton();

        // ═══════════════════════════════════════════════════════════════════
        // SCROLL SPY — Marcar sección actual en navegación
        // ═══════════════════════════════════════════════════════════════════
        const navLinks = document.querySelectorAll('[data-nav-link]');
        const sectionIds = Array.from(navLinks).map(link => link.getAttribute('data-nav-link'));
        let currentActiveId = 'home';

        function updateActiveLink(targetId) {
            if (currentActiveId === targetId) return; // No hacer nada si ya está activo
            
            // Remover estilos de link anterior
            const prevLink = document.querySelector(`[data-nav-link="${currentActiveId}"]`);
            if (prevLink) {
                prevLink.classList.remove('bg-primary/10', 'text-primary');
                prevLink.classList.add('text-foreground/70');
            }

            // Agregar estilos al nuevo link
            const newLink = document.querySelector(`[data-nav-link="${targetId}"]`);
            if (newLink) {
                newLink.classList.add('bg-primary/10', 'text-primary');
                newLink.classList.remove('text-foreground/70');
            }

            currentActiveId = targetId;
        }

        // Observar scroll y detectar qué sección está más visible
        function detectVisibleSection() {
            const headerHeight = 64; // altura del header sticky
            let mostVisibleSection = 'home'; // por defecto Inicio
            let maxVisibility = 0;

            sectionIds.forEach(id => {
                const section = document.getElementById(id);
                if (!section) return;

                const rect = section.getBoundingClientRect();
                const viewportHeight = window.innerHeight;
                
                // Calcular cuánto de la sección está visible
                const sectionTop = Math.max(rect.top - headerHeight, 0);
                const sectionBottom = Math.min(rect.bottom, viewportHeight);
                const visibleHeight = Math.max(0, sectionBottom - sectionTop);
                const visibility = visibleHeight / viewportHeight;

                // Si esta sección está más visible que la anterior, marcarla
                if (visibility > maxVisibility && rect.top < viewportHeight && rect.bottom > headerHeight) {
                    maxVisibility = visibility;
                    mostVisibleSection = id;
                }
            });

            // Si el usuario está muy arriba (antes de cualquier sección), mostrar Inicio
            const homeSection = document.getElementById('home');
            if (homeSection && homeSection.getBoundingClientRect().bottom > window.innerHeight * 0.5) {
                mostVisibleSection = 'home';
            }

            updateActiveLink(mostVisibleSection);
        }

        // Ejecutar detección en eventos clave
        window.addEventListener('scroll', () => {
            requestAnimationFrame(detectVisibleSection);
        }, { passive: true });

        window.addEventListener('resize', detectVisibleSection, { passive: true });

        // Marcar Inicio como activo al cargar (esto asegura que aparezca sombreado por defecto)
        updateActiveLink('home');
        
        // Necesario para algunos navegadores que cachean scroll state
        setTimeout(() => {
            detectVisibleSection();
        }, 100);
    });
</script>
{{-- SyntiTrack --}}
<script>
(function() {
    const TENANT_ID = {{ $tenant->id }};
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    function track(eventType) {
        fetch('/api/analytics/track', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ tenant_id: TENANT_ID, event_type: eventType })
        }).catch(() => {});
    }
    track('pageview');
    document.addEventListener('click', function(e) {
        if (e.target.closest('a[href*="wa.me"]')) track('click_whatsapp');
        if (e.target.closest('a[href^="tel:"]')) track('click_call');
    });
    setInterval(() => track('time_on_page'), 30000);
})();
</script>
@endpush
