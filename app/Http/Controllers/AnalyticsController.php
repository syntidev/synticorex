<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\AnalyticsEvent;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AnalyticsController extends Controller
{
    /**
     * Track analytics events from frontend (público, sin autenticación)
     * 
     * Route: POST /api/analytics/track
     * Rate limit: 100 eventos/minuto por tenant
     */
    public function track(Request $request): JsonResponse
    {
        try {
            // Validar datos
            $validated = $request->validate([
                'tenant_id' => 'required|integer|exists:tenants,id',
                'event_type' => 'required|string|in:pageview,click_whatsapp,click_call,click_toggle_currency,time_on_page,qr_scan',
                'metadata' => 'nullable|array',
            ]);

            $tenantId = $validated['tenant_id'];
            $eventType = $validated['event_type'];

            // Rate limiting: máx 100 eventos/minuto por tenant
            $rateLimitKey = "analytics_rate_limit:{$tenantId}";
            $currentCount = Cache::get($rateLimitKey, 0);

            if ($currentCount >= 100) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rate limit exceeded'
                ], 429);
            }

            // Incrementar contador de rate limit
            Cache::put($rateLimitKey, $currentCount + 1, now()->addMinute());

            // Hash de IP (no guardar IP completa por privacidad)
            $ipHash = hash('sha256', $request->ip() . config('app.key'));

            // Timestamp actual
            $now = now();

            // Crear evento
            AnalyticsEvent::create([
                'tenant_id' => $tenantId,
                'event_type' => $eventType,
                'reference_type' => null,
                'reference_id' => null,
                'user_ip' => substr($ipHash, 0, 45), // Primeros 45 chars del hash
                'user_agent' => $request->userAgent(),
                'referer' => $request->header('referer'),
                'event_date' => $now->toDateString(),
                'event_hour' => (int) $now->format('H'),
            ]);

            return response()->json(['success' => true]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid data',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Analytics tracking failed', [
                'error' => $e->getMessage(),
                'tenant_id' => $request->input('tenant_id'),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Tracking failed'
            ], 500);
        }
    }

    /**
     * Get analytics data for dashboard
     * 
     * Route: GET /tenant/{tenantId}/analytics
     */
    public function getData(int $tenantId): JsonResponse
    {
        try {
            $tenant = Tenant::findOrFail($tenantId);

            // Fechas
            $today = now('America/Caracas')->toDateString();
            $weekAgo = now('America/Caracas')->subDays(6)->toDateString();

            // Visitantes únicos hoy (COUNT DISTINCT user_ip)
            $visitorsToday = AnalyticsEvent::where('tenant_id', $tenantId)
                ->where('event_date', $today)
                ->where('event_type', 'pageview')
                ->selectRaw('COUNT(DISTINCT user_ip) as uv')->value('uv') ?? 0;

            // Visitantes únicos esta semana
            $visitorsWeek = AnalyticsEvent::where('tenant_id', $tenantId)
                ->whereBetween('event_date', [$weekAgo, $today])
                ->where('event_type', 'pageview')
                ->selectRaw('COUNT(DISTINCT user_ip) as uv')->value('uv') ?? 0;

            // Clics WhatsApp
            $whatsappClicks = AnalyticsEvent::where('tenant_id', $tenantId)
                ->where('event_type', 'click_whatsapp')
                ->whereBetween('event_date', [$weekAgo, $today])
                ->count();

            // Clics Llamada
            $callClicks = AnalyticsEvent::where('tenant_id', $tenantId)
                ->where('event_type', 'click_call')
                ->whereBetween('event_date', [$weekAgo, $today])
                ->count();

            // Clics Toggle Moneda
            $currencyToggles = AnalyticsEvent::where('tenant_id', $tenantId)
                ->where('event_type', 'click_toggle_currency')
                ->whereBetween('event_date', [$weekAgo, $today])
                ->count();

            // Escaneos QR
            $qrScans = AnalyticsEvent::where('tenant_id', $tenantId)
                ->where('event_type', 'qr_scan')
                ->whereBetween('event_date', [$weekAgo, $today])
                ->count();

            // Tiempo promedio en página (eventos time_on_page)
            $avgTimeOnPage = AnalyticsEvent::where('tenant_id', $tenantId)
                ->where('event_type', 'time_on_page')
                ->whereBetween('event_date', [$weekAgo, $today])
                ->count() * 30; // Cada evento = 30 segundos

            // Gráfico últimos 7 días (visitantes únicos por día)
            $last7Days = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = now('America/Caracas')->subDays($i)->toDateString();
                $visitors = AnalyticsEvent::where('tenant_id', $tenantId)
                    ->where('event_date', $date)
                    ->where('event_type', 'pageview')
                    ->selectRaw('COUNT(DISTINCT user_ip) as uv')->value('uv') ?? 0;

                $last7Days[] = [
                    'date' => $date,
                    'visitors' => $visitors,
                ];
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'visitors_today' => $visitorsToday,
                    'visitors_week' => $visitorsWeek,
                    'whatsapp_clicks' => $whatsappClicks,
                    'call_clicks' => $callClicks,
                    'currency_toggles' => $currencyToggles,
                    'qr_scans' => $qrScans,
                    'avg_time_on_page' => round($avgTimeOnPage ?? 0),
                    'last_7_days' => $last7Days,
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('Analytics data fetch failed', [
                'error' => $e->getMessage(),
                'tenant_id' => $tenantId,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch analytics data'
            ], 500);
        }
    }
	public function getToday(int $tenantId): JsonResponse
{
    try {
        $today = now('America/Caracas')->toDateString();

        $visitors = AnalyticsEvent::where('tenant_id', $tenantId)
            ->where('event_date', $today)
            ->where('event_type', 'pageview')
            ->selectRaw('COUNT(DISTINCT user_ip) as uv')->value('uv') ?? 0;

        $whatsapp = AnalyticsEvent::where('tenant_id', $tenantId)
            ->where('event_date', $today)
            ->where('event_type', 'click_whatsapp')->count();

        $qr = AnalyticsEvent::where('tenant_id', $tenantId)
            ->where('event_date', $today)
            ->where('event_type', 'qr_scan')->count();

        $pageViews = AnalyticsEvent::where('tenant_id', $tenantId)
            ->where('event_date', $today)
            ->where('event_type', 'pageview')->count();

        return response()->json([
            'success'         => true,
            'visitors_today'  => $visitors,
            'whatsapp_clicks' => $whatsapp,
            'qr_scans'        => $qr,
            'page_views'      => $pageViews,
        ]);

    } catch (\Exception $e) {
        return response()->json(['success' => false], 500);
    }
}
}
