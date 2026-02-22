<!DOCTYPE html>
<html data-theme="{{ $themeSlug }}" lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- SEO Meta Tags --}}
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
    
    {{-- Google Fonts for FlyonUI Themes --}}
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Amaranth:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Archivo:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Fira+Code:wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    
    {{-- FlyonUI Theme System + Compiled CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- 
        FlyonUI provides 32 official themes via data-theme attribute
        Themes: light, dark, cupcake, bumblebee, emerald, corporate, synthwave, retro, cyberpunk, 
                valentine, halloween, garden, forest, aqua, lofi, pastel, fantasy, wireframe, black, 
                luxury, dracula, cmyk, autumn, business, acid, lemonade, night, coffee, winter, dim, 
                nord, sunset
        
        Theme colors are automatically handled by FlyonUI CSS
    --}}
    
    {{-- Custom Styles --}}
    @stack('styles')

</head>
<body class="min-h-screen bg-base-200 text-base-content">
    
    {{-- Preview Banner --}}
    @isset($isPreview)
    <div class="bg-yellow-500 text-black text-center py-2 text-sm font-semibold">
        ⚠️ MODO VISTA PREVIA - Los cambios no están publicados
    </div>
    @endisset

    {{-- Header --}}
    @include('landing.partials.header')
    
    {{-- Hero Section --}}
    @include('landing.partials.hero')
    
    {{-- Products Section --}}
    @if($products->count() > 0)
        @include('landing.partials.products')
    @endif
    
    {{-- Services Section --}}
    @if($services->count() > 0)
        @include('landing.partials.services')
    @endif
    
    {{-- About Section (if plan allows) --}}
    @if($plan?->show_about_section && $tenant->description)
        @include('landing.partials.about')
    @endif
    
    {{-- FAQ Section (if plan allows) --}}
    @if($plan?->show_faq && $customization?->faq_items)
        @include('landing.partials.faq')
    @endif
    
    {{-- CTA Section (if plan allows) --}}
    @if($plan?->show_cta_special && $customization?->cta_title)
        @include('landing.partials.cta')
    @endif
    
    {{-- Payment Methods Section --}}
    @if($plan?->show_payment_methods)
        @include('landing.partials.payment-methods')
    @endif
    
    {{-- Contact/Footer --}}
    @include('landing.partials.footer')
    
    {{-- WhatsApp Floating Button --}}
    @include('landing.partials.whatsapp-button')
    
    {{-- Currency Configuration --}}
    <script>
        const CURRENCY_MODE = '{{ data_get($tenant->settings, "engine_settings.currency.display.saved_display_mode", "reference_only") }}';
        const CURRENCY_SYMBOL = '{{ data_get($tenant->settings, "engine_settings.currency.display.symbols.reference", "REF") }}';
        const DOLLAR_RATE = {{ $dollarRate ?? 36.50 }};
    </script>
    
    {{-- Currency Toggle Script --}}
    <script>
        const CLIENT_DATA = {
            tenant_id: {{ $tenant->id }},
            business_name: "{{ $tenant->business_name }}",
            currency: {
                exchange_rate: {{ $dollarRate }},
                settings: @json($currencySettings)
            }
        };
        
        let currentCurrency = CLIENT_DATA.currency.settings.default_currency;
        
        function formatPrice(priceUSD) {
            const rate = DOLLAR_RATE;
            const settings = CLIENT_DATA.currency.settings;
            
            // Handle 'hidden' mode
            if (CURRENCY_MODE === 'hidden') {
                return 'Más Info';
            }
            
            // Determine currency to display based on mode
            let displayCurrency = currentCurrency;
            if (CURRENCY_MODE === 'reference_only') {
                displayCurrency = CURRENCY_SYMBOL;
            } else if (CURRENCY_MODE === 'bolivares_only') {
                displayCurrency = 'Bs.';
            }
            
            if (displayCurrency === CURRENCY_SYMBOL || displayCurrency === 'REF' || displayCurrency === 'USD') {
                return `${CURRENCY_SYMBOL} ${parseFloat(priceUSD).toFixed(settings.decimals)}`;
            } else {
                const priceBS = parseFloat(priceUSD) * rate;
                return `${settings.symbols.bolivares} ${formatNumber(priceBS, settings.decimals)}`;
            }
        }
        
        function formatNumber(num, decimals = 2) {
            return num.toFixed(decimals).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
        
        function toggleCurrency() {
            // Only allow toggling in 'both_toggle' mode
            if (CURRENCY_MODE !== 'both_toggle') return;
            
            currentCurrency = (currentCurrency === CURRENCY_SYMBOL || currentCurrency === 'REF' || currentCurrency === 'USD') ? 'Bs.' : CURRENCY_SYMBOL;
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
                // Hide button if mode is not 'both_toggle'
                if (CURRENCY_MODE !== 'both_toggle') {
                    btn.style.display = 'none';
                } else {
                    btn.style.display = '';
                    btn.textContent = (currentCurrency === CURRENCY_SYMBOL || currentCurrency === 'REF') ? 'Ver en Bs.' : `Ver en ${CURRENCY_SYMBOL}`;
                }
            }
        }
        
        // Initialize on load
        document.addEventListener('DOMContentLoaded', function() {
            // Set initial currency based on mode
            if (CURRENCY_MODE === 'bolivares_only') {
                currentCurrency = 'Bs.';
            } else {
                currentCurrency = CURRENCY_SYMBOL;
            }
            
            renderAllPrices();
            updateToggleButton();
        });
    </script>
    
    {{-- Floating Panel --}}
    @include('landing.partials.floating-panel')
    
    @stack('scripts')
</body>
</html>
