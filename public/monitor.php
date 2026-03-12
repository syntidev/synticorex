<?php

declare(strict_types=1);

/**
 * SyntiWeb Health Monitor — Standalone diagnostic page
 * Access: http://synticorex.test/monitor.php
 * No Laravel bootstrap required.
 */

// Only allow access from localhost
$allowed = ['127.0.0.1', '::1', 'localhost'];
$clientIp = $_SERVER['REMOTE_ADDR'] ?? '';
if (!in_array($clientIp, $allowed, true)) {
    http_response_code(403);
    exit('Forbidden');
}

$baseUrl = 'http://synticorex.test';
$projectRoot = dirname(__DIR__);
$startTime = microtime(true);

// ── Helper: HTTP check ──────────────────────────────────────────────
function checkUrl(string $url, int $timeout = 5): array
{
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => $timeout,
        CURLOPT_CONNECTTIMEOUT => $timeout,
        CURLOPT_NOBODY => false,
        CURLOPT_HEADER => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT => 'SyntiWeb-Monitor/1.0',
    ]);

    $body = curl_exec($ch);
    $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $totalTime = round((float) curl_getinfo($ch, CURLINFO_TOTAL_TIME) * 1000);
    $error = curl_error($ch);
    curl_close($ch);

    $status = 'ok';
    if ($httpCode === 0) {
        $status = 'down';
    } elseif ($httpCode >= 500) {
        $status = 'error';
    } elseif ($httpCode >= 400) {
        $status = 'warn';
    } elseif ($httpCode >= 300) {
        $status = 'redirect';
    }

    return [
        'url'       => $url,
        'code'      => $httpCode,
        'time_ms'   => $totalTime,
        'status'    => $status,
        'error'     => $error ?: null,
        'size'      => is_string($body) ? strlen($body) : 0,
    ];
}

// ── Routes to check ─────────────────────────────────────────────────
$publicRoutes = [
    ['GET', '/',                  'Marketing Home'],
    ['GET', '/login',             'Login'],
    ['GET', '/register',          'Register'],
    ['GET', '/planes',            'Planes'],
    ['GET', '/studio',            'Marketing Studio'],
    ['GET', '/food',              'Marketing Food'],
    ['GET', '/cat',               'Marketing Cat'],
    ['GET', '/forgot-password',   'Forgot Password'],
];

$authRoutes = [
    ['GET', '/mis-negocios',       'Mis Negocios (auth)'],
    ['GET', '/onboarding',         'Onboarding Selector (auth)'],
    ['GET', '/onboarding/nuevo',   'Onboarding Nuevo (auth)'],
    ['GET', '/onboarding/studio',  'Onboarding Studio (auth)'],
    ['GET', '/onboarding/food',    'Onboarding Food (auth)'],
    ['GET', '/onboarding/cat',     'Onboarding Cat (auth)'],
];

$apiRoutes = [
    ['GET', '/api/dollar-rate',    'Dollar Rate API'],
    ['GET', '/api/euro-rate',      'Euro Rate API'],
    ['GET', '/api/tenants',        'Tenants API'],
];

// ── Run checks ──────────────────────────────────────────────────────
$results = [];

foreach ($publicRoutes as [$method, $path, $label]) {
    $r = checkUrl($baseUrl . $path);
    $r['label'] = $label;
    $r['group'] = 'Public';
    $r['expected'] = 200;
    $results[] = $r;
}

foreach ($authRoutes as [$method, $path, $label]) {
    $r = checkUrl($baseUrl . $path);
    $r['label'] = $label;
    $r['group'] = 'Auth (expect redirect)';
    $r['expected'] = 200; // will redirect to login = 200 after follow
    $results[] = $r;
}

foreach ($apiRoutes as [$method, $path, $label]) {
    $r = checkUrl($baseUrl . $path);
    $r['label'] = $label;
    $r['group'] = 'API';
    $r['expected'] = 200;
    $results[] = $r;
}

// ── PHP / Environment checks ────────────────────────────────────────
$envChecks = [];

// PHP version
$envChecks[] = [
    'label'  => 'PHP Version',
    'value'  => PHP_VERSION,
    'status' => version_compare(PHP_VERSION, '8.2.0', '>=') ? 'ok' : 'error',
];

// Required extensions
foreach (['curl', 'mbstring', 'openssl', 'pdo_mysql', 'gd', 'fileinfo', 'xml'] as $ext) {
    $envChecks[] = [
        'label'  => "ext-{$ext}",
        'value'  => extension_loaded($ext) ? 'Loaded' : 'MISSING',
        'status' => extension_loaded($ext) ? 'ok' : 'error',
    ];
}

// .env exists
$envChecks[] = [
    'label'  => '.env file',
    'value'  => file_exists($projectRoot . '/.env') ? 'Present' : 'MISSING',
    'status' => file_exists($projectRoot . '/.env') ? 'ok' : 'error',
];

// Storage writable
$storageWritable = is_writable($projectRoot . '/storage/logs');
$envChecks[] = [
    'label'  => 'storage/logs writable',
    'value'  => $storageWritable ? 'Yes' : 'NO',
    'status' => $storageWritable ? 'ok' : 'error',
];

// Database
try {
    $envFile = file_get_contents($projectRoot . '/.env');
    preg_match('/^DB_HOST=(.+)$/m', $envFile, $m);
    $dbHost = trim($m[1] ?? '127.0.0.1');
    preg_match('/^DB_PORT=(.+)$/m', $envFile, $m);
    $dbPort = (int) trim($m[1] ?? '3306');
    preg_match('/^DB_DATABASE=(.+)$/m', $envFile, $m);
    $dbName = trim($m[1] ?? '');
    preg_match('/^DB_USERNAME=(.+)$/m', $envFile, $m);
    $dbUser = trim($m[1] ?? 'root');
    preg_match('/^DB_PASSWORD=(.*)$/m', $envFile, $m);
    $dbPass = trim($m[1] ?? '');

    $pdo = new PDO("mysql:host={$dbHost};port={$dbPort};dbname={$dbName}", $dbUser, $dbPass, [
        PDO::ATTR_TIMEOUT => 3,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    $stmt = $pdo->query("SELECT COUNT(*) as c FROM tenants");
    $tenantCount = (int) $stmt->fetch(PDO::FETCH_ASSOC)['c'];

    $envChecks[] = ['label' => 'MySQL Connection', 'value' => "OK ({$dbName})", 'status' => 'ok'];
    $envChecks[] = ['label' => 'Tenants in DB', 'value' => (string) $tenantCount, 'status' => $tenantCount > 0 ? 'ok' : 'warn'];

    // Check migrations table
    $stmt = $pdo->query("SELECT COUNT(*) as c FROM migrations");
    $migCount = (int) $stmt->fetch(PDO::FETCH_ASSOC)['c'];
    $envChecks[] = ['label' => 'Migrations ran', 'value' => (string) $migCount, 'status' => 'ok'];
} catch (\Throwable $e) {
    $envChecks[] = ['label' => 'MySQL Connection', 'value' => $e->getMessage(), 'status' => 'error'];
}

// Vite manifest
$viteManifest = $projectRoot . '/public/build/manifest.json';
$envChecks[] = [
    'label'  => 'Vite manifest',
    'value'  => file_exists($viteManifest) ? 'Present' : 'MISSING (run npm run build)',
    'status' => file_exists($viteManifest) ? 'ok' : 'error',
];

// Storage link (is_link may fail on Windows junctions, check dir exists too)
$storageLink = $projectRoot . '/public/storage';
$storageLinkExists = is_link($storageLink) || is_dir($storageLink) || file_exists($storageLink);
$envChecks[] = [
    'label'  => 'Storage symlink',
    'value'  => $storageLinkExists ? 'Present' : 'MISSING (run php artisan storage:link)',
    'status' => $storageLinkExists ? 'ok' : 'error',
];

// ── Laravel Log (last errors) ───────────────────────────────────────
$logErrors = [];
$logFile = $projectRoot . '/storage/logs/laravel.log';
if (file_exists($logFile)) {
    $logSize = filesize($logFile);
    $logSizeFormatted = $logSize > 1048576
        ? round($logSize / 1048576, 1) . ' MB'
        : round($logSize / 1024, 1) . ' KB';

    // Read last 8KB to find recent errors
    $fh = fopen($logFile, 'r');
    if ($fh) {
        $readSize = min(8192, $logSize);
        fseek($fh, -$readSize, SEEK_END);
        $tail = fread($fh, $readSize);
        fclose($fh);

        // Extract error entries
        preg_match_all('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] \w+\.ERROR: (.+?)(?=\n\[|\z)/s', $tail, $matches, PREG_SET_ORDER);
        foreach (array_slice($matches, -10) as $m) {
            $msg = trim(explode("\n", $m[2])[0]);
            if (strlen($msg) > 150) {
                $msg = substr($msg, 0, 150) . '...';
            }
            $logErrors[] = ['time' => $m[1], 'message' => htmlspecialchars($msg, ENT_QUOTES, 'UTF-8')];
        }
    }
} else {
    $logSizeFormatted = 'File not found';
}

// ── Summary ─────────────────────────────────────────────────────────
$totalChecks = count($results);
$okCount = count(array_filter($results, fn($r) => $r['status'] === 'ok'));
$failCount = $totalChecks - $okCount;
$envOk = count(array_filter($envChecks, fn($c) => $c['status'] === 'ok'));
$envFail = count($envChecks) - $envOk;

$totalTime = round((microtime(true) - $startTime) * 1000);

// ── Render ──────────────────────────────────────────────────────────
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SyntiWeb Monitor</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; background: #0f172a; color: #e2e8f0; padding: 20px; }
        .container { max-width: 960px; margin: 0 auto; }
        h1 { font-size: 1.5rem; margin-bottom: 4px; }
        .subtitle { color: #64748b; font-size: 0.85rem; margin-bottom: 20px; }
        .summary { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 24px; }
        .summary-card { flex: 1; min-width: 120px; background: #1e293b; border-radius: 8px; padding: 16px; text-align: center; }
        .summary-card .num { font-size: 1.8rem; font-weight: 800; }
        .summary-card .lbl { font-size: 0.75rem; color: #94a3b8; margin-top: 4px; }
        .green { color: #22c55e; } .red { color: #ef4444; } .yellow { color: #eab308; } .blue { color: #3b82f6; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        th { text-align: left; padding: 8px 12px; font-size: 0.7rem; text-transform: uppercase; color: #64748b; border-bottom: 1px solid #334155; }
        td { padding: 8px 12px; font-size: 0.85rem; border-bottom: 1px solid #1e293b; }
        tr:hover { background: #1e293b; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 0.7rem; font-weight: 600; }
        .badge-ok { background: #14532d; color: #22c55e; }
        .badge-error { background: #450a0a; color: #ef4444; }
        .badge-warn { background: #422006; color: #eab308; }
        .badge-redirect { background: #172554; color: #3b82f6; }
        .badge-down { background: #450a0a; color: #ef4444; }
        .section-title { font-size: 1rem; font-weight: 700; margin: 20px 0 10px; padding-bottom: 6px; border-bottom: 1px solid #334155; }
        .log-entry { background: #1e293b; border-radius: 6px; padding: 10px 14px; margin-bottom: 8px; font-size: 0.8rem; }
        .log-time { color: #64748b; font-size: 0.7rem; }
        .log-msg { color: #fca5a5; margin-top: 2px; word-break: break-all; }
        .mono { font-family: 'Cascadia Code', 'Fira Code', monospace; font-size: 0.8rem; }
        .refresh { display: inline-block; margin-top: 16px; padding: 8px 20px; background: #3b82f6; color: white; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 0.85rem; }
        .refresh:hover { background: #2563eb; }
        @media (max-width: 640px) { .summary-card { min-width: 80px; padding: 12px; } .summary-card .num { font-size: 1.3rem; } }
    </style>
</head>
<body>
<div class="container">
    <h1>SyntiWeb Health Monitor</h1>
    <div class="subtitle"><?= date('Y-m-d H:i:s') ?> &mdash; Scan completed in <?= $totalTime ?>ms</div>

    <div class="summary">
        <div class="summary-card">
            <div class="num green"><?= $okCount ?></div>
            <div class="lbl">Routes OK</div>
        </div>
        <div class="summary-card">
            <div class="num <?= $failCount > 0 ? 'red' : 'green' ?>"><?= $failCount ?></div>
            <div class="lbl">Routes Failed</div>
        </div>
        <div class="summary-card">
            <div class="num <?= $envFail > 0 ? 'yellow' : 'green' ?>"><?= $envOk ?>/<?= count($envChecks) ?></div>
            <div class="lbl">Env Checks</div>
        </div>
        <div class="summary-card">
            <div class="num blue"><?= count($logErrors) ?></div>
            <div class="lbl">Recent Errors</div>
        </div>
    </div>

    <!-- ROUTES -->
    <div class="section-title">Route Health</div>
    <table>
        <thead>
            <tr><th>Route</th><th>Label</th><th>Status</th><th>Code</th><th>Time</th><th>Size</th></tr>
        </thead>
        <tbody>
        <?php foreach ($results as $r): ?>
            <tr>
                <td class="mono"><?= htmlspecialchars($r['url'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($r['label'], ENT_QUOTES, 'UTF-8') ?> <span style="color:#475569;font-size:0.7rem">(<?= $r['group'] ?>)</span></td>
                <td><span class="badge badge-<?= $r['status'] ?>"><?= strtoupper($r['status']) ?></span></td>
                <td class="mono"><?= $r['code'] ?></td>
                <td class="mono"><?= $r['time_ms'] ?>ms</td>
                <td class="mono"><?= $r['size'] > 1024 ? round($r['size'] / 1024, 1) . 'K' : $r['size'] . 'B' ?></td>
            </tr>
            <?php if ($r['error']): ?>
            <tr><td colspan="6" style="color:#fca5a5;padding-left:24px;font-size:0.75rem;">&rarr; <?= htmlspecialchars($r['error'], ENT_QUOTES, 'UTF-8') ?></td></tr>
            <?php endif; ?>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- ENV CHECKS -->
    <div class="section-title">Environment</div>
    <table>
        <thead><tr><th>Check</th><th>Value</th><th>Status</th></tr></thead>
        <tbody>
        <?php foreach ($envChecks as $c): ?>
            <tr>
                <td><?= htmlspecialchars($c['label'], ENT_QUOTES, 'UTF-8') ?></td>
                <td class="mono"><?= htmlspecialchars($c['value'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><span class="badge badge-<?= $c['status'] ?>"><?= strtoupper($c['status']) ?></span></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- LARAVEL LOG -->
    <div class="section-title">Laravel Log <span style="color:#64748b;font-weight:normal;font-size:0.75rem">(<?= $logSizeFormatted ?>)</span></div>
    <?php if (empty($logErrors)): ?>
        <div class="log-entry" style="color:#22c55e;">No recent errors found.</div>
    <?php else: ?>
        <?php foreach ($logErrors as $le): ?>
        <div class="log-entry">
            <div class="log-time"><?= $le['time'] ?></div>
            <div class="log-msg"><?= $le['message'] ?></div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <a href="monitor.php" class="refresh">Refresh</a>
    <span style="color:#475569;font-size:0.75rem;margin-left:12px;">Localhost only. Do NOT expose to production.</span>
</div>
</body>
</html>
