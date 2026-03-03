{{-- Hero Split Layout — adapted from FlyonUI official landing template --}}
<section id="home">
    <div class="bg-base-200 py-8 sm:py-16 lg:py-24">
        <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-2 lg:gap-24 items-center">
                {{-- Contenido --}}
                <div class="space-y-6 max-lg:text-center">
                    @if($tenant->tagline)
                        <div class="bg-base-100 border-base-content/20 w-fit rounded-full border px-3 py-1 max-lg:mx-auto">
                            <span>✨ {{ $tenant->tagline }}</span>
                        </div>
                    @endif

                    <h1 class="text-base-content text-4xl font-bold leading-[1.15] md:text-5xl lg:text-6xl">
                        {!! nl2br(e($tenant->slogan ?? $tenant->business_name)) !!}
                    </h1>

                    <p class="text-base-content/80 text-lg max-w-xl max-lg:mx-auto">
                        {{ Str::limit($customization->about_text ?? $tenant->description, 250) }}
                    </p>

                    <div class="flex flex-wrap gap-4 max-lg:justify-center">
                        @if($tenant->whatsapp)
                            <a href="https://wa.me/{{ $tenant->whatsapp }}" class="inline-flex items-center py-3 px-6 rounded-lg font-medium transition-colors text-lg bg-blue-600 text-white hover:bg-blue-700">
                                <span class="icon-[tabler--brand-whatsapp] size-5"></span>
                                Contáctanos
                            </a>
                        @endif
                        <a href="#productos" class="inline-flex items-center py-3 px-6 rounded-lg font-medium transition-colors text-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
                            Ver Productos
                            <span class="icon-[tabler--arrow-down] size-5"></span>
                        </a>
                    </div>
                </div>

                {{-- Imagen --}}
                <div class="relative max-lg:order-first">
                    <img src="{{ $customization->hero_main_filename
                        ? asset('storage/tenants/'.$tenant->id.'/'.$customization->hero_main_filename)
                        : 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=1200' }}"
                         alt="{{ $tenant->business_name }}"
                         class="w-full h-[400px] lg:h-[500px] object-cover rounded-3xl shadow-2xl">
                </div>
            </div>
        </div>
    </div>
</section>
