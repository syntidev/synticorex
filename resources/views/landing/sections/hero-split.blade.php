{{-- Hero Split Layout — Preline 4.1.2 + Tailwind v4 --}}
<section id="home">
    <div class="bg-background py-8 sm:py-16 lg:py-24">
        <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-2 lg:gap-24 items-center">
                {{-- Contenido --}}
                <div class="space-y-6 max-lg:text-center">
                    @if($tenant->city)
                        <div class="bg-background border-border w-fit rounded-full border px-3 py-1 max-lg:mx-auto">
                            <span class="text-foreground">✨ {{ $tenant->city }}</span>
                        </div>
                    @endif

                    <h1 class="text-foreground text-4xl font-bold leading-[1.15] md:text-5xl lg:text-6xl">
                        {!! nl2br(e($customization->getHeroTitle() ?? $tenant->business_name)) !!}
                    </h1>

                    <p class="text-muted-foreground-1 text-lg max-w-xl max-lg:mx-auto">
                        {{ Str::limit($customization->getHeroSubtitle() ?? '', 250) }}
                    </p>

                    <div class="flex flex-wrap gap-4 max-lg:justify-center">
                        @if($tenant->whatsapp)
                            <a href="https://wa.me/{{ $tenant->whatsapp }}" class="inline-flex items-center gap-2 py-3 px-6 rounded-lg font-medium bg-primary text-primary-foreground hover:bg-primary-hover transition-colors">
                                <span class="iconify tabler--brand-whatsapp size-5"></span>
                                Contáctanos
                            </a>
                        @endif
                        <a href="#products" class="inline-flex items-center gap-2 py-3 px-6 rounded-lg font-medium border border-border text-foreground hover:bg-surface transition-colors">
                            Ver Productos
                            <span class="iconify tabler--arrow-down size-5"></span>
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
