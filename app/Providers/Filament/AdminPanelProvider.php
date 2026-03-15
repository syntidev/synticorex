<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use EslamRedaDiv\FilamentCopilot\FilamentCopilotPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->colors(['primary' => Color::Blue])
            ->login()
            ->authGuard('web')
            ->authMiddleware([
                Authenticate::class,
                \App\Http\Middleware\EnsureAdmin::class,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([Pages\Dashboard::class])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugin(
                FilamentCopilotPlugin::make()
                    ->provider('gemini')
                    ->model('gemini-2.0-flash')
                    ->systemPrompt('Eres el asistente admin de SYNTIweb. Puedes gestionar tenants: listar, buscar, suspender, restaurar y cambiar planes. Responde siempre en español.')
                    ->quickActions([
                        'Tenants activos'   => 'Lista los 10 tenants con status activo más recientes.',
                        'Tenants vencidos'  => 'Lista los tenants cuya suscripción ya venció.',
                        'Tenants suspendidos' => 'Lista todos los tenants con status frozen.',
                    ])
                    ->managementEnabled()
                    ->memoryEnabled()
                    ->authorizeUsing(fn ($user) => $user->isAdmin())
            );
    }
}
