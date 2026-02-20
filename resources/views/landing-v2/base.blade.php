<!doctype html>
<html lang="es" data-theme="{{ $tenant->colorPalette->slug ?? 'light' }}" class="scroll-smooth">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    
    {{-- SEO Meta Tags --}}
    <title>{{ $tenant->business_name }} | {{ $tenant->tagline ?? 'Bienvenidos' }}</title>
    <meta name="description" content="{{ $customization->about_text ?? $tenant->tagline ?? 'Descubre nuestros productos y servicios' }}" />
    <meta name="keywords" content="{{ $tenant->business_name }}, {{ $tenant->business_segment ?? 'negocio' }}" />
    
    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $tenant->business_name }}" />
    <meta property="og:description" content="{{ $tenant->tagline ?? '' }}" />
    <meta property="og:type" content="website" />
    @if($customization->hero_filename)
    <meta property="og:image" content="{{ asset('storage/tenants/' . $tenant->id . '/' . $customization->hero_filename) }}" />
    @endif
    
    {{-- Favicon --}}
    @if($customization?->logo_filename)
    <link rel="icon" type="image/png" href="{{ asset('storage/tenants/' . $tenant->id . '/' . $customization->logo_filename) }}" />
    @endif

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />

    {{-- FlyonUI CSS --}}
    @vite(['resources/css/app.css'])
    
    {{-- Flatpickr CSS (for date inputs) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />

    {{-- Currency System Data --}}
    <script>
        window.SYNTIWEB = {
            tenant_id: {{ $tenant->id }},
            business_name: "{{ $tenant->business_name }}",
            whatsapp: "{{ $tenant->whatsapp_sales ?? '' }}",
            currency: {
                exchange_rate: {{ $dollarRate ?? 36.50 }},
                symbol_ref: "REF",
                symbol_bs: "Bs.",
                current: "REF",
                decimals: 2
            }
        };
    </script>
</head>

<body>
    <div class="bg-base-100">
        {{-- Navbar --}}
        @include('landing-v2.partials.navbar')

        <main>
            {{-- Hero Section --}}
            @include('landing-v2.partials.hero')

            {{-- About Us Section --}}
            @include('landing-v2.partials.about')

            {{-- Products Section --}}
            @if($products->count() > 0)
                @include('landing-v2.partials.products')
            @endif

            {{-- Services Section --}}
            @if($services->count() > 0)
                @include('landing-v2.partials.services')
            @endif

            {{-- Testimonials Section --}}
            @include('landing-v2.partials.testimonials')

            {{-- CTA Section --}}
            @include('landing-v2.partials.cta')

            {{-- Team Section (opcional) --}}
            {{-- @include('landing-v2.partials.team') --}}

            {{-- Contact Section --}}
            @include('landing-v2.partials.contact')

            {{-- FAQ Section --}}
            @include('landing-v2.partials.faq')
        </main>

        {{-- Footer --}}
        @include('landing-v2.partials.footer')
    </div>

    {{-- WhatsApp Floating Button --}}
    @if($tenant->whatsapp_sales)
    <a 
        href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tenant->whatsapp_sales) }}?text={{ urlencode('Hola! Vengo de tu página web') }}"
        target="_blank"
        class="btn btn-circle btn-success btn-lg fixed bottom-20 end-6 z-50 shadow-lg"
        aria-label="Contactar por WhatsApp"
    >
        <span class="icon-[tabler--brand-whatsapp] size-7"></span>
    </a>
    @endif

    {{-- Scroll to Top Button --}}
    <button id="scrollToTopBtn" class="btn btn-circle btn-soft btn-secondary/20 bottom-6 end-6 fixed z-40 hidden" aria-label="Volver arriba">
        <span class="icon-[tabler--chevron-up] size-5 shrink-0"></span>
    </button>

    {{-- Vendor JS --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    {{-- FlyonUI JS --}}
    @vite(['resources/js/app.js'])
    
    {{-- Landing Page JS --}}
    <script src="{{ asset('js/landing-page-free.js') }}"></script>

    {{-- Currency Toggle System --}}
    <script>
        // Toggle currency function
        function toggleCurrency() {
            const current = window.SYNTIWEB.currency.current;
            window.SYNTIWEB.currency.current = (current === 'REF') ? 'Bs.' : 'REF';
            renderAllPrices();
            updateCurrencyButtons();
        }

        // Format price based on current currency
        function formatPrice(priceUSD) {
            const { exchange_rate, symbol_ref, symbol_bs, current, decimals } = window.SYNTIWEB.currency;
            
            if (current === 'REF') {
                return `${symbol_ref} ${parseFloat(priceUSD).toFixed(decimals)}`;
            } else {
                const priceBS = parseFloat(priceUSD) * exchange_rate;
                return `${symbol_bs} ${priceBS.toLocaleString('es-VE', { minimumFractionDigits: decimals, maximumFractionDigits: decimals })}`;
            }
        }

        // Render all prices on page
        function renderAllPrices() {
            document.querySelectorAll('[data-price-usd]').forEach(el => {
                const priceUSD = el.getAttribute('data-price-usd');
                el.textContent = formatPrice(priceUSD);
            });
        }

        // Update toggle buttons text
        function updateCurrencyButtons() {
            const current = window.SYNTIWEB.currency.current;
            document.querySelectorAll('.currency-toggle-text').forEach(el => {
                el.textContent = current === 'REF' ? 'Ver en Bs.' : 'Ver en REF';
            });
        }

        // Build WhatsApp message for product
        function buildWhatsAppLink(productName, productPrice) {
            const phone = window.SYNTIWEB.whatsapp.replace(/[^0-9]/g, '');
            const message = encodeURIComponent(`Hola! Me interesa: ${productName} (${formatPrice(productPrice)})`);
            return `https://wa.me/${phone}?text=${message}`;
        }

        // Initialize on DOM ready
        document.addEventListener('DOMContentLoaded', function() {
            renderAllPrices();
            
            // Scroll to top button logic
            const scrollBtn = document.getElementById('scrollToTopBtn');
            if (scrollBtn) {
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 300) {
                        scrollBtn.classList.remove('hidden');
                    } else {
                        scrollBtn.classList.add('hidden');
                    }
                });
                scrollBtn.addEventListener('click', function() {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            }
        });
    </script>
</body>
</html>
