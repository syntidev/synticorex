<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Error del servidor | SYNTIweb</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
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
        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 72px; height: 72px;
            border-radius: 50%;
            background: #fef2f2;
            font-size: 22px;
            font-weight: 900;
            color: #dc2626;
            margin-bottom: 28px;
        }
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

    <div class="badge">500</div>

    <h1>Algo salió mal en el servidor</h1>
    <p>El error es nuestro, no tuyo.<br>Estamos en ello. Intenta de nuevo en unos momentos.</p>

    <a href="/">Volver al inicio</a>
</body>
</html>
        </div>
    </div>
</body>
</html>
