<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\EditAction;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->fontFamily('mono'),
                TextColumn::make('email_verified_at')
                    ->label('Verificado')
                    ->date('d M Y')
                    ->sortable()
                    ->placeholder('No verificado')
                    ->badge()
                    ->color(fn ($state): string => $state ? 'success' : 'gray'),
                TextColumn::make('role')
                    ->label('Rol')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin'    => 'danger',
                        'vendedor' => 'warning',
                        'soporte'  => 'info',
                        default    => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Registrado')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('email_verified_at')
                    ->label('Email verificado')
                    ->nullable(),
            ])
            ->recordActions([
                Action::make('cambiar_role')
                    ->label('Cambiar rol')
                    ->icon('heroicon-m-shield-check')
                    ->form([
                        \Filament\Forms\Components\Select::make('role')
                            ->label('Rol')
                            ->options([
                                User::ROLE_ADMIN    => 'Admin',
                                User::ROLE_VENDEDOR => 'Vendedor',
                                User::ROLE_SOPORTE  => 'Soporte',
                                User::ROLE_CLIENTE  => 'Cliente',
                            ])
                            ->required(),
                    ])
                    ->action(function (User $record, array $data): void {
                        $record->update(['role' => $data['role']]);
                    })
                    ->fillForm(fn (User $record): array => ['role' => $record->role]),
                EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
