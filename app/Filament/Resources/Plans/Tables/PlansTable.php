<?php

declare(strict_types=1);

namespace App\Filament\Resources\Plans\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PlansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable()
                    ->fontFamily('mono')
                    ->color('gray'),
                TextColumn::make('blueprint')
                    ->label('Blueprint')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'studio' => 'info',
                        'food'   => 'warning',
                        'cat'    => 'success',
                        default  => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('price_usd')
                    ->label('Precio anual')
                    ->money('USD')
                    ->sortable()
                    ->placeholder('—'),
                TextColumn::make('products_limit')
                    ->label('Máx prod.')
                    ->sortable()
                    ->placeholder('∞'),
                TextColumn::make('services_limit')
                    ->label('Máx serv.')
                    ->sortable()
                    ->placeholder('∞'),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->defaultSort('blueprint', 'asc');
    }
}
