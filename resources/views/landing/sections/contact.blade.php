{{--
    Contact Section — SYNTIweb Preline 4.1.2
    Centralizado: map + cards grid (Horario, Direccion, WhatsApp, Email)
--}}

@php
    $embedUrl     = data_get($tenant->settings, 'business_info.contact.maps_url', '');
    $hasMaps      = $tenant->isAtLeastCrecimiento() && !empty($embedUrl);

    $address      = $tenant->address ?? data_get($tenant->settings, 'contact_info.address', '');
    $city         = $tenant->city ?? '';
    
    // WhatsApp: primario + soporte (Plan 2/3)
    $whatsappMain = $tenant->getActiveWhatsapp() ?? '';
    $whatsappSupport = $tenant->whatsapp_support ?? '';
    $whatsappMainClean = preg_replace('/[^0-9]/', '', $whatsappMain);
    $whatsappSupportClean = preg_replace('/[^0-9]/', '', $whatsappSupport ?? '');
    
    // Email
    $email = $tenant->email ?? '';
    $phone = $tenant->phone ?? '';

    // Título y subtítulo de la sección Contacto (variables propias, independientes de description)
    $contactTitle    = data_get($tenant->settings, 'business_info.contact.title')
                        ?: ($tenant->slogan ?: 'Estamos para ayudarte');
    $contactSubtitle = data_get($tenant->settings, 'business_info.contact.subtitle')
                        ?: '';
@endphp

<section id="contact" class="bg-surface py-10 sm:py-16 lg:py-20">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12 space-y-3">
      <h2 class="text-foreground text-2xl font-semibold md:text-3xl lg:text-4xl">
        {!! $customization->getSectionTitle('contact', 'Contáctanos') !!}
      </h2>
      <div class="w-16 h-1 bg-primary mx-auto rounded-full"></div>
    </div>

    <div class="grid items-stretch gap-10 lg:grid-cols-2 lg:gap-12">
      
      {{-- Columna 1: Google Maps --}}
      @if($hasMaps)
        <div class="rounded-2xl overflow-hidden w-full shadow-sm">
          <iframe 
            src="{{ $embedUrl }}"
            width="100%" 
            style="border:0; height: 100%; min-height: 280px;" 
            allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            title="Ubicación en Google Maps">
          </iframe>
        </div>
      @else
        <div class="rounded-2xl bg-layer border border-card-line flex items-center justify-center w-full">
          <div class="text-center">
            <span class="iconify tabler--map-pin size-16 text-primary/30"></span>
            <p class="text-foreground/40 text-sm mt-2">Mapa no disponible</p>
          </div>
        </div>
      @endif

      {{-- Columna 2: Cards de contacto --}}
      <div class="flex flex-col space-y-4">
        {{-- Encabezado de la sección Contacto --}}
        <div>
          <h3 class="text-foreground text-lg font-semibold mb-2">
            {{ $contactTitle }}
          </h3>
          @if($contactSubtitle)
          <p class="text-foreground/70 text-sm">
            {{ $contactSubtitle }}
          </p>
          @endif
        </div>

        {{-- Grid 2x2 de cards — se expande para llenar altura --}}
        <div class="grid gap-4 sm:grid-cols-2 flex-1">
          
          {{-- Card: Horario --}}
          <div class="bg-card border border-card-line rounded-lg p-4 shadow-sm">
            <div class="flex flex-col items-center text-center gap-2">
              <span class="iconify tabler--clock size-8 text-primary"></span>
              <h4 class="text-foreground font-semibold text-sm">Horario</h4>
              <div class="text-foreground/70 text-xs space-y-1">
                @php
                  $days = ['monday'=>'Lun','tuesday'=>'Mar','wednesday'=>'Mié',
                           'thursday'=>'Jue','friday'=>'Vie','saturday'=>'Sáb','sunday'=>'Dom'];
                  $hours = is_string($tenant->business_hours) 
                    ? json_decode($tenant->business_hours, true) 
                    : (is_array($tenant->business_hours) ? $tenant->business_hours : []);
                  $open = collect($hours)->filter(fn($h) => $h && isset($h['open']))
                    ->map(fn($h,$d) => ($days[$d] ?? $d).': '.$h['open'].'-'.$h['close']);
                  $firstDay = $open->keys()->first();
                  $lastDay = $open->keys()->last();
                  $hoursStr = $open->isEmpty() ? 'Lun–Sáb 9:00–18:00' 
                    : ($days[$firstDay].'–'.$days[$lastDay].': '.$hours[$firstDay]['open'].'–'.$hours[$lastDay]['close']);
                @endphp
                <p>{{ $hoursStr }}</p>
              </div>
            </div>
          </div>

          {{-- Card: Dirección --}}
          <div class="bg-card border border-card-line rounded-lg p-4 shadow-sm">
            <div class="flex flex-col items-center text-center gap-2">
              <span class="iconify tabler--map-pin size-8 text-primary"></span>
              <h4 class="text-foreground font-semibold text-sm">Dirección</h4>
              <address class="text-foreground/70 text-xs not-italic leading-snug">
                {{ $address }}<br>
                {{ $city }}{{ $tenant->state ? ', ' . $tenant->state : '' }}
              </address>
            </div>
          </div>

          {{-- Card: WhatsApp --}}
          <div class="bg-card border border-card-line rounded-lg p-4 shadow-sm">
            <div class="flex flex-col items-center text-center gap-2">
              <span class="iconify tabler--brand-whatsapp size-8 text-primary"></span>
              <h4 class="text-foreground font-semibold text-sm">WhatsApp</h4>
              @if($whatsappMainClean)
                <a href="https://wa.me/{{ $whatsappMainClean }}"
                   class="text-primary font-semibold text-xs hover:underline">
                  +{{ $whatsappMainClean }}
                </a>
              @else
                <p class="text-foreground/40 text-xs italic">No configurado</p>
              @endif
            </div>
          </div>

          {{-- Card: Email + Soporte --}}
          <div class="bg-card border border-card-line rounded-lg p-4 shadow-sm">
            <div class="flex flex-col items-center text-center gap-2">
              <span class="iconify tabler--mail size-8 text-primary"></span>
              <h4 class="text-foreground font-semibold text-sm">Contacto</h4>
              <div class="text-foreground/70 text-xs space-y-1">
                @if($email)
                  <p>{{ $email }}</p>
                @endif
                {{-- Mostrar soporte si Plan 2/3 y existe --}}
                @if($whatsappSupportClean && $tenant->plan && in_array($tenant->plan->slug, ['crecimiento', 'vision']))
                  <p class="text-primary font-semibold">
                    <a href="https://wa.me/{{ $whatsappSupportClean }}" class="hover:underline">
                      Soporte: +{{ $whatsappSupportClean }}
                    </a>
                  </p>
                @elseif($phone && !$email)
                  <p>{{ $phone }}</p>
                @endif
                @if(!$email && !$whatsappSupportClean && !$phone)
                  <p class="text-foreground/40 italic">No configurado</p>
                @endif
              </div>
            </div>
          </div>

        </div>{{-- /grid --}}
      </div>{{-- /col 2 --}}

    </div>{{-- /grid principal --}}
  </div>
</section>