{{-- Footer v2 — Preline 4.1.2 + Tailwind v4 --}}
<footer>
    <div class="mx-auto max-w-[1280px] px-4 py-4 sm:px-6 sm:py-6 lg:px-8 lg:py-8">
        <div class="flex items-center justify-between gap-3 max-md:flex-col">
            {{-- Logo & Business Name --}}
            <a href="#home" class="text-foreground flex items-center gap-3 text-xl font-semibold">
                {{-- Logo SVG inline --}}
                <svg xmlns="http://www.w3.org/2000/svg" 
                     viewBox="0 0 100 100" 
                     width="36" height="36">
                  <path d="M 30,22 L 78,22 L 78,70 Q 78,78 70,78 L 62,78 
                           L 62,38 L 22,38 L 22,30 Q 22,22 30,22 Z" 
                        fill="#1a1a1a"/>
                  <circle cx="38" cy="63" r="14" fill="#4A80E4"/>
                </svg>
                <span>{{ $tenant->business_name }}</span>
            </a>
            
            {{-- Navigation Links --}}
            <nav class="flex items-center gap-6 max-sm:flex-wrap max-sm:justify-center">
                <a href="#about" class="link link-animated text-foreground/80 font-medium">Nosotros</a>
                @if($products->count() > 0)
                <a href="#products" class="link link-animated text-foreground/80 font-medium">Productos</a>
                @endif
                @if($services->count() > 0)
                <a href="#services" class="link link-animated text-foreground/80 font-medium">Servicios</a>
                @endif
                <a href="#contact" class="link link-animated text-foreground/80 font-medium">Contacto</a>
                <a href="#faq" class="link link-animated text-foreground/80 font-medium">FAQ</a>
            </nav>
            
            {{-- Social Links --}}
            <div class="text-foreground flex h-5 gap-4">
                @php $sn = is_array($customization->social_networks ?? null) ? $customization->social_networks : []; @endphp
                @if($tenant->getActiveWhatsapp())
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tenant->getActiveWhatsapp()) }}" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   aria-label="WhatsApp"
                   class="hover:text-success transition-colors">
                    <span class="iconify tabler--brand-whatsapp size-5"></span>
                </a>
                @endif
                
                @if($sn['instagram'] ?? null)
                <a href="https://instagram.com/{{ ltrim($sn['instagram'], '@') }}" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   aria-label="Instagram"
                   class="hover:text-pink-500 transition-colors">
                    <span class="iconify tabler--brand-instagram size-5"></span>
                </a>
                @endif
                
                @if($sn['facebook'] ?? null)
                <a href="https://facebook.com/{{ $sn['facebook'] }}" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   aria-label="Facebook"
                   class="hover:text-primary transition-colors">
                    <span class="iconify tabler--brand-facebook size-5"></span>
                </a>
                @endif
                
                @if($sn['tiktok'] ?? null)
                <a href="https://tiktok.com/{{ $sn['tiktok'] }}" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   aria-label="TikTok"
                   class="hover:text-foreground transition-colors">
                    <span class="iconify tabler--brand-tiktok size-5"></span>
                </a>
                @endif
                
                @if($sn['twitter'] ?? null)
                <a href="https://twitter.com/{{ ltrim($sn['twitter'], '@') }}" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   aria-label="Twitter/X"
                   class="hover:text-foreground transition-colors">
                    <span class="iconify tabler--brand-x size-5"></span>
                </a>
                @endif
                
                @if($tenant->email)
                <a href="mailto:{{ $tenant->email }}" 
                   aria-label="Email"
                   class="hover:text-primary transition-colors">
                    <span class="iconify tabler--mail size-5"></span>
                </a>
                @endif
            </div>
        </div>
    </div>
    
    <div class="border-t border-border my-4 mx-auto max-w-[1280px]"></div>

    <div class="mx-auto max-w-[1280px] px-4 pt-2 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-center gap-x-4 gap-y-2 text-xs text-foreground/60">
            <a href="{{ route('marketing.about') }}" class="hover:text-primary transition-colors">Nosotros</a>
            <span class="opacity-50">•</span>
            <a href="{{ route('marketing.privacy') }}" class="hover:text-primary transition-colors">Privacidad</a>
            <span class="opacity-50">•</span>
            <a href="{{ route('marketing.terms') }}" class="hover:text-primary transition-colors">Terminos</a>
        </div>
    </div>
    
    <div class="mx-auto max-w-[1280px] px-4 py-8 sm:px-6 lg:px-8">
        <div class="text-foreground text-center text-base">
            &copy; {{ date('Y') }}
            <span class="text-primary font-semibold">{{ $tenant->business_name }}</span>
            <br class="md:hidden" />
            <span class="text-base-content/60">• Todos los derechos reservados</span>
        </div>
        
        {{-- Powered by SYNTIweb --}}
        <div class="mt-4 text-center">
            <a href="https://syntiweb.com" 
               target="_blank" 
               class="text-xs text-foreground/40 hover:text-primary transition-colors">
                Powered by <span class="font-semibold">SYNTIweb</span>
            </a>
        </div>
    </div>
</footer>
