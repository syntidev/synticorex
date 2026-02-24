{{--
    Header Top Partial — SYNTIweb
    ─────────────────────────────────────
    Plan requerido : 2 (CRECIMIENTO) o 3 (VISIÓN)
    Guard de plan  : en base.blade.php (@if plan_id >= 2)
    Posición       : fixed top-0 z-[110] — encima del nav principal (z-100)
    Altura         : 40px (h-10)
    Variables      :
      $tenant->settings['business_info']['schedule_display']
      $tenant->settings['contact_info']['phone']
      $tenant->settings['business_info']['delivery_available']
    Fallbacks      : ver @php abajo
--}}

@php
    $schedule = data_get($tenant->settings, 'business_info.schedule_display', 'Lun–Sáb 9:00–18:00');
    $phone    = data_get($tenant->settings, 'contact_info.phone',
                    preg_replace('/[^0-9]/', '', $tenant->whatsapp_number ?? ''));
    $delivery = (bool) data_get($tenant->settings, 'business_info.delivery_available', false);

    // Número limpio para tel: y wa.me
    $phoneClean = preg_replace('/[^0-9]/', '', $phone);
    $phoneDisplay = $phone ?: null;
@endphp

<div id="header-top" class="fixed top-0 left-0 right-0 z-[110] h-10 bg-base-200 border-b border-base-content/10 flex items-center">
    <div class="container mx-auto px-4 md:px-6 flex items-center justify-between gap-4 text-xs">

        {{-- ── Izquierda: Horario ──────────────────────────── --}}
        @if($schedule)
        <div class="flex items-center gap-1.5 text-base-content/60 min-w-0">
            <iconify-icon icon="tabler:clock" width="14" height="14" class="shrink-0 text-primary/70"></iconify-icon>
            <span class="hidden sm:block truncate">{{ $schedule }}</span>
            {{-- Mobile: solo ícono, tooltip nativo --}}
            <span class="sm:hidden" title="{{ $schedule }}"></span>
        </div>
        @endif

        {{-- ── Centro: Delivery badge ──────────────────────── --}}
        @if($delivery)
        <div class="hidden md:flex items-center gap-1.5 text-base-content/60">
            <iconify-icon icon="tabler:motorbike" width="14" height="14" class="text-primary/70 shrink-0"></iconify-icon>
            <span>Delivery disponible</span>
        </div>
        @endif

        {{-- ── Derecha: Teléfono ───────────────────────────── --}}
        @if($phoneDisplay)
        <div class="flex items-center gap-1.5 ml-auto">
            <iconify-icon icon="tabler:phone" width="14" height="14" class="shrink-0 text-primary/70"></iconify-icon>
            {{-- Desktop: número visible con enlace --}}
            <a href="tel:+{{ $phoneClean }}"
               class="hidden sm:block font-medium text-base-content/70 hover:text-primary transition-colors tabular-nums">
                +{{ $phoneClean }}
            </a>
            {{-- Mobile: solo ícono enlazado --}}
            <a href="tel:+{{ $phoneClean }}" class="sm:hidden text-base-content/70 hover:text-primary transition-colors" title="+{{ $phoneClean }}">
                <iconify-icon icon="tabler:phone-call" width="14" height="14"></iconify-icon>
            </a>
        </div>
        @endif

    </div>
</div>
