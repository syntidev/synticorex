{{-- Hero Split 50/50 — Plan 3 VISIÓN (Preline 4.1.2 + Tailwind v4) --}}
<section id="home" class="min-h-[90vh] grid lg:grid-cols-2">

    {{-- IZQUIERDA: Contenido sobre fondo blanco --}}
    <div class="bg-background flex items-center px-8 py-16 lg:px-16 lg:py-24">
        <div class="max-w-xl space-y-6">
            {{-- Badge ciudad --}}
            @if($tenant->city ?? $tenant->tagline)
            <div class="inline-flex items-center gap-2 border border-primary/20 bg-primary/5 rounded-full px-4 py-1.5 text-sm text-primary">
                <span class="size-2 rounded-full bg-primary"></span>
                {{ $tenant->city ?? $tenant->tagline }}
            </div>
            @endif

            {{-- Título --}}
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-foreground leading-tight">
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
                   class="inline-flex items-center gap-2 py-3 px-6 rounded-lg font-medium bg-primary text-primary-foreground hover:bg-primary-hover transition-colors text-base">
                    <span class="iconify tabler--brand-whatsapp size-5"></span>
                    Contactar por WhatsApp
                </a>
                @elseif($tenant->phone)
                <a href="tel:{{ $tenant->phone }}"
                   class="inline-flex items-center gap-2 py-3 px-6 rounded-lg font-medium bg-primary text-primary-foreground hover:bg-primary-hover transition-colors text-base">
                    <span class="iconify tabler--phone size-5"></span>
                    Llamar Ahora
                </a>
                @endif
                <a href="#services"
                   class="inline-flex items-center gap-2 py-3 px-6 rounded-lg font-medium border border-border text-foreground hover:bg-surface transition-colors text-base">
                    Ver servicios
                    <span class="iconify tabler--arrow-down size-5"></span>
                </a>
            </div>

            {{-- Bloque de confianza --}}
            <div class="flex items-center gap-3 pt-2">
                <div class="flex -space-x-2">
                    <div class="size-8 rounded-full bg-primary/20 border-2 border-background flex items-center justify-center text-xs text-primary font-bold">A</div>
                    <div class="size-8 rounded-full bg-primary/30 border-2 border-background flex items-center justify-center text-xs text-primary font-bold">B</div>
                    <div class="size-8 rounded-full bg-primary/40 border-2 border-background flex items-center justify-center text-xs text-primary font-bold">C</div>
                </div>
                <span class="text-sm text-muted-foreground-1">Clientes satisfechos en {{ $tenant->city ?? 'Venezuela' }}</span>
            </div>
        </div>
    </div>

    {{-- DERECHA: Imagen a pantalla completa --}}
    <div class="relative min-h-[50vh] lg:min-h-full">
        <img src="{{ $customization->hero_main_filename
            ? asset('storage/tenants/'.$tenant->id.'/'.$customization->hero_main_filename)
            : 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=1200&q=80' }}"
             alt="{{ $tenant->business_name }}"
             class="absolute inset-0 w-full h-full object-cover">
        {{-- Overlay sutil con acento primario --}}
        <div class="absolute inset-0 bg-primary/10 pointer-events-none"></div>
    </div>
</section>
