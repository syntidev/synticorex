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
<section id="branches" class="relative overflow-hidden py-12 sm:py-20 lg:py-28 bg-background">

    <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
        <div class="absolute -top-20 -right-20 size-80 rounded-full opacity-[0.05] blur-3xl"
             style="background:var(--color-primary)"></div>
    </div>

    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

        {{-- Section header --}}
        <div class="text-center mb-12">
            <span class="inline-block text-xs font-bold uppercase tracking-[0.3em] text-primary mb-3">{{ $customization->getContentBlock('branches', 'eyebrow') ?: 'Nuestras sedes' }}</span>
            <h2 class="text-foreground text-2xl font-semibold md:text-3xl lg:text-4xl"
                style="text-shadow: 0 4px 24px color-mix(in oklch, var(--color-foreground) 15%, transparent), 0 1px 4px color-mix(in oklch, var(--color-foreground) 8%, transparent);">
                {!! $customization->getSectionTitle('branches', 'Encuéntranos <span class="text-primary italic">Cerca de Ti</span>') !!}
            </h2>
            <div class="w-16 h-0.5 mx-auto rounded-full mt-5"
                 style="background:var(--color-primary);box-shadow:0 0 12px 2px color-mix(in oklch,var(--color-primary) 60%,transparent)"></div>
        </div>

        {{-- Cards grid --}}
        <div class="grid grid-cols-1 {{ $colClass }} gap-6">
            @foreach($activeBranches as $branch)
            @php $branchMethods = $branchPayMethodsData[(string)$branch->id] ?? []; @endphp

            <div class="bg-card border border-card-line rounded-2xl shadow-2xs overflow-hidden flex flex-col hover:-translate-y-1 hover:shadow-md transition-all duration-300">

                {{-- Accent panel — replaces image, shows location icon --}}
                <div class="relative w-full bg-primary/10 flex items-center justify-center py-10">
                    <div class="w-16 h-16 rounded-2xl bg-primary/15 border border-primary/25 flex items-center justify-center">
                        <span class="iconify tabler--map-pin-filled text-primary size-6" aria-hidden="true"></span>
                    </div>
                </div>

                {{-- Card body --}}
                <div class="p-5 flex flex-col flex-1">

                    <h3 class="text-base font-semibold text-foreground leading-snug">
                        {{ $branch->name }}
                    </h3>

                    @if($branch->address)
                    <p class="mt-2 text-sm text-muted-foreground-1 leading-relaxed flex items-start gap-1.5">
                        <span class="iconify tabler--map-pin shrink-0 mt-0.5 text-primary/70 size-3.5" aria-hidden="true"></span>
                        {{ $branch->address }}
                    </p>
                    @endif

                    @if($branch->phone)
                    <p class="mt-1.5 text-sm text-muted-foreground-1 flex items-center gap-1.5">
                        <span class="iconify tabler--phone shrink-0 text-primary/70 size-3.5" aria-hidden="true"></span>
                        {{ $branch->phone }}
                    </p>
                    @endif

                    @if($branch->schedule)
                    <p class="mt-1.5 text-sm text-muted-foreground-1 flex items-center gap-1.5">
                        <span class="iconify tabler--clock shrink-0 text-primary/70 size-3.5" aria-hidden="true"></span>
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
                                <span class="iconify tabler--{{ $bm['icon'] }} {{ $bm['color'] }} size-3" aria-hidden="true"></span>
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
