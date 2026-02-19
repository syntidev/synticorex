<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Services\DollarRateService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;

class DashboardController extends Controller
{
    /**
     * @param DollarRateService $dollarRateService
     */
    public function __construct(
        private readonly DollarRateService $dollarRateService
    ) {}

    /**
     * Display tenant dashboard.
     *
     * @param int $tenantId
     * @return View|Response
     */
    public function index(int $tenantId): View|Response
    {
        try {
            // Find tenant and verify status
            $tenant = Tenant::with([
                'plan',
                'colorPalette',
                'customization',
                'products' => fn($q) => $q
                    ->where('is_active', true)
                    ->orderBy('position')
                    ->orderByDesc('created_at'),
                'services' => fn($q) => $q
                    ->where('is_active', true)
                    ->orderBy('position')
                    ->orderByDesc('created_at'),
            ])
            ->where('id', $tenantId)
            ->where('status', 'active')
            ->firstOrFail();

            $plan = $tenant->plan;
            $customization = $tenant->customization;
            $products = $tenant->products;
            $services = $tenant->services;

            // Get current dollar rate
            $dollarRate = $this->dollarRateService->getCurrentRate() ?? 36.50;

            return view('dashboard.index', compact(
                'tenant',
                'plan',
                'customization',
                'products',
                'services',
                'dollarRate'
            ));
        } catch (\Exception $e) {
            return response()->view('errors.404', [], 404);
        }
    }
}
