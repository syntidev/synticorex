<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Tenant;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestTenantsWidget extends TableWidget
{
    protected static ?string $heading = 'Últimos 10 negocios registrados';

    protected int|string|array $columnSpan = 'full';
    protected ?string $pollingInterval = null;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Tenant::query()
                    ->with('plan')
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('business_name')
                    ->label('Negocio'),
                TextColumn::make('subdomain')
                    ->label('Subdominio'),
                TextColumn::make('plan.name')
                    ->label('Plan')
                    ->placeholder('—'),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'active'    => 'success',
                        'trial'     => 'info',
                        'suspended' => 'danger',
                        'frozen'    => 'warning',
                        default     => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Registrado')
                    ->since(),
            ])
            ->paginated(false)
            ->emptyStateHeading('Sin tenants aún')
            ->emptyStateDescription('Los negocios registrados aparecerán aquí.')
            ->emptyStateIcon('heroicon-o-building-storefront');
    }
}
