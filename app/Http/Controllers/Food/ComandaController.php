<?php

declare(strict_types=1);

namespace App\Http\Controllers\Food;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Services\ComandaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ComandaController extends Controller
{
    public function __construct(
        private readonly ComandaService $comandaService,
    ) {}

    public function store(Request $request, string $subdomain): JsonResponse
    {
        $validated = $request->validate([
            'customer_name'   => ['required', 'string', 'max:120'],
            'modalidad'       => ['required', 'string', 'in:sitio,llevar,delivery'],
            'items'           => ['required', 'array', 'min:1'],
            'items.*.nombre'  => ['required', 'string', 'max:200'],
            'items.*.qty'     => ['required', 'integer', 'min:1'],
            'items.*.precio'  => ['required', 'numeric', 'min:0'],
        ]);

        $tenant = Tenant::with('plan')->where('subdomain', $subdomain)->first();

        if (!$tenant) {
            return response()->json(['success' => false, 'error' => 'tenant_not_found'], 404);
        }

        // Block comanda generation if tenant is closed
        $isOpen = app(\App\Services\BusinessHoursService::class)->isOpen($tenant);
        if (!$isOpen) {
            return response()->json([
                'success' => false,
                'error'   => 'closed',
                'message' => 'El negocio está cerrado. No se generó la comanda.',
            ], 422);
        }

        $planSlug = $tenant->plan->slug ?? '';
        $canPersist = $planSlug === 'food-anual' || ($tenant->plan_id ?? 0) >= 3;

        // Generate comanda (always gets SF-XXXX code)
        $comanda = $this->comandaService->generate($tenant, $validated['customer_name'], $validated['modalidad'], $validated['items']);

        // Non-persisting plans: delete the saved file
        if (!$canPersist) {
            $year  = date('Y', strtotime($comanda['date']));
            $month = date('m', strtotime($comanda['date']));
            $path  = "tenants/{$tenant->id}/comandas/{$year}/{$month}/{$comanda['id']}.json";
            \Illuminate\Support\Facades\Storage::disk('local')->delete($path);
        }

        // Build WhatsApp message
        $lines   = [];
        $lines[] = "🍽 Comanda {$comanda['id']}";
        $lines[] = '';

        foreach ($comanda['items'] as $item) {
            $total   = number_format($item['qty'] * $item['precio'], 2, ',', '.');
            $lines[] = "• {$item['nombre']} x{$item['qty']} — REF {$total}";
        }

        $lines[] = '';
        $lines[] = 'Total: REF ' . number_format($comanda['total'], 2, ',', '.');
        $lines[] = '';
        $lines[] = "Nombre: {$comanda['customer_name']}";

        $modalidadLabels = ['sitio' => 'Comer en sitio', 'llevar' => 'Para llevar', 'delivery' => 'Delivery'];
        $lines[] = 'Modalidad: ' . ($modalidadLabels[$comanda['modalidad']] ?? $comanda['modalidad']);

        $message = implode("\n", $lines);
        $activeWhatsapp = $tenant->getActiveWhatsapp()
            ?: $tenant->whatsapp_sales
            ?: $tenant->phone
            ?: '';
        $waNumber = preg_replace('/\D/', '', (string) $activeWhatsapp);
        $waUrl    = 'https://wa.me/' . $waNumber . '?text=' . rawurlencode($message);

        return response()->json([
            'success'      => true,
            'comanda_id'   => $comanda['id'],
            'whatsapp_url' => $waUrl,
        ]);
    }
}
