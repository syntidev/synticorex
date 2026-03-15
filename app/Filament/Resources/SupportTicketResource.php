<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\SupportTicketResource\Pages\ListSupportTickets;
use App\Filament\Resources\SupportTicketResource\Pages\ViewSupportTicket;
use App\Models\SupportTicket;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use UnitEnum;

class SupportTicketResource extends Resource
{
    protected static ?string $model = SupportTicket::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationLabel = 'Tickets';

    protected static ?string $modelLabel = 'Ticket';

    protected static ?string $pluralModelLabel = 'Tickets';

    protected static UnitEnum|string|null $navigationGroup = 'Facturación';

    protected static ?int $navigationSort = 20;

    protected static ?string $recordTitleAttribute = 'subject';

    public static function getNavigationBadge(): ?string
    {
        $count = SupportTicket::where('status', 'open')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'danger';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('status', 'asc')
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),
                TextColumn::make('tenant.business_name')
                    ->label('Negocio')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subject')
                    ->label('Asunto')
                    ->searchable()
                    ->limit(50),
                TextColumn::make('category')
                    ->label('Categoría')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'billing' => 'warning',
                        'technical' => 'info',
                        'general' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'billing' => 'Facturación',
                        'technical' => 'Técnico',
                        'general' => 'General',
                        default => $state,
                    }),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'danger',
                        'answered' => 'warning',
                        'closed' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'open' => 'Abierto',
                        'answered' => 'Respondido',
                        'closed' => 'Cerrado',
                        default => $state,
                    }),
                TextColumn::make('created_at')
                    ->label('Hace')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'open' => 'Abierto',
                        'answered' => 'Respondido',
                        'closed' => 'Cerrado',
                    ]),
                SelectFilter::make('category')
                    ->label('Categoría')
                    ->options([
                        'billing' => 'Facturación',
                        'technical' => 'Técnico',
                        'general' => 'General',
                    ]),
            ])
            ->headerActions([
                ExportAction::make()->exports([
                    ExcelExport::make('tickets')->fromTable()
                        ->withFilename('tickets-' . now()->format('Y-m-d'))
                        ->withColumns([
                            Column::make('id')->heading('#'),
                            Column::make('tenant.business_name')->heading('Negocio'),
                            Column::make('subject')->heading('Asunto'),
                            Column::make('category')->heading('Categoría'),
                            Column::make('status')->heading('Estado'),
                            Column::make('created_at')->heading('Creado'),
                        ]),
                ]),
            ])
            ->actions([
                \Filament\Actions\ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSupportTickets::route('/'),
            'view' => ViewSupportTicket::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with(['tenant', 'user']);
    }
}
