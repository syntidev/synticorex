<?php

declare(strict_types=1);

namespace App\Filament\Resources\DomainResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class EventsRelationManager extends RelationManager
{
    protected static string $relationship = 'events';
    protected static ?string $title       = 'Historial de eventos';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')->label('Evento'),
                Tables\Columns\TextColumn::make('performer.name')->label('Ejecutado por')->default('Sistema'),
                Tables\Columns\TextColumn::make('payload')->label('Detalle')
                    ->formatStateUsing(fn ($state) => $state ? json_encode($state) : '—'),
                Tables\Columns\TextColumn::make('created_at')->label('Fecha')->dateTime('d/m/Y H:i'),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25]);
    }
}
