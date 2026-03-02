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
        /* ══ SYNTIWEB Dashboard — Premium Design System ══ */
        :root { --synti: #4D8FFF; --synti-glow: rgba(77,143,255,0.14); --synti-bdr: rgba(77,143,255,0.22); --synti-soft: rgba(77,143,255,0.10); }

        /* Sidebar */
        #layout-sidebar { position:fixed; top:0; bottom:0; left:0; width:16rem; z-index:80; transform:translateX(-100%); transition:transform .3s ease; display:flex !important; }
        #layout-sidebar.opened, #layout-sidebar.open { transform:translateX(0); }
        @media(min-width:1024px){ #layout-sidebar { transform:translateX(0); z-index:50; } }

        /* Tabs */
        .tab-content { display:none; } .tab-content.active { display:block; }

        /* Brand fonts */
        .card-title, .table-title, .crud-dialog-title { font-family:'Plus Jakarta Sans',sans-serif !important; letter-spacing:-0.3px; }
        .sidebar-logo-text { font-family:'Plus Jakarta Sans',sans-serif; font-size:1.2rem; font-weight:800; letter-spacing:-0.5px; line-height:1; }
        .sidebar-logo-synti { color:#fff; } .sidebar-logo-web { color:rgba(255,255,255,.7); }
        #live-clock { font-family:'Plus Jakarta Sans',sans-serif; font-size:.8rem; font-weight:700; color:var(--synti); letter-spacing:.5px; }

        /* Sidebar dark elegant */
        #layout-sidebar > .drawer-body { background:linear-gradient(180deg,#0f172a 0%,#1e293b 100%); color:#e2e8f0; }
        #layout-sidebar .sidebar-footer-link { color:#e2e8f0 !important; }
        #layout-sidebar .menu li button {
            color:rgba(226,232,240,.65); border-left:3px solid transparent;
            padding:.65rem 1rem; border-radius:0 .5rem .5rem 0; margin-bottom:2px;
            transition:all .2s ease;
        }
        #layout-sidebar .menu li button:hover { background:rgba(77,143,255,.12); color:#93c5fd; border-left-color:rgba(77,143,255,.5); }
        #layout-sidebar .menu li button.menu-active,
        #layout-sidebar .menu li button[aria-selected="true"] {
            background:linear-gradient(90deg,rgba(77,143,255,.22) 0%,rgba(77,143,255,.06) 100%);
            color:#60a5fa; border-left:3px solid #60a5fa; font-weight:600;
        }

        /* Breathing dots */
        @keyframes synti-breathe { 0%,100%{opacity:1;box-shadow:0 0 0 0 rgba(34,197,94,.5)} 50%{opacity:.7;box-shadow:0 0 0 4px rgba(34,197,94,0)} }
        @keyframes synti-breathe-off { 0%,100%{opacity:1} 50%{opacity:.5} }
        .dot-online { display:inline-block; width:8px; height:8px; border-radius:50%; background:#22c55e; flex-shrink:0; animation:synti-breathe 2.5s ease-in-out infinite; }
        .dot-offline { display:inline-block; width:8px; height:8px; border-radius:50%; background:#ef4444; flex-shrink:0; animation:synti-breathe-off 2s ease-in-out infinite; }

        /* CRUD Modals — elevated */
        .crud-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); backdrop-filter:blur(8px); z-index:9999; align-items:center; justify-content:center; padding:1rem; }
        .crud-overlay.show { display:flex; }
        .crud-dialog { background:var(--color-base-100); border-radius:1rem; width:100%; max-width:600px; max-height:90vh; overflow-y:auto; z-index:10000; box-shadow:0 25px 60px -12px rgba(0,0,0,.35); border:1px solid color-mix(in oklch,var(--color-base-content) 8%,transparent); }
        .crud-dialog-header { padding:1.25rem 1.5rem; background:linear-gradient(135deg,#2563eb 0%,#3b82f6 100%); color:#fff; display:flex; justify-content:space-between; align-items:center; border-radius:1rem 1rem 0 0; }
        .crud-dialog-title { color:#fff !important; font-size:1.1rem; }
        .crud-dialog-close { background:rgba(255,255,255,.15); border:none; color:#fff; cursor:pointer; width:32px; height:32px; display:flex; align-items:center; justify-content:center; border-radius:50%; transition:all .2s; backdrop-filter:blur(4px); }
        .crud-dialog-close:hover { background:rgba(255,255,255,.3); transform:scale(1.05); }
        .crud-dialog-body { padding:1.5rem; }
        @media(max-width:639px) { .crud-dialog { max-width:100%; max-height:100vh; border-radius:0; height:100%; } .crud-dialog-header { border-radius:0; } }

        /* Form focus ring (FlyonUI native inputs) */
        .input:focus, .textarea:focus, .select:focus, .file-input:focus { box-shadow:0 0 0 3px var(--synti-glow); border-color:var(--synti); }

        /* Image previews */
        .image-preview { margin-bottom:1rem; text-align:center; position:relative; }
        .image-preview img { max-width:200px; max-height:200px; border-radius:.75rem; object-fit:cover; border:2px solid var(--synti-bdr); }
        .gallery-thumb { position:relative; display:inline-block; }
        .gallery-thumb img { width:80px; height:80px; border-radius:8px; object-fit:cover; border:2px solid color-mix(in oklch,var(--color-base-content) 15%,transparent); }
        .gallery-thumb-delete { position:absolute; top:-6px; right:-6px; width:20px; height:20px; border-radius:50%; background:var(--color-error); color:#fff; border:none; font-size:12px; cursor:pointer; display:flex; align-items:center; justify-content:center; }
        .gallery-preview-thumb img { width:80px; height:80px; border-radius:8px; object-fit:cover; border:2px dashed var(--synti-bdr); }

        /* Icon picker */
        #icon-picker-grid { scrollbar-width:thin; scrollbar-color:var(--synti-bdr) transparent; }
        .icon-pick-item { transition:all .18s; border:1px solid color-mix(in oklch,var(--color-base-content) 10%,transparent) !important; background:var(--color-base-200) !important; color:var(--color-base-content) !important; border-radius:.5rem; }
        .icon-pick-item:hover { background:var(--synti-soft) !important; border-color:var(--synti-bdr) !important; color:var(--synti) !important; transform:translateY(-2px); }
        .icon-pick-item.selected { background:var(--synti) !important; border-color:var(--synti) !important; color:#fff !important; }

        /* Segmented / mode bar */
        .svc-segment { display:inline-flex; align-items:center; background:var(--color-base-200); border-radius:var(--radius-box); padding:3px; gap:2px; }
        .svc-segment button { display:flex; align-items:center; gap:6px; padding:.45rem 1rem; border-radius:calc(var(--radius-box) - 3px); font-size:.8125rem; font-weight:600; border:none; cursor:pointer; transition:all .2s; color:color-mix(in oklch,var(--color-base-content) 55%,transparent); background:transparent; }
        .svc-segment button.seg-active { background:var(--color-base-100); color:var(--synti); box-shadow:0 1px 6px rgba(0,0,0,.1); }

        /* Card elevation system */
        .card { transition:all .3s ease; }
        .card-elevated { border-radius:1rem !important; }
        .card-elevated:hover { box-shadow:0 10px 25px -5px rgba(0,0,0,.08), 0 4px 6px -2px rgba(0,0,0,.04); transform:translateY(-1px); }

        /* Focus WCAG */
        :focus-visible { outline:2px solid var(--synti) !important; outline-offset:2px !important; }

        /* Scrollable sections */
        .section-scroll { max-height:400px; overflow-y:auto; scrollbar-width:thin; }

        /* QR display — force SVG to fill container exactly */
        #qr-display svg { width:100% !important; height:100% !important; display:block; }

        /* Drag handles */
        .drag-handle { cursor: grab !important; color: rgba(148,163,184,0.55); transition: color .18s; }
        .drag-handle:hover { color: #4D8FFF !important; }
        .drag-handle:active { cursor: grabbing !important; }

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
    <div class="lg:ps-64 sticky top-0 z-50" style="background:linear-gradient(135deg,#1e40af 0%,#2563eb 50%,#3b82f6 100%);">
        <div class="mx-auto w-full">
            <nav class="navbar py-3 px-4 lg:px-6">
                <div class="navbar-start items-center gap-3">
                    {{-- Hamburger: visible solo en móvil --}}
                    <button type="button"
                            class="btn btn-sm btn-square border-0 bg-white/10 text-white hover:bg-white/20 lg:hidden"
                            aria-haspopup="dialog"
                            aria-expanded="false"
                            aria-controls="layout-sidebar"
                            data-overlay="#layout-sidebar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/></svg>
                    </button>
                    <a href="/" class="flex items-center gap-1.5 lg:hidden">
                        <img src="{{ asset('brand/syntiweb-logo-negative.svg') }}" 
                             alt="SYNTIweb" width="28" height="28">
                        <span class="font-bold text-base tracking-tight"><span style="color:#4A80E4">SYNTI</span><span style="color:#FFFFFF">web</span></span>
                    </a>
                    {{-- Negocio + dot de estado (desktop) --}}
                    <div class="hidden lg:flex items-center gap-3 min-w-0">
                        <span class="{{ $tenant->is_open ? 'dot-online' : 'dot-offline' }}"></span>
                        <span class="text-sm font-semibold text-white truncate max-w-52">{{ $tenant->business_name }}</span>
                        <span class="badge badge-sm font-bold border-0
                            {{ $plan->id === 1 ? 'bg-amber-400/90 text-amber-900' : ($plan->id === 2 ? 'bg-emerald-400/90 text-emerald-900' : 'bg-sky-300/90 text-sky-900') }}">
                            {{ $plan->name }}
                        </span>
                        @if($tenant->industry_segment)
                        <span class="badge badge-sm font-medium border-0 bg-white/20 text-white backdrop-blur-sm" title="{{ $tenant->getBlueprintLabel() }}">
                            {{ $tenant->getItemLabel() }}
                        </span>
                        @endif
                    </div>
                </div>

                {{-- Center: reloj + tasa BCV --}}
                <div id="header-extras" class="navbar-center hidden md:flex items-center gap-3">
                    <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border" style="background:rgba(255,255,255,0.18);border-color:rgba(255,255,255,0.25)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#bfdbfe" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <span id="live-clock" class="text-xs font-bold tracking-wide" style="color:#fff" aria-label="Hora actual">--:--</span>
                    </div>
                    <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border" style="background:rgba(255,255,255,0.18);border-color:rgba(255,255,255,0.25)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#6ee7b7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        <span class="text-xs font-semibold" style="color:#fff">
                            Bs. <span id="header-dollar-rate">{{ number_format($dollarRate, 2) }}</span>
                        </span>
                    </div>
                </div>

                {{-- End: estado + cerrar --}}
                <div class="navbar-end items-center gap-2">
                    <span class="{{ $tenant->is_open ? 'dot-online' : 'dot-offline' }} lg:hidden"></span>
                    <a href="/{{ $tenant->subdomain }}"
                       class="btn btn-sm gap-1.5 border-0 hover:opacity-90 transition-opacity"
                       style="background:rgba(255,255,255,0.2);color:#fff;border:1px solid rgba(255,255,255,0.3)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                        <span class="hidden sm:inline" style="color:#fff">Ver sitio</span>
                    </a>
                </div>
            </nav>
        </div>
    </div>

    <!-- ══ SIDEBAR — 4 Menús Lógicos ════════════════════════════════════ -->
    <aside id="layout-sidebar"
           class="overlay [--auto-close:lg]"
           aria-label="Sidebar"
           tabindex="-1">
        <div class="drawer-body h-full border-e border-white/5 p-0">
            <div class="flex h-full max-h-full flex-col">
                {{-- Close (mobile) --}}
                <button type="button"
                        class="btn btn-text btn-circle btn-sm absolute end-3 top-3 lg:hidden text-white/60 hover:text-white hover:bg-white/10"
                        aria-label="Close"
                        data-overlay="#layout-sidebar">
                    <span class="iconify tabler--x size-4.5"></span>
                </button>

                <a href="/" class="flex items-center gap-3">
                    <img src="{{ asset('brand/syntiweb-logo-negative.svg') }}" 
                         alt="SYNTIweb" width="36" height="36">
                    <div>
                        <span class="sidebar-logo-text">
                            <span style="color:#4A80E4">SYNTI</span><span style="color:#FFFFFF">web</span>
                        </span>
                        <p class="text-slate-400 text-xs mt-0.5">{{ $tenant->business_name }}</p>
                    </div>
                </a>

                {{-- Navigation — 4 Menús --}}
                <div class="h-full overflow-y-auto py-2">
                    <p class="px-5 py-2 text-[10px] font-bold uppercase tracking-widest text-slate-500">Menú</p>
                    <ul class="menu menu-sm gap-0.5 px-3" role="tablist">
                        <li role="presentation">
                            <button class="menu-active w-full text-start" role="tab"
                                    aria-selected="true" aria-controls="tab-info"
                                    id="tab-info-btn" data-tab="info" tabindex="0">
                                <span class="iconify tabler--info-circle size-4.5"></span>
                                <span class="grow">Tu Información</span>
                            </button>
                        </li>
                        <li role="presentation">
                            <button class="w-full text-start" role="tab"
                                    aria-selected="false" aria-controls="tab-productos"
                                    id="tab-productos-btn" data-tab="productos" tabindex="-1">
                                <span class="iconify tabler--package size-4.5"></span>
                                <span class="grow">Qué Vendes</span>
                            </button>
                        </li>
                        <li role="presentation">
                            <button class="w-full text-start" role="tab"
                                    aria-selected="false" aria-controls="tab-diseno"
                                    id="tab-diseno-btn" data-tab="diseno" tabindex="-1">
                                <span class="iconify tabler--palette size-4.5"></span>
                                <span class="grow">Cómo Se Ve</span>
                            </button>
                        </li>
                        <li role="presentation">
                            <button class="w-full text-start" role="tab"
                                    aria-selected="false" aria-controls="tab-mensaje"
                                    id="tab-mensaje-btn" data-tab="mensaje" tabindex="-1">
                                <span class="iconify tabler--writing size-4.5"></span>
                                <span class="grow">Tu Mensaje</span>
                            </button>
                        </li>
                        <li role="presentation">
                            <button class="w-full text-start" role="tab"
                                    aria-selected="false" aria-controls="tab-ventas"
                                    id="tab-ventas-btn" data-tab="ventas" tabindex="-1">
                                <span class="iconify tabler--chart-bar size-4.5"></span>
                                <span class="grow">Pulso del Negocio</span>
                            </button>
                        </li>
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
                <div class="border-t border-white/8 p-3 mt-auto">
                    <a href="/{{ $tenant->subdomain }}" target="_blank" rel="noopener noreferrer"
                       class="sidebar-footer-link btn btn-block btn-sm gap-2 border-0 bg-white/8 hover:bg-white/15 transition-all">
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
    <main id="main-content" class="mx-auto w-full max-w-[1200px] flex-1 grow p-5 lg:p-8" tabindex="-1">
        
    {{-- ═══ TAB 1: TU INFORMACIÓN ═══ --}}
    @include('dashboard.components.info-section')

    {{-- ═══ TAB 2: QUÉ VENDES (Productos + Servicios) ═══ --}}
    @include('dashboard.components.products-section')
    @include('dashboard.modals.product-modal')
    @include('dashboard.components.services-section')
    @include('dashboard.modals.service-modal')

    {{-- ═══ Modales compartidos ═══ --}}
    @include('dashboard.modals.shared-modals')

    {{-- ═══ TAB 3: CÓMO SE VE ═══ --}}
    @include('dashboard.components.design-section')

    {{-- ═══ TAB 4: TU MENSAJE ═══ --}}
    @include('dashboard.components.message-section')

    {{-- ═══ TAB 5: PULSO DEL NEGOCIO ═══ --}}
    @include('dashboard.components.sales-section')

    {{-- ═══ TAB 6: CONFIGURACIÓN ═══ --}}
    @include('dashboard.components.config-section')

    </main>

    {{-- ═══ SCRIPTS ═══ --}}
    @include('dashboard.scripts.tab-product-scripts')
    @include('dashboard.scripts.service-scripts')
    @include('dashboard.scripts.design-config-scripts')
    @include('dashboard.scripts.sortable-scripts')

    </div>{{-- /lg:ps-64 content wrapper --}}
</div>{{-- /flex min-h-screen --}}
</body>
</html>
