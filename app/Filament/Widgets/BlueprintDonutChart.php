<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

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
        $data = DB::table('tenants')
            ->join('plans', 'plans.id', '=', 'tenants.plan_id')
            ->selectRaw('plans.blueprint, COUNT(*) as total')
            ->groupBy('plans.blueprint')
            ->pluck('total', 'blueprint');

        return [
            'datasets' => [[
                'data' => array_values($data->toArray()),
                'backgroundColor' => ['#4A80E4', '#F59E0B', '#10B981'],
            ]],
            'labels' => array_keys($data->toArray()),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
