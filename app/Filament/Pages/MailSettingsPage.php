<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\MailSetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use UnitEnum;

class MailSettingsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'tabler--mail-cog';

    protected static ?string $navigationLabel = 'Correo SMTP';

    protected static UnitEnum|string|null $navigationGroup = 'Configuración';

    protected static ?string $title = 'Configuración de Correo SMTP';

    protected string $view = 'filament.pages.mail-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $setting = MailSetting::current();

        $this->form->fill($setting ? $setting->toArray() : [
            'driver' => 'smtp',
            'port' => 587,
            'is_active' => true,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('driver')
                    ->label('Driver')
                    ->options([
                        'smtp' => 'SMTP',
                        'sendmail' => 'Sendmail',
                        'mailgun' => 'Mailgun',
                    ])
                    ->default('smtp')
                    ->required(),
                TextInput::make('host')
                    ->label('Host')
                    ->placeholder('smtp.gmail.com')
                    ->required(),
                TextInput::make('port')
                    ->label('Puerto')
                    ->numeric()
                    ->default(587)
                    ->required(),
                Select::make('encryption')
                    ->label('Encriptación')
                    ->options([
                        '' => 'Ninguna',
                        'tls' => 'TLS',
                        'ssl' => 'SSL',
                    ])
                    ->nullable(),
                TextInput::make('username')
                    ->label('Usuario'),
                TextInput::make('password')
                    ->label('Contraseña')
                    ->password()
                    ->revealable(),
                TextInput::make('from_address')
                    ->label('Email remitente')
                    ->email()
                    ->required(),
                TextInput::make('from_name')
                    ->label('Nombre remitente')
                    ->required(),
                Toggle::make('is_active')
                    ->label('Activo'),
            ])
            ->columns(2)
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('guardar')
                ->label('Guardar')
                ->icon('tabler--device-floppy')
                ->action(function (): void {
                    $data = $this->form->getState();

                    MailSetting::updateOrCreate(
                        ['id' => MailSetting::current()?->id ?? 0],
                        $data,
                    );

                    Notification::make()
                        ->title('Configuración de correo guardada')
                        ->success()
                        ->send();
                }),

            Action::make('enviar_prueba')
                ->label('Enviar correo de prueba')
                ->icon('tabler--send')
                ->color('gray')
                ->form([
                    TextInput::make('test_email')
                        ->label('Email destino')
                        ->email()
                        ->required(),
                ])
                ->action(function (array $data): void {
                    try {
                        Mail::raw('Prueba SMTP desde SYNTIweb admin', function ($message) use ($data) {
                            $message->to($data['test_email'])->subject('Test SMTP');
                        });

                        Notification::make()
                            ->title('Correo de prueba enviado')
                            ->success()
                            ->send();
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title('Error enviando correo')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();

                        Log::error('SMTP test failed', ['error' => $e->getMessage()]);
                    }
                }),
        ];
    }
}
