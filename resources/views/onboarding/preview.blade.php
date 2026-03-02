<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista Previa — {{ $tenant->business_name }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flyonui@2.4.1/dist/flyonui.min.css">
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js" defer></script>
    <style>
        body { margin: 0; background: #0f172a; font-family: system-ui, sans-serif; }
        .toolbar { height: 56px; background: #1e293b; display: flex; align-items: center; padding: 0 1.5rem; gap: 1rem; position: sticky; top: 0; z-index: 100; border-bottom: 1px solid rgba(255,255,255,.08); }
        iframe { display: block; width: 100%; border: none; }
    </style>
</head>
<body>
    <div class="toolbar">
        <div class="badge badge-warning gap-1">
            <iconify-icon icon="tabler:eye" width="14"></iconify-icon>
            Vista Previa
        </div>
        <span class="text-white/80 text-sm font-medium">{{ $tenant->business_name }}</span>
        <span class="text-white/40 text-xs">{{ $tenant->subdomain }}.syntiweb.com</span>

        <div class="ml-auto flex items-center gap-2">
            <form method="POST" action="{{ route('onboarding.publish', $tenant->id) }}">
                @csrf
                <button type="submit"
                        class="btn btn-success btn-sm gap-2">
                    <iconify-icon icon="tabler:rocket" width="16"></iconify-icon>
                    Publicar página
                </button>
            </form>
        </div>
    </div>

    <iframe src="{{ url('/' . $tenant->subdomain) }}"
            style="height: calc(100vh - 56px);"
            title="Preview {{ $tenant->business_name }}">
    </iframe>
</body>
</html>
