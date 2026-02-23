{{-- resources/views/landing/frozen.blade.php --}}
{{-- Static freeze page shown when a tenant's subscription has expired (status = 'frozen'). --}}
{{-- No tenant data is exposed here — minimal, clean, reassuring. --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vuelve Pronto</title>
    <meta name="robots" content="noindex, nofollow">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #05080f;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: #e5e7eb;
            overflow: hidden;
        }

        /* Ambient glow */
        body::before {
            content: '';
            position: fixed;
            top: -200px;
            left: 50%;
            transform: translateX(-50%);
            width: 800px;
            height: 600px;
            background: radial-gradient(ellipse at center, rgba(59,130,246,0.08) 0%, transparent 70%);
            pointer-events: none;
        }

        .card {
            position: relative;
            max-width: 480px;
            width: 90%;
            text-align: center;
            padding: 56px 40px;
            background: rgba(15, 28, 50, 0.9);
            border: 1px solid rgba(59,130,246,0.15);
            border-radius: 24px;
            backdrop-filter: blur(12px);
            box-shadow: 0 32px 80px rgba(0,0,0,0.6);
        }

        .icon {
            font-size: 56px;
            margin-bottom: 28px;
            display: block;
            line-height: 1;
        }

        h1 {
            font-size: clamp(20px, 5vw, 26px);
            font-weight: 700;
            line-height: 1.35;
            color: #f1f5f9;
            margin-bottom: 18px;
            letter-spacing: -0.3px;
        }

        h1 span {
            color: #60a5fa;
        }

        p {
            font-size: 15px;
            color: #94a3b8;
            line-height: 1.7;
        }

        .divider {
            width: 40px;
            height: 2px;
            background: rgba(59,130,246,0.4);
            border-radius: 2px;
            margin: 28px auto;
        }

        .badge {
            display: inline-block;
            margin-top: 32px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: #475569;
        }

        /* Subtle floating animation on icon */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-8px); }
        }
        .icon { animation: float 4s ease-in-out infinite; }
    </style>
</head>
<body>
    <div class="card">
        <span class="icon">🔄</span>

        <h1>Este negocio está<br><span>renovando su presencia digital</span></h1>

        <div class="divider"></div>

        <p>Pronto estaremos de vuelta con todo nuestro catálogo.<br>¡Gracias por tu paciencia!</p>

        <span class="badge">Vuelve pronto</span>
    </div>
</body>
</html>
