<?php
$apiKey = '136e544b88716d05b70a9ac4615faedf';
$model  = 'Qwen/Qwen3-8B';

$endpoints = [
    "https://api.bytez.com/models/v2/{$model}",
    "https://api.bytez.com/models/v2/{$model}/run",
];

$payload = json_encode([
    'messages' => [['role' => 'user', 'content' => 'hola, responde solo: funciona']],
    'params'   => ['max_new_tokens' => 50],
]);

foreach ($endpoints as $url) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_HTTPHEADER     => [
            'Authorization: ' . $apiKey,
            'Content-Type: application/json',
        ],
    ]);
    $res  = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    echo "=== {$url} [{$code}] ===\n{$res}\n\n";
    curl_close($ch);
}
die();

$payload = json_encode([
    'messages'   => [['role' => 'user', 'content' => 'hola, responde solo: funciona']],
    'max_tokens' => 50,
]);

// Test 1: /run
$ch = curl_init("https://api.bytez.com/model/v2/{$model}/run");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $payload,
    CURLOPT_HTTPHEADER     => [
        'Authorization: Bearer ' . $apiKey,
        'Content-Type: application/json',
    ],
]);
$res1 = curl_exec($ch);
echo "=== TEST /run ===\n" . $res1 . "\n\n";

// Test 2: sin /run
curl_setopt($ch, CURLOPT_URL, "https://api.bytez.com/model/v2/{$model}");
$res2 = curl_exec($ch);
echo "=== TEST sin /run ===\n" . $res2 . "\n\n";

// Test 3: model en body
$payload2 = json_encode([
    'model'      => $model,
    'messages'   => [['role' => 'user', 'content' => 'hola']],
    'max_tokens' => 50,
]);
curl_setopt($ch, CURLOPT_URL, "https://api.bytez.com/model/v2");
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload2);
$res3 = curl_exec($ch);
echo "=== TEST model en body ===\n" . $res3 . "\n\n";

curl_close($ch);