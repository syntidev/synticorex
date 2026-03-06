        <!-- Tab: Pulso del Negocio (Analytics) -->
        <div id="tab-ventas" class="tab-content">
            <div class="p-6">
            {{-- ── Hero header "Así se mueve tu negocio" ────────────────── --}}
            <div class="mb-5 pb-5 border-b border-border">
                <div class="flex items-center gap-3">
                    <div class="size-10 rounded-xl flex items-center justify-center shrink-0"
                         style="background:linear-gradient(135deg,rgba(77,143,255,.15) 0%,rgba(77,143,255,.05) 100%);border:1px solid rgba(77,143,255,.2)">
                        <span class="iconify tabler--activity size-5" style="color:#4D8FFF"></span>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-foreground leading-tight" style="font-family:'Plus Jakarta Sans',sans-serif">
                            Así se mueve tu negocio
                        </h2>
                        <p class="text-xs text-muted-foreground-1 mt-0.5">Métricas en vivo • últimos 7 días</p>
                    </div>
                    <button onclick="loadAnalytics()" class="inline-flex items-center text-sm py-1.5 px-3 rounded-lg font-medium transition-colors text-foreground hover:bg-muted-hover gap-1.5 ml-auto" title="Actualizar métricas">
                        <span class="iconify tabler--refresh size-4"></span>
                        <span class="hidden sm:inline">Actualizar</span>
                    </button>
                </div>
            </div>

            {{-- ── KPI Cards — compact single grid ──────────────────────── --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 mb-4">
                @php
                    $kpis = [
                        ['id' => 'visitors-today',    'label' => 'Visitantes hoy',    'icon' => 'tabler--eye',              'color' => 'primary',   'badge' => 'Hoy'],
                        ['id' => 'visitors-week',     'label' => 'Visitantes 7d',     'icon' => 'tabler--users',            'color' => 'info',      'badge' => '7d'],
                        ['id' => 'whatsapp-clicks',   'label' => 'WhatsApp',          'icon' => 'tabler--brand-whatsapp',   'color' => 'success',   'badge' => '7d'],
                        ['id' => 'qr-scans',          'label' => 'Escaneos QR',       'icon' => 'tabler--qrcode',           'color' => 'warning',   'badge' => '7d'],
                        ['id' => 'call-clicks',       'label' => 'Llamadas',          'icon' => 'tabler--phone',            'color' => 'error',     'badge' => '7d'],
                        ['id' => 'currency-toggles',  'label' => 'Cambios moneda',    'icon' => 'tabler--currency-dollar',  'color' => 'secondary', 'badge' => '7d'],
                        ['id' => 'avg-time',          'label' => 'Tiempo prom. (s)',  'icon' => 'tabler--clock',            'color' => 'accent',    'badge' => '7d'],
                    ];
                @endphp
                @foreach($kpis as $kpi)
                <div class="rounded-lg border border-border bg-surface p-3">
                    <div class="flex items-center justify-between mb-2">
                        <div class="size-8 rounded-field bg-{{ $kpi['color'] }}/10 text-{{ $kpi['color'] }} flex items-center justify-center">
                            <span class="iconify {{ $kpi['icon'] }} size-4"></span>
                        </div>
                        <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-muted text-muted-foreground-1">{{ $kpi['badge'] }}</span>
                    </div>
                    <div id="{{ $kpi['id'] }}" class="text-xl font-bold text-foreground leading-none">-</div>
                    <div class="text-[11px] text-muted-foreground-1 mt-1">{{ $kpi['label'] }}</div>
                </div>
                @endforeach
            </div>

            {{-- ── Gráfico + Distribución side-by-side ──────────────────── --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">
                <div class="lg:col-span-2 bg-surface rounded-xl shadow-sm border border-border overflow-hidden min-w-0">
                    <div class="flex items-center justify-between p-4">
                        <h4 class="font-semibold text-foreground text-base">Visitantes — últimos 7 días</h4>
                    </div>
                    <div class="pt-2 overflow-hidden">
                        <canvas id="analytics-chart" class="max-h-[250px] w-full"></canvas>
                    </div>
                </div>

                <div class="bg-surface rounded-xl shadow-sm border border-border">
                    <div class="p-4">
                        <h4 class="font-semibold text-foreground text-base">Distribución</h4>
                    </div>
                    <div class="pt-0 flex flex-col items-center">
                        <div id="analytics-donut-chart"></div>
                        <div class="flex flex-col gap-2 w-full mt-2">
                            <div class="flex items-center justify-between text-xs">
                                <span class="flex items-center gap-1.5">
                                    <span class="inline-block size-2.5 rounded-full bg-primary"></span>
                                    Visitas
                                </span>
                                <span class="font-semibold text-foreground">—</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="flex items-center gap-1.5">
                                    <span class="inline-block size-2.5 rounded-full bg-success"></span>
                                    WhatsApp
                                </span>
                                <span class="font-semibold text-foreground">—</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="flex items-center gap-1.5">
                                    <span class="inline-block size-2.5 rounded-full bg-warning"></span>
                                    QR
                                </span>
                                <span class="font-semibold text-foreground">—</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            </div>{{-- /p-6 --}}
        </div>{{-- /tab-ventas --}}
