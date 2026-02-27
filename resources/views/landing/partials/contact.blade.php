{{--
    Contact Section — SYNTIweb
    ─────────────────────────────────────────
    Plan 1  : 3 bloques (Ubicación, Contacto, Horario) centrados, sin mapa
    Plan 2-3: Mapa embed + 3 bloques, teléfono secundario
    Variables:
      $tenant->plan_id
      $tenant->settings['business_info']['contact']['maps_url']
      $tenant->address, $tenant->city, $tenant->whatsapp_number, $tenant->phone, $tenant->email
      $tenant->settings['business_info']['schedule_display']
      $tenant->settings['contact_info']['phone_secondary']
--}}

@php
    $embedUrl     = data_get($tenant->settings, 'business_info.contact.maps_url', '');
    $hasMaps      = $tenant->plan_id >= 2 && !empty($embedUrl);

    $address      = $tenant->address ?? data_get($tenant->settings, 'contact_info.address', '');
    $city         = $tenant->city ?? '';
    $phone        = $tenant->whatsapp_number ?? $tenant->phone ?? '';
    $phoneClean   = preg_replace('/[^0-9]/', '', $phone);
    $schedule     = data_get($tenant->settings, 'business_info.schedule_display', '');
    $contactTitle = data_get($tenant->settings, 'business_info.contact.title', 'Contáctanos');
    $contactSub   = data_get($tenant->settings, 'business_info.contact.subtitle', 'Estamos aquí para atenderte');

    // Teléfono secundario (Plan 2-3 only)
    $phone2      = $tenant->plan_id >= 2 ? data_get($tenant->settings, 'contact_info.phone_secondary', '') : '';
    $phone2Clean = preg_replace('/[^0-9]/', '', $phone2);

    // Social networks
    $socialNetworks = is_array($customization->social_networks ?? null) ? $customization->social_networks : [];
    $socialMeta = [
        'instagram' => ['icon' => 'tabler:brand-instagram', 'label' => 'Instagram', 'base' => 'https://instagram.com/'],
        'facebook'  => ['icon' => 'tabler:brand-facebook',  'label' => 'Facebook',  'base' => 'https://facebook.com/'],
        'tiktok'    => ['icon' => 'tabler:brand-tiktok',    'label' => 'TikTok',    'base' => 'https://tiktok.com/@'],
        'linkedin'  => ['icon' => 'tabler:brand-linkedin',  'label' => 'LinkedIn',  'base' => 'https://linkedin.com/in/'],
        'youtube'   => ['icon' => 'tabler:brand-youtube',   'label' => 'YouTube',   'base' => ''],
        'x'         => ['icon' => 'tabler:brand-x',         'label' => 'Twitter/X', 'base' => 'https://x.com/'],
    ];
    $resolvedSocial = [];
    foreach ($socialNetworks as $key => $value) {
        if (!$value || !isset($socialMeta[$key])) continue;
        $url = (str_starts_with($value, 'http') || str_starts_with($value, '/'))
            ? $value
            : $socialMeta[$key]['base'] . ltrim($value, '@');
        $resolvedSocial[$key] = ['url' => $url, 'icon' => $socialMeta[$key]['icon'], 'label' => $socialMeta[$key]['label']];
    }
@endphp

<section id="contact" class="relative py-20 bg-base-100">
    <div class="container mx-auto px-4 md:px-6">

        {{-- Título de sección --}}
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold mb-4 text-base-content">
                {{ $contactTitle }}
            </h2>
            <p class="text-base-content/60 max-w-2xl mx-auto text-lg">
                {{ $contactSub }}
            </p>
        </div>

        {{-- Grid condicional: 2 cols (mapa + info) para Plan 2-3 con URL, 1 col centrada para Plan 1 --}}
        <div class="grid {{ $hasMaps ? 'grid-cols-1 lg:grid-cols-2 items-start gap-12' : 'grid-cols-1' }}">

            @if($hasMaps)
                {{-- ── COLUMNA MAPA (Plan 2-3 con URL configurada) ── --}}
                <div class="rounded-2xl overflow-hidden shadow-xl border border-base-200">
                    <iframe src="{{ $embedUrl }}"
                            width="100%"
                            height="320"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            class="w-full block">
                    </iframe>
                </div>
            @endif

            {{-- ── COLUMNA INFO (todos los planes) ── --}}
            <div class="{{ $hasMaps ? '' : 'max-w-4xl mx-auto w-full' }}">

                {{-- 3 bloques: Ubicación · Contacto · Horario --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">

                    {{-- 1. Ubicación --}}
                    <div class="p-6">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-primary/10
                                    flex items-center justify-center">
                            <iconify-icon icon="tabler:map-pin" width="32" class="text-primary"></iconify-icon>
                        </div>
                        <h3 class="font-bold text-lg mb-2">Ubicación</h3>
                        @if($address || $city)
                            <p class="text-base-content/70 text-sm leading-relaxed">
                                {{ $address }}@if($address && $city), @endif{{ $city }}
                            </p>
                        @else
                            <p class="text-base-content/30 text-sm italic">No configurada</p>
                        @endif
                    </div>

                    {{-- 2. Contacto --}}
                    <div class="p-6">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-success/10
                                    flex items-center justify-center">
                            <iconify-icon icon="tabler:phone" width="32" class="text-success"></iconify-icon>
                        </div>
                        <h3 class="font-bold text-lg mb-2">Contacto</h3>
                        @if($phoneClean)
                            <a href="https://wa.me/{{ $phoneClean }}"
                               target="_blank" rel="noopener noreferrer"
                               onclick="trackAnalyticsEvent('whatsapp_click', {phone: '{{ $phoneClean }}', section: 'contact'})"
                               class="block text-success hover:text-success/80 transition-colors font-medium mb-1">
                                +{{ $phoneClean }}
                            </a>
                            {{-- Botón de llamada telefónica --}}
                            <a href="tel:+{{ $phoneClean }}"
                               onclick="trackAnalyticsEvent('phone_click', {phone: '{{ $phoneClean }}', section: 'contact'})"
                               class="inline-flex items-center gap-1.5 mt-2 px-3 py-1.5 bg-primary/10 hover:bg-primary/20 text-primary rounded-lg transition-colors text-sm font-medium">
                                <iconify-icon icon="tabler:phone-call" width="16" height="16"></iconify-icon>
                                Llamar ahora
                            </a>
                        @endif
                        @if($phone2Clean)
                            <a href="tel:+{{ $phone2Clean }}"
                               class="block text-base-content/60 hover:text-base-content/90 transition-colors text-sm mb-1">
                                +{{ $phone2Clean }}
                            </a>
                        @endif
                        @if($tenant->email)
                            <a href="mailto:{{ $tenant->email }}"
                               class="block text-base-content/60 hover:text-base-content/90 transition-colors text-sm break-all">
                                {{ $tenant->email }}
                            </a>
                        @endif
                        @if(!$phoneClean && !$tenant->email)
                            <p class="text-base-content/30 text-sm italic">No configurado</p>
                        @endif
                    </div>

                    {{-- 3. Horario --}}
                    <div class="p-6">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-warning/10
                                    flex items-center justify-center">
                            <iconify-icon icon="tabler:clock" width="32" class="text-warning"></iconify-icon>
                        </div>
                        <h3 class="font-bold text-lg mb-2">Horario</h3>
                        @if($schedule)
                            <p class="text-base-content/70 text-sm leading-relaxed">{{ $schedule }}</p>
                        @else
                            <p class="text-base-content/30 text-sm italic">No configurado</p>
                        @endif
                    </div>

                </div>

                {{-- Redes Sociales --}}
                @if(count($resolvedSocial))
                    <div class="mt-10 flex flex-wrap justify-center gap-3">
                        @foreach($resolvedSocial as $net)
                            <a href="{{ $net['url'] }}"
                               target="_blank" rel="noopener noreferrer"
                               title="{{ $net['label'] }}"
                               class="flex items-center gap-2 px-4 py-2 rounded-xl
                                      bg-base-200 hover:bg-primary/10 hover:text-primary
                                      border border-base-content/10 hover:border-primary/30
                                      text-sm font-semibold transition-all">
                                <iconify-icon icon="{{ $net['icon'] }}" width="20"></iconify-icon>
                                {{ $net['label'] }}
                            </a>
                        @endforeach
                    </div>
                @endif

                {{-- CTA WhatsApp --}}
                @if($phoneClean)
                    <div class="mt-10 text-center">
                        <a href="https://wa.me/{{ $phoneClean }}?text={{ urlencode('Hola, vengo desde la web') }}"
                           target="_blank" rel="noopener noreferrer"
                           class="btn btn-success btn-lg gap-3
                                  shadow-lg shadow-success/20 hover:shadow-success/30
                                  transition-all">
                            <iconify-icon icon="tabler:brand-whatsapp" width="24"></iconify-icon>
                            Escríbenos por WhatsApp
                        </a>
                    </div>
                @endif

            </div>
        </div>

    </div>
</section>
