<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\AnalyticsEvent;
use App\Models\Tenant;
use App\Notifications\TenantAnalyticsReportNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class SendAnalyticsReports extends Command
{
    protected $signature = 'reports:send {--period=weekly} {--tenant=}';

    protected $description = 'Envía reportes de analytics a tenants según su plan';

    public function handle(): int
    {
        $period = $this->option('period');
        $tenantId = $this->option('tenant');

        $query = Tenant::where('status', 'active')->with('plan');

        if ($tenantId) {
            $query->where('id', (int) $tenantId);
        }

        $tenants = $query->get();
        $sent = 0;

        foreach ($tenants as $tenant) {
            $reportType = $this->determineReportType($tenant);

            if ($reportType === null) {
                continue;
            }

            if ($period === 'weekly' && $reportType['frequency'] !== 'weekly') {
                continue;
            }

            if ($period === 'monthly' && $reportType['frequency'] !== 'monthly') {
                continue;
            }

            // For single tenant test, bypass frequency check
            if ($tenantId) {
                $reportType['frequency'] = $period;
            }

            $days = $reportType['frequency'] === 'weekly' ? 7 : 30;
            $data = $this->buildReportData($tenant, $days, $reportType);

            $email = $tenant->email ?? $tenant->user?->email;
            if (!$email) {
                continue;
            }

            Notification::route('mail', $email)
                ->notify(new TenantAnalyticsReportNotification($tenant, $data, $period));

            $sent++;
            $this->line("Reporte enviado a: {$tenant->business_name} ({$email})");
        }

        $this->info("Reportes enviados: {$sent}");

        return self::SUCCESS;
    }

    private function determineReportType(Tenant $tenant): ?array
    {
        $slug = $tenant->plan?->slug ?? '';

        return match (true) {
            // Studio Visión / top tier → weekly, todo + PDF
            in_array($slug, ['vision', 'studio-vision', 'food-anual', 'cat-anual'], true) => [
                'frequency' => 'weekly',
                'include_growth' => true,
                'include_vision' => true,
                'attach_pdf' => true,
            ],
            // Crecimiento / semestral → weekly, totales + hora pico + fuentes
            in_array($slug, ['crecimiento', 'studio-crecimiento', 'food-semestral', 'cat-semestral'], true) => [
                'frequency' => 'weekly',
                'include_growth' => true,
                'include_vision' => false,
                'attach_pdf' => false,
            ],
            // Oportunidad studio → monthly, totales + hora pico
            in_array($slug, ['oportunidad', 'studio-oportunidad'], true) => [
                'frequency' => 'monthly',
                'include_growth' => true,
                'include_vision' => false,
                'attach_pdf' => false,
            ],
            // Básico food/cat → monthly, solo totales
            in_array($slug, ['food-basico', 'cat-basico'], true) => [
                'frequency' => 'monthly',
                'include_growth' => false,
                'include_vision' => false,
                'attach_pdf' => false,
            ],
            default => null,
        };
    }

    private function buildReportData(Tenant $tenant, int $days, array $reportType): array
    {
        $since = now()->subDays($days)->toDateString();
        $previousSince = now()->subDays($days * 2)->toDateString();
        $previousEnd = now()->subDays($days)->toDateString();

        $visitors = AnalyticsEvent::where('tenant_id', $tenant->id)
            ->where('event_type', 'pageview')
            ->where('event_date', '>=', $since)
            ->distinct('user_ip')
            ->count('user_ip');

        $previousVisitors = AnalyticsEvent::where('tenant_id', $tenant->id)
            ->where('event_type', 'pageview')
            ->whereBetween('event_date', [$previousSince, $previousEnd])
            ->distinct('user_ip')
            ->count('user_ip');

        $change = $previousVisitors > 0
            ? (int) round(($visitors - $previousVisitors) / $previousVisitors * 100)
            : 0;

        $whatsappClicks = AnalyticsEvent::where('tenant_id', $tenant->id)
            ->where('event_type', 'click_whatsapp')
            ->where('event_date', '>=', $since)
            ->count();

        $qrScans = AnalyticsEvent::where('tenant_id', $tenant->id)
            ->where('event_type', 'qr_scan')
            ->where('event_date', '>=', $since)
            ->count();

        $data = [
            'visitors' => $visitors,
            'change' => $change,
            'whatsapp_clicks' => $whatsappClicks,
            'qr_scans' => $qrScans,
            'include_growth' => $reportType['include_growth'],
            'include_vision' => $reportType['include_vision'],
            'attach_pdf' => $reportType['attach_pdf'],
        ];

        if ($reportType['include_growth']) {
            $peakHour = AnalyticsEvent::where('tenant_id', $tenant->id)
                ->where('event_type', 'pageview')
                ->where('event_date', '>=', $since)
                ->select('event_hour', DB::raw('COUNT(*) as total'))
                ->groupBy('event_hour')
                ->orderByDesc('total')
                ->value('event_hour') ?? 0;

            $topSource = $this->getTopSource($tenant->id, $since);

            $data['peak_hour'] = $peakHour;
            $data['top_source'] = $topSource;
        }

        if ($reportType['include_vision']) {
            $data['top_device'] = $this->getTopDevice($tenant->id, $since);
            $data['top_os'] = $this->getTopOS($tenant->id, $since);
            $data['trend'] = $this->getTrend($tenant->id, $days);
        }

        return $data;
    }

    private function getTopSource(int $tenantId, string $since): string
    {
        $rows = AnalyticsEvent::where('tenant_id', $tenantId)
            ->where('event_type', 'pageview')
            ->where('event_date', '>=', $since)
            ->select('referer', DB::raw('COUNT(*) as total'))
            ->groupBy('referer')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $sources = [];
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

        return array_key_first($sources) ?? 'Directo';
    }

    private function getTopDevice(int $tenantId, string $since): string
    {
        $rows = AnalyticsEvent::where('tenant_id', $tenantId)
            ->where('event_type', 'pageview')
            ->where('event_date', '>=', $since)
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

        arsort($devices);

        return array_key_first($devices) ?? 'Desktop';
    }

    private function getTopOS(int $tenantId, string $since): string
    {
        $rows = AnalyticsEvent::where('tenant_id', $tenantId)
            ->where('event_type', 'pageview')
            ->where('event_date', '>=', $since)
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

        return array_key_first($osList) ?? 'Otro';
    }

    private function getTrend(int $tenantId, int $days): array
    {
        $results = AnalyticsEvent::where('tenant_id', $tenantId)
            ->where('event_type', 'pageview')
            ->where('event_date', '>=', now()->subDays($days)->toDateString())
            ->select('event_date', DB::raw('COUNT(DISTINCT user_ip) as visitors'))
            ->groupBy('event_date')
            ->orderBy('event_date')
            ->get();

        $trend = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $row = $results->firstWhere('event_date', $date);
            $trend[$date] = $row ? $row->visitors : 0;
        }

        return $trend;
    }
}
