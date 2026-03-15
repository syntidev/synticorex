<?php

declare(strict_types=1);

namespace App\Filament\Resources\Tenants;

use App\Filament\Resources\Tenants\Pages\CreateTenant;
use App\Filament\Resources\Tenants\Pages\EditTenant;
use App\Filament\Resources\Tenants\Pages\ListTenants;
use App\Filament\Resources\Tenants\Schemas\TenantForm;
use App\Filament\Resources\Tenants\Tables\TenantsTable;
use App\Models\Tenant;
use BackedEnum;
use EslamRedaDiv\FilamentCopilot\Contracts\CopilotResource;
use Filament\Resources\Resource;
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
        return TenantForm::configure($schema);
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

