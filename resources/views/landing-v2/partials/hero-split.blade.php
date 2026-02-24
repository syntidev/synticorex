{{-- Hero Split Layout - 50% Content / 50% Image --}}
<section id="home" class="relative min-h-screen bg-base-200">
    <div class="container mx-auto px-4 py-16 lg:py-24">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            
            {{-- LADO IZQUIERDO: CONTENIDO --}}
            <div class="space-y-6 max-lg:text-center">
                {{-- Badge Superior --}}
                @if($tenant->tagline)
                <span class="badge badge-primary badge-lg">
                    {{ $tenant->tagline }}
                </span>
                @elseif($tenant->created_at)
                <span class="badge badge-primary badge-lg">
                    🎉 Desde {{ $tenant->created_at->format('Y') }}
                </span>
                @endif
                
                {{-- Título Principal --}}
                <h1 class="text-4xl lg:text-6xl font-bold text-base-content leading-tight">
                    @if($tenant->slogan)
                        {!! nl2br(e($tenant->slogan)) !!}
                    @else
                        {{ $tenant->business_name }}
                    @endif
                </h1>
                
                {{-- Descripción --}}
                <p class="text-lg text-base-content/80 max-w-xl max-lg:mx-auto">
                    @if($customization->about_text)
                        {{ Str::limit($customization->about_text, 250) }}
                    @else
                        Bienvenido a una experiencia donde la calidad, frescura y hospitalidad se unen. Ya sea tu primera visita o la número cien, cada momento está diseñado para impresionarte.
                    @endif
                </p>
                
                {{-- CTAs --}}
                <div class="flex flex-wrap gap-4 max-lg:justify-center">
                    @if($tenant->whatsapp)
                        <a href="https://wa.me/{{ $tenant->whatsapp }}?text={{ urlencode('Hola ' . $tenant->business_name . ', me gustaría obtener más información') }}" 
                           target="_blank"
                           class="btn btn-primary btn-gradient btn-lg">
                            <span class="iconify tabler--brand-whatsapp size-5"></span>
                            Contactar por WhatsApp
                        </a>
                    @elseif($tenant->phone)
                        <a href="tel:{{ $tenant->phone }}" 
                           class="btn btn-primary btn-gradient btn-lg">
                            <span class="iconify tabler--phone size-5"></span>
                            Llamar Ahora
                        </a>
                    @endif
                    
                    <a href="#about" class="btn btn-outline btn-lg">
                        Conocer Más
                        <span class="iconify tabler--arrow-down size-5"></span>
                    </a>
                </div>
                
                {{-- Stats (opcional) --}}
                @if($tenant->created_at)
                <div class="flex flex-wrap gap-8 pt-4 max-lg:justify-center">
                    <div>
                        <div class="text-3xl font-bold text-primary">
                            {{ now()->year - $tenant->created_at->year }}+
                        </div>
                        <div class="text-sm text-base-content/60">Años de experiencia</div>
                    </div>
                    
                    <div>
                        <div class="text-3xl font-bold text-primary">100%</div>
                        <div class="text-sm text-base-content/60">Satisfacción garantizada</div>
                    </div>
                    
                    <div>
                        <div class="text-3xl font-bold text-primary">24/7</div>
                        <div class="text-sm text-base-content/60">Atención disponible</div>
                    </div>
                </div>
                @endif
            </div>
            
            {{-- LADO DERECHO: IMAGEN PRINCIPAL --}}
            <div class="relative">
                {{-- Decoración de fondo --}}
                <div class="absolute inset-0 bg-gradient-to-br from-primary/20 to-secondary/20 rounded-3xl blur-3xl -z-10"></div>
                
                {{-- Imagen Hero Principal --}}
                <div class="relative rounded-3xl overflow-hidden shadow-2xl">
                    <img src="{{ $customization->hero_main_filename ? asset('storage/tenants/'.$tenant->id.'/'.$customization->hero_main_filename) : ($customization->hero_filename ? asset('storage/tenants/'.$tenant->id.'/'.$customization->hero_filename) : 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=1920&q=80') }}" 
                         alt="{{ $tenant->business_name }}" 
                         class="w-full h-[500px] lg:h-[600px] object-cover">
                    
                    {{-- Overlay con gradiente sutil --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-base-300/50 to-transparent"></div>
                </div>
                
                {{-- Badge flotante (opcional) --}}
                <div class="absolute top-6 right-6 bg-base-100 rounded-xl shadow-lg p-4 hidden lg:block">
                    <div class="flex items-center gap-2">
                        <span class="text-2xl">⭐</span>
                        <div>
                            <div class="font-bold text-base-content">Calidad</div>
                            <div class="text-xs text-base-content/60">Garantizada</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
