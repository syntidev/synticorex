{{-- Hero Split 50/50 — Plan 2 CRECIMIENTO (Preline 4.1.2 + Tailwind v4) --}}
<section id="home" class="min-h-[90vh] grid lg:grid-cols-2">
    {{-- IZQUIERDA: Contenido sobre fondo blanco --}}
    <div class="relative bg-background flex items-center px-8 py-16 lg:px-16 lg:py-24">
        {{-- Fondo decorativo --}}
        <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
            <div class="absolute -top-32 -right-32 size-[500px] rounded-full opacity-[0.05] blur-3xl"
                 style="background:var(--color-primary)"></div>
            <div class="absolute inset-0 opacity-[0.015]"
                 style="background-image:url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22><filter id=%22n%22><feTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 numOctaves=%223%22 stitchTiles=%22stitch%22/></filter><rect width=%22200%22 height=%22200%22 filter=%22url(%23n)%22 opacity=%221%22/></svg>');background-size:200px"></div>
        </div>
        <div class="max-w-xl space-y-6">
            {{-- Badge ciudad --}}
            @if($tenant->city ?? $tenant->tagline)
            <div class="inline-flex items-center gap-2 border border-primary/20 bg-primary/5 backdrop-blur-sm rounded-full px-4 py-1.5 text-sm text-primary">
                <span class="size-2 rounded-full bg-primary"></span>
                {{ $tenant->city ?? $tenant->tagline }}
            </div>
            @endif

            {{-- Título --}}
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-foreground leading-tight"
                style="text-shadow: 0 4px 24px color-mix(in oklch, var(--color-foreground) 15%, transparent), 0 1px 4px color-mix(in oklch, var(--color-foreground) 8%, transparent);">
                {!! nl2br(e($customization->getHeroTitle() ?? $tenant->business_name)) !!}
            </h1>

            {{-- Descripción --}}
            <p class="text-lg text-muted-foreground-1 max-w-lg">
                {{ Str::limit($customization->getHeroSubtitle() ?? 'Bienvenido a una experiencia donde la calidad y el servicio se unen.', 200) }}
            </p>

            {{-- CTAs --}}
            <div class="flex flex-wrap gap-3 pt-2">
                @if($tenant->whatsapp)
                <a href="https://wa.me/{{ preg_replace('/\D/', '', $tenant->whatsapp) }}?text={{ urlencode('Hola ' . $tenant->business_name . ', me gustaría obtener más información') }}"
                   target="_blank"
                   class="inline-flex items-center gap-2 py-3 px-6 rounded-lg font-medium bg-primary text-primary-foreground hover:bg-primary-hover shadow-[0_4px_14px_0_color-mix(in_oklch,var(--color-primary)_40%,transparent)] hover:shadow-[0_6px_20px_0_color-mix(in_oklch,var(--color-primary)_50%,transparent)] hover:-translate-y-1 transition-all duration-200 text-base">
                    <span class="iconify tabler--brand-whatsapp size-5"></span>
                    Contactar por WhatsApp
                </a>
                @elseif($tenant->phone)
                <a href="tel:{{ $tenant->phone }}"
                   class="inline-flex items-center gap-2 py-3 px-6 rounded-lg font-medium bg-primary text-primary-foreground hover:bg-primary-hover shadow-[0_4px_14px_0_color-mix(in_oklch,var(--color-primary)_40%,transparent)] hover:shadow-[0_6px_20px_0_color-mix(in_oklch,var(--color-primary)_50%,transparent)] hover:-translate-y-1 transition-all duration-200 text-base">
                    <span class="iconify tabler--phone size-5"></span>
                    Llamar Ahora
                </a>
                @endif
                <a href="#products"
                   class="inline-flex items-center gap-2 py-3 px-6 rounded-lg font-medium border border-border text-foreground hover:bg-surface transition-colors text-base">
                    Ver servicios
                    <span class="iconify tabler--arrow-down size-5"></span>
                </a>
            </div>

            {{-- Línea divisora + stats --}}
            <div class="flex gap-8 pt-4 border-t border-border">
                <div>
                    <div class="text-2xl font-bold text-primary" style="text-shadow: 0 2px 8px color-mix(in oklch, var(--color-primary) 30%, transparent)">{{ $products->count() ?? '0' }}+</div>
                    <div class="text-xs text-muted-foreground-1">Productos</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-primary" style="text-shadow: 0 2px 8px color-mix(in oklch, var(--color-primary) 30%, transparent)">SEO</div>
                    <div class="text-xs text-muted-foreground-1">Incluido</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-primary" style="text-shadow: 0 2px 8px color-mix(in oklch, var(--color-primary) 30%, transparent)">24/7</div>
                    <div class="text-xs text-muted-foreground-1">En línea</div>
                </div>
            </div>
        </div>
    </div>

    {{-- DERECHA: Imagen a pantalla completa --}}
    <div class="relative min-h-[50vh] lg:min-h-full">
        <img src="{{ $customization->hero_main_filename
            ? asset('storage/tenants/'.$tenant->id.'/'.$customization->hero_main_filename)
            : 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=1200&q=80' }}"
             alt="{{ $tenant->business_name }}"
             class="absolute inset-0 w-full h-full object-cover"
             loading="lazy">
        {{-- Overlay sutil con acento primario --}}
        <div class="absolute inset-0 bg-primary/10 pointer-events-none"></div>
        {{-- Gradiente transición izquierda --}}
        <div class="absolute inset-y-0 left-0 w-24 pointer-events-none"
             style="background:linear-gradient(to right, var(--color-background, #fff) 0%, transparent 100%)"></div>
    </div>
</section>
