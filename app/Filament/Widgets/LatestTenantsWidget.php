<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Tenant;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestTenantsWidget extends TableWidget
{
    protected static ?string $heading = 'Últimos 5 negocios registrados';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Tenant::query()
                    ->with('plan')
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('business_name')
                    ->label('Negocio')
                    ->searchable(),
                TextColumn::make('plan.blueprint')
                    ->label('Producto')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'studio' => 'info',
                        'food'   => 'warning',
                        'cat'    => 'success',
                        default  => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => $state ? ucfirst($state) : '—'),
                TextColumn::make('plan.name')
                    ->label('Plan')
                    ->placeholder('—'),
                TextColumn::make('created_at')
                    ->label('Registrado')
                    ->since(),
            ])
            ->paginated(false);
    }
}
