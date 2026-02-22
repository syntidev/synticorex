<footer class="bg-base-100 border-t border-base-content/10 py-12">
    <div class="container mx-auto px-6">
        {{-- Fila Principal --}}
        <div class="flex flex-col md:flex-row items-center justify-between gap-10 mb-12">
            
            {{-- Identidad de Marca (Tenant) --}}
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-primary rounded-2xl flex items-center justify-center shadow-lg shadow-primary/20">
                    <span class="icon-[lucide--layers] text-white text-2xl"></span>
                </div>
                <span class="text-2xl font-black text-base-content italic tracking-tighter uppercase leading-none">
                    {{ $tenant->business_name }}
                </span>
            </div>

            {{-- Navegación: Etiquetas con Espaciado (tracking-widest) --}}
            <nav class="flex flex-wrap justify-center gap-x-12 gap-y-4">
                <a href="#productos" class="text-sm font-bold text-base-content/70 hover:text-primary transition-all uppercase tracking-[0.3em]">Productos | </a>
                <a href="#servicios" class="text-sm font-bold text-base-content/70 hover:text-primary transition-all uppercase tracking-[0.3em]">Servicios | </a>
                <a href="#faq" class="text-sm font-bold text-base-content/70 hover:text-primary transition-all uppercase tracking-[0.2em]">Ayuda </a>
            </nav>

            {{-- Social: Símbolos Digitales --}}
            <div class="flex items-center gap-6">
                @if($tenant->social_facebook)
                    <a href="{{ $tenant->social_facebook }}" class="text-base-content/30 hover:text-primary transition-transform hover:scale-110">
                        <span class="icon-[lucide--facebook] text-2xl"></span>
                    </a>
                @endif
                @if($tenant->social_instagram)
                    <a href="{{ $tenant->social_instagram }}" class="text-base-content/30 hover:text-primary transition-transform hover:scale-110">
                        <span class="icon-[lucide--instagram] text-2xl"></span>
                    </a>
                @endif
            </div>
        </div>

        {{-- Créditos de Ingeniería y Orgullo Nacional --}}
        <div class="pt-8 border-t border-base-content/5 flex flex-col items-center gap-4 text-center">
            <div class="flex flex-wrap items-center justify-center gap-2 text-[11px] font-medium text-base-content/40 italic">
                
				<p class="text-[10px] uppercase tracking-[0.5em] text-base-content/30 font-medium">
                &copy; {{ date('Y') }} — {{ $tenant->business_name }} | Desarrollado con Ingeniería de Última Generación por 
                <a href="https://syntiweb.com" target="_blank" class="font-black text-base-content/60 hover:text-primary transition-colors tracking-normal">SYNTIweb.com</a>
				</p>
            </div>
            
            
        </div>
    </div>
</footer>