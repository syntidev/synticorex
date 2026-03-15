<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo negocio — SYNTIweb</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Geist', ui-sans-serif, system-ui, sans-serif; }
        .wiz-gradient-header { background: #fff; border-bottom: 1px solid #e2e8f0; }
        .wiz-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 1rem; box-shadow: 0 4px 24px -4px rgba(0,0,0,0.08); }

        /* ── product cards ── */
        .sel-card { background: #fff; border: 1.5px solid #e2e8f0; border-radius: 1rem; box-shadow: 0 4px 16px -4px rgba(0,0,0,0.06); transition: transform .2s, box-shadow .2s; }
        .sel-card:hover { transform: translateY(-2px); box-shadow: 0 12px 32px -6px rgba(0,0,0,0.12); }

        /* ── CTA buttons per product ── */
        .sel-btn-studio { display:block; background: #4A80E4; color: #fff; border-radius: 0.625rem; font-weight: 700; transition: all .2s; box-shadow: 0 4px 14px -4px rgba(74,128,228,0.4); text-align:center; padding: 0.625rem 1rem; font-size:.875rem; text-decoration:none; }
        .sel-btn-studio:hover { background: #3a70d4; transform: translateY(-1px); box-shadow: 0 8px 20px -4px rgba(74,128,228,0.45); }
        .sel-btn-food { display:block; background: #f97316; color: #fff; border-radius: 0.625rem; font-weight: 700; transition: all .2s; box-shadow: 0 4px 14px -4px rgba(249,115,22,0.4); text-align:center; padding: 0.625rem 1rem; font-size:.875rem; text-decoration:none; }
        .sel-btn-food:hover { background: #ea6c0c; transform: translateY(-1px); box-shadow: 0 8px 20px -4px rgba(249,115,22,0.45); }
        .sel-btn-cat { display:block; background: #10b981; color: #fff; border-radius: 0.625rem; font-weight: 700; transition: all .2s; box-shadow: 0 4px 14px -4px rgba(16,185,129,0.4); text-align:center; padding: 0.625rem 1rem; font-size:.875rem; text-decoration:none; }
        .sel-btn-cat:hover { background: #0da874; transform: translateY(-1px); box-shadow: 0 8px 20px -4px rgba(16,185,129,0.45); }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">

{{-- ── HEADER ── --}}
<header class="wiz-gradient-header sticky top-0 z-50">
    <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="/" class="flex items-center gap-2">
                <img src="{{ asset('brand/syntiweb-logo-positive.svg') }}" alt="SYNTIweb" width="32" height="32">
                <span class="font-bold text-lg tracking-tight">
                    <span style="color:#1a1a1a">SYNTI</span><span style="color:#4A80E4">web</span>
                </span>
            </a>
            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-600 border border-blue-100">
                <span class="iconify tabler--sparkles size-3"></span>
                Nuevo negocio
            </span>
        </div>
        <a href="{{ url('/') }}" class="text-sm text-slate-400 hover:text-slate-600 transition-colors">
            Volver al inicio
        </a>
    </div>
</header>

{{-- ── HERO TEXT ── --}}
<main class="max-w-3xl mx-auto px-4 pt-14 pb-8">
    <div class="text-center mb-10">
        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 mb-3">
            ¿Qué necesitas para tu negocio?
        </h1>
        <p class="text-base sm:text-lg text-slate-500 max-w-xl mx-auto">
            Elige tu herramienta. Cada una está diseñada para un propósito específico.
        </p>
    </div>

    {{-- ── CARDS GRID ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">

        {{-- ── TARJETA 1: SYNTIstudio ── --}}
        <div class="sel-card flex flex-col p-6">
            <div class="mb-4">
                <span class="iconify tabler--world" style="font-size:2.5rem; color:#4A80E4;"></span>
            </div>
            <h2 class="text-lg font-black text-slate-900 mb-0.5">SYNTIstudio</h2>
            <p class="text-sm font-semibold text-slate-500 mb-4">Página web completa</p>

            <div class="border-t border-slate-100 pt-4 mb-4">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Para</p>
                <p class="text-sm text-slate-600">Freelancers, consultorios, barberías, tiendas con identidad</p>
            </div>

            <div class="bg-blue-50 rounded-lg px-3 py-2 mb-5">
                <p class="text-sm font-semibold text-blue-700">
                    <span class="iconify tabler--search size-4 inline-block align-middle mr-1"></span>
                    Apareces en Google. Tus clientes te encuentran.
                </p>
            </div>

            <a href="{{ route('onboarding.studio') }}" class="sel-btn-studio mt-auto">
                Crear mi página web →
            </a>
        </div>

        {{-- ── TARJETA 2: SYNTIfood ── --}}
        <div class="sel-card flex flex-col p-6">
            <div class="mb-4">
                <span class="iconify tabler--tools-kitchen-2" style="font-size:2.5rem; color:#f97316;"></span>
            </div>
            <h2 class="text-lg font-black text-slate-900 mb-0.5">SYNTIfood</h2>
            <p class="text-sm font-semibold text-slate-500 mb-4">Menú digital + Pedido por WhatsApp</p>

            <div class="border-t border-slate-100 pt-4 mb-4">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Para</p>
                <p class="text-sm text-slate-600">Restaurantes, areperas, pastelerías, comida a domicilio</p>
            </div>

            <div class="bg-orange-50 rounded-lg px-3 py-2 mb-5">
                <p class="text-sm font-semibold text-orange-700">
                    <span class="iconify tabler--brand-whatsapp size-4 inline-block align-middle mr-1"></span>
                    Tu cliente pide directo por WhatsApp. Sin impresiones.
                </p>
            </div>

            <a href="{{ route('onboarding.food') }}" class="sel-btn-food mt-auto">
                Crear mi menú digital →
            </a>
        </div>

        {{-- ── TARJETA 3: SYNTIcat ── --}}
        <div class="sel-card flex flex-col p-6">
            <div class="mb-4">
                <span class="iconify tabler--shopping-bag" style="font-size:2.5rem; color:#10b981;"></span>
            </div>
            <h2 class="text-lg font-black text-slate-900 mb-0.5">SYNTIcat</h2>
            <p class="text-sm font-semibold text-slate-500 mb-4">Catálogo visual + Carrito WhatsApp</p>

            <div class="border-t border-slate-100 pt-4 mb-4">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Para</p>
                <p class="text-sm text-slate-600">Tiendas de ropa, proveedores, retail con muchos productos</p>
            </div>

            <div class="bg-emerald-50 rounded-lg px-3 py-2 mb-5">
                <p class="text-sm font-semibold text-emerald-700">
                    <span class="iconify tabler--shopping-cart size-4 inline-block align-middle mr-1"></span>
                    Carrito completo. Pedido estructurado a WhatsApp.
                </p>
            </div>

            <a href="{{ route('onboarding.cat') }}" class="sel-btn-cat mt-auto">
                Crear mi catálogo →
            </a>
        </div>

    </div>{{-- /grid --}}

    {{-- ── FOOTER MÍNIMO ── --}}
    <div class="text-center mt-12 pb-8">
        <p class="text-sm text-slate-400">
            ¿Dudas?
            <a href="https://wa.me/58000000000" class="text-emerald-600 font-semibold hover:underline" target="_blank" rel="noopener">
                Escríbenos por WhatsApp
            </a>
        </p>
    </div>
</main>

</body>
</html>
