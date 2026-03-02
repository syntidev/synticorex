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
@endphp

<section id="contacto" class="bg-base-200 py-8 sm:py-16 lg:py-24">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="relative mx-auto mb-12 w-fit">
      <h2 class="text-base-content text-2xl font-semibold md:text-3xl lg:text-4xl">Contactanos</h2>
      <span class="from-primary/40 to-primary/5 absolute start-0 top-9 h-1 w-full rounded-full bg-gradient-to-r"></span>
    </div>
    <div class="grid items-center gap-12 lg:grid-cols-2">
      @if($customization->google_maps_embed)
        <div class="rounded-2xl overflow-hidden w-full" style="height:300px">
          <iframe 
            src="{{ $customization->google_maps_embed }}"
            width="100%" height="300" 
            style="border:0;" 
            allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
          </iframe>
        </div>
      @else
        <div class="rounded-2xl bg-base-200 flex items-center justify-center w-full" style="height:300px">
          <span class="icon-[tabler--map-pin] size-16 text-primary/30"></span>
        </div>
      @endif
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


      </div>
    </div>
  </div>
</section>