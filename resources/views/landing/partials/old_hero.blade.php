{{-- 
════════════════════════════════════════════════════════════════════════════
HERO SECTION - FlyonUI Strict Implementation
════════════════════════════════════════════════════════════════════════════
Layout: 40% contenido (izquierda) / 60% imagen (derecha) en desktop
Mobile: Stacked (imagen arriba, contenido abajo)
Clases: Solo FlyonUI + Tailwind utility classes oficiales
JS: Solo el requerido por FlyonUI (ninguno custom)
════════════════════════════════════════════════════════════════════════════
--}}

<section id="home" class="bg-primary overflow-hidden">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Grid: 2 columnas en desktop, stacked en mobile --}}
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 lg:gap-12 items-center min-h-[60vh] py-12 lg:py-16">
            
            {{-- ══════════════════════════════════════════════════════════════ --}}
            {{-- COLUMNA CONTENIDO (40% → 2 cols de 5) --}}
            {{-- ══════════════════════════════════════════════════════════════ --}}
            <div class="lg:col-span-2 text-center lg:text-left order-2 lg:order-1 space-y-6">
                
                {{-- Badge/Tagline (si existe) --}}
                @if($tenant->tagline)
                    <div class="badge badge-lg badge-primary badge-outline">
                        <span class="icon-[tabler--sparkles] size-4"></span>
                        {{ $tenant->tagline }}
                    </div>
                @endif
                
                {{-- Título Principal - Business Name --}}
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight text-primary-content">
                    {{ $tenant->business_name }}
                </h1>
                
                {{-- Slogan/Descripción --}}
                @if($tenant->slogan)
                    <p class="text-lg md:text-xl leading-relaxed max-w-xl mx-auto lg:mx-0 text-primary-content/90">
                        {{ $tenant->slogan }}
                    </p>
                @endif
                
                {{-- Botones CTA - Condicionales según datos disponibles --}}
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-4">
                    
                    {{-- Botón Productos (solo si existen) --}}
                    @if($products && $products->count() > 0)
                        <a href="#products" 
                           class="btn btn-secondary btn-gradient btn-lg gap-2">
                            <span class="icon-[tabler--shopping-bag] size-5"></span>
                            Ver Productos ({{ $products->count() }})
                        </a>
                    @endif
                    
                    {{-- Botón Servicios (solo si existen) --}}
                    @if($services && $services->count() > 0)
                        <a href="#services" 
                           class="btn btn-outline btn-lg gap-2 text-primary-content border-primary-content hover:bg-primary-content hover:text-primary">
                            <span class="icon-[tabler--tools] size-5"></span>
                            Nuestros Servicios
                        </a>
                    @endif
                    
                    {{-- Fallback: WhatsApp si no hay productos ni servicios --}}
                    @if((!$products || $products->count() === 0) && (!$services || $services->count() === 0) && $tenant->whatsapp_sales)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tenant->whatsapp_sales) }}?text={{ urlencode('Hola! Vengo de tu página web') }}"
                           target="_blank"
                           class="btn btn-success btn-gradient btn-lg gap-2">
                            <span class="icon-[tabler--brand-whatsapp] size-6"></span>
                            Contáctanos por WhatsApp
                        </a>
                    @endif
                    
                </div>
                
                {{-- Stats Mini (opcional - años de experiencia) --}}
                @if($tenant->years_experience)
                    <div class="flex items-center gap-3 justify-center lg:justify-start pt-6">
                        <div class="flex items-center gap-2">
                            <span class="text-3xl font-bold text-primary-content">{{ $tenant->years_experience }}+</span>
                            <span class="text-sm text-primary-content/80">Años de<br>Experiencia</span>
                        </div>
                        @if($products && $products->count() > 0)
                            <div class="divider divider-horizontal opacity-30"></div>
                            <div class="flex items-center gap-2">
                                <span class="text-3xl font-bold text-primary-content">{{ $products->count() }}</span>
                                <span class="text-sm text-primary-content/80">Productos<br>Disponibles</span>
                            </div>
                        @endif
                    </div>
                @endif
                
            </div>
            
            {{-- ══════════════════════════════════════════════════════════════ --}}
            {{-- COLUMNA IMAGEN (60% → 3 cols de 5) --}}
            {{-- ══════════════════════════════════════════════════════════════ --}}
            <div class="lg:col-span-3 order-1 lg:order-2">
                
                {{-- Imagen Hero con clases FlyonUI --}}
                <figure class="relative w-full h-[400px] lg:h-[600px] rounded-3xl overflow-hidden shadow-2xl">
                    
                    @if($customization?->hero_filename)
                        {{-- Imagen del tenant --}}
                        <img 
                            src="{{ asset('storage/tenants/' . $tenant->id . '/' . $customization->hero_filename) }}" 
                            alt="{{ $tenant->business_name }}"
                            class="w-full h-full object-cover"
                        />
                    @else
                        {{-- Placeholder Unsplash (imagen genérica de negocios) --}}
                        <img 
                            src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=1200&h=800&fit=crop&q=80" 
                            alt="Negocio profesional"
                            class="w-full h-full object-cover"
                        />
                    @endif
                    
                    {{-- Decorative Overlay (sutil) --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                    
                    {{-- Badge flotante "Abierto/Cerrado" sobre imagen --}}
                    <div class="absolute top-6 right-6">
                        @if($tenant->is_open)
                            <div class="badge badge-success badge-lg gap-2 shadow-lg">
                                <span class="relative flex h-3 w-3">
                                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-success-content opacity-75"></span>
                                    <span class="relative inline-flex h-3 w-3 rounded-full bg-success-content"></span>
                                </span>
                                <span class="font-bold">ABIERTO</span>
                            </div>
                        @else
                            <div class="badge badge-error badge-lg shadow-lg">
                                <span class="font-bold">CERRADO</span>
                            </div>
                        @endif
                    </div>
                    
                </figure>
                
            </div>
            
        </div>
        
    </div>
</section>

{{-- 
════════════════════════════════════════════════════════════════════════════
NOTAS DE IMPLEMENTACIÓN:
════════════════════════════════════════════════════════════════════════════

✅ CLASES FLYONUI USADAS:
   - btn, btn-primary, btn-outline, btn-lg (botones)
   - badge, badge-lg (estado abierto/cerrado)
   - container, grid, grid-cols-* (layout responsive)
   - rounded-3xl, shadow-2xl (figura)
   - icon-[tabler--*] (iconos)
   - animate-ping (animación estado)
   - divider, divider-horizontal (separador stats)

✅ VARIABLES CSS TENANT:
   - var(--color-primary) → Background section
   - var(--color-secondary) → Botón productos

✅ CONDICIONALES:
   - Tagline: Solo si existe
   - Slogan: Solo si existe
   - Botón Productos: Solo si $products->count() > 0
   - Botón Servicios: Solo si $services->count() > 0
   - Fallback WhatsApp: Si no hay productos ni servicios
   - Stats: Solo si years_experience existe

✅ RESPONSIVE:
   - Mobile: Stacked vertical (imagen arriba)
   - Desktop: Grid 2/5 (40%) y 3/5 (60%)
   - Breakpoints: sm, md, lg (estándar Tailwind)

✅ NO INCLUYE:
   ❌ JavaScript custom
   ❌ Animaciones CSS inventadas
   ❌ Efectos de parallax
   ❌ Canvas particles
   ❌ Código fuera del estándar FlyonUI

════════════════════════════════════════════════════════════════════════════
--}}
