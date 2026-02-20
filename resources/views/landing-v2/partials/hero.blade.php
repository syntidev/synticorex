{{-- Hero Section Partial - FlyonUI --}}
<section id="home">
    <div class="gap-18 md:pt-45 lg:gap-35 lg:pt-47.5 flex h-full flex-col justify-between bg-cover bg-center bg-no-repeat py-8 pt-40 sm:py-16 md:gap-24 lg:py-24"
         @if($customization->hero_filename)
         style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('{{ asset('storage/tenants/' . $tenant->id . '/' . $customization->hero_filename) }}');"
         @else
         style="background-image: url('{{ asset('img/free-layer-blur.png') }}');"
         @endif
    >
        <div class="mx-auto flex max-w-[1280px] flex-col items-center gap-6 justify-self-center px-4 text-center sm:px-6 lg:px-8">
            {{-- Status Badge --}}
            <div class="bg-base-200 border-base-content/20 w-fit rounded-full border px-3 py-1">
                @if($tenant->is_open)
                    <span class="text-success">🟢 Estamos Abiertos</span>
                @else
                    <span class="text-error">🔴 Cerrado Temporalmente</span>
                @endif
                @if($tenant->tagline)
                    <span class="mx-2">•</span>
                    <span>{{ $tenant->tagline }}</span>
                @endif
            </div>
            
            {{-- Main Title --}}
            <h1 class="text-base-content z-1 relative text-5xl font-bold leading-[1.15] max-md:text-2xl md:max-w-3xl {{ $customization->hero_filename ? 'text-white' : '' }}">
                <span>{{ $tenant->business_name }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="348" height="10" viewBox="0 0 348 10" fill="none" class="-z-1 left-25 absolute -bottom-1.5 max-lg:left-4 max-md:hidden">
                    <path d="M1.85645 8.23715C62.4821 3.49284 119.04 1.88864 180.031 1.88864C225.103 1.88864 275.146 1.32978 319.673 4.85546C328.6 5.24983 336.734 6.33887 346.695 7.60269" stroke="url(#hero-underline)" stroke-width="2" stroke-linecap="round"/>
                    <defs>
                        <linearGradient id="hero-underline" x1="29.7873" y1="1.85626" x2="45.2975" y2="69.7387" gradientUnits="userSpaceOnUse">
                            <stop stop-color="var(--color-primary)"/>
                            <stop offset="1" stop-color="var(--color-primary-content)"/>
                        </linearGradient>
                    </defs>
                </svg>
            </h1>
            
            {{-- Tagline/Description --}}
            @if($tenant->slogan || $tenant->description)
            <p class="max-w-3xl {{ $customization->hero_filename ? 'text-white/90' : 'text-base-content/80' }}">
                {{ $tenant->slogan ?? Str::limit($tenant->description, 200) }}
            </p>
            @endif
            
            {{-- CTA Buttons --}}
            <div class="flex flex-wrap items-center justify-center gap-4">
                {{-- Primary CTA - Ver Catálogo --}}
                @if($products->count() > 0)
                <a href="#products" class="btn btn-primary btn-gradient btn-lg">
                    Ver Catálogo
                    <span class="icon-[tabler--arrow-down] size-5"></span>
                </a>
                @endif
                
                {{-- Secondary CTA - WhatsApp --}}
                @if($tenant->whatsapp_sales)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tenant->whatsapp_sales) }}?text={{ urlencode('Hola! Quiero información sobre sus productos') }}" 
                   target="_blank"
                   class="btn btn-success btn-lg">
                    <span class="icon-[tabler--brand-whatsapp] size-5"></span>
                    Contáctanos
                </a>
                @endif
            </div>
            
            {{-- Currency Toggle (small) --}}
            @if($products->count() > 0)
            <div class="mt-4">
                <button onclick="toggleCurrency()" class="btn btn-soft btn-sm">
                    <span class="icon-[tabler--currency-dollar] size-4"></span>
                    <span class="currency-toggle-text">Ver en Bs.</span>
                </button>
                <span class="text-xs {{ $customization->hero_filename ? 'text-white/60' : 'text-base-content/60' }} ml-2">
                    Tasa: Bs. {{ number_format($dollarRate ?? 36.50, 2, ',', '.') }}
                </span>
            </div>
            @endif
        </div>
        
        {{-- Hero Image Bottom (optional decorative) --}}
        @if(!$customization->hero_filename)
        <div class="min-h-20 w-full"></div>
        @endif
    </div>
</section>
