<section class="relative bg-white pt-24 pb-32 lg:pt-40 lg:pb-52 overflow-hidden">
    <x-ui.decorative-background />

    <div class="container mx-auto px-8 lg:px-12 relative z-10">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-12 lg:gap-16">
            
            {{-- COLUMNA TEXTO --}}
            <div class="w-full lg:w-[42%] space-y-8">
                <div class="inline-flex items-center gap-2 rounded-full bg-primary/10 px-4 py-1.5 text-xs font-bold text-primary">
                    {{ $tenant->business_name }} • {{ $tenant->years_experience ?: '5+' }} años
                </div>
                <h1 class="text-5xl lg:text-7xl font-black text-gray-900 leading-[1.05] tracking-tight">
                    Explore <span class="text-primary italic">Course</span> Categories
                </h1>
                <p class="text-lg text-gray-600 max-w-md leading-relaxed">
                    Create and sell online courses, build vibrant communities, and monetize your expertise.
                </p>
                <div class="flex gap-4 pt-4">
                    <button class="btn btn-primary btn-lg px-10 rounded-2xl shadow-xl shadow-primary/20 transition-transform hover:-translate-y-1">Buy Now</button>
                    <button class="btn btn-outline btn-lg px-10 rounded-2xl border-gray-200">Learn More</button>
                </div>
            </div>

            {{-- COLUMNA IMAGEN: Relación Alargada Personalizada --}}
            <div class="w-full lg:w-[58%] flex justify-end">
                {{-- 
                   - lg:w-[58%]: Expandimos la imagen para que domine.
                   - aspect-[16/11]: Relación alargada pero con cuerpo.
                   - rounded-[3rem]: Curvatura Bento equilibrada.
                --}}
                <div class="relative w-full max-w-[750px] aspect-[16/11] overflow-hidden rounded-[3rem] shadow-2xl border-[8px] border-white transition-all duration-500 hover:scale-[1.01]">
                    <img 
                        src="{{ $customization->hero_filename ? asset('storage/' . $customization->hero_filename) : 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=800' }}" 
                        alt="Hero Image" 
                        class="w-full h-full object-cover"
                    >
                </div>
            </div>

        </div>
    </div>
</section>