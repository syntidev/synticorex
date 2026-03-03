<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Página creada! — {{ $tenant->business_name }} · SYNTIweb</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js" defer></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Inter', system-ui, sans-serif;
            background: #0f172a;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        /* ── Top toolbar ── */
        .toolbar {
            height: 52px;
            flex-shrink: 0;
            background: #1e293b;
            display: flex;
            align-items: center;
            padding: 0 1.25rem;
            gap: 10px;
            border-bottom: 1px solid rgba(255,255,255,.07);
        }
        .toolbar-name {
            font-size: 13px;
            font-weight: 600;
            color: rgba(255,255,255,.8);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            flex: 1;
            min-width: 0;
        }
        .badge-preview {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: rgba(251,191,36,.15);
            color: #fbbf24;
            border: 1px solid rgba(251,191,36,.3);
            border-radius: 6px;
            padding: 2px 8px;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .btn-visit {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(255,255,255,.07);
            color: rgba(255,255,255,.8);
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 7px;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .btn-visit:hover { background: rgba(255,255,255,.12); color: #fff; }
        .btn-dash {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, #3b82f6, #4f46e5);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 7px 16px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            white-space: nowrap;
            flex-shrink: 0;
            box-shadow: 0 2px 10px rgba(59,130,246,.4);
        }
        .btn-dash:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(59,130,246,.5); }

        /* ── Success info panel ── */
        .info-panel {
            flex-shrink: 0;
            background: #020c1b;
            border-bottom: 1px solid rgba(255,255,255,.06);
            padding: 0;
            overflow: hidden;
        }
        /* Hero success strip */
        .success-hero {
            padding: 12px 1.25rem 10px;
            background: linear-gradient(135deg, #052818 0%, #052035 100%);
            border-bottom: 1px solid rgba(255,255,255,.06);
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .success-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: rgba(52,211,153,.15);
            border: 1px solid rgba(52,211,153,.3);
            flex-shrink: 0;
        }
        .success-text h2 {
            margin: 0;
            font-size: 14px;
            font-weight: 800;
            color: #34d399;
            line-height: 1.2;
        }
        .success-text p {
            margin: 2px 0 0;
            font-size: 11px;
            color: rgba(255,255,255,.45);
        }
        /* URLs row */
        .urls-row {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 1.25rem;
            flex-wrap: wrap;
        }
        .url-block {
            display: flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 8px;
            padding: 6px 10px;
            min-width: 0;
            flex-shrink: 0;
        }
        .url-type {
            font-size: 10px;
            font-weight: 700;
            color: rgba(255,255,255,.35);
            white-space: nowrap;
            text-transform: uppercase;
            letter-spacing: .05em;
        }
        .url-val {
            font-size: 11.5px;
            font-family: 'Courier New', monospace;
            color: #60a5fa;
            white-space: nowrap;
            max-width: 210px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .url-val a { color: #60a5fa; text-decoration: none; }
        .url-val a:hover { text-decoration: underline; }
        .btn-copy {
            background: rgba(255,255,255,.07);
            border: 1px solid rgba(255,255,255,.1);
            color: rgba(255,255,255,.45);
            border-radius: 5px;
            padding: 3px 7px;
            font-size: 10px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            flex-shrink: 0;
        }
        .btn-copy:hover { background: rgba(255,255,255,.13); color: #fff; }
        /* Next steps */
        .next-steps {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 1.25rem;
            background: rgba(79,70,229,.08);
            border-top: 1px solid rgba(79,70,229,.15);
            flex-wrap: wrap;
        }
        .step-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            color: rgba(255,255,255,.5);
            white-space: nowrap;
        }
        .step-num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 17px;
            height: 17px;
            border-radius: 50%;
            background: rgba(79,70,229,.4);
            color: #a5b4fc;
            font-size: 9px;
            font-weight: 800;
            flex-shrink: 0;
        }
        .step-sep { color: rgba(255,255,255,.15); font-size: 14px; }
        .pin-tip {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 5px;
            background: rgba(251,191,36,.08);
            border: 1px solid rgba(251,191,36,.2);
            border-radius: 6px;
            padding: 4px 9px;
            font-size: 11px;
            color: #fde68a;
            flex-shrink: 0;
        }
        .pin-tip strong { font-family: monospace; font-size: 13px; letter-spacing: .1em; }

        /* ── iframe ── */
        iframe {
            flex: 1;
            display: block;
            width: 100%;
            border: none;
            min-height: 0;
        }
    </style>
</head>
<body>

    @php
        $landingLocal = config('app.url') . '/' . $tenant->subdomain;
        $landingProd  = 'https://' . $tenant->subdomain . '.' . ($tenant->base_domain ?? 'syntiweb.com');
        $dashUrl      = config('app.url') . '/tenant/' . $tenant->id . '/dashboard';
    @endphp

    {{-- ── Toolbar ── --}}
    <div class="toolbar">
        <img src="{{ asset('brand/syntiweb-logo-negative.svg') }}" width="22" height="22" alt="SYNTIweb" style="flex-shrink:0;">
        <div class="badge-preview">
            <iconify-icon icon="tabler:eye" width="12"></iconify-icon>
            Vista previa
        </div>
        <span class="toolbar-name">{{ $tenant->business_name }}</span>
        <a href="{{ $landingLocal }}" target="_blank" class="btn-visit">
            <iconify-icon icon="tabler:external-link" width="13"></iconify-icon>
            Ver sitio
        </a>
        <a href="{{ $dashUrl }}" class="btn-dash">
            <iconify-icon icon="tabler:layout-dashboard" width="14"></iconify-icon>
            Ir a mi panel
        </a>
    </div>

    {{-- ── Info panel ── --}}
    <div class="info-panel">

        {{-- Success hero strip --}}
        <div class="success-hero">
            <div class="success-icon">
                <iconify-icon icon="tabler:circle-check-filled" width="20" style="color:#34d399"></iconify-icon>
            </div>
            <div class="success-text">
                <h2>¡Tu página web ya está en línea, {{ explode(' ', $tenant->business_name)[0] }}!</h2>
                <p>Tu negocio ahora tiene presencia digital real. Aquí están tus accesos:</p>
            </div>
        </div>

        {{-- URLs row --}}
        <div class="urls-row">

            <div class="url-block">
                <span class="url-type">🌐 Sitio web</span>
                <span class="url-val"><a href="{{ $landingLocal }}" target="_blank">{{ $landingLocal }}</a></span>
                <button class="btn-copy" onclick="copyUrl('{{ $landingLocal }}', this)">Copiar</button>
            </div>

            <div class="url-block">
                <span class="url-type">🚀 Producción</span>
                <span class="url-val" title="{{ $landingProd }}">{{ $landingProd }}</span>
                <button class="btn-copy" onclick="copyUrl('{{ $landingProd }}', this)">Copiar</button>
            </div>

            <div class="url-block">
                <span class="url-type">⚙️ Panel admin</span>
                <span class="url-val"><a href="{{ $dashUrl }}" target="_blank">{{ $dashUrl }}</a></span>
                <button class="btn-copy" onclick="copyUrl('{{ $dashUrl }}', this)">Copiar</button>
            </div>

        </div>

        {{-- Next steps guide --}}
        <div class="next-steps">
            <span style="font-size:10px;font-weight:700;color:rgba(255,255,255,.3);text-transform:uppercase;letter-spacing:.05em;white-space:nowrap;flex-shrink:0;">Próximos pasos</span>
            <span class="step-item"><span class="step-num">1</span>Sube tu logo y foto del hero</span>
            <span class="step-sep">›</span>
            <span class="step-item"><span class="step-num">2</span>Agrega tus productos o servicios</span>
            <span class="step-sep">›</span>
            <span class="step-item"><span class="step-num">3</span>Elige tu paleta de colores</span>
            <span class="step-sep">›</span>
            <span class="step-item"><span class="step-num">4</span>Comparte tu enlace</span>
            <div class="pin-tip">
                <iconify-icon icon="tabler:key" width="12"></iconify-icon>
                PIN del panel: <strong>1234</strong>
            </div>
        </div>

    </div>

    {{-- ── Landing preview ── --}}
    <iframe src="{{ $landingLocal }}"
            title="Vista previa — {{ $tenant->business_name }}">
    </iframe>

    <script>
    function copyUrl(text, btn) {
        navigator.clipboard.writeText(text).then(() => {
            const orig = btn.textContent;
            btn.textContent = '✓';
            btn.style.color = '#34d399';
            setTimeout(() => { btn.textContent = orig; btn.style.color = ''; }, 2000);
        }).catch(() => {
            const ta = document.createElement('textarea');
            ta.value = text;
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
            const orig = btn.textContent;
            btn.textContent = '✓';
            setTimeout(() => { btn.textContent = orig; }, 2000);
        });
    }
    </script>

</body>
</html>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página creada — {{ $tenant->business_name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js" defer></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Inter', system-ui, sans-serif;
            background: #0f172a;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        /* ── Top toolbar ── */
        .toolbar {
            height: 52px;
            flex-shrink: 0;
            background: #1e293b;
            display: flex;
            align-items: center;
            padding: 0 1.25rem;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,.07);
        }
        .toolbar-title {
            font-size: 14px;
            font-weight: 600;
            color: rgba(255,255,255,.85);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .toolbar-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: rgba(251,191,36,.15);
            color: #fbbf24;
            border: 1px solid rgba(251,191,36,.3);
            border-radius: 6px;
            padding: 2px 8px;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }
        .btn-dash {
            margin-left: auto;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, #3b82f6, #4f46e5);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 7px 16px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            white-space: nowrap;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(59,130,246,.35);
        }
        .btn-dash:hover { opacity: .9; }

        /* ── Success info banner ── */
        .info-banner {
            flex-shrink: 0;
            background: #020d1a;
            border-bottom: 1px solid rgba(255,255,255,.06);
            padding: 10px 1.25rem;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .success-label {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #34d399;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }
        .url-chip {
            display: flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,.05);
            border: 1px solid rgba(255,255,255,.09);
            border-radius: 7px;
            padding: 5px 10px;
            min-width: 0;
        }
        .url-chip-label {
            font-size: 10px;
            font-weight: 600;
            color: rgba(255,255,255,.4);
            white-space: nowrap;
            text-transform: uppercase;
            letter-spacing: .04em;
        }
        .url-chip-value {
            font-size: 12px;
            font-family: 'Courier New', monospace;
            color: #60a5fa;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 260px;
        }
        .url-chip-value a {
            color: #60a5fa;
            text-decoration: none;
        }
        .url-chip-value a:hover { text-decoration: underline; }
        .copy-btn {
            background: rgba(255,255,255,.07);
            border: 1px solid rgba(255,255,255,.1);
            color: rgba(255,255,255,.5);
            border-radius: 5px;
            padding: 3px 8px;
            font-size: 10px;
            font-weight: 600;
            cursor: pointer;
            flex-shrink: 0;
            font-family: inherit;
        }
        .copy-btn:hover { background: rgba(255,255,255,.13); color: #fff; }
        .pin-chip {
            display: flex;
            align-items: center;
            gap: 6px;
            background: rgba(251,191,36,.08);
            border: 1px solid rgba(251,191,36,.25);
            border-radius: 7px;
            padding: 5px 10px;
            font-size: 11px;
            color: #fde68a;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .pin-chip strong { font-size: 14px; font-family: monospace; letter-spacing: .1em; }

        /* ── iframe ── */
        iframe {
            flex: 1;
            display: block;
            width: 100%;
            border: none;
            min-height: 0;
        }
    </style>
</head>
<body>

    {{-- ── Toolbar ── --}}
    <div class="toolbar">
        <img src="{{ asset('brand/syntiweb-logo-negative.svg') }}" width="22" height="22" alt="SYNTIweb" style="flex-shrink:0;">
        <div class="toolbar-badge">
            <iconify-icon icon="tabler:eye" width="13"></iconify-icon>
            Vista Previa
        </div>
        <span class="toolbar-title">{{ $tenant->business_name }}</span>
        <a href="/tenant/{{ $tenant->id }}/dashboard" class="btn-dash">
            <iconify-icon icon="tabler:layout-dashboard" width="15"></iconify-icon>
            Ir al Panel
        </a>
    </div>

    {{-- ── Success info strip ── --}}
    <div class="info-banner">

        <div class="success-label">
            <iconify-icon icon="tabler:circle-check-filled" width="18"></iconify-icon>
            ¡Creado!
        </div>

        {{-- URL Landing (local / dev) --}}
        @php
            $landingLocal = config('app.url') . '/' . $tenant->subdomain;
            $landingProd  = 'https://' . $tenant->subdomain . '.' . ($tenant->base_domain ?? 'syntiweb.com');
            $dashLocal    = config('app.url') . '/tenant/' . $tenant->id . '/dashboard';
        @endphp

        <div class="url-chip">
            <span class="url-chip-label">🌐 Landing</span>
            <span class="url-chip-value">
                <a href="{{ $landingLocal }}" target="_blank">{{ $landingLocal }}</a>
            </span>
            <button class="copy-btn" onclick="copyUrl('{{ $landingLocal }}', this)">Copiar</button>
        </div>

        <div class="url-chip">
            <span class="url-chip-label">🚀 Producción</span>
            <span class="url-chip-value">{{ $landingProd }}</span>
            <button class="copy-btn" onclick="copyUrl('{{ $landingProd }}', this)">Copiar</button>
        </div>

        <div class="url-chip">
            <span class="url-chip-label">⚙️ Dashboard</span>
            <span class="url-chip-value">
                <a href="{{ $dashLocal }}" target="_blank">{{ $dashLocal }}</a>
            </span>
            <button class="copy-btn" onclick="copyUrl('{{ $dashLocal }}', this)">Copiar</button>
        </div>

        <div class="pin-chip">
            <iconify-icon icon="tabler:key" width="14"></iconify-icon>
            PIN inicial: <strong>1234</strong>
        </div>

    </div>

    {{-- ── Landing preview ── --}}
    <iframe src="{{ $landingLocal }}"
            title="Vista previa — {{ $tenant->business_name }}">
    </iframe>

    <script>
    function copyUrl(text, btn) {
        navigator.clipboard.writeText(text).then(() => {
            const orig = btn.textContent;
            btn.textContent = '✓ OK';
            btn.style.color = '#34d399';
            setTimeout(() => { btn.textContent = orig; btn.style.color = ''; }, 2000);
        }).catch(() => {
            // Fallback for older browsers
            const ta = document.createElement('textarea');
            ta.value = text;
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
            const orig = btn.textContent;
            btn.textContent = '✓';
            setTimeout(() => { btn.textContent = orig; }, 2000);
        });
    }
    </script>

</body>
</html>
