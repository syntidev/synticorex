<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Panel de administración de {{ $tenant->business_name }} — gestiona tu información, productos, servicios y diseño de tu sitio web.">
    <meta name="robots" content="noindex, nofollow">
    <title>Dashboard — {{ $tenant->business_name }} | SYNTIweb</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- Chart.js para Analytics --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* ══ SYNTIWEB Dashboard — Minimal CSS ══ */
        :root { --synti: #4D8FFF; --synti-glow: rgba(77,143,255,0.14); --synti-bdr: rgba(77,143,255,0.22); --synti-soft: rgba(77,143,255,0.10); }

        /* Sidebar */
        #layout-sidebar { position:fixed; top:0; bottom:0; left:0; width:16rem; z-index:80; background:var(--color-base-100); transform:translateX(-100%); transition:transform .3s ease; display:flex !important; }
        #layout-sidebar.opened, #layout-sidebar.open { transform:translateX(0); }
        @media(min-width:1024px){ #layout-sidebar { transform:translateX(0); z-index:50; } }

        /* Tabs */
        .tab-content { display:none; } .tab-content.active { display:block; }

        /* Brand fonts */
        .card-title, .table-title, .crud-dialog-title { font-family:'Plus Jakarta Sans',sans-serif !important; color:var(--synti) !important; letter-spacing:-0.3px; }
        .sidebar-logo-text { font-family:'Plus Jakarta Sans',sans-serif; font-size:1.2rem; font-weight:800; letter-spacing:-0.5px; line-height:1; }
        .sidebar-logo-synti { color:#4D8FFF; } .sidebar-logo-web { color:var(--color-base-content); }
        #live-clock { font-family:'Plus Jakarta Sans',sans-serif; font-size:.8rem; font-weight:700; color:var(--synti); letter-spacing:.5px; }

        /* Sidebar active */
        .menu li button.menu-active, .menu li button[aria-selected="true"] { background:linear-gradient(90deg,rgba(77,143,255,.18) 0%,rgba(77,143,255,.04) 100%) !important; color:#4D8FFF !important; border-left:3px solid #4D8FFF; padding-left:calc(.75rem - 3px); font-weight:600; }
        .menu li button:not(.menu-active):not([aria-selected="true"]):hover { background:rgba(77,143,255,.07) !important; color:#4D8FFF !important; }

        /* Breathing dots */
        @keyframes synti-breathe { 0%,100%{opacity:1;box-shadow:0 0 0 0 rgba(34,197,94,.5)} 50%{opacity:.7;box-shadow:0 0 0 4px rgba(34,197,94,0)} }
        @keyframes synti-breathe-off { 0%,100%{opacity:1} 50%{opacity:.5} }
        .dot-online { display:inline-block; width:8px; height:8px; border-radius:50%; background:#22c55e; flex-shrink:0; animation:synti-breathe 2.5s ease-in-out infinite; }
        .dot-offline { display:inline-block; width:8px; height:8px; border-radius:50%; background:#ef4444; flex-shrink:0; animation:synti-breathe-off 2s ease-in-out infinite; }

        /* CRUD Modals */
        .crud-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.55); backdrop-filter:blur(4px); z-index:9999; align-items:center; justify-content:center; padding:1rem; }
        .crud-overlay.show { display:flex; }
        .crud-dialog { background:var(--color-base-100); border-radius:var(--radius-box); width:100%; max-width:600px; max-height:90vh; overflow-y:auto; z-index:10000; box-shadow:0 25px 80px rgba(0,0,0,.25); border:1px solid color-mix(in oklch,var(--color-base-content) 10%,transparent); }
        .crud-dialog-header { padding:1rem 1.25rem; border-bottom:1px solid color-mix(in oklch,var(--color-base-content) 10%,transparent); display:flex; justify-content:space-between; align-items:center; }
        .crud-dialog-close { background:color-mix(in oklch,var(--color-base-content) 8%,transparent); border:1px solid color-mix(in oklch,var(--color-base-content) 12%,transparent); color:var(--color-base-content); cursor:pointer; width:32px; height:32px; display:flex; align-items:center; justify-content:center; border-radius:var(--radius-field); transition:all .2s; }
        .crud-dialog-close:hover { background:var(--synti-soft); color:var(--synti); }
        .crud-dialog-body { padding:1.25rem; }
        @media(max-width:639px) { .crud-dialog { max-width:100%; max-height:100vh; border-radius:0; height:100%; } }

        /* Form focus ring (FlyonUI native inputs) */
        .input:focus, .textarea:focus, .select:focus, .file-input:focus { box-shadow:0 0 0 3px var(--synti-glow); }

        /* Image previews */
        .image-preview { margin-bottom:1rem; text-align:center; }
        .image-preview img { max-width:200px; max-height:200px; border-radius:var(--radius-box); object-fit:cover; border:2px solid var(--synti-bdr); }
        .gallery-thumb { position:relative; display:inline-block; }
        .gallery-thumb img { width:80px; height:80px; border-radius:6px; object-fit:cover; border:2px solid color-mix(in oklch,var(--color-base-content) 15%,transparent); }
        .gallery-thumb-delete { position:absolute; top:-6px; right:-6px; width:20px; height:20px; border-radius:50%; background:var(--color-error); color:#fff; border:none; font-size:12px; cursor:pointer; display:flex; align-items:center; justify-content:center; }
        .gallery-preview-thumb img { width:80px; height:80px; border-radius:6px; object-fit:cover; border:2px dashed var(--synti-bdr); }

        /* Icon picker */
        #icon-picker-grid { scrollbar-width:thin; scrollbar-color:var(--synti-bdr) transparent; }
        .icon-pick-item { transition:all .18s; border:1px solid color-mix(in oklch,var(--color-base-content) 10%,transparent) !important; background:var(--color-base-200) !important; color:var(--color-base-content) !important; }
        .icon-pick-item:hover { background:var(--synti-soft) !important; border-color:var(--synti-bdr) !important; color:var(--synti) !important; transform:translateY(-2px); }
        .icon-pick-item.selected { background:var(--synti) !important; border-color:var(--synti) !important; color:#fff !important; }

        /* Segmented / mode bar */
        .svc-segment { display:inline-flex; align-items:center; background:var(--color-base-200); border-radius:var(--radius-box); padding:3px; gap:2px; }
        .svc-segment button { display:flex; align-items:center; gap:6px; padding:.45rem 1rem; border-radius:calc(var(--radius-box) - 3px); font-size:.8125rem; font-weight:600; border:none; cursor:pointer; transition:all .2s; color:color-mix(in oklch,var(--color-base-content) 55%,transparent); background:transparent; }
        .svc-segment button.seg-active { background:var(--color-base-100); color:var(--synti); box-shadow:0 1px 6px rgba(0,0,0,.1); }

        /* (Legacy toggle removed — migrated to FlyonUI .toggle component) */

        /* Focus WCAG */
        :focus-visible { outline:2px solid var(--synti) !important; outline-offset:2px !important; }

        /* Scrollable sections */
        .section-scroll { max-height:400px; overflow-y:auto; scrollbar-width:thin; }

        /* QR display — force SVG to fill container exactly */
        #qr-display svg { width:100% !important; height:100% !important; display:block; }

        /* Responsive */
        @media(max-width:639px) { #header-extras { display:none !important; } }
    </style>
</head>
<body class="bg-base-200">

{{-- Skip link accesibilidad: visible solo al recibir foco por teclado --}}
<a href="#main-content"
   class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:start-2 focus:z-[9999] focus:btn focus:btn-primary focus:btn-sm">
    Saltar al contenido principal
</a>

{{-- Región aria-live para anunciar toasts a lectores de pantalla --}}
<div id="toast-announcer" aria-live="polite" aria-atomic="true" class="sr-only"></div>

<div class="bg-base-200 flex min-h-screen flex-col">

    <!-- ══ HEADER ════════════════════════════════════════════════════════ -->
    <div class="bg-base-100 border-base-content/20 lg:ps-64 sticky top-0 z-50 flex border-b">
        <div class="mx-auto w-full">
            <nav class="navbar py-2 px-3">
                <div class="navbar-start items-center gap-2">
                    {{-- Hamburger: visible solo en móvil --}}
                    <button type="button"
                            class="btn btn-soft btn-square btn-sm lg:hidden"
                            aria-haspopup="dialog"
                            aria-expanded="false"
                            aria-controls="layout-sidebar"
                            data-overlay="#layout-sidebar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/></svg>
                    </button>
                    {{-- Logo mobile --}}
                    <span class="sidebar-logo-text text-base lg:hidden">
                        <span class="sidebar-logo-synti">SYNTI</span><span class="sidebar-logo-web">web</span>
                    </span>
                    {{-- Negocio + dot de estado (desktop) --}}
                    <div class="hidden lg:flex items-center gap-2 min-w-0">
                        <span class="{{ $tenant->is_open ? 'dot-online' : 'dot-offline' }}"></span>
                        <span class="text-sm font-semibold text-base-content truncate max-w-52">{{ $tenant->business_name }}</span>
                        <span class="badge badge-soft badge-xs shrink-0
                            {{ $plan->id === 1 ? 'badge-warning' : ($plan->id === 2 ? 'badge-success' : 'badge-info') }}">
                            {{ $plan->name }}
                        </span>
                    </div>
                </div>

                {{-- Center: reloj + tasa BCV --}}
                <div id="header-extras" class="navbar-center hidden md:flex items-center gap-3">
                    <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-box bg-base-200/70 border border-base-content/8">
                        <span class="icon-[tabler--clock] size-3.5 text-base-content/50"></span>
                        <span id="live-clock" aria-label="Hora actual">--:--</span>
                    </div>
                    <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-box bg-base-200/70 border border-base-content/8">
                        <span class="icon-[tabler--currency-dollar] size-3.5 text-green-500"></span>
                        <span class="text-xs font-semibold text-base-content/70">
                            Bs. <span id="header-dollar-rate">{{ number_format($dollarRate, 2) }}</span>
                        </span>
                    </div>
                </div>

                {{-- End: estado + cerrar --}}
                <div class="navbar-end items-center gap-2">
                    <span class="{{ $tenant->is_open ? 'dot-online' : 'dot-offline' }} lg:hidden"></span>
                    <a href="/{{ $tenant->subdomain }}"
                       class="btn btn-soft btn-sm gap-1.5">
                        <span class="icon-[tabler--layout-sidebar-right-collapse] size-4"></span>
                        <span class="hidden sm:inline">Cerrar</span>
                    </a>
                </div>
            </nav>
        </div>
    </div>

    <!-- ══ SIDEBAR ═══════════════════════════════════════════════════════ -->
    <aside id="layout-sidebar"
           class="overlay [--auto-close:lg]"
           aria-label="Sidebar"
           tabindex="-1">
        <div class="drawer-body border-base-content/20 h-full border-e p-0">
            <div class="flex h-full max-h-full flex-col">
                {{-- Close (mobile) --}}
                <button type="button"
                        class="btn btn-text btn-circle btn-sm absolute end-3 top-3 lg:hidden"
                        aria-label="Close"
                        data-overlay="#layout-sidebar">
                    <span class="icon-[tabler--x] size-4.5"></span>
                </button>

                {{-- Logo --}}
                <div class="text-base-content border-base-content/20 flex items-center gap-3 border-b px-5 py-5">
                    <svg viewBox="0 0 900 900" class="size-8 shrink-0" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="0" y="0" width="560" height="80" fill="currentColor"/>
                        <rect x="0" y="80" width="80" height="480" fill="currentColor"/>
                        <rect x="820" y="300" width="80" height="520" fill="currentColor"/>
                        <rect x="340" y="820" width="560" height="80" fill="currentColor"/>
                        <rect x="0" y="700" width="80" height="120" fill="currentColor"/>
                        <rect x="0" y="820" width="200" height="80" fill="currentColor"/>
                        <circle cx="780" cy="120" r="120" fill="#4D8FFF"/>
                    </svg>
                    <div>
                        <span class="sidebar-logo-text">
                            <span class="sidebar-logo-synti">SYNTI</span><span class="sidebar-logo-web">web</span>
                        </span>
                        <p class="text-base-content/60 text-xs">{{ $tenant->business_name }}</p>
                    </div>
                </div>

                {{-- Navigation --}}
                <div class="h-full overflow-y-auto">
                    <ul class="menu menu-sm gap-1 p-3" role="tablist">
                        <li role="presentation">
                            <button class="menu-active w-full text-start" role="tab"
                                    aria-selected="true" aria-controls="tab-info"
                                    id="tab-info-btn" data-tab="info" tabindex="0">
                                <span class="icon-[tabler--info-circle] size-4.5"></span>
                                <span class="grow">Información</span>
                            </button>
                        </li>
                        <li role="presentation">
                            <button class="w-full text-start" role="tab"
                                    aria-selected="false" aria-controls="tab-productos"
                                    id="tab-productos-btn" data-tab="productos" tabindex="-1">
                                <span class="icon-[tabler--package] size-4.5"></span>
                                <span class="grow">Productos</span>
                            </button>
                        </li>
                        <li role="presentation">
                            <button class="w-full text-start" role="tab"
                                    aria-selected="false" aria-controls="tab-servicios"
                                    id="tab-servicios-btn" data-tab="servicios" tabindex="-1">
                                <span class="icon-[tabler--tool] size-4.5"></span>
                                <span class="grow">Servicios</span>
                            </button>
                        </li>
                        <li role="presentation">
                            <button class="w-full text-start" role="tab"
                                    aria-selected="false" aria-controls="tab-diseno"
                                    id="tab-diseno-btn" data-tab="diseno" tabindex="-1">
                                <span class="icon-[tabler--palette] size-4.5"></span>
                                <span class="grow">Diseño</span>
                            </button>
                        </li>
                        <li role="presentation">
                            <button class="w-full text-start" role="tab"
                                    aria-selected="false" aria-controls="tab-analytics"
                                    id="tab-analytics-btn" data-tab="analytics" tabindex="-1">
                                <span class="icon-[tabler--chart-bar] size-4.5"></span>
                                <span class="grow">Analytics</span>
                            </button>
                        </li>
                        @if($plan->id === 3)
                        <li role="presentation">
                            <button class="w-full text-start" role="tab"
                                    aria-selected="false" aria-controls="tab-sucursales"
                                    id="tab-sucursales-btn" data-tab="sucursales" tabindex="-1">
                                <span class="icon-[tabler--map-pin] size-4.5"></span>
                                <span class="grow">Sucursales</span>
                            </button>
                        </li>
                        @endif
                        <li role="presentation">
                            <button class="w-full text-start" role="tab"
                                    aria-selected="false" aria-controls="tab-config"
                                    id="tab-config-btn" data-tab="config" tabindex="-1">
                                <span class="icon-[tabler--settings] size-4.5"></span>
                                <span class="grow">Configuración</span>
                            </button>
                        </li>
                    </ul>
                </div>

                {{-- Footer --}}
                <div class="border-base-content/20 border-t p-3 mt-auto">
                    <a href="/{{ $tenant->subdomain }}" target="_blank" rel="noopener noreferrer"
                       class="btn btn-soft btn-block btn-sm gap-2">
                        <span class="icon-[tabler--external-link] size-4"></span>
                        Ver mi sitio
                    </a>
                </div>
            </div>
        </div>
    </aside>

    <!-- ══ LAYOUT CONTENT ════════════════════════════════════════════════ -->
    <div class="lg:ps-64 flex grow flex-col">

    {{-- ── Plan Expiry Notices ──────────────────────────────────────────── --}}
    @if($isFrozen)
    {{-- FROZEN: subscription expired --}}
    <div class="alert alert-error rounded-none border-x-0 border-t-0 flex items-center gap-3 flex-wrap px-6">
        <span class="icon-[tabler--circle-x] size-6 shrink-0" aria-hidden="true"></span>
        <div class="flex-1 min-w-0">
            <p class="font-bold text-sm">Tu plan venció — tu landing pública está pausada</p>
            <p class="text-sm opacity-80">
                @if($graceRemainingDays !== null && $graceRemainingDays > 0)
                    Tienes <strong>{{ $graceRemainingDays }} día{{ $graceRemainingDays === 1 ? '' : 's' }}</strong> de gracia antes de que tu cuenta se archive. Ningún dato se borra.
                @else
                    El período de gracia ha vencido. Contacta a soporte para renovar y restaurar tu sitio.
                @endif
            </p>
        </div>
        <a href="mailto:soporte@synticorex.com" class="btn btn-sm btn-error border-error-content/30 text-error-content shrink-0">
            Renovar plan
        </a>
    </div>
    @elseif($isExpiringSoon && $daysUntilExpiry !== null)
    {{-- EXPIRING SOON: 30 days or fewer remaining --}}
    <div class="alert alert-warning rounded-none border-x-0 border-t-0 flex items-center gap-3 flex-wrap px-6">
        <span class="icon-[tabler--alert-triangle] size-6 shrink-0" aria-hidden="true"></span>
        <div class="flex-1 min-w-0">
            <p class="font-bold text-sm">Tu plan vence en {{ $daysUntilExpiry }} día{{ $daysUntilExpiry === 1 ? '' : 's' }}</p>
            <p class="text-sm opacity-80">
                Renueva antes del <strong>{{ $tenant->subscription_ends_at->format('d/m/Y') }}</strong> para mantener tu landing pública activa sin interrupciones.
            </p>
        </div>
        <a href="mailto:soporte@synticorex.com" class="btn btn-sm btn-warning border-warning-content/30 text-warning-content shrink-0">
            Renovar ahora
        </a>
    </div>
    @endif
    {{-- ── End Plan Expiry Notices ─────────────────────────────────────── --}}

    <!-- Content -->
    <main id="main-content" class="mx-auto w-full max-w-[1200px] flex-1 grow p-4 lg:p-6" tabindex="-1">
        
        <!-- Tab: Info -->
        <div id="tab-info" class="tab-content active">

            {{-- ══ Visual Assets: Logo + Hero + QR ═══════════════════════════ --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                {{-- Logo Card (200x200) --}}
                <div class="card bg-base-100 shadow-sm border border-base-content/10">
                    <div class="card-body p-4">
                        <p class="text-xs font-bold text-base-content/40 uppercase tracking-wider mb-2">Logo</p>
                        <div id="logo-dropzone"
                             class="bg-base-200 rounded-box h-40 flex items-center justify-center mb-3 overflow-hidden border-2 border-dashed border-transparent transition-colors cursor-pointer"
                             onclick="document.getElementById('logo-file').click()"
                             ondragover="event.preventDefault(); this.classList.add('border-primary','bg-primary/5')"
                             ondragleave="this.classList.remove('border-primary','bg-primary/5')"
                             ondrop="event.preventDefault(); this.classList.remove('border-primary','bg-primary/5'); handleDropUpload(event, 'logo')">
                            @if($customization && $customization->logo_filename)
                                <img id="logo-preview"
                                     src="{{ asset('storage/tenants/' . $tenant->id . '/' . $customization->logo_filename) }}"
                                     alt="Logo" class="max-w-full max-h-full object-contain">
                            @else
                                <div id="logo-placeholder" class="text-center text-base-content/30">
                                    <span class="icon-[tabler--cloud-upload] size-8 mb-1 block mx-auto"></span>
                                    <p class="text-xs">Arrastra o haz clic</p>
                                </div>
                            @endif
                        </div>
                        <input type="file" id="logo-file" accept="image/*" class="hidden" onchange="uploadLogo(event)">
                        <button onclick="document.getElementById('logo-file').click()"
                                class="btn btn-primary btn-block btn-sm gap-2">
                            <span class="icon-[tabler--upload] size-4"></span>
                            Cambiar Logo
                        </button>
                    </div>
                </div>

                {{-- Hero Card (400x300) --}}
                <div class="card bg-base-100 shadow-sm border border-base-content/10">
                    <div class="card-body p-4">
                        <p class="text-xs font-bold text-base-content/40 uppercase tracking-wider mb-2">Hero</p>
                        <div id="hero-dropzone"
                             class="bg-base-200 rounded-box h-40 flex items-center justify-center mb-3 overflow-hidden border-2 border-dashed border-transparent transition-colors cursor-pointer"
                             onclick="document.getElementById('hero-file').click()"
                             ondragover="event.preventDefault(); this.classList.add('border-primary','bg-primary/5')"
                             ondragleave="this.classList.remove('border-primary','bg-primary/5')"
                             ondrop="event.preventDefault(); this.classList.remove('border-primary','bg-primary/5'); handleDropUpload(event, 'hero')">
                            @if($customization && $customization->hero_main_filename)
                                <img id="hero-preview"
                                     src="{{ asset('storage/tenants/' . $tenant->id . '/' . $customization->hero_main_filename) }}"
                                     alt="Hero" class="w-full h-full object-cover">
                            @else
                                <div id="hero-placeholder" class="text-center text-base-content/30">
                                    <span class="icon-[tabler--cloud-upload] size-8 mb-1 block mx-auto"></span>
                                    <p class="text-xs">Arrastra o haz clic</p>
                                </div>
                            @endif
                        </div>
                        <input type="file" id="hero-file" accept="image/*" class="hidden" onchange="uploadHero(event)">
                        <button onclick="document.getElementById('hero-file').click()"
                                class="btn btn-primary btn-block btn-sm gap-2">
                            <span class="icon-[tabler--upload] size-4"></span>
                            Cambiar Hero
                        </button>
                    </div>
                </div>

                {{-- QR Card (200x200) --}}
                <div class="card bg-base-100 shadow-sm border border-base-content/10">
                    <div class="card-body p-4">
                        <p class="text-xs font-bold text-base-content/40 uppercase tracking-wider mb-2">QR Tracking</p>
                        <div class="flex justify-center mb-2">
                            <div class="bg-white p-2 rounded-lg border border-base-content/10" style="width:140px;height:140px;overflow:hidden;">
                                <div id="qr-display" style="width:136px;height:136px;overflow:hidden;">
                                    {!! $trackingQR !!}
                                </div>
                            </div>
                        </div>
                        <p class="text-[10px] text-base-content/40 text-center mb-2 break-all leading-tight select-all">{{ $trackingShortlink }}</p>
                        <div class="flex gap-2">
                            <a href="/tenant/{{ $tenant->id }}/qr/download" class="btn btn-primary btn-sm gap-1 flex-1" download>
                                <span class="icon-[tabler--download] size-3.5"></span>
                                PNG
                            </a>
                            <button type="button" onclick="downloadQRSVG()" class="btn btn-soft btn-sm gap-1 flex-1">
                                <span class="icon-[tabler--file-type-svg] size-3.5"></span>
                                SVG
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══ Info Form ══════════════════════════════════════════════════ --}}
            <form id="form-info" onsubmit="saveInfo(event)">
                <div class="card bg-base-100 shadow-sm border border-base-content/10">
                    <div class="card-header">
                        <h2 class="card-title flex items-center gap-2">
                            <span class="icon-[tabler--building-store] size-5 text-primary"></span>
                            Información del Negocio
                        </h2>
                        <p class="text-xs text-base-content/50 mt-0.5">Datos que se muestran en tu landing pública</p>
                    </div>
                    <div class="card-body pt-2">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="form-control">
                                <label class="label pb-1" for="info-name">
                                    <span class="label-text font-medium text-sm">Nombre del Negocio *</span>
                                </label>
                                <input id="info-name" type="text" class="input input-bordered w-full"
                                       name="business_name" value="{{ $tenant->business_name }}" required autocomplete="organization">
                            </div>
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-medium text-sm">Eslogan / Tagline</span>
                                </label>
                                <input type="text" name="tagline" class="input input-bordered w-full"
                                       value="{{ $tenant->tagline }}" placeholder="Tu frase corta" autocomplete="off">
                            </div>
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-medium text-sm">Subdominio</span>
                                </label>
                                <input type="text" class="input input-bordered w-full bg-base-200 text-base-content/60"
                                       value="{{ $tenant->subdomain }}" disabled>
                            </div>
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-medium text-sm">Teléfono</span>
                                </label>
                                <input type="tel" name="phone" class="input input-bordered w-full"
                                       value="{{ $tenant->phone }}" placeholder="+58 XXX XXXXXXX" autocomplete="tel">
                            </div>
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-medium text-sm">WhatsApp</span>
                                </label>
                                <input type="tel" name="whatsapp" class="input input-bordered w-full"
                                       value="{{ $tenant->whatsapp }}" placeholder="+58 XXX XXXXXXX" autocomplete="tel">
                            </div>
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-medium text-sm">Email</span>
                                </label>
                                <input type="email" name="email" class="input input-bordered w-full"
                                       value="{{ $tenant->email }}" autocomplete="email">
                            </div>
                            <div class="form-control sm:col-span-2">
                                <label class="label pb-1" for="info-address">
                                    <span class="label-text font-medium text-sm">Dirección</span>
                                </label>
                                <input id="info-address" type="text" class="input input-bordered w-full"
                                       name="address" value="{{ $tenant->address }}" autocomplete="street-address">
                            </div>
                            <div class="form-control">
                                <label class="label pb-1" for="info-city">
                                    <span class="label-text font-medium text-sm">Ciudad</span>
                                </label>
                                <input id="info-city" type="text" class="input input-bordered w-full"
                                       name="city" value="{{ $tenant->city }}" autocomplete="address-level2">
                            </div>
                            @if($tenant->plan_id >= 2)
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-medium text-sm flex items-center gap-1">
                                        Título Contacto
                                        <span class="badge badge-soft badge-success badge-xs">{{ $plan->name }}</span>
                                    </span>
                                </label>
                                <input type="text" name="contact_title" class="input input-bordered w-full"
                                       value="{{ data_get($tenant->settings, 'business_info.contact.title', '') }}"
                                       placeholder="Contáctanos">
                            </div>
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-medium text-sm flex items-center gap-1">
                                        Subtítulo Contacto
                                        <span class="badge badge-soft badge-success badge-xs">{{ $plan->name }}</span>
                                    </span>
                                </label>
                                <input type="text" name="contact_subtitle" class="input input-bordered w-full"
                                       value="{{ data_get($tenant->settings, 'business_info.contact.subtitle', '') }}"
                                       placeholder="Estamos aquí para atenderte">
                            </div>
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-medium text-sm flex items-center gap-1">
                                        Teléfono Secundario
                                        <span class="badge badge-soft badge-success badge-xs">{{ $plan->name }}</span>
                                    </span>
                                </label>
                                <input type="tel" name="phone_secondary" class="input input-bordered w-full"
                                       value="{{ data_get($tenant->settings, 'contact_info.phone_secondary', '') }}"
                                       placeholder="+58 XXX XXXXXXX">
                            </div>
                            <div class="form-control sm:col-span-2 lg:col-span-3">
                                <label class="label pb-1">
                                    <span class="label-text font-medium text-sm flex items-center gap-1">
                                        URL Google Maps
                                        <span class="badge badge-soft badge-success badge-xs">{{ $plan->name }}</span>
                                    </span>
                                </label>
                                <input type="url" name="contact_maps_url" class="input input-bordered w-full"
                                       value="{{ data_get($tenant->settings, 'business_info.contact.maps_url', '') }}"
                                       placeholder="https://www.google.com/maps/embed?pb=...">
                            </div>
                            @endif
                        </div>

                        <div class="form-control mt-3">
                            <label class="label pb-1" for="info-description">
                                <span class="label-text font-medium text-sm">Descripción del Negocio</span>
                            </label>
                            <textarea id="info-description" class="textarea textarea-bordered w-full min-h-20"
                                      name="description">{{ $tenant->description }}</textarea>
                        </div>

                        {{-- ══ Indicador de Horario (Opcional) ═══════════════════════ --}}
                        <div class="divider text-xs text-base-content/40 mt-6 mb-4">Indicador de Horario en Navbar</div>

                        <div class="alert alert-info mb-4">
                            <span class="icon-[tabler--info-circle] size-5 shrink-0"></span>
                            <div class="text-sm">
                                <p class="font-semibold">Indicador de Estado Opcional</p>
                                <p class="text-xs opacity-80">Muestra un badge "ABIERTO" o "CERRADO" en la navbar según tu horario de atención.</p>
                            </div>
                        </div>

                        <div class="form-control">
                            <label class="label cursor-pointer justify-start gap-3">
                                <input type="hidden" name="show_hours_indicator" value="0">
                                <input type="checkbox" name="show_hours_indicator" id="show-hours-toggle"
                                       class="switch switch-success"
                                       value="1"
                                       {{ data_get($tenant->settings, 'engine_settings.features.show_hours_indicator', false) ? 'checked' : '' }}
                                       onchange="toggleHoursIndicatorFields()">
                                <div class="flex-1">
                                    <span class="label-text font-medium">¿Mostrar estado de horario en navbar?</span>
                                    <p class="text-xs text-base-content/60 mt-0.5">Activa para mostrar badge ABIERTO/CERRADO junto al botón WhatsApp</p>
                                </div>
                            </label>
                        </div>

                        <div id="hours-indicator-fields" class="{{ data_get($tenant->settings, 'engine_settings.features.show_hours_indicator', false) ? '' : 'hidden' }} mt-4 p-4 bg-base-200/50 rounded-box space-y-4">
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-medium text-sm">Mensaje cuando estamos cerrados</span>
                                    <span class="label-text-alt text-base-content/50" id="char-count">0 / 150</span>
                                </label>
                                <textarea id="closed-message-input" name="closed_message" class="textarea textarea-bordered w-full min-h-16"
                                          placeholder="Estamos cerrados. Te responderemos durante nuestro horario de atención."
                                          maxlength="150"
                                          oninput="updateCharCount(); updatePreview()">{{ data_get($tenant->settings, 'business_info.closed_message', 'Estamos cerrados. Te responderemos durante nuestro horario de atención.') }}</textarea>
                                <label class="label">
                                    <span class="label-text-alt text-base-content/60">Este mensaje se usará en el botón de WhatsApp cuando tu negocio esté cerrado</span>
                                </label>
                            </div>

                            <div class="alert alert-info">
                                <span class="icon-[tabler--eye] size-5 shrink-0"></span>
                                <div class="text-xs">
                                    <p class="font-semibold mb-2">Así se verá en tu navbar</p>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="badge badge-sm gap-1 bg-success text-white border-success">
                                            <span class="icon-[tabler--circle-filled] size-3"></span>
                                            ABIERTO
                                        </span>
                                        <span class="text-base-content/30">o</span>
                                        <span class="badge badge-sm gap-1 bg-error text-white border-error">
                                            <span class="icon-[tabler--circle-filled] size-3"></span>
                                            CERRADO
                                        </span>
                                    </div>
                                    <p class="mt-3 p-2 bg-base-100/50 rounded text-xs text-base-content border-l-2 border-info">
                                        <span class="font-semibold">Mensaje WhatsApp:</span> <span id="preview-message">{{ data_get($tenant->settings, 'business_info.closed_message', 'Estamos cerrados. Te responderemos durante nuestro horario de atención.') }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 justify-end pt-4 border-t border-base-content/10 mt-4">
                            <button type="button" class="btn btn-ghost" onclick="resetForm('form-info')">Cancelar</button>
                            <button type="submit" class="btn btn-primary gap-2">
                                <span class="icon-[tabler--device-floppy] size-4" aria-hidden="true"></span>
                                Guardar Cambios
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            {{-- ══ Horario de Atención (solo lectura) ══════════════════════════ --}}
            @php
                $bhReadonly = $tenant->business_hours ?? [];
                $daysReadonly = [
                    'monday' => 'Lun', 'tuesday' => 'Mar', 'wednesday' => 'Mié',
                    'thursday' => 'Jue', 'friday' => 'Vie', 'saturday' => 'Sáb', 'sunday' => 'Dom',
                ];
            @endphp
            <div class="card bg-base-100 shadow-sm border border-base-content/10 mt-4">
                <div class="card-header flex items-center justify-between gap-2">
                    <h3 class="card-title flex items-center gap-2 text-sm">
                        <span class="icon-[tabler--clock] size-4 text-primary" aria-hidden="true"></span>
                        Horario de Atención
                    </h3>
                    <span class="text-[10px] text-base-content/40">Editar en Configuración</span>
                </div>
                <div class="card-body pt-0 pb-3">
                    <div class="flex flex-wrap gap-2">
                        @foreach($daysReadonly as $dk => $dl)
                        @php $dData = $bhReadonly[$dk] ?? null; @endphp
                        <div class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs
                            {{ $dData ? 'bg-success/10 text-success border border-success/20' : 'bg-base-200 text-base-content/40 border border-base-content/10' }}">
                            <span class="font-semibold">{{ $dl }}</span>
                            @if($dData)
                                <span>{{ $dData['open'] ?? '?' }}–{{ $dData['close'] ?? '?' }}</span>
                            @else
                                <span>Cerrado</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>

        <!-- Tab: Productos -->
        <div id="tab-productos" class="tab-content">
            @php
                $maxProducts = (int) ($plan->products_limit ?? 6);
                $currentCount = $products->count();
            @endphp

            {{-- ── Productos card ─────────────────────────────────── --}}
            <div class="card bg-base-100 shadow-sm border border-base-content/10 mb-4">
                <div class="card-header flex items-center justify-between gap-3 flex-wrap">
                    <div>
                        <h2 class="card-title flex items-center gap-2">
                            <span class="icon-[tabler--package] size-5 text-primary" aria-hidden="true"></span>
                            Productos
                        </h2>
                        <p class="text-xs text-base-content/50 mt-0.5">{{ $currentCount }} de {{ $maxProducts }} productos</p>
                    </div>
                    <button class="btn btn-primary btn-sm gap-1.5"
                            onclick="checkAndOpenProductModal()"
                            title="Agregar nuevo producto">
                        <span class="icon-[tabler--plus] size-4" aria-hidden="true"></span>
                        Agregar Producto
                    </button>
                </div>

                @if($currentCount >= $maxProducts)
                <div class="alert alert-info mx-4 mb-2 flex items-center justify-between gap-4 flex-wrap">
                    <div class="flex items-center gap-3">
                        <span class="icon-[tabler--info-circle] size-5 shrink-0" aria-hidden="true"></span>
                        <div>
                            <p class="font-semibold text-sm">Límite alcanzado ({{ $maxProducts }}/{{ $maxProducts }} productos)</p>
                            <p class="text-xs opacity-70">
                                @if($plan->id === 1)Plan CRECIMIENTO: hasta 12 · Plan VISIÓN: hasta 18
                                @else Plan VISIÓN: hasta 18 productos @endif
                            </p>
                        </div>
                    </div>
                    <a href="https://syntiweb.com/planes" target="_blank" rel="noopener noreferrer"
                       class="btn btn-primary btn-sm shrink-0">Ver Planes ↗</a>
                </div>
                @endif

                @if($products->count() > 0)
                <div class="card-body pt-2 section-scroll">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($products as $product)
                        <div class="group relative rounded-lg border border-base-content/10 bg-base-200/30 overflow-hidden transition-all hover:border-primary/30 hover:shadow-sm">
                            {{-- Imagen thumbnail --}}
                            <div class="h-32 bg-base-200 flex items-center justify-center overflow-hidden">
                                @if($product->image_filename)
                                    <img src="{{ asset('storage/tenants/' . $tenant->id . '/' . $product->image_filename) }}"
                                         alt="{{ $product->name }}"
                                         class="w-full h-full object-cover"
                                         loading="lazy" decoding="async">
                                @else
                                    <span class="icon-[tabler--package] size-10 text-base-content/20" aria-hidden="true"></span>
                                @endif
                            </div>
                            {{-- Info --}}
                            <div class="p-3">
                                <div class="flex items-start justify-between gap-2 mb-1">
                                    <h4 class="text-sm font-semibold text-base-content truncate flex-1">{{ $product->name }}</h4>
                                    @if(!$product->is_active)
                                        <span class="badge badge-soft badge-error badge-xs shrink-0">Off</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-base font-bold text-primary">${{ number_format($product->price_usd, 2) }}</span>
                                    @if($product->badge === 'hot')
                                        <span class="badge badge-soft badge-error badge-xs">🔥 Hot</span>
                                    @elseif($product->badge === 'new')
                                        <span class="badge badge-soft badge-success badge-xs">✨ New</span>
                                    @elseif($product->badge === 'promo')
                                        <span class="badge badge-soft badge-warning badge-xs">🎉 Promo</span>
                                    @endif
                                    @if($product->is_featured)
                                        <span class="badge badge-soft badge-warning badge-xs">⭐</span>
                                    @endif
                                </div>
                                {{-- Actions --}}
                                <div class="flex gap-2">
                                    <button onclick="editProduct({{ $product->id }})"
                                            class="btn btn-primary btn-sm btn-square" title="Editar">
                                        <span class="icon-[tabler--pencil] size-5" aria-hidden="true"></span>
                                    </button>
                                    <button onclick="deleteProduct({{ $product->id }})"
                                            class="btn btn-error btn-sm btn-square" title="Eliminar">
                                        <span class="icon-[tabler--trash] size-5" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="card-body flex flex-col items-center justify-center py-10 text-center">
                    <span class="icon-[tabler--package] size-10 text-base-content/20 mb-2" aria-hidden="true"></span>
                    <h3 class="font-semibold text-sm text-base-content/60 mb-1">No hay productos aún</h3>
                    <p class="text-xs text-base-content/40">Comienza agregando tu primer producto</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Modal: Producto -->
        <div id="product-modal" class="crud-overlay"
             role="dialog" aria-modal="true" aria-labelledby="product-modal-title" aria-hidden="true">
            <div class="crud-dialog">
                <div class="crud-dialog-header">
                    <h3 class="crud-dialog-title" id="product-modal-title">Agregar Producto</h3>
                    <button class="crud-dialog-close" onclick="closeProductModal()" aria-label="Cerrar modal">&times;</button>
                </div>
                <div class="crud-dialog-body">
                    <form id="product-form" onsubmit="saveProduct(event)">
                        <input type="hidden" id="product-id">

                        <div class="image-preview" id="product-image-preview" style="display: none;">
                            <img id="product-image-preview-img" src="" alt="Preview">
                        </div>

                        <div class="form-control py-2">
                            <label for="product-image" class="label"><span class="label-text text-sm font-medium">Imagen Principal del Producto</span></label>
                            <input type="file" id="product-image" accept="image/*" class="file-input file-input-bordered w-full" onchange="previewProductImage(event)">
                            <p class="text-xs text-base-content/50 mt-1">Máx. 2MB, se redimensionará a 800px</p>
                        </div>

                        {{-- Gallery Section — Plan 3 (VISIÓN) only --}}
                        @if($plan->id === 3)
                        <div class="form-control py-2" id="product-gallery-section">
                            <label class="label flex items-center gap-2">
                                <span class="icon-[tabler--photo-scan] size-4 text-primary" aria-hidden="true"></span>
                                Galería Adicional
                                <span class="text-xs text-base-content/50 font-normal">(máx. 2 fotos extra — Plan Visión)</span>
                            </label>

                            {{-- Existing gallery images container --}}
                            <div id="product-gallery-existing" class="hidden mb-3">
                                <div id="product-gallery-thumbs" class="flex gap-2.5 flex-wrap"></div>
                            </div>

                            {{-- Upload new gallery images --}}
                            <div id="product-gallery-upload-area" class="flex gap-2.5 flex-wrap mt-2">
                                <div id="gallery-slot-1" class="gallery-upload-slot hidden">
                                    <input type="file" id="product-gallery-1" accept="image/*" class="file-input file-input-bordered file-input-sm w-full" onchange="previewGalleryImage(event, 1)">
                                </div>
                                <div id="gallery-slot-2" class="gallery-upload-slot hidden">
                                    <input type="file" id="product-gallery-2" accept="image/*" class="file-input file-input-bordered file-input-sm w-full" onchange="previewGalleryImage(event, 2)">
                                </div>
                            </div>

                            {{-- Gallery previews --}}
                            <div id="product-gallery-previews" class="flex gap-2.5 mt-2 flex-wrap"></div>

                            <p class="text-xs text-base-content/50 mt-1.5">
                                Las imágenes de galería se suben al guardar el producto. Total: 1 principal + 2 galería = 3 fotos.
                            </p>
                        </div>
                        @endif

                        <div class="form-control py-2">
                            <label for="product-name" class="label"><span class="label-text text-sm font-medium">Nombre *</span></label>
                            <input type="text" id="product-name" class="input input-bordered w-full" required maxlength="100">
                        </div>

                        <div class="form-control py-2">
                            <label for="product-description" class="label"><span class="label-text text-sm font-medium">Descripción</span></label>
                            <textarea id="product-description" class="textarea textarea-bordered w-full" rows="3" maxlength="500"></textarea>
                        </div>

                        <div class="form-control py-2">
                            <label for="product-price" class="label"><span class="label-text text-sm font-medium">Precio USD *</span></label>
                            <input type="number" id="product-price" class="input input-bordered w-full" required step="0.01" min="0">
                        </div>

                        <div class="form-control py-2">
                            <label for="product-badge" class="label"><span class="label-text text-sm font-medium">Badge</span></label>
                            <select id="product-badge" class="select select-bordered w-full">
                                <option value="">Sin badge</option>
                                <option value="hot">🔥 Hot</option>
                                <option value="new">✨ New</option>
                                <option value="promo">🎉 Promo</option>
                            </select>
                        </div>

                        <div class="form-control py-2">
                            <label class="label"><span class="label-text text-sm font-medium">Producto Activo</span></label>
                            <input type="checkbox" id="product-is-active" class="toggle toggle-success" checked>
                        </div>

                        <div class="form-control py-2">
                            <label class="label"><span class="label-text text-sm font-medium">Producto Destacado ⭐</span></label>
                            <input type="checkbox" id="product-is-featured" class="toggle toggle-warning">
                        </div>

                        <div class="flex gap-2 pt-3 border-t border-base-content/10 mt-2">
                            <button type="button" class="btn btn-ghost flex-1" onclick="closeProductModal()">Cancelar</button>
                            <button type="submit" class="btn btn-primary flex-1 gap-2">
                                <span class="icon-[tabler--device-floppy] size-4" aria-hidden="true"></span>
                                Guardar Producto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tab: Servicios -->
        <div id="tab-servicios" class="tab-content">
            @php
                $maxServices = (int) ($plan->services_limit ?? 3);
                $currentServiceCount = $services->count();
            @endphp

            {{-- Global display mode selector (Plan 2/3 only) --}}
            @if($plan->id !== 1)
            <div class="alert alert-info mb-4 flex items-center justify-between gap-4 flex-wrap">
                <div>
                    <p class="font-semibold text-sm">Modo visual de servicios</p>
                    <p class="text-xs opacity-70">Elige entre íconos o imágenes — mantén la coherencia estética</p>
                </div>
                <div class="join">
                    <button type="button" id="global-mode-icon-btn"
                            onclick="setGlobalServiceMode('icon')"
                            class="btn btn-sm join-item btn-primary">
                        <span class="icon-[tabler--palette] size-4" aria-hidden="true"></span> Ícono
                    </button>
                    <button type="button" id="global-mode-image-btn"
                            onclick="setGlobalServiceMode('image')"
                            class="btn btn-sm join-item btn-ghost">
                        <span class="icon-[tabler--photo] size-4" aria-hidden="true"></span> Imagen
                    </button>
                </div>
            </div>
            @endif

            {{-- ── Servicios card ──────────────────────────────────── --}}
            <div class="card bg-base-100 shadow-sm border border-base-content/10 mb-4">
                <div class="card-header flex items-center justify-between gap-3 flex-wrap">
                    <div>
                        <h2 class="card-title flex items-center gap-2">
                            <span class="icon-[tabler--tool] size-5 text-secondary" aria-hidden="true"></span>
                            Servicios
                        </h2>
                        <p class="text-xs text-base-content/50 mt-0.5">{{ $currentServiceCount }} de {{ $maxServices }} servicios</p>
                    </div>
                    <button class="btn btn-secondary btn-sm gap-1.5"
                            onclick="checkAndOpenServiceModal()"
                            title="Agregar nuevo servicio">
                        <span class="icon-[tabler--plus] size-4" aria-hidden="true"></span>
                        Agregar Servicio
                    </button>
                </div>

                @if($currentServiceCount >= $maxServices)
                <div class="alert alert-info mx-4 mb-2 flex items-center justify-between gap-4 flex-wrap">
                    <div class="flex items-center gap-3">
                        <span class="icon-[tabler--info-circle] size-5 shrink-0" aria-hidden="true"></span>
                        <div>
                            <p class="font-semibold text-sm">Límite alcanzado ({{ $currentServiceCount }}/{{ $maxServices }} servicios)</p>
                            <p class="text-xs opacity-70">
                                @if($plan->id === 1)Plan CRECIMIENTO: hasta 6 · Plan VISIÓN: hasta 9
                                @else Plan VISIÓN: hasta 9 servicios @endif
                            </p>
                        </div>
                    </div>
                    <a href="https://syntiweb.com/planes" target="_blank" rel="noopener noreferrer"
                       class="btn btn-primary btn-sm shrink-0">Ver Planes ↗</a>
                </div>
                @endif

                @if($services->count() > 0)
                <div class="card-body pt-2 section-scroll">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($services as $service)
                        <div class="rounded-lg border border-base-content/10 bg-base-200/30 p-3 transition-all hover:border-secondary/30 hover:shadow-sm">
                            <div class="flex items-start gap-3 mb-2">
                                {{-- Icon/Image --}}
                                <div class="size-12 rounded-lg shrink-0 flex items-center justify-center overflow-hidden
                                    {{ $service->image_filename ? '' : 'bg-secondary/10 border border-secondary/20' }}">
                                    @if($service->image_filename)
                                        <img src="{{ asset('storage/tenants/' . $tenant->id . '/' . $service->image_filename) }}"
                                             alt="{{ $service->name }}"
                                             class="w-full h-full object-cover rounded-lg"
                                             loading="lazy" decoding="async">
                                    @elseif($service->icon_name)
                                        <span class="iconify tabler--{{ str_replace('_', '-', $service->icon_name) }} text-secondary text-xl"></span>
                                    @else
                                        <span class="icon-[tabler--tool] size-6 text-base-content/30" aria-hidden="true"></span>
                                    @endif
                                </div>
                                {{-- Name + Status --}}
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-semibold text-base-content truncate">{{ $service->name }}</h4>
                                    <p class="text-xs text-base-content/50 line-clamp-2 mt-0.5">
                                        {{ $service->description ? Str::limit($service->description, 80) : '—' }}
                                    </p>
                                </div>
                                @if(!$service->is_active)
                                    <span class="badge badge-soft badge-error badge-xs shrink-0">Off</span>
                                @endif
                            </div>
                            {{-- Actions --}}
                            <div class="flex gap-2">
                                <button onclick="editService({{ $service->id }})"
                                        class="btn btn-secondary btn-sm btn-square" title="Editar">
                                    <span class="icon-[tabler--pencil] size-5" aria-hidden="true"></span>
                                </button>
                                <button onclick="deleteService({{ $service->id }})"
                                        class="btn btn-error btn-sm btn-square" title="Eliminar">
                                    <span class="icon-[tabler--trash] size-5" aria-hidden="true"></span>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="card-body flex flex-col items-center justify-center py-10 text-center">
                    <span class="icon-[tabler--tool] size-10 text-base-content/20 mb-2" aria-hidden="true"></span>
                    <h3 class="font-semibold text-sm text-base-content/60 mb-1">No hay servicios aún</h3>
                    <p class="text-xs text-base-content/40">Comienza agregando tu primer servicio</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Modal: Servicio -->
        <div id="service-modal" class="crud-overlay"
             role="dialog" aria-modal="true" aria-labelledby="service-modal-title" aria-hidden="true">
            <div class="crud-dialog max-w-2xl">
                <div class="crud-dialog-header">
                    <h3 class="crud-dialog-title" id="service-modal-title">Agregar Servicio</h3>
                    <button class="crud-dialog-close" onclick="closeServiceModal()" aria-label="Cerrar modal">
                        <span class="icon-[tabler--x] size-5" aria-hidden="true"></span>
                    </button>
                </div>
                <div class="crud-dialog-body p-7">
                    <form id="service-form" onsubmit="saveService(event)">
                        <input type="hidden" id="service-id">
                        <input type="hidden" id="service-icon-name">

                        {{-- Mode tabs: Plan 2/3 only --}}
                        @if($plan->id !== 1)
                        <div class="svc-segment w-full mb-6" role="tablist" aria-label="Modo de representación del servicio">
                            <button type="button" id="svc-tab-icon" role="tab"
                                class="seg-active flex-1 flex items-center justify-center gap-2 py-2.5 px-4 rounded-btn text-sm font-bold transition-all"
                                onclick="setServiceModalMode('icon')">
                                <span class="icon-[tabler--color-picker] size-4" aria-hidden="true"></span> Ícono
                            </button>
                            <button type="button" id="svc-tab-image" role="tab"
                                class="flex-1 flex items-center justify-center gap-2 py-2.5 px-4 rounded-btn text-sm font-bold transition-all"
                                onclick="setServiceModalMode('image')">
                                <span class="icon-[tabler--photo-up] size-4" aria-hidden="true"></span> Imagen
                            </button>
                        </div>
                        @endif

                        {{-- ICON PICKER (Plan 1: always; Plan 2/3: when icon mode) --}}
                        <div id="svc-section-icon" class="form-control py-2 mb-4">
                            <label class="label"><span class="label-text text-sm font-semibold">Ícono del Servicio</span></label>

                            {{-- Current selection preview --}}
                            <div class="flex flex-col items-center p-7 rounded-2xl mb-6 border" style="background:linear-gradient(135deg,var(--synti-soft) 0%,transparent 60%);border-color:var(--synti-bdr);">
                                <div class="size-20 rounded-full flex items-center justify-center shrink-0 mb-4 border-2" style="background:var(--synti-soft);border-color:var(--synti-bdr);">
                                    <span id="icon-preview-el" class="iconify tabler--settings size-12 text-primary"></span>
                                </div>
                                <p id="icon-preview-label" class="text-sm font-semibold text-base-content text-center mb-0">Sin ícono seleccionado</p>
                                <p class="text-xs text-base-content/40 text-center mt-1">Busca y selecciona un ícono</p>
                            </div>

                            {{-- Search --}}
                            <input type="text" id="icon-search" class="input input-sm w-full mb-4"
                                placeholder="Busca: scissors, camera, truck, heart..."
                                oninput="filterIcons(this.value)" autocomplete="off">

                            {{-- Icon Grid --}}
                            <div id="icon-picker-grid" class="grid grid-cols-6 gap-3 max-h-80 overflow-y-auto p-3 rounded-xl bg-base-200/60 border border-base-content/8"></div>
                            <p class="text-xs text-base-content/30 mt-3 text-center">60+ iconos disponibles</p>
                            
                            {{-- Hidden div to force Tailwind to generate icon classes --}}
                            <div class="hidden">
                                <span class="iconify tabler--briefcase"></span><span class="iconify tabler--building-store"></span><span class="iconify tabler--award"></span>
                                <span class="iconify tabler--certificate"></span><span class="iconify tabler--crown"></span><span class="iconify tabler--diamond"></span>
                                <span class="iconify tabler--rocket"></span><span class="iconify tabler--target"></span><span class="iconify tabler--trophy"></span>
                                <span class="iconify tabler--star"></span><span class="iconify tabler--heart"></span><span class="iconify tabler--thumb-up"></span>
                                <span class="iconify tabler--shield-check"></span><span class="iconify tabler--rosette-discount-check"></span>
                                <span class="iconify tabler--tool"></span><span class="iconify tabler--hammer"></span><span class="iconify tabler--paint"></span>
                                <span class="iconify tabler--scissors"></span><span class="iconify tabler--needle-thread"></span><span class="iconify tabler--pencil-bolt"></span>
                                <span class="iconify tabler--bolt"></span><span class="iconify tabler--car"></span><span class="iconify tabler--home"></span>
                                <span class="iconify tabler--building"></span><span class="iconify tabler--bucket"></span><span class="iconify tabler--wash"></span>
                                <span class="iconify tabler--device-desktop"></span><span class="iconify tabler--device-mobile"></span><span class="iconify tabler--wifi"></span>
                                <span class="iconify tabler--cpu"></span><span class="iconify tabler--code"></span><span class="iconify tabler--cloud"></span>
                                <span class="iconify tabler--headset"></span><span class="iconify tabler--printer"></span>
                                <span class="iconify tabler--camera"></span><span class="iconify tabler--video"></span><span class="iconify tabler--microphone"></span>
                                <span class="iconify tabler--palette"></span><span class="iconify tabler--ballpen"></span><span class="iconify tabler--photo"></span>
                                <span class="iconify tabler--stethoscope"></span><span class="iconify tabler--first-aid-kit"></span><span class="iconify tabler--activity"></span>
                                <span class="iconify tabler--bath"></span><span class="iconify tabler--barbell"></span><span class="iconify tabler--leaf"></span>
                                <span class="iconify tabler--eye"></span><span class="iconify tabler--brain"></span>
                                <span class="iconify tabler--book"></span><span class="iconify tabler--school"></span><span class="iconify tabler--pencil"></span><span class="iconify tabler--flask"></span>
                                <span class="iconify tabler--soup"></span><span class="iconify tabler--pizza"></span><span class="iconify tabler--coffee"></span><span class="iconify tabler--apple"></span>
                                <span class="iconify tabler--shopping-cart"></span><span class="iconify tabler--package"></span><span class="iconify tabler--truck"></span><span class="iconify tabler--map-pin"></span>
                                <span class="iconify tabler--phone"></span><span class="iconify tabler--mail"></span><span class="iconify tabler--message-circle"></span>
                                <span class="iconify tabler--calendar"></span><span class="iconify tabler--clock"></span><span class="iconify tabler--users"></span><span class="iconify tabler--user-check"></span>
                                <span class="iconify tabler--settings"></span><span class="iconify tabler--tool"></span>
                            </div>
                        </div>

                        {{-- IMAGE UPLOAD (Plan 2/3 only; hidden by default) --}}
                        @if($plan->id !== 1)
                        <div id="svc-section-image" class="form-control py-2" style="display: none;">
                            <label class="label"><span class="label-text text-sm font-medium">Imagen del Servicio</span></label>
                            <div class="image-preview" id="service-image-preview" style="display: none;">
                                <img id="service-image-preview-img" src="" alt="Preview">
                            </div>
                            <input type="file" id="service-image" accept="image/*" class="file-input file-input-bordered w-full" onchange="previewServiceImage(event)">
                            <p class="text-xs text-base-content/50 mt-1">Máx. 2MB, se redimensionará a 800px</p>
                        </div>
                        @endif

                        <div class="form-control py-2">
                            <label for="service-name" class="label"><span class="label-text text-sm font-medium">Nombre *</span></label>
                            <input type="text" id="service-name" class="input input-bordered w-full" required maxlength="100">
                        </div>

                        <div class="form-control py-2">
                            <label for="service-description" class="label"><span class="label-text text-sm font-medium">Descripción</span></label>
                            <textarea id="service-description" class="textarea textarea-bordered w-full" rows="3" maxlength="500"></textarea>
                        </div>

                        <div class="form-control py-2">
                            <label class="label"><span class="label-text text-sm font-medium">Servicio Activo</span></label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" id="service-is-active" class="toggle toggle-success" checked>
                                <span class="text-sm text-base-content/60">Mostrar en landing page</span>
                            </label>
                        </div>

                        <div class="flex gap-3 pt-6 border-t border-base-content/10 justify-end">
                            <button type="button" class="btn btn-soft" onclick="closeServiceModal()">Cancelar</button>
                            <button type="submit" class="btn btn-primary gap-2">
                                <span class="icon-[tabler--device-floppy] size-4" aria-hidden="true"></span>
                                Guardar Servicio
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal: Agregar/Editar Sucursal -->
        <div id="branch-modal" class="crud-overlay"
             role="dialog" aria-modal="true" aria-labelledby="branch-modal-title" aria-hidden="true">
            <div class="crud-dialog max-w-md">
                <div class="crud-dialog-header">
                    <h3 class="crud-dialog-title" id="branch-modal-title">+ Agregar Sucursal</h3>
                    <button class="crud-dialog-close" onclick="closeBranchModal()" aria-label="Cerrar modal">&times;</button>
                </div>
                <div class="crud-dialog-body">
                    <input type="hidden" id="branch-edit-id" value="">
                    <form id="branch-form" onsubmit="saveBranch(event)" class="flex flex-col gap-3">
                        <div class="form-control py-2">
                            <label for="branch-name" class="label"><span class="label-text text-sm font-medium">Nombre *</span></label>
                            <input type="text" id="branch-name" class="input input-bordered w-full" required maxlength="150" placeholder="Sede Centro, Sucursal Altamira...">
                        </div>

                        <div class="form-control py-2">
                            <label for="branch-address" class="label"><span class="label-text text-sm font-medium">Dirección *</span></label>
                            <textarea id="branch-address" class="textarea textarea-bordered w-full" rows="2" required maxlength="500" placeholder="Av. Libertador, Torre X, Piso 3..."></textarea>
                        </div>

                        <div class="flex gap-3 pt-6 border-t border-base-content/10 justify-end">
                            <button type="button" class="btn btn-soft" onclick="closeBranchModal()">Cancelar</button>
                            <button type="submit" class="btn btn-primary gap-2">
                                <span class="icon-[tabler--device-floppy] size-4" aria-hidden="true"></span>
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal: Editar Testimonial -->
        <div id="testimonial-modal" class="crud-overlay"
             role="dialog" aria-modal="true" aria-labelledby="testimonial-modal-title" aria-hidden="true">
            <div class="crud-dialog max-w-md">
                <div class="crud-dialog-header">
                    <h3 class="crud-dialog-title" id="testimonial-modal-title">Editar Testimonial</h3>
                    <button class="crud-dialog-close" onclick="closeTestimonialModal()" aria-label="Cerrar modal">&times;</button>
                </div>
                <div class="crud-dialog-body">
                    <input type="hidden" id="testimonial-edit-index" value="">
                    <form id="testimonial-form" onsubmit="saveTestimonialItem(event)" class="flex flex-col gap-3">
                        <div class="form-control py-2">
                            <label for="testimonial-name" class="label"><span class="label-text text-sm font-medium">Nombre *</span></label>
                            <input type="text" id="testimonial-name" class="input input-bordered w-full" required maxlength="100" placeholder="Juan Pérez...">
                        </div>

                        <div class="form-control py-2">
                            <label for="testimonial-title" class="label"><span class="label-text text-sm font-medium">Cargo/Rol</span></label>
                            <input type="text" id="testimonial-title" class="input input-bordered w-full" maxlength="100" placeholder="CEO de Empresa...">
                        </div>

                        <div class="form-control py-2">
                            <label for="testimonial-text" class="label"><span class="label-text text-sm font-medium">Testimonio *</span></label>
                            <textarea id="testimonial-text" class="textarea textarea-bordered w-full" rows="3" required maxlength="200" placeholder="Excelente servicio..."></textarea>
                        </div>

                        <div class="form-control py-2">
                            <label for="testimonial-rating" class="label"><span class="label-text text-sm font-medium">Calificación</span></label>
                            <select id="testimonial-rating" class="select select-bordered w-full">
                                <option value="5" selected>★★★★★ Excelente (5)</option>
                                <option value="4">★★★★☆ Muy bueno (4)</option>
                                <option value="3">★★★☆☆ Bueno (3)</option>
                                <option value="2">★★☆☆☆ Aceptable (2)</option>
                                <option value="1">★☆☆☆☆ Pobre (1)</option>
                            </select>
                        </div>

                        <div class="flex gap-3 pt-6 border-t border-base-content/10 justify-end">
                            <button type="button" class="btn btn-soft" onclick="closeTestimonialModal()">Cancelar</button>
                            <button type="submit" class="btn btn-primary gap-2">
                                <span class="icon-[tabler--device-floppy] size-4" aria-hidden="true"></span>
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal: Editar FAQ -->
        <div id="faq-modal" class="crud-overlay"
             role="dialog" aria-modal="true" aria-labelledby="faq-modal-title" aria-hidden="true">
            <div class="crud-dialog max-w-md">
                <div class="crud-dialog-header">
                    <h3 class="crud-dialog-title" id="faq-modal-title">Editar Pregunta</h3>
                    <button class="crud-dialog-close" onclick="closeFaqModal()" aria-label="Cerrar modal">&times;</button>
                </div>
                <div class="crud-dialog-body">
                    <input type="hidden" id="faq-edit-index" value="">
                    <form id="faq-form" onsubmit="saveFaqItem(event)" class="flex flex-col gap-3">
                        <div class="form-control py-2">
                            <label for="faq-question" class="label"><span class="label-text text-sm font-medium">Pregunta *</span></label>
                            <input type="text" id="faq-question" class="input input-bordered w-full" required maxlength="150" placeholder="¿Cuáles son tus horarios?...">
                        </div>

                        <div class="form-control py-2">
                            <label for="faq-answer" class="label"><span class="label-text text-sm font-medium">Respuesta *</span></label>
                            <textarea id="faq-answer" class="textarea textarea-bordered w-full" rows="3" required maxlength="300" placeholder="Abierto de lunes a viernes..."></textarea>
                        </div>

                        <div class="flex gap-3 pt-6 border-t border-base-content/10 justify-end">
                            <button type="button" class="btn btn-soft" onclick="closeFaqModal()">Cancelar</button>
                            <button type="submit" class="btn btn-secondary gap-2">
                                <span class="icon-[tabler--device-floppy] size-4" aria-hidden="true"></span>
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal: Límite de Plan -->
        <div id="limit-modal" class="crud-overlay"
             role="dialog" aria-modal="true" aria-labelledby="limit-modal-title" aria-hidden="true">
            <div class="crud-dialog max-w-md">
                <div class="crud-dialog-header">
                    <h3 class="crud-dialog-title" id="limit-modal-title">⚠️ Límite Alcanzado</h3>
                    <button class="crud-dialog-close" onclick="closeLimitModal()" aria-label="Cerrar modal">&times;</button>
                </div>
                <div class="crud-dialog-body">
                    <p id="limit-modal-message" class="text-base-content/80 leading-relaxed mb-5"></p>
                    <div id="limit-modal-actions" class="flex gap-3 flex-wrap">
                        <button onclick="closeLimitModal()" class="btn btn-ghost flex-1">Cerrar</button>
                        <div id="limit-modal-cta"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: Diseño -->
        <div id="tab-diseno" class="tab-content">
@php
// activeTheme ya viene desde el controller

// Colores hardcodeados de cada tema FlyonUI (primary, secondary, accent, neutral, base)
$flyonuiThemes = [
    // DEFAULT
    ['slug'=>'light', 'name'=>'Light', 'category'=>'Default', 'colors'=>['#570df8','#f000b8','#37cdbe','#ffffff']],
    ['slug'=>'dark', 'name'=>'Dark', 'category'=>'Default', 'colors'=>['#661ae6','#d926a9','#1fb2a6','#2a303c']],
    // DARK MODES
    ['slug'=>'black', 'name'=>'Black', 'category'=>'Dark Modes', 'font'=>'Geist', 'colors'=>['#ffffff','#ffffff','#ffffff','#000000']],
    ['slug'=>'spotify', 'name'=>'Spotify', 'category'=>'Dark Modes', 'font'=>'Montserrat', 'colors'=>['#1db954','#1ed760','#1db954','#121212']],
    ['slug'=>'valorant', 'name'=>'Valorant', 'category'=>'Dark Modes', 'font'=>'Syne', 'colors'=>['#ff4655','#bd3944','#ff4655','#0f1923']],
    // PROFESSIONAL
    ['slug'=>'claude', 'name'=>'Claude', 'category'=>'Professional', 'font'=>'Lato', 'colors'=>['#da7756','#a0785a','#e8c9a0','#f5f0e8']],
    ['slug'=>'corporate', 'name'=>'Corporate', 'category'=>'Professional', 'font'=>'Inter', 'colors'=>['#4b6bfb','#7b92b2','#67cba0','#ffffff']],
    ['slug'=>'gourmet', 'name'=>'Gourmet', 'category'=>'Professional', 'font'=>'Montserrat', 'colors'=>['#9b2335','#d4a76a','#c8a97e','#fdfaf5']],
    ['slug'=>'luxury', 'name'=>'Luxury', 'category'=>'Professional', 'font'=>'Rubik', 'colors'=>['#ffffff','#a08740','#c5a028','#09090b']],
    // CREATIVE
    ['slug'=>'ghibli', 'name'=>'Ghibli', 'category'=>'Creative', 'font'=>'Amaranth', 'colors'=>['#6b7c5c','#c49a6c','#e8a87c','#faf6f0']],
    ['slug'=>'pastel', 'name'=>'Pastel', 'category'=>'Creative', 'font'=>'Open Sans', 'colors'=>['#d1c1f7','#f7d6c1','#c1f7d6','#ffffff']],
    ['slug'=>'soft', 'name'=>'Soft', 'category'=>'Creative', 'font'=>'Rubik', 'colors'=>['#6b21a8','#db2777','#0891b2','#ffffff']],
    // TECH
    ['slug'=>'mintlify', 'name'=>'Mintlify', 'category'=>'Tech', 'font'=>'Work Sans', 'colors'=>['#0ea474','#7c3aed','#0ea5e9','#ffffff']],
    ['slug'=>'perplexity', 'name'=>'Perplexity', 'category'=>'Tech', 'font'=>'Inter', 'colors'=>['#20b8cd','#1a9ab0','#15808f','#16191d']],
    ['slug'=>'shadcn', 'name'=>'Shadcn', 'category'=>'Tech', 'font'=>'Geist', 'colors'=>['#18181b','#f4f4f5','#18181b','#ffffff']],
    ['slug'=>'slack', 'name'=>'Slack', 'category'=>'Tech', 'font'=>'Lato', 'colors'=>['#4a154b','#1264a3','#ecb22e','#3f0e40']],
    ['slug'=>'vscode', 'name'=>'VS Code', 'category'=>'Tech', 'font'=>'DM Mono', 'colors'=>['#007acc','#6a9955','#569cd6','#1e1e1e']],
    ['slug'=>'perplexity', 'name'=>'Perplexity', 'category'=>'Tech',         'colors'=>['#20b2aa','#5f9ea0','#48d1cc','#708090','#f0f8ff']],
    ['slug'=>'shadcn',     'name'=>'Shadcn',     'category'=>'Tech',         'colors'=>['#18181b','#52525b','#3b82f6','#27272a','#fafafa']],
    ['slug'=>'slack',      'name'=>'Slack',      'category'=>'Tech',         'font'=>'Lato',       'colors'=>['#611f69','#36c5f0','#2eb67d','#1d1c1d','#1a1d21']],
    ['slug'=>'vscode',     'name'=>'VS Code',    'category'=>'Tech',         'font'=>'Fira Code',  'colors'=>['#007acc','#6c9ef8','#4ec9b0','#3c3c3c','#1e1e1e']],
];

// Filter to only the themes available for this tenant's plan (from DB)
$allowedSlugs = $palettes->pluck('slug')->toArray();
$flyonuiThemes = array_values(array_filter($flyonuiThemes, fn($t) => in_array($t['slug'], $allowedSlugs)));
$themesByCategory = collect($flyonuiThemes)->groupBy('category');
@endphp

{{-- ── Temas FlyonUI ─────────────────────────────────── --}}
<div class="card bg-base-100 shadow-sm border border-base-content/10 mb-4">
    <div class="card-header">
        <h2 class="card-title flex items-center gap-2">
            <span class="icon-[tabler--palette] size-5 text-primary" aria-hidden="true"></span>
            Tema Visual
        </h2>
        <p class="text-xs text-base-content/50 mt-0.5">Elige el tema que mejor represente tu marca</p>
    </div>
    <div class="card-body pt-2">
        <div id="theme-success-message" class="alert alert-success mb-3" style="display:none;">
            <span class="icon-[tabler--check] size-4"></span>
            <span class="text-sm">Tema actualizado correctamente</span>
        </div>

        <div class="max-h-[350px] overflow-y-auto pr-1 space-y-3">
            @foreach($themesByCategory as $category => $themes)
            <div>
                <h3 class="text-[10px] font-bold text-base-content/40 mb-2 uppercase tracking-wider sticky top-0 bg-base-100 py-1 z-10">
                    {{ $category }}
                </h3>
                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-2">
                    @foreach($themes as $theme)
                    @php
                        $isActive = $currentTheme === $theme['slug'];
                        $bg      = $theme['colors'][3];
                        $isDark  = in_array($theme['slug'], ['dark','black','spotify','valorant','luxury','perplexity','slack','vscode']);
                    @endphp
                    <div class="theme-card cursor-pointer rounded-lg overflow-hidden border-2 transition-all hover:scale-105 {{ $isActive ? 'border-primary ring-2 ring-primary/25' : 'border-base-content/10 hover:border-base-content/25' }}"
                         data-slug="{{ $theme['slug'] }}"
                         onclick="updateTheme('{{ $theme['slug'] }}')"
                         style="background:{{ $bg }}">
                        <div class="flex h-8">
                            @foreach(array_slice($theme['colors'], 0, 4) as $color)
                            <div class="flex-1" style="background:{{ $color }}"></div>
                            @endforeach
                        </div>
                        <div class="px-2 py-1.5 flex items-center justify-between gap-1">
                            <span class="text-[11px] font-semibold truncate" style="color:{{ $isDark ? 'rgba(255,255,255,.9)' : 'rgba(0,0,0,.85)' }}">{{ $theme['name'] }}</span>
                            @if($isActive)<span class="icon-[tabler--check] size-3.5 text-primary shrink-0"></span>@endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@if($tenant->plan_id === 3)
{{-- ── Paleta Personalizada (Plan VISIÓN) ──────────── --}}
<div class="card bg-base-100 shadow-sm border border-base-content/10 mb-4">
    <div class="card-header">
        <h3 class="card-title flex items-center gap-2">
            <span class="icon-[tabler--color-swatch] size-5 text-primary" aria-hidden="true"></span>
            Paleta Personalizada
            <span class="badge badge-soft badge-info badge-xs">Plan VISIÓN</span>
        </h3>
    </div>
    <div class="card-body">
        @php
        $customPalette = $tenant->settings['engine_settings']['visual']['custom_palette'] ?? [
            'primary' => '#570DF8', 'secondary' => '#F000B9', 'accent' => '#1DCDBC', 'base' => '#FFFFFF'
        ];
        @endphp
        <div class="grid grid-cols-4 gap-3">
            @foreach(['primary','secondary','accent','base'] as $colorKey)
            <div class="form-control">
                <label class="label pb-1"><span class="label-text text-xs font-medium capitalize">{{ $colorKey }}</span></label>
                <input type="color" id="custom-{{ $colorKey }}" class="w-full h-10 rounded-lg border border-base-content/10 cursor-pointer" value="{{ $customPalette[$colorKey] }}">
            </div>
            @endforeach
        </div>
        <button onclick="applyCustomPalette()" class="btn btn-primary btn-sm w-full gap-2 mt-3">
            <span class="icon-[tabler--palette] size-4"></span>
            Aplicar Paleta Custom
        </button>
    </div>
</div>
@endif

            {{-- ══════════════════════════════════════════════════════════════
                 SECCIÓN: Orden de Secciones (Drag & Drop)
            ══════════════════════════════════════════════════════════════ --}}
            <div class="card bg-base-100 shadow-sm border border-base-content/10 mb-4">
                <div class="card-header">
                    <h2 class="card-title flex items-center gap-2">
                        <span class="icon-[tabler--list-check] size-5 text-primary" aria-hidden="true"></span>
                        Orden de Secciones
                    </h2>
                    <p class="text-xs text-base-content/50 mt-0.5">Arrastra para reordenar. Las secciones apagadas no aparecen en tu landing.</p>
                </div>
                <div class="card-body pt-2">

                <div id="sortable-sections" class="grid grid-cols-1 lg:grid-cols-2 gap-2">
                    @php
                        // Get all 9 available sections from the Tenant model (based on plan access)
                        $allSections = [
                            'products'        => ['label' => 'Productos',        'icon' => 'tabler:shopping-cart',      'plan' => 1],
                            'services'        => ['label' => 'Servicios',        'icon' => 'tabler:tool',               'plan' => 1],
                            'contact'         => ['label' => 'Contacto',         'icon' => 'tabler:map-pin',            'plan' => 1],
                            'payment_methods' => ['label' => 'Medios de Pago',   'icon' => 'tabler:credit-card',        'plan' => 1],
                            'cta'             => ['label' => 'Llamado a Acción', 'icon' => 'tabler:send',               'plan' => 1],
                            'about'           => ['label' => 'Acerca de',        'icon' => 'tabler:info-circle',        'plan' => 2],
                            'testimonials'    => ['label' => 'Testimonios',      'icon' => 'tabler:message-star',       'plan' => 2],
                            'faq'             => ['label' => 'FAQ',              'icon' => 'tabler:help-circle',        'plan' => 3],
                            'branches'        => ['label' => 'Sucursales',       'icon' => 'tabler:building-bank',      'plan' => 3],
                        ];

                        $currentOrder = $customization->visual_effects['sections_order'] ?? [];

                        // Ordenar $allSections según $currentOrder
                        $availableSections = [];
                        if (!empty($currentOrder)) {
                            $orderedKeys = collect($currentOrder)->pluck('name')->toArray();
                            foreach ($orderedKeys as $k) {
                                if (isset($allSections[$k])) {
                                    $availableSections[$k] = $allSections[$k];
                                }
                            }
                            // Agregar las que no están en el orden guardado al final
                            foreach ($allSections as $k => $v) {
                                if (!isset($availableSections[$k])) {
                                    $availableSections[$k] = $v;
                                }
                            }
                        } else {
                            $availableSections = $allSections;
                        }
                    @endphp

                    @foreach($availableSections as $key => $section)
                        @php
                            $sectionData  = collect($currentOrder)->firstWhere('name', $key);
                            $isVisible    = $sectionData['visible'] ?? true;
                            $planRequired = $section['plan'];
                            $hasAccess    = $tenant->plan_id >= $planRequired;
                        @endphp

                        <div class="section-item {{ $hasAccess ? '' : 'no-drag opacity-40 pointer-events-none' }}"
                             data-section="{{ $key }}"
                             data-plan="{{ $planRequired }}">
                            <div class="flex items-center gap-2 px-2.5 py-2 rounded-lg
                                        bg-base-200 border border-base-content/10
                                        {{ $hasAccess ? 'cursor-move' : 'cursor-not-allowed' }}
                                        transition-colors hover:border-base-content/20">

                                {{-- Handle / Lock --}}
                                @if($hasAccess)
                                    <span class="drag-handle text-base-content/40 hover:text-base-content/70 cursor-grab select-none flex-shrink-0 active:cursor-grabbing">
                                        <span class="iconify tabler--grip-vertical text-base"></span>
                                    </span>
                                @else
                                    <span class="text-warning flex-shrink-0">
                                        <span class="icon-[tabler--lock] size-4"></span>
                                    </span>
                                @endif

                                {{-- Icon --}}
                                <span class="text-primary flex-shrink-0">
                                    <iconify-icon icon="{{ $section['icon'] }}" width="18"></iconify-icon>
                                </span>

                                {{-- Label --}}
                                <span class="flex-1 text-sm font-medium text-base-content">
                                    {{ $section['label'] }}
                                    @if(!$hasAccess)
                                        <span class="badge badge-warning badge-soft badge-xs ms-1">
                                            Plan {{ $planRequired == 2 ? 'CRECIMIENTO' : 'VISIÓN' }}
                                        </span>
                                    @endif
                                </span>

                                {{-- Toggle de visibilidad --}}
                                @if($hasAccess)
                                    <input type="checkbox"
                                           class="toggle toggle-primary toggle-sm section-toggle"
                                           id="section-{{ $key }}"
                                           @checked($isVisible)
                                           onchange="toggleSection('{{ $key }}', this.checked)">

                                    {{-- Flechas orden (alternativa al D&D) --}}
                                    <div class="flex flex-col gap-0 flex-shrink-0">
                                        <button type="button"
                                                onclick="moveSection('{{ $key }}', -1)"
                                                class="flex items-center justify-center w-6 h-5 rounded-t bg-base-300 hover:bg-primary hover:text-white text-base-content border border-base-content/20 transition-all"
                                                title="Subir">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
                                        </button>
                                        <button type="button"
                                                onclick="moveSection('{{ $key }}', 1)"
                                                class="flex items-center justify-center w-6 h-5 rounded-b bg-base-300 hover:bg-primary hover:text-white text-base-content border border-base-content/20 border-t-0 transition-all"
                                                title="Bajar">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                </div>
            </div>

            {{-- SortableJS se inicializa al abrir la pestaña Diseño (ver script global al pie del body) --}}

            {{-- ═══════════════════════════════════════════════════════
                 EDITOR TESTIMONIOS (Plan 2+)
            ═══════════════════════════════════════════════════════ --}}
            @if($plan->id >= 2)
            {{-- $savedTestimonials is provided by DashboardController --}}
            <div class="card bg-base-100 shadow-sm border border-base-content/10 mt-4">
                <div class="card-header flex items-center justify-between gap-3 flex-wrap">
                    <div>
                        <h3 class="card-title flex items-center gap-2">
                            <span class="icon-[tabler--message-star] size-5 text-primary"></span>
                            Testimonios de Clientes
                            <span class="badge badge-soft badge-primary badge-xs ms-1">Plan CRECIMIENTO+</span>
                        </h3>
                        <p class="text-base-content/50 text-xs mt-0.5">Agrega, edita y elimina los testimonios que desees.</p>
                    </div>
                </div>
                <div class="card-body pt-2 section-scroll">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($savedTestimonials as $ti => $testim)
                    @php $hasContent = !empty($testim['name']) || !empty($testim['text']); @endphp
                    <div class="rounded-lg border p-3 transition-all"
                         data-testimonial-index="{{ $ti }}"
                        {{ $hasContent ? 'class="border-primary/20 bg-primary/5"' : 'class="border-base-content/10 bg-base-200/30"' }}>
                        <div class="flex items-start justify-between mb-2">
                            <span class="text-[10px] font-bold text-base-content/40 uppercase tracking-wider">#{{ $ti + 1 }}</span>
                            <span class="text-sm text-yellow-500">{{ str_repeat('★', $testim['rating'] ?? 5) }}</span>
                        </div>
                        <h4 class="text-sm font-semibold text-base-content line-clamp-1">{{ $testim['name'] ?? '(vacío)' }}</h4>
                        <p class="text-xs text-base-content/50 line-clamp-1">{{ $testim['title'] ?? '(sin cargo)' }}</p>
                        <p class="text-xs text-base-content/60 line-clamp-2 mt-1">{{ $testim['text'] ?? '(vacío)' }}</p>
                        
                        <div class="flex gap-2 mt-3">
                            <button type="button" class="btn btn-primary btn-sm btn-square"
                                    onclick="editTestimonial({{ $ti }}, '{{ addslashes($testim['name'] ?? '') }}', '{{ addslashes($testim['title'] ?? '') }}', '{{ addslashes($testim['text'] ?? '') }}', {{ $testim['rating'] ?? 5 }})"
                                    title="Editar">
                                <span class="icon-[tabler--pencil] size-4" aria-hidden="true"></span>
                            </button>
                            <button type="button" class="btn btn-error btn-sm btn-square"
                                    onclick="deleteTestimonial({{ $ti }})"
                                    title="Eliminar">
                                <span class="icon-[tabler--trash] size-4" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>
                    @endforeach
                    </div>
                    <div class="flex gap-2 mt-3">
                        <button type="button" onclick="addTestimonial()" class="btn btn-primary flex-1 gap-2">
                            <span class="icon-[tabler--plus] size-4"></span>
                            Agregar Testimonio
                        </button>
                        <button type="button" onclick="saveTestimonials()" class="btn btn-primary flex-1 gap-2">
                            <span class="icon-[tabler--device-floppy] size-4"></span>
                            Guardar Testimonios
                        </button>
                    </div>
                </div>
            </div>
            @endif

            {{-- ═══════════════════════════════════════════════════════
                 EDITOR FAQ (Plan 3)
            ═══════════════════════════════════════════════════════ --}}
            @if($plan->id >= 3)
            @php
                $savedFaq = data_get($tenant->settings, 'business_info.faq', []);
            @endphp
            <div class="card bg-base-100 shadow-sm border border-base-content/10 mt-4">
                <div class="card-header flex items-center justify-between gap-3 flex-wrap">
                    <div>
                        <h3 class="card-title flex items-center gap-2">
                            <span class="icon-[tabler--help-circle] size-5 text-primary"></span>
                            Preguntas Frecuentes (FAQ)
                            <span class="badge badge-soft badge-secondary badge-xs ms-1">Plan VISIÓN</span>
                        </h3>
                        <p class="text-base-content/50 text-xs mt-0.5">Agrega, edita y elimina las preguntas frecuentes que desees.</p>
                    </div>
                </div>
                <div class="card-body pt-2 section-scroll">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($savedFaq as $fi => $fitem)
                        @php $hasFaqContent = !empty($fitem['question']) || !empty($fitem['answer']); @endphp
                        <div class="rounded-lg border p-3 transition-all"
                             data-faq-index="{{ $fi }}"
                            {{ $hasFaqContent ? 'style="border-color: var(--fallback-sc, oklch(var(--sc)/var(--tw-border-opacity)))" (class="border-secondary/20 bg-secondary/5")' : 'class="border-base-content/10 bg-base-200/30"' }}>
                            <span class="text-[10px] font-bold text-base-content/40 uppercase tracking-wider">#{{ $fi + 1 }}</span>
                            <h4 class="text-sm font-semibold text-base-content mt-1 line-clamp-2">{{ $fitem['question'] ?? '(vacío)' }}</h4>
                            <p class="text-xs text-base-content/50 mt-1 line-clamp-2">{{ $fitem['answer'] ?? '(vacío)' }}</p>
                            
                            <div class="flex gap-2 mt-3">
                                <button type="button" class="btn btn-secondary btn-sm btn-square"
                                        onclick="editFaq({{ $fi }}, '{{ addslashes($fitem['question'] ?? '') }}', '{{ addslashes($fitem['answer'] ?? '') }}')"
                                        title="Editar">
                                    <span class="icon-[tabler--pencil] size-4" aria-hidden="true"></span>
                                </button>
                                <button type="button" class="btn btn-error btn-sm btn-square"
                                        onclick="deleteFaq({{ $fi }})"
                                        title="Eliminar">
                                    <span class="icon-[tabler--trash] size-4" aria-hidden="true"></span>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="flex gap-2 mt-3">
                        <button type="button" onclick="addFaq()" class="btn btn-secondary flex-1 gap-2">
                            <span class="icon-[tabler--plus] size-4"></span>
                            Agregar Pregunta
                        </button>
                        <button type="button" onclick="saveFaq()" class="btn btn-secondary flex-1 gap-2">
                            <span class="icon-[tabler--device-floppy] size-4"></span>
                            Guardar FAQ
                        </button>
                    </div>
                </div>
            </div>
            @endif

            @if($plan->id === 1)
            <div class="alert alert-info mt-4 flex items-center justify-between gap-4 flex-wrap">
                <div class="flex items-center gap-3">
                    <span class="icon-[tabler--sparkles] size-5 shrink-0" aria-hidden="true"></span>
                    <div>
                        <p class="font-semibold text-sm">Desbloquea más personalización</p>
                        <p class="text-xs opacity-70">Header Top + Sección Acerca de disponibles desde el Plan CRECIMIENTO</p>
                    </div>
                </div>
                <a href="https://syntiweb.com/planes" target="_blank" rel="noopener noreferrer"
                   class="btn btn-primary btn-sm shrink-0">Ver Planes</a>
            </div>
            @endif

        </div>

        <!-- Tab: Analytics -->
        <div id="tab-analytics" class="tab-content">

            {{-- ── KPI Cards — compact single grid ──────────────────────── --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 mb-4">
                @php
                    $kpis = [
                        ['id' => 'visitors-today',   'label' => 'Visitantes hoy',    'icon' => 'tabler--eye',             'color' => 'primary', 'badge' => 'Hoy'],
                        ['id' => 'visitors-week',    'label' => 'Visitantes 7d',     'icon' => 'tabler--users',           'color' => 'info',    'badge' => '7d'],
                        ['id' => 'whatsapp-clicks',  'label' => 'WhatsApp',          'icon' => 'tabler--brand-whatsapp',  'color' => 'success', 'badge' => '7d'],
                        ['id' => 'qr-scans',         'label' => 'Escaneos QR',       'icon' => 'tabler--qrcode',          'color' => 'warning', 'badge' => '7d'],
                        ['id' => 'call-clicks',      'label' => 'Llamadas',          'icon' => 'tabler--phone',           'color' => 'error',   'badge' => '7d'],
                        ['id' => 'currency-toggles',  'label' => 'Cambios moneda',   'icon' => 'tabler--currency-dollar', 'color' => 'secondary','badge' => '7d'],
                        ['id' => 'avg-time',          'label' => 'Tiempo prom. (s)', 'icon' => 'tabler--clock',           'color' => 'accent',  'badge' => '7d'],
                    ];
                @endphp
                @foreach($kpis as $kpi)
                <div class="rounded-lg border border-base-content/10 bg-base-100 p-3">
                    <div class="flex items-center justify-between mb-2">
                        <div class="size-8 rounded-field bg-{{ $kpi['color'] }}/10 text-{{ $kpi['color'] }} flex items-center justify-center">
                            <span class="icon-[{{ $kpi['icon'] }}] size-4"></span>
                        </div>
                        <span class="badge badge-soft badge-xs badge-{{ $kpi['color'] }}">{{ $kpi['badge'] }}</span>
                    </div>
                    <div id="{{ $kpi['id'] }}" class="text-xl font-bold text-base-content leading-none">-</div>
                    <div class="text-[11px] text-base-content/50 mt-1">{{ $kpi['label'] }}</div>
                </div>
                @endforeach
            </div>

            {{-- ── Gráfico + Distribución side-by-side ──────────────────── --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
                <div class="lg:col-span-2 card bg-base-100 shadow-sm border border-base-content/10">
                    <div class="card-header flex items-center justify-between">
                        <h4 class="card-title text-base">Visitantes — últimos 7 días</h4>
                        <button onclick="loadAnalytics()" class="btn btn-sm btn-ghost gap-2">
                            <span class="icon-[tabler--refresh] size-4"></span>
                            Actualizar
                        </button>
                    </div>
                    <div class="card-body pt-2">
                        <canvas id="analytics-chart" class="max-h-[250px]"></canvas>
                    </div>
                </div>

                <div class="card bg-base-100 shadow-sm border border-base-content/10">
                    <div class="card-header">
                        <h4 class="card-title text-base">Distribución</h4>
                    </div>
                    <div class="card-body pt-0 flex flex-col items-center">
                        <div id="analytics-donut-chart"></div>
                        <div class="flex flex-col gap-2 w-full mt-2">
                            <div class="flex items-center justify-between text-xs">
                                <span class="flex items-center gap-1.5">
                                    <span class="inline-block size-2.5 rounded-full bg-primary"></span>
                                    Visitas
                                </span>
                                <span class="font-semibold text-base-content">—</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="flex items-center gap-1.5">
                                    <span class="inline-block size-2.5 rounded-full bg-success"></span>
                                    WhatsApp
                                </span>
                                <span class="font-semibold text-base-content">—</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="flex items-center gap-1.5">
                                    <span class="inline-block size-2.5 rounded-full bg-warning"></span>
                                    QR
                                </span>
                                <span class="font-semibold text-base-content">—</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Herramientas de negocio ───────────────────────────────── --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                {{-- Tasa del Dólar --}}
                <div class="card bg-base-100 shadow-sm border border-base-content/10">
                    <div class="card-header">
                        <div class="flex items-center gap-2">
                            <span class="icon-[tabler--currency-dollar] size-5 text-success"></span>
                            <h4 class="card-title text-base">Tasa del Dólar</h4>
                        </div>
                    </div>
                    <div class="card-body pt-2">
                        <div class="bg-base-200 rounded-box p-4 text-center mb-4">
                            <div class="text-3xl font-bold text-primary leading-none">
                                Bs. <span id="dollar-rate-value">{{ $dollarRate }}</span>
                            </div>
                            <div class="text-xs text-base-content/50 mt-1">por 1 USD — Tasa BCV</div>
                        </div>
                        <button onclick="updateDollarRate()" class="btn btn-primary btn-block btn-sm gap-2">
                            <span class="icon-[tabler--refresh] size-4"></span>
                            Actualizar tasa
                        </button>
                    </div>
                </div>

                {{-- Estado del negocio --}}
                <div class="card bg-base-100 shadow-sm border border-base-content/10">
                    <div class="card-header">
                        <div class="flex items-center gap-2">
                            <span class="icon-[tabler--building-store] size-5 text-primary"></span>
                            <h4 class="card-title text-base">Estado del Negocio</h4>
                        </div>
                    </div>
                    <div class="card-body pt-2">
                        <div class="bg-base-200 rounded-box p-4 flex flex-col items-center gap-3">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" class="switch switch-success"
                                       id="status-toggle-large"
                                       {{ $tenant->is_open ? 'checked' : '' }}
                                       onchange="toggleBusinessStatusLarge()">
                                <label for="status-toggle-large" class="label-text text-base font-medium cursor-pointer">
                                    <span id="business-status-label">
                                        {{ $tenant->is_open ? 'Abierto' : 'Cerrado' }}
                                    </span>
                                </label>
                            </div>
                            <div id="business-status-badge">
                                @if($tenant->is_open)
                                    <span class="badge badge-success badge-soft">🟢 Tu sitio está recibiendo clientes</span>
                                @else
                                    <span class="badge badge-error badge-soft">🔴 Tu sitio está pausado</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Tab: Sucursales (Plan 3 / VISIÓN only) --}}
        @if($plan->id === 3)
        <div id="tab-sucursales" class="tab-content">
            @php
                $branchesEnabled = data_get($tenant->settings, 'engine_settings.branches.enabled', false);
                $maxBranches = 3;
                $currentBranchCount = $branches->count();
            @endphp

            {{-- ── Sucursales header card ──────────────────────────────── --}}
            <div class="card bg-base-100 shadow-sm border border-base-content/10 mb-4">
                <div class="card-header flex items-center justify-between gap-3">
                    <div>
                        <h2 class="card-title flex items-center gap-2">
                            <span class="icon-[tabler--map-pin] size-5 text-primary" aria-hidden="true"></span>
                            Sucursales
                        </h2>
                        <p class="text-xs text-base-content/50 mt-0.5">Muestra hasta 3 sucursales en tu landing pública</p>
                    </div>
                    <input type="checkbox" id="branches-toggle"
                           class="switch switch-success"
                           {{ $branchesEnabled ? 'checked' : '' }}
                           onchange="toggleBranchesSection()">
                </div>
                <div class="card-body pt-0">
                    <div id="branches-status" class="alert {{ $branchesEnabled ? 'alert-success' : 'alert-info' }}">
                        <span class="icon-[tabler--{{ $branchesEnabled ? 'check' : 'pause' }}] size-4" aria-hidden="true"></span>
                        <p id="branches-status-text" class="text-sm">
                            {{ $branchesEnabled ? 'Sección visible en tu landing pública' : 'Sección oculta en tu landing pública' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Branch list + Forms (shown only when enabled) --}}
            <div id="branches-content" {{ $branchesEnabled ? '' : 'style="display:none"' }}>

                {{-- Existing branches — intelligent grid: 1=12col, 2=6+6, 3=4+4+4 --}}
                @php
                    $branchGridClass = match($currentBranchCount) {
                        0 => 'grid-cols-1',
                        1 => 'grid-cols-1',
                        2 => 'grid-cols-1 sm:grid-cols-2',
                        default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
                    };
                @endphp
                <div id="branches-list" class="grid {{ $branchGridClass }} gap-3 mb-4">
                    @foreach($branches as $branch)
                    <div class="rounded-lg border border-base-content/10 bg-base-200/30 p-4 transition-all hover:border-primary/30 branch-card" id="branch-card-{{ $branch->id }}">
                        <div class="flex items-start gap-3 mb-3">
                            <div class="size-10 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                                <span class="icon-[tabler--map-pin] size-5 text-primary" aria-hidden="true"></span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="branch-name text-sm font-semibold text-base-content truncate">{{ $branch->name }}</h3>
                                <p class="branch-address text-xs text-base-content/50 line-clamp-2 mt-0.5">{{ $branch->address }}</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button type="button"
                                    class="btn btn-primary btn-sm btn-square"
                                    onclick="editBranch({{ $branch->id }}, '{{ addslashes($branch->name) }}', '{{ addslashes($branch->address) }}')"
                                    title="Editar">
                                <span class="icon-[tabler--pencil] size-5" aria-hidden="true"></span>
                            </button>
                            <button type="button"
                                    class="btn btn-error btn-sm btn-square"
                                    onclick="deleteBranch({{ $branch->id }})"
                                    title="Eliminar">
                                <span class="icon-[tabler--trash] size-5" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>
                    @if($currentBranchCount < $maxBranches)
                    <div class="rounded-lg border border-base-content/10 bg-base-200/30 p-4 flex items-center justify-center transition-all hover:border-primary/30 cursor-pointer" onclick="openBranchModal()">
                        <div class="text-center">
                            <div class="size-10 rounded-lg bg-primary/10 flex items-center justify-center mx-auto mb-2">
                                <span class="icon-[tabler--plus] size-5 text-primary" aria-hidden="true"></span>
                            </div>
                            <p class="text-sm font-semibold text-base-content">Agregar Sucursal</p>
                            <p class="text-xs text-base-content/50">{{ $currentBranchCount }} de {{ $maxBranches }} usadas</p>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Tab: Config -->
        <div id="tab-config" class="tab-content">

            {{-- ═══════════════════════════════════════════════════════════
                 Section: Horario de Atención (Business Hours)
            ═══════════════════════════════════════════════════════════ --}}
            @php
                $businessHours = $tenant->business_hours ?? [];
                $daysMap = [
                    'monday'    => 'Lunes',
                    'tuesday'   => 'Martes',
                    'wednesday' => 'Miércoles',
                    'thursday'  => 'Jueves',
                    'friday'    => 'Viernes',
                    'saturday'  => 'Sábado',
                    'sunday'    => 'Domingo',
                ];
            @endphp
            <div class="card bg-base-100 shadow-sm border border-base-content/10 mb-4">
                <div class="card-header">
                    <h3 class="card-title flex items-center gap-2">
                        <span class="icon-[tabler--clock] size-5 text-primary" aria-hidden="true"></span>
                        Horario de Atención
                    </h3>
                    <p class="text-xs text-base-content/50 mt-0.5">Define el horario de tu negocio para cada día de la semana</p>
                </div>
                <div class="card-body pt-2">
                    <div class="section-scroll space-y-2" id="business-hours-list">
                        @foreach($daysMap as $dayKey => $dayLabel)
                        @php
                            $dayData = $businessHours[$dayKey] ?? null;
                            $isClosed = is_null($dayData);
                            $openTime = $dayData['open'] ?? '08:00';
                            $closeTime = $dayData['close'] ?? '18:00';
                        @endphp
                        <div class="flex items-center gap-3 p-3 rounded-lg bg-base-200/40 border border-base-content/10">
                            <span class="text-sm font-semibold text-base-content w-24 shrink-0">{{ $dayLabel }}</span>
                            <div class="flex items-center gap-2 flex-1 flex-wrap">
                                <input type="time" id="bh-{{ $dayKey }}-open"
                                       class="input input-sm input-bordered w-28"
                                       value="{{ $openTime }}"
                                       {{ $isClosed ? 'disabled' : '' }}>
                                <span class="text-xs text-base-content/40">a</span>
                                <input type="time" id="bh-{{ $dayKey }}-close"
                                       class="input input-sm input-bordered w-28"
                                       value="{{ $closeTime }}"
                                       {{ $isClosed ? 'disabled' : '' }}>
                            </div>
                            <label class="label cursor-pointer gap-2 shrink-0">
                                <span class="label-text text-xs text-base-content/50">Cerrado</span>
                                <input type="checkbox" id="bh-{{ $dayKey }}-closed"
                                       class="toggle toggle-error toggle-sm"
                                       {{ $isClosed ? 'checked' : '' }}
                                       onchange="toggleDayClosed('{{ $dayKey }}', this.checked)">
                            </label>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" onclick="saveBusinessHours()" class="btn btn-primary w-full gap-2 mt-3">
                        <span class="icon-[tabler--device-floppy] size-4" aria-hidden="true"></span>
                        Guardar Horario
                    </button>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════════════════
                 Section 0: Social Networks
            ═══════════════════════════════════════════════════════════ --}}
            <div class="card bg-base-100 shadow-sm border border-base-content/10 mb-4">
                @php
                    $rawSocial      = $customization->social_networks ?? [];
                    $socialNetworks = is_array($rawSocial) ? $rawSocial : [];
                    $allNetworksMeta = [
                        'instagram' => ['label' => 'Instagram',  'placeholder' => '@tuusuario',    'icon' => 'tabler--brand-instagram'],
                        'facebook'  => ['label' => 'Facebook',   'placeholder' => '@pagina o URL', 'icon' => 'tabler--brand-facebook'],
                        'tiktok'    => ['label' => 'TikTok',     'placeholder' => '@tuusuario',    'icon' => 'tabler--brand-tiktok'],
                        'linkedin'  => ['label' => 'LinkedIn',   'placeholder' => 'URL o usuario', 'icon' => 'tabler--brand-linkedin'],
                        'youtube'   => ['label' => 'YouTube',    'placeholder' => '@canal o URL',  'icon' => 'tabler--brand-youtube'],
                        'x'         => ['label' => 'Twitter / X','placeholder' => '@tuusuario',    'icon' => 'tabler--brand-x'],
                    ];
                    $plan1Networks  = ['instagram', 'facebook', 'tiktok', 'linkedin'];
                    $availableKeys  = $plan->id === 1 ? $plan1Networks : array_keys($allNetworksMeta);
                    $plan1Selected  = array_key_first(array_intersect_key($socialNetworks, array_flip($plan1Networks))) ?? '';
                    $plan1Handle    = $plan1Selected ? ($socialNetworks[$plan1Selected] ?? '') : '';
                @endphp

                <div class="card-header flex items-center justify-between gap-2 flex-wrap">
                    <h3 class="card-title flex items-center gap-2">
                        <span class="icon-[tabler--social] size-5 text-primary" aria-hidden="true"></span>
                        Redes Sociales
                    </h3>
                    @if($plan->id === 1)
                        <span class="badge badge-soft badge-warning badge-sm">Plan OPORTUNIDAD — 1 red social</span>
                    @else
                        <span class="badge badge-soft badge-success badge-sm">Plan {{ $plan->name }} — Todas las redes</span>
                    @endif
                </div>
                <div class="card-body">
                    @if($plan->id === 1)
                    {{-- ── Plan 1: radio select + single handle ── --}}
                    <div class="mb-4">
                        <label class="label"><span class="label-text font-medium">Elige tu red social</span></label>
                        <div class="flex flex-wrap gap-2 mb-4" id="social-radio-group">
                            @foreach($plan1Networks as $key)
                            @php $meta = $allNetworksMeta[$key]; @endphp
                            <label id="social-radio-label-{{ $key }}"
                                   onclick="selectSocialNetwork('{{ $key }}')"
                                   class="btn btn-sm gap-1.5 {{ $plan1Selected === $key ? 'btn-primary' : 'btn-ghost border border-base-content/20' }} cursor-pointer">
                                <input type="radio" name="social_plan1_choice" value="{{ $key }}"
                                       {{ $plan1Selected === $key ? 'checked' : '' }} class="hidden">
                                <span class="icon-[{{ $meta['icon'] }}] size-4" aria-hidden="true"></span>
                                {{ $meta['label'] }}
                            </label>
                            @endforeach
                        </div>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-medium">
                                    Tu usuario o enlace
                                    <span id="social-plan1-network-label" class="text-primary ml-1">
                                        {{ $plan1Selected ? '(' . $allNetworksMeta[$plan1Selected]['label'] . ')' : '' }}
                                    </span>
                                </span>
                            </label>
                            <input type="text" id="social-plan1-handle"
                                   value="{{ $plan1Handle }}"
                                   placeholder="{{ $plan1Selected ? $allNetworksMeta[$plan1Selected]['placeholder'] : 'Selecciona una red primero' }}"
                                   class="input input-bordered w-full"
                                   {{ !$plan1Selected ? 'disabled' : '' }}>
                        </div>
                    </div>

                    @else
                    {{-- ── Plan 2 + 3: grid cubo Rubik ── --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-4" id="social-all-fields">
                        @foreach($availableKeys as $key)
                        @php $meta = $allNetworksMeta[$key]; $current = $socialNetworks[$key] ?? ''; @endphp
                        <div class="flex flex-col items-center gap-2 p-3 rounded-lg border border-base-content/10 bg-base-200/40 transition-all hover:border-primary/30 hover:bg-primary/5">
                            <span class="icon-[{{ $meta['icon'] }}] size-7 text-primary" aria-hidden="true"></span>
                            <span class="text-[11px] font-semibold text-base-content/70">{{ $meta['label'] }}</span>
                            <input type="text" id="social-{{ $key }}" name="social_{{ $key }}"
                                   value="{{ $current }}"
                                   placeholder="{{ $meta['placeholder'] }}"
                                   maxlength="255"
                                   class="input input-bordered input-sm w-full text-center text-xs">
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <button type="button" onclick="saveSocialNetworks()" class="btn btn-primary w-full gap-2">
                        <span class="icon-[tabler--device-floppy] size-4" aria-hidden="true"></span>
                        Guardar Redes Sociales
                    </button>
                </div>
            </div>

            {{-- ── Medios de Pago card ─────────────────────────────────── --}}
            <div class="card bg-base-100 shadow-sm border border-base-content/10 mb-4">
                @php
                    $payMethods      = $customization->payment_methods ?? [];
                    $globalEnabled   = $payMethods['global'] ?? [];
                    $currencyEnabled = $payMethods['currency'] ?? [];
                    $branchPayMeta   = $payMethods['branches'] ?? [];
                    $allPayMeta      = [
                        'pagoMovil'  => ['label' => 'Pago Móvil',    'icon' => '📱', 'desc' => 'Transferencia bancaria móvil',  'group' => 'Nacional'],
                        'cash'       => ['label' => 'Efectivo',       'icon' => '💵', 'desc' => 'Pago en efectivo',              'group' => 'Nacional'],
                        'puntoventa' => ['label' => 'Punto de Venta', 'icon' => '💳', 'desc' => 'Terminal POS físico',           'group' => 'Nacional'],
                        'biopago'    => ['label' => 'Biopago',        'icon' => '👁️', 'desc' => 'Pago biométrico',              'group' => 'Nacional'],
                        'cashea'     => ['label' => 'Cashea',         'icon' => '🛒', 'desc' => 'Compra ahora, paga después',    'group' => 'Nacional'],
                        'krece'      => ['label' => 'Krece',          'icon' => '📈', 'desc' => 'Financiamiento tech/electro',   'group' => 'Nacional'],
                        'wepa'       => ['label' => 'Wepa',           'icon' => '📲', 'desc' => 'Cuotas desde el móvil',         'group' => 'Nacional'],
                        'lysto'      => ['label' => 'Lysto',          'icon' => '🗓️', 'desc' => 'Pago en cuotas en comercios',  'group' => 'Nacional'],
                        'chollo'     => ['label' => 'Chollo',         'icon' => '🏷️', 'desc' => 'Compras a cuotas en retail',   'group' => 'Nacional'],
                        'zelle'      => ['label' => 'Zelle',          'icon' => '⚡',  'desc' => 'Transferencia USD',             'group' => 'Divisa'],
                        'zinli'      => ['label' => 'Zinli',          'icon' => '🟣', 'desc' => 'Billetera digital USD',          'group' => 'Divisa'],
                        'paypal'     => ['label' => 'PayPal',         'icon' => '🅿️', 'desc' => 'Pagos internacionales',         'group' => 'Divisa'],
                    ];
                    $allCurrencyMeta = [
                        'usd' => ['label' => 'Dólares (USD)', 'icon' => '💵', 'desc' => 'Acepta billetes USD'],
                        'eur' => ['label' => 'Euros (€)',     'icon' => '💶', 'desc' => 'Acepta billetes EUR'],
                    ];
                    $activeBranchList = $branches->where('is_active', true);
                @endphp

                <div class="card-header flex items-center justify-between gap-2 flex-wrap">
                    <h3 class="card-title flex items-center gap-2">
                        <span class="icon-[tabler--credit-card] size-5 text-primary" aria-hidden="true"></span>
                        Medios de Pago
                    </h3>
                    @if($plan->id === 1)
                        <span class="badge badge-soft badge-warning badge-sm">Plan OPORTUNIDAD — fijos</span>
                    @elseif($plan->id === 2)
                        <span class="badge badge-soft badge-success badge-sm">Plan CRECIMIENTO</span>
                    @else
                        <span class="badge badge-soft badge-info badge-sm">Plan VISIÓN — global + sucursales</span>
                    @endif
                </div>
                <div class="card-body">
                    @if($plan->id === 1)
                    {{-- Plan 1: Fixed — read-only --}}
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        @foreach(['pagoMovil', 'cash'] as $mkey)
                        @php $m = $allPayMeta[$mkey]; @endphp
                        <div class="flex items-center gap-2 p-3 rounded-box bg-success/10 border border-success/20">
                            <span class="text-xl">{{ $m['icon'] }}</span>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-semibold text-success">{{ $m['label'] }}</div>
                                <div class="text-xs text-success/60">{{ $m['desc'] }}</div>
                            </div>
                            <span class="icon-[tabler--check] size-4 text-success shrink-0" aria-hidden="true"></span>
                        </div>
                        @endforeach
                    </div>
                    <div class="alert alert-info">
                        <span class="icon-[tabler--lock] size-4" aria-hidden="true"></span>
                        <span class="text-sm font-medium">Para elegir más métodos de pago, mejora al Plan CRECIMIENTO o superior.</span>
                    </div>

                    @else
                    {{-- Plan 2 + 3: Selectable checkboxes (global) --}}
                    <p class="text-sm font-medium text-base-content mb-3">
                        @if($plan->id === 3) Métodos globales (todos los clientes) @else Métodos visibles en tu landing @endif
                    </p>

                    <p class="text-xs font-semibold uppercase tracking-wide text-base-content/40 mb-2">Nacionales / Divisas</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-1.5 mb-4">
                        @foreach($allPayMeta as $mkey => $m)
                        @php $checked = in_array($mkey, $globalEnabled); @endphp
                        <label id="pay-label-{{ $mkey }}" onclick="togglePayMethod('{{ $mkey }}')"
                               class="flex items-center gap-1.5 cursor-pointer px-2.5 py-2 rounded-lg border transition-all select-none
                                      {{ $checked ? 'bg-primary/15 border-primary/40 text-primary font-semibold' : 'bg-base-200/40 border-base-content/10 text-base-content hover:border-base-content/20' }}">
                            <input type="checkbox" id="pay-check-{{ $mkey }}" value="{{ $mkey }}" {{ $checked ? 'checked' : '' }} class="hidden">
                            <span class="text-sm">{{ $m['icon'] }}</span>
                            <span class="text-xs font-medium flex-1 truncate {{ $checked ? 'text-primary' : 'text-base-content' }}">{{ $m['label'] }}</span>
                            <span id="pay-check-icon-{{ $mkey }}"
                                  class="icon-[tabler--check] size-3.5 shrink-0 transition-opacity {{ $checked ? 'text-primary opacity-100' : 'opacity-0' }}"
                                  aria-hidden="true"></span>
                        </label>
                        @endforeach
                    </div>

                    {{-- Denominaciones --}}
                    <div class="pt-4 border-t border-base-content/10">
                        <p class="text-sm font-medium text-base-content mb-3">
                            <span class="icon-[tabler--cash] size-4 inline-block mr-1 text-base-content/60" aria-hidden="true"></span>
                            Denominaciones Aceptadas
                        </p>
                        <div class="grid grid-cols-2 gap-2 mb-4">
                            @foreach($allCurrencyMeta as $ckey => $c)
                            @php $checked = in_array($ckey, $currencyEnabled); @endphp
                            <label id="curr-label-{{ $ckey }}" onclick="toggleCurrency('{{ $ckey }}')"
                                   class="flex items-center gap-2 cursor-pointer p-2.5 rounded-box border transition-all select-none
                                          {{ $checked ? 'bg-primary/20 border-primary/50 text-primary font-semibold' : 'bg-base-200/50 border-base-content/10 text-base-content hover:border-base-content/20' }}">
                                <input type="checkbox" id="curr-check-{{ $ckey }}" value="{{ $ckey }}" {{ $checked ? 'checked' : '' }} class="hidden">
                                <span class="text-base">{{ $c['icon'] }}</span>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs font-medium {{ $checked ? 'text-primary' : 'text-base-content' }}">{{ $c['label'] }}</div>
                                    <div class="text-xs {{ $checked ? 'text-primary/70' : 'text-base-content/40' }}">{{ $c['desc'] }}</div>
                                </div>
                                <span id="curr-check-icon-{{ $ckey }}"
                                      class="icon-[tabler--check] size-4 shrink-0 transition-opacity {{ $checked ? 'text-primary opacity-100' : 'opacity-0' }}"
                                      aria-hidden="true"></span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Vista previa --}}
                    <div class="rounded-box bg-base-200 border border-base-content/10 p-3 mb-4">
                        <p class="text-xs font-semibold text-base-content/50 uppercase tracking-wide mb-2">Vista previa en la landing:</p>
                        <div id="payment-preview" class="flex flex-wrap justify-center gap-2 min-h-8 items-center"></div>
                    </div>

                    @if($plan->id === 3 && $activeBranchList->isNotEmpty())
                    {{-- Plan 3: per-branch --}}
                    <div class="pt-4 border-t border-base-content/10">
                        <p class="text-sm font-medium text-base-content mb-1">Métodos por Sucursal</p>
                        <p class="text-xs text-base-content/50 mb-3">Personaliza los métodos aceptados en cada sucursal</p>
                        <div class="space-y-3">
                            @foreach($activeBranchList as $branch)
                            @php $bEnabled = $branchPayMeta[(string)$branch->id] ?? []; @endphp
                            <div class="rounded-box bg-base-200/50 border border-base-content/10 p-3">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="icon-[tabler--map-pin] size-4 text-base-content/50" aria-hidden="true"></span>
                                    <span class="font-semibold text-sm text-base-content">{{ $branch->name }}</span>
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-1.5">
                                    @foreach($allPayMeta as $mkey => $m)
                                    @php $bchecked = in_array($mkey, $bEnabled); @endphp
                                    <label id="pay-branch-label-{{ $branch->id }}-{{ $mkey }}"
                                           onclick="toggleBranchPayMethod({{ $branch->id }}, '{{ $mkey }}')"
                                           class="flex items-center gap-1.5 cursor-pointer px-2 py-1.5 rounded-lg border transition-all select-none
                                                  {{ $bchecked ? 'bg-primary/15 border-primary/40 text-primary font-semibold' : 'bg-base-100 border-base-content/10 text-base-content hover:border-base-content/20' }}">
                                        <input type="checkbox" id="pay-branch-check-{{ $branch->id }}-{{ $mkey }}" value="{{ $mkey }}" {{ $bchecked ? 'checked' : '' }} class="hidden">
                                        <span class="text-sm">{{ $m['icon'] }}</span>
                                        <span class="text-xs {{ $bchecked ? 'text-primary' : 'text-base-content' }} flex-1 truncate">{{ $m['label'] }}</span>
                                        <span id="pay-branch-check-icon-{{ $branch->id }}-{{ $mkey }}"
                                              class="icon-[tabler--check] size-3.5 shrink-0 transition-opacity {{ $bchecked ? 'text-primary opacity-100' : 'opacity-0' }}"
                                              aria-hidden="true"></span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <button type="button" onclick="savePaymentMethods()" class="btn btn-primary w-full gap-2 mt-4">
                        <span class="icon-[tabler--device-floppy] size-4" aria-hidden="true"></span>
                        Guardar Medios de Pago y Denominaciones
                    </button>
                    @endif
                </div>
            </div>

            {{-- ── Configuración de Moneda card ──────────────────────────── --}}
            <div class="card bg-base-100 shadow-sm border border-base-content/10 mb-4">
                <div class="card-header">
                    <h3 class="card-title flex items-center gap-2">
                        <span class="icon-[tabler--currency-dollar] size-5 text-primary" aria-hidden="true"></span>
                        Configuración de Moneda
                    </h3>
                </div>
                <div class="card-body">
                    {{-- Símbolo toggle --}}
                    <div class="flex items-center justify-between mb-6 p-3 rounded-box bg-base-200/50 border border-base-content/10">
                        <span class="font-medium text-sm text-base-content">Símbolo de Precio</span>
                        <div class="flex items-center gap-3">
                            <span id="symbol-ref-label" class="text-sm font-semibold text-primary">REF</span>
                            <input type="checkbox" id="currency-symbol-switch" class="switch switch-primary switch-sm">
                            <span id="symbol-dollar-label" class="text-sm font-semibold text-base-content/40">$</span>
                        </div>
                    </div>

                    {{-- Display Mode --}}
                    <div class="form-control mb-4">
                        <label class="label"><span class="label-text font-medium">Mostrar Precios en</span></label>
                        @php $savedMode = $tenant->settings['engine_settings']['currency']['display']['saved_display_mode'] ?? 'reference_only'; @endphp
                        <div class="flex flex-col gap-2">
                            @foreach([
                                'reference_only' => 'Solo Referencia (REF/$)',
                                'bolivares_only' => 'Solo Bolívares (Bs.)',
                                'both_toggle'   => 'Ambos con toggle público',
                                'hidden'        => 'Ocultar precio → activa "Más Info"',
                            ] as $val => $label)
                            <label class="flex items-center gap-2.5 cursor-pointer">
                                <input type="radio" name="display_mode" value="{{ $val }}"
                                       {{ $savedMode === $val ? 'checked' : '' }}
                                       class="radio radio-primary radio-sm">
                                <span class="text-sm text-base-content">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="alert alert-info mb-4">
                        <span class="icon-[tabler--info-circle] size-4" aria-hidden="true"></span>
                        <span class="text-sm">Si ocultás el precio, el botón cambia a "Más Info"</span>
                    </div>

                    <button type="button" onclick="saveCurrencyConfig()" class="btn btn-primary w-full gap-2">
                        <span class="icon-[tabler--device-floppy] size-4" aria-hidden="true"></span>
                        Guardar Configuración de Moneda
                    </button>
                </div>
            </div>

            {{-- ── Cambiar PIN ──────────────────────────────────── --}}
            <div class="card bg-base-100 shadow-sm border border-base-content/10 mb-4">
                <div class="card-header">
                    <h3 class="card-title flex items-center gap-2">
                        <span class="icon-[tabler--lock] size-5 text-primary" aria-hidden="true"></span>
                        Cambiar PIN de Acceso
                    </h3>
                </div>
                <div class="card-body">
                    <form id="pin-form" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="form-control">
                            <label class="label pb-1"><span class="label-text font-medium text-sm">PIN Actual</span></label>
                            <input type="password" id="current-pin" maxlength="4" pattern="[0-9]{4}" required
                                   class="input input-bordered w-full" placeholder="••••">
                        </div>
                        <div class="form-control">
                            <label class="label pb-1"><span class="label-text font-medium text-sm">PIN Nuevo</span></label>
                            <input type="password" id="new-pin" maxlength="4" pattern="[0-9]{4}" required
                                   class="input input-bordered w-full" placeholder="••••">
                        </div>
                        <div class="form-control">
                            <label class="label pb-1"><span class="label-text font-medium text-sm">Confirmar PIN</span></label>
                            <input type="password" id="confirm-pin" maxlength="4" pattern="[0-9]{4}" required
                                   class="input input-bordered w-full" placeholder="••••">
                        </div>
                    </form>
                    <button type="button" onclick="updatePin()" class="btn btn-primary w-full sm:w-auto gap-2 mt-3">
                        <span class="icon-[tabler--device-floppy] size-4" aria-hidden="true"></span>
                        Guardar PIN
                    </button>
                </div>
            </div>

            {{-- ── Información del Plan ────────────────────────── --}}
            <div class="card bg-base-100 shadow-sm border border-base-content/10">
                <div class="card-header flex items-center justify-between gap-2 flex-wrap">
                    <h3 class="card-title flex items-center gap-2">
                        <span class="icon-[tabler--crown] size-5 text-primary" aria-hidden="true"></span>
                        Información del Plan
                    </h3>
                    <span class="badge badge-soft badge-sm {{ $plan->id === 1 ? 'badge-warning' : ($plan->id === 2 ? 'badge-success' : 'badge-info') }}">
                        {{ $plan->name }}
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="divide-y divide-base-content/10">
                        <div class="flex items-center justify-between px-4 py-3">
                            <span class="text-sm text-base-content/60">Plan actual</span>
                            <span class="text-sm font-semibold text-base-content">{{ $plan->name }}</span>
                        </div>
                        <div class="flex items-center justify-between px-4 py-3">
                            <span class="text-sm text-base-content/60">Productos</span>
                            <span class="text-sm font-semibold text-base-content">{{ $products->count() }} / {{ $plan->products_limit }}</span>
                        </div>
                        <div class="flex items-center justify-between px-4 py-3">
                            <span class="text-sm text-base-content/60">Servicios</span>
                            <span class="text-sm font-semibold text-base-content">{{ $services->count() }} / {{ $plan->services_limit }}</span>
                        </div>
                        <div class="flex items-center justify-between px-4 py-3">
                            <span class="text-sm text-base-content/60">Miembro desde</span>
                            <span class="text-sm font-semibold text-base-content">{{ $tenant->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between px-4 py-3">
                            <span class="text-sm text-base-content/60">Renovación</span>
                            <span class="text-sm font-semibold text-base-content">Por definir</span>
                        </div>
                    </div>
                    <div class="px-4 pb-4 pt-2">
                        <a href="https://syntiweb.com/planes" target="_blank" rel="noopener noreferrer"
                           class="btn btn-soft btn-primary btn-sm btn-block gap-2">
                            <span class="icon-[tabler--external-link] size-4" aria-hidden="true"></span>
                            Ver planes disponibles
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </main>

    <script>
        // Tab Navigation — FlyonUI Sidebar
        document.addEventListener('DOMContentLoaded', function() {
            const tabs     = document.querySelectorAll('#layout-sidebar [role="tab"]');
            const contents = document.querySelectorAll('.tab-content');

            function switchTab(tabId) {
                // Reset all sidebar tabs
                tabs.forEach(t => {
                    t.classList.remove('menu-active');
                    t.setAttribute('aria-selected', 'false');
                    t.setAttribute('tabindex', '-1');
                });
                // Reset all content panels
                contents.forEach(c => c.classList.remove('active'));

                // Activate selected button + panel
                const activeBtn     = document.querySelector(`#layout-sidebar [data-tab="${tabId}"]`);
                const activeContent = document.getElementById('tab-' + tabId);

                activeBtn?.classList.add('menu-active');
                activeBtn?.setAttribute('aria-selected', 'true');
                activeBtn?.setAttribute('tabindex', '0');

                activeContent?.classList.add('active');

                // Auto-close mobile sidebar drawer after navigation (via FlyonUI API)
                if (window.innerWidth < 1024) {
                    if (window.HSOverlay) {
                        window.HSOverlay.close('#layout-sidebar');
                    } else {
                        const sb = document.getElementById('layout-sidebar');
                        if (sb) { sb.classList.remove('open', 'opened'); }
                    }
                }

                // Re-init SortableJS cada vez que se abre el tab Diseño
                if (tabId === 'diseno') {
                    requestAnimationFrame(function() { window.initSortable(); });
                }
            }

            // Bind click events to all sidebar nav buttons
            tabs.forEach((tab, index) => {
                tab.addEventListener('click', function() {
                    switchTab(this.getAttribute('data-tab'));
                });

                // Keyboard: ArrowUp/Down for vertical sidebar navigation
                tab.addEventListener('keydown', function(e) {
                    let nextTab = null;
                    if (e.key === 'ArrowDown') {
                        nextTab = tabs[index + 1] || tabs[0];
                        e.preventDefault();
                    } else if (e.key === 'ArrowUp') {
                        nextTab = tabs[index - 1] || tabs[tabs.length - 1];
                        e.preventDefault();
                    } else if (e.key === 'Home') {
                        nextTab = tabs[0];
                        e.preventDefault();
                    } else if (e.key === 'End') {
                        nextTab = tabs[tabs.length - 1];
                        e.preventDefault();
                    }
                    if (nextTab) {
                        nextTab.focus();
                        switchTab(nextTab.getAttribute('data-tab'));
                    }
                });
            });
        });

        // Toggle Hours Indicator Fields
        function toggleHoursIndicatorFields() {
            const toggle = document.getElementById('show-hours-toggle');
            const fields = document.getElementById('hours-indicator-fields');
            
            if (toggle.checked) {
                fields.classList.remove('hidden');
                updateCharCount();
                updatePreview();
            } else {
                fields.classList.add('hidden');
            }
        }

        // Update character count for closed message
        function updateCharCount() {
            const textarea = document.getElementById('closed-message-input');
            const charCount = document.getElementById('char-count');
            if (textarea && charCount) {
                charCount.textContent = `${textarea.value.length} / 150`;
            }
        }

        // Update preview message in real-time
        function updatePreview() {
            const textarea = document.getElementById('closed-message-input');
            const preview = document.getElementById('preview-message');
            if (textarea && preview) {
                preview.textContent = textarea.value || 'Estamos cerrados. Te responderemos durante nuestro horario de atención.';
            }
        }

        // Initialize character count on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCharCount();
            updatePreview();
        });

        // ── Business Hours ────────────────────────────────────────
        function toggleDayClosed(dayKey, isClosed) {
            const openInput = document.getElementById('bh-' + dayKey + '-open');
            const closeInput = document.getElementById('bh-' + dayKey + '-close');
            if (openInput) openInput.disabled = isClosed;
            if (closeInput) closeInput.disabled = isClosed;
        }

        async function saveBusinessHours() {
            const days = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
            const payload = {};

            days.forEach(day => {
                const closedToggle = document.getElementById('bh-' + day + '-closed');
                const openInput = document.getElementById('bh-' + day + '-open');
                const closeInput = document.getElementById('bh-' + day + '-close');

                if (closedToggle && closedToggle.checked) {
                    payload[day] = { closed: true };
                } else {
                    payload[day] = {
                        open: openInput ? openInput.value : '08:00',
                        close: closeInput ? closeInput.value : '18:00'
                    };
                }
            });

            try {
                const response = await fetch('/tenant/{{ $tenant->id }}/update-business-hours', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();
                if (result.success) {
                    window.showToast ? window.showToast('✅ Horario guardado', 'success') : alert('✓ Horario guardado');
                } else {
                    window.showToast ? window.showToast('❌ ' + (result.message || 'Error'), 'error') : alert('✗ ' + (result.message || 'Error'));
                }
            } catch (error) {
                console.error('Error:', error);
                window.showToast ? window.showToast('❌ Error de red', 'error') : alert('✗ Error al guardar horario');
            }
        }

        // Save Info Form
        async function saveInfo(event) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch('/tenant/{{ $tenant->id }}/update-info', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    alert('✓ Información actualizada correctamente');
                } else {
                    alert('✗ Error al actualizar: ' + (result.message || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('✗ Error al guardar los cambios');
            }
        }

        // Products CRUD
        let currentProductId = null;
        const productsData = @json($products);

        // ── Plan limits exposed from PHP ─────────────────────────────
        const planId      = {{ (int) $plan->id }};
        const planName    = '{{ addslashes($plan->name) }}';
        const productsMax = {{ (int) ($plan->products_limit ?? 6) }};
        const servicesMax = {{ (int) ($plan->services_limit ?? 3) }};
        const NEXT_PLAN   = { 1: { name:'CRECIMIENTO', prods:12, svcs:6 }, 2: { name:'VISIÓN', prods:18, svcs:9 } };
        const SUPPORT_WA  = 'https://wa.me/584120000000'; // ← actualizar con número de soporte real

        // ── Limit-check wrappers (called by "Agregar" buttons) ───────
        function checkAndOpenProductModal() {
            if (productsData.length >= productsMax) {
                openLimitModal('producto');
                return;
            }
            openProductModal();
        }

        function checkAndOpenServiceModal() {
            if (servicesData.length >= servicesMax) {
                openLimitModal('servicio');
                return;
            }
            openServiceModal();
        }

        // ── Limit-reached modal ──────────────────────────────────────
        function openLimitModal(type) {
            const next  = NEXT_PLAN[planId];
            const modal = document.getElementById('limit-modal');
            const title = document.getElementById('limit-modal-title');
            const msg   = document.getElementById('limit-modal-message');
            const cta   = document.getElementById('limit-modal-cta');
            const max   = type === 'producto' ? productsMax : servicesMax;
            const noun  = type === 'producto' ? 'productos' : 'servicios';

            title.textContent = `⚠️ Límite de ${noun} alcanzado`;

            if (next) {
                const nextQty = type === 'producto' ? next.prods : next.svcs;
                msg.innerHTML =
                    `<strong class="text-base-content">Has alcanzado el máximo de ${max} ${noun}</strong> del Plan <em>${planName}</em>.<br><br>` +
                    `Actualiza al Plan <strong class="text-success">${next.name}</strong> y gestiona hasta ` +
                    `<strong class="text-base-content">${nextQty} ${noun}</strong> en tu landing.`;
                cta.innerHTML =
                    `<a href="https://syntiweb.com/planes" target="_blank" rel="noopener noreferrer" class="btn btn-success btn-sm gap-2">` +
                    `🚀 Quiero el Plan ${next.name}</a>`;
            } else {
                // Plan 3 — last plan → contact support
                msg.innerHTML =
                    `<strong class="text-base-content">Has alcanzado el máximo de ${max} ${noun}</strong> del Plan <em>${planName}</em>.<br><br>` +
                    `Para necesidades especiales, nuestro equipo puede diseñar una solución ` +
                    `personalizada para tu negocio. Contáctanos directamente.`;
                cta.innerHTML =
                    `<a href="${SUPPORT_WA}?text=${encodeURIComponent('Hola, soy cliente del Plan VISIÓN y necesito soporte personalizado.')}" ` +
                    `target="_blank" class="btn btn-success btn-sm gap-2">` +
                    `💬 Contactar Soporte</a>`;
            }

            modal.classList.add('show');
            modal.removeAttribute('aria-hidden');
            modal.querySelector('.crud-dialog-close')?.focus();
        }

        function closeLimitModal() {
            const modal = document.getElementById('limit-modal');
            modal.classList.remove('show');
            modal.setAttribute('aria-hidden', 'true');
        }
        // ────────────────────────────────────────────────────────────

        function openProductModal(productId = null) {
            const modal = document.getElementById('product-modal');
            const title = document.getElementById('product-modal-title');
            const form = document.getElementById('product-form');
            
            form.reset();
            currentProductId = productId;

            // Reset gallery UI (Plan 3)
            resetGalleryUI();
            
            if (productId) {
                // Edit mode
                title.textContent = 'Editar Producto';
                const product = productsData.find(p => p.id === productId);
                
                if (product) {
                    document.getElementById('product-id').value = product.id;
                    document.getElementById('product-name').value = product.name;
                    document.getElementById('product-description').value = product.description || '';
                    document.getElementById('product-price').value = product.price_usd;
                    document.getElementById('product-badge').value = product.badge || '';
                    document.getElementById('product-is-active').checked = product.is_active == 1;
                    document.getElementById('product-is-featured').checked = product.is_featured == 1;
                    
                    if (product.image_filename) {
                        const preview = document.getElementById('product-image-preview');
                        const img = document.getElementById('product-image-preview-img');
                        img.src = `/storage/tenants/{{ $tenant->id }}/${product.image_filename}`;
                        preview.style.display = 'block';
                    }

                    // Populate gallery (Plan 3)
                    populateGalleryUI(product);
                }
            } else {
                // Add mode
                title.textContent = 'Agregar Producto';
                document.getElementById('product-image-preview').style.display = 'none';
                showGallerySlots(0);
            }
            
            modal.classList.add('show');
            modal.removeAttribute('aria-hidden');
            modal.querySelector('.crud-dialog-close')?.focus();
        }

        function closeProductModal() {
            const modal = document.getElementById('product-modal');
            modal.classList.remove('show');
            modal.setAttribute('aria-hidden', 'true');
            currentProductId = null;
        }

        function previewProductImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('product-image-preview');
                    const img = document.getElementById('product-image-preview-img');
                    img.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }

        // ── Gallery Functions (Plan 3 / VISIÓN) ──────────────────────
        @if($plan->id === 3)
        /**
         * Reset gallery UI to clean state.
         */
        function resetGalleryUI() {
            const thumbsContainer = document.getElementById('product-gallery-thumbs');
            const previewsContainer = document.getElementById('product-gallery-previews');
            const existingContainer = document.getElementById('product-gallery-existing');
            
            if (thumbsContainer) thumbsContainer.innerHTML = '';
            if (previewsContainer) previewsContainer.innerHTML = '';
            if (existingContainer) existingContainer.classList.add('hidden');
            
            // Reset file inputs
            const g1 = document.getElementById('product-gallery-1');
            const g2 = document.getElementById('product-gallery-2');
            if (g1) g1.value = '';
            if (g2) g2.value = '';
        }

        /**
         * Populate gallery thumbnails from existing product data (edit mode).
         */
        function populateGalleryUI(product) {
            const galleryImages = product.gallery_images || [];
            const thumbsContainer = document.getElementById('product-gallery-thumbs');
            const existingContainer = document.getElementById('product-gallery-existing');

            if (!thumbsContainer || !existingContainer) return;

            thumbsContainer.innerHTML = '';

            if (galleryImages.length > 0) {
                existingContainer.classList.remove('hidden');
                
                galleryImages.forEach(img => {
                    const thumb = document.createElement('div');
                    thumb.className = 'gallery-thumb';
                    thumb.id = `gallery-thumb-${img.id}`;
                    thumb.innerHTML = `
                        <img src="/storage/tenants/{{ $tenant->id }}/${img.image_filename}" alt="Gallery">
                        <button type="button" class="gallery-thumb-delete" onclick="deleteGalleryImage(${product.id}, ${img.id})" title="Eliminar">&times;</button>
                    `;
                    thumbsContainer.appendChild(thumb);
                });
            }

            // Show available upload slots (max 2 total gallery)
            showGallerySlots(galleryImages.length);
        }

        /**
         * Show/hide gallery file upload slots based on how many gallery images exist.
         */
        function showGallerySlots(existingCount) {
            const slot1 = document.getElementById('gallery-slot-1');
            const slot2 = document.getElementById('gallery-slot-2');
            if (!slot1 || !slot2) return;

            const availableSlots = 2 - existingCount;
            slot1.classList.toggle('hidden', availableSlots < 1);
            slot2.classList.toggle('hidden', availableSlots < 2);
        }

        /**
         * Preview a gallery image file before upload.
         */
        function previewGalleryImage(event, slotNumber) {
            const file = event.target.files[0];
            if (!file) return;

            const previewsContainer = document.getElementById('product-gallery-previews');
            
            // Remove existing preview for this slot
            const existingPreview = document.getElementById(`gallery-preview-${slotNumber}`);
            if (existingPreview) existingPreview.remove();

            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'gallery-preview-thumb';
                div.id = `gallery-preview-${slotNumber}`;
                div.innerHTML = `<img src="${e.target.result}" alt="Preview galería ${slotNumber}">`;
                previewsContainer.appendChild(div);
            };
            reader.readAsDataURL(file);
        }

        /**
         * Delete an existing gallery image via API.
         */
        async function deleteGalleryImage(productId, imageId) {
            if (!confirm('¿Eliminar esta imagen de la galería?')) return;

            try {
                const response = await fetch(`/tenant/{{ $tenant->id }}/upload/product/${productId}/gallery/${imageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    }
                });

                const result = await response.json();

                if (result.success) {
                    // Remove thumbnail from DOM
                    const thumb = document.getElementById(`gallery-thumb-${imageId}`);
                    if (thumb) thumb.remove();

                    // Update productsData locally
                    const product = productsData.find(p => p.id === productId);
                    if (product && product.gallery_images) {
                        product.gallery_images = product.gallery_images.filter(gi => gi.id !== imageId);
                        showGallerySlots(product.gallery_images.length);
                        
                        if (product.gallery_images.length === 0) {
                            document.getElementById('product-gallery-existing').classList.add('hidden');
                        }
                    }
                } else {
                    alert('✗ Error: ' + (result.error || 'No se pudo eliminar'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('✗ Error al eliminar imagen de galería');
            }
        }

        /**
         * Upload pending gallery files after product save.
         */
        async function uploadPendingGalleryImages(productId) {
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
            const slots = [
                document.getElementById('product-gallery-1'),
                document.getElementById('product-gallery-2')
            ];

            for (const input of slots) {
                if (input && input.files && input.files[0]) {
                    const formData = new FormData();
                    formData.append('image', input.files[0]);

                    try {
                        const res = await fetch(`/tenant/{{ $tenant->id }}/upload/product/${productId}/gallery`, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrf },
                            body: formData
                        });

                        const result = await res.json();
                        if (!result.success) {
                            console.warn('Gallery upload failed:', result.error);
                        }
                    } catch (err) {
                        console.error('Gallery upload error:', err);
                    }
                }
            }
        }
        @else
        // Plans 1 & 2: no-op gallery functions
        function resetGalleryUI() {}
        function populateGalleryUI() {}
        function showGallerySlots() {}
        @endif
        // ── End Gallery Functions ────────────────────────────────────

        async function saveProduct(event) {
            event.preventDefault();
            
            const productId = document.getElementById('product-id').value;
            const isEdit = productId !== '';
            
            const data = {
                name: document.getElementById('product-name').value,
                description: document.getElementById('product-description').value,
                price_usd: parseFloat(document.getElementById('product-price').value),
                badge: document.getElementById('product-badge').value || null,
                is_active: document.getElementById('product-is-active').checked ? 1 : 0,
                is_featured: document.getElementById('product-is-featured').checked ? 1 : 0
            };

            try {
                const url = isEdit 
                    ? `/tenant/{{ $tenant->id }}/products/${productId}`
                    : `/tenant/{{ $tenant->id }}/products`;
                
                const response = await fetch(url, {
                    method: isEdit ? 'PUT' : 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    const savedProductId = result.product.id;

                    // Handle main image upload if file selected
                    const imageFile = document.getElementById('product-image').files[0];
                    if (imageFile) {
                        const formData = new FormData();
                        formData.append('image', imageFile);
                        
                        await fetch(`/tenant/{{ $tenant->id }}/upload/product/${savedProductId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                            },
                            body: formData
                        });
                    }

                    // Handle gallery image uploads (Plan 3 only)
                    @if($plan->id === 3)
                    await uploadPendingGalleryImages(savedProductId);
                    @endif
                    
                    alert(`✓ Producto ${isEdit ? 'actualizado' : 'creado'} correctamente`);
                    closeProductModal();
                    location.reload();
                } else {
                    alert('✗ Error: ' + (result.message || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('✗ Error al guardar el producto');
            }
        }

        function editProduct(productId) {
            openProductModal(productId);
        }

        async function deleteProduct(productId) {
            if (!confirm('¿Estás seguro de eliminar este producto?')) {
                return;
            }

            try {
                const response = await fetch(`/tenant/{{ $tenant->id }}/products/${productId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    }
                });

                const result = await response.json();

                if (result.success) {
                    alert('✓ Producto eliminado correctamente');
                    location.reload();
                } else {
                    alert('✗ Error al eliminar: ' + (result.message || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('✗ Error al eliminar el producto');
            }
        }

        // Services CRUD
        let currentServiceId = null;
        const servicesData = @json($services);

        // ── Service Visual Mode ───────────────────────────────────────
        const SVC_MODE_KEY  = 'svc_mode_{{ $tenant->id }}';
        const PLAN_ID       = {{ $plan->id }};
        // Plan 1: icon-only — never read localStorage for mode
        let serviceModalMode = (PLAN_ID === 1) ? 'icon' : (localStorage.getItem(SVC_MODE_KEY) || 'icon');

        function setGlobalServiceMode(mode) {
            if (PLAN_ID === 1) return;   // Plan 1 is always icon-only
            serviceModalMode = mode;
            localStorage.setItem(SVC_MODE_KEY, mode);
            updateGlobalModeBtns();
        }

        function updateGlobalModeBtns() {
            const iBtn   = document.getElementById('global-mode-icon-btn');
            const imgBtn = document.getElementById('global-mode-image-btn');
            if (!iBtn) return;
            iBtn.classList.toggle('seg-active', serviceModalMode === 'icon');
            imgBtn.classList.toggle('seg-active', serviceModalMode === 'image');
        }

        function setServiceModalMode(mode) {
            serviceModalMode = mode;
            localStorage.setItem(SVC_MODE_KEY, mode);
            updateGlobalModeBtns();

            const iconSect = document.getElementById('svc-section-icon');
            const imgSect  = document.getElementById('svc-section-image');
            const tabIcon  = document.getElementById('svc-tab-icon');
            const tabImg   = document.getElementById('svc-tab-image');

            if (iconSect) iconSect.style.display = (mode === 'icon')  ? '' : 'none';
            if (imgSect)  imgSect.style.display  = (mode === 'image') ? '' : 'none';

            if (tabIcon) tabIcon.classList.toggle('seg-active', mode === 'icon');
            if (tabImg)  tabImg.classList.toggle('seg-active',  mode === 'image');

            // Clear icon name input when switching to image mode
            if (mode === 'image') {
                const hiddenInput = document.getElementById('service-icon-name');
                if (hiddenInput) hiddenInput.value = '';
                iconPickerSelected = '';
                const prevEl    = document.getElementById('icon-preview-el');
                const prevLabel = document.getElementById('icon-preview-label');
                if (prevEl) {
                    prevEl.className = 'iconify tabler--settings text-primary';
                }
                if (prevLabel) prevLabel.textContent = 'Sin ícono seleccionado';
            }
        }

        // ── Icon Picker ───────────────────────────────────────────────
        const ICON_CATALOG = [
            // Negocios
            {n:'briefcase', l:'Portafolio'},      {n:'building-store', l:'Tienda'},
            {n:'award', l:'Premio'},               {n:'certificate', l:'Certificado'},
            {n:'crown', l:'Premium'},              {n:'diamond', l:'Diamante'},
            {n:'rocket', l:'Lanzamiento'},         {n:'target', l:'Objetivo'},
            {n:'trophy', l:'Trofeo'},              {n:'star', l:'Estrella'},
            {n:'heart', l:'Favorito'},             {n:'thumb-up', l:'Recomendado'},
            {n:'shield-check', l:'Seguridad'},     {n:'rosette-discount-check', l:'Verificado'},
            // Servicios físicos
            {n:'tool', l:'Herramienta'},           {n:'hammer', l:'Construcción'},
            {n:'paint', l:'Pintura'},              {n:'scissors', l:'Estética'},
            {n:'needle-thread', l:'Costura'},      {n:'pencil-bolt', l:'Reparación'},
            {n:'bolt', l:'Electricidad'},          {n:'car', l:'Automotriz'},
            {n:'home', l:'Hogar'},                 {n:'building', l:'Inmobiliaria'},
            {n:'bucket', l:'Limpieza'},            {n:'wash', l:'Lavandería'},
            // Tecnología
            {n:'device-desktop', l:'Computadora'},{n:'device-mobile', l:'Móvil'},
            {n:'wifi', l:'Internet'},              {n:'cpu', l:'Hardware'},
            {n:'code', l:'Desarrollo'},            {n:'cloud', l:'Nube'},
            {n:'headset', l:'Soporte'},            {n:'printer', l:'Impresión'},
            // Foto / Medios
            {n:'camera', l:'Fotografía'},          {n:'video', l:'Video'},
            {n:'microphone', l:'Audio/Podcast'},   {n:'palette', l:'Diseño Gráfico'},
            {n:'ballpen', l:'Escritura'},          {n:'photo', l:'Galería'},
            // Salud y Bienestar
            {n:'stethoscope', l:'Medicina'},       {n:'first-aid-kit', l:'Primeros Auxilios'},
            {n:'activity', l:'Salud'},             {n:'bath', l:'Spa/Bienestar'},
            {n:'barbell', l:'Gimnasio'},           {n:'leaf', l:'Natural/Orgánico'},
            {n:'eye', l:'Óptica/Visión'},          {n:'brain', l:'Psicología'},
            // Educación
            {n:'book', l:'Libro/Educación'},       {n:'school', l:'Escuela'},
            {n:'pencil', l:'Enseñanza'},           {n:'flask', l:'Laboratorio'},
            // Comida y Bebida
            {n:'soup', l:'Cocina'},                {n:'pizza', l:'Pizza'},
            {n:'coffee', l:'Café'},                {n:'apple', l:'Nutrición'},
            // Logística
            {n:'shopping-cart', l:'Compras'},      {n:'package', l:'Paquete'},
            {n:'truck', l:'Entrega/Delivery'},     {n:'map-pin', l:'Ubicación'},
            // Comunicación / Agenda
            {n:'phone', l:'Teléfono'},             {n:'mail', l:'Email'},
            {n:'message-circle', l:'Chat'},        {n:'calendar', l:'Agenda'},
            {n:'clock', l:'Horario'},              {n:'users', l:'Clientes/Equipo'},
            {n:'user-check', l:'Verificado'},
        ];

        let iconPickerSelected = '';

        function renderIconGrid(filter = '') {
            const grid = document.getElementById('icon-picker-grid');
            if (!grid) return;

            const term     = filter.toLowerCase().trim();
            const filtered = term
                ? ICON_CATALOG.filter(ic => ic.n.includes(term) || ic.l.toLowerCase().includes(term))
                : ICON_CATALOG;

            grid.innerHTML = '';

            if (filtered.length === 0) {
                grid.innerHTML = `<div class="col-span-6 text-center text-base-content/30 py-6 text-sm">Sin resultados para "<em>${filter}</em>"</div>`;
                return;
            }

            filtered.forEach(ic => {
                const selected = iconPickerSelected === ic.n;
                const el = document.createElement('div');
                el.className   = 'icon-pick-item' + (selected ? ' selected' : '');
                el.title       = ic.l;
                el.dataset.name = ic.n;
                el.innerHTML = `
                    <span class="iconify tabler--${ic.n} size-10 ${selected ? 'text-white' : 'text-primary'}"></span>
                    <span class="text-[10px] text-center leading-tight font-medium truncate w-full">${ic.l}</span>
                `;
                el.addEventListener('click', () => selectIcon(ic.n, ic.l));
                grid.appendChild(el);
            });
        }

        function filterIcons(val) { renderIconGrid(val); }

        function selectIcon(iconName, iconLabel) {
            iconPickerSelected = iconName;
            const hidden = document.getElementById('service-icon-name');
            if (hidden) hidden.value = iconName;
            const prevEl    = document.getElementById('icon-preview-el');
            const prevLabel = document.getElementById('icon-preview-label');
            if (prevEl) {
                prevEl.className = 'iconify tabler--' + iconName + ' size-12 text-primary';
            }
            if (prevLabel) prevLabel.textContent = iconLabel;
            const searchVal = document.getElementById('icon-search')?.value || '';
            renderIconGrid(searchVal);
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateGlobalModeBtns();

            // ── Live Clock ────────────────────────────────────────────
            function updateClock() {
                const el = document.getElementById('live-clock');
                if (!el) return;
                el.textContent = new Date().toLocaleTimeString('es-VE', {
                    hour: '2-digit', minute: '2-digit', second: '2-digit'
                });
            }
            updateClock();
            setInterval(updateClock, 1000);
        });

        function openServiceModal(serviceId = null) {
            const modal = document.getElementById('service-modal');
            const title = document.getElementById('service-modal-title');
            const form  = document.getElementById('service-form');

            form.reset();
            currentServiceId = serviceId;

            if (serviceId) {
                // Edit mode
                title.textContent = 'Editar Servicio';
                const service = servicesData.find(s => s.id === serviceId);

                if (service) {
                    document.getElementById('service-id').value = service.id;
                    document.getElementById('service-name').value = service.name;
                    document.getElementById('service-description').value = service.description || '';
                    document.getElementById('service-is-active').checked = service.is_active == 1;

                    // Restore icon or image mode based on what the service has
                    const hasIcon  = !!service.icon_name;
                    const hasImage = !!service.image_filename;
                    const modeToSet = hasImage && !hasIcon ? 'image' : serviceModalMode;

                    if (hasIcon) {
                        iconPickerSelected = service.icon_name;
                        const hidden = document.getElementById('service-icon-name');
                        if (hidden) hidden.value = service.icon_name;
                        const prevEl    = document.getElementById('icon-preview-el');
                        const prevLabel = document.getElementById('icon-preview-label');
                        if (prevEl) {
                            prevEl.className = 'iconify tabler--' + service.icon_name + ' text-primary';
                        }
                        if (prevLabel) prevLabel.textContent = service.icon_name;
                    }

                    if (hasImage && modeToSet === 'image') {
                        const preview = document.getElementById('service-image-preview');
                        const img     = document.getElementById('service-image-preview-img');
                        if (preview && img) {
                            img.src = `/storage/tenants/{{ $tenant->id }}/${service.image_filename}`;
                            preview.style.display = 'block';
                        }
                    }

                    setServiceModalMode(modeToSet);
                }
            } else {
                // Add mode — reset picker
                title.textContent = 'Agregar Servicio';
                iconPickerSelected = '';
                const hidden = document.getElementById('service-icon-name');
                if (hidden) hidden.value = '';
                const prevEl    = document.getElementById('icon-preview-el');
                const prevLabel = document.getElementById('icon-preview-label');
                if (prevEl) {
                    prevEl.className = 'iconify tabler--settings text-primary';
                }
                if (prevLabel) prevLabel.textContent = 'Sin ícono seleccionado';
                const imgPrev = document.getElementById('service-image-preview');
                if (imgPrev) imgPrev.style.display = 'none';
                setServiceModalMode(serviceModalMode);
            }

            // Render icon grid (always)
            const searchInput = document.getElementById('icon-search');
            if (searchInput) searchInput.value = '';
            renderIconGrid('');

            modal.classList.add('show');
            modal.removeAttribute('aria-hidden');
            modal.querySelector('.crud-dialog-close')?.focus();
        }

        function closeServiceModal() {
            const modal = document.getElementById('service-modal');
            modal.classList.remove('show');
            modal.setAttribute('aria-hidden', 'true');
            currentServiceId = null;
        }

        function previewServiceImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('service-image-preview');
                    const img = document.getElementById('service-image-preview-img');
                    img.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }

        async function saveService(event) {
            event.preventDefault();
            
            const serviceId = document.getElementById('service-id').value;
            const isEdit = serviceId !== '';
            
            // Plan 1 is always icon mode regardless of localStorage
            const currentMode = (PLAN_ID === 1) ? 'icon' : serviceModalMode;
            const iconNameVal = document.getElementById('service-icon-name')?.value?.trim() || null;

            const data = {
                name: document.getElementById('service-name').value,
                description: document.getElementById('service-description').value,
                is_active: document.getElementById('service-is-active').checked ? 1 : 0,
                icon_name: currentMode === 'icon' ? (iconNameVal || null) : null,
            };

            try {
                const url = isEdit 
                    ? `/tenant/{{ $tenant->id }}/services/${serviceId}`
                    : `/tenant/{{ $tenant->id }}/services`;
                
                const response = await fetch(url, {
                    method: isEdit ? 'PUT' : 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    // Handle image upload if in image mode and a file was selected
                    const imageInput = document.getElementById('service-image');
                    const imageFile  = currentMode === 'image' ? imageInput?.files?.[0] : null;
                    if (imageFile) {
                        const formData = new FormData();
                        formData.append('image', imageFile);
                        
                        await fetch(`/tenant/{{ $tenant->id }}/upload/service/${result.data.id}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                            },
                            body: formData
                        });
                    }
                    
                    alert(`✓ Servicio ${isEdit ? 'actualizado' : 'creado'} correctamente`);
                    closeServiceModal();
                    location.reload();
                } else {
                    alert('✗ Error: ' + (result.message || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('✗ Error al guardar el servicio');
            }
        }

        function editService(serviceId) {
            openServiceModal(serviceId);
        }

        async function deleteService(serviceId) {
            if (!confirm('¿Estás seguro de eliminar este servicio?')) {
                return;
            }

            try {
                const response = await fetch(`/tenant/{{ $tenant->id }}/services/${serviceId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    }
                });

                const result = await response.json();

                if (result.success) {
                    alert('✓ Servicio eliminado correctamente');
                    location.reload();
                } else {
                    alert('✗ Error al eliminar: ' + (result.message || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('✗ Error al eliminar el servicio');
            }
        }

        // ── Image Uploads: Logo, Hero + Drag & Drop ─────────────
        async function _uploadImage(file, type) {
            if (!file || !file.type.startsWith('image/')) {
                showToast('❌ Selecciona un archivo de imagen válido', 'error');
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                showToast('❌ La imagen no debe superar 5MB', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('image', file);

            const dropzone = document.getElementById(type + '-dropzone');
            if (dropzone) dropzone.style.opacity = '0.5';

            try {
                const response = await fetch('/tenant/{{ $tenant->id }}/upload/' + type, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content },
                    body: formData
                });

                const result = await response.json();
                if (dropzone) dropzone.style.opacity = '1';

                if (result.success) {
                    const previewId = type + '-preview';
                    const placeholderId = type + '-placeholder';
                    const placeholder = document.getElementById(placeholderId);
                    let preview = document.getElementById(previewId);

                    if (placeholder) placeholder.style.display = 'none';

                    const newSrc = result.url + '?t=' + Date.now();
                    const imgClass = type === 'logo' ? 'max-w-full max-h-full object-contain' : 'w-full h-full object-cover';

                    if (preview) {
                        preview.src = newSrc;
                        preview.style.display = 'block';
                    } else {
                        const img = document.createElement('img');
                        img.id = previewId;
                        img.src = newSrc;
                        img.alt = type.charAt(0).toUpperCase() + type.slice(1);
                        img.className = imgClass;
                        const dz = document.getElementById(type + '-dropzone');
                        if (dz) dz.appendChild(img);
                    }
                    showToast('✅ ' + (type === 'logo' ? 'Logo' : 'Imagen hero') + ' actualizado correctamente');
                } else {
                    showToast('❌ Error al subir ' + type + ': ' + (result.error || result.message || 'Error desconocido'), 'error');
                }
            } catch (err) {
                if (dropzone) dropzone.style.opacity = '1';
                console.error('upload ' + type + ':', err);
                showToast('❌ Error de red al subir ' + type, 'error');
            }
        }

        function uploadLogo(event) { _uploadImage(event.target.files[0], 'logo'); }
        function uploadHero(event) { _uploadImage(event.target.files[0], 'hero'); }

        function handleDropUpload(event, type) {
            const file = event.dataTransfer.files[0];
            if (file) _uploadImage(file, type);
        }

        function downloadQRSVG() {
            const qrContainer = document.getElementById('qr-display');
            if (!qrContainer) return;
            const svgEl = qrContainer.querySelector('svg');
            if (!svgEl) {
                showToast('❌ No se encontró el SVG del QR', 'error');
                return;
            }
            const svgData = new XMLSerializer().serializeToString(svgEl);
            const blob = new Blob([svgData], { type: 'image/svg+xml;charset=utf-8' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'qr-{{ $tenant->subdomain }}.svg';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }
        // ── End Image Uploads ────────────────────────────────────────

        // Design Tab: Custom Palette (Plan 3)
        function applyCustomPalette() {
            const colors = {
                primary: document.getElementById('custom-primary').value,
                secondary: document.getElementById('custom-secondary').value,
                accent: document.getElementById('custom-accent').value,
                base: document.getElementById('custom-base').value
            };
            
            fetch(`/tenant/{{ $tenant->id }}/dashboard/save-custom-palette`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(colors)
            })
            .then(r => r.json())
            .then(data => {
                if(data.success) {
                    showToast('✅ Paleta personalizada guardada');
                    
                    // Aplicar HEX directo (FlyonUI acepta hex en vars CSS)
                    document.documentElement.style.setProperty('--color-primary', colors.primary);
                    document.documentElement.style.setProperty('--color-secondary', colors.secondary);
                    document.documentElement.style.setProperty('--color-accent', colors.accent);
                    document.documentElement.style.setProperty('--color-base-100', colors.base);
                    
                    setTimeout(() => location.reload(), 1500);
                }
            })
            .catch(err => showToast('❌ Error al aplicar paleta'));
        }

        // Design Tab: Theme Update (FlyonUI)
        function updateTheme(theme) {
            fetch(`/tenant/{{ $tenant->id }}/update-theme`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    theme_slug: theme,
                    clear_custom: true
                })
            })
            .then(r => r.json())
            .then(data => {
                if(data.success) {
                    showToast('✅ Tema ' + theme + ' aplicado');
                    setTimeout(() => location.reload(), 1000);
                }
            });
        }

        // (Duplicates removed — unified upload functions are above)

        // ══════════════════════════════════════════════════════════════
        // Analytics Tab: Load Analytics Data
        // ══════════════════════════════════════════════════════════════
        let analyticsChart = null;

        async function loadAnalytics() {
            const kpiIds = ['visitors-today','visitors-week','whatsapp-clicks','qr-scans','call-clicks','currency-toggles','avg-time'];

            // Loading state — Resiliencia Visual
            kpiIds.forEach(id => {
                const el = document.getElementById(id);
                if (el) { el.innerHTML = '<span class="loading loading-dots loading-xs"></span>'; }
            });

            try {
                const response = await fetch('/tenant/{{ $tenant->id }}/analytics');
                if (!response.ok) throw new Error('HTTP ' + response.status);
                const result = await response.json();

                if (result.success) {
                    const data = result.data;

                    document.getElementById('visitors-today').textContent = data.visitors_today || 0;
                    document.getElementById('visitors-week').textContent = data.visitors_week || 0;
                    document.getElementById('whatsapp-clicks').textContent = data.whatsapp_clicks || 0;
                    document.getElementById('qr-scans').textContent = data.qr_scans || 0;
                    document.getElementById('call-clicks').textContent = data.call_clicks || 0;
                    document.getElementById('currency-toggles').textContent = data.currency_toggles || 0;
                    document.getElementById('avg-time').textContent = data.avg_time_on_page || 0;

                    renderAnalyticsChart(data.last_7_days);
                } else {
                    // Error state
                    kpiIds.forEach(id => {
                        const el = document.getElementById(id);
                        if (el) el.textContent = '—';
                    });
                }
            } catch (error) {
                console.error('Error loading analytics:', error);
                // Error state — Resiliencia Visual
                kpiIds.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.textContent = '—';
                });
                showToast('❌ Error al cargar analytics', 'error');
            }
        }

        function renderAnalyticsChart(last7Days) {
            const ctx = document.getElementById('analytics-chart');
            if (!ctx) return;

            // Destruir gráfico anterior si existe
            if (analyticsChart) {
                analyticsChart.destroy();
            }

            const labels = last7Days.map(d => {
                const date = new Date(d.date);
                return date.toLocaleDateString('es-ES', { weekday: 'short', day: 'numeric' });
            });
            const visitors = last7Days.map(d => d.visitors);

            analyticsChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Visitantes',
                        data: visitors,
                        backgroundColor: 'rgba(87, 13, 248, 0.1)',
                        borderColor: 'rgba(87, 13, 248, 1)',
                        borderWidth: 2,
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        // Cargar analytics cuando se abre el tab
        document.addEventListener('DOMContentLoaded', function() {
            const analyticsTab = document.getElementById('tab-analytics-btn');
            if (analyticsTab) {
                analyticsTab.addEventListener('click', function() {
                    setTimeout(() => loadAnalytics(), 100);
                });
            }
        });

        // Analytics Tab: Update Dollar Rate
        async function updateDollarRate() {
            try {
                const response = await fetch('/api/dollar-rate');
                const result = await response.json();

                if (result.success && result.rate) {
                    // Actualizar el valor en pantalla
                    document.getElementById('dollar-rate-value').textContent = result.rate.toFixed(2);
                    alert('✓ Tasa del dólar actualizada correctamente');
                } else {
                    alert('✗ No se pudo actualizar la tasa del dólar');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('✗ Error al actualizar la tasa');
            }
        }

        // Analytics Tab: Toggle Business Status (Large)
        async function toggleBusinessStatusLarge() {
            const toggle = document.getElementById('status-toggle-large');
            const tenantId = {{ $tenant->id }};
            
            try {
                const response = await fetch(`/tenant/${tenantId}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const result = await response.json();

                if (result.success) {
                    // Actualizar UI
                    const statusText = document.querySelector('#tab-analytics span[style*="color: #00cc66"]');
                    if (statusText) {
                        const isOpen = toggle.checked;
                        statusText.textContent = isOpen ? '🟢 Abierto' : '🔴 Cerrado';
                    }
                } else {
                    // Revertir el toggle si falla
                    toggle.checked = !toggle.checked;
                    alert('✗ Error al cambiar estado');
                }
            } catch (error) {
                console.error('Error:', error);
                toggle.checked = !toggle.checked;
                alert('✗ Error al cambiar el estado');
            }
        }

        // Config Tab: Currency Symbol Toggle UI Only
        function updateCurrencySymbolUI() {
            const toggle = document.getElementById('currency-symbol-switch');
            const slider = document.getElementById('currency-slider');
            const refLabel = document.getElementById('symbol-ref-label');
            const dollarLabel = document.getElementById('symbol-dollar-label');
            
            // Update UI only
            if (toggle.checked) {
                slider.style.transform = 'translateX(26px)';
                slider.style.backgroundColor = '#2B6FFF';
                slider.parentElement.children[1].style.backgroundColor = '#2B6FFF';
                refLabel.style.color = '#6b7280';
                dollarLabel.style.color = '#2B6FFF';
            } else {
                slider.style.transform = 'translateX(0)';
                slider.style.backgroundColor = '#6b7280';
                slider.parentElement.children[1].style.backgroundColor = '#1e2a42';
                refLabel.style.color = '#2B6FFF';
                dollarLabel.style.color = '#6b7280';
            }
        }

        // Config Tab: Save Complete Currency Configuration
        async function saveCurrencyConfig() {
            const symbol = document.getElementById('currency-symbol-switch').checked ? '$' : 'REF';
            const display_mode = document.querySelector('input[name="display_mode"]:checked')?.value;
            const tenantId = {{ $tenant->id }};
            
            console.log('Payload moneda:', {symbol, display_mode});
            
            if (!display_mode) {
                alert('✗ Seleccioná un modo de visualización');
                return;
            }
            
            try {
                const response = await fetch(`/tenant/${tenantId}/update-currency-config`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ 
                        symbol: symbol,
                        display_mode: display_mode
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('✓ Configuración de moneda guardada correctamente');
                } else {
                    alert('✗ ' + (data.message || 'Error al guardar configuración'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('✗ Error al guardar configuración');
            }
        }

        // Config Tab: Update PIN
        async function updatePin() {
            const currentPin = document.getElementById('current-pin').value;
            const newPin = document.getElementById('new-pin').value;
            const confirmPin = document.getElementById('confirm-pin').value;
            const tenantId = {{ $tenant->id }};
            
            // Validation
            if (!currentPin || !newPin || !confirmPin) {
                alert('✗ Todos los campos son obligatorios');
                return;
            }
            
            if (!/^\d{4}$/.test(currentPin) || !/^\d{4}$/.test(newPin)) {
                alert('✗ El PIN debe tener exactamente 4 dígitos');
                return;
            }
            
            if (newPin !== confirmPin) {
                alert('✗ El PIN nuevo y la confirmación no coinciden');
                return;
            }
            
            if (currentPin === newPin) {
                alert('✗ El PIN nuevo debe ser diferente al actual');
                return;
            }
            
            try {
                const response = await fetch(`/tenant/${tenantId}/update-pin`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        current_pin: currentPin,
                        new_pin: newPin,
                        new_pin_confirmation: confirmPin
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('✓ PIN actualizado correctamente');
                    document.getElementById('pin-form').reset();
                } else {
                    alert('✗ ' + (data.message || 'Error al actualizar PIN'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('✗ Error al actualizar PIN');
            }
        }

        // Reset Form
        function resetForm(formId) {
            document.getElementById(formId).reset();
        }

        // ── Social Networks ──────────────────────────────────────────
        @php
            $plan1NetworksList = ['instagram', 'facebook', 'tiktok', 'linkedin'];
            $allNetworksList   = ['instagram', 'facebook', 'tiktok', 'linkedin', 'youtube', 'x'];
        @endphp
        let selectedSocialNetwork = '{{ $plan1Selected ?? '' }}';

        function selectSocialNetwork(key) {
            // Update selected state
            selectedSocialNetwork = key;

            // Update radio labels visually using FlyonUI classes
            @foreach($plan1Networks as $k)
            const el_{{ $k }} = document.getElementById('social-radio-label-{{ $k }}');
            if (key === '{{ $k }}') {
                el_{{ $k }}.className = 'btn btn-sm gap-1.5 btn-primary cursor-pointer';
            } else {
                el_{{ $k }}.className = 'btn btn-sm gap-1.5 btn-ghost border border-base-content/20 cursor-pointer';
            }
            @endforeach

            // Update label and placeholder
            const meta = {
                instagram: { label: 'Instagram',   placeholder: '@tuusuario' },
                facebook:  { label: 'Facebook',    placeholder: '@pagina o URL' },
                tiktok:    { label: 'TikTok',      placeholder: '@tuusuario' },
                linkedin:  { label: 'LinkedIn',    placeholder: 'URL o usuario' },
            };
            const networkLabel = document.getElementById('social-plan1-network-label');
            const handleInput  = document.getElementById('social-plan1-handle');
            if (networkLabel) networkLabel.textContent = '(' + (meta[key]?.label || '') + ')';
            if (handleInput) {
                handleInput.placeholder = meta[key]?.placeholder || '';
                handleInput.disabled = false;
            }
        }

        async function saveSocialNetworks() {
            const tenantId = {{ $tenant->id }};
            const plan = {{ $plan->id }};
            let payload = {};

            if (plan === 1) {
                if (!selectedSocialNetwork) {
                    alert('✗ Selecciona una red social primero');
                    return;
                }
                const handle = document.getElementById('social-plan1-handle')?.value?.trim();
                if (!handle) {
                    alert('✗ Ingresa el usuario o enlace de tu red social');
                    return;
                }
                payload[selectedSocialNetwork] = handle;
            } else {
                @foreach($allNetworksList as $k)
                const val_{{ $k }} = document.getElementById('social-{{ $k }}')?.value?.trim();
                if (val_{{ $k }}) payload['{{ $k }}'] = val_{{ $k }};
                @endforeach
            }

            try {
                const response = await fetch(`/tenant/${tenantId}/update-social-networks`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();
                if (result.success) {
                    alert('✓ Redes sociales guardadas correctamente');
                } else {
                    alert('✗ ' + (result.message || 'Error al guardar'));
                }
            } catch (err) {
                console.error('Error:', err);
                alert('✗ Error al guardar redes sociales');
            }
        }
        // ── End Social Networks ─────────────────────────────────────

        // ── Payment Methods ──────────────────────────────────────────
        @if($plan->id !== 1)
        const payAllKeys  = @json(array_keys($allPayMeta));
        const currAllKeys = @json(array_keys($allCurrencyMeta));
        const allPayMetaData  = @json($allPayMeta);
        const allCurrMetaData = @json($allCurrencyMeta);
        const divisaKeys  = ['zelle', 'zinli', 'paypal'];

        function togglePayMethod(key) {
            const check = document.getElementById('pay-check-' + key);
            const label = document.getElementById('pay-label-' + key);
            if (!check || !label) return;
            check.checked = !check.checked;
            // Use classList instead of inline styles (Tailwind classes take precedence)
            label.classList.toggle('bg-primary/20', check.checked);
            label.classList.toggle('border-primary/50', check.checked);
            label.classList.toggle('text-primary', check.checked);
            label.classList.toggle('font-semibold', check.checked);
            label.classList.toggle('bg-base-200/50', !check.checked);
            label.classList.toggle('border-base-content/10', !check.checked);
            label.classList.toggle('text-base-content', !check.checked);
            updatePaymentPreview();
        }

        function toggleCurrency(key) {
            const check = document.getElementById('curr-check-' + key);
            const label = document.getElementById('curr-label-' + key);
            if (!check || !label) return;
            check.checked = !check.checked;
            // Use classList instead of inline styles
            label.classList.toggle('bg-primary/20', check.checked);
            label.classList.toggle('border-primary/50', check.checked);
            label.classList.toggle('text-primary', check.checked);
            label.classList.toggle('font-semibold', check.checked);
            label.classList.toggle('bg-base-200/50', !check.checked);
            label.classList.toggle('border-base-content/10', !check.checked);
            label.classList.toggle('text-base-content', !check.checked);
            updatePaymentPreview();
        }

        function updatePaymentPreview() {
            const preview = document.getElementById('payment-preview');
            if (!preview) return;
            const selected = [];
            payAllKeys.forEach(k => {
                const el = document.getElementById('pay-check-' + k);
                if (el && el.checked) {
                    selected.push({ key: k, ...allPayMetaData[k], type: 'method' });
                }
            });
            currAllKeys.forEach(k => {
                const el = document.getElementById('curr-check-' + k);
                if (el && el.checked) {
                    selected.push({ key: k, ...allCurrMetaData[k], type: 'currency' });
                }
            });
            if (selected.length === 0) {
                preview.innerHTML = '<span class="text-base-content/40 text-xs">Selecciona métodos para ver la previa</span>';
                return;
            }
            preview.innerHTML = selected.map(item => 
                `<span class="badge badge-soft badge-success badge-sm gap-1.5">
                    <iconify-icon icon="${item.icon}" width="14"></iconify-icon> ${item.label}
                </span>`
            ).join('');
        }

        // Inicializar previa al cargar
        document.addEventListener('DOMContentLoaded', updatePaymentPreview);

        @if($plan->id === 3)
        function toggleBranchPayMethod(branchId, key) {
            const check = document.getElementById('pay-branch-check-' + branchId + '-' + key);
            const label = document.getElementById('pay-branch-label-' + branchId + '-' + key);
            if (!check || !label) return;
            check.checked = !check.checked;
            // Use classList instead of inline styles
            label.classList.toggle('bg-primary/20', check.checked);
            label.classList.toggle('border-primary/50', check.checked);
            label.classList.toggle('text-primary', check.checked);
            label.classList.toggle('font-semibold', check.checked);
            label.classList.toggle('bg-base-100', !check.checked);
            label.classList.toggle('border-base-content/10', !check.checked);
            label.classList.toggle('text-base-content', !check.checked);
        }
        @endif

        async function savePaymentMethods() {
            const tenantId = {{ $tenant->id }};
            const globalSelected = payAllKeys.filter(k => {
                const el = document.getElementById('pay-check-' + k);
                return el && el.checked;
            });
            const currencySelected = currAllKeys.filter(k => {
                const el = document.getElementById('curr-check-' + k);
                return el && el.checked;
            });
            const payload = { global: globalSelected, currency: currencySelected };

            @if($plan->id === 3)
            const branchData = {};
            @foreach($activeBranchList as $branch)
            const bMethods_{{ $branch->id }} = payAllKeys.filter(k => {
                const el = document.getElementById('pay-branch-check-{{ $branch->id }}-' + k);
                return el && el.checked;
            });
            if (bMethods_{{ $branch->id }}.length > 0) {
                branchData['{{ $branch->id }}'] = bMethods_{{ $branch->id }};
            }
            @endforeach
            payload.branches = branchData;
            @endif

            try {
                const response = await fetch('/tenant/' + tenantId + '/update-payment-methods', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    },
                    body: JSON.stringify(payload)
                });
                const result = await response.json();
                if (result.success) {
                    alert('✓ Medios de pago y denominaciones guardados correctamente');
                } else {
                    alert('✗ ' + (result.message || 'Error al guardar'));
                }
            } catch (err) {
                console.error('Error:', err);
                alert('✗ Error al guardar medios de pago');
            }
        }
        @endif
        // ── End Payment Methods ──────────────────────────────────────

        // ── Branches (Plan 3 / VISIÓN) ──────────────────────────────
        @if($plan->id === 3)
        let branchCount = {{ $branches->count() }};

        async function toggleBranchesSection() {
            const enabled = document.getElementById('branches-toggle').checked;

            try {
                const response = await fetch('/tenant/{{ $tenant->id }}/branches/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    },
                    body: JSON.stringify({ enabled })
                });

                const result = await response.json();

                if (result.success) {
                    const content = document.getElementById('branches-content');
                    const status = document.getElementById('branches-status');
                    const statusText = document.getElementById('branches-status-text');

                    content.style.display = enabled ? '' : 'none';
                    status.className = enabled ? 'alert alert-success' : 'alert alert-info';
                    statusText.textContent = enabled
                        ? 'Sección visible en tu landing pública'
                        : 'Sección oculta en tu landing pública';
                } else {
                    // Revert toggle
                    document.getElementById('branches-toggle').checked = !enabled;
                    alert('✗ ' + (result.message || 'Error'));
                }
            } catch (err) {
                document.getElementById('branches-toggle').checked = !enabled;
                console.error('Error:', err);
                alert('✗ Error al cambiar estado de sucursales');
            }
        }

        function openBranchModal() {
            document.getElementById('branch-modal-title').textContent = '+ Agregar Sucursal';
            document.getElementById('branch-edit-id').value = '';
            document.getElementById('branch-form').reset();
            document.getElementById('branch-modal').style.display = 'flex';
        }

        function editBranch(id, name, address) {
            document.getElementById('branch-modal-title').textContent = '✏️ Editar Sucursal';
            document.getElementById('branch-edit-id').value = id;
            document.getElementById('branch-name').value = name;
            document.getElementById('branch-address').value = address;
            document.getElementById('branch-modal').style.display = 'flex';
        }

        function closeBranchModal() {
            document.getElementById('branch-modal').style.display = 'none';
            document.getElementById('branch-form').reset();
            document.getElementById('branch-edit-id').value = '';
        }

        async function saveBranch(event) {
            event.preventDefault();
            
            const name = document.getElementById('branch-name').value.trim();
            const address = document.getElementById('branch-address').value.trim();
            const editId = document.getElementById('branch-edit-id').value;

            if (!name || !address) {
                alert('✗ Nombre y dirección son obligatorios');
                return;
            }

            const payload = { name, address, is_active: true };
            if (editId) payload.id = parseInt(editId);

            try {
                const response = await fetch('/tenant/{{ $tenant->id }}/branches', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (result.success) {
                    closeBranchModal();
                    alert('✓ ' + result.message);
                    location.reload();
                } else {
                    alert('✗ ' + (result.message || 'Error desconocido'));
                }
            } catch (err) {
                console.error('Error:', err);
                alert('✗ Error al guardar sucursal');
            }
        }

        async function deleteBranch(branchId) {
            if (!confirm('¿Eliminar esta sucursal?')) return;

            try {
                const response = await fetch(`/tenant/{{ $tenant->id }}/branches/${branchId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    }
                });

                const result = await response.json();

                if (result.success) {
                    alert('✓ Sucursal eliminada');
                    location.reload();
                } else {
                    alert('✗ ' + (result.message || 'Error'));
                }
            } catch (err) {
                console.error('Error:', err);
                alert('✗ Error al eliminar sucursal');
            }
        }
        @endif
        // ── End Branches ────────────────────────────────────────────
    </script>

    {{-- ═══ SortableJS CDN + global drag-and-drop init ═══ --}}
    <style>
        .sortable-ghost { opacity: 0.3 !important; }
        .sortable-drag  { box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5) !important; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        // ── Toast global ──────────────────────────────────────────────
        window.showToast = function(message, type) {
            const toast = document.createElement('div');
            const bg    = type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6';
            toast.textContent = message;
            Object.assign(toast.style, {
                position: 'fixed', bottom: '24px', right: '24px', zIndex: '99999',
                background: bg, color: '#fff', padding: '12px 20px',
                borderRadius: '10px', fontSize: '14px', fontWeight: '600',
                boxShadow: '0 4px 20px rgba(0,0,0,0.3)', opacity: '0',
                transition: 'opacity 0.3s ease', maxWidth: '320px'
            });
            document.body.appendChild(toast);
            requestAnimationFrame(() => { toast.style.opacity = '1'; });
            setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 2500);
            // Anunciar mensaje a lectores de pantalla via aria-live
            const announcer = document.getElementById('toast-announcer');
            if (announcer) { announcer.textContent = ''; requestAnimationFrame(() => { announcer.textContent = message; }); }
        };

        // ── Sortable init (se llama cada vez que abre tab Diseño) ────
        window._sortableInstance = null;
        window.initSortable = function() {
            if (typeof Sortable === 'undefined') {
                console.error('❌ SortableJS no cargó — D&D no disponible');
                return;
            }
            const sortableEl = document.getElementById('sortable-sections');
            if (!sortableEl) { console.error('❌ sortable-sections no encontrado'); return; }

            // Destruir instancia anterior si existe
            if (window._sortableInstance) {
                try { window._sortableInstance.destroy(); } catch(e) {}
                window._sortableInstance = null;
            }

            window._sortableInstance = new Sortable(sortableEl, {
                handle: '.drag-handle',
                animation: 200,
                ghostClass: 'sortable-ghost',
                dragClass:  'sortable-drag',
                forceFallback: false,
                onEnd: function() { saveSectionsOrder(); }
            });

            console.log('✅ SortableJS listo — ' + sortableEl.children.length + ' secciones');
        };

        function saveSectionsOrder() {
            const sortableEl = document.getElementById('sortable-sections');
            if (!sortableEl) return;
            const sections = [];
            sortableEl.querySelectorAll('.section-item').forEach((item, index) => {
                const name    = item.dataset.section;
                const toggle  = item.querySelector('.section-toggle');
                const visible = toggle ? toggle.checked : true;
                sections.push({ name, visible, order: index });
            });
            fetch(`/tenant/{{ $tenant->id }}/dashboard/save-section-order`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ sections_order: sections })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) { window.showToast('\u2705 Orden guardado', 'success'); }
                else              { window.showToast('\u274c Error al guardar', 'error'); }
            })
            .catch(() => window.showToast('\u274c Error de red', 'error'));
        }

        // ── Mover sección con flechas ▲▼ ─────────────────────────────
        function moveSection(key, direction) {
            const container = document.getElementById('sortable-sections');
            const items     = Array.from(container.querySelectorAll('.section-item'));
            const idx       = items.findIndex(el => el.dataset.section === key);
            const target    = idx + direction;
            if (target < 0 || target >= items.length) return;
            // Reordenar en DOM
            if (direction === -1) {
                container.insertBefore(items[idx], items[target]);
            } else {
                container.insertBefore(items[target], items[idx]);
            }
            saveSectionsOrder();
        }

        // ── Testimonials Global Storage ────────────────────────────────
        let testimonialData = [
            @foreach($savedTestimonials as $ti => $testim)
            { name: '{{ addslashes($testim['name'] ?? '') }}', title: '{{ addslashes($testim['title'] ?? '') }}', text: '{{ addslashes($testim['text'] ?? '') }}', rating: {{ $testim['rating'] ?? 5 }} },
            @endforeach
        ];

        function editTestimonial(index, name, title, text, rating) {
            document.getElementById('testimonial-edit-index').value = index;
            document.getElementById('testimonial-name').value = name;
            document.getElementById('testimonial-title').value = title;
            document.getElementById('testimonial-text').value = text;
            document.getElementById('testimonial-rating').value = rating;
            document.getElementById('testimonial-modal').style.display = 'flex';
        }

        function closeTestimonialModal() {
            document.getElementById('testimonial-modal').style.display = 'none';
            document.getElementById('testimonial-form').reset();
            document.getElementById('testimonial-edit-index').value = '';
        }

        function saveTestimonialItem(event) {
            event.preventDefault();
            const indexStr = document.getElementById('testimonial-edit-index').value;
            const name = document.getElementById('testimonial-name').value.trim();
            const title = document.getElementById('testimonial-title').value.trim();
            const text = document.getElementById('testimonial-text').value.trim();
            const rating = parseInt(document.getElementById('testimonial-rating').value);

            if (!name || !text) {
                alert('✗ Nombre y testimonio son obligatorios');
                return;
            }

            if (indexStr === '') {
                // Nuevo item
                testimonialData.push({ name, title, text, rating });
            } else {
                // Editar item existente
                const index = parseInt(indexStr);
                testimonialData[index] = { name, title, text, rating };
            }
            closeTestimonialModal();
        }

        function deleteTestimonial(index) {
            if (!confirm('¿Eliminar este testimonial?')) return;
            // REMOVER del array inmediatamente (como Branches)
            testimonialData.splice(index, 1);
            // Oculta la card visualmente
            const cardElement = document.querySelector(`[data-testimonial-index="${index}"]`);
            if (cardElement) {
                cardElement.style.opacity = '0';
                cardElement.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    cardElement.style.display = 'none';
                }, 300);
            }
        }

        function addTestimonial() {
            // Abre el modal en modo "crear nuevo" (sin índice)
            document.getElementById('testimonial-edit-index').value = '';
            document.getElementById('testimonial-name').value = '';
            document.getElementById('testimonial-title').value = '';
            document.getElementById('testimonial-text').value = '';
            document.getElementById('testimonial-rating').value = '5';
            document.getElementById('testimonial-modal').style.display = 'flex';
        }

        // ── Guardar Testimonios ────────────────────────────────────────
        function saveTestimonials() {
            // Ya no hay items vacíos porque los removemos con splice()
            fetch(`/tenant/{{ $tenant->id }}/update-testimonials`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ testimonials: testimonialData })
            })
            .then(r => r.json())
            .then(d => {
                if (d.success) {
                    window.showToast('✅ Testimonios guardados', 'success');
                    location.reload();
                } else {
                    window.showToast('✗ Error', 'error');
                }
            })
            .catch(() => window.showToast('✗ Error de red', 'error'));
        }

        // ── FAQ Global Storage ────────────────────────────────────────
        let faqData = [
            @foreach($savedFaq as $fi => $fitem)
            { question: '{{ addslashes($fitem['question'] ?? '') }}', answer: '{{ addslashes($fitem['answer'] ?? '') }}' },
            @endforeach
        ];

        function editFaq(index, question, answer) {
            document.getElementById('faq-edit-index').value = index;
            document.getElementById('faq-question').value = question;
            document.getElementById('faq-answer').value = answer;
            document.getElementById('faq-modal').style.display = 'flex';
        }

        function closeFaqModal() {
            document.getElementById('faq-modal').style.display = 'none';
            document.getElementById('faq-form').reset();
            document.getElementById('faq-edit-index').value = '';
        }

        function saveFaqItem(event) {
            event.preventDefault();
            const indexStr = document.getElementById('faq-edit-index').value;
            const question = document.getElementById('faq-question').value.trim();
            const answer = document.getElementById('faq-answer').value.trim();

            if (!question || !answer) {
                alert('✗ Pregunta y respuesta son obligatorias');
                return;
            }

            if (indexStr === '') {
                // Nuevo item
                faqData.push({ question, answer });
            } else {
                // Editar item existente
                const index = parseInt(indexStr);
                faqData[index] = { question, answer };
            }
            closeFaqModal();
        }

        function deleteFaq(index) {
            if (!confirm('¿Eliminar esta pregunta?')) return;
            // REMOVER del array inmediatamente (como Branches)
            faqData.splice(index, 1);
            // Oculta la card visualmente
            const cardElement = document.querySelector(`[data-faq-index="${index}"]`);
            if (cardElement) {
                cardElement.style.opacity = '0';
                cardElement.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    cardElement.style.display = 'none';
                }, 300);
            }
        }

        function addFaq() {
            // Abre el modal en modo "crear nuevo" (sin índice)
            document.getElementById('faq-edit-index').value = '';
            document.getElementById('faq-question').value = '';
            document.getElementById('faq-answer').value = '';
            document.getElementById('faq-modal').style.display = 'flex';
        }

        // ── Guardar FAQ ───────────────────────────────────────────────
        function saveFaq() {
            // Ya no hay items vacíos porque los removemos con splice()
            fetch(`/tenant/{{ $tenant->id }}/update-faq`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ faq: faqData })
            })
            .then(r => r.json())
            .then(d => {
                if (d.success) {
                    window.showToast('✅ FAQ guardado', 'success');
                    location.reload();
                } else {
                    window.showToast('✗ Error', 'error');
                }
            })
            .catch(() => window.showToast('✗ Error de red', 'error'));
        }

        // ── Toggle individual de sección ──────────────────────────────
        function toggleSection(section, visible) {
            fetch(`/tenant/{{ $tenant->id }}/dashboard/toggle-section`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ section, visible })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    window.showToast('✅ ' + (visible ? 'Sección activada' : 'Sección desactivada'), 'success');
                } else {
                    window.showToast('❌ Error al guardar', 'error');
                }
            })
            .catch(() => window.showToast('❌ Error de red', 'error'));
        }
    </script>
    </div>{{-- /lg:ps-64 content wrapper --}}
</div>{{-- /flex min-h-screen --}}
</body>
</html>