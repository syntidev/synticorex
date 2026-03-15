<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'error' => 'Método no permitido']);
    exit;
}

$jsonFile = __DIR__ . '/../data/posts.json';
$body     = file_get_contents('php://input');
$data     = json_decode($body, true);

if (empty($data['id'])) {
    echo json_encode(['ok' => false, 'error' => 'ID requerido']);
    exit;
}

if (!file_exists($jsonFile)) {
    echo json_encode(['ok' => false, 'error' => 'Archivo de datos no encontrado']);
    exit;
}

$all      = json_decode(file_get_contents($jsonFile), true);
$filtered = array_values(array_filter($all, fn($p) => $p['id'] !== $data['id']));

if (count($filtered) === count($all)) {
    echo json_encode(['ok' => false, 'error' => 'Post no encontrado']);
    exit;
}

$ok = file_put_contents(
    $jsonFile,
    json_encode($filtered, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
);

echo json_encode($ok !== false
    ? ['ok' => true]
    : ['ok' => false, 'error' => 'Error al guardar']
);