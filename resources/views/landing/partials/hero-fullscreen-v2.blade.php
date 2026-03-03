{{-- Hero Section - FlyonUI Landing V2 (Plan 2+ / fullscreen con imagen) --}}
<section id="home">
    <div class="relative gap-18 md:pt-45 lg:gap-35 lg:pt-47.5 flex h-full flex-col justify-between bg-cover bg-center bg-no-repeat py-8 pt-40 sm:py-16 md:gap-24 lg:py-24"
         @if($customization->hero_main_filename)
         style="background-image: url('{{ asset('storage/tenants/'.$tenant->id.'/'.$customization->hero_main_filename) }}');"
         @else
         style="background-image: url('https://images.unsplash.com/photo-1552664730-d307ca884978?w=1920&q=80');"
         @endif
    >
        {{-- Overlay de legibilidad --}}
        <div class="absolute inset-0 bg-gradient-to-b from-black/30 to-black/65 pointer-events-none"></div>

        <div class="relative z-10 mx-auto flex max-w-7xl flex-col items-center gap-6 justify-self-center px-4 text-center sm:px-6 lg:px-8">
            {{-- Badge Superior --}}
            @if($tenant->created_at)
                <div class="bg-white/10 backdrop-blur-sm border-white/20 w-fit rounded-full border px-3 py-1 text-white">
                    <span>🎉 {{ $tenant->tagline ?? 'Sirviendo con calidad desde ' . $tenant->created_at->format('Y') }}</span>
                </div>
            @endif

            {{-- Título Principal con SVG Underline --}}
            <h1 class="text-white z-1 relative text-5xl font-bold leading-[1.15] max-md:text-2xl md:max-w-3xl">
                <span>
                    @if($tenant->slogan)
                        {!! nl2br(e($tenant->slogan)) !!}
                    @else
                        Bienvenido a
                        <br />
                        {{ $tenant->business_name }}
                    @endif
                </span>
                {{-- SVG Decorativo (Underline) --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="348" height="10" viewBox="0 0 348 10" fill="none" class="-z-1 left-25 absolute -bottom-1.5 max-lg:left-4 max-md:hidden">
                    <path d="M1.85645 8.23715C62.4821 3.49284 119.04 1.88864 180.031 1.88864C225.103 1.88864 275.146 1.32978 319.673 4.85546C328.6 5.24983 336.734 6.33887 346.695 7.60269" stroke="url(#paint0_linear_17052_181397)" stroke-width="2" stroke-linecap="round" />
                    <defs>
                        <linearGradient id="paint0_linear_17052_181397" x1="29.7873" y1="1.85626" x2="45.2975" y2="69.7387" gradientUnits="userSpaceOnUse">
                            <stop stop-color="var(--color-primary)" />
                            <stop offset="1" stop-color="var(--color-primary-content)" />
                        </linearGradient>
                    </defs>
                </svg>
            </h1>

            {{-- Descripción --}}
            <p class="text-white/80 max-w-3xl">
                @if($customization->about_text ?? $tenant->description)
                    {{ Str::limit($customization->about_text ?? $tenant->description, 200) }}
                @else
                    Bienvenido a una experiencia donde la calidad, frescura y hospitalidad se unen. Ya sea tu primera visita o la número cien, cada momento está diseñado para impresionarte.
                @endif
            </p>

            {{-- CTAs --}}
            <div class="flex flex-wrap gap-4 justify-center">
                {{-- CTA principal: WhatsApp → btn-primary --}}
                @if($tenant->whatsapp)
                    <a href="https://wa.me/{{ $tenant->whatsapp }}?text={{ urlencode('Hola ' . $tenant->business_name . ', me gustaría obtener más información') }}"
                       target="_blank"
                       class="inline-flex items-center py-3 px-6 rounded-lg font-medium transition-colors text-lg bg-blue-600 text-white hover:bg-blue-700">
                        <span class="iconify tabler--brand-whatsapp size-5"></span>
                        Contactar por WhatsApp
                    </a>
                @elseif($tenant->phone)
                    <a href="tel:{{ $tenant->phone }}"
                       class="inline-flex items-center py-3 px-6 rounded-lg font-medium transition-colors text-lg bg-blue-600 text-white hover:bg-blue-700">
                        <span class="iconify tabler--phone size-5"></span>
                        Llamar Ahora
                    </a>
                @else
                    <a href="#contact-us"
                       class="inline-flex items-center py-3 px-6 rounded-lg font-medium transition-colors text-lg bg-blue-600 text-white hover:bg-blue-700">
                        Conocer Más
                        <span class="iconify tabler--arrow-right size-5 rtl:rotate-180"></span>
                    </a>
                @endif

                {{-- CTA secundario: borde blanco sobre imagen oscura --}}
                <a href="#productos" class="inline-flex items-center py-3 px-6 rounded-lg font-medium transition-colors text-lg border border-white text-white hover:bg-white/10">
                    Ver Catálogo
                    <span class="iconify tabler--arrow-down size-5"></span>
                </a>
            </div>
        </div>
    </div>
</section>
