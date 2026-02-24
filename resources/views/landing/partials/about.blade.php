{{--
    About Section Partial — SYNTIweb
    ─────────────────────────────────
    Plan requerido : 2 (CRECIMIENTO) o 3 (VISIÓN)
    Integración    : Incluido dinámicamente desde base.blade.php vía section_order
    Variables      : $tenant->settings['business_info']['about']['title']
                     $tenant->settings['business_info']['about']['content']
    Fallbacks      : "Acerca de Nosotros" / texto genérico
    Adapta          : FlyonUI v2 — respeta data-theme del HTML padre
    Parámetros     : $sConfig (array de configuración de sección, puede ser vacío)
--}}
@php
    $aboutTitle   = data_get($tenant->settings, 'business_info.about.title',
                        'Acerca de Nosotros');
    $aboutContent = data_get($tenant->settings, 'business_info.about.content',
                        'Somos un negocio comprometido con la excelencia y el servicio a nuestros clientes.');
    // Truncar a 500 caracteres por seguridad
    $aboutContent = mb_substr(strip_tags($aboutContent), 0, 500);
@endphp

<section id="about" class="relative py-24 bg-base-100 overflow-hidden">

    {{-- Decoración: glow tenue en esquina superior derecha --}}
    <div class="absolute -top-20 -right-20 w-80 h-80 bg-primary/5 blur-[100px] rounded-full pointer-events-none" aria-hidden="true"></div>
    {{-- Decoración: línea sutil en esquina inferior izquierda --}}
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-secondary/5 blur-[80px] rounded-full pointer-events-none" aria-hidden="true"></div>

    <div class="container mx-auto max-w-5xl px-6 relative z-10">

        {{-- Layout: columna en móvil · dos columnas en md+ --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 md:gap-16 items-center">

            {{-- ─── Columna izquierda: encabezado ─────────────────── --}}
            <div class="flex flex-col gap-6">

                {{-- Etiqueta de sección --}}
                <span class="inline-flex items-center gap-2 self-start text-xs font-bold uppercase tracking-widest text-primary/80 bg-primary/10 px-4 py-1.5 rounded-full">
                    <iconify-icon icon="tabler:building-store" width="14" height="14"></iconify-icon>
                    {{ $tenant->business_name }}
                </span>

                {{-- Título --}}
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-black tracking-tight text-base-content leading-tight">
                    {{ $aboutTitle }}
                </h2>

                {{-- Divisor cromático --}}
                <div class="flex items-center gap-3">
                    <div class="w-12 h-1 bg-primary rounded-full"></div>
                    <div class="w-4 h-1 bg-primary/40 rounded-full"></div>
                    <div class="w-2 h-1 bg-primary/20 rounded-full"></div>
                </div>

                {{-- Cita visual (decorativa, solo en desktop) --}}
                <p class="hidden md:block text-5xl text-primary/10 font-black leading-none select-none" aria-hidden="true">
                    "
                </p>

            </div>

            {{-- ─── Columna derecha: contenido ────────────────────── --}}
            <div class="flex flex-col justify-center">

                {{-- Tarjeta de contenido con borde sutil --}}
                <div class="relative bg-base-200/60 border border-base-content/10 rounded-[2rem] p-8 md:p-10 shadow-sm">

                    {{-- Acento de esquina --}}
                    <div class="absolute top-0 left-0 w-12 h-12 border-t-2 border-l-2 border-primary/30 rounded-tl-[2rem]" aria-hidden="true"></div>
                    <div class="absolute bottom-0 right-0 w-12 h-12 border-b-2 border-r-2 border-primary/30 rounded-br-[2rem]" aria-hidden="true"></div>

                    <p class="text-base-content/75 text-base md:text-lg leading-relaxed">
                        {{ $aboutContent }}
                    </p>

                </div>

            </div>

        </div>
    </div>
</section>
