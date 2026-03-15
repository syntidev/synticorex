<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Invoice;
use Filament\Widgets\ChartWidget;

class RevenueLineChart extends ChartWidget
{
    protected int|string|array $columnSpan = 2;
    protected ?string $pollingInterval = null;

    public function getHeading(): string
    {
        return 'Ingresos — Últimos 6 meses';
    }

    protected function getData(): array
    {
        try {
            $meses = collect(range(5, 0))->map(function (int $i) {
                $fecha = now()->subMonths($i);
                return [
                    'label' => $fecha->format('M'),
                    'total' => (float) Invoice::where('status', 'paid')
                        ->whereYear('reviewed_at', $fecha->year)
                        ->whereMonth('reviewed_at', $fecha->month)
                        ->sum('amount_usd'),
                ];
            });
        } catch (\Throwable) {
            $meses = collect(range(5, 0))->map(fn (int $i) => [
                'label' => now()->subMonths($i)->format('M'),
                'total' => 0,
            ]);
        }

        return [
            'datasets' => [[
                'label' => 'Ingresos USD',
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
