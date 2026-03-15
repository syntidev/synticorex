<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Services\PlatformAnalyticsService;
use BackedEnum;
use Filament\Pages\Page;
use UnitEnum;

class PlatformAnalyticsPage extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Analytics Plataforma';

    protected static UnitEnum|string|null $navigationGroup = 'Sistema';

    protected static ?string $title = 'Analytics de la Plataforma';

    protected string $view = 'filament.pages.platform-analytics';

    public function getViewData(): array
    {
        $service = app(PlatformAnalyticsService::class);
        $period = request()->query('period', '30d');

        if (!in_array($period, ['7d', '30d', '90d'], true)) {
            $period = '30d';
        }

        $days = match ($period) {
            '7d' => 7,
            '90d' => 90,
            default => 30,
        };

        return [
            'period' => $period,
            'summary' => $service->getSummary($period),
            'trafficSources' => $service->getTrafficSources(),
            'deviceBreakdown' => $service->getDeviceBreakdown(),
            'osBreakdown' => $service->getOSBreakdown(),
            'peakHours' => $service->getPeakHours(),
            'trend' => $service->getTrend($days),
        ];
    }
}
