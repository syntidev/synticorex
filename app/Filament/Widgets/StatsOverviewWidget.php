<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Tenant;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{

    protected function getStats(): array
    {
        $activeCount = Tenant::where('status', 'active')->count();
        $previousMonthActive = Tenant::where('status', 'active')
            ->where('created_at', '<', now()->startOfMonth())
            ->count();

        $mrr = Tenant::where('status', 'active')
            ->whereNotNull('plan_id')
            ->join('plans', 'tenants.plan_id', '=', 'plans.id')
            ->sum('plans.price_usd');

        $newThisMonth = Tenant::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $totalUsers = User::count();

        return [
            Stat::make('Negocios Activos', (string) $activeCount)
                ->description($activeCount - $previousMonthActive >= 0
                    ? '+' . ($activeCount - $previousMonthActive) . ' vs mes anterior'
                    : ($activeCount - $previousMonthActive) . ' vs mes anterior')
                ->descriptionIcon('heroicon-o-building-storefront')
                ->color('success'),

            Stat::make('MRR Estimado', '$' . number_format((float) $mrr, 2))
                ->description('Ingreso recurrente anual')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('primary'),

            Stat::make('Nuevos este mes', (string) $newThisMonth)
                ->description('Registros en ' . now()->translatedFormat('F'))
                ->descriptionIcon('heroicon-o-user-plus')
                ->color('warning'),

            Stat::make('Usuarios registrados', (string) $totalUsers)
                ->description('Total acumulado')
                ->descriptionIcon('heroicon-o-users')
                ->color('info'),
        ];
    }
}
