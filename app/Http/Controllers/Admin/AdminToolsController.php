<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;

class AdminToolsController extends Controller
{
    public function clearCache(): JsonResponse
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');

        return response()->json(['message' => 'Cache limpiado correctamente']);
    }

    public function clearLogs(): JsonResponse
    {
        $logPath = storage_path('logs/laravel.log');
        if (file_exists($logPath)) {
            file_put_contents($logPath, '');
        }

        return response()->json(['message' => 'Log vaciado']);
    }

    public function restartQueue(): JsonResponse
    {
        Artisan::call('queue:restart');

        return response()->json(['message' => 'Queue reiniciado']);
    }

    public function runMigrations(): JsonResponse
    {
        Artisan::call('migrate', ['--force' => true]);
        $output = Artisan::output();

        return response()->json(['message' => $output ?: 'Migraciones ejecutadas']);
    }

    public function suspendExpiredNow(): JsonResponse
    {
        Artisan::call('tenants:suspend-expired');
        $output = Artisan::output();

        return response()->json(['message' => $output ?: 'Tenants expirados suspendidos']);
    }

    public function reindexAiDocs(): JsonResponse
    {
        Artisan::call('ai:index-docs');
        $output = Artisan::output();

        return response()->json(['message' => $output ?: 'Docs reindexados']);
    }

    public function getDiskUsage(): JsonResponse
    {
        $root = PHP_OS_FAMILY === 'Windows' ? 'C:\\' : '/';
        $free = disk_free_space($root);
        $total = disk_total_space($root);
        $used = $total - $free;

        $storagePath = storage_path('app/public');
        $storageUsed = 0;
        if (is_dir($storagePath)) {
            $storageUsed = $this->getDirectorySize($storagePath);
        }

        return response()->json([
            'disk_total' => $this->formatBytes($total),
            'disk_free' => $this->formatBytes($free),
            'disk_used' => $this->formatBytes($used),
            'disk_used_percent' => round($used / $total * 100, 1),
            'storage_used' => $this->formatBytes($storageUsed),
        ]);
    }

    public function getLogTail(): JsonResponse
    {
        $logPath = storage_path('logs/laravel.log');
        if (!file_exists($logPath)) {
            return response()->json(['lines' => '']);
        }

        $lines = [];
        $file = new \SplFileObject($logPath, 'r');
        $file->seek(PHP_INT_MAX);
        $totalLines = $file->key();

        $start = max(0, $totalLines - 200);
        $file->seek($start);

        while (!$file->eof()) {
            $line = $file->fgets();
            if (str_contains($line, 'ERROR') || str_contains($line, 'WARNING')) {
                $lines[] = $line;
            }
        }

        $lines = array_slice($lines, -50);

        return response()->json(['lines' => implode('', $lines)]);
    }

    public function sendTestReport(int $tenantId): JsonResponse
    {
        Artisan::call('reports:send', ['--period' => 'weekly', '--tenant' => $tenantId]);
        $output = Artisan::output();

        return response()->json(['message' => $output ?: 'Reporte de prueba enviado']);
    }

    private function getDirectorySize(string $path): int
    {
        $size = 0;
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS)
        );
        foreach ($iterator as $file) {
            $size += $file->getSize();
        }

        return $size;
    }

    private function formatBytes(float $bytes): string
    {
        if ($bytes >= 1073741824) {
            return round($bytes / 1073741824, 2) . ' GB';
        }

        return round($bytes / 1048576, 2) . ' MB';
    }
}
