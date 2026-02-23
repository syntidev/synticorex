{{-- Path: resources/views/landing/partials/payment_methods.blade.php --}}
{{-- Informational payment methods section — no payment processing happens here --}}
@php
    $payMethods    = $customization->payment_methods ?? [];
    $globalEnabled = $payMethods['global'] ?? [];

    // Plan 1: always display Pago Móvil + Biopago (fixed)
    if (isset($plan) && (int) $plan->id === 1) {
        $globalEnabled = ['pagoMovil', 'biopago'];
    }

    $allPayMeta = [
        'pagoMovil'  => ['label' => 'Pago Móvil',    'icon' => 'device-mobile',  'color' => 'text-blue-400'],
        'biopago'    => ['label' => 'Biopago',        'icon' => 'fingerprint',    'color' => 'text-green-400'],
        'puntoventa' => ['label' => 'Punto de Venta', 'icon' => 'credit-card',    'color' => 'text-purple-400'],
        'zinli'      => ['label' => 'Zinli',          'icon' => 'wallet',         'color' => 'text-violet-400'],
        'zelle'      => ['label' => 'Zelle',          'icon' => 'bolt',           'color' => 'text-yellow-400'],
        'paypal'     => ['label' => 'PayPal',         'icon' => 'brand-paypal',   'color' => 'text-sky-400'],
    ];

    $visibleMethods = array_filter(
        $allPayMeta,
        fn($k) => in_array($k, $globalEnabled),
        ARRAY_FILTER_USE_KEY
    );
@endphp

@if(isset($plan) && !empty($visibleMethods))
<section id="medios-de-pago" class="py-20 bg-base-200/40">
    <div class="container mx-auto px-8 md:px-16">

        {{-- Header --}}
        <div class="text-center mb-12">
            <span class="inline-block text-xs font-bold uppercase tracking-[0.3em] text-primary mb-4">Formas de pago</span>
            <h2 class="text-3xl md:text-4xl font-black text-base-content tracking-tighter">
                Medios de <span class="text-primary italic">Pago</span>
            </h2>
            <p class="text-base-content/40 mt-3 text-xs uppercase tracking-widest">Solo informativo — ningún pago se procesa aquí</p>
            <div class="w-16 h-1 bg-primary mx-auto rounded-full mt-5"></div>
        </div>

        {{-- Method Pills --}}
        <div class="flex flex-wrap justify-center gap-4 max-w-3xl mx-auto">
            @foreach($visibleMethods as $mkey => $m)
            <div class="flex items-center gap-3 bg-base-100 border border-base-300/50 rounded-2xl px-6 py-4 shadow-sm hover:shadow-md hover:border-primary/30 transition-all duration-300">
                <iconify-icon icon="tabler:{{ $m['icon'] }}" class="{{ $m['color'] }} shrink-0" width="24" height="24"></iconify-icon>
                <span class="text-base-content font-semibold text-sm">{{ $m['label'] }}</span>
            </div>
            @endforeach
        </div>

    </div>
</section>
@endif
