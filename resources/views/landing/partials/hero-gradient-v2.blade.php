{{-- Hero Gradient Layout - Fondo degradado con imagen flotante decorativa --}}
<section id="home" class="relative min-h-screen overflow-hidden">
    {{-- Fondo con gradiente animado --}}
    <div class="absolute inset-0 bg-gradient-to-br from-primary via-secondary to-accent animate-gradient-xy"></div>
    
    {{-- Overlay para mejorar legibilidad --}}
    <div class="absolute inset-0 bg-base-content/20"></div>
    
    {{-- Patrón decorativo de fondo --}}
    <div class="absolute inset-0 opacity-10" 
         style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.4&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
    </div>
    
    {{-- Contenido principal --}}
    <div class="relative container mx-auto px-4 py-16 lg:py-24 min-h-screen flex items-center">
        <div class="grid lg:grid-cols-2 gap-12 items-center w-full">
            
            {{-- LADO IZQUIERDO: CONTENIDO --}}
            <div class="space-y-8 max-lg:text-center text-base-100 relative z-10">
                {{-- Badge animado --}}
                @if($tenant->tagline)
                <div class="inline-block animate-bounce-slow">
                    <span class="badge badge-lg bg-base-100/20 backdrop-blur-sm text-base-100 border-base-100/30">
                        ✨ {{ $tenant->tagline }}
                    </span>
                </div>
                @endif
                
                {{-- Título principal con efecto glow --}}
                <h1 class="text-5xl lg:text-7xl font-bold leading-tight drop-shadow-2xl">
                    @if($tenant->slogan)
                        {!! nl2br(e($tenant->slogan)) !!}
                    @else
                        <span class="block">Bienvenido a</span>
                        <span class="block mt-2 text-base-100/90">{{ $tenant->business_name }}</span>
                    @endif
                </h1>
                
                {{-- Descripción con backdrop blur --}}
                <p class="text-xl text-base-100/90 max-w-2xl max-lg:mx-auto backdrop-blur-sm bg-base-content/10 rounded-2xl p-6">
                    @if($customization->about_text ?? $tenant->description)
                        {{ Str::limit($customization->about_text ?? $tenant->description, 200) }}
                    @else
                        Transformamos tu visión en realidad con soluciones innovadoras y un servicio excepcional que supera todas las expectativas.
                    @endif
                </p>
                
                {{-- CTAs --}}
                <div class="flex flex-wrap gap-4 max-lg:justify-center">
                    {{-- CTA principal: WhatsApp → btn-primary --}}
                    @if($tenant->whatsapp)
                        <a href="https://wa.me/{{ $tenant->whatsapp }}?text={{ urlencode('Hola ' . $tenant->business_name . ', me gustaría obtener más información') }}"
                           target="_blank"
                           class="btn btn-primary btn-lg shadow-2xl">
                            <span class="iconify tabler--brand-whatsapp size-6"></span>
                            Contactar Ahora
                        </a>
                    @elseif($tenant->phone)
                        <a href="tel:{{ $tenant->phone }}"
                           class="btn btn-primary btn-lg shadow-2xl">
                            <span class="iconify tabler--phone size-6"></span>
                            Llamar Ahora
                        </a>
                    @endif

                    {{-- CTA secundario → btn-outline btn-secondary con glassmorphism para legibilidad en gradiente --}}
                    <a href="#about" class="btn btn-outline btn-secondary btn-lg bg-base-100/10 backdrop-blur-sm">
                        Descubrir Más
                        <span class="iconify tabler--arrow-right size-5"></span>
                    </a>
                </div>
                
                {{-- Features destacados --}}
                <div class="flex flex-wrap gap-6 pt-4 max-lg:justify-center">
                    <div class="flex items-center gap-2 text-base-100">
                        <span class="iconify tabler--check size-6 text-success"></span>
                        <span>Calidad Premium</span>
                    </div>
                    <div class="flex items-center gap-2 text-base-100">
                        <span class="iconify tabler--check size-6 text-success"></span>
                        <span>Servicio Rápido</span>
                    </div>
                    <div class="flex items-center gap-2 text-base-100">
                        <span class="iconify tabler--check size-6 text-success"></span>
                        <span>Atención 24/7</span>
                    </div>
                </div>
            </div>
            
            {{-- LADO DERECHO: IMÁGENES FLOTANTES --}}
            <div class="relative h-[500px] lg:h-[600px] hidden lg:block">
                {{-- Imagen principal flotante con animación --}}
                <div class="absolute top-0 right-0 w-80 h-80 rounded-3xl overflow-hidden shadow-2xl transform hover:scale-105 transition-transform duration-500 animate-float">
                    <img src="{{ $customization->hero_main_filename ? asset('storage/tenants/'.$tenant->id.'/'.$customization->hero_main_filename) : 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=800&q=80' }}" 
                         alt="{{ $tenant->business_name }}" 
                         class="w-full h-full object-cover">
                </div>
                
                {{-- Decoración de puntos flotantes --}}
                <div class="absolute top-10 left-10 w-16 h-16 bg-base-100/20 rounded-full animate-pulse"></div>
                <div class="absolute top-40 right-10 w-12 h-12 bg-base-100/20 rounded-full animate-pulse delay-100"></div>
                <div class="absolute bottom-32 left-20 w-20 h-20 bg-base-100/20 rounded-full animate-pulse delay-200"></div>
            </div>
        </div>
    </div>
    
    {{-- Onda decorativa inferior --}}
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
            <path d="M0,64L48,69.3C96,75,192,85,288,80C384,75,480,53,576,48C672,43,768,53,864,58.7C960,64,1056,64,1152,58.7C1248,53,1344,43,1392,37.3L1440,32L1440,120L1392,120C1344,120,1248,120,1152,120C1056,120,960,120,864,120C768,120,672,120,576,120C480,120,384,120,288,120C192,120,96,120,48,120L0,120Z" 
                  fill="currentColor" class="text-base-100"></path>
        </svg>
    </div>
</section>

{{-- Animaciones personalizadas --}}
<style>
@keyframes gradient-xy {
    0%, 100% { background-position: 0% 0%; }
    50% { background-position: 100% 100%; }
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

@keyframes float-delay-1 {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-15px); }
}

@keyframes float-delay-2 {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.animate-gradient-xy {
    background-size: 400% 400%;
    animation: gradient-xy 15s ease infinite;
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}

.animate-float-delay-1 {
    animation: float-delay-1 6s ease-in-out infinite;
    animation-delay: 1s;
}

.animate-float-delay-2 {
    animation: float-delay-2 6s ease-in-out infinite;
    animation-delay: 2s;
}

.animate-bounce-slow {
    animation: bounce 3s infinite;
}
</style>
