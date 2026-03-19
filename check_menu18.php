<?php
$t = DB::table('tenants')->where('id', 18)->first();
$settings = json_decode($t->settings, true);
$menu = $settings['menu'] ?? null;
if ($menu) {
    echo "Menu en settings — " . count($menu) . " categorías\n";
    foreach ($menu as $cat) {
        echo "  [{$cat['id']}] {$cat['nombre']} — " . count($cat['items']) . " items\n";
    }
} else {
    echo "No hay menu en settings\n";
    echo "Keys en settings: " . implode(', ', array_keys($settings)) . "\n";
}
