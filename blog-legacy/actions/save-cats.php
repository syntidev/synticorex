<?php
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok'=>false,'error'=>'Método no permitido']); exit;
}
$file = __DIR__ . '/../data/categories.json';
$body = json_decode(file_get_contents('php://input'), true);

if (!isset($body['categories']) || !is_array($body['categories'])) {
    echo json_encode(['ok'=>false,'error'=>'Datos inválidos']); exit;
}

// Validar y limpiar
$cats = [];
foreach ($body['categories'] as $c) {
    $name = trim($c['name'] ?? '');
    $color = trim($c['color'] ?? '#4A80E4');
    if ($name !== '') {
        $cats[] = ['name' => $name, 'color' => $color];
    }
}

if (empty($cats)) {
    echo json_encode(['ok'=>false,'error'=>'Debe haber al menos una categoría']); exit;
}

$ok = file_put_contents($file, json_encode($cats, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo json_encode($ok !== false ? ['ok'=>true] : ['ok'=>false,'error'=>'No se pudo guardar']);
