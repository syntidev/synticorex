<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Tenant;
use Filament\Widgets\ChartWidget;

class RevenueLineChart extends ChartWidget
{
    protected int|string|array $columnSpan = 2;
    protected ?string $pollingInterval = null;

    public function getHeading(): string
    {
        return 'Tenants Nuevos — Últimos 6 meses';
    }

    protected function getData(): array
    {
        $meses = collect(range(5, 0))->map(function (int $i) {
            $fecha = now()->subMonths($i);
            return [
                'label' => $fecha->format('M'),
                'total' => Tenant::whereYear('created_at', $fecha->year)
                    ->whereMonth('created_at', $fecha->month)->count(),
            ];
        });

        return [
            'datasets' => [[
                'label' => 'Nuevos tenants',
                'data' => $meses->pluck('total')->toArray(),
                'borderColor' => '#4A80E4',
                'backgroundColor' => 'rgba(74,128,228,0.15)',
                'fill' => true,
                'tension' => 0.4,
            ]],
            'labels' => $meses->pluck('label')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
