<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('dollar:update')
    ->hourly()
    ->withoutOverlapping()
    ->runInBackground();

Schedule::command('tenants:check-expiry')
    ->dailyAt('02:00')
    ->withoutOverlapping()
    ->runInBackground();

Schedule::command('tenants:suspend-expired')
    ->dailyAt('02:00')
    ->withoutOverlapping()
    ->runInBackground();
