<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Services\HealthCheckService;
use BackedEnum;
use Filament\Pages\Page;
use UnitEnum;

class SystemHealthPage extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'tabler--heart-rate-monitor';

    protected static ?string $navigationLabel = 'Salud del Sistema';

    protected static UnitEnum|string|null $navigationGroup = 'Sistema';

    protected static ?string $title = 'Salud del Sistema';

    protected string $view = 'filament.pages.system-health';

    public function getViewData(): array
    {
        $results = cache('health_results');

        if ($results === null) {
            $service = new HealthCheckService();
            $results = $service->checkAll();
            cache(['health_results' => $results], 300);
        }

        return [
            'checks'    => $results,
            'timestamp' => cache('health_timestamp', now()->toDateTimeString()),
        ];
    }
}
