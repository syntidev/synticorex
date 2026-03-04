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

    <main>
        @foreach($customization->getSectionsOrder() as $section)
            @php
                $sectionName = $section['name'];
                $isVisible = $section['visible'] ?? true;
                $canAccess = $customization->canAccessSection($sectionName, $tenant->plan_id);
                $shouldRender = $isVisible && $canAccess && $sectionName !== 'hero';
            @endphp

            @if($shouldRender)
                @php $sConfig = $customization->getSectionConfig($sectionName); @endphp

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
    });
</script>
@endpush
