<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
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
    
    {{-- Theme Colors CSS Variables --}}
    <style>
:root {
    --color-primary: {{ $colors['primary'] }};
    --color-secondary: {{ $colors['secondary'] }};
    --color-accent: {{ $colors['accent'] }};
    --color-text: {{ $colors['text'] }};
    --color-text-muted: {{ $colors['textMuted'] }};
    --color-bg: {{ $colors['background'] }};
    --color-bg-alt: {{ $colors['backgroundAlt'] }};
    --color-button-bg: {{ $colors['buttonBg'] }};
    --color-button-text: {{ $colors['buttonText'] }};
    --color-button-hover: {{ $colors['buttonHoverBg'] }};
    --color-link: {{ $colors['linkColor'] }};
    --color-link-hover: {{ $colors['linkHover'] }};
    --font-heading: '{{ $fonts['heading'] }}', sans-serif;
    --font-body: '{{ $fonts['body'] }}', sans-serif;
}

body {
    font-family: var(--font-body);
    color: var(--color-text);
    background-color: var(--color-bg);
}

h1, h2, h3, h4, h5, h6 {
    font-family: var(--font-heading);
}

.btn-primary {
    background-color: var(--color-button-bg);
    color: var(--color-button-text);
}

.btn-primary:hover {
    background-color: var(--color-button-hover);
}

a {
    color: var(--color-link);
}

a:hover {
    color: var(--color-link-hover);
}
    </style>
    
    {{-- Tailwind CSS (CDN for demo, replace with compiled in production) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Custom Styles --}}
    @stack('styles')
</head>
<body class="min-h-screen" style="background-color: var(--color-bg); color: var(--color-text);">
    
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
    
    {{-- Contact/Footer --}}
    @include('landing.partials.footer')
    
    {{-- WhatsApp Floating Button --}}
    @include('landing.partials.whatsapp-button')
    
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
            const rate = CLIENT_DATA.currency.exchange_rate;
            const settings = CLIENT_DATA.currency.settings;
            
            if (currentCurrency === 'REF' || currentCurrency === 'USD') {
                return `${settings.symbols.reference} ${parseFloat(priceUSD).toFixed(settings.decimals)}`;
            } else {
                const priceBS = parseFloat(priceUSD) * rate;
                return `${settings.symbols.bolivares} ${formatNumber(priceBS, settings.decimals)}`;
            }
        }
        
        function formatNumber(num, decimals = 2) {
            return num.toFixed(decimals).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
        
        function toggleCurrency() {
            currentCurrency = (currentCurrency === 'REF' || currentCurrency === 'USD') ? 'Bs.' : 'REF';
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
                btn.textContent = currentCurrency === 'REF' ? 'Ver en Bs.' : 'Ver en REF';
            }
        }
        
        // Initialize on load
        document.addEventListener('DOMContentLoaded', renderAllPrices);
    </script>
    
    @stack('scripts')
</body>
</html>
