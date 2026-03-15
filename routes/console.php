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

Schedule::command('alerts:check')
    ->hourly()
    ->withoutOverlapping()
    ->runInBackground();

Schedule::command('reports:send --period=weekly')
    ->weeklyOn(1, '08:00')
    ->withoutOverlapping()
    ->runInBackground();

Schedule::command('reports:send --period=monthly')
    ->monthlyOn(1, '08:00')
    ->withoutOverlapping()
    ->runInBackground();

Schedule::command('domains:verify-dns')
    ->dailyAt('06:00');

Schedule::command('domains:process-expirations')
    ->dailyAt('07:00');
