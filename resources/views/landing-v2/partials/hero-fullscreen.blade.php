{{-- Hero Section - FlyonUI Landing V2 (Estructura Original) --}}
<section id="home">
    <div class="gap-18 md:pt-45 lg:gap-35 lg:pt-47.5 flex h-full flex-col justify-between bg-cover bg-center bg-no-repeat py-8 pt-40 sm:py-16 md:gap-24 lg:py-24"
         @if($customization->hero_filename)
         style="background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('{{ asset('storage/tenants/'.$tenant->id.'/'.$customization->hero_filename) }}');"
         @else
         style="background-image: linear-gradient(rgba(0, 0, 0, 0.35), rgba(0, 0, 0, 0.35)), url('https://images.unsplash.com/photo-1552664730-d307ca884978?w=1920&q=80');"
         @endif
    >
        <div class="mx-auto flex max-w-7xl flex-col items-center gap-6 justify-self-center px-4 text-center sm:px-6 lg:px-8">
            {{-- Badge Superior --}}
            @if($tenant->created_at)
                <div class="bg-base-200 border-base-content/20 w-fit rounded-full border px-3 py-1">
                    <span>🎉 {{ $tenant->tagline ?? 'Sirviendo con calidad desde ' . $tenant->created_at->format('Y') }}</span>
                </div>
            @endif

            {{-- Título Principal con SVG Underline --}}
            <h1 class="text-base-content z-1 relative text-5xl font-bold leading-[1.15] max-md:text-2xl md:max-w-3xl">
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
            <p class="text-base-content/80 max-w-3xl">
                @if($customization->about_text)
                    {{ Str::limit($customization->about_text, 200) }}
                @else
                    Bienvenido a una experiencia donde la calidad, frescura y hospitalidad se unen. Ya sea tu primera visita o la número cien, cada momento está diseñado para impresionarte.
                @endif
            </p>

            {{-- Botón CTA Principal --}}
            @if($tenant->whatsapp)
                <a href="https://wa.me/{{ $tenant->whatsapp }}?text={{ urlencode('Hola ' . $tenant->business_name . ', me gustaría obtener más información') }}" 
                   target="_blank"
                   class="btn btn-primary btn-gradient btn-lg">
                    Contactar por WhatsApp
                    <span class="iconify tabler--arrow-right size-5 rtl:rotate-180"></span>
                </a>
            @elseif($tenant->phone)
                <a href="tel:{{ $tenant->phone }}" 
                   class="btn btn-primary btn-gradient btn-lg">
                    Llamar Ahora
                    <span class="iconify tabler--arrow-right size-5 rtl:rotate-180"></span>
                </a>
            @else
                <a href="#contact-us" 
                   class="btn btn-primary btn-gradient btn-lg">
                    Conocer Más
                    <span class="iconify tabler--arrow-right size-5 rtl:rotate-180"></span>
                </a>
            @endif
        </div>
    </div>
</section>
