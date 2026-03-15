<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\HealthCheckService;
use Illuminate\Http\RedirectResponse;

class HealthCheckController extends Controller
{
    public function refresh(HealthCheckService $service): RedirectResponse
    {
        $results = $service->checkAll();
        cache(['health_results' => $results], 300);
        cache(['health_timestamp' => now()->toDateTimeString()], 300);

        return redirect()->back();
    }
}
