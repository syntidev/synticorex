<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $content['settings']['nombre_negocio'] ?? $tenant->nombre }}</title>
</head>
<body>
    <h1>{{ $content['settings']['nombre_negocio'] ?? $tenant->nombre }}</h1>
    <p>{{ $content['secciones']['hero_banner']['titulo'] ?? $content['secciones']['hero']['titulo'] ?? 'Bienvenido' }}</p>
</body>
</html>
