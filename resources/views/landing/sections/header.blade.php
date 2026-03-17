<header id="main-nav" 
        class="sticky top-0 z-50 w-full border-b border-border bg-background">
    <div class="container mx-auto flex items-center justify-between px-6 py-3">
        {{-- Logo Dinámico --}}
        <div class="flex items-center gap-3">
            <a href="#home" class="flex items-center gap-3">
                @if($customization && $customization->logo_filename)
                    <img src="{{ asset('storage/tenants/' . $tenant->id . '/' . $customization->logo_filename) }}"
                         alt="{{ $tenant->business_name }}"
                         class="h-9 w-auto object-contain">
                @else
                    <svg xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 100 100"
                         width="36" height="36">
                      <path d="M 30,22 L 78,22 L 78,70 Q 78,78 70,78 L 62,78
                               L 62,38 L 22,38 L 22,30 Q 22,22 30,22 Z"
                            fill="#1a1a1a"/>
                      <circle cx="38" cy="63" r="14" fill="#4A80E4"/>
                    </svg>
                @endif
                <span class="text-lg font-bold tracking-tight text-foreground">{{ $tenant->business_name }}</span>
            </a>
        </div>

        {{-- Menú Central — dinámico según secciones activas --}}
        @php
            $navSectionMap = [
                'products'        => ['label' => 'Productos',   'anchor' => '#products'],
                'services'        => ['label' => 'Servicios',   'anchor' => '#services'],
                'about'           => ['label' => 'Nosotros',    'anchor' => '#about'],
                'contact'         => ['label' => 'Contacto',    'anchor' => '#contact'],
                'testimonials'    => ['label' => 'Testimonios', 'anchor' => '#testimonials'],
                'faq'             => ['label' => 'FAQ',         'anchor' => '#faq'],
                'branches'        => ['label' => 'Sucursales',  'anchor' => '#branches'],
                'payment_methods' => ['label' => 'Pagos',       'anchor' => '#payment_methods'],
            ];
            $visibleNavLinks = [];
            foreach ($customization->getSectionsOrder() as $sec) {
                $k = $sec['name'] ?? '';
                if (($sec['visible'] ?? true) && $customization->canAccessSection($k, $tenant->plan_id) && isset($navSectionMap[$k])) {
                    if (!collect($visibleNavLinks)->contains('anchor', $navSectionMap[$k]['anchor'])) {
                        $visibleNavLinks[] = $navSectionMap[$k];
                    }
                }
            }
        @endphp
        <nav class="hidden space-x-6 lg:flex items-center" id="desktop-nav">
            <a href="#home" data-nav-link="home" class="nav-link text-sm font-semibold text-foreground/70 hover:text-primary transition-colors px-2 py-1 rounded-lg">Inicio</a>
            @foreach($visibleNavLinks as $link)
                <a href="{{ $link['anchor'] }}" data-nav-link="{{ str_replace('#', '', $link['anchor']) }}" class="nav-link text-sm font-semibold text-foreground/70 hover:text-primary transition-colors px-2 py-1 rounded-lg">
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        {{-- Hamburger: visible solo en mobile --}}
        <button 
          id="synti-hamburger-trigger"
          class="py-1.5 px-3 rounded-lg font-medium transition-colors text-sm text-foreground/80 hover:bg-surface lg:hidden" 
          onclick="document.getElementById('mobile-menu').classList.toggle('hidden')"
          aria-label="Menú">
          <span class="icon-[tabler--menu-2] size-6 text-foreground"></span>
        </button>

        {{-- Acciones y Moneda --}}
        <div class="flex items-center gap-4">
            {{-- Toggle de Moneda — un solo toggle según el modo elegido por el tenant --}}
            @php $currencyMode = $savedDisplayMode ?? $displayMode ?? 'reference_only'; @endphp
            @if($currencyMode === 'both_toggle')
            <div id="currency-toggle-btn" class="hidden sm:flex items-center bg-surface p-1 rounded-xl border border-border">
                <button
                    onclick="setCurrency('ref')"
                    data-currency="ref"
                    class="px-3 py-1 text-[10px] font-black rounded-lg transition-all bg-background shadow-sm text-primary"
                >{{ $currencySettings['symbols']['reference'] ?? 'REF' }}</button>
                <button
                    onclick="setCurrency('bs')"
                    data-currency="bs"
                    class="px-3 py-1 text-[10px] font-black rounded-lg transition-all text-foreground/40"
                >Bs.</button>
            </div>
            @elseif($currencyMode === 'euro_toggle')
            <div id="currency-toggle-btn" class="hidden sm:flex items-center bg-surface p-1 rounded-xl border border-border">
                <button
                    onclick="setCurrency('eur')"
                    data-currency="eur"
                    class="px-3 py-1 text-[10px] font-black rounded-lg transition-all bg-background shadow-sm text-primary"
                >€</button>
                <button
                    onclick="setCurrency('bs')"
                    data-currency="bs"
                    class="px-3 py-1 text-[10px] font-black rounded-lg transition-all text-foreground/40"
                >Bs.</button>
            </div>
            @endif

            {{-- Indicador de Horario (Opcional) --}}
            @if($showHoursIndicator ?? false)
            <span class="inline-flex items-center gap-1.5 rounded-full {{ ($isOpen ?? false) ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }} px-3 py-1 text-xs font-bold">
                <span class="h-1.5 w-1.5 rounded-full {{ ($isOpen ?? false) ? 'bg-green-500' : 'bg-red-500' }} animate-pulse"></span>
                {{ ($isOpen ?? false) ? 'ABIERTO' : 'CERRADO' }}
            </span>
            @endif
            
            {{-- Botón WhatsApp con mensaje dinámico según horario --}}
            @php 
                $wa = $tenant->getActiveWhatsapp() ?? null;
                $waMessage = ($showHoursIndicator && !$isOpen) 
                    ? $closedMessage 
                    : 'Hola, vi tu vitrina';
            @endphp
            @if($wa)
                <a href="https://wa.me/{{ preg_replace('/\D/', '', $wa) }}?text={{ urlencode($waMessage) }}" target="_blank" rel="noopener noreferrer"
                   onclick="if(!window.__tenantIsOpen){event.preventDefault();showClosedToast(this.href);}"
                   class="py-1.5 px-3 rounded-lg font-medium transition-colors text-sm bg-green-600 text-white hover:bg-green-700 hidden sm:flex font-bold shadow-lg">WhatsApp</a>
            @endif
        </div>
    </div>
    {{-- Mobile menu dropdown --}}
    <div id="mobile-menu" class="hidden lg:hidden border-t border-border bg-background px-4 py-3 space-y-2">
      <a href="#home" data-nav-link="home" class="nav-link block rounded-lg px-3 py-2 text-sm font-semibold text-foreground/70 hover:bg-primary/10 hover:text-primary transition-colors"
         onclick="document.getElementById('mobile-menu').classList.add('hidden')">Inicio</a>
      @foreach($visibleNavLinks as $link)
        <a href="{{ $link['anchor'] }}" data-nav-link="{{ str_replace('#', '', $link['anchor']) }}"
           onclick="document.getElementById('mobile-menu').classList.add('hidden')"
           class="nav-link block rounded-lg px-3 py-2 text-sm font-semibold text-foreground/70 hover:bg-primary/10 hover:text-primary transition-colors">
          {{ $link['label'] }}
        </a>
      @endforeach
    </div>
</header>

<script>
(function(){
    var btn = document.getElementById('synti-hamburger-trigger');
    if(!btn) return;
    var t = null;
    btn.addEventListener('touchstart', function(){
        t = setTimeout(function(){
            if(typeof openSyntiPanel==='function') openSyntiPanel();
            if(navigator.vibrate) navigator.vibrate(60);
        }, 800);
    }, {passive:true});
    btn.addEventListener('touchend',   function(){ clearTimeout(t); }, {passive:true});
    btn.addEventListener('touchmove',  function(){ clearTimeout(t); }, {passive:true});
    btn.addEventListener('touchcancel',function(){ clearTimeout(t); }, {passive:true});
})();
</script>

