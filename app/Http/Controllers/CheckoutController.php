<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Services\OrderService;
use App\Services\WhatsappMessageBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService,
        private readonly WhatsappMessageBuilder $waBuilder,
    ) {}

    public function store(Request $request, string $subdomain): JsonResponse
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:120'],
            'location'     => ['nullable', 'string', 'max:120'],
            'items'        => ['required', 'array', 'min:1'],
            'items.*.title'   => ['required', 'string', 'max:200'],
            'items.*.qty'     => ['required', 'integer', 'min:1'],
            'items.*.price'   => ['required', 'numeric', 'min:0'],
            'items.*.variant' => ['nullable', 'string', 'max:100'],
        ]);

        $tenant = Tenant::where('subdomain', $subdomain)->first();

        if (!$tenant) {
            return response()->json(['success' => false, 'error' => 'tenant_not_found'], 404);
        }

        // Only cat-anual plan allows checkout
        if (!$tenant->plan || $tenant->plan->slug !== 'cat-anual') {
            return response()->json(['success' => false, 'error' => 'plan_requerido'], 403);
        }

        $customer = [
            'name'     => $validated['name'],
            'location' => $validated['location'] ?? '',
        ];

        $order   = $this->orderService->generate($tenant, $customer, $validated['items']);
        $message = $this->waBuilder->build($order);
        $waUrl   = $this->waBuilder->url($message, (string) ($tenant->whatsapp_sales ?: $tenant->phone ?: ''));

        return response()->json([
            'success'       => true,
            'order_id'      => $order['id'],
            'whatsapp_url'  => $waUrl,
        ]);
    }
}
