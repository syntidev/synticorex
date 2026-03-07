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
        /* ══ SYNTIWEB Dashboard — Preline CMS Sidebar ══ */
        :root {
            --synti: #4A80E4;
            --synti-glow: rgba(74,128,228,0.14);
            --synti-bdr: rgba(74,128,228,0.22);
            --synti-soft: rgba(74,128,228,0.10);
        }

        /* Tabs */
        .tab-content { display: none; }
        .tab-content.active { display: block; }

        /* Brand fonts */
        .card-title, .table-title, .crud-dialog-title {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            letter-spacing: -0.3px;
        }
        #live-clock {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: .8rem;
            font-weight: 700;
            color: var(--synti);
            letter-spacing: .5px;
        }

        /* Sidebar tab navigation */
        .sidebar-tab-btn {
            display: flex; align-items: center; gap: .5rem; width: 100%;
            padding: .5rem .625rem; border-radius: .5rem;
            font-size: .8125rem; font-weight: 500;
            color: var(--sidebar-2-nav-foreground, #6b7280);
            background: transparent;
            border: none; cursor: pointer;
            transition: all .15s ease; text-align: start;
        }
        .sidebar-tab-btn:hover {
            background: var(--sidebar-2-nav-hover, rgba(74,128,228,.08));
        }
        .sidebar-tab-btn.menu-active,
        .sidebar-tab-btn[aria-selected="true"] {
            background: var(--sidebar-2-nav-active, rgba(74,128,228,.12));
            color: var(--synti);
            font-weight: 600;
        }

        /* Breathing status dots */
        @keyframes synti-breathe {
            0%, 100% { opacity: 1; box-shadow: 0 0 0 0 rgba(34,197,94,.5); }
            50% { opacity: .7; box-shadow: 0 0 0 4px rgba(34,197,94,0); }
        }
        @keyframes synti-breathe-off {
            0%, 100% { opacity: 1; }
            50% { opacity: .5; }
        }
        .dot-online {
            display: inline-block; width: 8px; height: 8px; border-radius: 50%;
            background: #22c55e; flex-shrink: 0;
            animation: synti-breathe 2.5s ease-in-out infinite;
        }
        .dot-offline {
            display: inline-block; width: 8px; height: 8px; border-radius: 50%;
            background: #ef4444; flex-shrink: 0;
            animation: synti-breathe-off 2s ease-in-out infinite;
        }

        /* CRUD Modals — layout: sticky header + scrollable body */
        .crud-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,.55); backdrop-filter: blur(6px);
            z-index: 9999; align-items: center; justify-content: center;
            padding: 2rem 1rem;
        }
        .crud-overlay.show { display: flex; }
        .crud-dialog {
            background: var(--color-card, #fff);
            border: 1px solid var(--color-card-line, #e5e7eb);
            border-radius: 1rem;
            width: 100%; max-width: 560px;
            max-height: calc(100vh - 4rem);
            display: flex; flex-direction: column;
            z-index: 10000;
            box-shadow: 0 24px 48px -8px rgba(0,0,0,.28), 0 0 0 1px rgba(255,255,255,.06);
            animation: modalIn .18s ease-out;
        }
        @keyframes modalIn {
            from { opacity: 0; transform: scale(.96) translateY(6px); }
            to   { opacity: 1; transform: scale(1)  translateY(0); }
        }
        .crud-dialog-header {
            padding: .875rem 1.25rem;
            background: var(--color-primary, #4A80E4); color: #fff;
            display: flex; justify-content: space-between; align-items: center;
            border-radius: 1rem 1rem 0 0;
            flex-shrink: 0;
        }
        .crud-dialog-title { color: #fff !important; font-size: .9375rem; font-weight: 600; letter-spacing: -.01em; }
        .crud-dialog-close {
            background: rgba(255,255,255,.18); border: none; color: #fff; cursor: pointer;
            width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;
            border-radius: 50%; transition: background .15s, transform .15s; font-size: 1.1rem; line-height: 1;
            flex-shrink: 0;
        }
        .crud-dialog-close:hover { background: rgba(255,255,255,.32); transform: scale(1.08); }
        .crud-dialog-body { padding: 1.25rem 1.25rem 1.5rem; overflow-y: auto; flex: 1; }
        .crud-dialog-body::-webkit-scrollbar { width: 4px; }
        .crud-dialog-body::-webkit-scrollbar-track { background: transparent; }
        .crud-dialog-body::-webkit-scrollbar-thumb { background: var(--color-border, #d1d5db); border-radius: 4px; }
        /* Compact form controls inside modals */
        .crud-dialog-body .form-control,
        .crud-dialog-body > form > div { margin-bottom: 0; }
        /* Mobile: bottom-sheet */
        @media (max-width: 639px) {
            .crud-overlay { padding: 0; align-items: flex-end; }
            .crud-dialog {
                max-width: 100%; max-height: 92dvh; border-radius: 1.25rem 1.25rem 0 0;
                animation: sheetIn .22s ease-out;
            }
            .crud-dialog-header { border-radius: 1.25rem 1.25rem 0 0; }
            @keyframes sheetIn {
                from { transform: translateY(24px); opacity: 0; }
                to   { transform: translateY(0);    opacity: 1; }
            }
        }

        /* Image previews */
        .image-preview { margin-bottom: 1rem; text-align: center; position: relative; }
        .image-preview img {
            max-width: 200px; max-height: 200px; border-radius: .75rem;
            object-fit: cover; border: 2px solid var(--synti-bdr);
        }
        .gallery-thumb { position: relative; display: inline-block; }
        .gallery-thumb img {
            width: 80px; height: 80px; border-radius: 8px;
            object-fit: cover; border: 2px solid #d1d5db;
        }
        .gallery-thumb-delete {
            position: absolute; top: -6px; right: -6px;
            width: 20px; height: 20px; border-radius: 50%;
            background: #ef4444; color: #fff; border: none;
            font-size: 12px; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
        }
        .gallery-preview-thumb img {
            width: 80px; height: 80px; border-radius: 8px;
            object-fit: cover; border: 2px dashed var(--synti-bdr);
        }

        /* Icon picker */
        #icon-picker-grid { scrollbar-width: thin; scrollbar-color: var(--synti-bdr) transparent; }
        .icon-pick-item {
            transition: all .18s;
            border: 1px solid #d1d5db !important;
            background: #f3f4f6 !important;
            color: #1f2937 !important;
            border-radius: .5rem;
        }
        .icon-pick-item:hover {
            background: var(--synti-soft) !important;
            border-color: var(--synti-bdr) !important;
            color: var(--synti) !important;
            transform: translateY(-2px);
        }
        .icon-pick-item.selected {
            background: var(--synti) !important;
            border-color: var(--synti) !important;
            color: #fff !important;
        }

        /* Segmented / mode bar */
        .svc-segment {
            display: inline-flex; align-items: center;
            background: #f3f4f6; border-radius: .5rem; padding: 3px; gap: 2px;
        }
        .svc-segment button {
            display: flex; align-items: center; gap: 6px;
            padding: .45rem 1rem; border-radius: .375rem;
            font-size: .8125rem; font-weight: 600; border: none; cursor: pointer;
            transition: all .2s; color: #6b7280; background: transparent;
        }
        .svc-segment button.seg-active {
            background: #fff; color: var(--synti);
            box-shadow: 0 1px 6px rgba(0,0,0,.1);
        }

        /* Card elevation */
        .card { transition: all .3s ease; }
        .card-elevated { border-radius: 1rem !important; }
        .card-elevated:hover {
            box-shadow: 0 10px 25px -5px rgba(0,0,0,.08), 0 4px 6px -2px rgba(0,0,0,.04);
            transform: translateY(-1px);
        }

        /* Focus WCAG */
        :focus-visible { outline: 2px solid var(--synti) !important; outline-offset: 2px !important; }

        /* Scrollable sections */
        .section-scroll { max-height: 400px; overflow-y: auto; scrollbar-width: thin; }

        /* QR display */
        #qr-display svg { width: 100% !important; height: 100% !important; display: block; }

        /* Drag handles */
        .drag-handle { cursor: grab !important; color: rgba(148,163,184,0.55); transition: color .18s; }
        .drag-handle:hover { color: #4A80E4 !important; }
        .drag-handle:active { cursor: grabbing !important; }

        /* Responsive */
        @media (max-width: 639px) { #header-extras { display: none !important; } }
    </style>
</head>

<body class="hs-overlay-body-open overflow-hidden bg-gray-100 dark:bg-neutral-900">

{{-- Skip link accesibilidad --}}
<a href="#main-content"
   class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:start-2 focus:z-[9999] focus:inline-flex focus:py-2 focus:px-4 focus:rounded-lg focus:font-medium focus:bg-blue-600 focus:text-white focus:text-sm">
    Saltar al contenido principal
</a>

{{-- Región aria-live para anunciar toasts --}}
<div id="toast-announcer" aria-live="polite" aria-atomic="true" class="sr-only"></div>

<!-- ══════════════════════════════════════════════════════════════════
     HEADER — Fixed top navbar (Preline CMS pattern)
     ══════════════════════════════════════════════════════════════════ -->
<header class="fixed top-0 inset-x-0 flex flex-wrap md:justify-start md:flex-nowrap z-48 lg:z-61 w-full bg-white dark:bg-neutral-800 border-b border-gray-200 dark:border-neutral-700 text-sm py-2.5">
    <nav class="px-4 sm:px-5.5 flex basis-full items-center w-full mx-auto">
        <div class="w-full flex items-center gap-x-1.5">

            {{-- LEFT: Sidebar toggle + Logo --}}
            <ul class="flex items-center gap-1.5">
                <li class="inline-flex items-center gap-1 relative pe-1.5 last:pe-0 last:after:hidden after:absolute after:top-1/2 after:end-0 after:inline-block after:w-px after:h-3.5 after:bg-gray-300 dark:after:bg-neutral-600 after:rounded-full after:-translate-y-1/2 after:rotate-12">
                    {{-- Logo --}}
                    <a href="/" class="shrink-0 inline-flex justify-center items-center size-8 rounded-md focus:outline-hidden focus:opacity-80" aria-label="SYNTIweb">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="28" height="28" aria-hidden="true">
                            <path d="M 30,22 L 78,22 L 78,70 Q 78,78 70,78 L 62,78 L 62,38 L 22,38 L 22,30 Q 22,22 30,22 Z" fill="#1a1a1a"/>
                            <circle cx="38" cy="63" r="14" fill="#4A80E4"/>
                        </svg>
                    </a>

                    {{-- Sidebar Toggle --}}
                    <button type="button"
                            class="p-1.5 size-7.5 inline-flex items-center gap-x-1 text-xs rounded-md border border-transparent text-gray-600 dark:text-neutral-400 hover:bg-gray-100 dark:hover:bg-neutral-700 focus:outline-hidden focus:bg-gray-100 dark:focus:bg-neutral-700"
                            aria-haspopup="dialog" aria-expanded="false"
                            aria-controls="hs-application-sidebar"
                            data-hs-overlay="#hs-application-sidebar">
                        <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="18" x="3" y="3" rx="2"/>
                            <path d="M15 3v18"/>
                            <path d="m10 15-3-3 3-3"/>
                        </svg>
                        <span class="sr-only">Toggle sidebar</span>
                    </button>
                </li>

                <li class="inline-flex items-center relative pe-1.5">
                    {{-- Business name + status + plan --}}
                    <div class="hidden sm:flex items-center gap-2 py-1 px-2 min-h-8">
                        <span class="{{ $tenant->is_open ? 'dot-online' : 'dot-offline' }}"
                              aria-label="{{ $tenant->is_open ? 'Abierto' : 'Cerrado' }}"></span>
                        <span class="text-sm font-semibold text-gray-800 dark:text-neutral-200 truncate max-w-48">
                            {{ $tenant->business_name }}
                        </span>
                        <span class="inline-flex items-center py-0.5 px-2 rounded-full text-[11px] font-bold
                            {{ $plan->id === 1 ? 'bg-amber-100 text-amber-700' : ($plan->id === 2 ? 'bg-emerald-100 text-emerald-700' : 'bg-sky-100 text-sky-700') }}">
                            {{ $plan->name }}
                        </span>
                    </div>
                </li>
            </ul>

            {{-- RIGHT: Clock + Dollar + View Site --}}
            <ul class="flex flex-row items-center gap-x-3 ms-auto">
                <li id="header-extras" class="hidden lg:inline-flex items-center gap-1.5 relative pe-3 last:pe-0 last:after:hidden after:absolute after:top-1/2 after:end-0 after:inline-block after:w-px after:h-3.5 after:bg-gray-300 dark:after:bg-neutral-600 after:rounded-full after:-translate-y-1/2 after:rotate-12">
                    <div class="flex items-center gap-1.5 py-1 px-2.5 rounded-lg bg-gray-100 dark:bg-neutral-700 border border-gray-200 dark:border-neutral-600 text-xs">
                        <span class="iconify tabler--clock size-3.5 text-gray-400 dark:text-neutral-500"></span>
                        <span id="live-clock" class="font-bold text-gray-800 dark:text-neutral-200 tabular-nums" aria-label="Hora actual">--:--</span>
                    </div>
                    <div class="flex items-center gap-1.5 py-1 px-2.5 rounded-lg bg-gray-100 dark:bg-neutral-700 border border-gray-200 dark:border-neutral-600 text-xs">
                        <span class="iconify tabler--currency-dollar size-3.5 text-emerald-500"></span>
                        <span class="font-semibold text-gray-800 dark:text-neutral-200">
                            Bs. <span id="header-dollar-rate">{{ number_format($dollarRate, 2) }}</span>
                        </span>
                    </div>
                </li>

                <li class="inline-flex items-center">
                    <a href="/{{ $tenant->subdomain }}"
                       target="_blank" rel="noopener"
                       class="py-1.5 px-3 inline-flex items-center gap-x-1.5 text-sm font-medium rounded-lg bg-[#4A80E4] text-white hover:bg-[#3D6EC8] transition-colors"
                       title="Ver tu landing pública">
                        <span class="iconify tabler--external-link size-4"></span>
                        <span class="hidden sm:inline">Ver sitio</span>
                    </a>
                </li>
            </ul>

        </div>
    </nav>
</header>
<!-- ══ END HEADER ══ -->


<!-- ══════════════════════════════════════════════════════════════════
     MAIN — Preline CMS layout-open offset
     ══════════════════════════════════════════════════════════════════ -->
<main class="lg:hs-overlay-layout-open:ps-60 transition-all duration-300 lg:fixed lg:inset-0 pt-13.5 px-3 pb-3">

    <!-- ══ SIDEBAR — Preline hs-overlay (CMS pattern) ══════════════ -->
    <div id="hs-application-sidebar"
         class="hs-overlay
                [--body-scroll:true]
                lg:[--overlay-backdrop:false]
                [--is-layout-affect:true]
                [--opened:lg]
                [--auto-close:lg]
                hs-overlay-open:translate-x-0
                lg:hs-overlay-layout-open:translate-x-0
                -translate-x-full
                transition-all duration-300 transform
                w-60
                hidden
                fixed inset-y-0 z-60 start-0
                bg-white dark:bg-neutral-800
                border-e border-gray-200 dark:border-neutral-700
                lg:block lg:-translate-x-full lg:end-auto lg:bottom-0"
         role="dialog" tabindex="-1" aria-label="Sidebar">

        <div class="lg:pt-13 relative flex flex-col h-full max-h-full">

            {{-- Sidebar body --}}
            <nav class="p-3 size-full flex flex-col overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-none [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-600">

                {{-- Mobile: business info + close --}}
                <div class="lg:hidden mb-2 flex items-center justify-between">
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="{{ $tenant->is_open ? 'dot-online' : 'dot-offline' }}" aria-hidden="true"></span>
                        <span class="text-sm font-semibold text-gray-800 dark:text-neutral-200 truncate">{{ $tenant->business_name }}</span>
                    </div>
                    {{-- Close sidebar on mobile --}}
                    <button type="button"
                            class="p-1.5 size-7.5 inline-flex items-center gap-x-1 text-xs rounded-md text-gray-500 dark:text-neutral-400 focus:outline-hidden"
                            aria-controls="hs-application-sidebar"
                            data-hs-overlay="#hs-application-sidebar">
                        <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                        </svg>
                        <span class="sr-only">Cerrar sidebar</span>
                    </button>
                </div>

                {{-- Tab navigation section --}}
                <div class="flex flex-col" role="tablist" aria-label="Dashboard navigation">
                    <span class="block ps-2.5 mb-2 font-medium text-xs uppercase text-gray-500 dark:text-neutral-500">
                        Panel
                    </span>
                    <ul class="flex flex-col gap-y-0.5">
                        <li>
                            <button class="sidebar-tab-btn menu-active" role="tab"
                                    aria-selected="true" aria-controls="tab-info"
                                    data-tab="info" tabindex="0">
                                <span class="iconify tabler--info-circle size-4 shrink-0"></span>
                                Tu Información
                            </button>
                        </li>
                        <li>
                            <button class="sidebar-tab-btn" role="tab"
                                    aria-selected="false" aria-controls="tab-productos"
                                    data-tab="productos" tabindex="-1">
                                <span class="iconify tabler--package size-4 shrink-0"></span>
                                Qué Vendes
                            </button>
                        </li>
                        <li>
                            <button class="sidebar-tab-btn" role="tab"
                                    aria-selected="false" aria-controls="tab-diseno"
                                    data-tab="diseno" tabindex="-1">
                                <span class="iconify tabler--palette size-4 shrink-0"></span>
                                Cómo Se Ve
                            </button>
                        </li>
                        <li>
                            <button class="sidebar-tab-btn" role="tab"
                                    aria-selected="false" aria-controls="tab-mensaje"
                                    data-tab="mensaje" tabindex="-1">
                                <span class="iconify tabler--writing size-4 shrink-0"></span>
                                Tu Mensaje
                            </button>
                        </li>
                        <li>
                            <button class="sidebar-tab-btn" role="tab"
                                    aria-selected="false" aria-controls="tab-ventas"
                                    data-tab="ventas" tabindex="-1">
                                <span class="iconify tabler--chart-bar size-4 shrink-0"></span>
                                Pulso del Negocio
                            </button>
                        </li>
                        <li>
                            <button class="sidebar-tab-btn" role="tab"
                                    aria-selected="false" aria-controls="tab-visual"
                                    data-tab="visual" tabindex="-1">
                                <span class="iconify tabler--photo size-4 shrink-0"></span>
                                Visual
                            </button>
                        </li>
                        <li>
                            <button class="sidebar-tab-btn" role="tab"
                                    aria-selected="false" aria-controls="tab-config"
                                    data-tab="config" tabindex="-1">
                                <span class="iconify tabler--settings size-4 shrink-0"></span>
                                Configuración
                            </button>
                        </li>
                    </ul>
                </div>

            </nav>

            {{-- Sidebar footer --}}
            <footer class="mt-auto p-3 flex flex-col border-t border-gray-200 dark:border-neutral-700">
                <ul class="flex flex-col gap-y-0.5">
                    <li>
                        <a href="/{{ $tenant->subdomain }}" target="_blank" rel="noopener"
                           class="w-full flex items-center gap-x-2 py-2 px-2.5 text-sm text-gray-600 dark:text-neutral-400 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-700 focus:outline-hidden focus:bg-gray-100 dark:focus:bg-neutral-700">
                            <span class="iconify tabler--external-link size-4 shrink-0"></span>
                            Ver mi landing
                        </a>
                    </li>
                    <li>
                        <a href="mailto:soporte@synticorex.com"
                           class="w-full flex items-center gap-x-2 py-2 px-2.5 text-sm text-gray-600 dark:text-neutral-400 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-700 focus:outline-hidden focus:bg-gray-100 dark:focus:bg-neutral-700">
                            <span class="iconify tabler--help size-4 shrink-0"></span>
                            Soporte
                        </a>
                    </li>
                </ul>
                {{-- Branding --}}
                <div class="mt-3 pt-3 border-t border-gray-200 dark:border-neutral-700 px-2.5">
                    <span class="text-xs font-bold tracking-tight">
                        <span class="text-gray-800 dark:text-neutral-200">SYNTI</span><span style="color:#4A80E4">web</span>
                    </span>
                    <p class="text-[10px] text-gray-400 dark:text-neutral-500 mt-0.5">&copy; {{ date('Y') }} Synticorex</p>
                </div>
            </footer>

        </div>
    </div>
    <!-- ══ END SIDEBAR ══ -->


    <!-- ══ CONTENT AREA — Preline CMS rounded card ══════════════════ -->
    <div class="h-[calc(100dvh-62px)] lg:h-full overflow-hidden flex flex-col bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 shadow-xs rounded-lg">

        {{-- Content header — Plan expiry notices --}}
        @if($isFrozen)
        <div class="flex p-4 gap-3 bg-red-50 dark:bg-red-900/20 border-b border-red-200 dark:border-red-800 text-red-800 dark:text-red-300 items-center flex-wrap">
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
            <a href="mailto:soporte@synticorex.com"
               class="text-sm py-1.5 px-3 rounded-lg font-medium transition-colors bg-red-600 text-white hover:bg-red-700 shrink-0">
                Renovar plan
            </a>
        </div>
        @elseif($isExpiringSoon && $daysUntilExpiry !== null)
        <div class="flex p-4 gap-3 bg-yellow-50 dark:bg-yellow-900/20 border-b border-yellow-200 dark:border-yellow-800 text-yellow-800 dark:text-yellow-300 items-center flex-wrap">
            <span class="iconify tabler--alert-triangle size-6 shrink-0" aria-hidden="true"></span>
            <div class="flex-1 min-w-0">
                <p class="font-bold text-sm">Tu plan vence en {{ $daysUntilExpiry }} día{{ $daysUntilExpiry === 1 ? '' : 's' }}</p>
                <p class="text-sm opacity-80">
                    Renueva antes del <strong>{{ $tenant->subscription_ends_at->format('d/m/Y') }}</strong> para mantener tu landing pública activa sin interrupciones.
                </p>
            </div>
            <a href="mailto:soporte@synticorex.com"
               class="text-sm py-1.5 px-3 rounded-lg font-medium transition-colors bg-yellow-500 text-white hover:bg-yellow-600 shrink-0">
                Renovar ahora
            </a>
        </div>
        @endif

        {{-- Scrollable content body --}}
        <div class="flex-1 overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-none [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-600">
            <div id="main-content" class="mx-auto w-full max-w-[1200px] p-5 lg:p-8" tabindex="-1">

                {{-- ═══ TAB 1: TU INFORMACIÓN ═══ --}}
                @include('dashboard.components.info-section')

                {{-- ═══ TAB 2: QUÉ VENDES (Productos + Servicios) ═══ --}}
                @if($blueprint === 'food')
                    @include('dashboard.components.menu-section')
                @else
                    @include('dashboard.components.products-section')
                    @include('dashboard.modals.product-modal')
                    @include('dashboard.components.services-section')
                    @include('dashboard.modals.service-modal')
                @endif

                {{-- ═══ Modales compartidos ═══ --}}
                @include('dashboard.modals.shared-modals')

                {{-- ═══ TAB 3: CÓMO SE VE ═══ --}}
                @include('dashboard.components.design-section')

                {{-- ═══ TAB 4: TU MENSAJE ═══ --}}
                @include('dashboard.components.message-section')

                {{-- ═══ TAB 5: PULSO DEL NEGOCIO ═══ --}}
                @include('dashboard.components.sales-section')

                {{-- ═══ TAB: VISUAL ═══ --}}
                @include('dashboard.components.visual-section')

                {{-- ═══ TAB 6: CONFIGURACIÓN ═══ --}}
                @include('dashboard.components.config-section')

            </div>
        </div>

    </div>
    <!-- ══ END CONTENT AREA ══ -->

</main>

{{-- ═══ SCRIPTS ═══ --}}
@include('dashboard.scripts.tab-product-scripts')
@include('dashboard.scripts.service-scripts')
@include('dashboard.scripts.design-config-scripts')
@include('dashboard.scripts.sortable-scripts')

</body>
</html>
