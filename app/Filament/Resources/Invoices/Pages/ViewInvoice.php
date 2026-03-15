<?php

declare(strict_types=1);

namespace App\Filament\Resources\Invoices\Pages;

use App\Filament\Resources\Invoices\InvoiceResource;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewInvoice extends ViewRecord
{
    protected static string $resource = InvoiceResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Tenant')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('tenant.business_name')
                            ->label('Negocio'),
                        TextEntry::make('tenant.subdomain')
                            ->label('Subdominio'),
                        TextEntry::make('tenant.email')
                            ->label('Email'),
                        TextEntry::make('tenant.phone')
                            ->label('Teléfono'),
                    ]),

                Section::make('Pago')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('invoice_number')
                            ->label('Nº Factura'),
                        TextEntry::make('amount_usd')
                            ->label('Monto (USD)')
                            ->money('USD'),
                        TextEntry::make('currency')
                            ->label('Moneda'),
                        TextEntry::make('payment_channel')
                            ->label('Canal')
                            ->formatStateUsing(fn (?string $state): string => match ($state) {
                                'pago_movil' => 'Pago Móvil',
                                'paypal' => 'PayPal',
                                'zinli' => 'Zinli',
                                default => $state ?? '—',
                            }),
                        TextEntry::make('payment_reference')
                            ->label('Referencia'),
                        TextEntry::make('payment_date')
                            ->label('Fecha de pago')
                            ->date('d M Y'),
                        TextEntry::make('period_start')
                            ->label('Período inicio')
                            ->date('d M Y'),
                        TextEntry::make('period_end')
                            ->label('Período fin')
                            ->date('d M Y'),
                        TextEntry::make('status')
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
                    ]),

                Section::make('Revisión')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('reviewer.name')
                            ->label('Revisado por')
                            ->placeholder('Sin revisar'),
                        TextEntry::make('reviewed_at')
                            ->label('Fecha revisión')
                            ->dateTime('d M Y H:i')
                            ->placeholder('—'),
                        TextEntry::make('admin_notes')
                            ->label('Notas del admin')
                            ->placeholder('Sin notas')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
