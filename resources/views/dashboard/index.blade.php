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
                        <span class="iconify tabler--clock size-3.5 text-base-content/50"></span>
                        <span id="live-clock" aria-label="Hora actual">--:--</span>
                    </div>
                    <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-box bg-base-200/70 border border-base-content/8">
                        <span class="iconify tabler--currency-dollar size-3.5 text-green-500"></span>
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
                        <span class="iconify tabler--layout-sidebar-right-collapse size-4"></span>
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
                    <span class="iconify tabler--x size-4.5"></span>
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
                                <span class="iconify tabler--info-circle size-4.5"></span>
                                <span class="grow">Información</span>
                            </button>
                        </li>
                        <li role="presentation">
                            <button class="w-full text-start" role="tab"
                                    aria-selected="false" aria-controls="tab-productos"
                                    id="tab-productos-btn" data-tab="productos" tabindex="-1">
                                <span class="iconify tabler--package size-4.5"></span>
                                <span class="grow">Productos</span>
                            </button>
                        </li>
                        <li role="presentation">
                            <button class="w-full text-start" role="tab"
                                    aria-selected="false" aria-controls="tab-servicios"
                                    id="tab-servicios-btn" data-tab="servicios" tabindex="-1">
                                <span class="iconify tabler--tool size-4.5"></span>
                                <span class="grow">Servicios</span>
                            </button>
                        </li>
                        <li role="presentation">
                            <button class="w-full text-start" role="tab"
                                    aria-selected="false" aria-controls="tab-diseno"
                                    id="tab-diseno-btn" data-tab="diseno" tabindex="-1">
                                <span class="iconify tabler--palette size-4.5"></span>
                                <span class="grow">Diseño</span>
                            </button>
                        </li>
                        <li role="presentation">
                            <button class="w-full text-start" role="tab"
                                    aria-selected="false" aria-controls="tab-analytics"
                                    id="tab-analytics-btn" data-tab="analytics" tabindex="-1">
                                <span class="iconify tabler--chart-bar size-4.5"></span>
                                <span class="grow">Analytics</span>
                            </button>
                        </li>
                        @if($plan->id === 3)
                        <li role="presentation">
                            <button class="w-full text-start" role="tab"
                                    aria-selected="false" aria-controls="tab-sucursales"
                                    id="tab-sucursales-btn" data-tab="sucursales" tabindex="-1">
                                <span class="iconify tabler--map-pin size-4.5"></span>
                                <span class="grow">Sucursales</span>
                            </button>
                        </li>
                        @endif
                        <li role="presentation">
                            <button class="w-full text-start" role="tab"
                                    aria-selected="false" aria-controls="tab-config"
                                    id="tab-config-btn" data-tab="config" tabindex="-1">
                                <span class="iconify tabler--settings size-4.5"></span>
                                <span class="grow">Configuración</span>
                            </button>
                        </li>
                    </ul>
                </div>

                {{-- Footer --}}
                <div class="border-base-content/20 border-t p-3 mt-auto">
                    <a href="/{{ $tenant->subdomain }}" target="_blank" rel="noopener noreferrer"
                       class="btn btn-soft btn-block btn-sm gap-2">
                        <span class="iconify tabler--external-link size-4"></span>
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
        <span class="iconify tabler--circle-x size-6 shrink-0" aria-hidden="true"></span>
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
        <span class="iconify tabler--alert-triangle size-6 shrink-0" aria-hidden="true"></span>
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
        
    @include('dashboard.components.info-section')

    @include('dashboard.components.products-section')
    @include('dashboard.modals.product-modal')

    @include('dashboard.components.services-section')
    @include('dashboard.modals.service-modal')

    @include('dashboard.modals.shared-modals')

    @include('dashboard.components.design-section')

    @include('dashboard.components.analytics-section')

    @include('dashboard.components.branches-section')

    @include('dashboard.components.config-section')

    </main>


    @include('dashboard.scripts.tab-product-scripts')
    @include('dashboard.scripts.service-scripts')
    @include('dashboard.scripts.design-config-scripts')

    @include('dashboard.scripts.sortable-scripts')

    </div>{{-- /lg:ps-64 content wrapper --}}
</div>{{-- /flex min-h-screen --}}
</body>
</html>