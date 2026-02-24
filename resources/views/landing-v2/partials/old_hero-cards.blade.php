{{-- Hero Cards Layout - Background hero con 3 cards destacadas sobrepuestas --}}
<section id="home" class="relative pb-48 lg:pb-64">
    {{-- Hero Background Superior --}}
    <div class="relative min-h-[70vh] lg:min-h-[75vh] bg-cover bg-center flex items-center"
         @if($customization->hero_main_filename)
         style="background-image: linear-gradient(135deg, rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.3)), url('{{ asset('storage/tenants/'.$tenant->id.'/'.$customization->hero_main_filename) }}');"
         @elseif($customization->hero_filename)
         style="background-image: linear-gradient(135deg, rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.3)), url('{{ asset('storage/tenants/'.$tenant->id.'/'.$customization->hero_filename) }}');"
         @else
         style="background-image: linear-gradient(135deg, rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.3)), url('https://images.unsplash.com/photo-1497366216548-37526070297c?w=1920&q=80');"
         @endif
    >
        {{-- Contenido centrado --}}
        <div class="container mx-auto px-4 text-center text-base-100 relative z-10">
            <div class="max-w-4xl mx-auto space-y-6">
                {{-- Badge --}}
                @if($tenant->tagline)
                <div class="inline-block">
                    <span class="badge badge-lg bg-base-100/10 backdrop-blur-md text-base-100 border-base-100/30">
                        {{ $tenant->tagline }}
                    </span>
                </div>
                @endif
                
                {{-- Título épico --}}
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold leading-tight drop-shadow-2xl">
                    @if($tenant->slogan)
                        {!! nl2br(e($tenant->slogan)) !!}
                    @else
                        {{ $tenant->business_name }}
                    @endif
                </h1>
                
                {{-- Subtítulo --}}
                <p class="text-xl lg:text-2xl text-base-100/90 max-w-2xl mx-auto">
                    @if($customization->about_text)
                        {{ Str::limit($customization->about_text, 180) }}
                    @else
                        Descubre una nueva forma de hacer las cosas con nuestros servicios premium diseñados para ti.
                    @endif
                </p>
                
                {{-- CTAs principales --}}
                <div class="flex flex-wrap gap-4 justify-center pt-4">
                    @if($tenant->whatsapp)
                        <a href="https://wa.me/{{ $tenant->whatsapp }}?text={{ urlencode('Hola ' . $tenant->business_name . ', me gustaría obtener más información') }}" 
                           target="_blank"
                           class="btn btn-lg bg-base-100 text-primary hover:bg-base-100/90 border-0 shadow-xl">
                            <span class="iconify tabler--brand-whatsapp size-6"></span>
                            Contactar Ahora
                        </a>
                    @elseif($tenant->phone)
                        <a href="tel:{{ $tenant->phone }}" 
                           class="btn btn-lg bg-base-100 text-primary hover:bg-base-100/90 border-0 shadow-xl">
                            <span class="iconify tabler--phone size-6"></span>
                            Llamar Ahora
                        </a>
                    @endif
                    
                    <a href="#about" class="btn btn-lg bg-base-100/10 backdrop-blur-sm text-base-100 border-base-100/30 hover:bg-base-100/20">
                        Ver Más
                        <span class="iconify tabler--chevron-down size-5"></span>
                    </a>
                </div>
            </div>
        </div>
        
        {{-- Overlay pattern --}}
        <div class="absolute inset-0 opacity-5" 
             style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;100&quot; height=&quot;100&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Ccircle cx=&quot;10&quot; cy=&quot;10&quot; r=&quot;2&quot; fill=&quot;%23ffffff&quot;/%3E%3C/svg%3E');">
        </div>
    </div>
    
    {{-- Cards destacadas (sobrepuestas al hero) --}}
    <div class="container mx-auto px-4 relative -mt-32 lg:-mt-40">
        <div class="grid md:grid-cols-3 gap-6 lg:gap-8">
            
            {{-- Card 1: Solo contenido --}}
            <div class="card bg-base-100 shadow-2xl hover:shadow-primary/20 hover:-translate-y-2 transition-all duration-300">
                <div class="card-body">
                    <div class="text-4xl mb-2">🎯</div>
                    <h3 class="card-title text-base-content">
                        @if($tenant->tagline)
                            {{ Str::limit($tenant->tagline, 30) }}
                        @else
                            Calidad Premium
                        @endif
                    </h3>
                    <p class="text-base-content/70">
                        Ofrecemos productos y servicios de la más alta calidad para garantizar tu satisfacción total.
                    </p>
                    <div class="card-actions justify-end pt-2">
                        <a href="#about" class="btn btn-sm btn-primary">
                            Conocer más
                            <span class="iconify tabler--arrow-right size-4"></span>
                        </a>
                    </div>
                </div>
            </div>
            
            {{-- Card 2: Solo contenido --}}
            <div class="card bg-base-100 shadow-2xl hover:shadow-primary/20 hover:-translate-y-2 transition-all duration-300 md:scale-105">
                {{-- Badge destacado --}}
                <div class="absolute top-4 right-4 z-10">
                    <span class="badge badge-primary badge-lg shadow-lg">Destacado</span>
                </div>
                
                <div class="card-body">
                    <div class="text-4xl mb-2">⚡</div>
                    <h3 class="card-title text-base-content">
                        Servicio Rápido
                    </h3>
                    <p class="text-base-content/70">
                        Respuesta inmediata y atención personalizada. Tu tiempo es valioso y lo respetamos.
                    </p>
                    <div class="card-actions justify-end pt-2">
                        @if($tenant->whatsapp)
                            <a href="https://wa.me/{{ $tenant->whatsapp }}" 
                               target="_blank"
                               class="btn btn-sm btn-primary">
                                Contactar
                                <span class="iconify tabler--brand-whatsapp size-4"></span>
                            </a>
                        @else
                            <a href="#contact-us" class="btn btn-sm btn-primary">
                                Contactar
                                <span class="iconify tabler--message size-4"></span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            
            {{-- Card 3: Sin imagen, solo contenido --}}
            <div class="card bg-base-100 shadow-2xl hover:shadow-primary/20 hover:-translate-y-2 transition-all duration-300">
                <div class="card-body">
                    <div class="text-4xl mb-2">💎</div>
                    <h3 class="card-title text-base-content">
                        Experiencia Única
                    </h3>
                    <p class="text-base-content/70">
                        @if($tenant->created_at)
                            Con {{ now()->year - $tenant->created_at->year }}+ años de experiencia, garantizamos excelencia en cada detalle.
                        @else
                            Cada interacción está diseñada para superar tus expectativas y crear momentos memorables.
                        @endif
                    </p>
                    <div class="card-actions justify-end pt-2">
                        <a href="#products" class="btn btn-sm btn-primary">
                            Ver productos
                            <span class="iconify tabler--arrow-right size-4"></span>
                        </a>
                    </div>
                </div>
            </div>
            
        </div>
        
        {{-- Stats bar (opcional) --}}
        @if($tenant->created_at)
        <div class="mt-12 bg-base-200 rounded-3xl p-8 shadow-xl">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                @if($tenant->created_at)
                <div>
                    <div class="text-4xl font-bold text-primary">{{ now()->year - $tenant->created_at->year }}+</div>
                    <div class="text-sm text-base-content/60 mt-1">Años de experiencia</div>
                </div>
                @endif
                
                <div>
                    <div class="text-4xl font-bold text-primary">100%</div>
                    <div class="text-sm text-base-content/60 mt-1">Satisfacción garantizada</div>
                </div>
                
                <div>
                    <div class="text-4xl font-bold text-primary">24/7</div>
                    <div class="text-sm text-base-content/60 mt-1">Atención disponible</div>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
