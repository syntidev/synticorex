{{-- About Section — Plan 2+ --}}
@php
    $description = $customization->getAboutText() ?? null;
    $slogan      = $tenant->slogan ?? null;

    $businessImage = null;
    if (!empty($customization->about_image_filename)) {
        $businessImage = asset('storage/tenants/' . $tenant->id . '/' . $customization->about_image_filename);
    } elseif (!empty($customization->hero_main_filename)) {
        $businessImage = asset('storage/tenants/' . $tenant->id . '/' . $customization->hero_main_filename);
    }

    $logoImage = !empty($customization->logo_filename)
        ? asset('storage/tenants/' . $tenant->id . '/' . $customization->logo_filename)
        : null;
@endphp

<section id="about" class="relative py-10 sm:py-16 lg:py-24 bg-background overflow-hidden">

    {{-- ░░ ADORNOS GEOMÉTRICOS ░░ --}}
    {{-- Círculo grande top-right --}}
    <div class="pointer-events-none absolute -top-24 -right-24 w-96 h-96 rounded-full"
         style="background: radial-gradient(circle, var(--color-primary, #4A80E4) 0%, transparent 70%); opacity: 0.07;"></div>

    {{-- Círculo pequeño bottom-left --}}
    <div class="pointer-events-none absolute -bottom-16 -left-16 w-64 h-64 rounded-full"
         style="background: radial-gradient(circle, var(--color-primary, #4A80E4) 0%, transparent 70%); opacity: 0.05;"></div>

    {{-- Dot grid top — lado derecho (columna texto) --}}

    {{-- Dot grid bottom — lado derecho (columna texto) --}}


    <div class="relative mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">

            {{-- Separador vertical decorativo (desktop) --}}
            <div class="pointer-events-none absolute left-1/2 top-[15%] bottom-[15%] w-px hidden lg:block"
                 style="background:linear-gradient(to bottom, transparent, color-mix(in oklch, var(--color-primary) 20%, transparent) 30%, color-mix(in oklch, var(--color-primary) 20%, transparent) 70%, transparent)"></div>

            {{-- ░░ COLUMNA IZQUIERDA: imagen ░░ --}}
            <div class="relative about-col-img">

                @if($businessImage)
                    {{-- Sombra offset detrás --}}
                    <div class="absolute inset-0 translate-x-3 translate-y-3 bg-primary/10 rounded-2xl -z-10"></div>
                    {{-- Marcos de esquina decorativos --}}
                    <div class="absolute -top-3 -left-3 w-10 h-10 border-t-2 border-l-2 border-primary/50 rounded-tl-xl pointer-events-none"></div>
                    <div class="absolute -bottom-3 -right-3 w-10 h-10 border-b-2 border-r-2 border-primary/50 rounded-br-xl pointer-events-none"></div>

                    <img src="{{ $businessImage }}"
                         alt="{{ $tenant->business_name }}"
                         class="w-full h-auto rounded-2xl shadow-[0_20px_60px_-10px_rgba(0,0,0,0.15)] ring-1 ring-primary/10 relative z-10"
                         loading="lazy">

                @elseif($logoImage)
                    <div class="absolute inset-0 translate-x-3 translate-y-3 bg-primary/10 rounded-2xl -z-10"></div>
                    <div class="absolute -top-3 -left-3 w-10 h-10 border-t-2 border-l-2 border-primary/50 rounded-tl-xl pointer-events-none"></div>
                    <div class="absolute -bottom-3 -right-3 w-10 h-10 border-b-2 border-r-2 border-primary/50 rounded-br-xl pointer-events-none"></div>
                    <div class="relative z-10 bg-muted border border-border rounded-2xl flex items-center justify-center py-20 shadow-sm">
                        <img src="{{ $logoImage }}" alt="{{ $tenant->business_name }}"
                             class="max-h-40 w-auto object-contain opacity-90">
                    </div>

                @else
                    <div class="bg-muted border border-border rounded-2xl flex items-center justify-center py-24 shadow-sm">
                        <span class="iconify tabler--building size-16 text-primary/20"></span>
                    </div>
                @endif

            </div>

            {{-- ░░ COLUMNA DERECHA: contenido ░░ --}}
            <div class="relative flex flex-col justify-center about-col-text" style="isolation: isolate;">

                {{-- Dot grid top-right dentro de la columna de texto --}}
                <div class="pointer-events-none absolute -top-2 -right-2 -z-10 opacity-[0.20]"
                     style="width:100px; height:100px;
                            background-image: radial-gradient(circle, var(--color-primary, #4A80E4) 1.5px, transparent 1.5px);
                            background-size: 14px 14px;">
                </div>
                {{-- Dot grid bottom-right --}}
                <div class="pointer-events-none absolute -bottom-2 -right-2 -z-10 opacity-[0.12]"
                     style="width:70px; height:70px;
                            background-image: radial-gradient(circle, var(--color-primary, #4A80E4) 1.5px, transparent 1.5px);
                            background-size: 14px 14px;">
                </div>

                @if($tenant->city)
                    <p class="inline-flex items-center gap-1.5 text-primary text-xs font-semibold uppercase tracking-widest mb-4">
                        <span class="iconify tabler--map-pin size-4"></span>
                        {{ $tenant->city }}
                    </p>
                @endif

                <h2 class="text-foreground text-3xl lg:text-4xl font-bold leading-tight mb-2"
                    style="text-shadow: 0 4px 24px color-mix(in oklch, var(--color-foreground) 15%, transparent), 0 1px 4px color-mix(in oklch, var(--color-foreground) 8%, transparent);">
                    {!! $customization->getSectionTitle('about', 'Acerca de <span class="text-primary italic">nosotros</span>') !!}
                </h2>
				<div class="w-16 h-0.5 mt-4 mb-5 rounded-full"
                 style="background:var(--color-primary);box-shadow:0 0 12px 2px color-mix(in oklch,var(--color-primary) 60%,transparent)"></div>

                <p class="text-foreground/60 text-base font-medium mb-5">{{ $tenant->business_name }}</p>

                @if($slogan)
                    <p class="border-l-2 border-primary/40 pl-4 text-foreground/60 text-sm italic mb-6">
                        "{{ $slogan }}"
                    </p>
                @endif

                @if($description)
                    <p class="text-muted-foreground-1 text-base leading-relaxed mb-6">
                        {{ $description }}
                    </p>
                @endif

                {{-- Separador decorativo --}}
                <div class="flex items-center gap-3 mt-2">
                    <div class="h-px flex-1 bg-border"></div>
                    <span class="iconify tabler--sparkles size-4 text-primary/40"></span>
                    <div class="h-px flex-1 bg-border"></div>
                </div>

            </div>

        </div>
    </div>

</section>

@once
<style>
.about-col-img {
    animation: aboutFadeUp 0.55s ease both;
}
.about-col-text {
    animation: aboutFadeUp 0.55s 0.15s ease both;
    opacity: 0;
    animation-fill-mode: forwards;
}
@keyframes aboutFadeUp {
    from { opacity: 0; transform: translateY(22px); }
    to   { opacity: 1; transform: translateY(0); }
}
</style>
@endonce