<?php

namespace App\Providers;

use App\Models\Tenant;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
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
        View::composer('landing.partials.header-top', function ($view): void {
            $data = $view->getData();

            /** @var Tenant|null $tenant */
            $tenant = $data['tenant']
                ?? (app()->bound('tenant') ? app('tenant') : null)
                ?? request()->get('tenant');

            $schedule = null;
            $phone = null;
            $delivery = false;
            $bannerText = '';

            if ($tenant instanceof Tenant) {
                $schedule = data_get($tenant->settings, 'business_info.schedule_display', 'Lun–Sáb 9:00–18:00');
                $phone = data_get(
                    $tenant->settings,
                    'contact_info.phone',
                    preg_replace('/[^0-9]/', '', $tenant->whatsapp_number ?? '')
                );
                $delivery = (bool) data_get($tenant->settings, 'business_info.delivery_available', false);
                $bannerText = (string) data_get($tenant->settings, 'business_info.top_nav_banner', '');
            }

            $phoneClean = preg_replace('/[^0-9]/', '', (string) $phone);
            $phoneDisplay = $phone ?: null;

            $view->with([
                'schedule' => $schedule,
                'phone' => $phone,
                'delivery' => $delivery,
                'bannerText' => $bannerText,
                'phoneClean' => $phoneClean,
                'phoneDisplay' => $phoneDisplay,
            ]);
        });
    }
}
