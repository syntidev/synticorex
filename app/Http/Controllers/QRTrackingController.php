<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\AnalyticsEvent;
use App\Models\Tenant;
use App\Services\QRService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class QRTrackingController extends Controller
{
    public function __construct(
        private readonly QRService $qrService
    ) {}

    /**
     * Maneja el shortlink de tracking y redirige a la landing del tenant
     * Registra evento qr_scan en analytics_events
     * 
     * Route: GET /t/{tenantId}/{code}
     */
    public function handleShortlink(int $tenantId, string $code): RedirectResponse
    {
        // Verificar que el código sea válido
        if (!$this->qrService->verifyUniqueCode($tenantId, $code)) {
            abort(404, 'Invalid QR code');
        }

        // Buscar tenant
        $tenant = Tenant::find($tenantId);
        if (!$tenant) {
            abort(404, 'Tenant not found');
        }

        // Registrar evento de escaneo QR en analytics_events
        try {
            // Hash de IP (no guardar IP completa por privacidad)
            $ipHash = hash('sha256', request()->ip() . config('app.key'));
            $now = now();
            
            AnalyticsEvent::create([
                'tenant_id' => $tenantId,
                'event_type' => 'qr_scan',
                'reference_type' => null,
                'reference_id' => null,
                'user_ip' => substr($ipHash, 0, 45), // Primeros 45 chars del hash
                'user_agent' => request()->userAgent(),
                'referer' => request()->header('referer'),
                'event_date' => $now->toDateString(),
                'event_hour' => (int) $now->format('H'),
            ]);
        } catch (\Exception $e) {
            // Log error pero continuar con la redirección
            \Log::error('Error registrando QR scan event', [
                'tenant_id' => $tenantId,
                'error' => $e->getMessage(),
            ]);
        }

        // Redirigir a la landing del tenant
        $landingUrl = url('/' . $tenant->subdomain);
        return redirect($landingUrl);
    }

    /**
     * Descarga el QR como PNG
     * 
     * Route: GET /tenant/{id}/qr/download
     */
    public function downloadQR(int $id): Response
    {
        $tenant = Tenant::findOrFail($id);
        
        // Generar QR PNG
        $qrPng = $this->qrService->generateQRPNG($tenant->id, 300);
        
        // Nombre del archivo
        $filename = $tenant->subdomain . '_qr.png';
        
        return response($qrPng, 200)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
