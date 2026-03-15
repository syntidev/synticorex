<?php

declare(strict_types=1);

namespace App\Filament\Resources\Tenants\Tables;

use App\Models\Tenant;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TenantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('business_name')
                    ->label('Negocio')
                    ->searchable()->sortable()
                    ->description(fn (Tenant $r) => $r->user?->email ?? '—'),
                TextColumn::make('subdomain')
                    ->label('Subdominio')
                    ->searchable()
                    ->fontFamily('mono')
                    ->color('gray'),
                TextColumn::make('plan.name')
                    ->label('Plan')
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        str_contains(strtolower($state), 'visión')     => 'primary',
                        str_contains(strtolower($state), 'crecimiento') => 'info',
                        default                                          => 'success',
                    }),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active'   => 'success',
                        'frozen'   => 'warning',
                        'archived' => 'gray',
                        default    => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active'   => 'Activo',
                        'frozen'   => 'Suspendido',
                        'archived' => 'Archivado',
                        default    => $state,
                    }),
                TextColumn::make('subscription_ends_at')
                    ->label('Vence')
                    ->date('d M Y')
                    ->sortable()
                    ->color(fn (Tenant $r): string =>
                        $r->subscription_ends_at && $r->subscription_ends_at->isPast() ? 'danger' : 'gray'
                    ),
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'active'   => 'Activo',
                        'frozen'   => 'Suspendido',
                        'archived' => 'Archivado',
                    ]),
                SelectFilter::make('plan_id')
                    ->label('Plan')
                    ->relationship('plan', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
                \Filament\Actions\Action::make('suspend')
                    ->label('Suspender')
                    ->icon('heroicon-o-pause-circle')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn (Tenant $r) => $r->status === 'active')
                    ->action(fn (Tenant $r) => $r->update(['status' => 'frozen'])),
                \Filament\Actions\Action::make('restore')
                    ->label('Restaurar')
                    ->icon('heroicon-o-play-circle')
                    ->color('success')
                    ->visible(fn (Tenant $r) => $r->status === 'frozen')
                    ->action(fn (Tenant $r) => $r->update(['status' => 'active'])),
                \Filament\Actions\Action::make('visit')
                    ->label('Ver sitio')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('gray')
                    ->url(fn (Tenant $r) => url('/' . $r->subdomain))
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('suspend_bulk')
                        ->label('Suspender seleccionados')
                        ->icon('heroicon-o-pause-circle')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['status' => 'frozen'])),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
