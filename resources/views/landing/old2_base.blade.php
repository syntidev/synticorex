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
    
    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $meta['og_title'] }}">
    <meta property="og:description" content="{{ $meta['og_description'] }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $meta['canonical'] }}">
    @if($meta['og_image'])
    <meta property="og:image" content="{{ asset('storage/tenants/' . $tenant->id . '/' . $meta['og_image']) }}">
    @endif
    
    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>

<body class="min-h-screen bg-surface-50 dark:bg-surface-950 text-base-content antialiased transition-colors duration-500">

    {{-- Overlay de textura premium (opcional) --}}
    <div class="fixed inset-0 z-[9999] opacity-[0.02] pointer-events-none bg-[url('https://grainy-gradients.vercel.app/noise.svg')]"></div>

    @include('landing.partials.header')

    <main>
        @foreach($customization->getSectionsOrder() as $section)
            @if($customization->isSectionVisible($section))
                @php
                    $sConfig = $customization->getSectionConfig($section);
                @endphp
                
                @switch($section)
                    @case('hero')
                        {{-- Hero: Fondo Blanco/Luz --}}
                        @include('landing.partials.hero', ['sConfig' => $sConfig])
                        @break
                    
                    @case('products')
                        {{-- Sección Productos: Transición hacia la onda --}}
                        <div class="relative bg-base-100 pb-20">
                            @include('landing.partials.products', ['sConfig' => $sConfig])
                            
                            {{-- WAVE DINÁMICA: Hereda el color base-200 de Servicios --}}
                            <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-[0] transform translate-y-[99%] z-20 text-base-200">
                                <svg class="relative block w-full h-[60px] md:h-[100px] fill-current" viewBox="0 0 1200 120" preserveAspectRatio="none">
                                    <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z"></path>
                                </svg>
                            </div>
                        </div>
                        @break
                    
                    @case('services')
                        {{-- Sección Servicios: El alma del diseño oscuro con Glow --}}
                        <div class="relative bg-surface-900 dark:bg-black pt-32 pb-24 overflow-hidden">
                            @include('landing.partials.services', ['sConfig' => $sConfig])
                        </div>
                        @break
                    
                    @case('faq')
                        {{-- Sección FAQ: Fondo claro para descanso visual --}}
                        <div class="bg-surface-50 dark:bg-surface-950">
                            @include('landing.partials.faq', ['sConfig' => $sConfig])
                        </div>
                        @break
                    
                    @case('branches')
                        {{-- Branches Section (Plan 3 / VISIÓN only) --}}
                        @if($tenant->plan_id >= 3)
                            <div class="bg-surface-50 dark:bg-surface-950">
                                @include('landing.partials.branches', ['sConfig' => $sConfig])
                            </div>
                        @endif
                        @break
                    
                    @case('payment_methods')
                        {{-- Payment Methods Section --}}
                        <div class="bg-surface-50 dark:bg-surface-950">
                            @include('landing.partials.payment_methods', ['sConfig' => $sConfig])
                        </div>
                        @break
                    
                    @case('cta')
                        {{-- Call to Action Section --}}
                        @include('landing.partials.cta', ['sConfig' => $sConfig])
                        @break
                    
                    @case('footer')
                        {{-- Footer (handled outside main) --}}
                        @break
                @endswitch
            @endif
        @endforeach

        {{-- Sección Testimonios (Crea el archivo parcial si no existe) --}}
        @if(view()->exists('landing.partials.testimonials'))
            @include('landing.partials.testimonials')
        @endif
    </main>

    {{-- Footer con ADN dinámico --}}
    @include('landing.partials.footer', ['sConfig' => $customization->getSectionConfig('footer')])

    {{-- Lógica de Precios y Moneda --}}
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
                const priceUSD = el.getAttribute('data-price-usd');
                el.textContent = formatPrice(priceUSD);
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
    {{-- Iconify icon runtime — loads icons from CDN, inherits CSS color (theme-aware) --}}
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js" defer></script>
    @stack('scripts')
</body>
</html>