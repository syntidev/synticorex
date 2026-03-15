<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Tenant;
use Filament\Widgets\ChartWidget;

class RevenueLineChart extends ChartWidget
{
    protected ?string $heading = 'Tenants Nuevos — Últimos 6 meses';

    protected int|string|array $columnSpan = 2;

    protected function getData(): array
    {
        $months = collect(range(5, 0))->map(fn (int $i) => now()->subMonths($i));

        $counts = Tenant::selectRaw('MONTH(created_at) as mes, COUNT(*) as total')
            ->whereDate('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes')
            ->toArray();

        $labels = [];
        $values = [];

        foreach ($months as $month) {
            $labels[] = $month->translatedFormat('M');
            $values[] = $counts[(int) $month->format('n')] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Nuevos tenants',
                    'data'            => $values,
                    'fill'            => true,
                    'tension'         => 0.4,
                    'borderColor'     => '#4A80E4',
                    'backgroundColor' => 'rgba(74, 128, 228, 0.1)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
