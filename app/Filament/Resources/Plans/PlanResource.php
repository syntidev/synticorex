<?php

declare(strict_types=1);

namespace App\Filament\Resources\Plans;

use App\Filament\Resources\Plans\Pages\EditPlan;
use App\Filament\Resources\Plans\Pages\ListPlans;
use App\Filament\Resources\Plans\Tables\PlansTable;
use App\Models\Plan;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static ?string $navigationLabel = 'Planes';

    protected static ?string $modelLabel = 'Plan';

    protected static ?string $pluralModelLabel = 'Planes';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'slug';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identificación')->schema([
                    TextInput::make('slug')
                        ->label('Slug')
                        ->disabled()
                        ->dehydrated(false),
                    TextInput::make('blueprint')
                        ->label('Blueprint')
                        ->disabled()
                        ->dehydrated(false),
                    TextInput::make('name')
                        ->label('Nombre')
                        ->required()
                        ->maxLength(64),
                ])->columns(3),

                Section::make('Precios')->schema([
                    TextInput::make('price_usd')
                        ->label('Precio anual (USD)')
                        ->numeric()
                        ->step(0.01)
                        ->minValue(0)
                        ->nullable(),
                ])->columns(2),

                Section::make('Límites')->schema([
                    TextInput::make('products_limit')
                        ->label('Máx productos')
                        ->numeric()
                        ->nullable()
                        ->hint('Vacío = ilimitado'),
                    TextInput::make('services_limit')
                        ->label('Máx servicios')
                        ->numeric()
                        ->nullable()
                        ->hint('Vacío = ilimitado'),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return PlansTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPlans::route('/'),
            'edit' => EditPlan::route('/{record}/edit'),
        ];
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return !auth()->user()->isSoporte();
    }

    public static function canCreate(): bool
    {
        return !auth()->user()->isSoporte();
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return !auth()->user()->isSoporte();
    }
}
