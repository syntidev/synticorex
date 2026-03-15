<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AnalyticsEvent;
use Flowframe\Trend\Trend;
use Illuminate\Support\Facades\DB;

class PlatformAnalyticsService
{
    public function getSummary(string $period = '7d'): array
    {
        $days = match ($period) {
            '7d' => 7,
            '30d' => 30,
            '90d' => 90,
            default => 30,
        };

        $since = now()->subDays($days)->toDateString();

        $pageviews = AnalyticsEvent::where('event_type', 'pageview')
            ->where('event_date', '>=', $since)
            ->count();

        $uniqueVisitors = AnalyticsEvent::where('event_type', 'pageview')
            ->where('event_date', '>=', $since)
            ->distinct('user_ip')
            ->count('user_ip');

        $whatsappClicks = AnalyticsEvent::where('event_type', 'click_whatsapp')
            ->where('event_date', '>=', $since)
            ->count();

        $qrScans = AnalyticsEvent::where('event_type', 'qr_scan')
            ->where('event_date', '>=', $since)
            ->count();

        $topTenants = AnalyticsEvent::where('event_type', 'pageview')
            ->where('event_date', '>=', $since)
            ->select('tenant_id', DB::raw('COUNT(*) as total'))
            ->groupBy('tenant_id')
            ->orderByDesc('total')
            ->limit(5)
            ->with('tenant:id,business_name,subdomain')
            ->get()
            ->map(fn ($row) => [
                'tenant_id' => $row->tenant_id,
                'name' => $row->tenant?->business_name ?? '—',
                'subdomain' => $row->tenant?->subdomain ?? '—',
                'total' => $row->total,
            ])
            ->toArray();

        return [
            'pageviews' => $pageviews,
            'unique_visitors' => $uniqueVisitors,
            'whatsapp_clicks' => $whatsappClicks,
            'qr_scans' => $qrScans,
            'top_tenants' => $topTenants,
        ];
    }

    public function getTrafficSources(): array
    {
        $rows = AnalyticsEvent::where('event_type', 'pageview')
            ->select('referer', DB::raw('COUNT(*) as total'))
            ->groupBy('referer')
            ->orderByDesc('total')
            ->get();

        $sources = [];
        $grandTotal = $rows->sum('total');

        foreach ($rows as $row) {
            $referer = $row->referer;
            $source = match (true) {
                $referer === null || $referer === '' => 'Directo',
                str_contains($referer, 'google') => 'Google',
                str_contains($referer, 'instagram') || str_contains($referer, 't.co') => 'Redes Sociales',
                str_contains($referer, 'whatsapp') => 'WhatsApp',
                default => parse_url($referer, PHP_URL_HOST) ?: 'Otro',
            };

            $sources[$source] = ($sources[$source] ?? 0) + $row->total;
        }

        arsort($sources);

        $result = [];
        foreach ($sources as $name => $count) {
            $result[] = [
                'source' => $name,
                'count' => $count,
                'percentage' => $grandTotal > 0 ? round($count / $grandTotal * 100, 1) : 0,
            ];
        }

        return $result;
    }

    public function getDeviceBreakdown(): array
    {
        $rows = AnalyticsEvent::where('event_type', 'pageview')
            ->select('user_agent', DB::raw('COUNT(*) as total'))
            ->groupBy('user_agent')
            ->get();

        $devices = ['Móvil' => 0, 'Tablet' => 0, 'Desktop' => 0];

        foreach ($rows as $row) {
            $ua = $row->user_agent ?? '';
            $type = match (true) {
                str_contains($ua, 'Mobile') || str_contains($ua, 'Android') || str_contains($ua, 'iPhone') => 'Móvil',
                str_contains($ua, 'iPad') || str_contains($ua, 'Tablet') => 'Tablet',
                default => 'Desktop',
            };
            $devices[$type] += $row->total;
        }

        $grandTotal = array_sum($devices);
        $result = [];
        foreach ($devices as $name => $count) {
            $result[] = [
                'device' => $name,
                'count' => $count,
                'percentage' => $grandTotal > 0 ? round($count / $grandTotal * 100, 1) : 0,
            ];
        }

        return $result;
    }

    public function getPeakHours(): array
    {
        return AnalyticsEvent::where('event_type', 'pageview')
            ->select('event_hour', DB::raw('COUNT(*) as total'))
            ->groupBy('event_hour')
            ->orderByDesc('total')
            ->limit(24)
            ->get()
            ->map(fn ($row) => [
                'hour' => $row->event_hour,
                'count' => $row->total,
            ])
            ->toArray();
    }

    public function getOSBreakdown(): array
    {
        $rows = AnalyticsEvent::where('event_type', 'pageview')
            ->select('user_agent', DB::raw('COUNT(*) as total'))
            ->groupBy('user_agent')
            ->get();

        $osList = [];

        foreach ($rows as $row) {
            $ua = $row->user_agent ?? '';
            $os = match (true) {
                str_contains($ua, 'Android') => 'Android',
                str_contains($ua, 'iPhone') || str_contains($ua, 'iPad') || str_contains($ua, 'iOS') => 'iOS',
                str_contains($ua, 'Windows') => 'Windows',
                str_contains($ua, 'Mac') => 'macOS',
                str_contains($ua, 'Linux') => 'Linux',
                default => 'Otro',
            };
            $osList[$os] = ($osList[$os] ?? 0) + $row->total;
        }

        arsort($osList);

        $grandTotal = array_sum($osList);
        $result = [];
        foreach ($osList as $name => $count) {
            $result[] = [
                'os' => $name,
                'count' => $count,
                'percentage' => $grandTotal > 0 ? round($count / $grandTotal * 100, 1) : 0,
            ];
        }

        return $result;
    }

    public function getTrend(int $days = 30): array
    {
        $trend = Trend::query(AnalyticsEvent::where('event_type', 'pageview'))
            ->between(
                start: now()->subDays($days),
                end: now(),
            )
            ->perDay()
            ->count();

        $result = [];
        foreach ($trend as $item) {
            $result[$item->date] = $item->aggregate;
        }

        return $result;
    }
}
