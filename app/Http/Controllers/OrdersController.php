<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;

class OrdersController extends Controller
{
    public function index(int $tenantId): View
    {
        $tenant = Tenant::with('plan')->findOrFail($tenantId);

        $isPlanAnual = $tenant->plan && $tenant->plan->slug === 'cat-anual';

        if (!$isPlanAnual) {
            return view('dashboard.components.orders-section', [
                'orders'      => [],
                'tenant'      => $tenant,
                'isPlanAnual' => false,
            ]);
        }

        $files  = Storage::disk('local')->allFiles("tenants/{$tenantId}/orders");
        $orders = [];

        foreach ($files as $file) {
            if (!str_ends_with($file, '.json')) {
                continue;
            }
            $content = Storage::disk('local')->get($file);
            $order   = json_decode($content, true);
            if (is_array($order) && isset($order['id'])) {
                $orders[] = $order;
            }
        }

        // Sort by date DESC
        usort($orders, fn (array $a, array $b) => strcmp($b['date'] ?? '', $a['date'] ?? ''));

        return view('dashboard.components.orders-section', compact('orders', 'tenant', 'isPlanAnual'));
    }
}
