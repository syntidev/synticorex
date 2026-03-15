<?php

declare(strict_types=1);

namespace App\Filament\Resources\Tenants;

use App\Filament\Resources\Tenants\Pages\CreateTenant;
use App\Filament\Resources\Tenants\Pages\EditTenant;
use App\Filament\Resources\Tenants\Pages\ListTenants;
use App\Filament\Resources\Tenants\Tables\TenantsTable;
use App\Models\Plan;
use App\Models\Tenant;
use App\Models\User;
use BackedEnum;
use EslamRedaDiv\FilamentCopilot\Contracts\CopilotResource;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TenantResource extends Resource implements CopilotResource
{
    protected static ?string $model = Tenant::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    protected static ?string $navigationLabel = 'Tenants';

    protected static ?string $modelLabel = 'Tenant';

    protected static ?string $pluralModelLabel = 'Tenants';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'business_name';

    public static function copilotResourceDescription(): ?string
    {
        return 'Gestiona los tenants (negocios clientes) de SYNTIweb. Cada tenant tiene un subdominio, plan, estado y fecha de vencimiento.';
    }

    public static function copilotTools(): array
    {
        return [
            new \App\Filament\Resources\Tenants\CopilotTools\ListTenantsTool(),
            new \App\Filament\Resources\Tenants\CopilotTools\SearchTenantsTool(),
            new \App\Filament\Resources\Tenants\CopilotTools\SuspendTenantTool(),
            new \App\Filament\Resources\Tenants\CopilotTools\RestoreTenantTool(),
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del negocio')->schema([
                    Select::make('user_id')
                        ->label('Usuario propietario')
                        ->options(User::query()->orderBy('email')->pluck('email', 'id')->all())
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
                        ->options(
                            Plan::query()
                                ->orderBy('blueprint')
                                ->orderBy('id')
                                ->get()
                                ->mapWithKeys(fn ($plan) => [
                                    $plan->id => ucfirst($plan->blueprint) . ' · ' . $plan->name,
                                ])
                                ->all()
                        )
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

    public static function table(Table $table): Table
    {
        return TenantsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTenants::route('/'),
            'create' => CreateTenant::route('/create'),
            'edit' => EditTenant::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['plan', 'user']);
    }
}

