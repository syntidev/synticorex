        <!-- Tab: Configuración -->
        <div id="tab-config" class="tab-content">
            <div class="p-6">
            {{-- ── Section header ──────────────────────────────── --}}
            <div class="mb-6 flex items-center gap-3">
                <div class="size-10 rounded-xl bg-primary/10 flex items-center justify-center">
                    <span class="iconify tabler--settings size-6 text-primary" aria-hidden="true"></span>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-foreground" style="font-family:'Plus Jakarta Sans',sans-serif">Configuración</h2>
                    <p class="text-xs text-muted-foreground-1">Moneda, métodos de pago, seguridad y tu plan</p>
                </div>
            </div>

@php
    // $allPayMeta y $allCurrencyMeta vienen del Controller (fuente única de verdad)
    $payMethods      = $customization->payment_methods ?? [];
    $globalEnabled   = $payMethods['global'] ?? [];
    $currencyEnabled = $payMethods['currency'] ?? [];
    $branchPayMeta   = $payMethods['branches'] ?? [];
    $activeBranchList = $branches->where('is_active', true);
    $savedMode = $tenant->settings['engine_settings']['currency']['display']['saved_display_mode'] ?? 'reference_only';
    $legalLinksEnabled = (bool) data_get($customization->content_blocks ?? [], 'legal_links.enabled', false);
@endphp

            {{-- ══ ROW 1: Tasas BCV (compact bar) ═══════════════ --}}
            <div class="flex items-center justify-between flex-wrap gap-3 p-3 rounded-xl bg-surface border border-border shadow-sm mb-5">
                <div class="flex items-center gap-4 flex-wrap min-w-0">
                    <div class="flex items-center gap-3">
                        <div class="size-8 rounded-lg bg-success/10 flex items-center justify-center">
                            <span class="iconify tabler--currency-dollar size-4.5 text-success" aria-hidden="true"></span>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-muted-foreground-1">USD</span>
                            <span class="text-sm font-bold text-primary ml-1">Bs. <span id="dollar-rate-value">{{ $dollarRate }}</span></span>
                        </div>
                    </div>
                    <div class="w-px h-6 bg-border"></div>
                    <div class="flex items-center gap-3">
                        <div class="size-8 rounded-lg bg-info/10 flex items-center justify-center">
                            <span class="iconify tabler--currency-euro size-4.5 text-info" aria-hidden="true"></span>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-muted-foreground-1">EUR</span>
                            <span class="text-sm font-bold text-info ml-1">Bs. <span id="euro-rate-value">{{ $euroRate ?? '—' }}</span></span>
                        </div>
                    </div>
                </div>
                <button onclick="updateDollarRate()" class="inline-flex items-center text-sm py-1.5 px-3 rounded-lg font-medium transition-colors bg-blue-100 text-blue-700 hover:bg-blue-200 gap-1.5">
                    <span class="iconify tabler--refresh size-4"></span>
                    Actualizar
                </button>
            </div>

            {{-- ══ ROW 2: Métodos de Pago (8/12) + Moneda (4/12) ═══ --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 mb-5">

                {{-- LEFT: Métodos de Pago (8 cols) --}}
                <div class="lg:col-span-8">
                    <div class="bg-surface rounded-xl shadow-sm border border-border h-full">
                        <div class="px-5 pt-5 pb-3">
                            <div class="flex items-center gap-3">
                                <div class="size-9 rounded-lg bg-primary/10 flex items-center justify-center">
                                    <span class="iconify tabler--credit-card size-5 text-primary" aria-hidden="true"></span>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-foreground">Métodos de Pago</h3>
                                    <p class="text-xs text-muted-foreground-1">
                                        Activa los que aceptas
                                        @if($plan->id === 1)
                                            <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700 ms-1">fijos</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="px-5 pb-5 pt-1">
                            @if($plan->id === 1)
                            <div class="grid grid-cols-2 gap-2 mb-3">
                                @foreach(['pagoMovil', 'cash'] as $mkey)
                                @php $m = $allPayMeta[$mkey]; @endphp
                                <div class="flex items-center gap-2 p-2.5 rounded-lg bg-success/10 border border-success/20">
                                    <span class="iconify {{ $m['icon'] }} size-4 text-success shrink-0" aria-hidden="true"></span>
                                    <span class="text-xs font-semibold text-success flex-1">{{ $m['label'] }}</span>
                                    <span class="iconify tabler--check size-3.5 text-success shrink-0" aria-hidden="true"></span>
                                </div>
                                @endforeach
                            </div>
                            <div class="flex p-3 rounded-lg border gap-3 bg-blue-50 border-blue-200 text-blue-800">
                                <span class="iconify tabler--lock size-3.5" aria-hidden="true"></span>
                                <span class="text-xs">Más métodos disponibles desde Plan CRECIMIENTO.</span>
                            </div>
                            @else
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-1.5 mb-4">
                                @foreach($allPayMeta as $mkey => $m)
                                @php $checked = in_array($mkey, $globalEnabled); @endphp
                                <label id="pay-label-{{ $mkey }}"
                                       class="flex items-center gap-1.5 cursor-pointer px-2.5 py-2 rounded-lg border transition-all select-none
                                              {{ $checked ? 'bg-primary/15 border-primary/40' : 'bg-muted/40 border-border hover:border-border' }}">
                                    <input type="checkbox" id="pay-check-{{ $mkey }}" value="{{ $mkey }}" {{ $checked ? 'checked' : '' }} class="hidden"
                                           onchange="togglePayMethod('{{ $mkey }}')">
                                    <span class="iconify {{ $m['icon'] }} size-4 shrink-0 {{ $checked ? 'text-primary' : 'text-muted-foreground-1' }}" aria-hidden="true"></span>
                                    <span class="text-[11px] font-medium flex-1 truncate {{ $checked ? 'text-primary' : 'text-foreground' }}">{{ $m['label'] }}</span>
                                    <span id="pay-check-icon-{{ $mkey }}"
                                          class="iconify tabler--check size-3 shrink-0 transition-opacity {{ $checked ? 'text-primary opacity-100' : 'opacity-0' }}"
                                          aria-hidden="true"></span>
                                </label>
                                @endforeach
                            </div>

                            {{-- Denominaciones --}}
                            <p class="text-[10px] font-bold text-muted-foreground-1 uppercase tracking-wider mb-2">Divisas aceptadas</p>
                            <div class="flex gap-2 mb-4">
                                @foreach($allCurrencyMeta as $ckey => $c)
                                @php $checked = in_array($ckey, $currencyEnabled); @endphp
                                <label id="curr-label-{{ $ckey }}"
                                       class="flex items-center gap-2 cursor-pointer flex-1 p-2.5 rounded-lg border transition-all select-none
                                              {{ $checked ? 'bg-primary/15 border-primary/40' : 'bg-muted/40 border-border hover:border-border' }}">
                                    <input type="checkbox" id="curr-check-{{ $ckey }}" value="{{ $ckey }}" {{ $checked ? 'checked' : '' }} class="hidden"
                                           onchange="toggleCurrency('{{ $ckey }}')">
                                    <span class="iconify {{ $c['icon'] }} size-4.5 shrink-0 {{ $checked ? 'text-primary' : 'text-muted-foreground-1' }}" aria-hidden="true"></span>
                                    <span class="text-xs font-medium flex-1 {{ $checked ? 'text-primary' : 'text-foreground' }}">{{ $c['label'] }}</span>
                                    <span id="curr-check-icon-{{ $ckey }}"
                                          class="iconify tabler--check size-3.5 shrink-0 transition-opacity {{ $checked ? 'text-primary opacity-100' : 'opacity-0' }}"
                                          aria-hidden="true"></span>
                                </label>
                                @endforeach
                            </div>

                            {{-- Vista previa --}}
                            <div class="rounded-lg bg-muted border border-border p-2.5 mb-4">
                                <p class="text-[10px] font-semibold text-muted-foreground-1 uppercase tracking-wide mb-1.5">Preview</p>
                                <div id="payment-preview" class="flex flex-wrap justify-center gap-1.5 min-h-6 items-center"></div>
                            </div>

                            @if($plan->id === 3 && $activeBranchList->isNotEmpty())
                            <div class="pt-3 border-t border-border" x-data="{ branchPay: false }">
                                <button type="button" @click="branchPay = !branchPay"
                                        class="flex items-center gap-2 text-xs font-semibold text-muted-foreground-1 hover:text-foreground mb-2 w-full">
                                    <span class="iconify tabler--map-pin size-3.5" aria-hidden="true"></span>
                                    Métodos por Sucursal
                                    <span class="iconify tabler--chevron-down size-3.5 ml-auto transition-transform duration-200"
                                          :class="branchPay && 'rotate-180'" aria-hidden="true"></span>
                                </button>
                                <div x-show="branchPay" x-collapse x-cloak class="space-y-2">
                                    @foreach($activeBranchList as $branch)
                                    @php $bEnabled = $branchPayMeta[(string)$branch->id] ?? []; @endphp
                                    <div class="rounded-lg bg-muted border border-border p-2.5">
                                        <p class="text-xs font-semibold text-foreground mb-2">{{ $branch->name }}</p>
                                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-1">
                                            @foreach($allPayMeta as $mkey => $m)
                                            @php $bchecked = in_array($mkey, $bEnabled); @endphp
                                            <label id="pay-branch-label-{{ $branch->id }}-{{ $mkey }}"
                                                   class="flex items-center gap-1 cursor-pointer px-1.5 py-1 rounded border transition-all select-none text-[11px]
                                                          {{ $bchecked ? 'bg-primary/15 border-primary/40' : 'bg-surface border-border' }}">
                                                <input type="checkbox" id="pay-branch-check-{{ $branch->id }}-{{ $mkey }}" value="{{ $mkey }}" {{ $bchecked ? 'checked' : '' }} class="hidden"
                                                       onchange="toggleBranchPayMethod({{ $branch->id }}, '{{ $mkey }}')">
                                                <span class="iconify {{ $m['icon'] }} size-3 shrink-0 {{ $bchecked ? 'text-primary' : 'text-muted-foreground-1' }}" aria-hidden="true"></span>
                                                <span class="flex-1 truncate {{ $bchecked ? 'text-primary' : 'text-foreground' }}">{{ $m['label'] }}</span>
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

                            <button type="button" onclick="savePaymentMethods()" class="w-full mt-2 py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-primary border border-primary-line text-primary-foreground hover:bg-primary-hover focus:outline-hidden focus:bg-primary-focus disabled:opacity-50 disabled:pointer-events-none">
                                <span class="iconify tabler--device-floppy size-4" aria-hidden="true"></span>
                                Guardar Métodos de Pago
                            </button>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- RIGHT: Moneda y Precios (4 cols) --}}
                <div class="lg:col-span-4">
                    <div class="bg-surface rounded-xl shadow-sm border border-border h-full">
                        <div class="px-5 pt-5 pb-3">
                            <div class="flex items-center gap-3">
                                <div class="size-9 rounded-lg bg-warning/10 flex items-center justify-center">
                                    <span class="iconify tabler--coins size-5 text-warning" aria-hidden="true"></span>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-foreground">Moneda</h3>
                                    <p class="text-xs text-muted-foreground-1">Cómo se muestran los precios</p>
                                </div>
                            </div>
                        </div>
                        <div class="px-5 pb-5 pt-1">
                            {{-- Symbol toggle --}}
                            <div class="flex items-center justify-between p-2.5 rounded-lg bg-muted border border-border mb-4">
                                <span class="text-xs font-medium text-foreground">Símbolo</span>
                                <div class="flex items-center gap-2">
                                    <span id="symbol-ref-label" class="text-xs font-bold text-primary">REF</span>
                                    <input type="checkbox" id="currency-symbol-switch" class="relative w-[35px] h-[20px] bg-gray-200 checked:bg-primary border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 appearance-none focus:ring-primary-focus focus:ring-2 focus:ring-offset-2 before:inline-block before:size-[16px] before:bg-white before:rounded-full before:transform before:translate-x-0 checked:before:translate-x-full before:transition before:ease-in-out before:duration-200 before:shadow-sm">
                                    <span id="symbol-dollar-label" class="text-xs font-bold text-muted-foreground-1">$</span>
                                </div>
                            </div>

                            {{-- Display mode --}}
                            <p class="text-[10px] font-bold text-muted-foreground-1 uppercase tracking-wider mb-2">Modo de precio</p>
                            <div class="space-y-1.5 mb-4">
                                @foreach([
                                    'reference_only' => ['Solo Referencia (REF/$)', 'tabler--tag'],
                                    'bolivares_only' => ['Solo Bolívares (Bs.)', 'tabler--bold'],
                                    'both_toggle'    => ['Ambos con toggle (REF/Bs.)', 'tabler--switch-horizontal'],
                                    'euro_toggle'    => ['Toggle con Euro (€/Bs.)', 'tabler--currency-euro'],
                                    'hidden'         => ['Ocultar → "Más Info"', 'tabler--eye-off'],
                                ] as $val => [$label, $icon])
                                <label class="flex items-center gap-2 cursor-pointer px-2.5 py-2 rounded-lg border transition-all
                                              {{ $savedMode === $val ? 'bg-primary/10 border-primary/30' : 'border-border hover:border-border' }}">
                                    <input type="radio" name="display_mode" value="{{ $val }}"
                                           {{ $savedMode === $val ? 'checked' : '' }}
                                           class="size-3.5 rounded-full border border-gray-300 text-primary checked:bg-primary checked:border-primary focus:ring-primary-focus">
                                    <span class="iconify {{ $icon }} size-3.5 {{ $savedMode === $val ? 'text-primary' : 'text-muted-foreground-1' }}" aria-hidden="true"></span>
                                    <span class="text-xs text-foreground flex-1">{{ $label }}</span>
                                </label>
                                @endforeach
                            </div>

                            <button type="button" onclick="saveCurrencyConfig()" class="w-full py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-primary border border-primary-line text-primary-foreground hover:bg-primary-hover focus:outline-hidden focus:bg-primary-focus disabled:opacity-50 disabled:pointer-events-none">
                                <span class="iconify tabler--device-floppy size-4" aria-hidden="true"></span>
                                Guardar Moneda
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            @if(in_array($blueprint, ['food', 'studio', 'catalog'], true))
            <div class="mb-5">
                <div class="bg-surface rounded-xl shadow-sm border border-border">
                    <div class="px-5 pt-5 pb-3">
                        <div class="flex items-center gap-3">
                            <div class="size-9 rounded-lg bg-primary/10 flex items-center justify-center">
                                <span class="iconify tabler--shield-check size-5 text-primary" aria-hidden="true"></span>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-foreground">Legal y Privacidad</h3>
                                <p class="text-xs text-muted-foreground-1">Activa los enlaces legales visibles en el footer público</p>
                            </div>
                        </div>
                    </div>
                    <div class="px-5 pb-5 pt-1">
                        <div class="flex items-center justify-between p-3 rounded-lg bg-muted border border-border gap-3">
                            <div>
                                <p class="text-sm font-semibold text-foreground">Mostrar Privacidad y Términos</p>
                                <p class="text-[11px] text-muted-foreground-1">Landing {{ $blueprint === 'food' ? 'Food' : ($blueprint === 'catalog' ? 'Cat' : 'Studio') }}. El cambio se guarda automáticamente.</p>
                            </div>
                            <label class="inline-flex items-center cursor-pointer shrink-0">
                                <input type="checkbox" id="legal-links-enabled"
                                       onchange="saveLegalLinksConfig(this)"
                                       class="relative w-[35px] h-[20px] bg-gray-200 checked:bg-primary border-2 border-transparent rounded-full transition-colors ease-in-out duration-200 appearance-none focus:ring-primary-focus focus:ring-2 focus:ring-offset-2 before:inline-block before:size-[16px] before:bg-white before:rounded-full before:transform before:translate-x-0 checked:before:translate-x-full before:transition before:ease-in-out before:duration-200 before:shadow-sm cursor-pointer"
                                       {{ ($plan->id > 1 && $legalLinksEnabled) ? 'checked' : '' }}
                                       {{ $plan->id === 1 ? 'disabled' : '' }}>
                            </label>
                        </div>
                        @if($plan->id === 1)
                        <p class="text-[11px] text-muted-foreground-1 mt-1.5">Disponible desde Plan CRECIMIENTO.</p>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            {{-- ══ ROW 3: PIN (6/12) + Plan Info (6/12) ═══════ --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

                {{-- LEFT: Cambiar PIN --}}
                <div class="bg-surface rounded-xl shadow-sm border border-border">
                    <div class="px-5 pt-5 pb-3">
                        <div class="flex items-center gap-3">
                            <div class="size-9 rounded-lg bg-error/10 flex items-center justify-center">
                                <span class="iconify tabler--shield-lock size-5 text-error" aria-hidden="true"></span>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-foreground">PIN de Acceso</h3>
                                <p class="text-xs text-muted-foreground-1">Protege tu dashboard</p>
                            </div>
                        </div>
                    </div>
                    <div class="px-5 pb-5 pt-1">
                        <form id="pin-form" class="space-y-3">
                            <div class="grid grid-cols-3 gap-2">
                                <div class="form-control">
                                    <label class="inline-block text-sm font-medium text-foreground mb-1">Actual</label>
                                    <input type="password" id="current-pin" maxlength="4" pattern="[0-9]{4}" required
                                           class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none text-center tracking-[.3em]" placeholder="••••">
                                </div>
                                <div class="form-control">
                                    <label class="inline-block text-sm font-medium text-foreground mb-1">Nuevo</label>
                                    <input type="password" id="new-pin" maxlength="4" pattern="[0-9]{4}" required
                                           class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none text-center tracking-[.3em]" placeholder="••••">
                                </div>
                                <div class="form-control">
                                    <label class="inline-block text-sm font-medium text-foreground mb-1">Confirmar</label>
                                    <input type="password" id="confirm-pin" maxlength="4" pattern="[0-9]{4}" required
                                           class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none text-center tracking-[.3em]" placeholder="••••">
                                </div>
                            </div>
                            <button type="button" onclick="updatePin()" class="w-full py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-primary border border-primary-line text-primary-foreground hover:bg-primary-hover focus:outline-hidden focus:bg-primary-focus disabled:opacity-50 disabled:pointer-events-none">
                                <span class="iconify tabler--device-floppy size-4" aria-hidden="true"></span>
                                Guardar PIN
                            </button>
                        </form>
                    </div>
                </div>

                {{-- RIGHT: Plan Info --}}
                <div class="bg-surface rounded-xl shadow-sm border border-border">
                    <div class="px-5 pt-5 pb-3 flex items-center justify-between gap-2 flex-wrap">
                        <div class="flex items-center gap-3">
                            <div class="size-9 rounded-lg bg-info/10 flex items-center justify-center">
                                <span class="iconify tabler--crown size-5 text-info" aria-hidden="true"></span>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-foreground">Tu Plan</h3>
                                <p class="text-xs text-muted-foreground-1">Límites y renovación</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center py-0.5 px-2 rounded-full text-sm font-medium {{ $plan->id === 1 ? 'bg-yellow-100 text-yellow-700' : ($plan->id === 2 ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700') }}">
                            {{ $plan->name }}
                        </span>
                    </div>
                    <div class="p-0">
                        <div class="divide-y divide-border">
                            <div class="flex items-center justify-between px-5 py-2.5">
                                <span class="text-xs text-muted-foreground-1">Plan</span>
                                <span class="text-xs font-semibold text-foreground">{{ $plan->name }}</span>
                            </div>
                            <div class="flex items-center justify-between px-5 py-2.5">
                                <span class="text-xs text-muted-foreground-1">Productos</span>
                                <span class="text-xs font-semibold text-foreground">{{ $products->count() }} / {{ $plan->products_limit }}</span>
                            </div>
                            <div class="flex items-center justify-between px-5 py-2.5">
                                <span class="text-xs text-muted-foreground-1">Servicios</span>
                                <span class="text-xs font-semibold text-foreground">{{ $services->count() }} / {{ $plan->services_limit }}</span>
                            </div>
                            <div class="flex items-center justify-between px-5 py-2.5">
                                <span class="text-xs text-muted-foreground-1">Miembro desde</span>
                                <span class="text-xs font-semibold text-foreground">{{ $tenant->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between px-5 py-2.5">
                                <span class="text-xs text-muted-foreground-1">Renovación</span>
                                @if($tenant->subscription_ends_at)
                                    <span class="text-xs font-semibold {{ $isFrozen ? 'text-red-600' : ($isExpiringSoon ? 'text-yellow-600' : 'text-foreground') }}">
                                        {{ $tenant->subscription_ends_at->format('d/m/Y') }}
                                    </span>
                                @else
                                    <span class="text-xs font-semibold text-foreground">Sin fecha</span>
                                @endif
                            </div>
                        </div>
                        <div class="px-5 pb-4 pt-2">
                            <button onclick="document.querySelector('[data-tab=billing]').click()" type="button"
                               class="w-full inline-flex items-center justify-center text-sm py-1.5 px-3 rounded-lg font-medium transition-colors bg-blue-100 text-blue-700 hover:bg-blue-200 gap-2 cursor-pointer">
                                <span class="iconify tabler--receipt size-4" aria-hidden="true"></span>
                                Ir a Facturación
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            </div>{{-- /p-6 --}}
        </div>{{-- /tab-config --}}

