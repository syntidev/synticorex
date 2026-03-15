<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Invoice;
use App\Models\SupportTicket;
use App\Models\Tenant;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $activos = Tenant::where('status', 'active')->count();
        $nuevosEsteMes = Tenant::where('created_at', '>=', now()->startOfMonth())->count();

        $mrrActual = Invoice::where('status', 'paid')
            ->whereMonth('reviewed_at', now()->month)
            ->whereYear('reviewed_at', now()->year)
            ->sum('amount_usd');
        $mrrAnterior = Invoice::where('status', 'paid')
            ->whereMonth('reviewed_at', now()->subMonth()->month)
            ->whereYear('reviewed_at', now()->subMonth()->year)
            ->sum('amount_usd');

        $pagosPendientes = Invoice::where('status', 'pending_review')->count();
        $ticketsAbiertos = SupportTicket::where('status', 'open')->count();
        $suspendidos = Tenant::where('status', 'suspended')->count();
        $trial = Tenant::where('status', 'trial')->count();

        return [
            Stat::make('Tenants Activos', (string) $activos)
                ->description($nuevosEsteMes . ' nuevos este mes')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('success'),
            Stat::make('MRR', '$ ' . number_format((float) $mrrActual, 2))
                ->description('vs $' . number_format((float) $mrrAnterior, 2) . ' mes anterior')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('primary'),
            Stat::make('Pagos Pendientes', (string) $pagosPendientes)
                ->description('requieren revisión')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pagosPendientes > 0 ? 'warning' : 'success'),
            Stat::make('Tickets Abiertos', (string) $ticketsAbiertos)
                ->description('soporte abierto')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color($ticketsAbiertos > 0 ? 'danger' : 'success'),
            Stat::make('Tenants Suspendidos', (string) $suspendidos)
                ->description('por vencimiento')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($suspendidos > 0 ? 'danger' : 'success'),
            Stat::make('Trial Activos', (string) $trial)
                ->description('en período de prueba')
                ->descriptionIcon('heroicon-m-beaker')
                ->color('info'),
        ];
    }
}
