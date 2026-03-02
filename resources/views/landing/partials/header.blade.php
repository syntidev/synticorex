<header id="main-nav" 
        class="sticky top-0 z-50 w-full border-b border-base-200 bg-base-100">
    <div class="container mx-auto flex items-center justify-between px-6 py-3">
        {{-- Logo Dinámico --}}
        <div class="flex items-center gap-3">
            <a href="#home" class="flex items-center gap-3">
                @if($customization->logo_filename)
                    <img src="{{ asset('storage/tenants/'.$tenant->id.'/'.$customization->logo_filename) }}" class="h-10 w-10 object-contain rounded-lg">
                @else
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary text-white shadow-lg shadow-primary/30">
                        <span class="text-xl font-bold">{{ substr($tenant->business_name, 0, 1) }}</span>
                    </div>
                @endif
                <span class="text-lg font-bold tracking-tight text-base-content">{{ $tenant->business_name }}</span>
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
        <nav class="hidden space-x-6 lg:flex items-center">
            <a href="#home" class="text-sm font-semibold text-base-content/70 hover:text-primary transition-colors">Home</a>
            @foreach($visibleNavLinks as $link)
                <a href="{{ $link['anchor'] }}" class="text-sm font-semibold text-base-content/70 hover:text-primary transition-colors">
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        {{-- Hamburger: visible solo en mobile --}}
        <button 
          class="btn btn-ghost btn-sm lg:hidden" 
          onclick="document.getElementById('mobile-menu').classList.toggle('hidden')"
          aria-label="Menú">
          <span class="icon-[tabler--menu-2] size-6 text-base-content"></span>
        </button>

        {{-- Acciones y Moneda --}}
        <div class="flex items-center gap-4">
            {{-- Toggle de Moneda — un solo toggle según el modo elegido por el tenant --}}
            @php $currencyMode = $savedDisplayMode ?? $displayMode ?? 'reference_only'; @endphp
            @if($currencyMode === 'both_toggle')
            <div id="currency-toggle-btn" class="hidden sm:flex items-center bg-base-200 p-1 rounded-xl border border-base-300">
                <button
                    onclick="setCurrency('ref')"
                    data-currency="ref"
                    class="px-3 py-1 text-[10px] font-black rounded-lg transition-all bg-base-100 shadow-sm text-primary"
                >{{ $currencySettings['symbols']['reference'] ?? 'REF' }}</button>
                <button
                    onclick="setCurrency('bs')"
                    data-currency="bs"
                    class="px-3 py-1 text-[10px] font-black rounded-lg transition-all text-base-content/40"
                >Bs.</button>
            </div>
            @elseif($currencyMode === 'euro_toggle')
            <div id="currency-toggle-btn" class="hidden sm:flex items-center bg-base-200 p-1 rounded-xl border border-base-300">
                <button
                    onclick="setCurrency('eur')"
                    data-currency="eur"
                    class="px-3 py-1 text-[10px] font-black rounded-lg transition-all bg-base-100 shadow-sm text-primary"
                >€</button>
                <button
                    onclick="setCurrency('bs')"
                    data-currency="bs"
                    class="px-3 py-1 text-[10px] font-black rounded-lg transition-all text-base-content/40"
                >Bs.</button>
            </div>
            @endif

            {{-- Indicador de Horario (Opcional) --}}
            @if($showHoursIndicator ?? false)
            <span class="inline-flex items-center gap-1.5 rounded-full {{ $isOpen ? 'bg-success/10 text-success' : 'bg-error/10 text-error' }} px-3 py-1 text-xs font-bold">
                <span class="h-1.5 w-1.5 rounded-full {{ $isOpen ? 'bg-success' : 'bg-error' }} animate-pulse"></span>
                {{ $isOpen ? '🟢 ABIERTO' : '🔴 CERRADO' }}
            </span>
            @endif
            
            {{-- Botón WhatsApp con mensaje dinámico según horario --}}
            @php 
                $wa = $tenant->whatsapp_sales ?? $tenant->whatsapp ?? null;
                $waMessage = ($showHoursIndicator && !$isOpen) 
                    ? $closedMessage 
                    : 'Hola, vi tu vitrina';
            @endphp
            @if($wa)
                <a href="https://wa.me/{{ $wa }}?text={{ urlencode($waMessage) }}" target="_blank" rel="noopener noreferrer"
                   class="btn btn-sm btn-success hidden sm:flex font-bold rounded-xl shadow-lg shadow-success/20">WhatsApp</a>
            @endif
        </div>
    </div>
    {{-- Mobile menu dropdown --}}
    <div id="mobile-menu" class="hidden lg:hidden border-t border-base-200 bg-base-100 px-4 py-3 space-y-2">
      <a href="#home" class="block rounded-lg px-3 py-2 text-sm font-semibold text-base-content/70 hover:bg-primary/10 hover:text-primary transition-colors">Home</a>
      @foreach($visibleNavLinks as $link)
        <a href="{{ $link['anchor'] }}" 
           onclick="document.getElementById('mobile-menu').classList.add('hidden')"
           class="block rounded-lg px-3 py-2 text-sm font-semibold text-base-content/70 hover:bg-primary/10 hover:text-primary transition-colors">
          {{ $link['label'] }}
        </a>
      @endforeach
    </div>
</header>

