{{--
    Hero Gradient Animado — SYNTIweb
    ─────────────────────────────────────────────
    Sección  : hero
    Variante : gradient (Plan 3 VISIÓN)
    Variables: $tenant, $customization, $plan
--}}
@php
    $heroSlogan   = $tenant->slogan ?? $tenant->business_name ?? 'Tu negocio profesional';
    $sloganBefore = Str::contains($heroSlogan, ' ')
        ? Str::beforeLast($heroSlogan, ' ')
        : $heroSlogan;
    $sloganAccent = Str::contains($heroSlogan, ' ')
        ? Str::afterLast($heroSlogan, ' ')
        : '';

    $heroSubtitle = $customization->getHeroSubtitle();
    $heroFallback = 'Estamos aquí para hacer crecer tu negocio. Contáctanos hoy.';

    $heroImage = $customization->hero_main_filename
        ? asset('storage/tenants/' . $tenant->id . '/' . $customization->hero_main_filename)
        : null;

    $waRaw  = $tenant->whatsapp_sales ?? $tenant->whatsapp_support ?? null;
    $waLink = $waRaw ? 'https://wa.me/' . preg_replace('/\D/', '', $waRaw) : '#contacto';

    $badgeText = $tenant->city ?? 'Presencia profesional';
@endphp

{{-- Shimmer keyframe (se dispara una sola vez al cargar) --}}
<style>
@keyframes shimmer {
    0%   { background-position: -200% center; }
    100% { background-position:  200% center; }
}
</style>

<section id="home" class="relative min-h-[85vh] lg:min-h-screen overflow-hidden">

    {{-- Gradiente animado --}}
    <div class="absolute inset-0 bg-gradient-to-br from-primary via-secondary to-accent"
         style="background-size:400% 400%;animation:gradient-xy 15s ease infinite;"></div>

    {{-- Overlay sutil --}}
    <div class="absolute inset-0 bg-base-content/10 pointer-events-none"></div>

    <div class="relative container mx-auto px-6 py-20 lg:py-28 min-h-[85vh] lg:min-h-screen flex items-center">

        <div class="grid lg:grid-cols-2 gap-12 items-center w-full">

            {{-- ── COLUMNA IZQUIERDA: CONTENIDO ── --}}
            <div class="text-base-100 space-y-6">

                {{-- SECCIÓN 1: Badge --}}
                <div class="inline-flex items-center gap-2 bg-base-100/15 backdrop-blur-sm
                            border border-base-100/25 rounded-full px-4 py-1.5 text-sm text-base-100
                            overflow-hidden relative"
                     style="animation:shimmer 2s ease 1s 1;">
                    <span class="size-2 rounded-full bg-accent animate-pulse shrink-0"></span>
                    {{ $badgeText }}
                </div>

                {{-- SECCIÓN 2: Título --}}
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight">
                    {{ $sloganBefore }}@if($sloganAccent)<span class="text-accent"> {{ $sloganAccent }}</span>@endif
                </h1>

                {{-- SECCIÓN 3: Descripción --}}
                @if($heroSubtitle)
                <p class="text-base text-base-100/80 max-w-lg">
                    {{ Str::limit($heroSubtitle, 150) }}
                </p>
                @else
                <p class="text-base text-base-100/60 max-w-lg italic">
                    {{ $heroFallback }}
                </p>
                @endif

                {{-- SECCIÓN 4: CTAs --}}
                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                    @if($waRaw)
                    <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center py-3 px-6 rounded-lg font-medium transition-colors text-lg bg-base-100 text-primary hover:bg-base-100/90 w-full sm:w-auto">
                        <iconify-icon icon="tabler:brand-whatsapp" width="20" height="20"></iconify-icon>
                        Contactar por WhatsApp
                    </a>
                    @endif
                    <a href="#servicios"
                       class="inline-flex items-center py-3 px-6 rounded-lg font-medium transition-colors text-lg border border-base-100/40 text-base-100
                              hover:bg-base-100/10 w-full sm:w-auto">
                        Ver servicios
                        <iconify-icon icon="tabler:arrow-down" width="20" height="20"></iconify-icon>
                    </a>
                </div>

                {{-- SECCIÓN 6: Stats (solo Plan 3 VISIÓN) --}}
                @if($tenant->plan_id >= 3)
                <div class="flex gap-6 pt-2 border-t border-base-100/20">
                    <div>
                        <div class="text-xl font-bold text-base-100">17+</div>
                        <div class="text-xs text-base-100/60">Temas</div>
                    </div>
                    <div>
                        <div class="text-xl font-bold text-base-100">SEO</div>
                        <div class="text-xs text-base-100/60">Incluido</div>
                    </div>
                    <div>
                        <div class="text-xl font-bold text-base-100">24/7</div>
                        <div class="text-xs text-base-100/60">En línea</div>
                    </div>
                </div>
                @endif

            </div>

            {{-- ── COLUMNA DERECHA: IMAGEN ── --}}
            <div class="relative">

                {{-- Glow decorativo --}}
                <div class="absolute -inset-4 bg-accent/20 rounded-full blur-3xl pointer-events-none"></div>

                @if($heroImage)
                    <img src="{{ $heroImage }}"
                         alt="{{ $tenant->business_name }}"
                         class="relative w-full max-h-48 lg:h-80 object-cover
                                rounded-2xl lg:rounded-3xl
                                ring-4 ring-base-100/20 shadow-2xl
                                [animation:pulse_4s_ease-in-out_infinite]">
                @else
                    <div class="relative w-full min-h-48 lg:h-80 bg-base-100/10
                                rounded-2xl lg:rounded-3xl
                                ring-4 ring-base-100/20 shadow-2xl
                                flex items-center justify-center
                                [animation:pulse_4s_ease-in-out_infinite]">
                        <iconify-icon icon="tabler:building-store" width="80" height="80"
                                      class="text-base-100/30"></iconify-icon>
                    </div>
                @endif

            </div>

        </div>
    </div>
</section>
