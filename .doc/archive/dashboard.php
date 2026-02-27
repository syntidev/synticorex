<?php
$progressFile = __DIR__ . '/PROGRESS.md';
if (!file_exists($progressFile)) die('ERROR: PROGRESS.md no encontrado');

$content = file_get_contents($progressFile);

// Parsear API
preg_match('/\*\*Gastado:\*\* \$([0-9.]+)/', $content, $gastado);
preg_match('/\*\*Restante:\*\* \$([0-9.]+)/', $content, $restante);
$apiGastado = isset($gastado[1]) ? floatval($gastado[1]) : 4.50;
$apiRestante = isset($restante[1]) ? floatval($restante[1]) : 45.50;
$apiTotal = 50.00;

// Extraer próxima tarea
preg_match('/\*\*🎯 Próxima tarea:\*\* (.+)/', $content, $proxima);
$proximaTarea = isset($proxima[1]) ? $proxima[1] : 'Completar Semana 2';

// Progreso detallado
$semanas = [
    1 => ['completadas' => 13, 'total' => 13, 'nombre' => 'Fundación', 'estado' => 'complete'],
    2 => ['completadas' => 10, 'total' => 13, 'nombre' => 'Motor Renderizado', 'estado' => 'active'],
    3 => ['completadas' => 0, 'total' => 14, 'nombre' => 'Dashboard Admin', 'estado' => 'pending'],
    4 => ['completadas' => 0, 'total' => 12, 'nombre' => 'Analytics & Polish', 'estado' => 'pending']
];

$totalTareas = array_sum(array_column($semanas, 'total'));
$tareasCompletadas = array_sum(array_column($semanas, 'completadas'));
$progresoGeneral = round(($tareasCompletadas / $totalTareas) * 100, 1);

$horasInvertidas = 22; // Actualizar manualmente
$horasTotales = 160;
?>
<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SYNTIweb MVP - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    animation: {
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-black text-gray-100 antialiased">
    <!-- Gradient Background -->
    <div class="fixed inset-0 bg-gradient-to-br from-purple-900/20 via-black to-cyan-900/20"></div>
    
    <div class="relative min-h-screen">
        <!-- Header -->
        <header class="border-b border-gray-800 backdrop-blur-xl bg-black/50">
            <div class="max-w-7xl mx-auto px-6 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-purple-400 to-cyan-400 bg-clip-text text-transparent">
                            SYNTIweb MVP
                        </h1>
                        <p class="text-sm text-gray-400 mt-1">Desarrollo en Progreso</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="h-2 w-2 rounded-full bg-green-500 animate-pulse-slow"></div>
                        <span class="text-sm text-gray-400">Sistema Operativo</span>
                    </div>
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-6 py-12">
            
            <!-- Próxima Tarea -->
            <div class="mb-12">
                <div class="bg-gradient-to-r from-purple-500/10 to-cyan-500/10 border border-purple-500/20 rounded-2xl p-6 backdrop-blur-xl">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-purple-500/20 flex items-center justify-center">
                            <span class="text-xl">🎯</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-gray-400 mb-1">Próxima Tarea</h3>
                            <p class="text-lg font-medium text-white"><?= htmlspecialchars($proximaTarea) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
                
                <!-- Progreso General -->
                <div class="bg-gray-900/50 border border-gray-800 rounded-2xl p-6 backdrop-blur-xl hover:border-purple-500/50 transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-medium text-gray-400">Progreso General</h3>
                        <div class="w-8 h-8 rounded-lg bg-purple-500/20 flex items-center justify-center">
                            <span class="text-lg">📈</span>
                        </div>
                    </div>
                    <div class="text-4xl font-bold text-white mb-4"><?= $progresoGeneral ?>%</div>
                    <div class="h-2 bg-gray-800 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-purple-500 to-cyan-500 transition-all duration-1000" 
                             style="width: <?= $progresoGeneral ?>%"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-3"><?= $tareasCompletadas ?> de <?= $totalTareas ?> tareas</p>
                </div>

                <!-- Tiempo -->
                <div class="bg-gray-900/50 border border-gray-800 rounded-2xl p-6 backdrop-blur-xl hover:border-cyan-500/50 transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-medium text-gray-400">Tiempo Invertido</h3>
                        <div class="w-8 h-8 rounded-lg bg-cyan-500/20 flex items-center justify-center">
                            <span class="text-lg">⏱️</span>
                        </div>
                    </div>
                    <div class="text-4xl font-bold text-white mb-1"><?= $horasInvertidas ?>h</div>
                    <p class="text-sm text-gray-400">de <?= $horasTotales ?>h estimadas</p>
                    <div class="mt-4 flex items-center gap-2">
                        <div class="flex-1 h-1.5 bg-gray-800 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-cyan-500 to-blue-500 transition-all duration-1000"
                                 style="width: <?= round(($horasInvertidas/$horasTotales)*100, 1) ?>%"></div>
                        </div>
                        <span class="text-xs text-gray-500"><?= round(($horasInvertidas/$horasTotales)*100, 1) ?>%</span>
                    </div>
                </div>

                <!-- Presupuesto API -->
                <div class="bg-gray-900/50 border border-gray-800 rounded-2xl p-6 backdrop-blur-xl hover:border-green-500/50 transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-medium text-gray-400">Presupuesto API</h3>
                        <div class="w-8 h-8 rounded-lg bg-green-500/20 flex items-center justify-center">
                            <span class="text-lg">💰</span>
                        </div>
                    </div>
                    <div class="text-4xl font-bold text-green-400 mb-1">$<?= number_format($apiRestante, 2) ?></div>
                    <p class="text-sm text-gray-400">de $<?= number_format($apiTotal, 2) ?> restantes</p>
                    <div class="mt-4 flex gap-2 text-xs">
                        <div class="px-2 py-1 rounded bg-red-500/20 text-red-300">
                            Gastado: $<?= number_format($apiGastado, 2) ?>
                        </div>
                    </div>
                </div>

                <!-- Commits -->
                <div class="bg-gray-900/50 border border-gray-800 rounded-2xl p-6 backdrop-blur-xl hover:border-yellow-500/50 transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-medium text-gray-400">Commits</h3>
                        <div class="w-8 h-8 rounded-lg bg-yellow-500/20 flex items-center justify-center">
                            <span class="text-lg">🚀</span>
                        </div>
                    </div>
                    <div class="text-4xl font-bold text-white mb-1">4</div>
                    <p class="text-sm text-gray-400">en GitHub</p>
                    <div class="mt-4">
                        <a href="https://github.com/syntidev/synticorex" target="_blank" 
                           class="text-xs text-cyan-400 hover:text-cyan-300 underline">
                            Ver repositorio →
                        </a>
                    </div>
                </div>

            </div>

            <!-- Semanas Timeline -->
            <div class="space-y-4">
                <h2 class="text-xl font-bold text-white mb-6">Progreso por Semana</h2>
                
                <?php foreach ($semanas as $num => $data): 
                    $progreso = $data['total'] > 0 ? round(($data['completadas'] / $data['total']) * 100, 1) : 0;
                    
                    if ($data['estado'] === 'complete') {
                        $borderColor = 'border-green-500/50';
                        $badgeBg = 'bg-green-500/20';
                        $badgeText = 'text-green-300';
                        $badgeIcon = '✓';
                    } elseif ($data['estado'] === 'active') {
                        $borderColor = 'border-purple-500/50';
                        $badgeBg = 'bg-purple-500/20';
                        $badgeText = 'text-purple-300';
                        $badgeIcon = '⚡';
                    } else {
                        $borderColor = 'border-gray-800';
                        $badgeBg = 'bg-gray-800';
                        $badgeText = 'text-gray-400';
                        $badgeIcon = '○';
                    }
                ?>
                
                <div class="bg-gray-900/50 border <?= $borderColor ?> rounded-2xl p-6 backdrop-blur-xl transition-all hover:border-opacity-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center justify-center w-12 h-12 rounded-xl <?= $badgeBg ?> <?= $badgeText ?> font-bold text-lg">
                                <?= $badgeIcon ?>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-white">Semana <?= $num ?>: <?= $data['nombre'] ?></h3>
                                <p class="text-sm text-gray-400"><?= $data['completadas'] ?> de <?= $data['total'] ?> tareas completadas</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold <?= $progreso >= 100 ? 'text-green-400' : 'text-white' ?>">
                                <?= $progreso ?>%
                            </div>
                        </div>
                    </div>
                    
                    <div class="h-3 bg-gray-800 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r <?= $data['estado'] === 'complete' ? 'from-green-500 to-emerald-500' : ($data['estado'] === 'active' ? 'from-purple-500 to-cyan-500' : 'from-gray-700 to-gray-600') ?> transition-all duration-1000"
                             style="width: <?= $progreso ?>%"></div>
                    </div>
                </div>
                
                <?php endforeach; ?>
            </div>

            <!-- Footer -->
            <div class="mt-12 text-center">
                <button onclick="location.reload()" 
                        class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-purple-500 to-cyan-500 hover:from-purple-600 hover:to-cyan-600 text-white font-medium transition-all transform hover:scale-105">
                    <span>🔄</span>
                    <span>Actualizar Dashboard</span>
                </button>
                <p class="text-xs text-gray-500 mt-4">
                    Última actualización: <?= date('d M Y, H:i') ?>
                </p>
            </div>

        </main>
    </div>
</body>
</html>