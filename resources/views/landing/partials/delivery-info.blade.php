{{-- Delivery Information Component -- SYNTIweb --}}
@php
    $deliveryAvailable = (bool) data_get($tenant->settings, 'business_info.delivery_available', false);
    $deliveryZones = collect(data_get($tenant->settings, 'business_info.delivery_zones', []))
        ->filter(fn($zone) => !empty($zone['name']) && !empty($zone['cost']))
        ->take(5)
        ->values();
    
    $deliveryCost = data_get($tenant->settings, 'business_info.delivery_cost', 0);
    $minOrder = data_get($tenant->settings, 'business_info.delivery_min_order', 0);
    $deliveryTime = data_get($tenant->settings, 'business_info.delivery_time', '30-45 min');
@endphp

@if($deliveryAvailable)
<section id="delivery-info" class="py-12 bg-gradient-to-br from-primary/5 to-success/5">
    <div class="container mx-auto px-6 max-w-4xl">
        <div class="text-center mb-8">
            <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-success/10 flex items-center justify-center">
                <iconify-icon icon="tabler:motorbike" width="32" class="text-success"></iconify-icon>
            </div>
            <h2 class="text-base-content text-2xl font-semibold md:text-3xl lg:text-4xl">
                Información de <span class="text-primary italic">Delivery</span>
            </h2>
            <div class="w-16 h-1 bg-primary mx-auto mt-4 rounded-full"></div>
            <p class="text-base-content/70 mt-2">Llevamos tu pedido hasta la puerta de tu casa</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            {{-- Tarjeta de Tiempo --}}
            <div class="bg-base-100 rounded-xl p-6 shadow-sm border border-base-content/10">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-warning/10 flex items-center justify-center">
                        <iconify-icon icon="tabler:clock" width="20" class="text-warning"></iconify-icon>
                    </div>
                    <h3 class="text-xl font-semibold">Tiempo de Entrega</h3>
                </div>
                <p class="text-2xl font-bold text-warning mb-1">{{ $deliveryTime }}</p>
                <p class="text-sm text-base-content/60">Promedio estimado</p>
            </div>

            {{-- Tarjeta de Costo --}}
            <div class="bg-base-100 rounded-xl p-6 shadow-sm border border-base-content/10">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                        <iconify-icon icon="tabler:currency-dollar" width="20" class="text-primary"></iconify-icon>
                    </div>
                    <h3 class="text-xl font-semibold">Costo de Delivery</h3>
                </div>
                <p class="text-2xl font-bold text-primary mb-1">
                    @if($deliveryCost > 0)
                        ${{ number_format($deliveryCost, 2) }}
                    @else
                        ¡Gratis!
                    @endif
                </p>
                <p class="text-sm text-base-content/60">
                    @if($minOrder > 0)
                        Mínimo ${{ number_format($minOrder, 2) }}
                    @else
                        Sin mínimo
                    @endif
                </p>
            </div>

            {{-- Tarjeta de Zonas --}}
            <div class="bg-base-100 rounded-xl p-6 shadow-sm border border-base-content/10">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-success/10 flex items-center justify-center">
                        <iconify-icon icon="tabler:map-2" width="20" class="text-success"></iconify-icon>
                    </div>
                    <h3 class="text-xl font-semibold">Zonas de Cobertura</h3>
                </div>
                @if($deliveryZones->isNotEmpty())
                    <div class="space-y-2">
                        @foreach($deliveryZones->take(3) as $zone)
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-base-content/80">{{ $zone['name'] }}</span>
                                <span class="font-medium text-success">
                                    @if($zone['cost'] > 0)
                                        ${{ number_format($zone['cost'], 2) }}
                                    @else
                                        Gratis
                                    @endif
                                </span>
                            </div>
                        @endforeach
                        @if($deliveryZones->count() > 3)
                            <p class="text-xs text-base-content/50 italic">
                                +{{ $deliveryZones->count() - 3 }} zonas más
                            </p>
                        @endif
                    </div>
                @else
                    <p class="text-sm text-base-content/60">Cobertura amplia en la ciudad</p>
                @endif
            </div>
        </div>

        {{-- Nota importante --}}
        <div class="mt-8 p-4 bg-base-100 rounded-lg border border-base-content/10">
            <div class="flex items-start gap-3">
                <iconify-icon icon="tabler:info-circle" width="20" class="text-primary mt-0.5"></iconify-icon>
                <div class="text-sm text-base-content/70">
                    <p class="font-medium text-base-content mb-1">Información Importante:</p>
                    <p>Los tiempos de entrega son estimados y pueden variar según la distancia, tráfico y condiciones climáticas. 
                       Confirma tu dirección y tiempo estimado al realizar tu pedido.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
