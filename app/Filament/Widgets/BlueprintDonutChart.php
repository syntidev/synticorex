<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Tenant;
use Filament\Widgets\ChartWidget;

class BlueprintDonutChart extends ChartWidget
{
    protected int|string|array $columnSpan = 1;
    protected ?string $pollingInterval = null;

    public function getHeading(): string
    {
        return 'Distribución por Producto';
    }

    protected function getData(): array
    {
        try {
            $data = Tenant::selectRaw('blueprint, COUNT(*) as total')
                ->groupBy('blueprint')
                ->pluck('total', 'blueprint');
        } catch (\Throwable) {
            $data = collect();
        }

        if ($data->isEmpty()) {
            $data = collect(['Studio' => 0, 'Food' => 0, 'Cat' => 0]);
        }

        return [
            'datasets' => [[
                'data' => array_values($data->toArray()),
                'backgroundColor' => ['#4A80E4', '#F59E0B', '#10B981', '#8B5CF6', '#EF4444'],
            ]],
            'labels' => array_map(fn ($k) => ucfirst((string) $k), array_keys($data->toArray())),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
