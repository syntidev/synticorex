<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Services\DollarRateService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

    /**
     * Update tenant information.
     *
     * @param Request $request
     * @param int $tenantId
     * @return JsonResponse
     */
    public function updateInfo(Request $request, int $tenantId): JsonResponse
    {
        try {
            // Find tenant and verify status
            $tenant = Tenant::where('id', $tenantId)
                ->where('status', 'active')
                ->firstOrFail();

            // Validate input
            $validated = $request->validate([
                'business_name' => 'required|string|max:255',
                'slogan' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'whatsapp_sales' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'address' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:100',
                'description' => 'nullable|string|max:500',
                'is_open' => 'nullable|boolean',
            ]);

            // Update tenant
            $tenant->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Información actualizada'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar información: ' . $e->getMessage()
            ], 422);
        }
    }
}
