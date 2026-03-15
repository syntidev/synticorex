<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\LandingSectionResource\Pages\EditLandingSection;
use App\Filament\Resources\LandingSectionResource\Pages\ListLandingSections;
use App\Models\LandingSection;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class LandingSectionResource extends Resource
{
    protected static ?string $model = LandingSection::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationLabel = 'Landing Page';

    protected static ?string $modelLabel = 'Sección';

    protected static ?string $pluralModelLabel = 'Landing Page';

    protected static UnitEnum|string|null $navigationGroup = 'Contenido';

    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Sección')->schema([
                    TextInput::make('section_label')
                        ->label('Etiqueta')
                        ->required()
                        ->maxLength(100),
                    Toggle::make('is_active')
                        ->label('Activa'),
                    TextInput::make('sort_order')
                        ->label('Orden')
                        ->numeric()
                        ->default(0),
                    KeyValue::make('content')
                        ->label('Contenido editable')
                        ->columnSpanFull(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('section_label')
                    ->label('Sección')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Activa')
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label('Orden')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Última edición')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLandingSections::route('/'),
            'edit'  => EditLandingSection::route('/{record}/edit'),
        ];
    }
}
