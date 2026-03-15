<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureMailFromDatabase();

        \App\Models\SupportTicket::observe(\App\Observers\SupportTicketObserver::class);
    }

    private function configureMailFromDatabase(): void
    {
        try {
            $settings = \App\Models\MailSetting::current();
        } catch (\Throwable) {
            return;
        }

        if ($settings && $settings->is_active) {
            config([
                'mail.default'                 => $settings->driver,
                'mail.mailers.smtp.host'       => $settings->host,
                'mail.mailers.smtp.port'       => $settings->port,
                'mail.mailers.smtp.encryption' => $settings->encryption,
                'mail.mailers.smtp.username'   => $settings->username,
                'mail.mailers.smtp.password'   => $settings->password,
                'mail.from.address'            => $settings->from_address,
                'mail.from.name'               => $settings->from_name,
            ]);
        }
    }
}
