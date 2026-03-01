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
        // Nacionales / Flujo
        'pagoMovil'  => ['label' => 'Pago Móvil',    'icon' => 'tabler:device-mobile'],
        'cash'       => ['label' => 'Efectivo',       'icon' => 'tabler:cash'],
        'puntoventa' => ['label' => 'Punto de Venta', 'icon' => 'tabler:credit-card'],
        'biopago'    => ['label' => 'Biopago',        'icon' => 'tabler:fingerprint'],
        'cashea'     => ['label' => 'Cashea',         'icon' => 'tabler:shopping-cart-dollar'],
        'krece'      => ['label' => 'Krece',          'icon' => 'tabler:trending-up'],
        'wepa'       => ['label' => 'Wepa',           'icon' => 'tabler:device-mobile-dollar'],
        'lysto'      => ['label' => 'Lysto',          'icon' => 'tabler:calendar-dollar'],
        'chollo'     => ['label' => 'Chollo',         'icon' => 'tabler:tag'],
        // Divisas
        'zelle'      => ['label' => 'Zelle',          'icon' => 'tabler:bolt'],
        'zinli'      => ['label' => 'Zinli',          'icon' => 'tabler:wallet'],
        'paypal'     => ['label' => 'PayPal',         'icon' => 'tabler:brand-paypal'],
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
<section id="payment_methods" class="py-8 sm:py-16 lg:py-24 bg-base-200/50">
    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">

        {{-- Título único --}}
        <div class="text-center mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-base-content">Medios de Pago</h2>
        </div>

        {{-- Una sola cinta responsive --}}
        <div class="flex flex-wrap justify-center gap-2 md:gap-3 mb-4">
            @foreach($visibleAll as $item)
                <span class="inline-flex items-center gap-1.5 px-3 py-2 rounded-full
                             bg-base-100 border border-base-300/60
                             text-xs md:text-sm font-medium text-base-content
                             hover:border-primary/40 hover:bg-primary/5 transition-all whitespace-nowrap">
                    <iconify-icon icon="{{ $item['icon'] }}" width="16"></iconify-icon>
                    {{ $item['label'] }}
                </span>
            @endforeach
        </div>

        {{-- Aclarador informativo --}}
        <p class="text-center text-xs text-base-content/50">
            Información de medios de pago que aceptamos. Nuestro sitio web no es pasarela de pago.
        </p>

    </div>
</section>
@endif

