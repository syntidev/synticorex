{{--
    Contact Section — SYNTIweb FlyonUI
    Centralizado: img + cards grid (Horario, Direccion, WhatsApp, Email)
--}}

@php
    $embedUrl     = data_get($tenant->settings, 'business_info.contact.maps_url', '');
    $hasMaps      = $tenant->isAtLeastCrecimiento() && !empty($embedUrl);

    $address      = $tenant->address ?? data_get($tenant->settings, 'contact_info.address', '');
    $city         = $tenant->city ?? '';
    $phone        = $tenant->whatsapp_number ?? $tenant->phone ?? '';
    $phoneClean   = preg_replace('/[^0-9]/', '', $phone);
    $schedule     = data_get($tenant->settings, 'business_info.schedule_display', '');

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

<section id="contacto" class="bg-base-200 py-8 sm:py-16 lg:py-24">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="relative mx-auto mb-12 w-fit">
      <h2 class="text-base-content text-2xl font-semibold md:text-3xl lg:text-4xl">Contactanos</h2>
      <span class="from-primary/40 to-primary/5 absolute start-0 top-9 h-1 w-full rounded-full bg-gradient-to-r"></span>
    </div>
    <div class="grid items-center gap-12 lg:grid-cols-2">
      <img src="{{ $customization->contact_image_url ?? 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=800' }}"
           alt="Contactanos" class="size-full rounded-2xl object-cover">
      <div>
        <h3 class="text-base-content mb-6 text-xl font-semibold">
          {{ $tenant->tagline ?? 'Estamos para ayudarte!' }}
        </h3>
        <p class="text-base-content/80 mb-10 text-lg font-medium">
          {{ $tenant->description ?? 'Contactanos y te atendemos a la brevedad.' }}
        </p>
        <div class="grid gap-6 md:grid-cols-2">
          <div class="card shadow-none">
            <div class="card-body items-center gap-3">
              <div class="avatar avatar-placeholder">
                <div class="border-primary/20 text-primary w-9 rounded-full border">
                  <span class="icon-[tabler--clock] text-primary size-6"></span>
                </div>
              </div>
              <h4 class="text-base-content text-base font-medium">Horario</h4>
              <div class="text-center text-base-content/80">
                @php
                  $hours = $tenant->business_hours;
                  $hoursText = is_array($hours) 
                    ? collect($hours)->map(fn($h,$d) => "$d: $h")->implode(' | ')
                    : (is_string($hours) ? $hours : 'Lun–Sáb 9:00–18:00');
                @endphp
                <p>{{ $hoursText }}</p>
              </div>
            </div>
          </div>
          <div class="card shadow-none">
            <div class="card-body items-center gap-3">
              <div class="avatar avatar-placeholder">
                <div class="border-primary/20 text-primary w-9 rounded-full border">
                  <span class="icon-[tabler--map-pin] text-primary size-6"></span>
                </div>
              </div>
              <h4 class="text-base-content text-base font-medium">Direccion</h4>
              <address class="text-base-content/80 text-center not-italic">
                {{ $address }}<br>
                {{ $city }}{{ $tenant->state ? ', ' . $tenant->state : '' }}
              </address>
            </div>
          </div>
          <div class="card shadow-none">
            <div class="card-body items-center gap-3">
              <div class="avatar avatar-placeholder">
                <div class="border-primary/20 text-primary w-9 rounded-full border">
                  <span class="icon-[tabler--brand-whatsapp] text-primary size-6"></span>
                </div>
              </div>
              <h4 class="text-base-content text-base font-medium">WhatsApp</h4>
              @if($phoneClean)
                <a href="https://wa.me/{{ $phoneClean }}"
                   class="text-primary font-semibold hover:underline">
                  +{{ $phoneClean }}
                </a>
              @else
                <p class="text-base-content/40 text-sm italic">No configurado</p>
              @endif
            </div>
          </div>
          <div class="card shadow-none">
            <div class="card-body items-center gap-3">
              <div class="avatar avatar-placeholder">
                <div class="border-primary/20 text-primary w-9 rounded-full border">
                  <span class="icon-[tabler--mail] text-primary size-6"></span>
                </div>
              </div>
              <h4 class="text-base-content text-base font-medium">Email</h4>
              <div class="text-center text-base-content/80">
                @if($tenant->email)
                  <p>{{ $tenant->email }}</p>
                @endif
                @if($tenant->phone)
                  <p>{{ $tenant->phone }}</p>
                @endif
                @if(!$tenant->email && !$tenant->phone)
                  <p class="text-base-content/40 text-sm italic">No configurado</p>
                @endif
              </div>
            </div>
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
                              bg-base-100 hover:bg-primary/10 hover:text-primary
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
                    <span class="icon-[tabler--brand-whatsapp] size-6"></span>
                    Escribenos por WhatsApp
                </a>
            </div>
        @endif

      </div>
    </div>
  </div>
</section>