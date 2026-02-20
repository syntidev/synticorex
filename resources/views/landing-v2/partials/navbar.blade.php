{{-- Navbar Partial - FlyonUI --}}
<header class="border-base-content/20 bg-base-100 py-0.25 fixed top-0 z-10 w-full border-b">
    <nav class="navbar mx-auto max-w-[1280px] rounded-b-xl px-4 sm:px-6 lg:px-8">
        <div class="w-full lg:flex lg:items-center lg:gap-2">
            {{-- Logo & Mobile Toggle --}}
            <div class="navbar-start items-center justify-between max-lg:w-full">
                <a class="text-base-content flex items-center gap-3 text-xl font-semibold" href="#home">
                    @if($customization->logo_filename)
                        <img 
                            src="{{ asset('storage/tenants/' . $tenant->id . '/' . $customization->logo_filename) }}" 
                            alt="{{ $tenant->business_name }}"
                            class="h-8 w-auto"
                        />
                    @else
                        <span class="text-primary">
                            <svg width="32" height="32" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="34" height="34" rx="8.5" fill="currentColor"/>
                                <path d="M17 8L25 24H9L17 8Z" fill="white" fill-opacity="0.9"/>
                            </svg>
                        </span>
                    @endif
                    <span>{{ $tenant->business_name }}</span>
                </a>
                
                <div class="flex items-center gap-5 lg:hidden">
                    {{-- Mobile CTA --}}
                    @if($tenant->whatsapp_sales)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tenant->whatsapp_sales) }}" 
                       target="_blank"
                       class="btn btn-primary btn-sm">
                        WhatsApp
                    </a>
                    @endif
                    
                    {{-- Mobile Menu Toggle --}}
                    <button type="button" 
                            class="collapse-toggle btn btn-outline btn-secondary btn-square" 
                            data-collapse="#navbar-collapse" 
                            aria-controls="navbar-collapse" 
                            aria-label="Toggle navigation">
                        <span class="icon-[tabler--menu-2] collapse-open:hidden size-5.5"></span>
                        <span class="icon-[tabler--x] collapse-open:block size-5.5 hidden"></span>
                    </button>
                </div>
            </div>
            
            {{-- Navigation Links --}}
            <div id="navbar-collapse" class="lg:navbar-center transition-height collapse hidden grow overflow-hidden font-medium duration-300 lg:flex">
                <div class="text-base-content flex gap-6 text-base max-lg:mt-4 max-lg:flex-col lg:items-center">
                    <a href="#home" class="hover:text-primary nav-link">Inicio</a>
                    <a href="#about" class="hover:text-primary nav-link">Nosotros</a>
                    @if($products->count() > 0)
                    <a href="#products" class="hover:text-primary nav-link">Productos</a>
                    @endif
                    @if($services->count() > 0)
                    <a href="#services" class="hover:text-primary nav-link">Servicios</a>
                    @endif
                    <a href="#contact" class="hover:text-primary nav-link">Contacto</a>
                    <a href="#faq" class="hover:text-primary nav-link">FAQ</a>
                </div>
            </div>
            
            {{-- Desktop CTA & Status --}}
            <div class="navbar-end flex items-center gap-4 max-lg:hidden">
                {{-- Open/Closed Badge --}}
                @if($tenant->is_open)
                    <span class="badge badge-success badge-soft">
                        <span class="badge badge-success badge-xs me-1 animate-pulse"></span>
                        Abierto
                    </span>
                @else
                    <span class="badge badge-error badge-soft">
                        Cerrado
                    </span>
                @endif
                
                {{-- WhatsApp CTA --}}
                @if($tenant->whatsapp_sales)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tenant->whatsapp_sales) }}" 
                   target="_blank"
                   class="btn btn-primary">
                    <span class="icon-[tabler--brand-whatsapp] size-5"></span>
                    WhatsApp
                </a>
                @endif
            </div>
        </div>
    </nav>
</header>
