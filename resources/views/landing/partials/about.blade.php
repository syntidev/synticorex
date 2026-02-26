{{-- About Section — Plan 2+ --}}
@php
    $description = $tenant->description ?? null;
    $slogan      = $tenant->slogan ?? null;
    $hours       = $tenant->business_hours ?? [];
    $address     = $tenant->address ?? null;
    $city        = $tenant->city ?? null;
    $phone       = $tenant->phone ?? null;
    $email       = $tenant->email ?? null;

    $days = [
        'monday'    => 'Lunes',
        'tuesday'   => 'Martes',
        'wednesday' => 'Miércoles',
        'thursday'  => 'Jueves',
        'friday'    => 'Viernes',
        'saturday'  => 'Sábado',
        'sunday'    => 'Domingo',
    ];
@endphp

<section id="about" class="py-20 px-4 bg-base-100">
    <div class="container mx-auto max-w-5xl">

        {{-- Header --}}
        <div class="text-center mb-14">
            <span class="text-primary text-sm font-semibold uppercase tracking-widest mb-3 block">Acerca de nosotros</span>
            <h2 class="text-3xl md:text-4xl font-bold text-base-content mb-4">
                {{ $tenant->business_name }}
            </h2>
            @if($slogan)
                <p class="text-lg text-base-content/60 max-w-xl mx-auto italic">"{{ $slogan }}"</p>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-start">

            {{-- Descripción --}}
            <div>
                @if($description)
                    <p class="text-base-content/80 leading-relaxed text-lg mb-6">
                        {{ $description }}
                    </p>
                @endif

                {{-- Datos de contacto --}}
                <ul class="space-y-3">
                    @if($address || $city)
                        <li class="flex items-start gap-3 text-sm text-base-content/70">
                            <svg class="w-5 h-5 text-primary mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>{{ implode(', ', array_filter([$address, $city])) }}</span>
                        </li>
                    @endif

                    @if($phone)
                        <li class="flex items-center gap-3 text-sm text-base-content/70">
                            <svg class="w-5 h-5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>{{ $phone }}</span>
                        </li>
                    @endif

                    @if($email)
                        <li class="flex items-center gap-3 text-sm text-base-content/70">
                            <svg class="w-5 h-5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ $email }}</span>
                        </li>
                    @endif
                </ul>
            </div>

            {{-- Horarios --}}
            @if(!empty($hours))
                <div class="bg-base-200 rounded-2xl p-6">
                    <h3 class="font-bold text-base-content mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Horario de Atención
                    </h3>
                    <ul class="space-y-2">
                        @foreach($days as $key => $label)
                            @if(isset($hours[$key]))
                                @php $h = $hours[$key]; @endphp
                                <li class="flex justify-between text-sm">
                                    <span class="text-base-content/60 font-medium">{{ $label }}</span>
                                    @if($h['closed'] ?? false)
                                        <span class="text-error/70">Cerrado</span>
                                    @else
                                        <span class="text-base-content font-semibold">
                                            {{ $h['open'] ?? '--:--' }} – {{ $h['close'] ?? '--:--' }}
                                        </span>
                                    @endif
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @elseif(!$description)
                {{-- Fallback si no hay descripción ni horarios --}}
                <div class="bg-base-200 rounded-2xl p-8 flex flex-col items-center justify-center text-center gap-3">
                    <svg class="w-10 h-10 text-primary/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <p class="text-base-content/40 text-sm">Completa tu perfil de negocio en el dashboard</p>
                </div>
            @endif

        </div>
    </div>
</section>
