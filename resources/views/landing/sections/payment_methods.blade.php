{{-- Payment Methods Section — Single ribbon, responsive, informational --}}
@php
    $payMethods      = $customization->payment_methods ?? [];
    $globalEnabled   = $payMethods['global'] ?? [];
    $currencyEnabled = $payMethods['currency'] ?? [];

    // Plan 1: fijos — no configurables
    if ($tenant->plan_id === 1) {
        $globalEnabled   = ['pagoMovil', 'cash'];
        $currencyEnabled = [];
    }

    // Orden canónico: Nacionales primero, Divisas al final
    $allPayMeta = [
        // ── Nacionales ──────────────────────────────────────────────
        'pagoMovil'  => ['label' => 'Pago Móvil',    'icon' => 'tabler:device-mobile'],
        'cash'       => ['label' => 'Efectivo',       'icon' => 'tabler:cash'],
        'puntoventa' => ['label' => 'Punto de Venta', 'icon' => 'tabler:credit-card'],
        'biopago'    => ['label' => 'Biopago',        'icon' => 'tabler:fingerprint'],
        'cashea'     => ['label' => 'Cashea',         'icon' => 'tabler:wallet'],
        'krece'      => ['label' => 'Krece',          'icon' => 'tabler:trending-up'],
        'wepa'       => ['label' => 'Wepa',           'icon' => 'tabler:shopping-cart'],
        'lysto'      => ['label' => 'Lysto',          'icon' => 'tabler:calendar-dollar'],
        'chollo'     => ['label' => 'Chollo',         'icon' => 'tabler:discount-2'],
        'wally'      => ['label' => 'Wally',          'icon' => 'tabler:send-2'],
        'kontigo'    => ['label' => 'Kontigo',        'icon' => 'tabler:file-invoice'],
        // ── Internacionales / Divisas ────────────────────────────────
        'zelle'      => ['label' => 'Zelle',          'icon' => 'tabler:bolt'],
        'paypal'     => ['label' => 'PayPal',         'icon' => 'tabler:brand-paypal'],
        'zinli'      => ['label' => 'Zinli',          'icon' => 'tabler:moneybag'],
        'airtm'      => ['label' => 'AirTM',          'icon' => 'tabler:exchange'],
        'reserve'    => ['label' => 'Reserve (RSV)',  'icon' => 'tabler:shield-dollar'],
        'binancepay' => ['label' => 'Binance Pay',    'icon' => 'tabler:currency-bitcoin'],
        'usdt'       => ['label' => 'USDT',           'icon' => 'tabler:coin'],
    ];

    $allCurrencyMeta = [
        'usd'  => ['label' => 'Dólares USD',  'icon' => 'tabler:currency-dollar'],
        'eur'  => ['label' => 'Euros',        'icon' => 'tabler:currency-euro'],
    ];

    $visibleMethods    = array_filter($allPayMeta,      fn($k) => in_array($k, $globalEnabled),   ARRAY_FILTER_USE_KEY);
    $visibleCurrencies = array_filter($allCurrencyMeta, fn($k) => in_array($k, $currencyEnabled), ARRAY_FILTER_USE_KEY);
    $visibleAll        = array_merge($visibleMethods, $visibleCurrencies);

    $hasAnything = !empty($visibleAll);
@endphp

@if($hasAnything)
<section id="payment_methods" class="relative overflow-hidden py-8 sm:py-16 lg:py-24 bg-surface">

    <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
        <div class="absolute -bottom-16 -left-16 size-64 rounded-full opacity-[0.04] blur-3xl"
             style="background:var(--color-primary)"></div>
    </div>

    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">

        {{-- Título único --}}
        <div class="text-center mb-8">
            <h2 class="text-foreground text-2xl font-semibold md:text-3xl lg:text-4xl"
                style="text-shadow: 0 4px 24px color-mix(in oklch, var(--color-foreground) 15%, transparent), 0 1px 4px color-mix(in oklch, var(--color-foreground) 8%, transparent);">
                {!! $customization->getSectionTitle('payment_methods', 'Medios de <span class="text-primary">Pago</span>') !!}
            </h2>
            <div class="w-16 h-0.5 mx-auto mt-4 rounded-full"
                 style="background:var(--color-primary);box-shadow:0 0 12px 2px color-mix(in oklch,var(--color-primary) 60%,transparent)"></div>
        </div>

        {{-- Una sola cinta responsive --}}
        <div class="flex flex-wrap justify-center gap-2 md:gap-3 mb-4">
            @foreach($visibleAll as $item)
                <span class="inline-flex items-center gap-1.5 px-3 py-2 rounded-full
                             bg-background border border-border
                             text-xs md:text-sm font-medium text-foreground
                             hover:border-primary/40 hover:bg-primary/5 transition-all whitespace-nowrap">
                    <iconify-icon icon="{{ $item['icon'] }}" width="16"></iconify-icon>
                    {{ $item['label'] }}
                </span>
            @endforeach
        </div>

        {{-- Aclarador informativo --}}
        <p class="text-center text-xs text-foreground/50">
            Información de medios de pago que aceptamos. Nuestro sitio web no es pasarela de pago.
        </p>

    </div>
</section>
@endif

