        <!-- Tab: Config -->
        <div id="tab-config" class="tab-content">

            {{-- ═══════════════════════════════════════════════════════════
                 Section: Horario de Atención (Business Hours) — Apple-like
            ═══════════════════════════════════════════════════════════ --}}
            @php
                $businessHours = $tenant->business_hours ?? [];
                $daysMap = [
                    'monday'    => 'Lunes',
                    'tuesday'   => 'Martes',
                    'wednesday' => 'Miércoles',
                    'thursday'  => 'Jueves',
                    'friday'    => 'Viernes',
                    'saturday'  => 'Sábado',
                    'sunday'    => 'Domingo',
                ];
                $weekdays = ['monday','tuesday','wednesday','thursday','friday'];
                // Detect if all weekdays share same hours (for simple mode)
                $wdHours = array_filter(array_map(fn($d) => $businessHours[$d] ?? null, $weekdays));
                $allSame = count($wdHours) === 5
                    && count(array_unique(array_column($wdHours, 'open'))) === 1
                    && count(array_unique(array_column($wdHours, 'close'))) === 1;
                $defaultMode = (empty($businessHours) || $allSame) ? 'simple' : 'custom';
                $wdOpen  = $wdHours ? (reset($wdHours)['open'] ?? '08:00') : '08:00';
                $wdClose = $wdHours ? (reset($wdHours)['close'] ?? '18:00') : '18:00';
                $satData = $businessHours['saturday'] ?? null;
                $sunData = $businessHours['sunday'] ?? null;
                $satClosed = is_null($satData) || !empty($satData['closed']);
                $sunClosed = is_null($sunData) || !empty($sunData['closed']);
            @endphp
            <div class="card bg-base-100 shadow-sm border border-base-content/10 mb-4">
                <div class="card-header">
                    <h3 class="card-title flex items-center gap-2">
                        <span class="iconify tabler--clock size-5 text-primary" aria-hidden="true"></span>
                        Horario de Atención
                    </h3>
                    <p class="text-xs text-base-content/50 mt-0.5">Define cuándo tu negocio está abierto para tus clientes</p>
                </div>
                <div class="card-body pt-2">
                    {{-- Mode switcher --}}
                    <div class="flex rounded-lg bg-base-200/60 p-1 mb-4 gap-1">
                        <button type="button" id="hours-mode-simple" onclick="setHoursMode('simple')"
                                class="flex-1 py-2 px-3 rounded-md text-sm font-semibold transition-all {{ $defaultMode === 'simple' ? 'bg-primary text-primary-content shadow-sm' : 'text-base-content/60 hover:text-base-content' }}">
                            Rápido
                        </button>
                        <button type="button" id="hours-mode-custom" onclick="setHoursMode('custom')"
                                class="flex-1 py-2 px-3 rounded-md text-sm font-semibold transition-all {{ $defaultMode === 'custom' ? 'bg-primary text-primary-content shadow-sm' : 'text-base-content/60 hover:text-base-content' }}">
                            Por día
                        </button>
                    </div>

                    {{-- ── SIMPLE MODE ── --}}
                    <div id="hours-simple-mode" class="{{ $defaultMode === 'simple' ? '' : 'hidden' }}">
                        <div class="space-y-3">
                            {{-- Weekdays --}}
                            <div class="p-3 rounded-lg bg-base-200/40 border border-base-content/10">
                                <div class="flex items-center gap-2 mb-2.5">
                                    <span class="text-sm font-semibold text-base-content">Lunes a Viernes</span>
                                    <span class="badge badge-soft badge-primary badge-xs">5 días</span>
                                </div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <input type="time" id="bh-simple-wd-open" class="input input-sm input-bordered w-28" value="{{ $wdOpen }}">
                                    <span class="text-xs text-base-content/40 font-medium">a</span>
                                    <input type="time" id="bh-simple-wd-close" class="input input-sm input-bordered w-28" value="{{ $wdClose }}">
                                </div>
                            </div>

                            {{-- Saturday --}}
                            <div class="p-3 rounded-lg bg-base-200/40 border border-base-content/10">
                                <div class="flex items-center justify-between mb-2.5">
                                    <span class="text-sm font-semibold text-base-content">Sábado</span>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <span class="text-xs text-base-content/50">Cerrado</span>
                                        <input type="checkbox" id="bh-simple-sat-closed" class="toggle toggle-error toggle-sm"
                                               {{ $satClosed ? 'checked' : '' }}
                                               onchange="document.getElementById('bh-simple-sat-times').classList.toggle('hidden', this.checked)">
                                    </label>
                                </div>
                                <div id="bh-simple-sat-times" class="flex items-center gap-2 flex-wrap {{ $satClosed ? 'hidden' : '' }}">
                                    <input type="time" id="bh-simple-sat-open" class="input input-sm input-bordered w-28" value="{{ $satData['open'] ?? '09:00' }}">
                                    <span class="text-xs text-base-content/40 font-medium">a</span>
                                    <input type="time" id="bh-simple-sat-close" class="input input-sm input-bordered w-28" value="{{ $satData['close'] ?? '17:00' }}">
                                </div>
                            </div>

                            {{-- Sunday --}}
                            <div class="p-3 rounded-lg bg-base-200/40 border border-base-content/10">
                                <div class="flex items-center justify-between mb-2.5">
                                    <span class="text-sm font-semibold text-base-content">Domingo</span>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <span class="text-xs text-base-content/50">Cerrado</span>
                                        <input type="checkbox" id="bh-simple-sun-closed" class="toggle toggle-error toggle-sm"
                                               {{ $sunClosed ? 'checked' : '' }}
                                               onchange="document.getElementById('bh-simple-sun-times').classList.toggle('hidden', this.checked)">
                                    </label>
                                </div>
                                <div id="bh-simple-sun-times" class="flex items-center gap-2 flex-wrap {{ $sunClosed ? 'hidden' : '' }}">
                                    <input type="time" id="bh-simple-sun-open" class="input input-sm input-bordered w-28" value="{{ $sunData['open'] ?? '09:00' }}">
                                    <span class="text-xs text-base-content/40 font-medium">a</span>
                                    <input type="time" id="bh-simple-sun-close" class="input input-sm input-bordered w-28" value="{{ $sunData['close'] ?? '14:00' }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── CUSTOM MODE (per day) ── --}}
                    <div id="hours-custom-mode" class="{{ $defaultMode === 'custom' ? '' : 'hidden' }}">
                        <div class="space-y-2">
                            @foreach($daysMap as $dayKey => $dayLabel)
                            @php
                                $dayData = $businessHours[$dayKey] ?? null;
                                $isClosed = is_null($dayData) || !empty($dayData['closed']);
                                $openTime = $dayData['open'] ?? '08:00';
                                $closeTime = $dayData['close'] ?? '18:00';
                            @endphp
                            <div class="flex items-center gap-3 p-3 rounded-lg bg-base-200/40 border border-base-content/10">
                                <span class="text-sm font-semibold text-base-content w-24 shrink-0">{{ $dayLabel }}</span>
                                <div class="flex items-center gap-2 flex-1 flex-wrap">
                                    <input type="time" id="bh-{{ $dayKey }}-open"
                                           class="input input-sm input-bordered w-28"
                                           value="{{ $openTime }}"
                                           {{ $isClosed ? 'disabled' : '' }}>
                                    <span class="text-xs text-base-content/40">a</span>
                                    <input type="time" id="bh-{{ $dayKey }}-close"
                                           class="input input-sm input-bordered w-28"
                                           value="{{ $closeTime }}"
                                           {{ $isClosed ? 'disabled' : '' }}>
                                </div>
                                <label class="label cursor-pointer gap-2 shrink-0">
                                    <span class="label-text text-xs text-base-content/50">Cerrado</span>
                                    <input type="checkbox" id="bh-{{ $dayKey }}-closed"
                                           class="toggle toggle-error toggle-sm"
                                           {{ $isClosed ? 'checked' : '' }}
                                           onchange="toggleDayClosed('{{ $dayKey }}', this.checked)">
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <button type="button" onclick="saveBusinessHours()" class="btn btn-primary w-full gap-2 mt-4">
                        <span class="iconify tabler--device-floppy size-4" aria-hidden="true"></span>
                        Guardar Horario
                    </button>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════════════════
                 Section 0: Social Networks
            ═══════════════════════════════════════════════════════════ --}}
            <div class="card bg-base-100 shadow-sm border border-base-content/10 mb-4">
                @php
                    $rawSocial      = $customization->social_networks ?? [];
                    $socialNetworks = is_array($rawSocial) ? $rawSocial : [];
                    $allNetworksMeta = [
                        'instagram' => ['label' => 'Instagram',  'placeholder' => '@tuusuario',    'icon' => 'tabler--brand-instagram'],
                        'facebook'  => ['label' => 'Facebook',   'placeholder' => '@pagina o URL', 'icon' => 'tabler--brand-facebook'],
                        'tiktok'    => ['label' => 'TikTok',     'placeholder' => '@tuusuario',    'icon' => 'tabler--brand-tiktok'],
                        'linkedin'  => ['label' => 'LinkedIn',   'placeholder' => 'URL o usuario', 'icon' => 'tabler--brand-linkedin'],
                        'youtube'   => ['label' => 'YouTube',    'placeholder' => '@canal o URL',  'icon' => 'tabler--brand-youtube'],
                        'x'         => ['label' => 'Twitter / X','placeholder' => '@tuusuario',    'icon' => 'tabler--brand-x'],
                    ];
                    $plan1Networks  = ['instagram', 'facebook', 'tiktok', 'linkedin'];
                    $availableKeys  = $plan->id === 1 ? $plan1Networks : array_keys($allNetworksMeta);
                    $plan1Selected  = array_key_first(array_intersect_key($socialNetworks, array_flip($plan1Networks))) ?? '';
                    $plan1Handle    = $plan1Selected ? ($socialNetworks[$plan1Selected] ?? '') : '';
                @endphp

                <div class="card-header flex items-center justify-between gap-2 flex-wrap">
                    <h3 class="card-title flex items-center gap-2">
                        <span class="iconify tabler--social size-5 text-primary" aria-hidden="true"></span>
                        Redes Sociales
                    </h3>
                    @if($plan->id === 1)
                        <span class="badge badge-soft badge-warning badge-sm">Plan OPORTUNIDAD — 1 red social</span>
                    @else
                        <span class="badge badge-soft badge-success badge-sm">Plan {{ $plan->name }} — Todas las redes</span>
                    @endif
                </div>
                <div class="card-body">
                    @if($plan->id === 1)
                    {{-- ── Plan 1: radio select + single handle ── --}}
                    <div class="mb-4">
                        <label class="label"><span class="label-text font-medium">Elige tu red social</span></label>
                        <div class="flex flex-wrap gap-2 mb-4" id="social-radio-group">
                            @foreach($plan1Networks as $key)
                            @php $meta = $allNetworksMeta[$key]; @endphp
                            <label id="social-radio-label-{{ $key }}"
                                   onclick="selectSocialNetwork('{{ $key }}')"
                                   class="btn btn-sm gap-1.5 {{ $plan1Selected === $key ? 'btn-primary' : 'btn-ghost border border-base-content/20' }} cursor-pointer">
                                <input type="radio" name="social_plan1_choice" value="{{ $key }}"
                                       {{ $plan1Selected === $key ? 'checked' : '' }} class="hidden">
                                <span class="iconify {{ $meta['icon'] }} size-4" aria-hidden="true"></span>
                                {{ $meta['label'] }}
                            </label>
                            @endforeach
                        </div>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-medium">
                                    Tu usuario o enlace
                                    <span id="social-plan1-network-label" class="text-primary ml-1">
                                        {{ $plan1Selected ? '(' . $allNetworksMeta[$plan1Selected]['label'] . ')' : '' }}
                                    </span>
                                </span>
                            </label>
                            <input type="text" id="social-plan1-handle"
                                   value="{{ $plan1Handle }}"
                                   placeholder="{{ $plan1Selected ? $allNetworksMeta[$plan1Selected]['placeholder'] : 'Selecciona una red primero' }}"
                                   class="input input-bordered w-full"
                                   {{ !$plan1Selected ? 'disabled' : '' }}>
                        </div>
                    </div>

                    @else
                    {{-- ── Plan 2 + 3: grid cubo Rubik ── --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-4" id="social-all-fields">
                        @foreach($availableKeys as $key)
                        @php $meta = $allNetworksMeta[$key]; $current = $socialNetworks[$key] ?? ''; @endphp
                        <div class="flex flex-col items-center gap-2 p-3 rounded-lg border border-base-content/10 bg-base-200/40 transition-all hover:border-primary/30 hover:bg-primary/5">
                            <span class="iconify {{ $meta['icon'] }} size-7 text-primary" aria-hidden="true"></span>
                            <span class="text-[11px] font-semibold text-base-content/70">{{ $meta['label'] }}</span>
                            <input type="text" id="social-{{ $key }}" name="social_{{ $key }}"
                                   value="{{ $current }}"
                                   placeholder="{{ $meta['placeholder'] }}"
                                   maxlength="255"
                                   class="input input-bordered input-sm w-full text-center text-xs">
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <button type="button" onclick="saveSocialNetworks()" class="btn btn-primary w-full gap-2">
                        <span class="iconify tabler--device-floppy size-4" aria-hidden="true"></span>
                        Guardar Redes Sociales
                    </button>
                </div>
            </div>

            {{-- ── Medios de Pago card ─────────────────────────────────── --}}
            <div class="card bg-base-100 shadow-sm border border-base-content/10 mb-4">
                @php
                    $payMethods      = $customization->payment_methods ?? [];
                    $globalEnabled   = $payMethods['global'] ?? [];
                    $currencyEnabled = $payMethods['currency'] ?? [];
                    $branchPayMeta   = $payMethods['branches'] ?? [];
                    $allPayMeta      = [
                        'pagoMovil'  => ['label' => 'Pago Móvil',    'icon' => '📱', 'desc' => 'Transferencia bancaria móvil',  'group' => 'Nacional'],
                        'cash'       => ['label' => 'Efectivo',       'icon' => '💵', 'desc' => 'Pago en efectivo',              'group' => 'Nacional'],
                        'puntoventa' => ['label' => 'Punto de Venta', 'icon' => '💳', 'desc' => 'Terminal POS físico',           'group' => 'Nacional'],
                        'biopago'    => ['label' => 'Biopago',        'icon' => '👁️', 'desc' => 'Pago biométrico',              'group' => 'Nacional'],
                        'cashea'     => ['label' => 'Cashea',         'icon' => '🛒', 'desc' => 'Compra ahora, paga después',    'group' => 'Nacional'],
                        'krece'      => ['label' => 'Krece',          'icon' => '📈', 'desc' => 'Financiamiento tech/electro',   'group' => 'Nacional'],
                        'wepa'       => ['label' => 'Wepa',           'icon' => '📲', 'desc' => 'Cuotas desde el móvil',         'group' => 'Nacional'],
                        'lysto'      => ['label' => 'Lysto',          'icon' => '🗓️', 'desc' => 'Pago en cuotas en comercios',  'group' => 'Nacional'],
                        'chollo'     => ['label' => 'Chollo',         'icon' => '🏷️', 'desc' => 'Compras a cuotas en retail',   'group' => 'Nacional'],
                        'zelle'      => ['label' => 'Zelle',          'icon' => '⚡',  'desc' => 'Transferencia USD',             'group' => 'Divisa'],
                        'zinli'      => ['label' => 'Zinli',          'icon' => '🟣', 'desc' => 'Billetera digital USD',          'group' => 'Divisa'],
                        'paypal'     => ['label' => 'PayPal',         'icon' => '🅿️', 'desc' => 'Pagos internacionales',         'group' => 'Divisa'],
                    ];
                    $allCurrencyMeta = [
                        'usd' => ['label' => 'Dólares (USD)', 'icon' => '💵', 'desc' => 'Acepta billetes USD'],
                        'eur' => ['label' => 'Euros (€)',     'icon' => '💶', 'desc' => 'Acepta billetes EUR'],
                    ];
                    $activeBranchList = $branches->where('is_active', true);
                @endphp

                <div class="card-header flex items-center justify-between gap-2 flex-wrap">
                    <h3 class="card-title flex items-center gap-2">
                        <span class="iconify tabler--credit-card size-5 text-primary" aria-hidden="true"></span>
                        Medios de Pago
                    </h3>
                    @if($plan->id === 1)
                        <span class="badge badge-soft badge-warning badge-sm">Plan OPORTUNIDAD — fijos</span>
                    @elseif($plan->id === 2)
                        <span class="badge badge-soft badge-success badge-sm">Plan CRECIMIENTO</span>
                    @else
                        <span class="badge badge-soft badge-info badge-sm">Plan VISIÓN — global + sucursales</span>
                    @endif
                </div>
                <div class="card-body">
                    @if($plan->id === 1)
                    {{-- Plan 1: Fixed — read-only --}}
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        @foreach(['pagoMovil', 'cash'] as $mkey)
                        @php $m = $allPayMeta[$mkey]; @endphp
                        <div class="flex items-center gap-2 p-3 rounded-box bg-success/10 border border-success/20">
                            <span class="text-xl">{{ $m['icon'] }}</span>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-semibold text-success">{{ $m['label'] }}</div>
                                <div class="text-xs text-success/60">{{ $m['desc'] }}</div>
                            </div>
                            <span class="iconify tabler--check size-4 text-success shrink-0" aria-hidden="true"></span>
                        </div>
                        @endforeach
                    </div>
                    <div class="alert alert-info">
                        <span class="iconify tabler--lock size-4" aria-hidden="true"></span>
                        <span class="text-sm font-medium">Para elegir más métodos de pago, mejora al Plan CRECIMIENTO o superior.</span>
                    </div>

                    @else
                    {{-- Plan 2 + 3: Selectable checkboxes (global) --}}
                    <p class="text-sm font-medium text-base-content mb-3">
                        @if($plan->id === 3) Métodos globales (todos los clientes) @else Métodos visibles en tu landing @endif
                    </p>

                    <p class="text-xs font-semibold uppercase tracking-wide text-base-content/40 mb-2">Nacionales / Divisas</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-1.5 mb-4">
                        @foreach($allPayMeta as $mkey => $m)
                        @php $checked = in_array($mkey, $globalEnabled); @endphp
                        <label id="pay-label-{{ $mkey }}" onclick="togglePayMethod('{{ $mkey }}')"
                               class="flex items-center gap-1.5 cursor-pointer px-2.5 py-2 rounded-lg border transition-all select-none
                                      {{ $checked ? 'bg-primary/15 border-primary/40 text-primary font-semibold' : 'bg-base-200/40 border-base-content/10 text-base-content hover:border-base-content/20' }}">
                            <input type="checkbox" id="pay-check-{{ $mkey }}" value="{{ $mkey }}" {{ $checked ? 'checked' : '' }} class="hidden">
                            <span class="text-sm">{{ $m['icon'] }}</span>
                            <span class="text-xs font-medium flex-1 truncate {{ $checked ? 'text-primary' : 'text-base-content' }}">{{ $m['label'] }}</span>
                            <span id="pay-check-icon-{{ $mkey }}"
                                  class="iconify tabler--check size-3.5 shrink-0 transition-opacity {{ $checked ? 'text-primary opacity-100' : 'opacity-0' }}"
                                  aria-hidden="true"></span>
                        </label>
                        @endforeach
                    </div>

                    {{-- Denominaciones --}}
                    <div class="pt-4 border-t border-base-content/10">
                        <p class="text-sm font-medium text-base-content mb-3">
                            <span class="iconify tabler--cash size-4 inline-block mr-1 text-base-content/60" aria-hidden="true"></span>
                            Denominaciones Aceptadas
                        </p>
                        <div class="grid grid-cols-2 gap-2 mb-4">
                            @foreach($allCurrencyMeta as $ckey => $c)
                            @php $checked = in_array($ckey, $currencyEnabled); @endphp
                            <label id="curr-label-{{ $ckey }}" onclick="toggleCurrency('{{ $ckey }}')"
                                   class="flex items-center gap-2 cursor-pointer p-2.5 rounded-box border transition-all select-none
                                          {{ $checked ? 'bg-primary/20 border-primary/50 text-primary font-semibold' : 'bg-base-200/50 border-base-content/10 text-base-content hover:border-base-content/20' }}">
                                <input type="checkbox" id="curr-check-{{ $ckey }}" value="{{ $ckey }}" {{ $checked ? 'checked' : '' }} class="hidden">
                                <span class="text-base">{{ $c['icon'] }}</span>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs font-medium {{ $checked ? 'text-primary' : 'text-base-content' }}">{{ $c['label'] }}</div>
                                    <div class="text-xs {{ $checked ? 'text-primary/70' : 'text-base-content/40' }}">{{ $c['desc'] }}</div>
                                </div>
                                <span id="curr-check-icon-{{ $ckey }}"
                                      class="iconify tabler--check size-4 shrink-0 transition-opacity {{ $checked ? 'text-primary opacity-100' : 'opacity-0' }}"
                                      aria-hidden="true"></span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Vista previa --}}
                    <div class="rounded-box bg-base-200 border border-base-content/10 p-3 mb-4">
                        <p class="text-xs font-semibold text-base-content/50 uppercase tracking-wide mb-2">Vista previa en la landing:</p>
                        <div id="payment-preview" class="flex flex-wrap justify-center gap-2 min-h-8 items-center"></div>
                    </div>

                    @if($plan->id === 3 && $activeBranchList->isNotEmpty())
                    {{-- Plan 3: per-branch --}}
                    <div class="pt-4 border-t border-base-content/10">
                        <p class="text-sm font-medium text-base-content mb-1">Métodos por Sucursal</p>
                        <p class="text-xs text-base-content/50 mb-3">Personaliza los métodos aceptados en cada sucursal</p>
                        <div class="space-y-3">
                            @foreach($activeBranchList as $branch)
                            @php $bEnabled = $branchPayMeta[(string)$branch->id] ?? []; @endphp
                            <div class="rounded-box bg-base-200/50 border border-base-content/10 p-3">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="iconify tabler--map-pin size-4 text-base-content/50" aria-hidden="true"></span>
                                    <span class="font-semibold text-sm text-base-content">{{ $branch->name }}</span>
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-1.5">
                                    @foreach($allPayMeta as $mkey => $m)
                                    @php $bchecked = in_array($mkey, $bEnabled); @endphp
                                    <label id="pay-branch-label-{{ $branch->id }}-{{ $mkey }}"
                                           onclick="toggleBranchPayMethod({{ $branch->id }}, '{{ $mkey }}')"
                                           class="flex items-center gap-1.5 cursor-pointer px-2 py-1.5 rounded-lg border transition-all select-none
                                                  {{ $bchecked ? 'bg-primary/15 border-primary/40 text-primary font-semibold' : 'bg-base-100 border-base-content/10 text-base-content hover:border-base-content/20' }}">
                                        <input type="checkbox" id="pay-branch-check-{{ $branch->id }}-{{ $mkey }}" value="{{ $mkey }}" {{ $bchecked ? 'checked' : '' }} class="hidden">
                                        <span class="text-sm">{{ $m['icon'] }}</span>
                                        <span class="text-xs {{ $bchecked ? 'text-primary' : 'text-base-content' }} flex-1 truncate">{{ $m['label'] }}</span>
                                        <span id="pay-branch-check-icon-{{ $branch->id }}-{{ $mkey }}"
                                              class="iconify tabler--check size-3.5 shrink-0 transition-opacity {{ $bchecked ? 'text-primary opacity-100' : 'opacity-0' }}"
                                              aria-hidden="true"></span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <button type="button" onclick="savePaymentMethods()" class="btn btn-primary w-full gap-2 mt-4">
                        <span class="iconify tabler--device-floppy size-4" aria-hidden="true"></span>
                        Guardar Medios de Pago y Denominaciones
                    </button>
                    @endif
                </div>
            </div>

            {{-- ── Configuración de Moneda card ──────────────────────────── --}}
            <div class="card bg-base-100 shadow-sm border border-base-content/10 mb-4">
                <div class="card-header">
                    <h3 class="card-title flex items-center gap-2">
                        <span class="iconify tabler--currency-dollar size-5 text-primary" aria-hidden="true"></span>
                        Configuración de Moneda
                    </h3>
                </div>
                <div class="card-body">
                    {{-- Símbolo toggle --}}
                    <div class="flex items-center justify-between mb-6 p-3 rounded-box bg-base-200/50 border border-base-content/10">
                        <span class="font-medium text-sm text-base-content">Símbolo de Precio</span>
                        <div class="flex items-center gap-3">
                            <span id="symbol-ref-label" class="text-sm font-semibold text-primary">REF</span>
                            <input type="checkbox" id="currency-symbol-switch" class="switch switch-primary switch-sm">
                            <span id="symbol-dollar-label" class="text-sm font-semibold text-base-content/40">$</span>
                        </div>
                    </div>

                    {{-- Display Mode --}}
                    <div class="form-control mb-4">
                        <label class="label"><span class="label-text font-medium">Mostrar Precios en</span></label>
                        @php $savedMode = $tenant->settings['engine_settings']['currency']['display']['saved_display_mode'] ?? 'reference_only'; @endphp
                        <div class="flex flex-col gap-2">
                            @foreach([
                                'reference_only' => 'Solo Referencia (REF/$)',
                                'bolivares_only' => 'Solo Bolívares (Bs.)',
                                'both_toggle'   => 'Ambos con toggle público',
                                'hidden'        => 'Ocultar precio → activa "Más Info"',
                            ] as $val => $label)
                            <label class="flex items-center gap-2.5 cursor-pointer">
                                <input type="radio" name="display_mode" value="{{ $val }}"
                                       {{ $savedMode === $val ? 'checked' : '' }}
                                       class="radio radio-primary radio-sm">
                                <span class="text-sm text-base-content">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="alert alert-info mb-4">
                        <span class="iconify tabler--info-circle size-4" aria-hidden="true"></span>
                        <span class="text-sm">Si ocultás el precio, el botón cambia a "Más Info"</span>
                    </div>

                    <button type="button" onclick="saveCurrencyConfig()" class="btn btn-primary w-full gap-2">
                        <span class="iconify tabler--device-floppy size-4" aria-hidden="true"></span>
                        Guardar Configuración de Moneda
                    </button>
                </div>
            </div>

            {{-- ── Cambiar PIN ──────────────────────────────────── --}}
            <div class="card bg-base-100 shadow-sm border border-base-content/10 mb-4">
                <div class="card-header">
                    <h3 class="card-title flex items-center gap-2">
                        <span class="iconify tabler--lock size-5 text-primary" aria-hidden="true"></span>
                        Cambiar PIN de Acceso
                    </h3>
                </div>
                <div class="card-body">
                    <form id="pin-form" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="form-control">
                            <label class="label pb-1"><span class="label-text font-medium text-sm">PIN Actual</span></label>
                            <input type="password" id="current-pin" maxlength="4" pattern="[0-9]{4}" required
                                   class="input input-bordered w-full" placeholder="••••">
                        </div>
                        <div class="form-control">
                            <label class="label pb-1"><span class="label-text font-medium text-sm">PIN Nuevo</span></label>
                            <input type="password" id="new-pin" maxlength="4" pattern="[0-9]{4}" required
                                   class="input input-bordered w-full" placeholder="••••">
                        </div>
                        <div class="form-control">
                            <label class="label pb-1"><span class="label-text font-medium text-sm">Confirmar PIN</span></label>
                            <input type="password" id="confirm-pin" maxlength="4" pattern="[0-9]{4}" required
                                   class="input input-bordered w-full" placeholder="••••">
                        </div>
                    </form>
                    <button type="button" onclick="updatePin()" class="btn btn-primary w-full sm:w-auto gap-2 mt-3">
                        <span class="iconify tabler--device-floppy size-4" aria-hidden="true"></span>
                        Guardar PIN
                    </button>
                </div>
            </div>

            {{-- ── Información del Plan ────────────────────────── --}}
            <div class="card bg-base-100 shadow-sm border border-base-content/10">
                <div class="card-header flex items-center justify-between gap-2 flex-wrap">
                    <h3 class="card-title flex items-center gap-2">
                        <span class="iconify tabler--crown size-5 text-primary" aria-hidden="true"></span>
                        Información del Plan
                    </h3>
                    <span class="badge badge-soft badge-sm {{ $plan->id === 1 ? 'badge-warning' : ($plan->id === 2 ? 'badge-success' : 'badge-info') }}">
                        {{ $plan->name }}
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="divide-y divide-base-content/10">
                        <div class="flex items-center justify-between px-4 py-3">
                            <span class="text-sm text-base-content/60">Plan actual</span>
                            <span class="text-sm font-semibold text-base-content">{{ $plan->name }}</span>
                        </div>
                        <div class="flex items-center justify-between px-4 py-3">
                            <span class="text-sm text-base-content/60">Productos</span>
                            <span class="text-sm font-semibold text-base-content">{{ $products->count() }} / {{ $plan->products_limit }}</span>
                        </div>
                        <div class="flex items-center justify-between px-4 py-3">
                            <span class="text-sm text-base-content/60">Servicios</span>
                            <span class="text-sm font-semibold text-base-content">{{ $services->count() }} / {{ $plan->services_limit }}</span>
                        </div>
                        <div class="flex items-center justify-between px-4 py-3">
                            <span class="text-sm text-base-content/60">Miembro desde</span>
                            <span class="text-sm font-semibold text-base-content">{{ $tenant->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between px-4 py-3">
                            <span class="text-sm text-base-content/60">Renovación</span>
                            <span class="text-sm font-semibold text-base-content">Por definir</span>
                        </div>
                    </div>
                    <div class="px-4 pb-4 pt-2">
                        <a href="https://syntiweb.com/planes" target="_blank" rel="noopener noreferrer"
                           class="btn btn-soft btn-primary btn-sm btn-block gap-2">
                            <span class="iconify tabler--external-link size-4" aria-hidden="true"></span>
                            Ver planes disponibles
                        </a>
                    </div>
                </div>
            </div>

        </div>

