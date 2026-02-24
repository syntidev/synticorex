{{-- 1. Eliminamos el min-h forzado y usamos h-auto para matar el gap --}}
<section class="relative bg-base-100 pt-32 pb-16 lg:pt-40 lg:pb-20 h-auto flex items-center overflow-visible" style="isolation: isolate;">
    <x-ui.decorative-background />

    <div class="max-w-7xl mx-auto px-10 lg:px-16 relative z-10 w-full">
        <div class="flex flex-col lg:flex-row items-center justify-center gap-12 lg:gap-20">
            
            {{-- COLUMNA TEXTO --}}
            <div class="w-full lg:w-1/2 relative py-10">
                
                {{-- GEOMETRÍA: Forzada con z-index alto para que se renderice --}}
                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 600px; height: 600px; pointer-events: none; z-index: 0;">
                    {{-- Malla --}}
                    <div style="position: absolute; inset: 0; background-image: linear-gradient(to right, oklch(var(--p) / 0.1) 1px, transparent 1px), linear-gradient(to bottom, oklch(var(--p) / 0.1) 1px, transparent 1px); background-size: 40px 40px; mask-image: radial-gradient(circle, black, transparent 80%); -webkit-mask-image: radial-gradient(circle, black, transparent 80%); transform: perspective(1000px) rotateX(15deg);"></div>
                    {{-- Rombo --}}
                    <div style="position: absolute; inset: 15%; border: 2px solid oklch(var(--p) / 0.2); border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; transform: rotate(-6deg);"></div>
                </div>

                <div class="relative z-10 space-y-8">
                    <div class="inline-flex items-center gap-2 rounded-full bg-primary/10 px-4 py-1.5 text-[10px] font-bold text-primary uppercase tracking-[0.2em]">
                        {{ $tenant->business_name }}
                    </div>
                    <h1 class="text-5xl lg:text-7xl font-black text-base-content leading-tight tracking-tighter">
                        Explore <span class="text-primary italic">Course</span> <br> Categories
                    </h1>
                    <p class="text-lg text-base-content/70 max-w-sm leading-relaxed opacity-80">
                        Create and sell online courses, build vibrant communities, and monetize your expertise.
                    </p>
                    <div class="flex items-center gap-6 pt-4">
                        <button class="btn btn-primary btn-lg px-10 rounded-2xl shadow-xl shadow-primary/20">Buy Now</button>
                        <button class="font-bold text-base-content hover:text-primary transition-colors">Learn More →</button>
                    </div>
                </div>
            </div>

            {{-- COLUMNA IMAGEN: Grande, 4:3 y con Borde Blanco de 6px --}}
            <div class="w-full lg:w-1/2 flex justify-center lg:justify-end">
                <div class="relative w-full max-w-[600px] aspect-[4/3] overflow-hidden rounded-[2.5rem] shadow-2xl border-[6px] border-base-100 bg-base-100">
                    <img src="{{ $customization->hero_filename ? asset('storage/' . $customization->hero_filename) : 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=800' }}" 
                         class="w-full h-full object-cover">
                </div>
            </div>

        </div>
    </div>
</section>