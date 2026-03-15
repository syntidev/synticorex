<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'SYNTIweb — Centro de Control';

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\StatsOverviewWidget::class,
            \App\Filament\Widgets\BlueprintDonutChart::class,
            \App\Filament\Widgets\RevenueLineChart::class,
            \App\Filament\Widgets\LatestTenantsWidget::class,
        ];
    }

    public function getColumns(): int
    {
        return 3;
    }
}
