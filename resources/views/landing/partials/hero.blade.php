{{-- Cambiamos el fondo para que los objetos resalten por contraste --}}
<section class="relative overflow-hidden bg-base-200/50 py-12 lg:py-24">
    
    {{-- Llamada al componente acentuado --}}
    <x-ui.decorative-background />

    <div class="container relative z-20 mx-auto px-6"> {{-- Subimos a z-20 --}}
        <div class="flex flex-col items-center gap-12 lg:flex-row">
            
            <div class="w-full lg:w-1/2">
                <div class="inline-flex items-center gap-2 rounded-full bg-primary/10 px-4 py-1.5 text-sm font-medium text-primary mb-6">
                    <span class="relative flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-primary opacity-75"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-primary"></span>
                    </span>
                    {{ $tenant->business_name }} • {{ $tenant->years_experience ?: '5+' }} años
                </div>
                
                <h1 class="text-4xl font-black tracking-tight text-base-content lg:text-6xl">
                    Explore <span class="text-primary italic">Course</span> Categories
                </h1>
                
                <p class="mt-6 text-lg leading-relaxed text-base-content/70 max-w-xl">
                    Create and sell online courses, build vibrant communities, and monetize your expertise on a single, scalable platform.
                </p>

                <div class="mt-10 flex flex-wrap gap-4">
                    <button class="btn btn-primary btn-lg shadow-xl shadow-primary/40 group">
                        Buy Now
                        <span class="icon-[tabler--arrow-right] transition-transform group-hover:translate-x-1"></span>
                    </button>
                    <button class="btn btn-outline btn-lg border-base-300">Learn More</button>
                </div>
            </div>

            <div class="relative w-full lg:w-1/2 flex justify-center items-center py-10">
                <div class="relative z-10 group">
                    <img 
                        src="{{ $customization->hero_filename ? asset('storage/' . $customization->hero_filename) : 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=800' }}" 
                        alt="Hero Image" 
                        class="h-[400px] lg:h-[500px] w-auto object-contain drop-shadow-2xl transition-transform duration-500 group-hover:scale-105"
                    >
                </div>
            </div>

        </div>
    </div>
</section>