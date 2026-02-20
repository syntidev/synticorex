{{-- Footer Partial - FlyonUI --}}
<footer>
    <div class="mx-auto max-w-[1280px] px-4 py-4 sm:px-6 sm:py-6 lg:px-8 lg:py-8">
        <div class="flex items-center justify-between gap-3 max-md:flex-col">
            {{-- Logo & Business Name --}}
            <a href="#home" class="text-base-content flex items-center gap-3 text-xl font-semibold">
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
            
            {{-- Navigation Links --}}
            <nav class="flex items-center gap-6 max-sm:flex-wrap max-sm:justify-center">
                <a href="#about" class="link link-animated text-base-content/80 font-medium">Nosotros</a>
                @if($products->count() > 0)
                <a href="#products" class="link link-animated text-base-content/80 font-medium">Productos</a>
                @endif
                @if($services->count() > 0)
                <a href="#services" class="link link-animated text-base-content/80 font-medium">Servicios</a>
                @endif
                <a href="#contact" class="link link-animated text-base-content/80 font-medium">Contacto</a>
                <a href="#faq" class="link link-animated text-base-content/80 font-medium">FAQ</a>
            </nav>
            
            {{-- Social Links --}}
            <div class="text-base-content flex h-5 gap-4">
                @if($tenant->whatsapp_sales)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tenant->whatsapp_sales) }}" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   aria-label="WhatsApp"
                   class="hover:text-success transition-colors">
                    <span class="icon-[tabler--brand-whatsapp] size-5"></span>
                </a>
                @endif
                
                @if($customization->social_networks['instagram'] ?? null)
                <a href="https://instagram.com/{{ ltrim($customization->social_networks['instagram'], '@') }}" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   aria-label="Instagram"
                   class="hover:text-pink-500 transition-colors">
                    <span class="icon-[tabler--brand-instagram] size-5"></span>
                </a>
                @endif
                
                @if($customization->social_networks['facebook'] ?? null)
                <a href="https://facebook.com/{{ $customization->social_networks['facebook'] }}" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   aria-label="Facebook"
                   class="hover:text-blue-600 transition-colors">
                    <span class="icon-[tabler--brand-facebook] size-5"></span>
                </a>
                @endif
                
                @if($customization->social_networks['tiktok'] ?? null)
                <a href="https://tiktok.com/{{ $customization->social_networks['tiktok'] }}" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   aria-label="TikTok"
                   class="hover:text-base-content transition-colors">
                    <span class="icon-[tabler--brand-tiktok] size-5"></span>
                </a>
                @endif
                
                @if($customization->social_networks['twitter'] ?? null)
                <a href="https://twitter.com/{{ ltrim($customization->social_networks['twitter'], '@') }}" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   aria-label="Twitter/X"
                   class="hover:text-base-content transition-colors">
                    <span class="icon-[tabler--brand-x] size-5"></span>
                </a>
                @endif
                
                @if($tenant->email)
                <a href="mailto:{{ $tenant->email }}" 
                   aria-label="Email"
                   class="hover:text-primary transition-colors">
                    <span class="icon-[tabler--mail] size-5"></span>
                </a>
                @endif
            </div>
        </div>
    </div>
    
    <div class="divider mx-auto max-w-[1280px]"></div>
    
    <div class="mx-auto max-w-[1280px] px-4 py-8 sm:px-6 lg:px-8">
        <div class="text-base-content text-center text-base">
            &copy; {{ date('Y') }}
            <span class="text-primary font-semibold">{{ $tenant->business_name }}</span>
            <br class="md:hidden" />
            <span class="text-base-content/60">• Todos los derechos reservados</span>
        </div>
        
        {{-- Powered by SYNTIweb --}}
        <div class="mt-4 text-center">
            <a href="https://syntiweb.com" 
               target="_blank" 
               class="text-xs text-base-content/40 hover:text-primary transition-colors">
                Powered by <span class="font-semibold">SYNTIweb</span>
            </a>
        </div>
    </div>
</footer>
