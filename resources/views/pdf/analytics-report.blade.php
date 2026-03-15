<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; color: #333; font-size: 14px; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #4A80E4; padding-bottom: 15px; }
        .header h1 { color: #4A80E4; font-size: 22px; margin: 0 0 5px; }
        .header p { color: #666; margin: 0; }
        .section { margin-bottom: 25px; }
        .section h2 { font-size: 16px; color: #4A80E4; border-bottom: 1px solid #e5e7eb; padding-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #f3f4f6; text-align: left; padding: 8px 12px; font-size: 12px; color: #666; text-transform: uppercase; }
        td { padding: 8px 12px; border-bottom: 1px solid #f3f4f6; }
        .kpi-grid { display: table; width: 100%; }
        .kpi-row { display: table-row; }
        .kpi-cell { display: table-cell; width: 33%; text-align: center; padding: 15px; }
        .kpi-value { font-size: 28px; font-weight: bold; color: #4A80E4; }
        .kpi-label { font-size: 12px; color: #666; margin-top: 4px; }
        .trend { font-family: monospace; font-size: 11px; line-height: 1.4; }
        .footer { text-align: center; color: #999; font-size: 11px; margin-top: 30px; border-top: 1px solid #e5e7eb; padding-top: 15px; }
        .change-positive { color: #10b981; }
        .change-negative { color: #ef4444; }
    </style>
</head>
<body>
    <div class="header">
        <h1>SYNTIweb</h1>
        <p>Reporte {{ $period === 'weekly' ? 'semanal' : 'mensual' }} &mdash; {{ $tenant->business_name }}</p>
        <p style="font-size: 12px; color: #999; margin-top: 5px;">{{ now()->format('d/m/Y') }}</p>
    </div>

    <div class="section">
        <h2>Métricas principales</h2>
        <div class="kpi-grid">
            <div class="kpi-row">
                <div class="kpi-cell">
                    <div class="kpi-value">{{ number_format($data['visitors']) }}</div>
                    <div class="kpi-label">Visitantes únicos</div>
                    @if ($data['change'] != 0)
                        <div class="{{ $data['change'] > 0 ? 'change-positive' : 'change-negative' }}">
                            {{ $data['change'] > 0 ? '+' : '' }}{{ $data['change'] }}% vs anterior
                        </div>
                    @endif
                </div>
                <div class="kpi-cell">
                    <div class="kpi-value">{{ number_format($data['whatsapp_clicks']) }}</div>
                    <div class="kpi-label">Clicks WhatsApp</div>
                </div>
                <div class="kpi-cell">
                    <div class="kpi-value">{{ number_format($data['qr_scans']) }}</div>
                    <div class="kpi-label">Escaneos QR</div>
                </div>
            </div>
        </div>
    </div>

    @if (!empty($data['include_growth']))
    <div class="section">
        <h2>Análisis de tráfico</h2>
        <table>
            <tr><th>Métrica</th><th>Valor</th></tr>
            <tr><td>Hora pico</td><td>{{ $data['peak_hour'] ?? 0 }}:00h</td></tr>
            <tr><td>Fuente principal</td><td>{{ $data['top_source'] ?? 'Directo' }}</td></tr>
            @if (!empty($data['include_vision']))
                <tr><td>Dispositivo principal</td><td>{{ $data['top_device'] ?? 'Desktop' }}</td></tr>
                <tr><td>Sistema operativo</td><td>{{ $data['top_os'] ?? 'Otro' }}</td></tr>
            @endif
        </table>
    </div>
    @endif

    @if (!empty($data['trend']))
    <div class="section">
        <h2>Tendencia de visitas ({{ count($data['trend']) }} días)</h2>
        <div class="trend">
            @php
                $maxVal = max(1, max($data['trend']));
                $barWidth = 30;
            @endphp
            @foreach ($data['trend'] as $date => $count)
                @php $bar = str_repeat('█', (int) round($count / $maxVal * $barWidth)); @endphp
                {{ $date }} | {{ str_pad($bar, $barWidth) }} {{ $count }}<br>
            @endforeach
        </div>
    </div>
    @endif

    <div class="footer">
        <p>Generado automáticamente por SYNTIweb &mdash; {{ now()->format('d/m/Y H:i') }}</p>
        <p>{{ $tenant->subdomain }}.synticorex.com</p>
    </div>
</body>
</html>
