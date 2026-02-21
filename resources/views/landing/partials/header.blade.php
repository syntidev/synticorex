<header class="fixed top-0 z-[100] w-full border-b border-gray-100 !bg-white/95 backdrop-blur-md">
    <div class="container mx-auto flex items-center justify-between px-6 py-4">
        {{-- Logo Dinámico (Recuperado del viejo) --}}
        <div class="flex items-center gap-3">
            <a href="#home" class="flex items-center gap-3">
                @if($customization->logo_filename)
                    <img src="{{ asset('storage/tenants/'.$tenant->id.'/'.$customization->logo_filename) }}" class="h-10 w-10 object-contain rounded-lg">
                @else
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary text-white shadow-lg shadow-primary/30">
                        <span class="text-xl font-bold">{{ substr($tenant->business_name, 0, 1) }}</span>
                    </div>
                @endif
                <span class="text-lg font-bold tracking-tight text-gray-900">{{ $tenant->business_name }}</span>
            </a>
        </div>

        {{-- Menú Central con Rutas del Viejo y Condicionales --}}
        <nav class="hidden space-x-8 lg:flex">
            <a href="#home" class="text-sm font-semibold text-gray-600 hover:text-primary transition-colors">Home</a>
            <a href="#products" class="text-sm font-semibold text-gray-600 hover:text-primary transition-colors">Productos</a>
            <a href="#services" class="text-sm font-semibold text-gray-600 hover:text-primary transition-colors">Servicios</a>
            
            {{-- Solo si no es plan Oportunidad --}}
            @if($tenant->plan->slug !== 'oportunidad')
                <a href="#about" class="text-sm font-semibold text-gray-600 hover:text-primary transition-colors">Nosotros</a>
            @endif
        </nav>

        {{-- Estado y WhatsApp (Recuperado) --}}
        <div class="flex items-center gap-4">
            <span class="inline-flex items-center gap-1.5 rounded-full {{ $tenant->is_open ? 'bg-success/10 text-success' : 'bg-error/10 text-error' }} px-3 py-1 text-xs font-bold">
                <span class="h-1.5 w-1.5 rounded-full {{ $tenant->is_open ? 'bg-success' : 'bg-error' }} animate-pulse"></span>
                {{ $tenant->is_open ? 'ABIERTO' : 'CERRADO' }}
            </span>
            
            @if($tenant->whatsapp)
                <a href="https://wa.me/{{ $tenant->whatsapp }}" class="btn btn-sm btn-success hidden sm:flex">WhatsApp</a>
            @endif
        </div>
    </div>
</header>