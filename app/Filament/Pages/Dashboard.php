<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'SYNTIweb — Centro de Control';

    public function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\StatsOverviewWidget::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return 3;
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\LatestTenantsWidget::class,
            \App\Filament\Widgets\BlueprintDonutChart::class,
            \App\Filament\Widgets\RevenueLineChart::class,
            \App\Filament\Widgets\CurrencyRatesWidget::class,
        ];
    }

    public function getColumns(): int
    {
        return 2;
    }
}
