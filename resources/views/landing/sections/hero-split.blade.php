{{-- Hero Split Layout � Preline 4.1.2 + Tailwind v4 --}}
<section id="home">
    <div class="relative bg-background overflow-hidden py-8 sm:py-16 lg:py-24">

        {{-- Fondo decorativo --}}
        <div class="pointer-events-none absolute inset-0" aria-hidden="true">
            <div class="absolute -bottom-20 -left-20 size-96 rounded-full bg-primary opacity-[0.06] blur-2xl"></div>
        </div>

        <div class="relative mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-2 lg:gap-24 items-center">

                {{-- Contenido --}}
                <div class="space-y-6 max-lg:text-center">

                    {{-- Badge ciudad � SOLO si plan 2/3 y ciudad configurada --}}
                    @if($tenant->city && $tenant->isAtLeastCrecimiento())
                        <div class="inline-flex items-center gap-2 bg-primary/8 border border-primary/20 w-fit rounded-full px-4 py-1.5 max-lg:mx-auto">
                            <span class="size-2 rounded-full bg-primary animate-pulse"></span>
                            <span class="text-primary text-sm font-medium">{{ $tenant->city }}</span>
                        </div>
                    @endif

                    {{-- T�tulo con sombra de texto sutil --}}
                    <h1 class="text-foreground text-4xl font-bold leading-[1.1] md:text-5xl lg:text-6xl tracking-tight"
                        style="text-shadow: 0 4px 24px color-mix(in oklch, var(--color-foreground) 15%, transparent), 0 1px 4px color-mix(in oklch, var(--color-foreground) 8%, transparent);">
                        {!! nl2br(e($customization->getHeroTitle() ?? $tenant->business_name)) !!}
                    </h1>

                    <p class="text-muted-foreground-1 text-lg max-w-xl max-lg:mx-auto leading-relaxed">
                        {{ Str::limit($customization->getHeroSubtitle() ?? '', 250) }}
                    </p>

                    {{-- CTAs --}}
                    @php
                        $heroWa = $tenant->getActiveWhatsapp()
                            ? preg_replace('/\D/', '', $tenant->getActiveWhatsapp()) : null;
                        $heroWaMsg = 'Hola, vi tu p�gina y me gustar�a obtener m�s informaci�n.';
                    @endphp
                    <div class="flex flex-wrap gap-4 max-lg:justify-center">
                        @if($heroWa)
                            <a href="https://wa.me/{{ $heroWa }}?text={{ urlencode($heroWaMsg) }}"
                               target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-2 py-3 px-6 rounded-xl font-semibold shadow-[0_4px_14px_0_color-mix(in_oklch,var(--color-primary)_40%,transparent)] bg-primary text-primary-foreground hover:bg-primary-hover hover:shadow-[0_6px_20px_0_color-mix(in_oklch,var(--color-primary)_50%,transparent)] hover:-translate-y-1 transition-all duration-200">
                                <span class="iconify tabler--brand-whatsapp size-5"></span>
                                Llamar Ahora
                            </a>
                        @endif
                        <a href="#services"
                           class="inline-flex items-center gap-2 py-3 px-6 rounded-xl font-semibold border border-border text-foreground hover:bg-muted hover:border-primary/30 hover:-translate-y-0.5 transition-all duration-200">
                            Ver servicios
                            <span class="iconify tabler--chevron-down size-5"></span>
                        </a>
                    </div>

                    
                </div>

                {{-- Imagen --}}
                <div class="relative max-lg:order-first overflow-hidden">
                    {{-- Sombra decorativa detr�s --}}
                    {{-- sombra decorativa eliminada --}}
                    @php
                        $heroSrc = !empty($customization->hero_main_filename)
                            ? (str_starts_with($customization->hero_main_filename, 'http') ? $customization->hero_main_filename : asset('storage/tenants/'.$tenant->id.'/'.$customization->hero_main_filename))
                            : 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=1200';
                    @endphp
                    <img src="{{ $heroSrc }}"
                         alt="{{ $tenant->business_name }}"
                         class="relative w-full h-[400px] lg:h-[500px] object-cover rounded-3xl ring-1 ring-white/10"
                         loading="lazy">
                </div>

            </div>
        </div>
    </div>
</section>