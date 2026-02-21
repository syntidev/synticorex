{{-- Header Partial - FlyonUI White Theme (Always) --}}
<header class="border-base-content/20 bg-base-100 sticky top-0 z-50 border-b shadow-sm">
    <div class="container mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            {{-- Logo / Business Name --}}
            <div class="flex items-center space-x-3">
                @if($customization?->logo_filename)
                    <img 
                        src="{{ asset('storage/tenants/' . $tenant->id . '/' . $customization->logo_filename) }}" 
                        alt="{{ $tenant->business_name }}"
                        class="h-12 w-auto"
                    >
                @endif
                <div>
                    <h1 class="text-xl font-bold text-base-content">{{ $tenant->business_name }}</h1>
                    @if($tenant->slogan)
                        <p class="text-sm text-base-content/80">{{ $tenant->slogan }}</p>
                    @endif
                </div>
            </div>
            
            {{-- Navigation Links (conditional by plan) --}}
            <nav class="hidden md:flex items-center space-x-6">
                <a href="#home" class="link link-hover text-sm font-medium text-base-content">
                    Inicio
                </a>
                <a href="#products" class="link link-hover text-sm font-medium text-base-content">
                    Productos
                </a>
                <a href="#services" class="link link-hover text-sm font-medium text-base-content">
                    Servicios
                </a>
                
                {{-- Nosotros (not in Oportunidad plan) --}}
                @if($tenant->plan->slug !== 'oportunidad')
                    <a href="#about" class="link link-hover text-sm font-medium text-base-content">
                        Nosotros
                    </a>
                @endif
                
                {{-- FAQ (only in Vision plan) --}}
                @if($tenant->plan->slug === 'vision')
                    <a href="#faq" class="link link-hover text-sm font-medium text-base-content">
                        FAQ
                    </a>
                @endif
            </nav>
            
            {{-- Right Side: Currency Toggle + Badges --}}
            <div class="flex items-center space-x-3">
                {{-- Currency Toggle (if enabled) --}}
                @if($currencySettings['show_conversion_button'] && $currencySettings['mode'] === 'toggle')
                    <button 
                        id="currency-toggle-btn"
                        onclick="toggleCurrency()"
                        class="btn btn-sm btn-primary"
                    >
                        Ver en Bs.
                    </button>
                @endif
                
                {{-- Additional Currency Toggle (PRIVATE - both_toggle mode) --}}
                @if($tenant->saved_display_mode === 'both_toggle')
                    <button 
                        id="currency-toggle"
                        onclick="toggleCurrency()"
                        class="btn btn-xs btn-outline"
                    >
                        <span id="currency-symbol">REF</span>
                    </button>
                @endif
                
                {{-- Delivery Badge (not in Oportunidad + has_delivery) --}}
                @if($tenant->plan->slug !== 'oportunidad' && $tenant->has_delivery)
                    <span class="badge badge-info gap-1">
                        <span class="icon-[tabler--truck-delivery] size-4"></span>
                        Delivery
                    </span>
                @endif
                
                {{-- Open/Closed Badge --}}
                <div class="flex items-center">
                    @if($tenant->is_open)
                        <span id="open-status-badge" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-500 text-white">
                            <span class="w-2 h-2 bg-white rounded-full mr-2 animate-pulse"></span>
                            Abierto
                        </span>
                    @else
                        <span id="open-status-badge" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-500 text-white">
                            Cerrado
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</header>
