<?php
$progressFile = __DIR__ . '/PROGRESS.md';
if (!file_exists($progressFile)) die('ERROR: PROGRESS.md no encontrado');

$content = file_get_contents($progressFile);

// Parsear API
preg_match('/\*\*Gastado:\*\* \$([0-9.]+)/', $content, $gastado);
preg_match('/\*\*Restante:\*\* \$([0-9.]+)/', $content, $restante);
$apiGastado = isset($gastado[1]) ? floatval($gastado[1]) : 2.87;
$apiRestante = isset($restante[1]) ? floatval($restante[1]) : 47.13;

// Extraer próxima tarea
preg_match('/\*\*🎯 Próxima tarea:\*\* (.+)/', $content, $proxima);
$proximaTarea = isset($proxima[1]) ? $proxima[1] : 'DollarRateService (4h con Continue + Sonnet)';

// Progreso manual (actualízalo cuando avances)
$semanas = [
    1 => ['completadas' => 8, 'total' => 13],
    2 => ['completadas' => 0, 'total' => 13],
    3 => ['completadas' => 0, 'total' => 14],
    4 => ['completadas' => 0, 'total' => 12]
];

$totalTareas = array_sum(array_column($semanas, 'total'));
$tareasCompletadas = array_sum(array_column($semanas, 'completadas'));
$progresoGeneral = round(($tareasCompletadas / $totalTareas) * 100, 1);

$progresoSemanas = [];
foreach ($semanas as $num => $data) {
    $progresoSemanas[$num] = $data['total'] > 0 ? round(($data['completadas'] / $data['total']) * 100, 1) : 0;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📊 SYNTIweb MVP - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-purple-600 via-blue-600 to-cyan-500 min-h-screen p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="text-center text-white mb-8">
            <h1 class="text-5xl font-bold mb-2">📊 SYNTIweb MVP</h1>
            <p class="text-xl opacity-90">Monitoreo de Progreso en Tiempo Real</p>
        </div>

        <!-- Próxima Tarea -->
        <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl p-6 mb-6 shadow-2xl">
            <h3 class="text-2xl font-bold text-white mb-2">🎯 Próxima Tarea</h3>
            <p class="text-white text-lg"><?= htmlspecialchars($proximaTarea) ?></p>
        </div>

        <!-- Cards Resumen -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Progreso General -->
            <div class="bg-white rounded-2xl p-6 shadow-2xl hover:scale-105 transition-transform">
                <h3 class="text-purple-600 text-xl font-bold mb-4">📈 Progreso General</h3>
                <div class="text-5xl font-bold text-gray-800 mb-4"><?= $progresoGeneral ?>%</div>
                <div class="bg-gray-200 rounded-full h-5 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-full flex items-center justify-center text-white text-sm font-bold transition-all duration-1000" style="width: <?= $progresoGeneral ?>%">
                        <?= $tareasCompletadas ?>/<?= $totalTareas ?>
                    </div>
                </div>
                <p class="text-gray-600 mt-3"><?= $tareasCompletadas ?> tareas de <?= $totalTareas ?></p>
            </div>

            <!-- Presupuesto API -->
            <div class="bg-white rounded-2xl p-6 shadow-2xl hover:scale-105 transition-transform">
                <h3 class="text-purple-600 text-xl font-bold mb-4">💰 Presupuesto API</h3>
                <div class="flex justify-between mb-4">
                    <div class="text-center">
                        <span class="text-sm text-gray-600">Gastado</span>
                        <div class="text-2xl font-bold text-red-500">$<?= number_format($apiGastado, 2) ?></div>
                    </div>
                    <div class="text-center">
                        <span class="text-sm text-gray-600">Restante</span>
                        <div class="text-2xl font-bold text-green-500">$<?= number_format($apiRestante, 2) ?></div>
                    </div>
                </div>
                <div class="bg-gray-200 rounded-full h-5">
                    <div class="bg-gradient-to-r from-red-500 to-orange-500 h-full rounded-full transition-all duration-1000" style="width: <?= round(($apiGastado/50)*100, 1) ?>%"></div>
                </div>
            </div>

            <!-- Tiempo -->
            <div class="bg-white rounded-2xl p-6 shadow-2xl hover:scale-105 transition-transform">
                <h3 class="text-purple-600 text-xl font-bold mb-4">⏱️ Tiempo Invertido</h3>
                <div class="text-5xl font-bold text-gray-800 mb-2"><?= round($tareasCompletadas * 2.5) ?>h</div>
                <p class="text-gray-600">de 160 horas totales</p>
                <p class="text-purple-600 font-bold mt-2">~<?= round(160 - ($tareasCompletadas * 2.5)) ?>h restantes</p>
            </div>
        </div>

        <!-- Semanas -->
        <?php foreach ($semanas as $num => $data): ?>
        <div class="bg-white rounded-2xl p-6 mb-4 shadow-2xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-2xl font-bold text-gray-800">🗓️ Semana <?= $num ?>: 
                    <?= $num == 1 ? 'Fundación' : ($num == 2 ? 'Template' : ($num == 3 ? 'Dashboard' : 'Polish')) ?>
                </h3>
                <span class="px-4 py-2 rounded-full text-white font-bold <?= $progresoSemanas[$num] > 50 ? 'bg-green-500' : ($progresoSemanas[$num] > 0 ? 'bg-yellow-500' : 'bg-gray-400') ?>">
                    <?= $progresoSemanas[$num] ?>%
                </span>
            </div>
            <div class="bg-gray-200 rounded-full h-6">
                <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-full rounded-full flex items-center justify-center text-white font-bold transition-all duration-1000" style="width: <?= $progresoSemanas[$num] ?>%">
                    <?= $data['completadas'] ?>/<?= $data['total'] ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Botón Refresh -->
    <button onclick="location.reload()" class="fixed bottom-8 right-8 bg-white px-6 py-3 rounded-full shadow-2xl hover:scale-110 transition-transform font-bold text-purple-600">
        🔄 Actualizar
    </button>
</body>
</html>