<x-filament-panels::page>
    {{-- Period selector --}}
    <div class="flex items-center gap-2 mb-6">
        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Período:</span>
        @foreach (['7d' => '7 días', '30d' => '30 días', '90d' => '90 días'] as $key => $label)
            <a href="?period={{ $key }}"
               class="cursor-pointer px-3 py-1.5 rounded-lg text-sm font-medium transition
                      {{ $period === $key
                          ? 'bg-primary-600 text-white shadow-sm'
                          : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 ring-1 ring-gray-200 dark:ring-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- KPIs Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        @php
            $kpis = [
                ['label' => 'Pageviews', 'value' => number_format($summary['pageviews']), 'icon' => 'tabler--eye'],
                ['label' => 'Visitantes únicos', 'value' => number_format($summary['unique_visitors']), 'icon' => 'tabler--users'],
                ['label' => 'Clicks WhatsApp', 'value' => number_format($summary['whatsapp_clicks']), 'icon' => 'tabler--brand-whatsapp'],
                ['label' => 'QR Scans', 'value' => number_format($summary['qr_scans']), 'icon' => 'tabler--qrcode'],
                ['label' => 'Top Tenants', 'value' => count($summary['top_tenants']), 'icon' => 'tabler--building-store'],
            ];
        @endphp
        @foreach ($kpis as $kpi)
            <div class="rounded-xl bg-white dark:bg-gray-800 p-4 shadow-sm ring-1 ring-gray-200 dark:ring-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary-50 dark:bg-primary-900/20">
                        <iconify-icon icon="{{ str_replace('tabler--', 'tabler:', $kpi['icon']) }}" width="20" height="20" class="text-primary-600 dark:text-primary-400"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $kpi['label'] }}</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $kpi['value'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Trend chart --}}
    <div class="rounded-xl bg-white dark:bg-gray-800 p-5 shadow-sm ring-1 ring-gray-200 dark:ring-gray-700 mb-6">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Tendencia de visitas</h3>
        <canvas id="trendChart" height="80"></canvas>
    </div>

    {{-- Sources + Devices --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="rounded-xl bg-white dark:bg-gray-800 p-5 shadow-sm ring-1 ring-gray-200 dark:ring-gray-700">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Fuentes de tráfico</h3>
            <canvas id="sourcesChart" height="200"></canvas>
        </div>
        <div class="rounded-xl bg-white dark:bg-gray-800 p-5 shadow-sm ring-1 ring-gray-200 dark:ring-gray-700">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Dispositivos</h3>
            <canvas id="devicesChart" height="200"></canvas>
        </div>
    </div>

    {{-- OS + Peak Hours --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="rounded-xl bg-white dark:bg-gray-800 p-5 shadow-sm ring-1 ring-gray-200 dark:ring-gray-700">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Sistema Operativo</h3>
            <canvas id="osChart" height="200"></canvas>
        </div>
        <div class="rounded-xl bg-white dark:bg-gray-800 p-5 shadow-sm ring-1 ring-gray-200 dark:ring-gray-700">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Horas pico (visitas)</h3>
            <canvas id="peakChart" height="200"></canvas>
        </div>
    </div>

    {{-- Top Tenants Table --}}
    <div class="rounded-xl bg-white dark:bg-gray-800 p-5 shadow-sm ring-1 ring-gray-200 dark:ring-gray-700">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Top Tenants por tráfico</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 dark:text-gray-400 uppercase border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Negocio</th>
                        <th class="py-3 px-4">Subdominio</th>
                        <th class="py-3 px-4 text-right">Pageviews</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($summary['top_tenants'] as $i => $t)
                        <tr class="border-b border-gray-100 dark:border-gray-700/50">
                            <td class="py-3 px-4 font-medium text-gray-900 dark:text-white">{{ $i + 1 }}</td>
                            <td class="py-3 px-4 text-gray-700 dark:text-gray-300">{{ $t['name'] }}</td>
                            <td class="py-3 px-4 font-mono text-gray-500">{{ $t['subdomain'] }}</td>
                            <td class="py-3 px-4 text-right font-semibold text-gray-900 dark:text-white">{{ number_format($t['total']) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-gray-400">Sin datos en este período</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const colors = ['#4A80E4','#F59E0B','#10B981','#EF4444','#8B5CF6','#EC4899','#06B6D4','#84CC16'];

            // Trend
            const trendData = @json($trend);
            const trendLabels = Object.keys(trendData);
            const trendValues = Object.values(trendData);
            new Chart(document.getElementById('trendChart'), {
                type: 'line',
                data: {
                    labels: trendLabels,
                    datasets: [{
                        label: 'Pageviews',
                        data: trendValues,
                        borderColor: '#4A80E4',
                        backgroundColor: 'rgba(74,128,228,0.1)',
                        fill: true,
                        tension: 0.3,
                        pointRadius: 2,
                    }]
                },
                options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
            });

            // Sources donut
            const srcData = @json($trafficSources);
            new Chart(document.getElementById('sourcesChart'), {
                type: 'doughnut',
                data: {
                    labels: srcData.map(s => s.source + ' (' + s.percentage + '%)'),
                    datasets: [{ data: srcData.map(s => s.count), backgroundColor: colors.slice(0, srcData.length) }]
                },
                options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
            });

            // Devices donut
            const devData = @json($deviceBreakdown);
            new Chart(document.getElementById('devicesChart'), {
                type: 'doughnut',
                data: {
                    labels: devData.map(d => d.device + ' (' + d.percentage + '%)'),
                    datasets: [{ data: devData.map(d => d.count), backgroundColor: ['#4A80E4','#F59E0B','#10B981'] }]
                },
                options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
            });

            // OS bars
            const osData = @json($osBreakdown);
            new Chart(document.getElementById('osChart'), {
                type: 'bar',
                data: {
                    labels: osData.map(o => o.os),
                    datasets: [{ label: 'Visitas', data: osData.map(o => o.count), backgroundColor: colors.slice(0, osData.length) }]
                },
                options: { responsive: true, indexAxis: 'y', plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true } } }
            });

            // Peak hours bars
            const peakData = @json($peakHours);
            const hoursLabels = Array.from({length: 24}, (_, i) => i + ':00');
            const hoursValues = new Array(24).fill(0);
            peakData.forEach(p => { hoursValues[p.hour] = p.count; });
            new Chart(document.getElementById('peakChart'), {
                type: 'bar',
                data: {
                    labels: hoursLabels,
                    datasets: [{ label: 'Visitas', data: hoursValues, backgroundColor: '#4A80E4' }]
                },
                options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
            });
        });
    </script>
    @endpush
</x-filament-panels::page>
