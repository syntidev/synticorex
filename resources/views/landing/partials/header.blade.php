{{-- Header Partial --}}
<header class="sticky top-0 z-50 shadow-md" style="background-color: var(--color-header-bg);">
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
                    <h1 class="text-xl font-bold" style="color: var(--color-header-text)">{{ $tenant->business_name }}</h1>
                    @if($tenant->slogan)
                        <p class="text-sm" style="color: var(--color-header-text); opacity: 0.8;">{{ $tenant->slogan }}</p>
                    @endif
                </div>
            </div>
            
            {{-- Currency Toggle (if enabled) --}}
            @if($currencySettings['show_conversion_button'] && $currencySettings['mode'] === 'toggle')
                <button 
                    id="currency-toggle-btn"
                    onclick="toggleCurrency()"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                    style="background-color: var(--color-header-text); color: var(--color-header-bg);"
                >
                    Ver en Bs.
                </button>
            @endif
            
            {{-- Open/Closed Badge --}}
            <div class="flex items-center">
                @if($tenant->is_open)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-500 text-white">
                        <span class="w-2 h-2 bg-white rounded-full mr-2 animate-pulse"></span>
                        Abierto
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-500 text-white">
                        Cerrado
                    </span>
                @endif
            </div>
        </div>
    </div>
</header>
