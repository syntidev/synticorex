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
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* ══════════════════════════════════════════════════════════
           SYNTIWEB DESIGN SYSTEM — Dashboard
           Primario: #4D8FFF  |  Font display: Plus Jakarta Sans
        ══════════════════════════════════════════════════════════ */
        :root {
            --synti: #4D8FFF;
            --synti-glow: rgba(77,143,255,0.14);
            --synti-bdr:  rgba(77,143,255,0.22);
            --synti-soft: rgba(77,143,255,0.10);
        }

        /* ── Tab visibility ─────────────────────────────────────────── */
        .tab-content { display: none; }
        .tab-content.active { display: block; }

        /* ── Display font override ──────────────────────────────────── */
        .card-title, .modal-title-display {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            color: var(--synti) !important;
            letter-spacing: -0.3px;
        }
        h2.card-title, h3.card-title { color: var(--synti) !important; }

        /* ── Sidebar branding & active state ────────────────────────── */
        .sidebar-logo-text {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.2rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            line-height: 1;
        }
        .sidebar-logo-synti { color: #4D8FFF; }
        .sidebar-logo-web   { color: var(--color-base-content); }

        /* Active nav item — gradient derivado del azul SYNTIweb */
        .menu li button.menu-active,
        .menu li button[aria-selected="true"] {
            background: linear-gradient(90deg, rgba(77,143,255,0.18) 0%, rgba(77,143,255,0.04) 100%) !important;
            color: #4D8FFF !important;
            border-left: 3px solid #4D8FFF;
            padding-left: calc(0.75rem - 3px);
            font-weight: 600;
        }
        .menu li button:not(.menu-active):not([aria-selected="true"]):hover {
            background: rgba(77,143,255,0.07) !important;
            color: #4D8FFF !important;
        }

        /* ── Breathing dot (is_open indicator) ─────────────────────── */
        @keyframes synti-breathe {
            0%,100% { opacity:1; box-shadow: 0 0 0 0 rgba(34,197,94,0.5); }
            50%      { opacity:.7; box-shadow: 0 0 0 4px rgba(34,197,94,0); }
        }
        @keyframes synti-breathe-off {
            0%,100% { opacity:1; }
            50%      { opacity:.5; }
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

        /* ── Clock display ──────────────────────────────────────────── */
        #live-clock {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.8rem; font-weight: 700;
            color: var(--synti); letter-spacing: 0.5px;
        }

        /* ── Section cards (FlyonUI variables) ──────────────────────── */
        .form-section {
            background: var(--color-base-100);
            border-radius: var(--radius-box, 0.5rem);
            border: 1px solid color-mix(in oklch, var(--color-base-content) 10%, transparent);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .form-section-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.125rem; font-weight: 700;
            margin-bottom: 1.25rem; color: var(--synti);
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1rem;
        }
        .form-group { margin-bottom: 1rem; }
        .form-label {
            display: block; font-size: 0.875rem; font-weight: 500;
            color: var(--color-base-content); margin-bottom: 0.375rem;
        }
        .form-input, .form-textarea, .form-select {
            width: 100%; padding: 0.625rem 0.75rem;
            background: var(--color-base-200);
            border: 1px solid color-mix(in oklch, var(--color-base-content) 20%, transparent);
            border-radius: var(--radius-field, 0.375rem);
            color: var(--color-base-content);
            font-size: 0.875rem; font-family: inherit;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-input:focus, .form-textarea:focus, .form-select:focus {
            outline: none;
            border-color: var(--synti);
            box-shadow: 0 0 0 3px var(--synti-glow);
        }
        .form-textarea { min-height: 100px; resize: vertical; }
        .form-actions { display: flex; gap: 0.75rem; margin-top: 1.25rem; flex-wrap: wrap; }

        /* Focus visible (WCAG 2.4.7) */
        :focus-visible { outline: 2px solid var(--synti) !important; outline-offset: 2px !important; }
        .form-input:focus-visible, .form-textarea:focus-visible, .form-select:focus-visible { outline: none !important; }

        /* ── Buttons ─────────────────────────────────────────────────── */
        .btn-primary {
            background: var(--synti); color: #fff; border: none;
            padding: 0.625rem 1.25rem; border-radius: var(--radius-field);
            font-size: 0.875rem; font-weight: 600; cursor: pointer; transition: opacity 0.2s;
        }
        .btn-primary:hover { opacity: 0.88; }
        .btn-secondary {
            background: transparent; color: var(--synti);
            border: 1px solid var(--synti);
            padding: 0.625rem 1.25rem; border-radius: var(--radius-field);
            font-size: 0.875rem; font-weight: 600; cursor: pointer; transition: all 0.2s;
        }
        .btn-secondary:hover { background: var(--synti); color: #fff; }
        .btn-add {
            background: var(--synti); color: #fff; border: none;
            padding: 0.625rem 1.25rem; border-radius: var(--radius-field);
            font-size: 0.875rem; font-weight: 600; cursor: pointer; transition: opacity 0.2s;
        }
        .btn-add:hover:not(:disabled) { opacity: 0.88; }
        .btn-add:disabled { opacity: 0.45; cursor: not-allowed; }
        .btn-icon {
            background: none; border: none;
            color: color-mix(in oklch, var(--color-base-content) 55%, transparent);
            cursor: pointer; padding: 0.375rem; border-radius: var(--radius-field);
            transition: all 0.2s; font-size: 0.875rem;
            display: inline-flex; align-items: center; justify-content: center;
        }
        .btn-icon:hover {
            background: var(--synti-soft); color: var(--synti);
        }
        .btn-danger:hover { color: var(--color-error) !important; }

        /* ── Placeholder / Empty state ───────────────────────────────── */
        .placeholder-content, .empty-state {
            text-align: center; padding: 3.5rem 1.25rem;
            color: color-mix(in oklch, var(--color-base-content) 45%, transparent);
        }
        .placeholder-content h3, .empty-state h3 {
            font-size: 1.125rem; margin-bottom: 0.5rem;
            color: color-mix(in oklch, var(--color-base-content) 65%, transparent);
        }
        .empty-state-icon { font-size: 3rem; margin-bottom: 1rem; }

        /* ── Table ───────────────────────────────────────────────────── */
        .table-header {
            display: flex; justify-content: space-between;
            align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.75rem;
        }
        .table-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.125rem; font-weight: 700; color: var(--synti);
        }
        .table-subtitle {
            font-size: 0.8125rem; margin-top: 0.25rem;
            color: color-mix(in oklch, var(--color-base-content) 55%, transparent);
        }
        .table-container {
            background: var(--color-base-100);
            border-radius: var(--radius-box);
            border: 1px solid color-mix(in oklch, var(--color-base-content) 10%, transparent);
            overflow: hidden;
        }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table thead { background: var(--color-base-200); }
        .data-table th {
            padding: 0.75rem 1rem; text-align: left; font-size: 0.8125rem;
            font-weight: 600; color: color-mix(in oklch, var(--color-base-content) 70%, transparent);
            border-bottom: 1px solid color-mix(in oklch, var(--color-base-content) 12%, transparent);
        }
        .data-table td {
            padding: 0.75rem 1rem; font-size: 0.875rem; color: var(--color-base-content);
            border-bottom: 1px solid color-mix(in oklch, var(--color-base-content) 7%, transparent);
        }
        .data-table tbody tr:hover { background: var(--synti-soft); }
        .product-image {
            width: 48px; height: 48px; border-radius: var(--radius-field);
            object-fit: cover; background: var(--color-base-200);
        }

        /* ── Badges ──────────────────────────────────────────────────── */
        .badge-hot  { background: var(--color-error);   color: var(--color-error-content, #fff); }
        .badge-new  { background: var(--color-success); color: var(--color-success-content, #fff); }
        .badge-promo{ background: var(--color-warning); color: var(--color-warning-content, #000); }
        .status-indicator { display: inline-flex; align-items: center; gap: 0.375rem; font-size: 0.8125rem; }
        .status-dot { width: 8px; height: 8px; border-radius: 50%; }
        .status-dot.active  { background: var(--color-success); }
        .status-dot.inactive{ background: color-mix(in oklch, var(--color-base-content) 35%, transparent); }

        /* ── CRUD Modals ─────────────────────────────────────────────── */
        .crud-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.55);
            backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);
            z-index: 9999; align-items: center; justify-content: center; padding: 1rem;
        }
        .crud-overlay.show { display: flex; }
        .crud-dialog {
            background: var(--color-base-100);
            border-radius: var(--radius-box); width: 100%;
            max-width: 600px; max-height: 90vh; overflow-y: auto;
            position: relative; z-index: 10000;
            box-shadow: 0 25px 80px rgba(0,0,0,0.25), 0 0 0 1px var(--synti-bdr);
            border: 1px solid color-mix(in oklch, var(--color-base-content) 10%, transparent);
        }
        .crud-dialog-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid color-mix(in oklch, var(--color-base-content) 10%, transparent);
            display: flex; justify-content: space-between; align-items: center;
            background: linear-gradient(135deg, var(--synti-soft) 0%, transparent 60%);
        }
        .crud-dialog-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.1rem; font-weight: 700; color: var(--synti);
        }
        .crud-dialog-close {
            background: color-mix(in oklch, var(--color-base-content) 8%, transparent);
            border: 1px solid color-mix(in oklch, var(--color-base-content) 12%, transparent);
            color: var(--color-base-content);
            font-size: 1.125rem; cursor: pointer;
            width: 32px; height: 32px;
            display: flex; align-items: center; justify-content: center;
            border-radius: var(--radius-field); transition: all 0.2s;
        }
        .crud-dialog-close:hover {
            background: var(--synti-soft);
            border-color: var(--synti-bdr);
            color: var(--synti);
        }
        .crud-dialog-body { padding: 1.5rem; }

        /* ── Image preview ───────────────────────────────────────────── */
        .image-preview { margin-bottom: 1rem; text-align: center; }
        .image-preview img {
            max-width: 200px; max-height: 200px;
            border-radius: var(--radius-box); object-fit: cover;
            border: 2px solid var(--synti-bdr);
        }

        /* ── Gallery thumbs (Plan 3) ─────────────────────────────────── */
        .gallery-thumb { position: relative; display: inline-block; }
        .gallery-thumb img {
            width: 100px; height: 100px; border-radius: 6px; object-fit: cover;
            border: 2px solid color-mix(in oklch, var(--color-base-content) 15%, transparent);
        }
        .gallery-thumb-delete {
            position: absolute; top: -6px; right: -6px;
            width: 22px; height: 22px; border-radius: 50%;
            background: var(--color-error); color: #fff; border: none;
            font-size: 13px; line-height: 22px; text-align: center;
            cursor: pointer; padding: 0; display: flex; align-items: center; justify-content: center;
        }
        .gallery-thumb-delete:hover { opacity: 0.85; }
        .gallery-preview-thumb img {
            width: 80px; height: 80px; border-radius: 6px; object-fit: cover;
            border: 2px dashed var(--synti-bdr);
        }

        /* ── Icon Picker ─────────────────────────────────────────────── */
        #icon-picker-grid {
            scrollbar-width: thin;
            scrollbar-color: var(--synti-bdr) transparent;
        }
        #icon-picker-grid::-webkit-scrollbar { width: 5px; }
        #icon-picker-grid::-webkit-scrollbar-track { background: transparent; }
        #icon-picker-grid::-webkit-scrollbar-thumb { background: var(--synti-bdr); border-radius: 3px; }
        .icon-pick-item {
            transition: all 0.18s ease;
            border: 1px solid color-mix(in oklch, var(--color-base-content) 10%, transparent) !important;
            background: var(--color-base-200) !important;
            color: var(--color-base-content) !important;
        }
        .icon-pick-item:hover {
            background: var(--synti-soft) !important;
            border-color: var(--synti-bdr) !important;
            color: var(--synti) !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px var(--synti-glow) !important;
        }
        .icon-pick-item.selected {
            background: var(--synti) !important;
            border-color: var(--synti) !important;
            color: #fff !important;
            box-shadow: 0 4px 14px var(--synti-glow) !important;
        }

        /* ── Segmented control (Ícono / Imagen) ─────────────────────── */
        .svc-segment {
            display: inline-flex; align-items: center;
            background: var(--color-base-200);
            border-radius: var(--radius-box); padding: 3px; gap: 2px;
        }
        .svc-segment button {
            display: flex; align-items: center; gap: 6px;
            padding: 0.45rem 1rem; border-radius: calc(var(--radius-box) - 3px);
            font-size: 0.8125rem; font-weight: 600; border: none; cursor: pointer;
            transition: all 0.2s; color: color-mix(in oklch, var(--color-base-content) 55%, transparent);
            background: transparent;
        }
        .svc-segment button.seg-active {
            background: var(--color-base-100);
            color: var(--synti);
            box-shadow: 0 1px 6px rgba(0,0,0,0.10);
        }
        .svc-segment button .seg-icon { font-size: 1rem; }

        /* ── Service mode bar (legacy global selector on tab-servicios) ── */
        .svc-mode-bar {
            display: flex; gap: 0; border-radius: var(--radius-box); overflow: hidden;
            border: 1px solid color-mix(in oklch, var(--color-base-content) 12%, transparent);
            flex-shrink: 0;
        }
        .svc-mode-bar button {
            flex: 1; padding: 0.5rem 1.125rem; font-size: 0.75rem; font-weight: 700;
            border: none; cursor: pointer; transition: all .2s;
            background: var(--color-base-200); color: var(--color-base-content);
        }
        .svc-mode-bar button.active {
            background: var(--synti); color: #fff;
        }

        /* ── Legacy toggle ───────────────────────────────────────────── */
        .toggle-switch { position: relative; display: inline-block; width: 48px; height: 24px; }
        .toggle-switch input { opacity: 0; width: 0; height: 0; }
        .toggle-slider {
            position: absolute; cursor: pointer; inset: 0;
            background-color: color-mix(in oklch, var(--color-base-content) 20%, transparent);
            transition: 0.3s; border-radius: 24px;
        }
        .toggle-slider:before {
            position: absolute; content: "";
            height: 18px; width: 18px; left: 3px; bottom: 3px;
            background-color: var(--color-base-100); transition: 0.3s; border-radius: 50%;
        }
        .toggle-switch input:checked + .toggle-slider { background-color: var(--color-success); }
        .toggle-switch input:checked + .toggle-slider:before { transform: translateX(24px); }

        /* ═══════════════════════════════════════════════════════════════
           RESPONSIVE
        ═══════════════════════════════════════════════════════════════ */
        @media (max-width: 374px) {
            .form-section { padding: 16px; margin-bottom: 16px; }
            .form-grid { grid-template-columns: 1fr; }
            .btn-primary, .btn-secondary { min-height: 44px; padding: 10px 12px; }
        }
        @media (max-width: 639px) {
            .form-section { padding: 16px; margin-bottom: 16px; }
            .form-grid { grid-template-columns: 1fr; gap: 12px; }
            .form-group { margin-bottom: 12px; }
            .form-input, .form-textarea, .form-select { padding: 10px 12px; }
            .form-actions { flex-wrap: wrap; }
            .data-table thead { display: none; }
            .data-table tbody tr {
                display: block; margin-bottom: 0.75rem;
                background: var(--color-base-100);
                border-radius: var(--radius-box); padding: 0.75rem;
                border: 1px solid color-mix(in oklch, var(--color-base-content) 10%, transparent);
            }
            .data-table td {
                display: flex; justify-content: space-between; align-items: center;
                padding: 0.5rem 0;
                border-bottom: 1px solid color-mix(in oklch, var(--color-base-content) 7%, transparent);
                font-size: 0.8125rem;
            }
            .data-table td:last-child { border-bottom: none; }
            .data-table td::before {
                content: attr(data-label); font-weight: 600;
                color: color-mix(in oklch, var(--color-base-content) 55%, transparent);
                flex-basis: 90px; flex-shrink: 0; font-size: 12px;
            }
            .product-image { width: 48px; height: 48px; object-fit: cover; border-radius: 4px; aspect-ratio: 1; }
            .btn-primary, .btn-secondary { min-height: 44px; min-width: 44px; padding: 10px 12px; font-size: 13px; }
            .action-button { min-height: 44px; min-width: 44px; display: inline-flex; align-items: center; justify-content: center; }
            .theme-grid { grid-template-columns: repeat(2, 1fr) !important; }
            #header-extras { display: none !important; }
        }
        @media (max-width: 767px) {
            .form-grid { grid-template-columns: repeat(2, 1fr); gap: 14px; }
            .form-section { padding: 18px; margin-bottom: 18px; }
            .theme-grid { grid-template-columns: repeat(3, 1fr) !important; }
        }
        @media (max-width: 1023px) {
            .form-grid { grid-template-columns: repeat(2, 1fr); }
            .theme-grid { grid-template-columns: repeat(4, 1fr) !important; }
        }
        @media (min-width: 1024px) {
            .form-grid { grid-template-columns: repeat(3, 1fr); }
            .data-table tbody tr { display: table-row !important; }
            .data-table td::before { display: none !important; }
            .data-table thead { display: table-header-group !important; }
        }
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

<div class="flex min-h-screen flex-col">

    <!-- ══ HEADER NAVBAR ══════════════════════════════════════════════════ -->
    <div class="navbar bg-base-100 border-base-content/10 lg:ps-64 sticky top-0 z-50 border-b min-h-14 px-3 gap-2"
         style="box-shadow: 0 1px 12px rgba(77,143,255,0.06);">

        {{-- ── Start: hamburger + nombre negocio con dot de estado ── --}}
        <div class="navbar-start gap-2 min-w-0">
            <button type="button"
                    class="btn btn-text btn-square btn-sm lg:hidden shrink-0"
                    aria-haspopup="dialog" aria-expanded="false"
                    aria-controls="layout-sidebar"
                    data-overlay="#layout-sidebar">
                <span class="icon-[tabler--menu-2] size-5" aria-hidden="true"></span>
                <span class="sr-only">Abrir menú</span>
            </button>
            {{-- Logo mobile --}}
            <span class="sidebar-logo-text text-base lg:hidden shrink-0">
                <span class="sidebar-logo-synti">SYNTI</span><span class="sidebar-logo-web">web</span>
            </span>
            {{-- Negocio + dot de estado (desktop) --}}
            <div class="hidden lg:flex items-center gap-2 min-w-0">
                <span class="{{ $tenant->is_open ? 'dot-online' : 'dot-offline' }}" aria-hidden="true"></span>
                <span class="text-sm font-semibold text-base-content truncate max-w-52">{{ $tenant->business_name }}</span>
                <span class="badge badge-soft badge-xs shrink-0
                    {{ $plan->id === 1 ? 'badge-warning' : ($plan->id === 2 ? 'badge-success' : 'badge-info') }}">
                    {{ $plan->name }}
                </span>
            </div>
        </div>

        {{-- ── Center: reloj + tasa BCV (hidden mobile) ── --}}
        <div id="header-extras" class="navbar-center hidden md:flex items-center gap-3">
            {{-- Reloj en tiempo real --}}
            <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-box bg-base-200/70 border border-base-content/8">
                <span class="icon-[tabler--clock] size-3.5 text-base-content/50" aria-hidden="true"></span>
                <span id="live-clock" aria-label="Hora actual">--:--</span>
            </div>
            {{-- Tasa del dólar BCV --}}
            <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-box bg-base-200/70 border border-base-content/8">
                <span class="icon-[tabler--currency-dollar] size-3.5 text-green-500" aria-hidden="true"></span>
                <span class="text-xs font-semibold text-base-content/70">
                    Bs. <span id="header-dollar-rate">{{ number_format($dollarRate, 2) }}</span>
                </span>
            </div>
        </div>

        {{-- ── End: link al sitio + cerrar ── --}}
        <div class="navbar-end gap-1.5 shrink-0">
            {{-- Ver mi sitio público --}}
            <a href="/{{ $tenant->subdomain }}" target="_blank" rel="noopener noreferrer"
               class="btn btn-text btn-sm btn-circle hidden sm:flex"
               title="Ver mi sitio público">
                <span class="icon-[tabler--external-link] size-4 text-base-content/60" aria-hidden="true"></span>
            </a>
            {{-- Estado del negocio (mobile) --}}
            <span class="sm:hidden {{ $tenant->is_open ? 'dot-online' : 'dot-offline' }}" aria-hidden="true"></span>
            {{-- Cerrar / salir del dashboard --}}
            <a href="/{{ $tenant->subdomain }}"
               class="btn btn-soft btn-sm gap-1.5"
               title="Cerrar dashboard">
                <span class="icon-[tabler--layout-sidebar-right-collapse] size-4" aria-hidden="true"></span>
                <span class="hidden sm:inline">Cerrar</span>
            </a>
        </div>
    </div>

    <!-- ══ SIDEBAR ════════════════════════════════════════════════════════ -->
    <aside id="layout-sidebar"
           class="overlay overlay-open:translate-x-0 drawer drawer-start w-64
                  inset-y-0 start-0 hidden h-full [--auto-close:lg]
                  lg:z-50 lg:block lg:translate-x-0 lg:shadow-none"
           aria-label="Navegación principal"
           tabindex="-1">
        <div class="drawer-body border-base-content/20 h-full border-e p-0">
            <div class="flex h-full flex-col">

                {{-- Cerrar (solo mobile) --}}
                <button type="button"
                        class="btn btn-text btn-circle btn-sm absolute end-3 top-3 lg:hidden"
                        aria-label="Cerrar menú"
                        data-overlay="#layout-sidebar">
                    <span class="icon-[tabler--x] size-4.5" aria-hidden="true"></span>
                </button>

                {{-- Logo --}}
                <div class="border-base-content/20 flex items-center gap-2.5 border-b px-5 py-4">
                    {{-- Símbolo geométrico SYNTIweb --}}
                    <svg viewBox="0 0 900 900" class="size-7 shrink-0" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <rect x="0"   y="0"   width="560" height="80"  fill="currentColor" class="text-base-content"/>
                        <rect x="0"   y="80"  width="80"  height="480" fill="currentColor" class="text-base-content"/>
                        <rect x="820" y="300" width="80"  height="520" fill="currentColor" class="text-base-content"/>
                        <rect x="340" y="820" width="560" height="80"  fill="currentColor" class="text-base-content"/>
                        <rect x="0"   y="700" width="80"  height="120" fill="currentColor" class="text-base-content"/>
                        <rect x="0"   y="820" width="200" height="80"  fill="currentColor" class="text-base-content"/>
                        <circle cx="780" cy="120" r="120" fill="#4D8FFF"/>
                    </svg>
                    <span class="sidebar-logo-text">
                        <span class="sidebar-logo-synti">SYNTI</span><span class="sidebar-logo-web">web</span>
                    </span>
                </div>

                {{-- Tenant info --}}
                <div class="border-base-content/20 border-b px-5 py-3">
                    <p class="text-base-content text-sm font-semibold truncate">{{ $tenant->business_name }}</p>
                    <p class="text-base-content/60 text-xs">Plan {{ $plan->name }}</p>
                </div>

                {{-- Menú de navegación --}}
                <nav class="h-full overflow-y-auto" aria-label="Secciones del dashboard">
                    <ul class="menu menu-sm gap-0.5 p-3" role="tablist">
                        <li role="presentation">
                            <button class="w-full text-start menu-active" role="tab"
                                    aria-selected="true" aria-controls="tab-info"
                                    id="tab-info-btn" data-tab="info" tabindex="0">
                                <span class="icon-[tabler--info-circle] size-4.5" aria-hidden="true"></span>
                                Información
                            </button>
                        </li>
                        <li role="presentation">
                            <button class="w-full text-start" role="tab"
                                    aria-selected="false" aria-controls="tab-productos"
                                    id="tab-productos-btn" data-tab="productos" tabindex="-1">
                                <span class="icon-[tabler--package] size-4.5" aria-hidden="true"></span>
                                Productos
                            </button>
                        </li>
                        <li role="presentation">
                            <button class="w-full text-start" role="tab"
                                    aria-selected="false" aria-controls="tab-servicios"
                                    id="tab-servicios-btn" data-tab="servicios" tabindex="-1">
                                <span class="icon-[tabler--tool] size-4.5" aria-hidden="true"></span>
                                Servicios
                            </button>
                        </li>
                        <li role="presentation">
                            <button class="w-full text-start" role="tab"
                                    aria-selected="false" aria-controls="tab-diseno"
                                    id="tab-diseno-btn" data-tab="diseno" tabindex="-1">
                                <span class="icon-[tabler--palette] size-4.5" aria-hidden="true"></span>
                                Diseño
                            </button>
                        </li>
                        <li role="presentation">
                            <button class="w-full text-start" role="tab"
                                    aria-selected="false" aria-controls="tab-analytics"
                                    id="tab-analytics-btn" data-tab="analytics" tabindex="-1">
                                <span class="icon-[tabler--chart-bar] size-4.5" aria-hidden="true"></span>
                                Analytics
                            </button>
                        </li>
                        @if($plan->id === 3)
                        <li role="presentation">
                            <button class="w-full text-start" role="tab"
                                    aria-selected="false" aria-controls="tab-sucursales"
                                    id="tab-sucursales-btn" data-tab="sucursales" tabindex="-1">
                                <span class="icon-[tabler--map-pin] size-4.5" aria-hidden="true"></span>
                                Sucursales
                            </button>
                        </li>
                        @endif
                        <li role="presentation">
                            <button class="w-full text-start" role="tab"
                                    aria-selected="false" aria-controls="tab-config"
                                    id="tab-config-btn" data-tab="config" tabindex="-1">
                                <span class="icon-[tabler--settings] size-4.5" aria-hidden="true"></span>
                                Configuración
                            </button>
                        </li>
                    </ul>
                </nav>

                {{-- Footer sidebar --}}
                <div class="border-base-content/20 border-t p-3 mt-auto">
                    <a href="/{{ $tenant->subdomain }}" target="_blank" rel="noopener noreferrer"
                       class="btn btn-soft btn-block btn-sm">
                        <span class="icon-[tabler--external-link] size-4" aria-hidden="true"></span>
                        Ver mi sitio
                    </a>
                </div>

            </div>
        </div>
    </aside>

    <!-- ══ LAYOUT CONTENT con offset sidebar en desktop ══════════════════ -->
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
    <main id="main-content" class="mx-auto w-full max-w-7xl flex-1 grow p-4 lg:p-6" tabindex="-1">
        
        <!-- Tab: Info -->
        <div id="tab-info" class="tab-content active">
            <form id="form-info" onsubmit="saveInfo(event)">
                <div class="card bg-base-100 shadow-sm border border-base-content/10">
                    <div class="card-header">
                        <h2 class="card-title flex items-center gap-2">
                            <span class="icon-[tabler--building-store] size-5 text-primary" aria-hidden="true"></span>
                            Información del Negocio
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <div class="form-control">
                                <label class="label" for="info-business-name">
                                    <span class="label-text font-medium">Nombre del Negocio</span>
                                </label>
                                <input id="info-business-name" type="text" class="input input-bordered w-full"
                                       name="business_name" value="{{ $tenant->business_name }}"
                                       required autocomplete="organization">
                            </div>

                            <div class="form-control">
                                <label class="label" for="info-subdomain">
                                    <span class="label-text font-medium">Subdominio</span>
                                </label>
                                <input id="info-subdomain" type="text" class="input input-bordered w-full opacity-60"
                                       value="{{ $tenant->subdomain }}" disabled aria-readonly="true">
                            </div>

                            <div class="form-control">
                                <label class="label" for="info-slogan">
                                    <span class="label-text font-medium">Eslogan</span>
                                </label>
                                <input id="info-slogan" type="text" class="input input-bordered w-full"
                                       name="slogan" value="{{ $tenant->slogan }}">
                            </div>

                            <div class="form-control">
                                <label class="label" for="info-phone">
                                    <span class="label-text font-medium">Teléfono</span>
                                </label>
                                <input id="info-phone" type="text" class="input input-bordered w-full"
                                       name="phone" value="{{ $tenant->phone }}" autocomplete="tel">
                            </div>

                            <div class="form-control">
                                <label class="label" for="info-whatsapp">
                                    <span class="label-text font-medium">WhatsApp Ventas</span>
                                </label>
                                <input id="info-whatsapp" type="text" class="input input-bordered w-full"
                                       name="whatsapp_sales" value="{{ $tenant->whatsapp_sales }}" autocomplete="off">
                            </div>

                            <div class="form-control">
                                <label class="label" for="info-email">
                                    <span class="label-text font-medium">Email</span>
                                </label>
                                <input id="info-email" type="email" class="input input-bordered w-full"
                                       name="email" value="{{ $tenant->email }}" autocomplete="email">
                            </div>

                            <div class="form-control">
                                <label class="label" for="info-address">
                                    <span class="label-text font-medium">Dirección</span>
                                </label>
                                <input id="info-address" type="text" class="input input-bordered w-full"
                                       name="address" value="{{ $tenant->address }}" autocomplete="street-address">
                            </div>

                            <div class="form-control">
                                <label class="label" for="info-city">
                                    <span class="label-text font-medium">Ciudad</span>
                                </label>
                                <input id="info-city" type="text" class="input input-bordered w-full"
                                       name="city" value="{{ $tenant->city }}" autocomplete="address-level2">
                            </div>

                            @if($tenant->plan_id >= 2)
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-medium flex items-center gap-2">
                                        Título sección Contacto
                                        <span class="badge badge-soft badge-success badge-xs">Plan {{ $plan->name }}</span>
                                    </span>
                                </label>
                                <input type="text" name="contact_title" class="input input-bordered w-full"
                                       value="{{ data_get($tenant->settings, 'business_info.contact.title', '') }}"
                                       placeholder="Contáctanos">
                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-medium flex items-center gap-2">
                                        Subtítulo sección Contacto
                                        <span class="badge badge-soft badge-success badge-xs">Plan {{ $plan->name }}</span>
                                    </span>
                                </label>
                                <input type="text" name="contact_subtitle" class="input input-bordered w-full"
                                       value="{{ data_get($tenant->settings, 'business_info.contact.subtitle', '') }}"
                                       placeholder="Estamos aquí para atenderte">
                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-medium flex items-center gap-2">
                                        URL Google Maps
                                        <span class="badge badge-soft badge-success badge-xs">Plan {{ $plan->name }}</span>
                                    </span>
                                </label>
                                <input type="url" name="contact_maps_url" class="input input-bordered w-full"
                                       value="{{ data_get($tenant->settings, 'business_info.contact.maps_url', '') }}"
                                       placeholder="https://www.google.com/maps/embed?pb=...">
                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-medium flex items-center gap-2">
                                        Teléfono Secundario
                                        <span class="badge badge-soft badge-success badge-xs">Plan {{ $plan->name }}</span>
                                    </span>
                                </label>
                                <input type="tel" name="phone_secondary" class="input input-bordered w-full"
                                       value="{{ data_get($tenant->settings, 'contact_info.phone_secondary', '') }}"
                                       placeholder="+58 XXX XXXXXXX">
                            </div>
                            @endif
                        </div>

                        <div class="form-control mt-4">
                            <label class="label" for="info-description">
                                <span class="label-text font-medium">Descripción</span>
                            </label>
                            <textarea id="info-description" class="textarea textarea-bordered w-full min-h-24"
                                      name="description">{{ $tenant->description }}</textarea>
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
                <div class="card-body pt-0 pb-0 overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Imagen</th>
                                <th>Nombre</th>
                                <th>Precio USD</th>
                                <th>Badge</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr class="hover:bg-base-200/50 transition-colors">
                                <td>
                                    @if($product->image_filename)
                                        <img src="{{ asset('storage/tenants/' . $tenant->id . '/' . $product->image_filename) }}"
                                             alt="{{ $product->name }}"
                                             class="size-12 rounded-box object-cover border border-base-content/10"
                                             width="48" height="48"
                                             loading="lazy"
                                             decoding="async">
                                    @else
                                        <div class="size-12 rounded-box bg-base-200 flex items-center justify-center border border-base-content/10">
                                            <span class="icon-[tabler--package] size-6 text-base-content/30" aria-hidden="true"></span>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="font-semibold text-base-content">{{ $product->name }}</span>
                                    @if($product->is_featured)
                                        <span class="badge badge-soft badge-warning badge-xs ml-1">Destacado</span>
                                    @endif
                                </td>
                                <td class="font-medium text-base-content/80">${{ number_format($product->price_usd, 2) }}</td>
                                <td>
                                    @if($product->badge === 'hot')
                                        <span class="badge badge-soft badge-error badge-sm">Hot</span>
                                    @elseif($product->badge === 'new')
                                        <span class="badge badge-soft badge-success badge-sm">New</span>
                                    @elseif($product->badge === 'promo')
                                        <span class="badge badge-soft badge-warning badge-sm">Promo</span>
                                    @else
                                        <span class="text-base-content/30 text-xs">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->is_active)
                                        <span class="badge badge-soft badge-success badge-sm gap-1">
                                            <span class="size-1.5 rounded-full bg-success inline-block"></span>
                                            Activo
                                        </span>
                                    @else
                                        <span class="badge badge-soft badge-error badge-sm gap-1">
                                            <span class="size-1.5 rounded-full bg-error inline-block"></span>
                                            Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center gap-1">
                                        <button onclick="editProduct({{ $product->id }})" class="btn btn-sm btn-ghost" title="Editar" aria-label="Editar producto">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button onclick="deleteProduct({{ $product->id }})" class="btn btn-sm btn-ghost text-error" title="Eliminar" aria-label="Eliminar producto">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="card-body flex flex-col items-center justify-center py-12 text-center">
                    <span class="icon-[tabler--package] size-12 text-base-content/20 mb-3" aria-hidden="true"></span>
                    <h3 class="font-semibold text-base-content/60 mb-1">No hay productos aún</h3>
                    <p class="text-sm text-base-content/40">Comienza agregando tu primer producto</p>
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

                        <div class="form-section">
                            <label for="product-image" class="form-label">Imagen Principal del Producto</label>
                            <input type="file" id="product-image" accept="image/*" class="form-input" onchange="previewProductImage(event)">
                            <p class="text-xs text-base-content/50 mt-1">Máx. 2MB, se redimensionará a 800px</p>
                        </div>

                        {{-- Gallery Section — Plan 3 (VISIÓN) only --}}
                        @if($plan->id === 3)
                        <div class="form-section" id="product-gallery-section">
                            <label class="form-label flex items-center gap-2">
                                <span class="icon-[tabler--photo-scan] size-4 text-primary" aria-hidden="true"></span>
                                Galería Adicional
                                <span class="text-xs text-base-content/50 font-normal">(máx. 2 fotos extra — Plan Visión)</span>
                            </label>

                            {{-- Existing gallery images container --}}
                            <div id="product-gallery-existing" style="display: none; margin-bottom: 12px;">
                                <div id="product-gallery-thumbs" style="display: flex; gap: 10px; flex-wrap: wrap;"></div>
                            </div>

                            {{-- Upload new gallery images --}}
                            <div id="product-gallery-upload-area" style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 8px;">
                                <div id="gallery-slot-1" class="gallery-upload-slot" style="display: none;">
                                    <input type="file" id="product-gallery-1" accept="image/*" class="form-input" onchange="previewGalleryImage(event, 1)" style="font-size: 13px;">
                                </div>
                                <div id="gallery-slot-2" class="gallery-upload-slot" style="display: none;">
                                    <input type="file" id="product-gallery-2" accept="image/*" class="form-input" onchange="previewGalleryImage(event, 2)" style="font-size: 13px;">
                                </div>
                            </div>

                            {{-- Gallery previews --}}
                            <div id="product-gallery-previews" style="display: flex; gap: 10px; margin-top: 8px; flex-wrap: wrap;"></div>

                            <p class="text-xs text-base-content/50 mt-1.5">
                                Las imágenes de galería se suben al guardar el producto. Total: 1 principal + 2 galería = 3 fotos.
                            </p>
                        </div>
                        @endif

                        <div class="form-section">
                            <label for="product-name" class="form-label">Nombre *</label>
                            <input type="text" id="product-name" class="form-input" required maxlength="100">
                        </div>

                        <div class="form-section">
                            <label for="product-description" class="form-label">Descripción</label>
                            <textarea id="product-description" class="form-input" rows="3" maxlength="500"></textarea>
                        </div>

                        <div class="form-section">
                            <label for="product-price" class="form-label">Precio USD *</label>
                            <input type="number" id="product-price" class="form-input" required step="0.01" min="0">
                        </div>

                        <div class="form-section">
                            <label for="product-badge" class="form-label">Badge</label>
                            <select id="product-badge" class="form-input">
                                <option value="">Sin badge</option>
                                <option value="hot">🔥 Hot</option>
                                <option value="new">✨ New</option>
                                <option value="promo">🎉 Promo</option>
                            </select>
                        </div>

                        <div class="form-section">
                            <label class="form-label">Producto Activo</label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="product-is-active" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="form-section">
                            <label class="form-label">Producto Destacado ⭐</label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="product-is-featured">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn-secondary" onclick="closeProductModal()">Cancelar</button>
                            <button type="submit" class="btn-primary">Guardar Producto</button>
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
                <div class="card-body pt-0 pb-0 overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Imagen</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($services as $service)
                            <tr class="hover:bg-base-200/50 transition-colors">
                                <td>
                                    @if($service->image_filename)
                                        <img src="{{ asset('storage/tenants/' . $tenant->id . '/' . $service->image_filename) }}"
                                             alt="{{ $service->name }}"
                                             class="size-12 rounded-box object-cover border border-base-content/10"
                                             width="48" height="48"
                                             loading="lazy"
                                             decoding="async">
                                    @elseif($service->icon_name)
                                        <div class="size-12 rounded-box bg-primary/10 flex items-center justify-center border border-primary/20">
                                            <span class="iconify tabler--{{ str_replace('_', '-', $service->icon_name) }} text-primary text-2xl"></span>
                                        </div>
                                    @else
                                        <div class="size-12 rounded-box bg-base-200 flex items-center justify-center border border-base-content/10">
                                            <span class="icon-[tabler--tool] size-6 text-base-content/30" aria-hidden="true"></span>
                                        </div>
                                    @endif
                                </td>
                                <td class="font-semibold text-base-content">{{ $service->name }}</td>
                                <td class="text-sm text-base-content/60">
                                    @if($service->description)
                                        {{ Str::limit($service->description, 60) }}
                                    @else
                                        <span class="text-base-content/30">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($service->is_active)
                                        <span class="badge badge-soft badge-success badge-sm gap-1">
                                            <span class="size-1.5 rounded-full bg-success inline-block"></span>
                                            Activo
                                        </span>
                                    @else
                                        <span class="badge badge-soft badge-error badge-sm gap-1">
                                            <span class="size-1.5 rounded-full bg-error inline-block"></span>
                                            Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center gap-1">
                                        <button onclick="editService({{ $service->id }})" class="btn btn-sm btn-ghost" title="Editar" aria-label="Editar servicio">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button onclick="deleteService({{ $service->id }})" class="btn btn-sm btn-ghost text-error" title="Eliminar" aria-label="Eliminar servicio">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="card-body flex flex-col items-center justify-center py-12 text-center">
                    <span class="icon-[tabler--tool] size-12 text-base-content/20 mb-3" aria-hidden="true"></span>
                    <h3 class="font-semibold text-base-content/60 mb-1">No hay servicios aún</h3>
                    <p class="text-sm text-base-content/40">Comienza agregando tu primer servicio</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Modal: Servicio -->
        <div id="service-modal" class="crud-overlay"
             role="dialog" aria-modal="true" aria-labelledby="service-modal-title" aria-hidden="true">
            <div class="crud-dialog" style="max-width: 700px;">
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
                        <div id="svc-section-icon" class="form-section" style="margin-bottom: 28px;">
                            <label class="form-label font-semibold mb-4 block">Ícono del Servicio</label>

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
                        <div id="svc-section-image" class="form-section" style="display: none;">
                            <label class="form-label">Imagen del Servicio</label>
                            <div class="image-preview" id="service-image-preview" style="display: none;">
                                <img id="service-image-preview-img" src="" alt="Preview">
                            </div>
                            <input type="file" id="service-image" accept="image/*" class="form-input" onchange="previewServiceImage(event)">
                            <p class="text-xs text-base-content/50 mt-1">Máx. 2MB, se redimensionará a 800px</p>
                        </div>
                        @endif

                        <div class="form-section mb-6">
                            <label for="service-name" class="form-label font-semibold mb-2 block">Nombre *</label>
                            <input type="text" id="service-name" class="input w-full" required maxlength="100">
                        </div>

                        <div class="form-section mb-6">
                            <label for="service-description" class="form-label font-semibold mb-2 block">Descripción</label>
                            <textarea id="service-description" class="textarea w-full" rows="3" maxlength="500"></textarea>
                        </div>

                        <div class="form-section mb-7">
                            <label class="form-label font-semibold mb-2 block">Servicio Activo</label>
                            <label class="toggle-switch flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" id="service-is-active" checked>
                                <span class="toggle-slider"></span>
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

        <!-- Modal: Límite de Plan -->
        <div id="limit-modal" class="crud-overlay"
             role="dialog" aria-modal="true" aria-labelledby="limit-modal-title" aria-hidden="true">
            <div class="crud-dialog" style="max-width: 480px;">
                <div class="crud-dialog-header">
                    <h3 class="crud-dialog-title" id="limit-modal-title">⚠️ Límite Alcanzado</h3>
                    <button class="crud-dialog-close" onclick="closeLimitModal()" aria-label="Cerrar modal">&times;</button>
                </div>
                <div class="crud-dialog-body">
                    <p id="limit-modal-message" style="color: #cbd5e1; line-height: 1.6; margin-bottom: 20px;"></p>
                    <div id="limit-modal-actions" style="display: flex; gap: 12px; flex-wrap: wrap;">
                        <button onclick="closeLimitModal()" class="btn-secondary" style="flex: 1;">Cerrar</button>
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

<!-- Sección: Temas FlyonUI -->
<div class="form-section">
    <h2 class="table-title">🎨 Tema Visual (FlyonUI)</h2>
    <p class="table-subtitle" style="margin-bottom: 16px;">Elige el tema que mejor represente tu marca</p>
    
    <div id="theme-success-message" style="display: none; padding: 12px; background: rgba(0,204,102,0.2); border-radius: 8px; margin-bottom: 16px; color: #00cc66; font-size: 14px;">
        ✓ Tema actualizado correctamente
    </div>

    {{-- activeTheme viene del controller --}}
    @foreach($themesByCategory as $category => $themes)
    <div style="margin-bottom: 28px;">
        <h3 class="text-[11px] font-bold text-base-content/40 mb-3 uppercase tracking-widest">
            {{ $category }}
        </h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 10px;">
            @foreach($themes as $theme)
            @php
                $isActive = $currentTheme === $theme['slug'];
                $bg      = $theme['colors'][3]; // base background
                $isDark  = in_array($theme['slug'], ['dark','black','spotify','valorant','luxury','perplexity','slack','vscode']);
                $textColor = $isDark ? 'rgba(255,255,255,0.9)' : 'rgba(0,0,0,0.85)';
                $subColor  = $isDark ? 'rgba(255,255,255,0.45)' : 'rgba(0,0,0,0.4)';
            @endphp
            <div
                class="theme-card"
                data-slug="{{ $theme['slug'] }}"
                onclick="updateTheme('{{ $theme['slug'] }}')"
                style="
                    cursor: pointer;
                    border-radius: 12px;
                    border: {{ $isActive ? '2px solid #2B6FFF' : '1px solid rgba(255,255,255,0.08)' }};
                    box-shadow: {{ $isActive ? '0 0 0 3px rgba(43,111,255,0.25)' : '0 2px 8px rgba(0,0,0,0.3)' }};
                    transition: all 0.2s ease;
                    position: relative;
                    overflow: hidden;
                    background: {{ $bg }};
                "
            >

                <!-- Barra de colores primarios -->
                <!-- Barra de colores primarios -->
                <div style="display: flex; height: 48px; border-radius: 10px 10px 0 0; overflow: hidden;">
                    @foreach(array_slice($theme['colors'], 0, 4) as $color)
                    <div style="flex: 1; background: {{ $color }};"></div>
                    @endforeach
                </div>

                <!-- Info del tema -->
                <div style="padding: 8px 10px 10px;">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <span style="font-size: 12px; font-weight: 600; color: {{ $textColor }}; line-height: 1.2;">
                            {{ $theme['name'] }}
                        </span>
                        @if($activeTheme === $theme['slug'])
                        <div class="badge badge-primary" style="margin-left:8px;">✓</div>
                        @endif
                    </div>
                    @if(isset($theme['font']))
                    <div style="font-size: 10px; color: {{ $subColor }}; margin-top: 2px; font-style: italic;">
                        {{ $theme['font'] }}
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
</div>

@if($tenant->plan_id === 3)
<div class="form-section" style="margin-top: 24px;">
    <div class="divider" style="margin: 0 0 20px;">O personaliza tus colores</div>
    <div class="card bg-base-200">
        <div class="card-body">
            <h3 class="card-title">🎨 Paleta Personalizada</h3>
            @php
            $customPalette = $tenant->settings['engine_settings']['visual']['custom_palette'] ?? [
                'primary' => '#570DF8',
                'secondary' => '#F000B9',
                'accent' => '#1DCDBC',
                'base' => '#FFFFFF'
            ];
            @endphp
            <div class="grid grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label"><span class="label-text">Primary</span></label>
                    <input type="color" id="custom-primary" class="input w-full h-10" value="{{ $customPalette['primary'] }}">
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Secondary</span></label>
                    <input type="color" id="custom-secondary" class="input w-full h-10" value="{{ $customPalette['secondary'] }}">
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Accent</span></label>
                    <input type="color" id="custom-accent" class="input w-full h-10" value="{{ $customPalette['accent'] }}">
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Base</span></label>
                    <input type="color" id="custom-base" class="input w-full h-10" value="{{ $customPalette['base'] }}">
                </div>
            </div>
            <button onclick="applyCustomPalette()" class="btn btn-primary mt-4">
                Aplicar Paleta Custom
            </button>
        </div>
    </div>
</div>
@endif

            {{-- ══════════════════════════════════════════════════════════════
                 SECCIÓN: Orden de Secciones (Drag & Drop)
            ══════════════════════════════════════════════════════════════ --}}
            <div class="form-section" style="margin-top: 32px;">
                <h2 class="table-title">📋 Orden de Secciones</h2>
                <p class="table-subtitle" style="margin-bottom: 16px;">
                    Arrastra para reordenar. Las secciones apagadas no aparecen en tu landing.
                </p>

                <div id="sortable-sections" class="space-y-2">
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
                            <div class="flex items-center gap-3 px-3 py-2.5 rounded-box
                                        bg-base-200 border border-base-content/10
                                        {{ $hasAccess ? 'cursor-move' : 'cursor-not-allowed' }}
                                        transition-colors hover:border-base-content/20">

                                {{-- Handle / Lock --}}
                                @if($hasAccess)
                                    <span class="drag-handle text-base-content/40 hover:text-base-content/70 cursor-grab select-none flex-shrink-0 active:cursor-grabbing">
                                        <span class="iconify tabler--grip-vertical" style="font-size: 16px;"></span>
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
                                           class="toggle toggle-primary toggle-sm"
                                           id="section-{{ $key }}"
                                           @checked($isVisible)
                                           onchange="toggleSection('{{ $key }}', this.checked)">
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- SortableJS se inicializa al abrir la pestaña Diseño (ver script global al pie del body) --}}

            @if($plan->id === 1)
            <div style="background: linear-gradient(135deg, #1a2040 0%, #0f1c32 100%); border: 1px solid rgba(43,111,255,0.3); border-radius: 12px; padding: 16px 20px; margin-top: 24px; margin-bottom: 8px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span style="font-size: 22px;">🎨</span>
                    <div>
                        <p style="color: #fff; font-weight: 600; font-size: 14px; margin: 0 0 2px;">Desbloquea más personalización</p>
                        <p style="color: rgba(255,255,255,0.5); font-size: 12px; margin: 0;">Header Top + Sección Acerca de disponibles desde el Plan CRECIMIENTO</p>
                    </div>
                </div>
                <a href="https://syntiweb.com/planes" target="_blank" rel="noopener noreferrer"
                   style="background: #2B6FFF; color: #fff; text-decoration: none; padding: 8px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; white-space: nowrap;"
                   onmouseover="this.style.background='#1e5beb'"
                   onmouseout="this.style.background='#2B6FFF'">
                    Ver Planes ↗
                </a>
            </div>
            @endif

            {{-- ── Imágenes ──────────────────────────────────────────────── --}}
            <div class="card bg-base-100 shadow-sm border border-base-content/10 mt-4">
                <div class="card-header">
                    <div class="flex items-center gap-2">
                        <span class="icon-[tabler--photo] size-5 text-primary"></span>
                        <h3 class="card-title">Imágenes del Negocio</h3>
                    </div>
                    <p class="text-xs text-base-content/50 mt-0.5">Logo e imagen principal de tu landing</p>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        {{-- Logo --}}
                        <div>
                            <p class="text-sm font-medium text-base-content mb-2">Logo del Negocio</p>
                            <div class="bg-base-200 rounded-box h-44 flex items-center justify-center mb-3 overflow-hidden">
                                @if($customization && $customization->logo_filename)
                                    <img id="logo-preview"
                                         src="{{ asset('storage/tenants/' . $tenant->id . '/' . $customization->logo_filename) }}"
                                         alt="Logo" class="max-w-full max-h-full object-contain" loading="lazy">
                                @else
                                    <div id="logo-placeholder" class="text-center text-base-content/30">
                                        <span class="icon-[tabler--photo-off] size-12 mb-2 block mx-auto"></span>
                                        <p class="text-xs">Sin logo</p>
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

                        {{-- Hero --}}
                        <div>
                            <p class="text-sm font-medium text-base-content mb-2">Imagen Principal (Hero)</p>
                            <div class="bg-base-200 rounded-box h-44 flex items-center justify-center mb-3 overflow-hidden">
                                @if($customization && $customization->hero_filename)
                                    <img id="hero-preview"
                                         src="{{ asset('storage/tenants/' . $tenant->id . '/' . $customization->hero_filename) }}"
                                         alt="Hero" class="w-full h-full object-cover" loading="lazy">
                                @else
                                    <div id="hero-placeholder" class="text-center text-base-content/30">
                                        <span class="icon-[tabler--panorama] size-12 mb-2 block mx-auto"></span>
                                        <p class="text-xs">Sin imagen hero</p>
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
                </div>
            </div>
        </div>

        <!-- Tab: Analytics -->
        <div id="tab-analytics" class="tab-content">

            {{-- ── KPI Stat Cards ────────────────────────────────────────── --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

                {{-- Visitas --}}
                <div class="card bg-base-100 shadow-sm border border-base-content/10">
                    <div class="card-body p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="avatar avatar-placeholder">
                                <div class="bg-primary/10 text-primary rounded-field size-10">
                                    <span class="icon-[tabler--eye] size-5"></span>
                                </div>
                            </div>
                            <span class="badge badge-soft badge-sm badge-primary">Hoy</span>
                        </div>
                        <div class="text-2xl font-bold text-base-content">0</div>
                        <div class="text-xs text-base-content/55 mt-0.5">Visitas al sitio</div>
                    </div>
                </div>

                {{-- WhatsApp --}}
                <div class="card bg-base-100 shadow-sm border border-base-content/10">
                    <div class="card-body p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="avatar avatar-placeholder">
                                <div class="bg-success/10 text-success rounded-field size-10">
                                    <span class="icon-[tabler--brand-whatsapp] size-5"></span>
                                </div>
                            </div>
                            <span class="badge badge-soft badge-sm badge-success">Hoy</span>
                        </div>
                        <div class="text-2xl font-bold text-base-content">0</div>
                        <div class="text-xs text-base-content/55 mt-0.5">Clicks WhatsApp</div>
                    </div>
                </div>

                {{-- QR --}}
                <div class="card bg-base-100 shadow-sm border border-base-content/10">
                    <div class="card-body p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="avatar avatar-placeholder">
                                <div class="bg-warning/10 text-warning rounded-field size-10">
                                    <span class="icon-[tabler--qrcode] size-5"></span>
                                </div>
                            </div>
                            <span class="badge badge-soft badge-sm badge-warning">Hoy</span>
                        </div>
                        <div class="text-2xl font-bold text-base-content">0</div>
                        <div class="text-xs text-base-content/55 mt-0.5">Escaneos QR</div>
                    </div>
                </div>

                {{-- Productos vistos --}}
                <div class="card bg-base-100 shadow-sm border border-base-content/10">
                    <div class="card-body p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="avatar avatar-placeholder">
                                <div class="bg-info/10 text-info rounded-field size-10">
                                    <span class="icon-[tabler--package] size-5"></span>
                                </div>
                            </div>
                            <span class="badge badge-soft badge-sm badge-info">Hoy</span>
                        </div>
                        <div class="text-2xl font-bold text-base-content">0</div>
                        <div class="text-xs text-base-content/55 mt-0.5">Productos vistos</div>
                    </div>
                </div>
            </div>

            {{-- ── Charts Row ────────────────────────────────────────────── --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">

                {{-- Actividad 7 días (área) --}}
                <div class="card bg-base-100 shadow-sm border border-base-content/10 lg:col-span-2">
                    <div class="card-header flex items-center justify-between">
                        <h4 class="card-title text-base">Actividad — últimos 7 días</h4>
                        <span class="badge badge-soft badge-primary badge-sm">Próximamente</span>
                    </div>
                    <div class="card-body pt-0">
                        <div id="analytics-area-chart"></div>
                        <p class="text-xs text-base-content/40 text-center mt-2">
                            La analítica detallada estará disponible en la próxima versión
                        </p>
                    </div>
                </div>

                {{-- Distribución (donut) --}}
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

                {{-- Existing branches --}}
                <div id="branches-list" class="space-y-3 mb-4">
                    @foreach($branches as $branch)
                    <div class="card bg-base-100 shadow-sm border border-base-content/10 branch-card" id="branch-card-{{ $branch->id }}">
                        <div class="card-body flex-row items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="icon-[tabler--map-pin] size-4 text-primary shrink-0" aria-hidden="true"></span>
                                    <h3 class="branch-name font-semibold text-base-content">{{ $branch->name }}</h3>
                                </div>
                                <p class="branch-address text-sm text-base-content/60 leading-relaxed">{{ $branch->address }}</p>
                            </div>
                            <div class="flex gap-1 shrink-0">
                                <button type="button"
                                        class="btn btn-circle btn-text btn-sm text-base-content/60 hover:text-primary"
                                        onclick="editBranch({{ $branch->id }}, '{{ addslashes($branch->name) }}', '{{ addslashes($branch->address) }}')"
                                        title="Editar" aria-label="Editar sucursal">
                                    <span class="icon-[tabler--pencil] size-4" aria-hidden="true"></span>
                                </button>
                                <button type="button"
                                        class="btn btn-circle btn-text btn-sm text-base-content/60 hover:text-error"
                                        onclick="deleteBranch({{ $branch->id }})"
                                        title="Eliminar" aria-label="Eliminar sucursal">
                                    <span class="icon-[tabler--trash] size-4" aria-hidden="true"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Add/Edit Branch Form --}}
                <div id="branch-form-container" class="card bg-base-100 shadow-sm border border-base-content/10"
                     {{ $currentBranchCount >= $maxBranches ? 'style="display:none"' : '' }}>
                    <div class="card-header">
                        <h3 id="branch-form-title" class="card-title text-base flex items-center gap-2">
                            <span class="icon-[tabler--plus] size-4 text-primary" aria-hidden="true"></span>
                            Agregar Sucursal
                        </h3>
                    </div>
                    <div class="card-body">
                        <input type="hidden" id="branch-edit-id" value="">
                        <div class="flex flex-col gap-4">
                            <div class="form-control">
                                <label class="label" for="branch-name">
                                    <span class="label-text font-medium">Nombre de la Sucursal *</span>
                                </label>
                                <input type="text" id="branch-name" maxlength="150" required
                                       class="input input-bordered w-full"
                                       placeholder="Ej: Sede Centro, Sucursal Altamira...">
                            </div>
                            <div class="form-control">
                                <label class="label" for="branch-address">
                                    <span class="label-text font-medium">Dirección *</span>
                                </label>
                                <textarea id="branch-address" maxlength="500" rows="2" required
                                          class="textarea textarea-bordered w-full"
                                          placeholder="Ej: Av. Libertador, Torre X, Piso 3, Caracas"></textarea>
                            </div>
                            <div class="flex items-center gap-3 pt-2 border-t border-base-content/10">
                                <button type="button" onclick="saveBranch()" class="btn btn-primary flex-1 gap-2">
                                    <span class="icon-[tabler--device-floppy] size-4" aria-hidden="true"></span>
                                    Guardar Sucursal
                                </button>
                                <button type="button" id="branch-cancel-btn" onclick="cancelBranchEdit()"
                                        class="btn btn-ghost hidden">
                                    Cancelar
                                </button>
                            </div>
                        </div>
                        <p class="text-xs text-base-content/40 mt-3">{{ $currentBranchCount }} de {{ $maxBranches }} sucursales usadas</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Tab: Config -->
        <div id="tab-config" class="tab-content">

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
                    {{-- ── Plan 2 + 3: all 6 networks ── --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4" id="social-all-fields">
                        @foreach($availableKeys as $key)
                        @php $meta = $allNetworksMeta[$key]; $current = $socialNetworks[$key] ?? ''; @endphp
                        <div class="form-control">
                            <label class="label" for="social-{{ $key }}">
                                <span class="label-text flex items-center gap-1.5 font-medium">
                                    <span class="icon-[{{ $meta['icon'] }}] size-4 text-base-content/60" aria-hidden="true"></span>
                                    {{ $meta['label'] }}
                                </span>
                            </label>
                            <input type="text" id="social-{{ $key }}" name="social_{{ $key }}"
                                   value="{{ $current }}"
                                   placeholder="{{ $meta['placeholder'] }}"
                                   maxlength="255"
                                   class="input input-bordered w-full">
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
                    <div class="alert alert-warning">
                        <span class="icon-[tabler--lock] size-4" aria-hidden="true"></span>
                        <span class="text-sm">Para elegir los medios de pago, mejora al Plan CRECIMIENTO o superior.</span>
                    </div>

                    @else
                    {{-- Plan 2 + 3: Selectable checkboxes (global) --}}
                    <p class="text-sm font-medium text-base-content mb-3">
                        @if($plan->id === 3) Métodos globales (todos los clientes) @else Métodos visibles en tu landing @endif
                    </p>

                    <p class="text-xs font-semibold uppercase tracking-wide text-base-content/40 mb-2">Nacionales / Divisas</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-4">
                        @foreach($allPayMeta as $mkey => $m)
                        @php $checked = in_array($mkey, $globalEnabled); @endphp
                        <label id="pay-label-{{ $mkey }}" onclick="togglePayMethod('{{ $mkey }}')"
                               class="flex items-center gap-2 cursor-pointer p-2.5 rounded-box border transition-all select-none
                                      {{ $checked ? 'bg-primary/20 border-primary/50 text-primary font-semibold' : 'bg-base-200/50 border-base-content/10 text-base-content hover:border-base-content/20' }}">
                            <input type="checkbox" id="pay-check-{{ $mkey }}" value="{{ $mkey }}" {{ $checked ? 'checked' : '' }} class="hidden">
                            <span class="text-base">{{ $m['icon'] }}</span>
                            <div class="flex-1 min-w-0">
                                <div class="text-xs font-medium {{ $checked ? 'text-primary' : 'text-base-content' }}">{{ $m['label'] }}</div>
                                <div class="text-xs {{ $checked ? 'text-primary/70' : 'text-base-content/40' }}">{{ $m['desc'] }}</div>
                            </div>
                            <span id="pay-check-icon-{{ $mkey }}"
                                  class="icon-[tabler--check] size-4 shrink-0 transition-opacity {{ $checked ? 'text-primary opacity-100' : 'opacity-0' }}"
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
                                <div class="grid grid-cols-2 gap-1.5">
                                    @foreach($allPayMeta as $mkey => $m)
                                    @php $bchecked = in_array($mkey, $bEnabled); @endphp
                                    <label id="pay-branch-label-{{ $branch->id }}-{{ $mkey }}"
                                           onclick="toggleBranchPayMethod({{ $branch->id }}, '{{ $mkey }}')"
                                           class="flex items-center gap-1.5 cursor-pointer p-2 rounded-lg border transition-all select-none
                                                  {{ $bchecked ? 'bg-primary/20 border-primary/50 text-primary font-semibold' : 'bg-base-100 border-base-content/10 text-base-content hover:border-base-content/20' }}">
                                        <input type="checkbox" id="pay-branch-check-{{ $branch->id }}-{{ $mkey }}" value="{{ $mkey }}" {{ $bchecked ? 'checked' : '' }} class="hidden">
                                        <span class="text-sm">{{ $m['icon'] }}</span>
                                        <span class="text-xs {{ $bchecked ? 'text-primary' : 'text-base-content' }} flex-1">{{ $m['label'] }}</span>
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

            <!-- Section 2: Change PIN -->
            <div class="config-section" style="background: #0f1c32; border-radius: 12px; padding: 24px; margin-bottom: 24px;">
                <h3 style="color: #fff; font-size: 18px; font-weight: 600; margin-bottom: 24px;">Cambiar PIN de Acceso</h3>
                
                <form id="pin-form" style="display: flex; flex-direction: column; gap: 16px;">
                    <div>
                        <label style="color: #e5e7eb; font-size: 13px; display: block; margin-bottom: 6px;">PIN Actual</label>
                        <input type="password" id="current-pin" maxlength="4" pattern="[0-9]{4}" required
                            style="width: 100%; background: #07101F; border: 1px solid #1e2a42; border-radius: 8px; padding: 12px 16px; color: #fff; font-size: 15px;"
                            placeholder="••••">
                    </div>
                    
                    <div>
                        <label style="color: #e5e7eb; font-size: 13px; display: block; margin-bottom: 6px;">PIN Nuevo</label>
                        <input type="password" id="new-pin" maxlength="4" pattern="[0-9]{4}" required
                            style="width: 100%; background: #07101F; border: 1px solid #1e2a42; border-radius: 8px; padding: 12px 16px; color: #fff; font-size: 15px;"
                            placeholder="••••">
                    </div>
                    
                    <div>
                        <label style="color: #e5e7eb; font-size: 13px; display: block; margin-bottom: 6px;">Confirmar PIN Nuevo</label>
                        <input type="password" id="confirm-pin" maxlength="4" pattern="[0-9]{4}" required
                            style="width: 100%; background: #07101F; border: 1px solid #1e2a42; border-radius: 8px; padding: 12px 16px; color: #fff; font-size: 15px;"
                            placeholder="••••">
                    </div>
                    
                    <button type="button" onclick="updatePin()"
                        style="background: #2B6FFF; color: #fff; border: none; border-radius: 8px; padding: 12px 24px; font-size: 15px; font-weight: 500; cursor: pointer; transition: all 0.2s;"
                        onmouseover="this.style.background='#1e5ce6'"
                        onmouseout="this.style.background='#2B6FFF'">
                        Guardar PIN
                    </button>
                </form>
            </div>

            <!-- Section 3: Plan Information -->
            <div class="config-section" style="background: #0f1c32; border-radius: 12px; padding: 24px;">
                <h3 style="color: #fff; font-size: 18px; font-weight: 600; margin-bottom: 24px;">Información del Plan</h3>
                
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #1e2a42;">
                        <span style="color: #94a3b8; font-size: 14px;">Plan actual</span>
                        <span style="color: #fff; font-size: 15px; font-weight: 600;">{{ $plan->name }}</span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #1e2a42;">
                        <span style="color: #94a3b8; font-size: 14px;">Productos</span>
                        <span style="color: #fff; font-size: 15px; font-weight: 600;">
                            {{ $products->count() }} de {{ $plan->products_limit }}
                        </span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #1e2a42;">
                        <span style="color: #94a3b8; font-size: 14px;">Servicios</span>
                        <span style="color: #fff; font-size: 15px; font-weight: 600;">
                            {{ $services->count() }} de {{ $plan->services_limit }}
                        </span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #1e2a42;">
                        <span style="color: #94a3b8; font-size: 14px;">Miembro desde</span>
                        <span style="color: #fff; font-size: 15px; font-weight: 600;">{{ $tenant->created_at->format('d/m/Y') }}</span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #1e2a42;">
                        <span style="color: #94a3b8; font-size: 14px;">Renovación</span>
                        <span style="color: #fff; font-size: 15px; font-weight: 600;">Por definir</span>
                    </div>
                    
                    <a href="https://syntiweb.com/planes" target="_blank" rel="noopener noreferrer"
                       style="color: #2B6FFF; font-size: 14px; font-weight: 500; text-decoration: none; margin-top: 8px; display: inline-block;"
                        onmouseover="this.style.textDecoration='underline'"
                        onmouseout="this.style.textDecoration='none'">
                        Ver planes disponibles ↗
                    </a>
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

                // Auto-close mobile sidebar drawer after navigation
                if (window.innerWidth < 1024) {
                    document.getElementById('layout-sidebar')?.classList.remove('overlay-open');
                }

                // Init SortableJS the first time the Diseño tab opens
                if (tabId === 'diseno' && !window._sortableReady) {
                    window._sortableReady = true;
                    window.initSortable();
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
                    `<strong style="color:#fff;">Has alcanzado el máximo de ${max} ${noun}</strong> del Plan <em>${planName}</em>.<br><br>` +
                    `Actualiza al Plan <strong style="color:#22c55e;">${next.name}</strong> y gestiona hasta ` +
                    `<strong style="color:#fff;">${nextQty} ${noun}</strong> en tu landing.`;
                cta.innerHTML =
                    `<a href="https://syntiweb.com/planes" target="_blank" rel="noopener noreferrer" style="background:#22c55e;color:#000;font-weight:700;text-decoration:none;` +
                    `padding:10px 22px;border-radius:8px;display:inline-block;cursor:pointer;font-size:13px;">` +
                    `\uD83D\DE80 Quiero el Plan ${next.name}</a>`;
            } else {
                // Plan 3 — last plan → contact support
                msg.innerHTML =
                    `<strong style="color:#fff;">Has alcanzado el máximo de ${max} ${noun}</strong> del Plan <em>${planName}</em>.<br><br>` +
                    `Para necesidades especiales, nuestro equipo puede diseñar una solución ` +
                    `personalizada para tu negocio. Contáctanos directamente.`;
                cta.innerHTML =
                    `<a href="${SUPPORT_WA}?text=${encodeURIComponent('Hola, soy cliente del Plan VISIÓN y necesito soporte personalizado.')}" ` +
                    `target="_blank" style="background:#25d366;color:#000;font-weight:700;text-decoration:none;` +
                    `padding:10px 22px;border-radius:8px;display:inline-block;font-size:13px;">` +
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
            if (existingContainer) existingContainer.style.display = 'none';
            
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
                existingContainer.style.display = 'block';
                
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
            slot1.style.display = availableSlots >= 1 ? 'block' : 'none';
            slot2.style.display = availableSlots >= 2 ? 'block' : 'none';
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
                            document.getElementById('product-gallery-existing').style.display = 'none';
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

        // Design Tab: Upload Logo
        async function uploadLogo(event) {
            const file = event.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('image', file);

            try {
                const response = await fetch('/tenant/{{ $tenant->id }}/upload/logo', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    },
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    // Update preview
                    const preview = document.getElementById('logo-preview');
                    const placeholder = document.getElementById('logo-placeholder');
                    
                    if (preview) {
                        preview.src = result.url + '?t=' + new Date().getTime();
                    } else if (placeholder) {
                        placeholder.parentElement.innerHTML = `<img id="logo-preview" src="${result.url}" alt="Logo" style="max-width: 100%; max-height: 100%; object-fit: contain;">`;
                    }
                    
                    alert('✓ Logo actualizado correctamente');
                } else {
                    alert('✗ Error al subir logo: ' + (result.message || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('✗ Error al subir el logo');
            }
        }

        // Design Tab: Upload Hero
        async function uploadHero(event) {
            const file = event.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('image', file);

            try {
                const response = await fetch('/tenant/{{ $tenant->id }}/upload/hero', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    },
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    // Update preview
                    const preview = document.getElementById('hero-preview');
                    const placeholder = document.getElementById('hero-placeholder');
                    
                    if (preview) {
                        preview.src = result.url + '?t=' + new Date().getTime();
                    } else if (placeholder) {
                        placeholder.parentElement.innerHTML = `<img id="hero-preview" src="${result.url}" alt="Hero" style="max-width: 100%; max-height: 100%; object-fit: cover; border-radius: 8px;">`;
                    }
                    
                    alert('✓ Imagen Hero actualizada correctamente');
                } else {
                    alert('✗ Error al subir hero: ' + (result.message || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('✗ Error al subir la imagen hero');
            }
        }

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

            // Update radio labels visually
            @foreach($plan1Networks as $k)
            document.getElementById('social-radio-label-{{ $k }}').style.border    = key === '{{ $k }}' ? '1px solid #2B6FFF' : '1px solid #1e2a42';
            document.getElementById('social-radio-label-{{ $k }}').style.background = key === '{{ $k }}' ? '#1a2f5e' : 'transparent';
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
                preview.innerHTML = '<span style="color:#6b7280; font-size:12px;">Selecciona métodos para ver la previa</span>';
                return;
            }
            preview.innerHTML = selected.map(item => 
                `<span style="display:inline-flex; align-items:center; gap:6px; padding:6px 12px; border-radius:20px;
                         background:#0d2f18; border:1px solid #22c55e60; color:#86efac; font-size:12px; font-weight:500; white-space:nowrap;">
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
                    status.style.background = enabled ? '#0d3320' : '#1e2a42';
                    status.style.borderLeftColor = enabled ? '#22c55e' : '#6b7280';
                    statusText.style.color = enabled ? '#86efac' : '#94a3b8';
                    statusText.textContent = enabled
                        ? '✅ Sección visible en tu landing pública'
                        : '⏸️ Sección oculta en tu landing pública';
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

        function editBranch(id, name, address) {
            document.getElementById('branch-edit-id').value = id;
            document.getElementById('branch-name').value = name;
            document.getElementById('branch-address').value = address;
            document.getElementById('branch-form-title').textContent = '✏️ Editar Sucursal';
            document.getElementById('branch-cancel-btn').style.display = '';
            document.getElementById('branch-form-container').style.display = '';
        }

        function cancelBranchEdit() {
            document.getElementById('branch-edit-id').value = '';
            document.getElementById('branch-name').value = '';
            document.getElementById('branch-address').value = '';
            document.getElementById('branch-form-title').textContent = '+ Agregar Sucursal';
            document.getElementById('branch-cancel-btn').style.display = 'none';

            // Hide form if at max
            if (branchCount >= 3) {
                document.getElementById('branch-form-container').style.display = 'none';
            }
        }

        async function saveBranch() {
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
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"
            integrity="sha256-ipiJrDxYBg3KPcKWCKoGLFUMjjFvFugmzOY9qbHAVQ="
            crossorigin="anonymous"></script>
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

        // ── Sortable init (called when the Diseño tab opens) ─────────
        window._sortableReady = false;
        window.initSortable = function() {
            const sortableEl = document.getElementById('sortable-sections');
            if (!sortableEl) { console.error('\u274c sortable-sections no encontrado'); return; }

            new Sortable(sortableEl, {
                handle: '.drag-handle',
                animation: 200,
                ghostClass: 'sortable-ghost',
                dragClass: 'sortable-drag',
                filter: '.no-drag',      // Ignore items without access
                preventOnFilter: false,
                onEnd: function() { saveSectionsOrder(); }
            });

            // Section toggles now use onchange="toggleSection()" directly on each input

            console.log('\u2705 SortableJS inicializado correctamente');
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