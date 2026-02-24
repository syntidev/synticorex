<!DOCTYPE html>
<html data-theme="{{ $themeSlug }}" lang="es" class="scroll-smooth">
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

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="min-h-screen bg-base-100 text-base-content antialiased transition-colors duration-500">

    <div class="fixed inset-0 z-[9999] opacity-[0.02] pointer-events-none bg-[url('https://grainy-gradients.vercel.app/noise.svg')]"></div>

    @if($tenant->plan_id >= 2)
        {{-- Barra informativa: horario, teléfono, delivery --}}
        @include('landing.partials.header-top')
        {{-- Empuja el nav principal 40px hacia abajo para no solapar header-top --}}
        <style>header.fixed { top: 40px !important; }</style>
    @endif

    @include('landing.partials.header')

    <main>
        @foreach($customization->getSectionsOrder() as $section)
            @if($customization->isSectionVisible($section))
                @php $sConfig = $customization->getSectionConfig($section); @endphp

                @switch($section)

                    @case('hero')
                        @include('landing.partials.hero', ['sConfig' => $sConfig])
                        @break

                    @case('about')
                        @if($tenant->plan_id >= 2)
                            @include('landing.partials.about', ['sConfig' => $sConfig])
                        @endif
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

                    @case('faq')
                        @if($tenant->plan_id >= 3)
                            @include('landing.partials.faq', ['sConfig' => $sConfig])
                        @endif
                        @break

                    @case('branches')
                        @if($tenant->plan_id >= 3)
                            @include('landing.partials.branches', ['sConfig' => $sConfig])
                        @endif
                        @break

                    @case('payment_methods')
                        @include('landing.partials.payment_methods', ['sConfig' => $sConfig])
                        @break

                    @case('cta')
                        @include('landing.partials.cta', ['sConfig' => $sConfig])
                        @break

                    @case('footer')
                        @break

                @endswitch
            @endif
        @endforeach

        @if(view()->exists('landing.partials.testimonials'))
            @include('landing.partials.testimonials')
        @endif
    </main>

    @include('landing.partials.footer', ['sConfig' => $customization->getSectionConfig('footer')])

    <script>
        const CURRENCY_MODE = @json($tenant->currency_mode);
        const CURRENCY_SYMBOL = @json($tenant->currency_symbol);
        const EXCHANGE_RATE = @json($tenant->exchange_rate);
        let currentCurrency = 'USD';

        function formatPrice(usdPrice) {
            if (currentCurrency === 'Bs.') {
                return 'Bs. ' + (usdPrice * EXCHANGE_RATE).toLocaleString('es-VE', {minimumFractionDigits: 2});
            }
            return (currentCurrency === 'REF' ? 'REF ' : CURRENCY_SYMBOL + ' ') + parseFloat(usdPrice).toLocaleString('en-US', {minimumFractionDigits: 2});
        }

        function toggleCurrency() {
            currentCurrency = (currentCurrency === CURRENCY_SYMBOL || currentCurrency === 'REF') ? 'Bs.' : CURRENCY_SYMBOL;
            renderAllPrices();
            updateToggleButton();
        }

        function renderAllPrices() {
            document.querySelectorAll('[data-price-usd]').forEach(el => {
                el.textContent = formatPrice(el.getAttribute('data-price-usd'));
            });
        }

        function updateToggleButton() {
            const btn = document.getElementById('currency-toggle-btn');
            if (btn) {
                if (CURRENCY_MODE !== 'both_toggle') {
                    btn.style.display = 'none';
                } else {
                    btn.style.display = '';
                    btn.textContent = (currentCurrency === CURRENCY_SYMBOL || currentCurrency === 'REF') ? 'Ver en Bs.' : `Ver en ${CURRENCY_SYMBOL}`;
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            currentCurrency = (CURRENCY_MODE === 'bolivares_only') ? 'Bs.' : CURRENCY_SYMBOL;
            renderAllPrices();
            updateToggleButton();
        });
    </script>

    @include('landing.partials.floating-panel')
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js" defer></script>
    @stack('scripts')
</body>
</html>