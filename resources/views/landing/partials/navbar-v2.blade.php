{{-- 
  NAVBAR CONDICIONAL POR PLAN
  ────────────────────────────
  Elementos siempre visibles: Logo, Nombre, Home, Productos, Servicios, WhatsApp, Estado
  Condicionales: Nosotros (no Oportunidad), Delivery (has_delivery), FAQ (Vision)
  Toggle moneda (privado): Solo si saved_display_mode === 'both_toggle'
--}}

<header class="border-base-content/20 bg-base-100 py-0.25 fixed top-0 z-10 w-full border-b" data-theme="{{ $themeSlug ?? 'light' }}">
  <nav class="navbar mx-auto max-w-[1280px] rounded-b-xl px-4 sm:px-6 lg:px-8">
    <div class="w-full lg:flex lg:items-center lg:gap-2">
      
      {{-- LOGO + NOMBRE + BOTONES MOBILE --}}
      <div class="navbar-start items-center justify-between max-lg:w-full">
        <a class="text-base-content flex items-center gap-3 text-xl font-semibold" href="#home">
          <img src="{{ asset('brand/syntiweb-logo-positive.svg') }}" 
               alt="SYNTIweb" width="36" height="36">
          {{-- Nombre del negocio --}}
          <span class="max-w-[180px] truncate">{{ $tenant->business_name }}</span>
        </a>

        {{-- MOBILE: WhatsApp + Estado + Hamburger --}}
        <div class="flex items-center gap-3 lg:hidden">
          {{-- WhatsApp Mobile --}}
          @if($tenant->whatsapp)
            <a href="https://wa.me/{{ $tenant->whatsapp }}" 
               target="_blank"
               class="btn btn-circle btn-sm btn-ghost text-success"
               title="WhatsApp: {{ $tenant->whatsapp }}">
              <span class="iconify tabler--brand-whatsapp size-5"></span>
            </a>
          @endif

          {{-- Estado Mobile --}}
          <div class="flex items-center gap-1.5" title="{{ $tenant->is_open ? 'Abierto ahora' : 'Cerrado' }}">
            <span class="relative flex h-2.5 w-2.5">
              <span class="absolute inline-flex h-full w-full animate-ping rounded-full {{ $tenant->is_open ? 'bg-success' : 'bg-error' }} opacity-75"></span>
              <span class="relative inline-flex h-2.5 w-2.5 rounded-full {{ $tenant->is_open ? 'bg-success' : 'bg-error' }}"></span>
            </span>
          </div>

          {{-- Hamburger Toggle --}}
          <button type="button" 
                  class="collapse-toggle btn btn-outline btn-secondary btn-square btn-sm" 
                  data-collapse="#navbar-collapse" 
                  aria-controls="navbar-collapse" 
                  aria-label="Toggle navigation">
            <span class="iconify tabler--menu-2 collapse-open:hidden size-5"></span>
            <span class="iconify tabler--x collapse-open:block size-5 hidden"></span>
          </button>
        </div>
      </div>

      {{-- LINKS DE NAVEGACIÓN (DESKTOP Y MOBILE COLLAPSE) --}}
      <div id="navbar-collapse" 
           class="lg:navbar-center transition-height collapse hidden grow overflow-hidden font-medium duration-300 lg:flex">
        <div class="text-base-content flex gap-6 text-base max-lg:mt-4 max-lg:flex-col lg:items-center">
          {{-- Links siempre visibles --}}
          <a href="#home" class="hover:text-primary nav-link">🏠 Home</a>
          <a href="#products" class="hover:text-primary nav-link">🛍️ Productos</a>
          <a href="#services" class="hover:text-primary nav-link">⚙️ Servicios</a>
          
          {{-- CONDICIONAL: Nosotros (no mostrar en plan Oportunidad) --}}
          @if($tenant->plan->slug !== 'oportunidad')
            <a href="#about" class="hover:text-primary nav-link">ℹ️ Nosotros</a>
          @endif

          {{-- CONDICIONAL: FAQ (solo plan Visión) --}}
          @if($tenant->plan->slug === 'vision')
            <a href="#faq" class="hover:text-primary nav-link">❓ FAQ</a>
          @endif

          {{-- MOBILE: Links adicionales en collapse --}}
          <div class="flex flex-col gap-4 lg:hidden mt-4 pt-4 border-t border-base-content/20">
            {{-- WhatsApp Link Mobile --}}
            @if($tenant->whatsapp)
              <a href="https://wa.me/{{ $tenant->whatsapp }}" 
                 target="_blank"
                 class="btn btn-success btn-sm gap-2">
                <span class="iconify tabler--brand-whatsapp size-5"></span>
                WhatsApp
              </a>
            @endif

            {{-- Toggle Moneda Mobile --}}
            @if($tenant->saved_display_mode === 'both_toggle')
              <button id="currency-toggle-mobile" 
                      class="btn btn-outline btn-sm gap-2 currency-toggle">
                <span class="iconify tabler--currency-dollar size-5"></span>
                <span class="currency-symbol">REF</span>
              </button>
            @endif
          </div>
        </div>
      </div>

      {{-- DESKTOP: BOTONES DERECHA --}}
      <div class="navbar-end gap-3 max-lg:hidden">
        {{-- CONDICIONAL: Icono Delivery (no Oportunidad + has_delivery) --}}
        @if($tenant->plan->slug !== 'oportunidad' && $tenant->has_delivery)
          <div class="tooltip tooltip-bottom" data-tooltip="Servicio de Delivery">
            <button class="btn btn-circle btn-ghost btn-sm text-primary">
              <span class="iconify tabler--truck-delivery size-5"></span>
            </button>
          </div>
        @endif

        {{-- Estado Desktop --}}
        <div class="flex items-center gap-2 px-3 py-1.5 rounded-full {{ $tenant->is_open ? 'bg-success/10 text-success' : 'bg-error/10 text-error' }}">
          <span class="relative flex h-2 w-2">
            <span class="absolute inline-flex h-full w-full animate-ping rounded-full {{ $tenant->is_open ? 'bg-success' : 'bg-error' }} opacity-75"></span>
            <span class="relative inline-flex h-2 w-2 rounded-full {{ $tenant->is_open ? 'bg-success' : 'bg-error' }}"></span>
          </span>
          <span class="text-sm font-semibold">{{ $tenant->is_open ? 'ABIERTO' : 'CERRADO' }}</span>
        </div>

        {{-- Toggle Moneda Desktop (PRIVADO) --}}
        @if($tenant->saved_display_mode === 'both_toggle')
          <button id="currency-toggle" 
                  class="btn btn-sm gap-2 currency-toggle"
                  title="Cambiar moneda">
            <span class="iconify tabler--currency-dollar size-5"></span>
            <span class="currency-symbol font-mono font-bold">REF</span>
          </button>
        @endif

        {{-- WhatsApp Desktop --}}
        @if($tenant->whatsapp)
          <a href="https://wa.me/{{ $tenant->whatsapp }}?text=Hola%20{{ urlencode($tenant->business_name) }}%2C%20quisiera%20más%20información" 
             target="_blank"
             class="btn btn-success gap-2">
            <span class="iconify tabler--brand-whatsapp size-5"></span>
            WhatsApp
          </a>
        @endif
      </div>

    </div>
  </nav>
</header>

{{-- Spacer para compensar el fixed navbar --}}
<div class="h-16"></div>

{{-- JAVASCRIPT: Toggle de Moneda --}}
@if($tenant->saved_display_mode === 'both_toggle')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const toggleButtons = document.querySelectorAll('.currency-toggle');
    const currencySymbols = document.querySelectorAll('.currency-symbol');
    const dollarPrices = document.querySelectorAll('.dollar-price');
    const refPrices = document.querySelectorAll('.ref-price');
    
    let currentCurrency = localStorage.getItem('currency_{{ $tenant->id }}') || 'ref';
    
    // Aplicar estado inicial
    updateCurrency(currentCurrency);
    
    // Event listeners para todos los botones toggle
    toggleButtons.forEach(button => {
      button.addEventListener('click', function() {
        currentCurrency = currentCurrency === 'ref' ? 'dollar' : 'ref';
        localStorage.setItem('currency_{{ $tenant->id }}', currentCurrency);
        updateCurrency(currentCurrency);
      });
    });
    
    function updateCurrency(currency) {
      // Actualizar símbolos en botones
      currencySymbols.forEach(symbol => {
        symbol.textContent = currency === 'ref' ? 'REF' : '$';
      });
      
      // Mostrar/ocultar precios
      if (currency === 'dollar') {
        dollarPrices.forEach(el => el.classList.remove('hidden'));
        refPrices.forEach(el => el.classList.add('hidden'));
      } else {
        dollarPrices.forEach(el => el.classList.add('hidden'));
        refPrices.forEach(el => el.classList.remove('hidden'));
      }
    }
  });
</script>
@endif
