{{-- Hero Split 50/50 — Plan 3 VISIÓN (Preline 4.1.2 + Tailwind v4) --}}
<section id="home" class="min-h-[90vh] grid lg:grid-cols-2">

    {{-- IZQUIERDA: Contenido sobre fondo blanco --}}
    <div class="bg-background flex items-center px-8 py-16 lg:px-16 lg:py-24">
        <div class="max-w-xl space-y-6">
			
			{{-- Patrón decorativo SVG de fondo --}}
			<div class="pointer-events-none absolute inset-0 flex items-end justify-start opacity-80 aria-hidden="true">
			<img src="{{ asset('admin_assets/hyperdrive.svg') }}" 
             class="w-full object-cover object-bottom" 
             alt="">
			</div>

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
                @if($tenant->getActiveWhatsapp())
                <a href="https://wa.me/{{ preg_replace('/\D/', '', $tenant->getActiveWhatsapp()) }}?text={{ urlencode('Hola ' . $tenant->business_name . ', me gustaría obtener más información') }}"
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
        {{-- overlay eliminado --}}
    </div>
</section>
