<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class HealthCheckService
{
    /**
     * Execute all health checks and return array of results.
     *
     * @return array<int, array{key: string, label: string, status: string, latency_ms: int|null, message: string}>
     */
    public function checkAll(): array
    {
        return [
            $this->checkDatabase(),
            $this->checkBcvApi(),
            $this->checkAnthropicApi(),
            $this->checkGeminiApi(),
            $this->checkStorageDisk(),
            $this->checkQueueJobs(),
            $this->checkFailedJobs(),
            $this->checkTenantsExpiringSoon(),
            $this->checkDiskSpace(),
            $this->checkLogErrors24h(),
        ];
    }

    private function checkDatabase(): array
    {
        try {
            $start = microtime(true);
            DB::select('SELECT 1');
            $latency = (int) round((microtime(true) - $start) * 1000);

            $status = match (true) {
                $latency < 100 => 'ok',
                $latency < 500 => 'warning',
                default        => 'error',
            };

            return [
                'key'        => 'database',
                'label'      => 'Base de datos MySQL',
                'status'     => $status,
                'latency_ms' => $latency,
                'message'    => "Latencia: {$latency}ms",
            ];
        } catch (Throwable $e) {
            return [
                'key'        => 'database',
                'label'      => 'Base de datos MySQL',
                'status'     => 'error',
                'latency_ms' => null,
                'message'    => 'Error: ' . $e->getMessage(),
            ];
        }
    }

    private function checkBcvApi(): array
    {
        try {
            $start = microtime(true);
            $response = Http::timeout(5)->acceptJson()->get('https://pydolarve.org/api/v1/dollar?page=bcv');
            $latency = (int) round((microtime(true) - $start) * 1000);

            if (!$response->successful()) {
                return [
                    'key'        => 'bcv_api',
                    'label'      => 'API Tasa BCV',
                    'status'     => 'error',
                    'latency_ms' => $latency,
                    'message'    => 'HTTP ' . $response->status(),
                ];
            }

            $data = $response->json();
            $rate = $data['monitors']['bcv']['price'] ?? $data['price'] ?? null;

            return [
                'key'        => 'bcv_api',
                'label'      => 'API Tasa BCV',
                'status'     => 'ok',
                'latency_ms' => $latency,
                'message'    => $rate ? "Tasa actual: Bs. {$rate}" : 'Respuesta ok, tasa no disponible',
            ];
        } catch (Throwable $e) {
            return [
                'key'        => 'bcv_api',
                'label'      => 'API Tasa BCV',
                'status'     => 'error',
                'latency_ms' => null,
                'message'    => 'Error: ' . $e->getMessage(),
            ];
        }
    }

    private function checkAnthropicApi(): array
    {
        $apiKey = config('services.anthropic.key');
        if (empty($apiKey)) {
            return [
                'key'        => 'anthropic_api',
                'label'      => 'Anthropic API',
                'status'     => 'warning',
                'latency_ms' => null,
                'message'    => 'ANTHROPIC_API_KEY no configurada',
            ];
        }

        try {
            $start = microtime(true);
            $response = Http::timeout(10)
                ->withHeaders([
                    'x-api-key'         => $apiKey,
                    'anthropic-version'  => '2023-06-01',
                    'content-type'       => 'application/json',
                ])
                ->post('https://api.anthropic.com/v1/messages', [
                    'model'      => 'claude-haiku-4-5-20251001',
                    'max_tokens' => 1,
                    'messages'   => [['role' => 'user', 'content' => 'ping']],
                ]);
            $latency = (int) round((microtime(true) - $start) * 1000);

            return [
                'key'        => 'anthropic_api',
                'label'      => 'Anthropic API',
                'status'     => $response->successful() ? 'ok' : 'error',
                'latency_ms' => $latency,
                'message'    => $response->successful() ? "OK ({$latency}ms)" : 'HTTP ' . $response->status(),
            ];
        } catch (Throwable $e) {
            return [
                'key'        => 'anthropic_api',
                'label'      => 'Anthropic API',
                'status'     => 'error',
                'latency_ms' => null,
                'message'    => 'Error: ' . $e->getMessage(),
            ];
        }
    }

    private function checkGeminiApi(): array
    {
        $apiKey = config('services.gemini.key', env('GEMINI_API_KEY'));
        if (empty($apiKey)) {
            return [
                'key'        => 'gemini_api',
                'label'      => 'Gemini API',
                'status'     => 'skip',
                'latency_ms' => null,
                'message'    => 'GEMINI_API_KEY no configurada — omitido',
            ];
        }

        try {
            $start = microtime(true);
            $response = Http::timeout(5)->get("https://generativelanguage.googleapis.com/v1/models?key={$apiKey}");
            $latency = (int) round((microtime(true) - $start) * 1000);

            return [
                'key'        => 'gemini_api',
                'label'      => 'Gemini API',
                'status'     => $response->successful() ? 'ok' : 'error',
                'latency_ms' => $latency,
                'message'    => $response->successful() ? "OK ({$latency}ms)" : 'HTTP ' . $response->status(),
            ];
        } catch (Throwable $e) {
            return [
                'key'        => 'gemini_api',
                'label'      => 'Gemini API',
                'status'     => 'error',
                'latency_ms' => null,
                'message'    => 'Error: ' . $e->getMessage(),
            ];
        }
    }

    private function checkStorageDisk(): array
    {
        try {
            Storage::disk('public')->put('health-check.txt', now()->toString());
            Storage::disk('public')->delete('health-check.txt');

            return [
                'key'        => 'storage_disk',
                'label'      => 'Storage (public)',
                'status'     => 'ok',
                'latency_ms' => null,
                'message'    => 'Escritura/lectura OK',
            ];
        } catch (Throwable $e) {
            return [
                'key'        => 'storage_disk',
                'label'      => 'Storage (public)',
                'status'     => 'error',
                'latency_ms' => null,
                'message'    => 'Error: ' . $e->getMessage(),
            ];
        }
    }

    private function checkQueueJobs(): array
    {
        try {
            $pending = DB::table('jobs')->count();
            $status = match (true) {
                $pending < 10 => 'ok',
                $pending < 50 => 'warning',
                default       => 'error',
            };

            return [
                'key'        => 'queue_jobs',
                'label'      => 'Jobs en cola',
                'status'     => $status,
                'latency_ms' => null,
                'message'    => "{$pending} jobs en cola",
            ];
        } catch (Throwable $e) {
            return [
                'key'        => 'queue_jobs',
                'label'      => 'Jobs en cola',
                'status'     => 'warning',
                'latency_ms' => null,
                'message'    => 'Tabla jobs no disponible',
            ];
        }
    }

    private function checkFailedJobs(): array
    {
        try {
            $failed = DB::table('failed_jobs')->count();
            $status = match (true) {
                $failed === 0 => 'ok',
                $failed < 5   => 'warning',
                default       => 'error',
            };

            return [
                'key'        => 'failed_jobs',
                'label'      => 'Jobs fallidos',
                'status'     => $status,
                'latency_ms' => null,
                'message'    => "{$failed} jobs fallidos",
            ];
        } catch (Throwable $e) {
            return [
                'key'        => 'failed_jobs',
                'label'      => 'Jobs fallidos',
                'status'     => 'warning',
                'latency_ms' => null,
                'message'    => 'Tabla failed_jobs no disponible',
            ];
        }
    }

    private function checkTenantsExpiringSoon(): array
    {
        $count = Tenant::where('status', 'active')
            ->whereBetween('subscription_ends_at', [now(), now()->addDays(7)])
            ->count();

        return [
            'key'        => 'tenants_expiring_soon',
            'label'      => 'Tenants por vencer',
            'status'     => $count === 0 ? 'ok' : 'warning',
            'latency_ms' => null,
            'message'    => "{$count} tenants vencen en 7 días",
        ];
    }

    private function checkDiskSpace(): array
    {
        $path = PHP_OS_FAMILY === 'Windows' ? 'C:\\' : '/';
        $free = disk_free_space($path);
        $total = disk_total_space($path);

        if ($free === false || $total === false || $total === 0) {
            return [
                'key'        => 'disk_space',
                'label'      => 'Espacio en disco',
                'status'     => 'warning',
                'latency_ms' => null,
                'message'    => 'No se pudo obtener info del disco',
            ];
        }

        $usedPct = (int) round((($total - $free) / $total) * 100);
        $status = match (true) {
            $usedPct < 70 => 'ok',
            $usedPct < 85 => 'warning',
            default       => 'error',
        };

        return [
            'key'        => 'disk_space',
            'label'      => 'Espacio en disco',
            'status'     => $status,
            'latency_ms' => null,
            'message'    => "{$usedPct}% usado",
        ];
    }

    private function checkLogErrors24h(): array
    {
        $logPath = storage_path('logs/laravel.log');
        if (!file_exists($logPath)) {
            return [
                'key'        => 'log_errors_24h',
                'label'      => 'Errores (24h)',
                'status'     => 'ok',
                'latency_ms' => null,
                'message'    => 'Sin archivo de log',
            ];
        }

        $count = 0;
        $cutoff = now()->subDay();
        $handle = fopen($logPath, 'r');

        if ($handle) {
            // Read from end — scan last 10000 lines max for performance
            $lines = [];
            while (($line = fgets($handle)) !== false) {
                $lines[] = $line;
                if (count($lines) > 10000) {
                    array_shift($lines);
                }
            }
            fclose($handle);

            foreach ($lines as $line) {
                if (stripos($line, 'ERROR') !== false) {
                    // Try to extract date from log line
                    if (preg_match('/^\[(\d{4}-\d{2}-\d{2}[T ]\d{2}:\d{2}:\d{2})/', $line, $m)) {
                        try {
                            $lineDate = \Carbon\Carbon::parse($m[1]);
                            if ($lineDate->gte($cutoff)) {
                                $count++;
                            }
                        } catch (Throwable) {
                            // Skip unparseable dates
                        }
                    }
                }
            }
        }

        $status = match (true) {
            $count === 0 => 'ok',
            $count < 10  => 'warning',
            default      => 'error',
        };

        return [
            'key'        => 'log_errors_24h',
            'label'      => 'Errores (24h)',
            'status'     => $status,
            'latency_ms' => null,
            'message'    => "{$count} errores en últimas 24h",
        ];
    }
}
