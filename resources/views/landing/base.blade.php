<!DOCTYPE html>
@php
$customPalette = $tenant->settings['engine_settings']['visual']['custom_palette'] ?? null;
$effectiveTheme = $customPalette ? 'custom' : $themeSlug;
@endphp
<html data-theme="{{ $effectiveTheme }}" lang="es" class="scroll-smooth">

@if($customPalette)
<style>
[data-theme="custom"] {
    --color-primary: {{ $customPalette['primary'] ?? '#570DF8' }};
    --p: {{ $customPalette['primary'] ?? '#570DF8' }};
    --color-secondary: {{ $customPalette['secondary'] ?? '#F000B9' }};
    --s: {{ $customPalette['secondary'] ?? '#F000B9' }};
    --color-accent: {{ $customPalette['accent'] ?? '#1DCDBC' }};
    --a: {{ $customPalette['accent'] ?? '#1DCDBC' }};
    --color-base-100: {{ $customPalette['base'] ?? '#FFFFFF' }};
    --b1: {{ $customPalette['base'] ?? '#FFFFFF' }};
    --bc: #1f2937;
}
</style>
@endif
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $meta['title'] }}</title>
    <meta name="description" content="{{ $meta['description'] }}">
    @if($meta['keywords'])
    <meta name="keywords" content="{{ $meta['keywords'] }}">
    @endif
    <link rel="canonical" href="{{ $meta['canonical'] }}">
    
    <meta property="og:title" content="{{ $meta['og_title'] }}">
    <meta property="og:description" content="{{ $meta['og_description'] }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $meta['canonical'] }}">
    @if($meta['og_image'])
    <meta property="og:image" content="{{ asset('storage/tenants/' . $tenant->id . '/' . $meta['og_image']) }}">
    @endif
    
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="preload" href="https://api.iconify.design/tabler.css" as="style">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    {{-- ═══ Schema.org automático según Blueprint ═══ --}}
    @php $schemaType = ($blueprint['schema_type'] ?? null) ?: $tenant->getSchemaType(); @endphp
    @switch($schemaType)
        @case('Restaurant')
            @include('landing.schemas.restaurant', compact('tenant'))
            @break
        @case('Store')
            @include('landing.schemas.store', compact('tenant'))
            @break
        @case('HealthAndBeautyBusiness')
            @include('landing.schemas.health', compact('tenant'))
            @break
        @case('ProfessionalService')
            @include('landing.schemas.professional', compact('tenant'))
            @break
        @default
            @include('landing.schemas.local-business', compact('tenant'))
    @endswitch
</head>

<body class="min-h-screen bg-base-100 text-base-content antialiased transition-colors duration-500">

    <div class="fixed inset-0 z-[9999] opacity-[0.02] pointer-events-none bg-[url('https://grainy-gradients.vercel.app/noise.svg')]"></div>

    {{-- ══════════════════════════════════════════════
         SECCIONES FIJAS - SIEMPRE VISIBLES
    ══════════════════════════════════════════════ --}}

    @if($tenant->isAtLeastCrecimiento())
        {{-- Barra informativa: horario, teléfono, delivery --}}
        @include('landing.partials.header-top')
    @endif

    @include('landing.partials.header')

    {{-- Wrapper que compensa las barras fixed: header-top (40px plan2+) + nav (60px) --}}
    <div class="{{ $tenant->isAtLeastCrecimiento() ? 'pt-[100px]' : 'pt-[60px]' }}">

    @php $sConfig = $customization->getSectionConfig('hero'); @endphp
    @include('landing.partials.hero', ['sConfig' => $sConfig])

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
                        @include('landing.partials.about', ['sConfig' => $sConfig])
                        @break

                    @case('contact')
                        @include('landing.partials.contact', ['sConfig' => $sConfig])
                        @break

                    @case('products')
                        {{-- Sin wrapper, sin wave — respira solo --}}
                        @include('landing.partials.products', ['sConfig' => $sConfig])
                        @break

                    @case('services')
                        @php
                            $servicesView = match($sConfig['variant'] ?? 'cards') {
                                'spotlight' => 'landing.partials.services-spotlight',
                                default     => 'landing.partials.services',
                            };
                            $usesDarkWrapper = ($sConfig['variant'] ?? 'cards') === 'cards';
                        @endphp

                        @if($usesDarkWrapper)
                            {{-- Variante original: oscura con glow --}}
                            <div class="relative bg-base-200 pt-32 pb-24">
                                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[700px] bg-primary/10 blur-[130px] rounded-full pointer-events-none"></div>
                                @include($servicesView, ['sConfig' => $sConfig])
                            </div>
                        @else
                            {{-- Variante spotlight: sin wrapper, la sección maneja su propio fondo y geometría --}}
                            @include($servicesView, ['sConfig' => $sConfig])
                        @endif
                        @break

                    @case('testimonials')
                        @include('landing.partials.testimonials', ['sConfig' => $sConfig])
                        @break

                    @case('faq')
                        @include('landing.partials.faq', ['sConfig' => $sConfig])
                        @break

                    @case('branches')
                        @include('landing.partials.branches', ['sConfig' => $sConfig])
                        @break

                    @case('payment_methods')
                        @include('landing.partials.payment_methods', ['sConfig' => $sConfig])
                        @break

                    @case('cta')
                        @include('landing.partials.cta', ['sConfig' => $sConfig])
                        @break

                @endswitch
            @endif
        @endforeach

    </main>

    </div>{{-- /pt wrapper --}}

    {{-- ══════════════════════════════════════════════
         FOOTER FIJO
    ══════════════════════════════════════════════ --}}

    @include('landing.partials.footer', ['sConfig' => $customization->getSectionConfig('footer')])

    <script>
        const CURRENCY_MODE   = @json($savedDisplayMode ?? $displayMode ?? 'reference_only');
        const CURRENCY_SYMBOL = @json($currencySettings['symbols']['reference'] ?? 'REF');
        const EXCHANGE_RATE   = @json($dollarRate ?? 36.50);
        const EURO_RATE       = @json($euroRate ?? 495.00);
        // currentCurrency: CURRENCY_SYMBOL (REF/$) | 'Bs.' | '€'
        let currentCurrency = CURRENCY_SYMBOL;

        function formatPrice(usdPrice) {
            const val = parseFloat(usdPrice);
            if (currentCurrency === 'Bs.') {
                // Bs. usa EURO_RATE en modo euro_toggle, EXCHANGE_RATE en los demás
                const rate = CURRENCY_MODE === 'euro_toggle' ? EURO_RATE : EXCHANGE_RATE;
                return `<span class="text-xs font-medium opacity-50 mr-1">Bs.</span>${(val * rate).toLocaleString('es-VE', {minimumFractionDigits: 2})}`;
            }
            if (currentCurrency === '€') {
                // € reemplaza $ visualmente — mismo valor numérico que el precio base
                return `<span class="text-xs font-medium opacity-50 mr-1">€</span>${val.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
            }
            // REF / $
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
            const active   = 'bg-base-100 shadow-sm text-primary';
            const inactive = 'text-base-content/40';
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

    @include('landing.partials.floating-panel')
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js" defer></script>
    @stack('scripts')
</body>
</html>