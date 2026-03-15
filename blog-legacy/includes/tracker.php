<?php
/**
 * SYNTIweb Blog — Tracker de visitas
 */
function sw_track(string $type = 'index', string $slug = ''): void {
    $file = __DIR__ . '/../data/stats.json';

    $stats = file_exists($file)
        ? (json_decode(file_get_contents($file), true) ?? [])
        : [];

    $today = date('Y-m-d');
    $hour  = (int)date('H');

    // ── Rotación diaria: si cambió el día, mover hoy → ayer ──
    $lastDay = $stats['today_date'] ?? '';
    if ($lastDay !== $today && $lastDay !== '') {
        // Nuevo día: lo de hoy pasa a ayer
        $stats['yesterday_visits'] = $stats['today_visits'] ?? 0;
        $stats['yesterday_date']   = $lastDay;
        $stats['today_visits']     = 0;
        $stats['hours']            = array_fill(0, 24, 0);
    }
    $stats['today_date'] = $today;

    // ── Contadores globales ──
    $stats['total_visits'] = ($stats['total_visits'] ?? 0) + 1;
    $stats['today_visits'] = ($stats['today_visits'] ?? 0) + 1;

    // Inicializar ayer si no existe
    if (!isset($stats['yesterday_visits'])) $stats['yesterday_visits'] = 0;

    // ── Visitas por hora ──
    if (!isset($stats['hours']) || !is_array($stats['hours'])) {
        $stats['hours'] = array_fill(0, 24, 0);
    }
    $stats['hours'][$hour] = ($stats['hours'][$hour] ?? 0) + 1;

    // ── Tracking por post ──
    if ($type === 'post' && $slug !== '') {
        if (!isset($stats['posts']) || !is_array($stats['posts'])) {
            $stats['posts'] = [];
        }
        if (!isset($stats['posts'][$slug])) {
            $stats['posts'][$slug] = ['views' => 0, 'today' => 0, 'date' => $today];
        }
        // Reset contador diario del post si cambió el día
        if (($stats['posts'][$slug]['date'] ?? '') !== $today) {
            $stats['posts'][$slug]['today'] = 0;
            $stats['posts'][$slug]['date']  = $today;
        }
        $stats['posts'][$slug]['views'] = ($stats['posts'][$slug]['views'] ?? 0) + 1;
        $stats['posts'][$slug]['today'] = ($stats['posts'][$slug]['today'] ?? 0) + 1;
        $stats['posts'][$slug]['last']  = date('Y-m-d H:i');
    }

    // ── Guardar con lock ──
    // JSON_FORCE_OBJECT evita que 'posts' vacío se serialice como [] en vez de {}
    $fp = fopen($file, 'c+');
    if ($fp && flock($fp, LOCK_EX)) {
        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode($stats, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT));
        flock($fp, LOCK_UN);
        fclose($fp);
    }
}