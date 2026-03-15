<?php

declare(strict_types=1);

namespace App\Filament\Resources\Invoices;

use App\Filament\Resources\Invoices\Pages\ListInvoices;
use App\Filament\Resources\Invoices\Pages\ViewInvoice;
use App\Models\Invoice;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?string $navigationLabel = 'Facturas';

    protected static ?string $modelLabel = 'Factura';

    protected static ?string $pluralModelLabel = 'Facturas';

    protected static UnitEnum|string|null $navigationGroup = 'Finanzas';

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'invoice_number';

    public static function getNavigationBadge(): ?string
    {
        $count = Invoice::pendingReview()->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')
                    ->label('Nº Factura')
                    ->searchable()
                    ->sortable()
                    ->fontFamily('mono'),
                TextColumn::make('tenant.business_name')
                    ->label('Negocio')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tenant.subdomain')
                    ->label('Subdominio')
                    ->fontFamily('mono')
                    ->color('gray'),
                TextColumn::make('amount_usd')
                    ->label('Monto (USD)')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('payment_channel')
                    ->label('Canal')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'pago_movil' => 'Pago Móvil',
                        'paypal' => 'PayPal',
                        'zinli' => 'Zinli',
                        default => $state ?? '—',
                    }),
                TextColumn::make('payment_reference')
                    ->label('Referencia')
                    ->searchable()
                    ->fontFamily('mono')
                    ->limit(20),
                TextColumn::make('payment_date')
                    ->label('Fecha pago')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'pending_review' => 'warning',
                        'paid' => 'success',
                        'rejected' => 'danger',
                        'cancelled' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pendiente',
                        'pending_review' => 'En Revisión',
                        'paid' => 'Pagado',
                        'rejected' => 'Rechazado',
                        'cancelled' => 'Cancelado',
                        default => $state,
                    }),
                TextColumn::make('created_at')
                    ->label('Creada')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'pending' => 'Pendiente',
                        'pending_review' => 'En Revisión',
                        'paid' => 'Pagado',
                        'rejected' => 'Rechazado',
                        'cancelled' => 'Cancelado',
                    ]),
            ])
            ->actions([
                Action::make('aprobar')
                    ->label('Aprobar')
                    ->icon(Heroicon::OutlinedCheck)
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Aprobar factura')
                    ->modalDescription('El tenant pasará a estado activo y su suscripción se renovará.')
                    ->form([
                        Textarea::make('admin_notes')
                            ->label('Notas (opcional)')
                            ->rows(3),
                    ])
                    ->action(function (Invoice $record, array $data): void {
                        $record->update([
                            'status' => 'paid',
                            'admin_notes' => $data['admin_notes'] ?? null,
                            'reviewed_at' => now(),
                            'reviewed_by' => Auth::id(),
                        ]);

                        $tenant = $record->tenant;
                        if ($tenant !== null) {
                            $tenant->update([
                                'status' => 'active',
                                'subscription_ends_at' => $record->period_end,
                            ]);

                            $tenant->user?->notify(new \App\Notifications\PaymentApprovedNotification($record));
                        }
                    })
                    ->visible(fn (Invoice $record): bool => $record->isPendingReview()),

                Action::make('rechazar')
                    ->label('Rechazar')
                    ->icon(Heroicon::OutlinedXMark)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Rechazar factura')
                    ->modalDescription('Indica el motivo del rechazo.')
                    ->form([
                        Textarea::make('admin_notes')
                            ->label('Motivo del rechazo')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (Invoice $record, array $data): void {
                        $record->update([
                            'status' => 'rejected',
                            'admin_notes' => $data['admin_notes'],
                            'reviewed_at' => now(),
                            'reviewed_by' => Auth::id(),
                        ]);

                        $record->tenant?->user?->notify(new \App\Notifications\PaymentRejectedNotification($record));
                    })
                    ->visible(fn (Invoice $record): bool => $record->isPendingReview()),

                Action::make('ver_comprobante')
                    ->label('Comprobante')
                    ->icon(Heroicon::OutlinedEye)
                    ->color('gray')
                    ->url(fn (Invoice $record): ?string => $record->receipt_path
                        ? url('storage/' . $record->receipt_path)
                        : null)
                    ->openUrlInNewTab()
                    ->visible(fn (Invoice $record): bool => $record->receipt_path !== null),

                \Filament\Actions\ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInvoices::route('/'),
            'view' => ViewInvoice::route('/{record}'),
        ];
    }
}
