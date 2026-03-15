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
        try {
            $activos = Tenant::where('status', 'active')->count();
            $nuevosEsteMes = Tenant::where('created_at', '>=', now()->startOfMonth())->count();
        } catch (\Throwable) {
            $activos = 0;
            $nuevosEsteMes = 0;
        }

        try {
            $mrrActual = Invoice::where('status', 'paid')
                ->whereMonth('reviewed_at', now()->month)
                ->whereYear('reviewed_at', now()->year)
                ->sum('amount_usd');
            $mrrAnterior = Invoice::where('status', 'paid')
                ->whereMonth('reviewed_at', now()->subMonth()->month)
                ->whereYear('reviewed_at', now()->subMonth()->year)
                ->sum('amount_usd');
        } catch (\Throwable) {
            $mrrActual = 0;
            $mrrAnterior = 0;
        }

        try {
            $pagosPendientes = Invoice::where('status', 'pending_review')->count();
        } catch (\Throwable) {
            $pagosPendientes = 0;
        }

        try {
            $ticketsAbiertos = SupportTicket::where('status', 'open')->count();
        } catch (\Throwable) {
            $ticketsAbiertos = 0;
        }

        try {
            $suspendidos = Tenant::where('status', 'suspended')->count();
            $trial = Tenant::where('status', 'trial')->count();
        } catch (\Throwable) {
            $suspendidos = 0;
            $trial = 0;
        }

        return [
            Stat::make('Tenants Activos', (string) $activos)
                ->description($nuevosEsteMes . ' nuevos este mes')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->icon('heroicon-o-building-storefront')
                ->color('success'),
            Stat::make('MRR', '$ ' . number_format((float) $mrrActual, 2))
                ->description('vs $' . number_format((float) $mrrAnterior, 2) . ' mes anterior')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->icon('heroicon-o-banknotes')
                ->color('primary'),
            Stat::make('Pagos Pendientes', (string) $pagosPendientes)
                ->description('requieren revisión')
                ->descriptionIcon('heroicon-m-clock')
                ->icon('heroicon-o-clock')
                ->color($pagosPendientes > 0 ? 'warning' : 'success'),
            Stat::make('Tickets Abiertos', (string) $ticketsAbiertos)
                ->description('soporte abierto')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->icon('heroicon-o-ticket')
                ->color($ticketsAbiertos > 0 ? 'danger' : 'success'),
            Stat::make('Tenants Suspendidos', (string) $suspendidos)
                ->description('por vencimiento')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->icon('heroicon-o-no-symbol')
                ->color($suspendidos > 0 ? 'danger' : 'success'),
            Stat::make('Trial Activos', (string) $trial)
                ->description('en período de prueba')
                ->descriptionIcon('heroicon-m-beaker')
                ->icon('heroicon-o-clock')
                ->color('info'),
            Stat::make('Dominios por vencer', (string) \App\Models\Domain::where('status', 'expiring_soon')->count())
                ->color('warning')
                ->icon('heroicon-o-clock'),
            Stat::make('DNS fallando', (string) \App\Models\Domain::where('dns_status', 'failing')->count())
                ->color('danger')
                ->icon('heroicon-o-exclamation-triangle'),
            Stat::make('En período de gracia', (string) \App\Models\Domain::where('status', 'grace_period')->count())
                ->color('danger')
                ->icon('heroicon-o-shield-exclamation'),
        ];
    }
}
