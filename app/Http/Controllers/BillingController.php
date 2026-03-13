<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BillingController extends Controller
{
    /**
     * Datos de pago de SYNTIweb (los muestra al cliente para que sepa dónde pagar).
     * Centralizados aquí para no dispersar en Blade.
     */
    private const SYNTIWEB_PAYMENT_CHANNELS = [
        'pago_movil' => [
            'label' => 'Pago Móvil',
            'icon'  => 'tabler--device-mobile',
            'details' => [
                'banco'   => 'Banesco (0134)',
                'cedula'  => 'V-28123456',
                'telefono' => '0412-0001234',
            ],
        ],
        'paypal' => [
            'label' => 'PayPal',
            'icon'  => 'tabler--brand-paypal',
            'details' => [
                'email' => 'pagos@syntiweb.com',
            ],
        ],
        'zinli' => [
            'label' => 'Zinli',
            'icon'  => 'tabler--moneybag',
            'details' => [
                'email' => 'pagos@syntiweb.com',
            ],
        ],
    ];

    /**
     * Precios por plan (USD/año).
     */
    private const PLAN_PRICES = [
        1 => 99.00,   // Oportunidad
        2 => 149.00,  // Crecimiento
        3 => 199.00,  // Visión
    ];

    /**
     * Obtener datos de facturación para un tenant (AJAX).
     * Retorna: historial de invoices + canales de pago + estado actual.
     */
    public function getBillingData(Request $request, int $tenantId): JsonResponse
    {
        $tenant = Tenant::with('plan')
            ->where('id', $tenantId)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $invoices = Invoice::where('tenant_id', $tenantId)
            ->orderByDesc('created_at')
            ->limit(20)
            ->get()
            ->map(fn (Invoice $inv) => [
                'id'              => $inv->id,
                'invoice_number'  => $inv->invoice_number,
                'amount_usd'      => $inv->amount_usd,
                'payment_channel' => $inv->payment_channel,
                'channel_label'   => $inv->channel_label,
                'status'          => $inv->status,
                'status_label'    => $inv->status_label,
                'status_color'    => $inv->status_color,
                'period_start'    => $inv->period_start?->format('d/m/Y'),
                'period_end'      => $inv->period_end?->format('d/m/Y'),
                'created_at'      => $inv->created_at?->format('d/m/Y H:i'),
            ]);

        $planPrice = self::PLAN_PRICES[$tenant->plan_id] ?? 99.00;

        return response()->json([
            'success'  => true,
            'channels' => self::SYNTIWEB_PAYMENT_CHANNELS,
            'invoices' => $invoices,
            'plan'     => [
                'name'               => $tenant->plan->name ?? 'Plan',
                'price_usd'          => $planPrice,
                'subscription_ends'  => $tenant->subscription_ends_at?->format('d/m/Y'),
                'days_until_expiry'  => $tenant->daysUntilExpiry(),
                'is_frozen'          => $tenant->isFrozen(),
                'is_expiring_soon'   => $tenant->isExpiringSoon(),
            ],
        ]);
    }

    /**
     * Reportar un pago (el cliente sube comprobante + datos).
     * Crea invoice en estado pending_review.
     */
    public function reportPayment(Request $request, int $tenantId): JsonResponse
    {
        $tenant = Tenant::with('plan')
            ->where('id', $tenantId)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        // Verificar que no haya un pago pendiente de revisión ya
        $hasPending = Invoice::where('tenant_id', $tenantId)
            ->where('status', 'pending_review')
            ->exists();

        if ($hasPending) {
            return response()->json([
                'success' => false,
                'message' => 'Ya tienes un pago en revisión. Espera la confirmación antes de reportar otro.',
            ], 422);
        }

        $validated = $request->validate([
            'payment_channel'   => ['required', 'string', 'in:pago_movil,paypal,zinli'],
            'payment_reference' => ['required', 'string', 'max:100'],
            'payment_date'      => ['required', 'date', 'before_or_equal:today'],
            'receipt'           => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
        ], [
            'payment_channel.required'   => 'Selecciona el método de pago.',
            'payment_channel.in'         => 'Método de pago no válido.',
            'payment_reference.required' => 'Ingresa la referencia del pago.',
            'payment_reference.max'      => 'La referencia no puede exceder 100 caracteres.',
            'payment_date.required'      => 'Ingresa la fecha del pago.',
            'payment_date.before_or_equal' => 'La fecha no puede ser futura.',
            'receipt.required'           => 'Sube el comprobante de pago.',
            'receipt.mimes'              => 'El comprobante debe ser JPG, PNG, WebP o PDF.',
            'receipt.max'                => 'El comprobante no puede exceder 5 MB.',
        ]);

        // Almacenar comprobante
        $receiptPath = $request->file('receipt')->store(
            "tenants/{$tenantId}/receipts",
            'local'
        );

        // Determinar período
        $periodStart = $tenant->subscription_ends_at ?? Carbon::now();
        if ($periodStart->isPast()) {
            $periodStart = Carbon::now();
        }
        $periodEnd = $periodStart->copy()->addYear();

        $planPrice = self::PLAN_PRICES[$tenant->plan_id] ?? 99.00;

        $invoice = Invoice::create([
            'tenant_id'         => $tenantId,
            'invoice_number'    => Invoice::generateNumber(),
            'amount_usd'        => $planPrice,
            'currency'          => 'USD',
            'payment_method'    => $validated['payment_channel'],
            'payment_channel'   => $validated['payment_channel'],
            'payment_reference' => $validated['payment_reference'],
            'payment_date'      => $validated['payment_date'],
            'receipt_path'      => $receiptPath,
            'status'            => 'pending_review',
            'period_start'      => $periodStart->toDateString(),
            'period_end'        => $periodEnd->toDateString(),
        ]);

        Log::info('Payment reported', [
            'invoice_id' => $invoice->id,
            'tenant_id'  => $tenantId,
            'channel'    => $validated['payment_channel'],
            'amount'     => $planPrice,
        ]);

        return response()->json([
            'success' => true,
            'message' => '¡Pago reportado! Lo revisaremos en las próximas 24 horas.',
            'invoice' => [
                'id'             => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'status'         => $invoice->status,
                'status_label'   => $invoice->status_label,
            ],
        ]);
    }

    // ══════════════════════════════════════════════════════════
    //  ADMIN — Cola de revisión de pagos
    // ══════════════════════════════════════════════════════════

    /**
     * Lista pagos pending_review para revisión admin.
     */
    public function adminQueue(Request $request): JsonResponse
    {
        $filter = $request->query('filter', 'pending_review');

        $query = Invoice::with(['tenant:id,name,subdomain,plan_id', 'tenant.plan:id,name'])
            ->orderByDesc('created_at');

        if ($filter === 'pending_review') {
            $query->where('status', 'pending_review');
        } elseif ($filter === 'reviewed') {
            $query->whereIn('status', ['paid', 'rejected']);
        }

        $invoices = $query->limit(50)->get()->map(fn (Invoice $inv) => [
            'id'                => $inv->id,
            'invoice_number'    => $inv->invoice_number,
            'amount_usd'        => $inv->amount_usd,
            'payment_channel'   => $inv->payment_channel,
            'channel_label'     => $inv->channel_label,
            'payment_reference' => $inv->payment_reference,
            'payment_date'      => $inv->payment_date?->format('d/m/Y'),
            'status'            => $inv->status,
            'status_label'      => $inv->status_label,
            'status_color'      => $inv->status_color,
            'receipt_path'      => $inv->receipt_path,
            'admin_notes'       => $inv->admin_notes,
            'period_start'      => $inv->period_start?->format('d/m/Y'),
            'period_end'        => $inv->period_end?->format('d/m/Y'),
            'created_at'        => $inv->created_at?->format('d/m/Y H:i'),
            'reviewed_at'       => $inv->reviewed_at?->format('d/m/Y H:i'),
            'tenant'            => [
                'id'        => $inv->tenant->id,
                'name'      => $inv->tenant->name,
                'subdomain' => $inv->tenant->subdomain,
                'plan'      => $inv->tenant->plan->name ?? 'N/A',
            ],
        ]);

        $pendingCount = Invoice::where('status', 'pending_review')->count();

        return response()->json([
            'success'       => true,
            'invoices'      => $invoices,
            'pending_count' => $pendingCount,
            'filter'        => $filter,
        ]);
    }

    /**
     * Aprobar pago — marca paid + activa tenant.
     */
    public function approvePayment(Request $request, int $invoiceId): JsonResponse
    {
        $invoice = Invoice::with('tenant')->findOrFail($invoiceId);

        if ($invoice->status !== 'pending_review') {
            return response()->json([
                'success' => false,
                'message' => 'Esta factura ya fue procesada.',
            ], 422);
        }

        $validated = $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $invoice->update([
            'status'      => 'paid',
            'admin_notes' => $validated['admin_notes'] ?? null,
            'reviewed_at' => Carbon::now(),
            'reviewed_by' => $request->user()->id,
        ]);

        // Activar tenant
        $tenant = $invoice->tenant;
        $tenant->update([
            'status'              => 'active',
            'subscription_ends_at' => $invoice->period_end,
        ]);

        Log::info('Payment approved', [
            'invoice_id' => $invoice->id,
            'tenant_id'  => $tenant->id,
            'admin_id'   => $request->user()->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Pago aprobado. {$tenant->name} activo hasta {$invoice->period_end->format('d/m/Y')}.",
        ]);
    }

    /**
     * Rechazar pago — marca rejected con motivo.
     */
    public function rejectPayment(Request $request, int $invoiceId): JsonResponse
    {
        $invoice = Invoice::with('tenant')->findOrFail($invoiceId);

        if ($invoice->status !== 'pending_review') {
            return response()->json([
                'success' => false,
                'message' => 'Esta factura ya fue procesada.',
            ], 422);
        }

        $validated = $request->validate([
            'admin_notes' => ['required', 'string', 'max:500'],
        ], [
            'admin_notes.required' => 'Debes indicar el motivo del rechazo.',
        ]);

        $invoice->update([
            'status'      => 'rejected',
            'admin_notes' => $validated['admin_notes'],
            'reviewed_at' => Carbon::now(),
            'reviewed_by' => $request->user()->id,
        ]);

        Log::info('Payment rejected', [
            'invoice_id' => $invoice->id,
            'tenant_id'  => $invoice->tenant_id,
            'admin_id'   => $request->user()->id,
            'reason'     => $validated['admin_notes'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pago rechazado. El cliente podrá enviar otro reporte.',
        ]);
    }

    /**
     * Ver/descargar comprobante de pago (admin).
     */
    public function viewReceipt(Request $request, int $invoiceId): StreamedResponse
    {
        if (!$request->user()?->is_admin) {
            abort(403, 'Acceso restringido a administradores.');
        }

        $invoice = Invoice::findOrFail($invoiceId);

        if (!$invoice->receipt_path || !Storage::disk('local')->exists($invoice->receipt_path)) {
            abort(404, 'Comprobante no encontrado.');
        }

        $mimeType = Storage::disk('local')->mimeType($invoice->receipt_path);

        return Storage::disk('local')->response($invoice->receipt_path, null, [
            'Content-Type' => $mimeType,
        ]);
    }
}
