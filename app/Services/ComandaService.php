<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Support\Facades\Storage;

class ComandaService
{
    /**
     * Generate a unique comanda ID (SF- + 6 uppercase alphanumeric chars).
     * Verifies uniqueness against existing JSON files in the tenant's comandas directory.
     */
    public function generateId(int $tenantId): string
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $year  = date('Y');
        $month = date('m');

        do {
            $random = '';
            for ($i = 0; $i < 6; $i++) {
                $random .= $chars[random_int(0, strlen($chars) - 1)];
            }
            $id   = 'SF-' . $random;
            $path = "tenants/{$tenantId}/comandas/{$year}/{$month}/{$id}.json";
        } while (Storage::disk('local')->exists($path));

        return $id;
    }

    /**
     * Generate a new comanda.
     *
     * @param  Tenant  $tenant
     * @param  string  $customerName
     * @param  string  $modalidad  sitio|llevar|delivery
     * @param  array<int, array{nombre: string, qty: int, precio: float}>  $items
     * @return array  The comanda array
     */
    public function generate(Tenant $tenant, string $customerName, string $modalidad, array $items): array
    {
        $id    = $this->generateId($tenant->id);
        $total = array_reduce($items, fn(float $carry, array $item) => $carry + ($item['qty'] * $item['precio']), 0.0);

        $comanda = [
            'id'          => $id,
            'tenant_id'   => $tenant->id,
            'date'        => now()->toIso8601String(),
            'customer_name' => $customerName,
            'modalidad'   => $modalidad,
            'items' => array_map(fn(array $item) => [
                'nombre' => $item['nombre'],
                'qty'    => (int) $item['qty'],
                'precio' => (float) $item['precio'],
            ], $items),
            'total'   => round($total, 2),
            'channel' => 'whatsapp',
        ];

        $this->save($tenant->id, $comanda);

        return $comanda;
    }

    /**
     * Persist the comanda as a JSON file.
     * Path: storage/app/tenants/{tenant_id}/comandas/{year}/{month}/SF-XXXX.json
     */
    public function save(int $tenantId, array $comanda): void
    {
        $year  = date('Y', strtotime($comanda['date']));
        $month = date('m', strtotime($comanda['date']));
        $path  = "tenants/{$tenantId}/comandas/{$year}/{$month}/{$comanda['id']}.json";

        Storage::disk('local')->put($path, json_encode($comanda, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
