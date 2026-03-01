        <!-- Tab: Configuración -->
        <div id="tab-config" class="tab-content">

            {{-- ── Section header ──────────────────────────────── --}}
            <div class="mb-6 flex items-center gap-3">
                <div class="size-10 rounded-xl bg-primary/10 flex items-center justify-center">
                    <span class="iconify tabler--settings size-6 text-primary" aria-hidden="true"></span>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-base-content" style="font-family:'Plus Jakarta Sans',sans-serif">Configuración</h2>
                    <p class="text-xs text-base-content/50">Moneda, métodos de pago, seguridad y tu plan</p>
                </div>
            </div>

@php
    $payMethods      = $customization->payment_methods ?? [];
    $globalEnabled   = $payMethods['global'] ?? [];
    $currencyEnabled = $payMethods['currency'] ?? [];
    $branchPayMeta   = $payMethods['branches'] ?? [];
    $allPayMeta      = [
        'pagoMovil'  => ['label' => 'Pago Móvil',    'icon' => '📱', 'group' => 'Nacional'],
        'cash'       => ['label' => 'Efectivo',       'icon' => '💵', 'group' => 'Nacional'],
        'puntoventa' => ['label' => 'Punto de Venta', 'icon' => '💳', 'group' => 'Nacional'],
        'biopago'    => ['label' => 'Biopago',        'icon' => '👁️', 'group' => 'Nacional'],
        'cashea'     => ['label' => 'Cashea',         'icon' => '🛒', 'group' => 'Nacional'],
        'krece'      => ['label' => 'Krece',          'icon' => '📈', 'group' => 'Nacional'],
        'wepa'       => ['label' => 'Wepa',           'icon' => '📲', 'group' => 'Nacional'],
        'lysto'      => ['label' => 'Lysto',          'icon' => '🗓️', 'group' => 'Nacional'],
        'chollo'     => ['label' => 'Chollo',         'icon' => '🏷️', 'group' => 'Nacional'],
        'zelle'      => ['label' => 'Zelle',          'icon' => '⚡',  'group' => 'Divisa'],
        'zinli'      => ['label' => 'Zinli',          'icon' => '🟣', 'group' => 'Divisa'],
        'paypal'     => ['label' => 'PayPal',         'icon' => '🅿️', 'group' => 'Divisa'],
    ];
    $allCurrencyMeta = [
        'usd' => ['label' => 'Dólares (USD)', 'icon' => '💵'],
        'eur' => ['label' => 'Euros (€)',     'icon' => '💶'],
    ];
    $activeBranchList = $branches->where('is_active', true);
    $savedMode = $tenant->settings['engine_settings']['currency']['display']['saved_display_mode'] ?? 'reference_only';
@endphp

            {{-- ══ ROW 1: Tasas BCV (compact bar) ═══════════════ --}}
            <div class="flex items-center justify-between p-3 rounded-xl bg-base-100 border border-base-content/8 shadow-sm mb-5">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="size-8 rounded-lg bg-success/10 flex items-center justify-center">
                            <span class="iconify tabler--currency-dollar size-4.5 text-success" aria-hidden="true"></span>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-base-content/60">USD</span>
                            <span class="text-sm font-bold text-primary ml-1">Bs. <span id="dollar-rate-value">{{ $dollarRate }}</span></span>
                        </div>
                    </div>
                    <div class="w-px h-6 bg-base-content/10"></div>
                    <div class="flex items-center gap-3">
                        <div class="size-8 rounded-lg bg-info/10 flex items-center justify-center">
                            <span class="iconify tabler--currency-euro size-4.5 text-info" aria-hidden="true"></span>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-base-content/60">EUR</span>
                            <span class="text-sm font-bold text-info ml-1">Bs. <span id="euro-rate-value">{{ $euroRate ?? '—' }}</span></span>
                        </div>
                    </div>
                </div>
                <button onclick="updateDollarRate()" class="btn btn-sm btn-soft btn-primary gap-1.5">
                    <span class="iconify tabler--refresh size-4"></span>
                    Actualizar
                </button>
            </div>

            {{-- ══ ROW 2: Métodos de Pago (8/12) + Moneda (4/12) ═══ --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 mb-5">

                {{-- LEFT: Métodos de Pago (8 cols) --}}
                <div class="lg:col-span-8">
                    <div class="card bg-base-100 shadow-md border border-base-content/8 card-elevated h-full">
                        <div class="card-header px-5 pt-5 pb-3">
                            <div class="flex items-center gap-3">
                                <div class="size-9 rounded-lg bg-primary/10 flex items-center justify-center">
                                    <span class="iconify tabler--credit-card size-5 text-primary" aria-hidden="true"></span>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-base-content">Métodos de Pago</h3>
                                    <p class="text-xs text-base-content/50">
                                        Activa los que aceptas
                                        @if($plan->id === 1)
                                            <span class="badge badge-soft badge-warning badge-xs ms-1">fijos</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-5 pb-5 pt-1">
                            @if($plan->id === 1)
                            <div class="grid grid-cols-2 gap-2 mb-3">
                                @foreach(['pagoMovil', 'cash'] as $mkey)
                                @php $m = $allPayMeta[$mkey]; @endphp
                                <div class="flex items-center gap-2 p-2.5 rounded-lg bg-success/10 border border-success/20">
                                    <span class="text-base">{{ $m['icon'] }}</span>
                                    <span class="text-xs font-semibold text-success flex-1">{{ $m['label'] }}</span>
                                    <span class="iconify tabler--check size-3.5 text-success shrink-0" aria-hidden="true"></span>
                                </div>
                                @endforeach
                            </div>
                            <div class="alert alert-info alert-sm">
                                <span class="iconify tabler--lock size-3.5" aria-hidden="true"></span>
                                <span class="text-xs">Más métodos disponibles desde Plan CRECIMIENTO.</span>
                            </div>
                            @else
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-1.5 mb-4">
                                @foreach($allPayMeta as $mkey => $m)
                                @php $checked = in_array($mkey, $globalEnabled); @endphp
                                <label id="pay-label-{{ $mkey }}" onclick="togglePayMethod('{{ $mkey }}')"
                                       class="flex items-center gap-1.5 cursor-pointer px-2.5 py-2 rounded-lg border transition-all select-none
                                              {{ $checked ? 'bg-primary/15 border-primary/40' : 'bg-base-200/40 border-base-content/10 hover:border-base-content/20' }}">
                                    <input type="checkbox" id="pay-check-{{ $mkey }}" value="{{ $mkey }}" {{ $checked ? 'checked' : '' }} class="hidden">
                                    <span class="text-sm">{{ $m['icon'] }}</span>
                                    <span class="text-[11px] font-medium flex-1 truncate {{ $checked ? 'text-primary' : 'text-base-content' }}">{{ $m['label'] }}</span>
                                    <span id="pay-check-icon-{{ $mkey }}"
                                          class="iconify tabler--check size-3 shrink-0 transition-opacity {{ $checked ? 'text-primary opacity-100' : 'opacity-0' }}"
                                          aria-hidden="true"></span>
                                </label>
                                @endforeach
                            </div>

                            {{-- Denominaciones --}}
                            <p class="text-[10px] font-bold text-base-content/40 uppercase tracking-wider mb-2">Divisas aceptadas</p>
                            <div class="flex gap-2 mb-4">
                                @foreach($allCurrencyMeta as $ckey => $c)
                                @php $checked = in_array($ckey, $currencyEnabled); @endphp
                                <label id="curr-label-{{ $ckey }}" onclick="toggleCurrency('{{ $ckey }}')"
                                       class="flex items-center gap-2 cursor-pointer flex-1 p-2.5 rounded-lg border transition-all select-none
                                              {{ $checked ? 'bg-primary/15 border-primary/40' : 'bg-base-200/40 border-base-content/10 hover:border-base-content/20' }}">
                                    <input type="checkbox" id="curr-check-{{ $ckey }}" value="{{ $ckey }}" {{ $checked ? 'checked' : '' }} class="hidden">
                                    <span class="text-base">{{ $c['icon'] }}</span>
                                    <span class="text-xs font-medium flex-1 {{ $checked ? 'text-primary' : 'text-base-content' }}">{{ $c['label'] }}</span>
                                    <span id="curr-check-icon-{{ $ckey }}"
                                          class="iconify tabler--check size-3.5 shrink-0 transition-opacity {{ $checked ? 'text-primary opacity-100' : 'opacity-0' }}"
                                          aria-hidden="true"></span>
                                </label>
                                @endforeach
                            </div>

                            {{-- Vista previa --}}
                            <div class="rounded-lg bg-base-200/50 border border-base-content/10 p-2.5 mb-4">
                                <p class="text-[10px] font-semibold text-base-content/40 uppercase tracking-wide mb-1.5">Preview</p>
                                <div id="payment-preview" class="flex flex-wrap justify-center gap-1.5 min-h-6 items-center"></div>
                            </div>

                            @if($plan->id === 3 && $activeBranchList->isNotEmpty())
                            <div class="pt-3 border-t border-base-content/10" x-data="{ branchPay: false }">
                                <button type="button" @click="branchPay = !branchPay"
                                        class="flex items-center gap-2 text-xs font-semibold text-base-content/60 hover:text-base-content mb-2 w-full">
                                    <span class="iconify tabler--map-pin size-3.5" aria-hidden="true"></span>
                                    Métodos por Sucursal
                                    <span class="iconify tabler--chevron-down size-3.5 ml-auto transition-transform duration-200"
                                          :class="branchPay && 'rotate-180'" aria-hidden="true"></span>
                                </button>
                                <div x-show="branchPay" x-collapse x-cloak class="space-y-2">
                                    @foreach($activeBranchList as $branch)
                                    @php $bEnabled = $branchPayMeta[(string)$branch->id] ?? []; @endphp
                                    <div class="rounded-lg bg-base-200/40 border border-base-content/10 p-2.5">
                                        <p class="text-xs font-semibold text-base-content mb-2">{{ $branch->name }}</p>
                                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-1">
                                            @foreach($allPayMeta as $mkey => $m)
                                            @php $bchecked = in_array($mkey, $bEnabled); @endphp
                                            <label id="pay-branch-label-{{ $branch->id }}-{{ $mkey }}"
                                                   onclick="toggleBranchPayMethod({{ $branch->id }}, '{{ $mkey }}')"
                                                   class="flex items-center gap-1 cursor-pointer px-1.5 py-1 rounded border transition-all select-none text-[11px]
                                                          {{ $bchecked ? 'bg-primary/15 border-primary/40' : 'bg-base-100 border-base-content/10' }}">
                                                <input type="checkbox" id="pay-branch-check-{{ $branch->id }}-{{ $mkey }}" value="{{ $mkey }}" {{ $bchecked ? 'checked' : '' }} class="hidden">
                                                <span>{{ $m['icon'] }}</span>
                                                <span class="flex-1 truncate {{ $bchecked ? 'text-primary' : 'text-base-content' }}">{{ $m['label'] }}</span>
                                                <span id="pay-branch-check-icon-{{ $branch->id }}-{{ $mkey }}"
                                                      class="iconify tabler--check size-3 shrink-0 transition-opacity {{ $bchecked ? 'text-primary opacity-100' : 'opacity-0' }}"
                                                      aria-hidden="true"></span>
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <button type="button" onclick="savePaymentMethods()" class="btn btn-primary btn-sm w-full gap-2 mt-2">
                                <span class="iconify tabler--device-floppy size-4" aria-hidden="true"></span>
                                Guardar Métodos de Pago
                            </button>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- RIGHT: Moneda y Precios (4 cols) --}}
                <div class="lg:col-span-4">
                    <div class="card bg-base-100 shadow-md border border-base-content/8 card-elevated h-full">
                        <div class="card-header px-5 pt-5 pb-3">
                            <div class="flex items-center gap-3">
                                <div class="size-9 rounded-lg bg-warning/10 flex items-center justify-center">
                                    <span class="iconify tabler--coins size-5 text-warning" aria-hidden="true"></span>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-base-content">Moneda</h3>
                                    <p class="text-xs text-base-content/50">Cómo se muestran los precios</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-5 pb-5 pt-1">
                            {{-- Symbol toggle --}}
                            <div class="flex items-center justify-between p-2.5 rounded-lg bg-base-200/40 border border-base-content/10 mb-4">
                                <span class="text-xs font-medium text-base-content">Símbolo</span>
                                <div class="flex items-center gap-2">
                                    <span id="symbol-ref-label" class="text-xs font-bold text-primary">REF</span>
                                    <input type="checkbox" id="currency-symbol-switch" class="switch switch-primary switch-xs">
                                    <span id="symbol-dollar-label" class="text-xs font-bold text-base-content/40">$</span>
                                </div>
                            </div>

                            {{-- Display mode --}}
                            <p class="text-[10px] font-bold text-base-content/40 uppercase tracking-wider mb-2">Modo de precio</p>
                            <div class="space-y-1.5 mb-4">
                                @foreach([
                                    'reference_only' => ['Solo Referencia (REF/$)', 'tabler--tag'],
                                    'bolivares_only' => ['Solo Bolívares (Bs.)', 'tabler--bold'],
                                    'both_toggle'    => ['Ambos con toggle (REF/Bs.)', 'tabler--switch-horizontal'],
                                    'euro_toggle'    => ['Toggle con Euro (€/Bs.)', 'tabler--currency-euro'],
                                    'hidden'         => ['Ocultar → "Más Info"', 'tabler--eye-off'],
                                ] as $val => [$label, $icon])
                                <label class="flex items-center gap-2 cursor-pointer px-2.5 py-2 rounded-lg border transition-all
                                              {{ $savedMode === $val ? 'bg-primary/10 border-primary/30' : 'border-base-content/10 hover:border-base-content/20' }}">
                                    <input type="radio" name="display_mode" value="{{ $val }}"
                                           {{ $savedMode === $val ? 'checked' : '' }}
                                           class="radio radio-primary radio-xs">
                                    <span class="iconify {{ $icon }} size-3.5 {{ $savedMode === $val ? 'text-primary' : 'text-base-content/40' }}" aria-hidden="true"></span>
                                    <span class="text-xs text-base-content flex-1">{{ $label }}</span>
                                </label>
                                @endforeach
                            </div>

                            <button type="button" onclick="saveCurrencyConfig()" class="btn btn-primary btn-sm w-full gap-2">
                                <span class="iconify tabler--device-floppy size-4" aria-hidden="true"></span>
                                Guardar Moneda
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══ ROW 3: PIN (6/12) + Plan Info (6/12) ═══════ --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

                {{-- LEFT: Cambiar PIN --}}
                <div class="card bg-base-100 shadow-md border border-base-content/8 card-elevated">
                    <div class="card-header px-5 pt-5 pb-3">
                        <div class="flex items-center gap-3">
                            <div class="size-9 rounded-lg bg-error/10 flex items-center justify-center">
                                <span class="iconify tabler--shield-lock size-5 text-error" aria-hidden="true"></span>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-base-content">PIN de Acceso</h3>
                                <p class="text-xs text-base-content/50">Protege tu dashboard</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-5 pb-5 pt-1">
                        <form id="pin-form" class="space-y-3">
                            <div class="grid grid-cols-3 gap-2">
                                <div class="form-control">
                                    <label class="label pb-1"><span class="label-text font-medium text-xs">Actual</span></label>
                                    <input type="password" id="current-pin" maxlength="4" pattern="[0-9]{4}" required
                                           class="input input-bordered input-sm w-full text-center tracking-[.3em]" placeholder="••••">
                                </div>
                                <div class="form-control">
                                    <label class="label pb-1"><span class="label-text font-medium text-xs">Nuevo</span></label>
                                    <input type="password" id="new-pin" maxlength="4" pattern="[0-9]{4}" required
                                           class="input input-bordered input-sm w-full text-center tracking-[.3em]" placeholder="••••">
                                </div>
                                <div class="form-control">
                                    <label class="label pb-1"><span class="label-text font-medium text-xs">Confirmar</span></label>
                                    <input type="password" id="confirm-pin" maxlength="4" pattern="[0-9]{4}" required
                                           class="input input-bordered input-sm w-full text-center tracking-[.3em]" placeholder="••••">
                                </div>
                            </div>
                            <button type="button" onclick="updatePin()" class="btn btn-primary btn-sm w-full gap-2">
                                <span class="iconify tabler--device-floppy size-4" aria-hidden="true"></span>
                                Guardar PIN
                            </button>
                        </form>
                    </div>
                </div>

                {{-- RIGHT: Plan Info --}}
                <div class="card bg-base-100 shadow-md border border-base-content/8 card-elevated">
                    <div class="card-header px-5 pt-5 pb-3 flex items-center justify-between gap-2 flex-wrap">
                        <div class="flex items-center gap-3">
                            <div class="size-9 rounded-lg bg-info/10 flex items-center justify-center">
                                <span class="iconify tabler--crown size-5 text-info" aria-hidden="true"></span>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-base-content">Tu Plan</h3>
                                <p class="text-xs text-base-content/50">Límites y renovación</p>
                            </div>
                        </div>
                        <span class="badge badge-soft badge-sm {{ $plan->id === 1 ? 'badge-warning' : ($plan->id === 2 ? 'badge-success' : 'badge-info') }}">
                            {{ $plan->name }}
                        </span>
                    </div>
                    <div class="card-body p-0">
                        <div class="divide-y divide-base-content/8">
                            <div class="flex items-center justify-between px-5 py-2.5">
                                <span class="text-xs text-base-content/50">Plan</span>
                                <span class="text-xs font-semibold text-base-content">{{ $plan->name }}</span>
                            </div>
                            <div class="flex items-center justify-between px-5 py-2.5">
                                <span class="text-xs text-base-content/50">Productos</span>
                                <span class="text-xs font-semibold text-base-content">{{ $products->count() }} / {{ $plan->products_limit }}</span>
                            </div>
                            <div class="flex items-center justify-between px-5 py-2.5">
                                <span class="text-xs text-base-content/50">Servicios</span>
                                <span class="text-xs font-semibold text-base-content">{{ $services->count() }} / {{ $plan->services_limit }}</span>
                            </div>
                            <div class="flex items-center justify-between px-5 py-2.5">
                                <span class="text-xs text-base-content/50">Miembro desde</span>
                                <span class="text-xs font-semibold text-base-content">{{ $tenant->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between px-5 py-2.5">
                                <span class="text-xs text-base-content/50">Renovación</span>
                                <span class="text-xs font-semibold text-base-content">Por definir</span>
                            </div>
                        </div>
                        <div class="px-5 pb-4 pt-2">
                            <a href="https://syntiweb.com/planes" target="_blank" rel="noopener noreferrer"
                               class="btn btn-soft btn-primary btn-sm btn-block gap-2">
                                <span class="iconify tabler--external-link size-4" aria-hidden="true"></span>
                                Ver planes disponibles
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- /tab-config --}}

