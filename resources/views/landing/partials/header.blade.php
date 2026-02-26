<header id="main-nav" 
        class="fixed top-0 z-[100] w-full border-b border-base-200 !bg-base-100/95 backdrop-blur-md"
        style="{{ $tenant->plan_id >= 2 ? 'top: 40px;' : '' }} transition: top 0.3s ease;">
    <div class="container mx-auto flex items-center justify-between px-6 py-4">
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

        {{-- Menú Central --}}
        <nav class="hidden space-x-8 lg:flex">
            <a href="#home" class="text-sm font-semibold text-base-content/70 hover:text-primary transition-colors">Home</a>
            <a href="#products" class="text-sm font-semibold text-base-content/70 hover:text-primary transition-colors">Productos</a>
            <a href="#services" class="text-sm font-semibold text-base-content/70 hover:text-primary transition-colors">Servicios</a>
            
            @if($tenant->plan->slug !== 'oportunidad')
                <a href="#about" class="text-sm font-semibold text-base-content/70 hover:text-primary transition-colors">Nosotros</a>
            @endif
        </nav>

        {{-- Acciones y Moneda --}}
        <div class="flex items-center gap-4">
            {{-- Switch de Moneda — solo visible cuando el tenant configura "Ambos con toggle" --}}
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
            @endif

            <span class="inline-flex items-center gap-1.5 rounded-full {{ $tenant->is_open ? 'bg-success/10 text-success' : 'bg-error/10 text-error' }} px-3 py-1 text-xs font-bold">
                <span class="h-1.5 w-1.5 rounded-full {{ $tenant->is_open ? 'bg-success' : 'bg-error' }} animate-pulse"></span>
                {{ $tenant->is_open ? 'ABIERTO' : 'CERRADO' }}
            </span>
            
            @if($tenant->whatsapp)
                <a href="https://wa.me/{{ $tenant->whatsapp }}" class="btn btn-sm btn-success hidden sm:flex font-bold rounded-xl shadow-lg shadow-success/20">WhatsApp</a>
            @endif
        </div>
    </div>
</header>

