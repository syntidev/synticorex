<section class="relative overflow-hidden bg-white pt-28 pb-12 lg:pt-40 lg:pb-24">
    
    {{-- El componente decorativo ahora vive en z-[1] --}}
    <x-ui.decorative-background />

    {{-- Subimos el contenido a z-10 para que los marcos pasen por DETRÁS --}}
    <div class="container relative z-10 mx-auto px-6"> 
        <div class="flex flex-col items-center gap-12 lg:flex-row">
            
            <div class="w-full lg:w-1/2">
                <div class="inline-flex items-center gap-2 rounded-full bg-primary/10 px-4 py-1.5 text-sm font-medium text-primary mb-6">
                    <span class="relative flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-primary opacity-75"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-primary"></span>
                    </span>
                    {{ $tenant->business_name }} • {{ $tenant->years_experience ?: '5+' }} años
                </div>
                
                <h1 class="text-5xl font-black tracking-tight text-gray-900 lg:text-7xl">
                    Explore <span class="text-primary italic">Course</span> Categories
                </h1>
                
                <p class="mt-8 text-lg leading-relaxed text-gray-600 max-w-xl">
                    Create and sell online courses, build vibrant communities, and monetize your expertise on a single, scalable platform.
                </p>

                <div class="mt-10 flex flex-wrap gap-4">
                    <button class="btn btn-primary btn-lg shadow-xl shadow-primary/30 group px-8">
                        Buy Now
                        <span class="icon-[tabler--arrow-right] transition-transform group-hover:translate-x-1"></span>
                    </button>
                    <button class="btn btn-outline btn-lg border-gray-200 hover:bg-gray-50 text-gray-700 px-8">Learn More</button>
                </div>
            </div>

            <div class="relative w-full lg:w-1/2 flex justify-center items-center">
                {{-- La imagen principal con su sombra para dar profundidad --}}
                <div class="relative group">
                    <img 
                        src="{{ $customization->hero_filename ? asset('storage/' . $customization->hero_filename) : 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=800' }}" 
                        alt="Hero Image" 
                        class="h-[450px] lg:h-[550px] w-auto object-contain drop-shadow-2xl transition-transform duration-500 group-hover:scale-105"
                    >
                </div>
            </div>

        </div>
    </div>
</section>