<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Acceso denegado | SYNTIweb</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="manifest" href="/site.webmanifest">
    <meta name="theme-color" content="#4A80E4">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Geist', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #ffffff;
            color: #0f172a;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 40px 20px;
            text-align: center;
        }
        @media (prefers-color-scheme: dark) {
            body { background: #1b1b1f; color: #f0f0f0; }
            .img-light { display: none; }
            .img-dark  { display: block; }
            p { color: #94a3b8; }
        }
        @media (prefers-color-scheme: light) {
            .img-dark  { display: none; }
            .img-light { display: block; }
        }
        .logo { margin-bottom: 32px; }
        .logo img { max-width: 60px; }
        .code { font-size: 72px; font-weight: 800; color: #4A80E4; margin-bottom: 8px; letter-spacing: -2px; }
        h1  { font-size: 20px; font-weight: 700; margin-bottom: 10px; }
        p   { font-size: 14px; color: #64748b; margin-bottom: 28px; line-height: 1.6; }
        .actions { display: flex; gap: 12px; flex-wrap: wrap; justify-content: center; }
        .btn-primary {
            background: #4A80E4;
            color: #fff;
            padding: 10px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: background 150ms;
        }
        .btn-primary:hover { background: #3a6fd4; }
        .btn-secondary {
            background: transparent;
            color: #4A80E4;
            padding: 10px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            border: 1.5px solid #E2E8F4;
            transition: border-color 150ms;
        }
        .btn-secondary:hover { border-color: #4A80E4; }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { transition-duration: 0.01ms !important; }
        }
    </style>
</head>
<body>
    <div class="logo">
        <img src="/brand/syntiweb-logo-positive.svg" class="img-light" alt="SYNTIweb">
        <img src="/brand/syntiweb-logo-negative.svg" class="img-dark" alt="SYNTIweb">
    </div>

    <div class="code">403</div>

    <h1>Acceso denegado</h1>
    <p>No tienes permisos para acceder a esta página.<br>Si crees que es un error, inicia sesión con la cuenta correcta.</p>

    <div class="actions">
        <a href="/login" class="btn-primary">Iniciar sesión</a>
        <a href="/" class="btn-secondary">Ir al inicio</a>
    </div>
</body>
</html>
