<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Widgets\BlueprintDonutChart;
use App\Filament\Widgets\LatestTenantsWidget;
use App\Filament\Widgets\RevenueLineChart;
use App\Filament\Widgets\StatsOverviewWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'SYNTIweb — Centro de Control';

    public function getWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
            BlueprintDonutChart::class,
            RevenueLineChart::class,
            LatestTenantsWidget::class,
        ];
    }

    public function getColumns(): int
    {
        return 3;
    }
}
