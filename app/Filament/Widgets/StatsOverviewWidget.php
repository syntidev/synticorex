<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Tenant;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $activos = Tenant::where('status', 'active')->count();
        $mrr = \DB::table('tenants')
            ->join('plans', 'plans.id', '=', 'tenants.plan_id')
            ->where('tenants.status', 'active')
            ->sum('plans.price_usd');
        $nuevos = Tenant::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)->count();
        $usuarios = \App\Models\User::count();

        return [
            Stat::make('Negocios Activos', (string) $activos)
                ->description('Tenants en estado active')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('success'),
            Stat::make('MRR Estimado', '$' . number_format((float) $mrr, 2))
                ->description('Suma planes activos')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('primary'),
            Stat::make('Nuevos este mes', (string) $nuevos)
                ->description('Registrados en ' . now()->format('F'))
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('warning'),
            Stat::make('Usuarios', (string) $usuarios)
                ->description('Total cuentas registradas')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
        ];
    }
}
