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

        /* Sidebar — posicionamiento vía clases Preline nativas */

        /* Tabs */
        .tab-content { display:none; } .tab-content.active { display:block; }

        /* Brand fonts */
        .card-title, .table-title, .crud-dialog-title { font-family:'Plus Jakarta Sans',sans-serif !important; letter-spacing:-0.3px; }
        .sidebar-logo-text { font-family:'Plus Jakarta Sans',sans-serif; font-size:1.2rem; font-weight:800; letter-spacing:-0.5px; line-height:1; }
        .sidebar-logo-synti { color:#fff; } .sidebar-logo-web { color:rgba(255,255,255,.7); }
        #live-clock { font-family:'Plus Jakarta Sans',sans-serif; font-size:.8rem; font-weight:700; color:var(--synti); letter-spacing:.5px; }

        /* Sidebar dark elegant */
        #layout-sidebar { background:linear-gradient(180deg,#0f172a 0%,#1e293b 100%); color:#e2e8f0; }
        #layout-sidebar .sidebar-footer-link { color:#e2e8f0 !important; }
        #layout-sidebar .sidebar-nav li button {
            color:rgba(226,232,240,.65); border-left:3px solid transparent;
            padding:.65rem 1rem; border-radius:0 .5rem .5rem 0; margin-bottom:2px;
            transition:all .2s ease;
        }
        #layout-sidebar .sidebar-nav li button:hover { background:rgba(77,143,255,.12); color:#93c5fd; border-left-color:rgba(77,143,255,.5); }
        #layout-sidebar .sidebar-nav li button.menu-active,
        #layout-sidebar .sidebar-nav li button[aria-selected="true"] {
            background:linear-gradient(90deg,rgba(77,143,255,.22) 0%,rgba(77,143,255,.06) 100%);
            color:#60a5fa; border-left:3px solid #60a5fa; font-weight:600;
        }

        /* Breathing dots */
        @keyframes synti-breathe { 0%,100%{opacity:1;box-shadow:0 0 0 0 rgba(34,197,94,.5)} 50%{opacity:.7;box-shadow:0 0 0 4px rgba(34,197,94,0)} }
        @keyframes synti-breathe-off { 0%,100%{opacity:1} 50%{opacity:.5} }
        .dot-online { display:inline-block; width:8px; height:8px; border-radius:50%; background:#22c55e; flex-shrink:0; animation:synti-breathe 2.5s ease-in-out infinite; }
        .dot-offline { display:inline-block; width:8px; height:8px; border-radius:50%; background:#ef4444; flex-shrink:0; animation:synti-breathe-off 2s ease-in-out infinite; }

        /* CRUD Modals — Preline 4.1.2 tokens */
        .crud-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); backdrop-filter:blur(4px); z-index:9999; align-items:center; justify-content:center; padding:1rem; }
        .crud-overlay.show { display:flex; }
        .crud-dialog { background:var(--color-card); border-radius:0.75rem; width:100%; max-width:600px; max-height:90vh; overflow-y:auto; z-index:10000; box-shadow:0 20px 25px -5px rgba(0,0,0,.1), 0 10px 10px -5px rgba(0,0,0,.04); border:1px solid var(--color-card-line); }
        .crud-dialog-header { padding:1.25rem 1.5rem; background:var(--color-primary); color:#fff; display:flex; justify-content:space-between; align-items:center; border-radius:0.75rem 0.75rem 0 0; }
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
<body class="bg-layer min-h-screen">

{{-- Skip link accesibilidad: visible solo al recibir foco por teclado --}}
<a href="#main-content"
   class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:start-2 focus:z-[9999] focus:inline-flex focus:py-2 focus:px-4 focus:rounded-lg focus:font-medium focus:bg-blue-600 focus:text-white focus:text-sm">
    Saltar al contenido principal
</a>

{{-- Región aria-live para anunciar toasts a lectores de pantalla --}}
<div id="toast-announcer" aria-live="polite" aria-atomic="true" class="sr-only"></div>

    <!-- ══ HEADER ══ -->
    <div class="lg:ps-64 sticky top-0 z-50 bg-surface border-b border-border">
        <div class="px-4 lg:px-6">
            <nav class="flex items-center justify-between h-14 gap-4">

                {{-- START: hamburger móvil + nombre negocio --}}
                <div class="flex items-center gap-3 min-w-0">

                    {{-- Hamburger — solo móvil --}}
                    <button type="button"
                        class="size-8 flex items-center justify-center rounded-lg text-muted-foreground-1 hover:bg-muted hover:text-foreground transition-colors lg:hidden"
                        aria-haspopup="dialog"
                        aria-expanded="false"
                        aria-controls="layout-sidebar"
                        data-hs-overlay="#layout-sidebar">
                        <iconify-icon icon="tabler:menu-2" width="20"></iconify-icon>
                    </button>

                    {{-- Logo — solo móvil --}}
                    <a href="/" class="flex items-center gap-2 lg:hidden shrink-0">
                        <img src="{{ asset('brand/syntiweb-logo-positive.svg') }}"
                             alt="SYNTIweb" width="26" height="26">
                        <span class="font-bold text-base tracking-tight">
                            <span class="text-foreground">SYNTI</span><span style="color:#4A80E4">web</span>
                        </span>
                    </a>

                    {{-- Negocio + estado + plan — desktop --}}
                    <div class="hidden lg:flex items-center gap-2.5 min-w-0">
                        <span class="{{ $tenant->is_open ? 'dot-online' : 'dot-offline' }}"
                              aria-label="{{ $tenant->is_open ? 'Abierto' : 'Cerrado' }}"></span>
                        <span class="text-sm font-semibold text-foreground truncate max-w-48">
                            {{ $tenant->business_name }}
                        </span>
                        <span class="inline-flex items-center py-0.5 px-2.5 rounded-full text-xs font-bold
                            {{ $plan->id === 1 ? 'bg-amber-100 text-amber-700' : ($plan->id === 2 ? 'bg-emerald-100 text-emerald-700' : 'bg-sky-100 text-sky-700') }}">
                            {{ $plan->name }}
                        </span>
                    </div>
                </div>

                {{-- CENTER: reloj + tasa BCV --}}
                <div id="header-extras" class="hidden md:flex items-center gap-2">
                    <div class="flex items-center gap-1.5 py-1 px-2.5 rounded-lg bg-muted border border-border text-xs">
                        <iconify-icon icon="tabler:clock" width="14" class="text-muted-foreground-1"></iconify-icon>
                        <span id="live-clock" class="font-bold text-foreground tabular-nums" aria-label="Hora actual">--:--</span>
                    </div>
                    <div class="flex items-center gap-1.5 py-1 px-2.5 rounded-lg bg-muted border border-border text-xs">
                        <iconify-icon icon="tabler:currency-dollar" width="14" class="text-success"></iconify-icon>
                        <span class="font-semibold text-foreground">
                            Bs. <span id="header-dollar-rate">{{ number_format($dollarRate, 2) }}</span>
                        </span>
                    </div>
                </div>

                {{-- END: ver sitio --}}
                <div class="flex items-center shrink-0">
                    <a href="/{{ $tenant->subdomain }}"
                       target="_blank"
                       rel="noopener"
                       class="py-1.5 px-3 inline-flex items-center gap-x-1.5 text-sm font-medium rounded-lg bg-primary border border-primary-line text-primary-foreground hover:bg-primary-hover transition-colors">
                        <iconify-icon icon="tabler:external-link" width="15"></iconify-icon>
                        <span class="hidden sm:inline">Ver sitio</span>
                    </a>
                </div>

            </nav>
        </div>
    </div>

    <!-- ══ SIDEBAR — 4 Menús Lógicos ════════════════════════════════════ -->
    <div id="layout-sidebar"
         class="hs-overlay [--auto-close:lg] hs-overlay-open:translate-x-0 -translate-x-full transition-all duration-300 transform
                w-64 hidden fixed inset-y-0 start-0 z-[60] overflow-y-auto
                border-e border-white/5
                lg:block lg:translate-x-0 lg:z-50"
         tabindex="-1" aria-label="Sidebar">
        <div class="flex h-full max-h-full flex-col">
                {{-- Close (mobile) --}}
                <button type="button"
                        class="p-2 rounded-full transition-colors absolute end-3 top-3 lg:hidden text-white/60 hover:text-white hover:bg-white/10"
                        aria-label="Close"
                        data-hs-overlay="#layout-sidebar">
                    <span class="iconify tabler--x size-4.5"></span>
                </button>

                <a href="/" class="flex items-center gap-2 px-5 pt-5 pb-3">
                    <img src="{{ asset('brand/syntiweb-logo-negative.svg') }}" alt="SYNTIweb" width="32" height="32">
                    <span class="font-bold text-lg tracking-tight">
                        <span style="color:#4A80E4">SYNTI</span><span style="color:#FFFFFF">web</span>
                    </span>
                </a>

                {{-- Navigation — 4 Menús --}}
                <div class="h-full overflow-y-auto py-2">
                    <p class="px-5 py-2 text-[10px] font-bold uppercase tracking-widest text-slate-500">Menú</p>
                    <ul class="sidebar-nav flex flex-col gap-0.5 px-3" role="tablist">
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
                <div class="border-t border-[var(--sw-border)] p-3 mt-auto text-[var(--sw-text-muted)]">
                    <p class="text-xs text-center py-2 px-3">© {{ date('Y') }} SYNTIweb</p>
                </div>
            </div>
        </div>
    </div>{{-- /layout-sidebar --}}

    <!-- ══ LAYOUT CONTENT ════════════════════════════════════ -->
    <div class="w-full lg:ps-64">

    {{-- ── Plan Expiry Notices ──────────────────────────────────────────── --}}
    @if($isFrozen)
    {{-- FROZEN: subscription expired --}}
    <div class="flex p-4 rounded-lg border gap-3 bg-red-50 border-red-200 text-red-800 rounded-none border-x-0 border-t-0 items-center flex-wrap px-6">
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
            <a href="mailto:soporte@synticorex.com" class="text-sm py-1.5 px-3 rounded-lg font-medium transition-colors bg-red-600 text-white hover:bg-red-700 border-error-content/30 text-error-content shrink-0">
            Renovar plan
        </a>
    </div>
    @elseif($isExpiringSoon && $daysUntilExpiry !== null)
    {{-- EXPIRING SOON: 30 days or fewer remaining --}}
    <div class="flex p-4 rounded-lg border gap-3 bg-yellow-50 border-yellow-200 text-yellow-800 rounded-none border-x-0 border-t-0 items-center flex-wrap px-6">
        <span class="iconify tabler--alert-triangle size-6 shrink-0" aria-hidden="true"></span>
        <div class="flex-1 min-w-0">
            <p class="font-bold text-sm">Tu plan vence en {{ $daysUntilExpiry }} día{{ $daysUntilExpiry === 1 ? '' : 's' }}</p>
            <p class="text-sm opacity-80">
                Renueva antes del <strong>{{ $tenant->subscription_ends_at->format('d/m/Y') }}</strong> para mantener tu landing pública activa sin interrupciones.
            </p>
        </div>
            <a href="mailto:soporte@synticorex.com" class="text-sm py-1.5 px-3 rounded-lg font-medium transition-colors bg-yellow-500 text-white hover:bg-yellow-600 border-warning-content/30 text-warning-content shrink-0">
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

    </div>{{-- /w-full lg:ps-64 content wrapper --}}
</body>
</html>
