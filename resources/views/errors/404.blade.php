<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Página no encontrada | SYNTIweb</title>
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
        }
        @media (prefers-color-scheme: light) {
            .img-dark  { display: none; }
            .img-light { display: block; }
        }
        .logo { margin-bottom: 32px; }
        .logo img { max-width: 60px; }
        .illustration { max-width: 300px; width: 100%; margin-bottom: 28px; }
        h1  { font-size: 20px; font-weight: 700; margin-bottom: 10px; }
        p   { font-size: 14px; color: #64748b; margin-bottom: 28px; line-height: 1.6; }
        a   {
            background: #4A80E4;
            color: #fff;
            padding: 10px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }
        a:hover { background: #3a6fd4; }
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

    <img src="/brand/404.svg" class="illustration" alt="404 Not Found">

    <h1>SYNT<i>iA</i> buscó esto y tampoco lo encontró ⚡</h1>
    <p>Esta página no existe o fue movida a otro<br>subdominio del universo SYNTIweb.</p>

    <a href="/">Llevarme al inicio</a>
</body>
</html>
