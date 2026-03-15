<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Tenant;
use Filament\Widgets\ChartWidget;

class BlueprintDonutChart extends ChartWidget
{
    protected ?string $heading = 'Distribución por Producto';

    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        $data = Tenant::query()
            ->join('plans', 'tenants.plan_id', '=', 'plans.id')
            ->selectRaw('plans.blueprint, COUNT(*) as total')
            ->groupBy('plans.blueprint')
            ->pluck('total', 'blueprint')
            ->toArray();

        $labels = [];
        $values = [];
        $colors = [];

        $colorMap = [
            'studio' => '#4A80E4',
            'food'   => '#F59E0B',
            'cat'    => '#10B981',
        ];

        foreach (['studio', 'food', 'cat'] as $bp) {
            $labels[] = ucfirst($bp);
            $values[] = $data[$bp] ?? 0;
            $colors[] = $colorMap[$bp];
        }

        return [
            'datasets' => [
                [
                    'data'            => $values,
                    'backgroundColor' => $colors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
