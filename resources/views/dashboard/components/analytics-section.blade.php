        <!-- Tab: Analytics -->
        <div id="tab-analytics" class="tab-content">

            {{-- ── KPI Cards — compact single grid ──────────────────────── --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 mb-4">
                @php
                    $kpis = [
                        ['id' => 'visitors-today',   'label' => 'Visitantes hoy',    'icon' => 'tabler--eye',             'color' => 'primary', 'badge' => 'Hoy'],
                        ['id' => 'visitors-week',    'label' => 'Visitantes 7d',     'icon' => 'tabler--users',           'color' => 'info',    'badge' => '7d'],
                        ['id' => 'whatsapp-clicks',  'label' => 'WhatsApp',          'icon' => 'tabler--brand-whatsapp',  'color' => 'success', 'badge' => '7d'],
                        ['id' => 'qr-scans',         'label' => 'Escaneos QR',       'icon' => 'tabler--qrcode',          'color' => 'warning', 'badge' => '7d'],
                        ['id' => 'call-clicks',      'label' => 'Llamadas',          'icon' => 'tabler--phone',           'color' => 'error',   'badge' => '7d'],
                        ['id' => 'currency-toggles',  'label' => 'Cambios moneda',   'icon' => 'tabler--currency-dollar', 'color' => 'secondary','badge' => '7d'],
                        ['id' => 'avg-time',          'label' => 'Tiempo prom. (s)', 'icon' => 'tabler--clock',           'color' => 'accent',  'badge' => '7d'],
                    ];
                @endphp
                @foreach($kpis as $kpi)
                <div class="rounded-lg border border-base-content/10 bg-base-100 p-3">
                    <div class="flex items-center justify-between mb-2">
                        <div class="size-8 rounded-field bg-{{ $kpi['color'] }}/10 text-{{ $kpi['color'] }} flex items-center justify-center">
                            <span class="iconify {{ $kpi['icon'] }} size-4"></span>
                        </div>
                        <span class="badge badge-soft badge-xs badge-{{ $kpi['color'] }}">{{ $kpi['badge'] }}</span>
                    </div>
                    <div id="{{ $kpi['id'] }}" class="text-xl font-bold text-base-content leading-none">-</div>
                    <div class="text-[11px] text-base-content/50 mt-1">{{ $kpi['label'] }}</div>
                </div>
                @endforeach
            </div>

            {{-- ── Gráfico + Distribución side-by-side ──────────────────── --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
                <div class="lg:col-span-2 card bg-base-100 shadow-sm border border-base-content/10">
                    <div class="card-header flex items-center justify-between">
                        <h4 class="card-title text-base">Visitantes — últimos 7 días</h4>
                        <button onclick="loadAnalytics()" class="btn btn-sm btn-ghost gap-2">
                            <span class="iconify tabler--refresh size-4"></span>
                            Actualizar
                        </button>
                    </div>
                    <div class="card-body pt-2">
                        <canvas id="analytics-chart" class="max-h-[250px]"></canvas>
                    </div>
                </div>

                <div class="card bg-base-100 shadow-sm border border-base-content/10">
                    <div class="card-header">
                        <h4 class="card-title text-base">Distribución</h4>
                    </div>
                    <div class="card-body pt-0 flex flex-col items-center">
                        <div id="analytics-donut-chart"></div>
                        <div class="flex flex-col gap-2 w-full mt-2">
                            <div class="flex items-center justify-between text-xs">
                                <span class="flex items-center gap-1.5">
                                    <span class="inline-block size-2.5 rounded-full bg-primary"></span>
                                    Visitas
                                </span>
                                <span class="font-semibold text-base-content">—</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="flex items-center gap-1.5">
                                    <span class="inline-block size-2.5 rounded-full bg-success"></span>
                                    WhatsApp
                                </span>
                                <span class="font-semibold text-base-content">—</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="flex items-center gap-1.5">
                                    <span class="inline-block size-2.5 rounded-full bg-warning"></span>
                                    QR
                                </span>
                                <span class="font-semibold text-base-content">—</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Herramientas de negocio ───────────────────────────────── --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                {{-- Tasa del Dólar --}}
                <div class="card bg-base-100 shadow-sm border border-base-content/10">
                    <div class="card-header">
                        <div class="flex items-center gap-2">
                            <span class="iconify tabler--currency-dollar size-5 text-success"></span>
                            <h4 class="card-title text-base">Tasa del Dólar</h4>
                        </div>
                    </div>
                    <div class="card-body pt-2">
                        <div class="bg-base-200 rounded-box p-4 text-center mb-4">
                            <div class="text-3xl font-bold text-primary leading-none">
                                Bs. <span id="dollar-rate-value">{{ $dollarRate }}</span>
                            </div>
                            <div class="text-xs text-base-content/50 mt-1">por 1 USD — Tasa BCV</div>
                        </div>
                        <button onclick="updateDollarRate()" class="btn btn-primary btn-block btn-sm gap-2">
                            <span class="iconify tabler--refresh size-4"></span>
                            Actualizar tasa
                        </button>
                    </div>
                </div>

                {{-- Estado del negocio --}}
                <div class="card bg-base-100 shadow-sm border border-base-content/10">
                    <div class="card-header">
                        <div class="flex items-center gap-2">
                            <span class="iconify tabler--building-store size-5 text-primary"></span>
                            <h4 class="card-title text-base">Estado del Negocio</h4>
                        </div>
                    </div>
                    <div class="card-body pt-2">
                        <div class="bg-base-200 rounded-box p-4 flex flex-col items-center gap-3">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" class="switch switch-success"
                                       id="status-toggle-large"
                                       {{ $tenant->is_open ? 'checked' : '' }}
                                       onchange="toggleBusinessStatusLarge()">
                                <label for="status-toggle-large" class="label-text text-base font-medium cursor-pointer">
                                    <span id="business-status-label">
                                        {{ $tenant->is_open ? 'Abierto' : 'Cerrado' }}
                                    </span>
                                </label>
                            </div>
                            <div id="business-status-badge">
                                @if($tenant->is_open)
                                    <span class="badge badge-success badge-soft">🟢 Tu sitio está recibiendo clientes</span>
                                @else
                                    <span class="badge badge-error badge-soft">🔴 Tu sitio está pausado</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

