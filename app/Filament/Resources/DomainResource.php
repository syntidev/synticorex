<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\DomainResource\Pages;
use App\Filament\Resources\DomainResource\RelationManagers;
use App\Models\Domain;
use BackedEnum;
use UnitEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Table;

class DomainResource extends Resource
{
    protected static ?string $model = Domain::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    protected static UnitEnum|string|null $navigationGroup = 'Infraestructura';

    protected static ?int $navigationSort = 10;

    protected static ?string $modelLabel = 'Dominio';

    protected static ?string $pluralModelLabel = 'Dominios';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Identidad')->schema([
                TextInput::make('domain')->label('Dominio')->required()->maxLength(255),
                TextInput::make('tld')->label('TLD')->required()->maxLength(20)->placeholder('.com'),
                Select::make('type')->label('Tipo')->required()->options([
                    'platform' => 'Plataforma (nuestro)',
                    'addon'    => 'Add-on (revendido)',
                    'external' => 'Externo (cliente)',
                ]),
                Select::make('managed_by')->label('Gestionado por')->required()->options([
                    'platform' => 'SYNTIweb',
                    'client'   => 'Cliente',
                ])->live(),
                Select::make('tenant_id')
                    ->label('Cliente / Tenant')
                    ->relationship('tenant', 'business_name')
                    ->searchable()
                    ->nullable(),
            ])->columns(2),

            Section::make('Registrador')
                ->visible(fn ($get) => $get('managed_by') === 'platform')
                ->schema([
                    Select::make('registrar')->label('Registrador')->options([
                        'namecheap' => 'Namecheap',
                        'porkbun'   => 'Porkbun',
                        'godaddy'   => 'GoDaddy',
                        'other'     => 'Otro',
                    ]),
                    TextInput::make('registrar_account')->label('Cuenta en registrador'),
                    TextInput::make('registrar_login')->label('Email de acceso')->email(),
                    TextInput::make('auth_code')->label('Código EPP')->password()->revealable(),
                ])->columns(2),

            Section::make('Ciclo de vida')->schema([
                DatePicker::make('registered_at')->label('Fecha de registro'),
                DatePicker::make('expires_at')->label('Fecha de vencimiento'),
                DatePicker::make('last_renewed_at')->label('Última renovación'),
                Toggle::make('auto_renew')->label('Auto-renovación'),
                Toggle::make('transfer_lock')->label('Bloqueo de transferencia'),
            ])->columns(2),

            Section::make('Financiero')
                ->visible(fn ($get) => $get('managed_by') === 'platform')
                ->schema([
                    TextInput::make('cost_price')->label('Costo (pagamos)')->numeric()->prefix('$'),
                    TextInput::make('sale_price')->label('Precio (cobramos)')->numeric()->prefix('$'),
                    Select::make('billing_cycle')->label('Ciclo')->options([
                        'monthly' => 'Mensual',
                        'annual'  => 'Anual',
                    ]),
                ])->columns(3),

            Section::make('DNS')->schema([
                Select::make('dns_status')->label('Estado DNS')->options([
                    'ok'      => 'OK',
                    'failing' => 'Fallando',
                    'pending' => 'Pendiente',
                    'unknown' => 'Sin verificar',
                ]),
                TextInput::make('dns_expected_ip')->label('IP esperada del servidor'),
            ])->columns(2),

            Section::make('Notas operativas')->schema([
                Textarea::make('notes')->label('Notas internas')->rows(4),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('domain')->label('Dominio')->searchable()->sortable()->weight('bold'),
                Tables\Columns\TextColumn::make('type')->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'platform' => 'primary',
                        'addon'    => 'success',
                        'external' => 'warning',
                        default    => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'platform' => 'Plataforma',
                        'addon'    => 'Add-on',
                        'external' => 'Externo',
                        default    => $state,
                    }),
                Tables\Columns\TextColumn::make('tenant.business_name')->label('Cliente')->searchable()->default('—'),
                Tables\Columns\TextColumn::make('expires_at')->label('Vence')->date('d/m/Y')->sortable()
                    ->color(fn ($record) => match (true) {
                        $record->expires_at === null      => null,
                        $record->daysUntilExpiry() <= 7   => 'danger',
                        $record->daysUntilExpiry() <= 45  => 'warning',
                        default                           => 'success',
                    }),
                Tables\Columns\TextColumn::make('dns_status')->label('DNS')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'ok'      => 'success',
                        'failing' => 'danger',
                        'pending' => 'warning',
                        default   => 'gray',
                    }),
                Tables\Columns\TextColumn::make('status')->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active'        => 'success',
                        'expiring_soon' => 'warning',
                        'expired', 'grace_period', 'redemption' => 'danger',
                        default         => 'gray',
                    }),
                Tables\Columns\IconColumn::make('auto_renew')->label('Auto-renew')->boolean(),
                Tables\Columns\TextColumn::make('managed_by')->label('Gestión')
                    ->formatStateUsing(fn ($state) => $state === 'platform' ? 'SYNTIweb' : 'Cliente'),
            ])
            ->defaultSort('expires_at', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')->label('Tipo')->options([
                    'platform' => 'Plataforma',
                    'addon'    => 'Add-on',
                    'external' => 'Externo',
                ]),
                Tables\Filters\SelectFilter::make('status')->label('Estado'),
                Tables\Filters\SelectFilter::make('dns_status')->label('DNS'),
                Tables\Filters\Filter::make('expiring_soon')->label('Por vencer (45 días)')
                    ->query(fn ($query) => $query->whereDate('expires_at', '<=', now()->addDays(45))->where('status', '!=', 'cancelled')),
                Tables\Filters\Filter::make('dns_failing')->label('DNS con problemas')
                    ->query(fn ($query) => $query->where('dns_status', 'failing')),
            ])
            ->actions([
                EditAction::make(),
                Action::make('verify_dns')
                    ->label('Verificar DNS')
                    ->icon('heroicon-o-arrow-path')
                    ->action(function (Domain $record): void {
                        $resolved = gethostbyname($record->domain);
                        $expected = $record->dns_expected_ip ?? config('app.server_ip');
                        $status   = ($resolved === $expected) ? 'ok' : 'failing';
                        $record->update(['dns_status' => $status, 'dns_verified_at' => now()]);
                        $record->logEvent(
                            $status === 'ok' ? 'dns_verified' : 'dns_failed',
                            ['resolved_ip' => $resolved, 'expected_ip' => $expected]
                        );
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [RelationManagers\EventsRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListDomains::route('/'),
            'create' => Pages\CreateDomain::route('/create'),
            'edit'   => Pages\EditDomain::route('/{record}/edit'),
        ];
    }
}
