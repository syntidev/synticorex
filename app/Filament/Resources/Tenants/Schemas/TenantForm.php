<?php

declare(strict_types=1);

namespace App\Filament\Resources\Tenants\Schemas;

use App\Models\Plan;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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
                    TextInput::make('email')
                        ->label('Email')->email()->maxLength(255),
                    TextInput::make('phone')
                        ->label('Teléfono')->maxLength(20),
                ])->columns(2),

                Section::make('Dominios')->schema([
                    TextInput::make('subdomain')
                        ->label('Subdominio')
                        ->placeholder('tallerdiesel')
                        ->helperText('Se accede como: tallerdiesel.syntiweb.com')
                        ->required()
                        ->maxLength(100),
                    TextInput::make('base_domain')
                        ->label('Dominio base')
                        ->placeholder('oficio.vip')
                        ->helperText('Dominio SYNTIweb asignado (oficio.vip, aqui.menu, punto.vip)')
                        ->nullable()
                        ->maxLength(255),
                    TextInput::make('custom_domain')
                        ->label('Dominio personalizado')
                        ->placeholder('tallerdiesel.oficio.vip')
                        ->helperText('Dominio externo. Requiere DNS wildcard configurado en Hostinger.')
                        ->nullable()
                        ->maxLength(255),
                    Toggle::make('domain_verified')
                        ->label('Dominio verificado')
                        ->helperText('Activar manualmente una vez confirmado el DNS'),
                ])->columns(2)->collapsible(),

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
