<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Support\Facades\Storage;

class OrderService
{
    /**
     * Generate a unique order ID (SC- + 6 uppercase alphanumeric chars).
     * Verifies uniqueness against existing JSON files in the tenant's orders directory.
     */
    public function generateId(int $tenantId): string
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $year  = date('Y');
        $month = date('m');

        do {
            $random  = '';
            for ($i = 0; $i < 6; $i++) {
                $random .= $chars[random_int(0, strlen($chars) - 1)];
            }
            $id   = 'SC-' . $random;
            $path = "tenants/{$tenantId}/orders/{$year}/{$month}/{$id}.json";
        } while (Storage::disk('local')->exists($path));

        return $id;
    }

    /**
     * Generate and persist a new order.
     *
     * @param  Tenant  $tenant
     * @param  array{name: string, location: string}  $customer
     * @param  array<int, array{title: string, qty: int, price: float, variant: string|null}>  $items
     * @return array  The saved order array
     */
    public function generate(Tenant $tenant, array $customer, array $items): array
    {
        $id       = $this->generateId($tenant->id);
        $subtotal = array_reduce($items, fn (float $carry, array $item) => $carry + ($item['qty'] * $item['price']), 0.0);

        $order = [
            'id'        => $id,
            'tenant_id' => $tenant->id,
            'date'      => now()->toIso8601String(),
            'customer'  => [
                'name'     => $customer['name'],
                'location' => $customer['location'] ?? '',
            ],
            'items'    => array_map(fn (array $item) => [
                'title'   => $item['title'],
                'qty'     => (int) $item['qty'],
                'price'   => (float) $item['price'],
                'variant' => $item['variant'] ?? null,
            ], $items),
            'subtotal' => round($subtotal, 2),
            'currency' => 'REF',
            'channel'  => 'whatsapp',
        ];

        $this->save($tenant->id, $order);

        return $order;
    }

    /**
     * Persist the order as a JSON file.
     * Path: storage/app/tenants/{tenant_id}/orders/{year}/{month}/SC-XXXX.json
     */
    public function save(int $tenantId, array $order): void
    {
        $year  = date('Y', strtotime($order['date']));
        $month = date('m', strtotime($order['date']));
        $path  = "tenants/{$tenantId}/orders/{$year}/{$month}/{$order['id']}.json";

        Storage::disk('local')->put($path, json_encode($order, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
