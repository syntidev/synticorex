{{-- About Section — Plan 2+ --}}
@php
    $description = $tenant->description ?? null;
    $slogan      = $tenant->slogan ?? null;

    // Imagen de la sección: hero > logo > placeholder
    $businessImage = null;
    if (!empty($customization->hero_filename)) {
        $businessImage = asset('storage/tenants/' . $tenant->id . '/' . $customization->hero_filename);
    }

    $logoImage = null;
    if (!empty($customization->logo_filename)) {
        $logoImage = asset('storage/tenants/' . $tenant->id . '/' . $customization->logo_filename);
    }
@endphp

<section id="about" class="py-8 sm:py-16 lg:py-24 bg-base-200/50">
    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-10 lg:gap-16 items-center">

            {{-- Col 8/12 — Descripción principal --}}
            <div class="flex-1 lg:basis-2/3">
                <span class="text-primary text-xs font-black uppercase tracking-[0.2em] mb-3 block">
                    Acerca de nosotros
                </span>
                <h2 class="text-base-content text-2xl font-semibold md:text-3xl lg:text-4xl mb-4">
                    {{ $tenant->business_name }}
                </h2>
                @if($slogan)
                    <p class="text-base-content/50 text-sm italic mb-5">"{{ $slogan }}"</p>
                @endif
                @if($description)
                    <p class="text-base-content/75 leading-relaxed text-base">
                        {{ $description }}
                    </p>
                @else
                    <p class="text-base-content/40 text-sm italic">
                        Agrega una descripción de tu empresa en el dashboard → Información.
                    </p>
                @endif

                @if($tenant->city)
                    <div class="mt-6 inline-flex items-center gap-2 bg-primary/10 text-primary text-xs font-bold px-3 py-1.5 rounded-full">
                        <span class="w-1.5 h-1.5 bg-primary rounded-full animate-pulse"></span>
                        {{ $tenant->city }}
                    </div>
                @endif
            </div>

            {{-- Col 4/12 — Imagen del negocio (hero o logo) --}}
            <div class="lg:basis-1/3 flex items-center justify-center w-full">
                @if($businessImage)
                    {{-- Hero image disponible: mostrar como foto del negocio --}}
                    <div class="relative w-full max-w-xs">
                        <div class="absolute -top-3 -right-3 w-24 h-24 bg-primary/10 rounded-full blur-2xl pointer-events-none"></div>
                        <div class="absolute -bottom-3 -left-3 w-20 h-20 bg-primary/5 rounded-full blur-xl pointer-events-none"></div>
                        <div class="relative rounded-3xl overflow-hidden shadow-lg border border-base-content/10 aspect-square">
                            <img src="{{ $businessImage }}"
                                 alt="{{ $tenant->business_name }}"
                                 class="w-full h-full object-cover">
                        </div>
                    </div>
                @elseif($logoImage)
                    {{-- Solo logo disponible: centrado en panel neutro --}}
                    <div class="relative w-full max-w-xs">
                        <div class="absolute -top-3 -right-3 w-24 h-24 bg-primary/10 rounded-full blur-2xl pointer-events-none"></div>
                        <div class="relative bg-base-100 border border-base-content/10 rounded-3xl p-10 shadow-sm flex items-center justify-center aspect-square">
                            <img src="{{ $logoImage }}"
                                 alt="{{ $tenant->business_name }}"
                                 class="max-w-[70%] max-h-[70%] object-contain">
                        </div>
                    </div>
                @else
                    {{-- Sin imagen: placeholder profesional --}}
                    <div class="relative w-full max-w-xs">
                        <div class="relative bg-base-100 border border-base-content/10 rounded-3xl p-10 shadow-sm flex flex-col items-center justify-center aspect-square gap-4">
                            <svg class="w-16 h-16 text-primary/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <p class="text-base-content/30 text-xs text-center">
                                {{ $tenant->business_name }}
                            </p>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</section>
