{{-- Path: resources/views/landing/partials/branches.blade.php --}}
{{-- Only renders for Plan 3 (VISIÓN) --}}
@php
    if (!$tenant->isVision()) return;

    $activeBranches = isset($tenant) && $tenant->relationLoaded('branches')
        ? $tenant->branches->where('is_active', true)
        : collect();

    // Per-branch payment method meta (for Plan 3)
    $branchPayMethodsMeta = [
        'pagoMovil'  => ['label' => 'Pago Móvil',    'icon' => 'device-mobile',  'color' => 'text-blue-400'],
        'biopago'    => ['label' => 'Biopago',        'icon' => 'fingerprint',    'color' => 'text-green-400'],
        'puntoventa' => ['label' => 'Punto de Venta', 'icon' => 'credit-card',    'color' => 'text-purple-400'],
        'zinli'      => ['label' => 'Zinli',          'icon' => 'wallet',         'color' => 'text-violet-400'],
        'zelle'      => ['label' => 'Zelle',          'icon' => 'bolt',           'color' => 'text-yellow-400'],
        'paypal'     => ['label' => 'PayPal',         'icon' => 'brand-paypal',   'color' => 'text-sky-400'],
    ];
    $branchPayMethodsData = ($customization->payment_methods['branches'] ?? []);
@endphp

@if($activeBranches->isNotEmpty())
<section id="branches" class="py-8 sm:py-16 lg:py-24 bg-base-100">
    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="text-center mb-16">
            <span class="inline-block text-xs font-bold uppercase tracking-[0.3em] text-primary mb-4">Nuestras sedes</span>
            <h2 class="text-3xl md:text-5xl font-black text-base-content tracking-tighter">
                Encuéntranos <span class="text-primary italic">Cerca de Ti</span>
            </h2>
            <div class="w-20 h-1.5 bg-primary mx-auto rounded-full mt-6"></div>
        </div>

        {{-- Branch Cards Grid --}}
        <div class="grid grid-cols-1 {{ $activeBranches->count() === 1 ? 'max-w-md mx-auto' : ($activeBranches->count() === 2 ? 'md:grid-cols-2 max-w-3xl mx-auto' : 'md:grid-cols-3') }} gap-8">
            @foreach($activeBranches as $branch)
            <div class="group bg-base-200/50 backdrop-blur-sm rounded-3xl p-8 border border-base-300/50 hover:shadow-xl hover:shadow-primary/5 transition-all duration-500 hover:-translate-y-1">
                {{-- Map Pin Icon --}}
                <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-primary/20 transition-colors">
                    <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>

                {{-- Branch Name --}}
                <h3 class="text-xl font-bold text-base-content mb-3 group-hover:text-primary transition-colors">
                    {{ $branch->name }}
                </h3>

                {{-- Address --}}
                <p class="text-base-content/60 text-sm leading-relaxed">
                    {{ $branch->address }}
                </p>

                {{-- Per-branch payment methods (Plan 3) --}}
                @php
                    $branchMethods = $branchPayMethodsData[(string)$branch->id] ?? [];
                @endphp
                @if(!empty($branchMethods))
                <div class="mt-5 pt-5 border-t border-base-300/30">
                    <p class="text-xs font-semibold uppercase tracking-wider text-base-content/40 mb-3">Acepta</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($branchMethods as $bMethod)
                        @if(isset($branchPayMethodsMeta[$bMethod]))
                        @php $bm = $branchPayMethodsMeta[$bMethod]; @endphp
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full bg-base-300/50 text-base-content/70">
                            <iconify-icon icon="tabler:{{ $bm['icon'] }}" class="{{ $bm['color'] }}" width="14" height="14"></iconify-icon>
                            {{ $bm['label'] }}
                        </span>
                        @endif
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>

    </div>
</section>
@endif
