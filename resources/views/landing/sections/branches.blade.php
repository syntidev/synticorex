{{-- Path: resources/views/landing/sections/branches.blade.php --}}
{{-- Only renders for Plan 3 (VISIÓN) --}}
@php
    if (!$tenant->isVision()) return;

    $activeBranches = isset($tenant) && $tenant->relationLoaded('branches')
        ? $tenant->branches->where('is_active', true)
        : collect();

    $branchPayMethodsMeta = [
        'pagoMovil'  => ['label' => 'Pago Móvil',    'icon' => 'device-mobile', 'color' => 'text-blue-500'],
        'biopago'    => ['label' => 'Biopago',        'icon' => 'fingerprint',   'color' => 'text-green-500'],
        'puntoventa' => ['label' => 'Punto de Venta', 'icon' => 'credit-card',   'color' => 'text-purple-500'],
        'zinli'      => ['label' => 'Zinli',          'icon' => 'wallet',        'color' => 'text-violet-500'],
        'zelle'      => ['label' => 'Zelle',          'icon' => 'bolt',          'color' => 'text-yellow-500'],
        'paypal'     => ['label' => 'PayPal',         'icon' => 'brand-paypal',  'color' => 'text-sky-500'],
    ];

    $branchPayMethodsData = ($customization->payment_methods['branches'] ?? []);

    $colClass = match(true) {
        $activeBranches->count() === 1 => 'max-w-sm mx-auto',
        $activeBranches->count() === 2 => 'sm:grid-cols-2 max-w-2xl mx-auto',
        default                        => 'sm:grid-cols-2 lg:grid-cols-3',
    };
@endphp

@if($activeBranches->isNotEmpty())
<section id="branches" class="py-12 sm:py-20 lg:py-28 bg-background">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

        {{-- Section header --}}
        <div class="text-center mb-12">
            <span class="inline-block text-xs font-bold uppercase tracking-[0.3em] text-primary mb-3">{{ $customization->getContentBlock('branches', 'eyebrow') ?: 'Nuestras sedes' }}</span>
            <h2 class="text-foreground text-2xl font-semibold md:text-3xl lg:text-4xl">
                {!! $customization->getSectionTitle('branches', 'Encuéntranos <span class="text-primary italic">Cerca de Ti</span>') !!}
            </h2>
            <div class="w-16 h-1 bg-primary mx-auto rounded-full mt-5"></div>
        </div>

        {{-- Cards grid --}}
        <div class="grid grid-cols-1 {{ $colClass }} gap-6">
            @foreach($activeBranches as $branch)
            @php $branchMethods = $branchPayMethodsData[(string)$branch->id] ?? []; @endphp

            <div class="bg-card border border-card-line rounded-xl shadow-2xs overflow-hidden flex flex-col">

                {{-- Accent panel — replaces image, shows location icon --}}
                <div class="relative w-full bg-primary/10 flex items-center justify-center py-10">
                    <div class="w-16 h-16 rounded-2xl bg-primary/15 border border-primary/25 flex items-center justify-center">
                        <iconify-icon icon="tabler:map-pin-filled" class="text-primary" width="32" height="32"></iconify-icon>
                    </div>
                </div>

                {{-- Card body --}}
                <div class="p-5 flex flex-col flex-1">

                    <h3 class="text-base font-semibold text-foreground leading-snug">
                        {{ $branch->name }}
                    </h3>

                    @if($branch->address)
                    <p class="mt-2 text-sm text-muted-foreground-1 leading-relaxed flex items-start gap-1.5">
                        <iconify-icon icon="tabler:map-pin" class="shrink-0 mt-0.5 text-primary/70" width="14" height="14"></iconify-icon>
                        {{ $branch->address }}
                    </p>
                    @endif

                    @if($branch->phone)
                    <p class="mt-1.5 text-sm text-muted-foreground-1 flex items-center gap-1.5">
                        <iconify-icon icon="tabler:phone" class="shrink-0 text-primary/70" width="14" height="14"></iconify-icon>
                        {{ $branch->phone }}
                    </p>
                    @endif

                    @if($branch->schedule)
                    <p class="mt-1.5 text-sm text-muted-foreground-1 flex items-center gap-1.5">
                        <iconify-icon icon="tabler:clock" class="shrink-0 text-primary/70" width="14" height="14"></iconify-icon>
                        {{ $branch->schedule }}
                    </p>
                    @endif

                    {{-- Payment methods pills --}}
                    @if(!empty($branchMethods))
                    <div class="mt-auto pt-4 border-t border-card-line mt-4">
                        <p class="text-xs font-medium text-muted-foreground-1 uppercase tracking-wider mb-2">Acepta</p>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($branchMethods as $bMethod)
                            @if(isset($branchPayMethodsMeta[$bMethod]))
                            @php $bm = $branchPayMethodsMeta[$bMethod]; @endphp
                            <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-0.5 rounded-full bg-muted text-foreground">
                                <iconify-icon icon="tabler:{{ $bm['icon'] }}" class="{{ $bm['color'] }}" width="12" height="12"></iconify-icon>
                                {{ $bm['label'] }}
                            </span>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>
@endif
