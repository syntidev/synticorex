<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\CompanySetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use UnitEnum;

class CompanySettingsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationLabel = 'Datos de Empresa';

    protected static UnitEnum|string|null $navigationGroup = 'Configuración';

    protected static ?string $title = 'Datos de la Empresa';

    protected string $view = 'filament.pages.company-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $setting = CompanySetting::current();
        $this->form->fill($setting->toArray());
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                TextInput::make('company_name')
                    ->label('Nombre de la empresa')
                    ->required(),
                TextInput::make('rif')
                    ->label('RIF')
                    ->placeholder('J-12345678-9'),
                Textarea::make('address')
                    ->label('Dirección')
                    ->rows(2),
                TextInput::make('phone')
                    ->label('Teléfono'),
                TextInput::make('whatsapp_support')
                    ->label('WhatsApp soporte')
                    ->placeholder('04121234567'),
                TextInput::make('email_support')
                    ->label('Email soporte')
                    ->email(),
                TextInput::make('website')
                    ->label('Sitio web'),
                TextInput::make('instagram')
                    ->label('Instagram')
                    ->placeholder('@syntiweb'),
                TextInput::make('twitter')
                    ->label('Twitter'),
                FileUpload::make('logo_path')
                    ->label('Logo')
                    ->disk('public')
                    ->directory('company')
                    ->image()
                    ->maxSize(2048)
                    ->columnSpanFull(),
            ])
            ->columns(2)
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Guardar')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        CompanySetting::updateOrCreate(['id' => 1], $data);

        Notification::make()
            ->title('Datos de empresa guardados')
            ->success()
            ->send();
    }
}
