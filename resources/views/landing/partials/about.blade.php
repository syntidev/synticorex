{{-- About Section — Plan 2+ — slim CTA style --}}
@php
    $description = $tenant->description ?? null;
    $slogan      = $tenant->slogan ?? null;
@endphp

<section id="about" class="py-16 bg-base-200/50">
    <div class="container mx-auto px-6 max-w-6xl">
        <div class="flex flex-col lg:flex-row gap-10 lg:gap-16 items-center">

            {{-- Col 8/12 — Descripción --}}
            <div class="flex-1 lg:basis-2/3">
                <span class="text-primary text-xs font-black uppercase tracking-[0.2em] mb-3 block">
                    Acerca de nosotros
                </span>
                <h2 class="text-3xl md:text-4xl font-black tracking-tight text-base-content mb-4 leading-tight">
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
            </div>

            {{-- Col 4/12 — Adorno geométrico + accent --}}
            <div class="lg:basis-1/3 flex items-center justify-center w-full">
                <div class="relative w-full max-w-xs">
                    {{-- Fondos decorativos blur --}}
                    <div class="absolute -top-4 -right-4 w-32 h-32 bg-primary/10 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="absolute -bottom-4 -left-4 w-24 h-24 bg-primary/5 rounded-full blur-xl pointer-events-none"></div>

                    {{-- Tarjeta accent --}}
                    <div class="relative bg-base-100 border border-base-content/10 rounded-3xl p-7 shadow-sm">
                        <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div class="space-y-3">
                            <div class="h-2 bg-primary/20 rounded-full w-3/4"></div>
                            <div class="h-2 bg-base-content/10 rounded-full w-full"></div>
                            <div class="h-2 bg-base-content/10 rounded-full w-5/6"></div>
                            <div class="h-2 bg-primary/10 rounded-full w-2/3 mt-4"></div>
                            <div class="h-2 bg-base-content/10 rounded-full w-full"></div>
                        </div>
                        <div class="mt-5 inline-flex items-center gap-2 bg-primary/10 text-primary text-xs font-bold px-3 py-1.5 rounded-full">
                            <span class="w-1.5 h-1.5 bg-primary rounded-full animate-pulse"></span>
                            {{ $tenant->city ?? 'Venezuela' }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
