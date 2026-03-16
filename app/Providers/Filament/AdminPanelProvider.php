<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use Filament\FontProviders\GoogleFontProvider;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
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
use App\Filament\Pages\Dashboard;
use EslamRedaDiv\FilamentCopilot\FilamentCopilotPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->brandName('SYNTIweb')
            ->brandLogo(asset('brand/syntiweb-logo-flat-negative.svg'))
            ->brandLogoHeight('2rem')
            ->favicon(asset('brand/favicon.ico'))
            ->darkMode(true)
            ->colors([
                'primary' => Color::hex('#4A80E4'),
                'gray'    => Color::Gray,
                'info'    => Color::hex('#3B82F6'),
                'success' => Color::hex('#22C55E'),
                'warning' => Color::hex('#F59E0B'),
                'danger'  => Color::hex('#EF4444'),
            ])
            ->font('Inter', provider: GoogleFontProvider::class)
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->navigationGroups([
                NavigationGroup::make('Plataforma')
                    ->collapsed(false),
                NavigationGroup::make('Facturación')
                    ->collapsed(false),
                NavigationGroup::make('Contenido')
                    ->collapsed(true),
                NavigationGroup::make('Configuración')
                    ->collapsed(true),
                NavigationGroup::make('Sistema')
                    ->collapsed(true),
            ])
            ->renderHook(
                'panels::topbar.end',
                fn () => view('filament.components.topbar-stats'),
            )
            ->login()
            ->homeUrl('/admin')
            ->authGuard('web')
            ->authMiddleware([
                Authenticate::class,
                \App\Http\Middleware\EnsureAdmin::class,
            ])
            ->resources([
                \App\Filament\Resources\Tenants\TenantResource::class,
                \App\Filament\Resources\Users\UserResource::class,
                \App\Filament\Resources\Plans\PlanResource::class,
                \App\Filament\Resources\Invoices\InvoiceResource::class,
                \App\Filament\Resources\SupportTicketResource::class,
                \App\Filament\Resources\BlogPostResource::class,
                \App\Filament\Resources\MediaResource::class,
                \App\Filament\Resources\LandingSectionResource::class,
                \App\Filament\Resources\DomainResource::class,
            ])
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([Dashboard::class])
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
                    ->provider('anthropic')
                    ->model('claude-haiku-4-5-20251001')
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
