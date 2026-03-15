<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'error' => 'Método no permitido']);
    exit;
}

$jsonFile = __DIR__ . '/../data/posts.json';

$body = file_get_contents('php://input');
$data = json_decode($body, true);

if (!$data || empty($data['title'])) {
    echo json_encode(['ok' => false, 'error' => 'Datos inválidos']);
    exit;
}

$all = file_exists($jsonFile)
    ? json_decode(file_get_contents($jsonFile), true)
    : [];

$isEdit = !empty($data['id']);

// Si es destacado, quitar destacado de los demás
if (!empty($data['featured'])) {
    foreach ($all as &$p) { $p['featured'] = false; }
    unset($p);
}

if ($isEdit) {
    $found = false;
    foreach ($all as &$p) {
        if ($p['id'] === $data['id']) {
            $p['title']            = $data['title'];
            $p['slug']             = $data['slug']             ?? '';
            $p['excerpt']          = $data['excerpt']          ?? '';
            $p['content']          = $data['content']          ?? '';
            $p['image']            = $data['image']            ?? '';
            $p['tag']              = $data['tag']              ?? '';
            $p['tag_color']        = $data['tag_color']        ?? '#4A80E4';
            $p['date']             = $data['date']             ?? date('Y-m-d');
            $p['date_label']       = $data['date_label']       ?? date('d M Y');
            $p['read']             = $data['read']             ?? '5 min';
            $p['featured']         = (bool)($data['featured']  ?? false);
            $p['status']           = $data['status']           ?? 'published';
            $p['meta_title']       = $data['meta_title']       ?? '';
            $p['meta_description'] = $data['meta_description'] ?? '';
            $p['tags']             = $data['tags']             ?? [];
            $found = true;
            break;
        }
    }
    unset($p);
    if (!$found) {
        echo json_encode(['ok' => false, 'error' => 'Post no encontrado']);
        exit;
    }
} else {
    // Generar ID único
    $existingIds = array_column($all, 'id');
    $newNum = count($all) + 1;
    $newId  = str_pad($newNum, 3, '0', STR_PAD_LEFT);
    while (in_array($newId, $existingIds)) {
        $newId = str_pad(++$newNum, 3, '0', STR_PAD_LEFT);
    }

    $new = [
        'id'               => $newId,
        'slug'             => $data['slug']             ?? '',
        'title'            => $data['title'],
        'excerpt'          => $data['excerpt']          ?? '',
        'content'          => $data['content']          ?? '',
        'image'            => $data['image']            ?? '',
        'tag'              => $data['tag']              ?? '',
        'tag_color'        => $data['tag_color']        ?? '#4A80E4',
        'author'           => $data['author']           ?? 'Equipo SYNTIweb',
        'avatar'           => $data['avatar']           ?? 'https://i.pravatar.cc/80?img=12',
        'date'             => $data['date']             ?? date('Y-m-d'),
        'date_label'       => $data['date_label']       ?? date('d M Y'),
        'read'             => $data['read']             ?? '5 min',
        'featured'         => (bool)($data['featured']  ?? false),
        'status'           => $data['status']           ?? 'published',
        'meta_title'       => $data['meta_title']       ?? '',
        'meta_description' => $data['meta_description'] ?? '',
        'tags'             => $data['tags']             ?? [],
    ];
    array_unshift($all, $new);
}

$ok = file_put_contents(
    $jsonFile,
    json_encode($all, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
);

echo json_encode($ok !== false
    ? ['ok' => true]
    : ['ok' => false, 'error' => 'No se pudo escribir el archivo']
);