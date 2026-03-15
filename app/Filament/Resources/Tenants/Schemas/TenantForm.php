<?php

declare(strict_types=1);

namespace App\Filament\Resources\Tenants\Schemas;

use App\Models\Plan;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TenantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del negocio')->schema([
                    Select::make('user_id')
                        ->label('Usuario propietario')
                        ->options(User::orderBy('email')->pluck('email', 'id'))
                        ->searchable()
                        ->required()
                        ->hint('El usuario que administrará este tenant')
                        ->columnSpanFull(),
                    TextInput::make('business_name')
                        ->label('Nombre del negocio')
                        ->required()->maxLength(128),
                    TextInput::make('subdomain')
                        ->label('Subdominio')
                        ->required()->maxLength(100)
                        ->unique(ignoreRecord: true),
                    TextInput::make('email')
                        ->label('Email')->email()->maxLength(255),
                    TextInput::make('phone')
                        ->label('Teléfono')->maxLength(20),
                ])->columns(2),

                Section::make('Plan y estado')->schema([
                    Select::make('plan_id')
                        ->label('Plan')
                        ->options(Plan::pluck('name', 'id'))
                        ->required(),
                    Select::make('status')
                        ->label('Estado')
                        ->options([
                            'active'   => 'Activo',
                            'frozen'   => 'Suspendido',
                            'archived' => 'Archivado',
                        ])
                        ->required(),
                    DateTimePicker::make('subscription_ends_at')
                        ->label('Vence suscripción')
                        ->nullable(),
                    DateTimePicker::make('trial_ends_at')
                        ->label('Fin de trial')
                        ->nullable(),
                ])->columns(2),
            ]);
    }
}
