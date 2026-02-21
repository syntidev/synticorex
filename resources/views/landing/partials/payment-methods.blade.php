{{-- Payment Methods Section Partial --}}
@if($plan?->show_payment_methods && $customization?->payment_methods)
<section id="medios-pago" class="py-16 px-4 bg-base-200">
    <div class="container mx-auto max-w-4xl">
        <h2 class="text-3xl font-bold text-center mb-12 text-primary">
            Medios de Pago Aceptados
        </h2>
        
        <div class="flex flex-wrap justify-center gap-3">
            @foreach($customization->payment_methods as $method => $enabled)
                @if($enabled)
                    <span class="badge badge-lg badge-outline badge-primary">,
                        @switch($method)
                            @case('zelle')
                                💵 Zelle
                                @break
                            @case('pago_movil')
                                📱 Pago Móvil
                                @break
                            @case('transferencia')
                                🏦 Transferencia
                                @break
                            @case('efectivo')
                                💰 Efectivo
                                @break
                            @case('binance')
                                ₿ Binance
                                @break
                            @case('cashea')
                                💳 Cashea
                                @break
                            @default
                                {{ ucfirst($method) }}
                        @endswitch
                    </span>
                @endif
            @endforeach
        </div>
    </div>
</section>
@endif
