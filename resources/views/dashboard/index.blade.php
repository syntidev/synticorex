<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - {{ $tenant->business_name }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #07101F;
            color: #ffffff;
            min-height: 100vh;
        }

        /* Header */
        .dashboard-header {
            position: sticky;
            top: 0;
            background: #0a1628;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .logo {
            font-size: 20px;
            font-weight: 700;
            color: #2B6FFF;
        }

        .logo em {
            color: #ffffff;
            font-style: normal;
        }

        .business-name {
            font-size: 16px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
            padding-left: 16px;
            border-left: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-close {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: #ffffff;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-close:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        /* Navigation Tabs */
        .dashboard-nav {
            background: #0a1628;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 0 24px;
            overflow-x: auto;
        }

        .nav-tabs {
            display: flex;
            gap: 8px;
            list-style: none;
        }

        .nav-tab {
            padding: 16px 20px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.6);
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .nav-tab:hover {
            color: rgba(255, 255, 255, 0.9);
        }

        .nav-tab.active {
            color: #2B6FFF;
            border-bottom-color: #2B6FFF;
        }

        /* Mobile Bottom Navigation */
        .mobile-bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 56px;
            background: #0a1628;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            flex-direction: row;
            align-items: center;
            justify-content: space-around;
            z-index: 40;
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .mobile-bottom-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex: 1;
            height: 100%;
            cursor: pointer;
            color: rgba(255, 255, 255, 0.6);
            border: none;
            background: none;
            padding: 0;
            font-size: 24px;
            transition: color 0.2s;
            gap: 4px;
            text-decoration: none;
        }

        .mobile-bottom-nav-item:hover,
        .mobile-bottom-nav-item.active {
            color: #2B6FFF;
        }

        .mobile-bottom-nav-label {
            font-size: 11px;
            font-weight: 500;
            color: inherit;
        }

        /* Content Area */
        .dashboard-content {
            padding: 24px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Forms */
        .form-section {
            background: #0f1c32;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .form-section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #ffffff;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 16px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 8px;
        }

        .form-input,
        .form-textarea,
        .form-select {
            width: 100%;
            padding: 10px 12px;
            background: #07101F;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 6px;
            color: #ffffff;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.2s;
        }

        .form-input:focus,
        .form-textarea:focus,
        .form-select:focus {
            outline: none;
            border-color: #2B6FFF;
        }

        .form-textarea {
            min-height: 100px;
            resize: vertical;
        }

        /* Buttons */
        .btn-primary {
            background: #2B6FFF;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-primary:hover {
            background: #1e5beb;
        }

        .btn-secondary {
            background: #0f1c32;
            color: #2B6FFF;
            border: 1px solid #2B6FFF;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-secondary:hover {
            background: #2B6FFF;
            color: #ffffff;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 20px;
        }

        /* Placeholder content */
        .placeholder-content {
            text-align: center;
            padding: 60px 20px;
            color: rgba(255, 255, 255, 0.5);
        }

        .placeholder-content h3 {
            font-size: 20px;
            margin-bottom: 8px;
            color: rgba(255, 255, 255, 0.7);
        }

        /* Table */
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .table-title {
            font-size: 18px;
            font-weight: 600;
        }

        .table-subtitle {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 4px;
        }

        .table-container {
            background: #0f1c32;
            border-radius: 12px;
            overflow: hidden;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table thead {
            background: #0a1628;
        }

        .data-table th {
            padding: 12px 16px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.7);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .data-table td {
            padding: 12px 16px;
            font-size: 14px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .data-table tbody tr:hover {
            background: rgba(43, 111, 255, 0.05);
        }

        .product-image {
            width: 48px;
            height: 48px;
            border-radius: 6px;
            object-fit: cover;
            background: #07101F;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-hot {
            background: #ff4444;
            color: #ffffff;
        }

        .badge-new {
            background: #00cc66;
            color: #ffffff;
        }

        .badge-promo {
            background: #ff9900;
            color: #ffffff;
        }

        .status-indicator {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .status-dot.active {
            background: #00cc66;
        }

        .status-dot.inactive {
            background: #666;
        }

        .btn-icon {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.6);
            cursor: pointer;
            padding: 6px;
            border-radius: 4px;
            transition: all 0.2s;
            font-size: 14px;
        }

        .btn-icon:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #2B6FFF;
        }

        .btn-danger:hover {
            color: #ff4444;
        }

        .btn-add {
            background: #2B6FFF;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-add:hover:not(:disabled) {
            background: #1e5beb;
        }

        .btn-add:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* CRUD Modals — prefixed to avoid FlyonUI .modal class conflict */
        .crud-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.75);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .crud-overlay.show {
            display: flex;
        }

        .crud-dialog {
            background: #0f1c32;
            border-radius: 12px;
            width: 100%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            z-index: 10000;
            box-shadow: 0 25px 60px rgba(0,0,0,0.6);
        }

        .crud-dialog-header {
            padding: 20px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .crud-dialog-title {
            font-size: 18px;
            font-weight: 600;
            color: #fff;
        }

        .crud-dialog-close {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.6);
            font-size: 24px;
            cursor: pointer;
            padding: 0;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .crud-dialog-close:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }

        .crud-dialog-body {
            padding: 24px;
        }

        .image-preview {
            margin-bottom: 16px;
            text-align: center;
        }

        .image-preview img {
            max-width: 200px;
            max-height: 200px;
            border-radius: 8px;
            object-fit: cover;
        }

        /* Gallery thumbnail styles (Plan 3) */
        .gallery-thumb {
            position: relative;
            display: inline-block;
        }

        .gallery-thumb img {
            width: 100px;
            height: 100px;
            border-radius: 6px;
            object-fit: cover;
            border: 2px solid rgba(255,255,255,0.15);
        }

        .gallery-thumb-delete {
            position: absolute;
            top: -6px;
            right: -6px;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #ef4444;
            color: #fff;
            border: none;
            font-size: 13px;
            line-height: 22px;
            text-align: center;
            cursor: pointer;
            padding: 0;
        }

        .gallery-thumb-delete:hover {
            background: #dc2626;
        }

        .gallery-preview-thumb img {
            width: 80px;
            height: 80px;
            border-radius: 6px;
            object-fit: cover;
            border: 2px dashed rgba(255,255,255,0.3);
        }

        /* Icon Picker */
        #icon-picker-grid {
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.15) transparent;
        }
        #icon-picker-grid::-webkit-scrollbar { width: 5px; }
        #icon-picker-grid::-webkit-scrollbar-track { background: transparent; }
        #icon-picker-grid::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 3px; }

        .icon-pick-item:hover {
            background: rgba(43,111,255,0.15) !important;
            border-color: rgba(43,111,255,0.4) !important;
            transform: translateY(-1px);
        }

        /* Service mode tabs */
        .svc-mode-bar {
            display: flex;
            gap: 0;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.1);
            flex-shrink: 0;
        }
        .svc-mode-bar button {
            flex: 1;
            padding: 8px 18px;
            font-size: 12px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: all .2s;
        }

        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 48px;
            height: 24px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #374151;
            transition: 0.3s;
            border-radius: 24px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
        }

        .toggle-switch input:checked + .toggle-slider {
            background-color: #2B6FFF;
        }

        .toggle-switch input:checked + .toggle-slider:before {
            transform: translateX(24px);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: rgba(255, 255, 255, 0.5);
        }

        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 16px;
        }

        /* ═══════════════════════════════════════════════════════════════════════ */
        /* RESPONSIVE BREAKPOINTS (Mobile-First) */
        /* ═══════════════════════════════════════════════════════════════════════ */

        /* Mobile S: 320-374px */
        @media (max-width: 374px) {
            .dashboard-container { padding: 12px; }
            .dashboard-header { padding: 12px 16px; }
            .nav-tabs { gap: 4px; }
            .nav-tab { padding: 8px 10px; font-size: 12px; height: 44px; }
            .dashboard-content { padding: 12px; }
            .form-section { padding: 16px; margin-bottom: 16px; }
            .form-section-title { font-size: 16px; margin-bottom: 16px; }
            .form-grid { grid-template-columns: 1fr; }
            .btn-primary, .btn-secondary { min-height: 44px; padding: 10px 12px; }
        }

        /* Mobile M/L: 375-639px */
        @media (max-width: 639px) {
            .dashboard-container { padding: 16px; }
            .dashboard-header {
                flex-wrap: wrap;
                gap: 12px;
                padding: 16px;
                min-height: 56px;
            }

            .header-left {
                flex-wrap: wrap;
                flex: 1;
            }

            .business-name {
                padding-left: 0;
                border-left: none;
                width: 100%;
            }

            .btn-close {
                min-height: 44px;
                min-width: 44px;
                padding: 10px;
                align-self: flex-start;
            }

            /* Navigation Tabs - Hide (use bottom nav instead) */
            .dashboard-nav {
                display: none !important;
            }

            /* Show Mobile Bottom Nav */
            .mobile-bottom-nav {
                display: flex;
            }

            /* Responsive Content Area */
            .dashboard-content {
                padding: 16px;
                padding-bottom: 80px; /* Space for bottom nav */
            }

            .tab-content { display: none; }
            .tab-content.active { display: block; }

            /* Forms */
            .form-section { padding: 16px; margin-bottom: 16px; }
            .form-section-title { font-size: 16px; margin-bottom: 12px; }
            .form-grid { grid-template-columns: 1fr; gap: 12px; }
            .form-group { margin-bottom: 12px; }
            .form-input, .form-textarea, .form-select { padding: 10px 12px; }
            .form-actions { flex-wrap: wrap; }

            /* Tables → Card Layout */
            .data-table {
                border-collapse: collapse;
            }

            .data-table thead {
                display: none;
            }

            .data-table tbody tr {
                display: block;
                margin-bottom: 12px;
                background: #0f1c32;
                border-radius: 8px;
                padding: 12px;
                border: 1px solid rgba(255,255,255,0.1);
            }

            .data-table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 8px 0;
                border-bottom: 1px solid rgba(255,255,255,0.05);
                font-size: 13px;
            }

            .data-table td:last-child {
                border-bottom: none;
            }

            .data-table td::before {
                content: attr(data-label);
                font-weight: 600;
                color: rgba(255,255,255,0.6);
                flex-basis: 90px;
                flex-shrink: 0;
                font-size: 12px;
            }

            /* Product image in card */
            .product-image {
                width: 48px;
                height: 48px;
                object-fit: cover;
                border-radius: 4px;
                aspect-ratio: 1;
            }

            /* Buttons */
            .btn-primary, .btn-secondary {
                min-height: 44px;
                min-width: 44px;
                padding: 10px 12px;
                font-size: 13px;
            }

            /* Icons in table actions */
            .action-button {
                min-height: 44px;
                min-width: 44px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 18px;
            }

            /* Grid layouts */
            .theme-grid { grid-template-columns: repeat(2, 1fr) !important; }
        }

        /* Tablet portrait: 640-767px */
        @media (max-width: 767px) {
            .dashboard-container { padding: 20px; }
            .dashboard-nav { padding: 0 16px; }
            .nav-tabs { gap: 6px; }
            .nav-tab { padding: 12px 16px; font-size: 13px; height: 44px; }
            .dashboard-content { padding: 20px; }
            .form-grid { grid-template-columns: repeat(2, 1fr); gap: 14px; }
            .form-section { padding: 18px; margin-bottom: 18px; }
            .theme-grid { grid-template-columns: repeat(3, 1fr) !important; }
        }

        /* Tablet landscape / Laptop: 768-1023px */
        @media (max-width: 1023px) {
            .dashboard-nav { padding: 0 20px; }
            .dashboard-content { padding: 24px; }
            .form-grid { grid-template-columns: repeat(2, 1fr); }
            .sidebar-info { flex-direction: column; }
            .theme-grid { grid-template-columns: repeat(4, 1fr) !important; }
        }

        /* Desktop: 1024px+ */
        @media (min-width: 1024px) {
            .dashboard-nav {
                display: flex !important;
                padding: 0 24px;
            }

            /* Hide Mobile Bottom Nav on Desktop */
            .mobile-bottom-nav {
                display: none !important;
            }

            .dashboard-content {
                max-width: 1400px;
                margin: 0 auto;
                padding: 24px;
                padding-bottom: 24px;
            }

            .form-grid { grid-template-columns: repeat(3, 1fr); }
            .data-table tbody tr { display: table-row !important; }
            .data-table td::before { display: none !important; }
            .data-table thead { display: table-header-group !important; }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="dashboard-header">
        <div class="header-left">
            <span class="logo">SYNTI<em>web</em></span>
            <span class="business-name">{{ $tenant->business_name }}</span>
        </div>
        <a href="/{{ $tenant->subdomain }}" class="btn-close">Cerrar ✕</a>
    </header>

    {{-- ── Plan Expiry Notices ──────────────────────────────────────────── --}}
    @if($isFrozen)
    {{-- FROZEN: subscription expired, in 30-day grace period --}}
    <div style="background: #3b0a0a; border-bottom: 2px solid #ef4444; padding: 14px 24px; display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
        <span style="font-size: 22px; flex-shrink: 0;">🔴</span>
        <div style="flex: 1; min-width: 0;">
            <p style="color: #fca5a5; font-size: 14px; font-weight: 700; margin-bottom: 3px;">
                Tu plan venció — tu landing pública está pausada
            </p>
            <p style="color: #f87171; font-size: 13px;">
                @if($graceRemainingDays !== null && $graceRemainingDays > 0)
                    Tienes <strong>{{ $graceRemainingDays }} día{{ $graceRemainingDays === 1 ? '' : 's' }}</strong> de gracia antes de que tu cuenta se archive. Ningún dato se borra.
                @else
                    El período de gracia ha vencido. Contacta a soporte para renovar y restaurar tu sitio.
                @endif
            </p>
        </div>
        <a href="mailto:soporte@synticorex.com"
           style="flex-shrink: 0; background: #ef4444; color: #fff; border: none; border-radius: 8px; padding: 10px 20px; font-size: 14px; font-weight: 600; text-decoration: none; white-space: nowrap;">
            Renovar plan
        </a>
    </div>
    @elseif($isExpiringSoon && $daysUntilExpiry !== null)
    {{-- EXPIRING SOON: 30 days or fewer remaining --}}
    <div style="background: #431407; border-bottom: 2px solid #f97316; padding: 14px 24px; display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
        <span style="font-size: 22px; flex-shrink: 0;">⚠️</span>
        <div style="flex: 1; min-width: 0;">
            <p style="color: #fed7aa; font-size: 14px; font-weight: 700; margin-bottom: 3px;">
                Tu plan vence en {{ $daysUntilExpiry }} día{{ $daysUntilExpiry === 1 ? '' : 's' }}
            </p>
            <p style="color: #fdba74; font-size: 13px;">
                Renueva antes del <strong>{{ $tenant->subscription_ends_at->format('d/m/Y') }}</strong> para mantener tu landing pública activa sin interrupciones.
            </p>
        </div>
        <a href="mailto:soporte@synticorex.com"
           style="flex-shrink: 0; background: #f97316; color: #fff; border: none; border-radius: 8px; padding: 10px 20px; font-size: 14px; font-weight: 600; text-decoration: none; white-space: nowrap;">
            Renovar ahora
        </a>
    </div>
    @endif
    {{-- ── End Plan Expiry Notices ─────────────────────────────────────── --}}

    <!-- Navigation Tabs -->
    <nav class="dashboard-nav">
        <ul class="nav-tabs">
            <li class="nav-tab active" data-tab="info">📋 Info</li>
            <li class="nav-tab" data-tab="productos">📦 Productos</li>
            <li class="nav-tab" data-tab="servicios">🛠️ Servicios</li>
            <li class="nav-tab" data-tab="diseno">🎨 Diseño</li>
            <li class="nav-tab" data-tab="analytics">📊 Analytics</li>
            @if($plan->id === 3)
            <li class="nav-tab" data-tab="sucursales">📍 Sucursales</li>
            @endif
            <li class="nav-tab" data-tab="config">⚙️ Config</li>
        </ul>
    </nav>

    <!-- Content -->
    <main class="dashboard-content">
        
        <!-- Tab: Info -->
        <div id="tab-info" class="tab-content active">
            <form id="form-info" onsubmit="saveInfo(event)">
                <div class="form-section">
                    <h2 class="form-section-title">Información del Negocio</h2>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Nombre del Negocio</label>
                            <input type="text" class="form-input" name="business_name" value="{{ $tenant->business_name }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Subdominio</label>
                            <input type="text" class="form-input" value="{{ $tenant->subdomain }}" disabled>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Eslogan</label>
                            <input type="text" class="form-input" name="slogan" value="{{ $tenant->slogan }}">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Teléfono</label>
                            <input type="text" class="form-input" name="phone" value="{{ $tenant->phone }}">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">WhatsApp Ventas</label>
                            <input type="text" class="form-input" name="whatsapp_sales" value="{{ $tenant->whatsapp_sales }}">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-input" name="email" value="{{ $tenant->email }}">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Dirección</label>
                            <input type="text" class="form-input" name="address" value="{{ $tenant->address }}">
                        </div>
                        
                        @if($tenant->plan_id >= 2)
                        <div class="form-group">
                            <label class="form-label flex items-center gap-2">
                                <span>Título sección Contacto</span>
                                <span style="font-size:11px; color:#22c55e; font-weight:600; background:rgba(34,197,94,.12); padding:2px 8px; border-radius:20px;">Plan {{ $plan->name }}</span>
                            </label>
                            <input type="text"
                                   name="contact_title"
                                   class="form-input"
                                   value="{{ data_get($tenant->settings, 'business_info.contact.title', '') }}"
                                   placeholder="Contáctanos">
                        </div>
                        <div class="form-group">
                            <label class="form-label flex items-center gap-2">
                                <span>Subtítulo sección Contacto</span>
                                <span style="font-size:11px; color:#22c55e; font-weight:600; background:rgba(34,197,94,.12); padding:2px 8px; border-radius:20px;">Plan {{ $plan->name }}</span>
                            </label>
                            <input type="text"
                                   name="contact_subtitle"
                                   class="form-input"
                                   value="{{ data_get($tenant->settings, 'business_info.contact.subtitle', '') }}"
                                   placeholder="Estamos aquí para atenderte">
                        </div>
                        <div class="form-group">
                            <label class="form-label flex items-center gap-2">
                                <span>URL Google Maps</span>
                                <span style="font-size:11px; color:#22c55e; font-weight:600; background:rgba(34,197,94,.12); padding:2px 8px; border-radius:20px;">Plan {{ $plan->name }}</span>
                            </label>
                            <input type="url"
                                   name="contact_maps_url"
                                   class="form-input"
                                   value="{{ data_get($tenant->settings, 'business_info.contact.maps_url', '') }}"
                                   placeholder="https://www.google.com/maps/embed?pb=...">
                        </div>
                        <div class="form-group">
                            <label class="form-label flex items-center gap-2">
                                <span>Teléfono Secundario</span>
                                <span style="font-size:11px; color:#22c55e; font-weight:600; background:rgba(34,197,94,.12); padding:2px 8px; border-radius:20px;">Plan {{ $plan->name }}</span>
                            </label>
                            <input type="tel"
                                   name="phone_secondary"
                                   class="form-input"
                                   value="{{ data_get($tenant->settings, 'contact_info.phone_secondary', '') }}"
                                   placeholder="+58 XXX XXXXXXX">
                        </div>
                        @endif

                        <div class="form-group">
                            <label class="form-label">Ciudad</label>
                            <input type="text" class="form-input" name="city" value="{{ $tenant->city }}">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-textarea" name="description">{{ $tenant->description }}</textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Guardar Cambios</button>
                        <button type="button" class="btn-secondary" onclick="resetForm('form-info')">Cancelar</button>
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

            <div class="table-header">
                <div>
                    <h2 class="table-title">📦 Productos</h2>
                    <p class="table-subtitle">{{ $currentCount }} de {{ $maxProducts }} productos</p>
                </div>
                <button 
                    class="btn-add" 
                    onclick="checkAndOpenProductModal()"
                    title="Agregar nuevo producto">
                    + Agregar Producto
                </button>
            </div>

            @if($currentCount >= $maxProducts)
            <div style="background: linear-gradient(135deg, #1a2040 0%, #0f1c32 100%); border: 1px solid rgba(43,111,255,0.3); border-radius: 12px; padding: 16px 20px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span style="font-size: 22px;">📦</span>
                    <div>
                        <p style="color: #fff; font-weight: 600; font-size: 14px; margin: 0 0 2px;">Límite de productos alcanzado ({{ $maxProducts }}/{{ $maxProducts }})</p>
                        <p style="color: rgba(255,255,255,0.5); font-size: 12px; margin: 0;">
                            @if($plan->id === 1)Plan CRECIMIENTO: hasta 12 productos · Plan VISIÓN: hasta 18 productos
                            @else Plan VISIÓN: hasta 18 productos @endif
                        </p>
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

            @if($products->count() > 0)
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Precio USD</th>
                            <th>Badge</th>
                            <th>Activo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td data-label="Imagen">
                                @if($product->image_filename)
                                    <img src="{{ asset('storage/tenants/' . $tenant->id . '/' . $product->image_filename) }}"
                                         alt="{{ $product->name }}"
                                         class="product-image">
                                @else
                                    <div class="product-image" style="background: #374151;"></div>
                                @endif
                            </td>
                            <td data-label="Nombre">
                                <strong>{{ $product->name }}</strong>
                                @if($product->is_featured)
                                    <span style="color: #ffd700; margin-left: 4px;">⭐</span>
                                @endif
                            </td>
                            <td data-label="Precio USD">${{ number_format($product->price_usd, 2) }}</td>
                            <td data-label="Badge">
                                @if($product->badge === 'hot')
                                    <span class="badge badge-hot">🔥 Hot</span>
                                @elseif($product->badge === 'new')
                                    <span class="badge badge-new">✨ New</span>
                                @elseif($product->badge === 'promo')
                                    <span class="badge badge-promo">🎉 Promo</span>
                                @else
                                    <span style="color: rgba(255,255,255,0.3);">-</span>
                                @endif
                            </td>
                            <td data-label="Activo">
                                <span class="status-indicator">
                                    <span class="status-dot {{ $product->is_active ? 'active' : 'inactive' }}"></span>
                                    {{ $product->is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td data-label="Acciones" style="gap: 8px;">
                                <button class="action-button btn-icon" onclick="editProduct({{ $product->id }})" title="Editar" aria-label="Editar producto">
                                    ✏️
                                </button>
                                <button class="action-button btn-icon btn-danger" onclick="deleteProduct({{ $product->id }})" title="Eliminar" aria-label="Eliminar producto">
                                    🗑️
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <div class="empty-state-icon">📦</div>
                <h3>No hay productos aún</h3>
                <p>Comienza agregando tu primer producto</p>
            </div>
            @endif
        </div>

        <!-- Modal: Producto -->
        <div id="product-modal" class="crud-overlay">
            <div class="crud-dialog">
                <div class="crud-dialog-header">
                    <h3 class="crud-dialog-title" id="product-modal-title">Agregar Producto</h3>
                    <button class="crud-dialog-close" onclick="closeProductModal()">&times;</button>
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
                            <p style="font-size: 12px; color: rgba(255,255,255,0.5); margin-top: 4px;">
                                Máx. 2MB, se redimensionará a 800px
                            </p>
                        </div>

                        {{-- Gallery Section — Plan 3 (VISIÓN) only --}}
                        @if($plan->id === 3)
                        <div class="form-section" id="product-gallery-section">
                            <label class="form-label">📸 Galería Adicional <span style="font-size: 12px; color: rgba(255,255,255,0.5);">(máx. 2 fotos extra — Plan Visión)</span></label>

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

                            <p style="font-size: 12px; color: rgba(255,255,255,0.5); margin-top: 6px;">
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
            <div style="background: rgba(43,111,255,0.05); border: 1px solid rgba(43,111,255,0.15); border-radius: 12px; padding: 16px 20px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
                <div>
                    <p style="color: #fff; font-weight: 600; font-size: 14px; margin-bottom: 4px;">🎨 Modo visual de servicios</p>
                    <p style="color: rgba(255,255,255,0.4); font-size: 12px;">Elige entre íconos o imágenes — mantén la coherencia estética</p>
                </div>
                <div class="svc-mode-bar">
                    <button type="button" id="global-mode-icon-btn" onclick="setGlobalServiceMode('icon')">🎨 Ícono</button>
                    <button type="button" id="global-mode-image-btn" onclick="setGlobalServiceMode('image')">🖼️ Imagen</button>
                </div>
            </div>
            @endif

            <div class="table-header">
                <div>
                    <h2 class="table-title">🛠️ Servicios</h2>
                    <p class="table-subtitle">{{ $currentServiceCount }} de {{ $maxServices }} servicios</p>
                </div>
                <button 
                    class="btn-add" 
                    onclick="checkAndOpenServiceModal()"
                    title="Agregar nuevo servicio">
                    + Agregar Servicio
                </button>
            </div>

            @if($currentServiceCount >= $maxServices)
            <div style="background: linear-gradient(135deg, #1a2040 0%, #0f1c32 100%); border: 1px solid rgba(43,111,255,0.3); border-radius: 12px; padding: 16px 20px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span style="font-size: 22px;">🛠️</span>
                    <div>
                        <p style="color: #fff; font-weight: 600; font-size: 14px; margin: 0 0 2px;">Límite de servicios alcanzado ({{ $currentServiceCount }}/{{ $maxServices }})</p>
                        <p style="color: rgba(255,255,255,0.5); font-size: 12px; margin: 0;">
                            @if($plan->id === 1)Plan CRECIMIENTO: hasta 6 servicios · Plan VISIÓN: hasta 9 servicios
                            @else Plan VISIÓN: hasta 9 servicios @endif
                        </p>
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

            @if($services->count() > 0)
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Activo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($services as $service)
                        <tr>
                            <td data-label="Imagen">
                                @if($service->image_filename)
                                    <img src="{{ asset('storage/tenants/' . $tenant->id . '/' . $service->image_filename) }}"
                                         alt="{{ $service->name }}"
                                         class="product-image">
                                @elseif($service->icon_name)
                                    <div class="product-image" style="background: linear-gradient(135deg, rgba(59,130,246,0.15) 0%, rgba(255,107,0,0.08) 100%); display: flex; align-items: center; justify-content: center; border: 1px solid rgba(59,130,246,0.25); border-radius: 12px; transition: all 0.2s;">
                                        <span class="iconify tabler--{{ str_replace('_', '-', $service->icon_name) }}" style="font-size: 40px; color: #3b82f6;"></span>
                                    </div>
                                @else
                                    <div class="product-image" style="background: rgba(255,255,255,0.04); display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255,255,255,0.08); border-radius: 12px;">
                                        <span class="iconify tabler--settings" style="font-size: 40px; color: rgba(255,255,255,0.3);"></span>
                                    </div>
                                @endif
                            </td>
                            <td data-label="Nombre"><strong>{{ $service->name }}</strong></td>
                            <td data-label="Descripción">
                                @if($service->description)
                                    {{ Str::limit($service->description, 60) }}
                                @else
                                    <span style="color: rgba(255,255,255,0.3);">-</span>
                                @endif
                            </td>
                            <td data-label="Activo">
                                <span class="status-indicator">
                                    <span class="status-dot {{ $service->is_active ? 'active' : 'inactive' }}"></span>
                                    {{ $service->is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td data-label="Acciones" style="gap: 8px;">
                                <button class="action-button btn-icon" onclick="editService({{ $service->id }})" title="Editar" aria-label="Editar servicio">
                                    ✏️
                                </button>
                                <button class="action-button btn-icon btn-danger" onclick="deleteService({{ $service->id }})" title="Eliminar" aria-label="Eliminar servicio">
                                    🗑️
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <div class="empty-state-icon">🛠️</div>
                <h3>No hay servicios aún</h3>
                <p>Comienza agregando tu primer servicio</p>
            </div>
            @endif
        </div>

        <!-- Modal: Servicio -->
        <div id="service-modal" class="crud-overlay">
            <div class="crud-dialog" style="max-width: 700px; border-radius: 16px; background: #1a1f2e; border: 1px solid #2d3748; box-shadow: 0 20px 60px rgba(0,0,0,0.5);">
                <div class="crud-dialog-header" style="padding: 24px 28px; border-bottom: 1px solid #2d3748; background: linear-gradient(135deg, rgba(59,130,246,0.05) 0%, rgba(255,107,0,0.02) 100%);">
                    <h3 class="crud-dialog-title" id="service-modal-title" style="font-size: 18px; font-weight: 700; color: #e2e8f0; margin: 0;">Agregar Servicio</h3>
                    <button class="crud-dialog-close" onclick="closeServiceModal()" style="position: absolute; top: 16px; right: 16px; width: 36px; height: 36px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; cursor: pointer; color: #e2e8f0; font-size: 20px; transition: all 0.2s;" onmouseover="this.style.background='rgba(59,130,246,0.15)'" onmouseout="this.style.background='rgba(255,255,255,0.05)'">×</button>
                </div>
                <div class="crud-dialog-body" style="padding: 28px;">
                    <form id="service-form" onsubmit="saveService(event)">
                        <input type="hidden" id="service-id">
                        <input type="hidden" id="service-icon-name">

                        {{-- Mode tabs: Plan 2/3 only --}}
                        @if($plan->id !== 1)
                        <div style="display: flex; gap: 0; margin-bottom: 24px; border-radius: 12px; overflow: hidden; border: 1px solid #2d3748; background: #0f1419;">
                            <button type="button" id="svc-tab-icon" onclick="setServiceModalMode('icon')"
                                style="flex: 1; padding: 12px; border: none; cursor: pointer; font-weight: 700; font-size: 13px; transition: all .2s; background: #2d3748; color: #e2e8f0;">
                                🎨 Ícono
                            </button>
                            <button type="button" id="svc-tab-image" onclick="setServiceModalMode('image')"
                                style="flex: 1; padding: 12px; border: none; cursor: pointer; font-weight: 700; font-size: 13px; transition: all .2s; background: transparent; color: rgba(255,255,255,0.4);">
                                🖼️ Imagen
                            </button>
                        </div>
                        @endif

                        {{-- ICON PICKER (Plan 1: always; Plan 2/3: when icon mode) --}}
                        <div id="svc-section-icon" class="form-section" style="margin-bottom: 28px;">
                            <label class="form-label" style="font-weight: 600; color: #e2e8f0; margin-bottom: 16px; display: block; font-size: 14px;">Ícono del Servicio</label>

                            {{-- Current selection preview --}}
                            <div style="display: flex; flex-direction: column; align-items: center; padding: 28px; background: linear-gradient(135deg, rgba(59,130,246,0.08) 0%, rgba(255,107,0,0.04) 100%); border-radius: 16px; margin-bottom: 24px; border: 1px solid rgba(59,130,246,0.2);">
                                <div style="width: 88px; height: 88px; background: rgba(59,130,246,0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-bottom: 16px; border: 2px solid rgba(59,130,246,0.3);">
                                    <span id="icon-preview-el" class="iconify tabler--settings text-blue-400" style="font-size: 48px; color: #60a5fa;"></span>
                                </div>
                                <p id="icon-preview-label" style="color: #e2e8f0; font-weight: 600; font-size: 14px; margin: 0; text-align: center;">Sin ícono seleccionado</p>
                                <p style="color: rgba(255,255,255,0.4); font-size: 12px; margin-top: 4px; text-align: center;">Busca y selecciona un ícono</p>
                            </div>

                            {{-- Search --}}
                            <input type="text" id="icon-search" class="form-input"
                                placeholder="🔍 Busca: scissors, camera, truck, heart..."
                                oninput="filterIcons(this.value)" autocomplete="off"
                                style="margin-bottom: 16px; padding: 12px 16px; background: #0f1419; border: 1px solid #2d3748; border-radius: 10px; color: #e2e8f0; font-size: 14px;" onmouseover="this.style.borderColor='rgba(59,130,246,0.4)'" onmouseout="this.style.borderColor='#2d3748'">

                            {{-- Icon Grid --}}
                            <div id="icon-picker-grid" style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 12px; max-height: 320px; overflow-y: auto; padding: 4px; border-radius: 12px; background: #0f1419; padding: 12px; border: 1px solid #2d3748;"></div>
                            <p style="color: rgba(255,255,255,0.25); font-size: 12px; margin-top: 12px; text-align: center;">60+ iconos disponibles</p>
                            
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
                            <p style="font-size: 12px; color: rgba(255,255,255,0.5); margin-top: 4px;">Máx. 2MB, se redimensionará a 800px</p>
                        </div>
                        @endif

                        <div class="form-section" style="margin-bottom: 24px;">
                            <label for="service-name" class="form-label" style="font-weight: 600; color: #e2e8f0; margin-bottom: 8px; display: block; font-size: 14px;">Nombre *</label>
                            <input type="text" id="service-name" class="form-input" required maxlength="100" style="padding: 12px 16px; background: #0f1419; border: 1px solid #2d3748; border-radius: 10px; color: #e2e8f0; font-size: 14px;" onmouseover="this.style.borderColor='rgba(59,130,246,0.4)'" onmouseout="this.style.borderColor='#2d3748'">
                        </div>

                        <div class="form-section" style="margin-bottom: 24px;">
                            <label for="service-description" class="form-label" style="font-weight: 600; color: #e2e8f0; margin-bottom: 8px; display: block; font-size: 14px;">Descripción</label>
                            <textarea id="service-description" class="form-input" rows="3" maxlength="500" style="padding: 12px 16px; background: #0f1419; border: 1px solid #2d3748; border-radius: 10px; color: #e2e8f0; font-size: 14px; font-family: inherit;" onmouseover="this.style.borderColor='rgba(59,130,246,0.4)'" onmouseout="this.style.borderColor='#2d3748'"></textarea>
                        </div>

                        <div class="form-section" style="margin-bottom: 28px;">
                            <label class="form-label" style="font-weight: 600; color: #e2e8f0; margin-bottom: 8px; display: block; font-size: 14px;">Servicio Activo</label>
                            <label class="toggle-switch" style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                                <input type="checkbox" id="service-is-active" checked>
                                <span class="toggle-slider"></span>
                                <span style="color: rgba(255,255,255,0.6); font-size: 13px;">Mostrar en landing page</span>
                            </label>
                        </div>

                        <div class="form-actions" style="display: flex; gap: 12px; padding-top: 24px; border-top: 1px solid #2d3748; justify-content: flex-end;">
                            <button type="button" class="btn-secondary" onclick="closeServiceModal()" style="padding: 11px 24px; background: rgba(255,255,255,0.05); border: 1px solid #2d3748; border-radius: 10px; color: #e2e8f0; font-weight: 600; font-size: 14px; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.08)'; this.style.borderColor='rgba(255,255,255,0.15)'" onmouseout="this.style.background='rgba(255,255,255,0.05)'; this.style.borderColor='#2d3748'">Cancelar</button>
                            <button type="submit" class="btn-primary" style="padding: 11px 28px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border: none; border-radius: 10px; color: #fff; font-weight: 600; font-size: 14px; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(59,130,246,0.3);" onmouseover="this.style.boxShadow='0 6px 16px rgba(59,130,246,0.4)'; this.style.transform='translateY(-1px)'" onmouseout="this.style.boxShadow='0 4px 12px rgba(59,130,246,0.3)'; this.style.transform='translateY(0)'">Guardar Servicio</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal: Límite de Plan -->
        <div id="limit-modal" class="crud-overlay">
            <div class="crud-dialog" style="max-width: 480px;">
                <div class="crud-dialog-header">
                    <h3 class="crud-dialog-title" id="limit-modal-title">⚠️ Límite Alcanzado</h3>
                    <button class="crud-dialog-close" onclick="closeLimitModal()">&times;</button>
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
        <h3 style="font-size: 11px; font-weight: 700; color: rgba(255,255,255,0.4); margin-bottom: 12px; text-transform: uppercase; letter-spacing: 1.5px;">
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
                        $availableSections = [
                            'products'        => ['label' => 'Productos',       'icon' => 'tabler:shopping-cart', 'plan' => 1],
                            'services'        => ['label' => 'Servicios',       'icon' => 'tabler:tool',          'plan' => 1],
                            'contact'         => ['label' => 'Contacto',        'icon' => 'tabler:map-pin',       'plan' => 1],
                            'payment_methods' => ['label' => 'Medios de Pago', 'icon' => 'tabler:credit-card',   'plan' => 2],
                            'faq'             => ['label' => 'FAQ',             'icon' => 'tabler:help-circle',   'plan' => 3],
                            'branches'        => ['label' => 'Sucursales',      'icon' => 'tabler:building-bank', 'plan' => 3],
                        ];

                        $currentOrder = $customization->visual_effects['sections_order'] ?? [];

                        // Ordenar $availableSections según $currentOrder
                        if (!empty($currentOrder)) {
                            $orderedKeys = collect($currentOrder)->pluck('name')->toArray();
                            $sortedSections = [];
                            foreach ($orderedKeys as $k) {
                                if (isset($availableSections[$k])) {
                                    $sortedSections[$k] = $availableSections[$k];
                                }
                            }
                            // Agregar las que no están en el orden guardado al final
                            foreach ($availableSections as $k => $v) {
                                if (!isset($sortedSections[$k])) {
                                    $sortedSections[$k] = $v;
                                }
                            }
                            $availableSections = $sortedSections;
                        }
                    @endphp

                    @foreach($availableSections as $key => $section)
                        @php
                            $sectionData   = collect($currentOrder)->firstWhere('name', $key);
                            $isVisible     = $sectionData['visible'] ?? true;
                            $planRequired  = $section['plan'];
                            $hasAccess     = $tenant->plan_id >= $planRequired;
                        @endphp

                        <div class="section-item {{ $hasAccess ? '' : 'opacity-50' }}"
                             data-section="{{ $key }}"
                             data-plan="{{ $planRequired }}"
                             style="margin-bottom: 8px;">
                            <div style="display: flex; align-items: center; gap: 12px; padding: 12px 14px;
                                        background: #0f1c32; border-radius: 10px;
                                        border: 1px solid {{ $hasAccess ? 'rgba(255,255,255,0.08)' : 'rgba(255,255,255,0.04)' }};
                                        {{ $hasAccess ? 'cursor: move;' : 'cursor: not-allowed;' }}
                                        transition: border-color 0.2s;">

                                {{-- Drag handle / Lock --}}
                                @if($hasAccess)
                                    <span class="drag-handle" style="display: inline-flex; align-items: center; justify-content: center; cursor: grab; flex-shrink: 0; width: 24px; height: 24px; color: #4ade80; font-weight: bold; font-size: 16px; user-select: none;">≡</span>
                                @else
                                    <span style="display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; width: 24px; height: 24px; color: #f59e0b; font-weight: bold; font-size: 16px;">🔒</span>
                                @endif

                                {{-- Section icon --}}
                                <iconify-icon icon="{{ $section['icon'] }}"
                                              style="color: #4d8dff; flex-shrink: 0;"
                                              width="20"></iconify-icon>

                                {{-- Label --}}
                                <span style="flex: 1; font-weight: 500; font-size: 14px; color: {{ $hasAccess ? '#fff' : 'rgba(255,255,255,0.4)' }};">
                                    {{ $section['label'] }}
                                    @if(!$hasAccess)
                                        <span style="font-size: 11px; color: #f59e0b; margin-left: 8px;">
                                            (Plan {{ $planRequired == 2 ? 'CRECIMIENTO' : 'VISIÓN' }})
                                        </span>
                                    @endif
                                </span>

                                {{-- Toggle visible --}}
                                @if($hasAccess)
                                    <label style="position: relative; display: inline-flex; align-items: center; cursor: pointer; flex-shrink: 0;">
                                        <input type="checkbox"
                                               class="sr-only peer section-toggle"
                                               data-section="{{ $key }}"
                                               {{ $isVisible ? 'checked' : '' }}>
                                        <div style="width: 44px; height: 24px; background: rgba(255,255,255,0.1); border-radius: 12px;
                                                    position: relative; transition: background 0.2s;"
                                             class="toggle-track">
                                            <div style="position: absolute; top: 2px; left: 2px; width: 20px; height: 20px;
                                                        background: {{ $isVisible ? '#2B6FFF' : 'rgba(255,255,255,0.4)' }};
                                                        border-radius: 50%; transition: all 0.2s;
                                                        transform: {{ $isVisible ? 'translateX(20px)' : 'translateX(0)' }};"
                                                 class="toggle-thumb"></div>
                                        </div>
                                    </label>
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

            <!-- Sección: Imágenes -->
            <div class="form-section" style="margin-top: 32px;">
                <h2 class="table-title">📸 Imágenes</h2>
                <p class="table-subtitle" style="margin-bottom: 16px;">Personaliza el logo y la imagen principal</p>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
                    <!-- Card: Logo -->
                    <div style="background: #0f1c32; border-radius: 12px; padding: 20px;">
                        <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 16px;">Logo del Negocio</h3>
                        
                        <div style="background: #07101F; border-radius: 8px; height: 200px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                            @if($customization && $customization->logo_filename)
                                <img 
                                    id="logo-preview" 
                                    src="{{ asset('storage/tenants/' . $tenant->id . '/' . $customization->logo_filename) }}" 
                                    alt="Logo"
                                    style="max-width: 100%; max-height: 100%; object-fit: contain;">
                            @else
                                <div id="logo-placeholder" style="text-align: center; color: rgba(255,255,255,0.3);">
                                    <div style="font-size: 48px; margin-bottom: 8px;">🖼️</div>
                                    <p style="font-size: 14px;">Sin logo</p>
                                </div>
                            @endif
                        </div>
                        
                        <input type="file" id="logo-file" accept="image/*" style="display: none;" onchange="uploadLogo(event)">
                        <button onclick="document.getElementById('logo-file').click()" class="btn-primary" style="width: 100%;">
                            📤 Cambiar Logo
                        </button>
                    </div>

                    <!-- Card: Hero -->
                    <div style="background: #0f1c32; border-radius: 12px; padding: 20px;">
                        <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 16px;">Imagen Principal (Hero)</h3>
                        
                        <div style="background: #07101F; border-radius: 8px; height: 200px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                            @if($customization && $customization->hero_filename)
                                <img 
                                    id="hero-preview" 
                                    src="{{ asset('storage/tenants/' . $tenant->id . '/' . $customization->hero_filename) }}" 
                                    alt="Hero"
                                    style="max-width: 100%; max-height: 100%; object-fit: cover; border-radius: 8px;">
                            @else
                                <div id="hero-placeholder" style="text-align: center; color: rgba(255,255,255,0.3);">
                                    <div style="font-size: 48px; margin-bottom: 8px;">🌄</div>
                                    <p style="font-size: 14px;">Sin imagen hero</p>
                                </div>
                            @endif
                        </div>
                        
                        <input type="file" id="hero-file" accept="image/*" style="display: none;" onchange="uploadHero(event)">
                        <button onclick="document.getElementById('hero-file').click()" class="btn-primary" style="width: 100%;">
                            📤 Cambiar Hero
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: Analytics -->
        <div id="tab-analytics" class="tab-content">
            <!-- Sección: El Radar -->
            <div class="form-section">
                <h2 class="table-title">📊 El Radar</h2>
                <p class="table-subtitle" style="margin-bottom: 24px;">Métricas básicas de tu negocio</p>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 32px;">
                    <!-- KPI: Visitas hoy -->
                    <div style="background: #0f1c32; border-radius: 12px; padding: 20px; text-align: center;">
                        <div style="font-size: 36px; font-weight: 700; color: #2B6FFF; margin-bottom: 8px;">0</div>
                        <div style="font-size: 13px; color: rgba(255,255,255,0.6);">Visitas hoy</div>
                    </div>

                    <!-- KPI: Clicks WhatsApp -->
                    <div style="background: #0f1c32; border-radius: 12px; padding: 20px; text-align: center;">
                        <div style="font-size: 36px; font-weight: 700; color: #2B6FFF; margin-bottom: 8px;">0</div>
                        <div style="font-size: 13px; color: rgba(255,255,255,0.6);">Clicks WhatsApp</div>
                    </div>

                    <!-- KPI: Escaneos QR -->
                    <div style="background: #0f1c32; border-radius: 12px; padding: 20px; text-align: center;">
                        <div style="font-size: 36px; font-weight: 700; color: #2B6FFF; margin-bottom: 8px;">0</div>
                        <div style="font-size: 13px; color: rgba(255,255,255,0.6);">Escaneos QR</div>
                    </div>

                    <!-- KPI: Productos vistos -->
                    <div style="background: #0f1c32; border-radius: 12px; padding: 20px; text-align: center;">
                        <div style="font-size: 36px; font-weight: 700; color: #2B6FFF; margin-bottom: 8px;">0</div>
                        <div style="font-size: 13px; color: rgba(255,255,255,0.6);">Productos vistos</div>
                    </div>
                </div>

                <!-- Nota informativa -->
                <div style="background: rgba(43, 111, 255, 0.1); border-left: 3px solid #2B6FFF; padding: 16px; border-radius: 8px; margin-bottom: 32px;">
                    <p style="font-size: 14px; color: rgba(255,255,255,0.8); margin: 0;">
                        ℹ️ El sistema de analítica detallada estará disponible próximamente. Por ahora puedes ver el pulso básico en tu panel flotante.
                    </p>
                </div>
            </div>

            <!-- Sección: Herramientas -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px;">
                <!-- Card: Tasa del Dólar -->
                <div style="background: #0f1c32; border-radius: 12px; padding: 24px;">
                    <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 16px;">💵 Tasa del Dólar</h3>
                    
                    <div style="background: #07101F; border-radius: 8px; padding: 20px; text-align: center; margin-bottom: 16px;">
                        <div style="font-size: 28px; font-weight: 700; color: #2B6FFF; margin-bottom: 4px;">
                            Bs. <span id="dollar-rate-value">{{ $dollarRate }}</span>
                        </div>
                        <div style="font-size: 12px; color: rgba(255,255,255,0.5);">por 1 USD</div>
                    </div>

                    <button onclick="updateDollarRate()" class="btn-primary" style="width: 100%;">
                        🔄 Actualizar
                    </button>
                </div>

                <!-- Card: Estado actual -->
                <div style="background: #0f1c32; border-radius: 12px; padding: 24px;">
                    <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 16px;">🔴 Estado actual</h3>
                    
                    <div style="background: #07101F; border-radius: 8px; padding: 20px; text-align: center;">
                        <div style="margin-bottom: 16px;">
                            <label class="toggle-switch" style="display: inline-block;">
                                <input 
                                    type="checkbox" 
                                    id="status-toggle-large"
                                    {{ $tenant->is_open ? 'checked' : '' }}
                                    onchange="toggleBusinessStatusLarge()">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        
                        <div style="font-size: 14px; color: rgba(255,255,255,0.7);">
                            Actualmente: 
                            <span style="color: #00cc66; font-weight: 600;">
                                {{ $tenant->is_open ? '🟢 Abierto' : '🔴 Cerrado' }}
                            </span>
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

            <div class="config-section" style="background: #0f1c32; border-radius: 12px; padding: 24px; margin-bottom: 24px;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                    <div>
                        <h2 style="color: #fff; font-size: 20px; font-weight: 700; margin: 0;">📍 Sucursales</h2>
                        <p style="color: #94a3b8; font-size: 13px; margin-top: 4px;">Muestra hasta 3 sucursales en tu landing pública</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="branches-toggle" {{ $branchesEnabled ? 'checked' : '' }} onchange="toggleBranchesSection()">
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div id="branches-status" style="background: {{ $branchesEnabled ? '#0d3320' : '#1e2a42' }}; border-left: 3px solid {{ $branchesEnabled ? '#22c55e' : '#6b7280' }}; padding: 10px 16px; border-radius: 6px; margin-bottom: 20px;">
                    <p id="branches-status-text" style="color: {{ $branchesEnabled ? '#86efac' : '#94a3b8' }}; font-size: 13px; margin: 0;">
                        {{ $branchesEnabled ? '✅ Sección visible en tu landing pública' : '⏸️ Sección oculta en tu landing pública' }}
                    </p>
                </div>
            </div>

            {{-- Branch list + Forms (shown only when enabled) --}}
            <div id="branches-content" style="{{ $branchesEnabled ? '' : 'display: none;' }}">
                
                {{-- Existing branches --}}
                <div id="branches-list">
                    @foreach($branches as $branch)
                    <div class="config-section branch-card" id="branch-card-{{ $branch->id }}" style="background: #0f1c32; border-radius: 12px; padding: 20px; margin-bottom: 16px;">
                        <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 12px;">
                            <div style="flex: 1;">
                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                    <span style="font-size: 18px;">📍</span>
                                    <h3 class="branch-name" style="color: #fff; font-size: 16px; font-weight: 600; margin: 0;">{{ $branch->name }}</h3>
                                </div>
                                <p class="branch-address" style="color: #94a3b8; font-size: 14px; margin: 0; line-height: 1.5;">{{ $branch->address }}</p>
                            </div>
                            <div style="display: flex; gap: 8px; flex-shrink: 0;">
                                <button type="button" class="btn-icon" onclick="editBranch({{ $branch->id }}, '{{ addslashes($branch->name) }}', '{{ addslashes($branch->address) }}')" title="Editar">✏️</button>
                                <button type="button" class="btn-icon btn-danger" onclick="deleteBranch({{ $branch->id }})" title="Eliminar">🗑️</button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Add/Edit Branch Form --}}
                <div id="branch-form-container" class="config-section" style="background: #0f1c32; border-radius: 12px; padding: 24px; margin-top: 16px; {{ $currentBranchCount >= $maxBranches ? 'display: none;' : '' }}">
                    <h3 id="branch-form-title" style="color: #fff; font-size: 16px; font-weight: 600; margin-bottom: 16px;">+ Agregar Sucursal</h3>
                    <input type="hidden" id="branch-edit-id" value="">

                    <div style="display: flex; flex-direction: column; gap: 14px;">
                        <div>
                            <label style="color: #e5e7eb; font-size: 13px; display: block; margin-bottom: 6px;">Nombre de la Sucursal *</label>
                            <input type="text" id="branch-name" maxlength="150" required
                                style="width: 100%; background: #07101F; border: 1px solid #1e2a42; border-radius: 8px; padding: 12px 16px; color: #fff; font-size: 15px;"
                                placeholder="Ej: Sede Centro, Sucursal Altamira...">
                        </div>

                        <div>
                            <label style="color: #e5e7eb; font-size: 13px; display: block; margin-bottom: 6px;">Dirección *</label>
                            <textarea id="branch-address" maxlength="500" rows="2" required
                                style="width: 100%; background: #07101F; border: 1px solid #1e2a42; border-radius: 8px; padding: 12px 16px; color: #fff; font-size: 15px; resize: vertical;"
                                placeholder="Ej: Av. Libertador, Torre X, Piso 3, Caracas"></textarea>
                        </div>

                        <div style="display: flex; gap: 12px;">
                            <button type="button" onclick="saveBranch()"
                                style="background: #2B6FFF; color: #fff; border: none; border-radius: 8px; padding: 12px 24px; font-size: 15px; font-weight: 500; cursor: pointer; flex: 1;"
                                onmouseover="this.style.background='#1e5ce6'"
                                onmouseout="this.style.background='#2B6FFF'">
                                Guardar Sucursal
                            </button>
                            <button type="button" id="branch-cancel-btn" onclick="cancelBranchEdit()" style="display: none; background: #374151; color: #fff; border: none; border-radius: 8px; padding: 12px 20px; font-size: 15px; cursor: pointer;">
                                Cancelar
                            </button>
                        </div>
                    </div>

                    <p style="font-size: 12px; color: rgba(255,255,255,0.4); margin-top: 12px;">
                        {{ $currentBranchCount }} de {{ $maxBranches }} sucursales usadas
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Tab: Config -->
        <div id="tab-config" class="tab-content">

            {{-- ═══════════════════════════════════════════════════════════
                 Section 0: Social Networks
            ═══════════════════════════════════════════════════════════ --}}
            <div class="config-section" style="background: #0f1c32; border-radius: 12px; padding: 24px; margin-bottom: 24px;">
                @php
                    $rawSocial      = $customization->social_networks ?? [];
                    $socialNetworks = is_array($rawSocial) ? $rawSocial : [];
                    $allNetworksMeta = [
                        'instagram' => ['label' => 'Instagram',  'placeholder' => '@tuusuario',          'icon' => '📸'],
                        'facebook'  => ['label' => 'Facebook',   'placeholder' => '@pagina o URL',       'icon' => '👤'],
                        'tiktok'    => ['label' => 'TikTok',     'placeholder' => '@tuusuario',          'icon' => '🎵'],
                        'linkedin'  => ['label' => 'LinkedIn',   'placeholder' => 'URL o usuario',       'icon' => '💼'],
                        'youtube'   => ['label' => 'YouTube',    'placeholder' => '@canal o URL',        'icon' => '▶️'],
                        'x'         => ['label' => 'Twitter / X','placeholder' => '@tuusuario',          'icon' => '🐦'],
                    ];
                    $plan1Networks  = ['instagram', 'facebook', 'tiktok', 'linkedin'];
                    $availableKeys  = $plan->id === 1 ? $plan1Networks : array_keys($allNetworksMeta);
                    // For Plan 1: determine currently selected network
                    $plan1Selected  = array_key_first(array_intersect_key($socialNetworks, array_flip($plan1Networks))) ?? '';
                    $plan1Handle    = $plan1Selected ? ($socialNetworks[$plan1Selected] ?? '') : '';
                @endphp

                <h3 style="color: #fff; font-size: 18px; font-weight: 600; margin-bottom: 6px;">🌐 Redes Sociales</h3>
                <p style="color: #94a3b8; font-size: 13px; margin-bottom: 20px;">
                    @if($plan->id === 1)
                        <span style="color: #f59e0b;">★ Plan OPORTUNIDAD</span> — Puedes configurar <strong style="color:#fff;">1 red social</strong>
                    @else
                        <span style="color: #22c55e;">★ Plan {{ $plan->name }}</span> — Todas las redes disponibles
                    @endif
                </p>

                @if($plan->id === 1)
                {{-- ── Plan 1: radio select + single handle ── --}}
                <div style="margin-bottom: 20px;">
                    <label style="color: #e5e7eb; font-size: 13px; display: block; margin-bottom: 10px;">Elige tu red social</label>
                    <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 18px;" id="social-radio-group">
                        @foreach($plan1Networks as $key)
                        @php $meta = $allNetworksMeta[$key]; @endphp
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 8px 14px; border-radius: 8px; border: 1px solid {{ $plan1Selected === $key ? '#2B6FFF' : '#1e2a42' }}; background: {{ $plan1Selected === $key ? '#1a2f5e' : 'transparent' }}; transition: all .2s;"
                               id="social-radio-label-{{ $key }}" onclick="selectSocialNetwork('{{ $key }}')">
                            <input type="radio" name="social_plan1_choice" value="{{ $key }}" {{ $plan1Selected === $key ? 'checked' : '' }} style="display: none;">
                            <span>{{ $meta['icon'] }}</span>
                            <span style="color: #e5e7eb; font-size: 14px; font-weight: 500;">{{ $meta['label'] }}</span>
                        </label>
                        @endforeach
                    </div>

                    <div>
                        <label style="color: #e5e7eb; font-size: 13px; display: block; margin-bottom: 6px;">
                            Tu usuario o enlace <span id="social-plan1-network-label" style="color:#2B6FFF;">{{ $plan1Selected ? '(' . $allNetworksMeta[$plan1Selected]['label'] . ')' : '' }}</span>
                        </label>
                        <input type="text" id="social-plan1-handle"
                            value="{{ $plan1Handle }}"
                            placeholder="{{ $plan1Selected ? $allNetworksMeta[$plan1Selected]['placeholder'] : 'Selecciona una red primero' }}"
                            style="width: 100%; background: #07101F; border: 1px solid #1e2a42; border-radius: 8px; padding: 12px 16px; color: #fff; font-size: 15px;"
                            {{ !$plan1Selected ? 'disabled' : '' }}>
                    </div>
                </div>

                @else
                {{-- ── Plan 2 + 3: all 6 networks ── --}}
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;" id="social-all-fields">
                    @foreach($availableKeys as $key)
                    @php $meta = $allNetworksMeta[$key]; $current = $socialNetworks[$key] ?? ''; @endphp
                    <div>
                        <label style="color: #e5e7eb; font-size: 13px; display: flex; align-items: center; gap: 6px; margin-bottom: 6px;">
                            <span>{{ $meta['icon'] }}</span> {{ $meta['label'] }}
                        </label>
                        <input type="text" id="social-{{ $key }}" name="social_{{ $key }}"
                            value="{{ $current }}"
                            placeholder="{{ $meta['placeholder'] }}"
                            maxlength="255"
                            style="width: 100%; background: #07101F; border: 1px solid #1e2a42; border-radius: 8px; padding: 10px 14px; color: #fff; font-size: 14px;">
                    </div>
                    @endforeach
                </div>
                @endif

                <button type="button" onclick="saveSocialNetworks()"
                    style="background: #2B6FFF; color: #fff; border: none; border-radius: 8px; padding: 12px 24px; font-size: 15px; font-weight: 500; cursor: pointer; width: 100%; transition: all .2s;"
                    onmouseover="this.style.background='#1e5ce6'"
                    onmouseout="this.style.background='#2B6FFF'">
                    Guardar Redes Sociales
                </button>
            </div>

            {{-- ════════════════════════════════════════════════════════════
                 Section: Payment Methods
            ═══════════════════════════════════════════════════════════ --}}
            <div class="config-section" style="background: #0f1c32; border-radius: 12px; padding: 24px; margin-bottom: 24px;">
                @php
                    $payMethods      = $customization->payment_methods ?? [];
                    $globalEnabled   = $payMethods['global'] ?? [];
                    $currencyEnabled = $payMethods['currency'] ?? [];
                    $branchPayMeta   = $payMethods['branches'] ?? [];
                    $allPayMeta      = [
                        // 🇻🇪 Nacionales / Flujo
                        'pagoMovil'  => ['label' => 'Pago Móvil',    'icon' => '📱', 'desc' => 'Transferencia bancaria móvil',  'group' => 'Nacional'],
                        'cash'       => ['label' => 'Efectivo',       'icon' => '💵', 'desc' => 'Pago en efectivo',                 'group' => 'Nacional'],
                        'puntoventa' => ['label' => 'Punto de Venta', 'icon' => '💳', 'desc' => 'Terminal POS físico',            'group' => 'Nacional'],
                        'biopago'    => ['label' => 'Biopago',        'icon' => '👁️', 'desc' => 'Pago biométrico',           'group' => 'Nacional'],
                        'cashea'     => ['label' => 'Cashea',         'icon' => '🛒', 'desc' => 'Compra ahora, paga después',     'group' => 'Nacional'],
                        'krece'      => ['label' => 'Krece',          'icon' => '📈', 'desc' => 'Financiamiento tech/electro',        'group' => 'Nacional'],
                        'wepa'       => ['label' => 'Wepa',           'icon' => '📲', 'desc' => 'Cuotas desde el móvil',           'group' => 'Nacional'],
                        'lysto'      => ['label' => 'Lysto',          'icon' => '🗓️', 'desc' => 'Pago en cuotas en comercios',  'group' => 'Nacional'],
                        'chollo'     => ['label' => 'Chollo',         'icon' => '🏷️', 'desc' => 'Compras a cuotas en retail',    'group' => 'Nacional'],
                        // 💱 Divisas
                        'zelle'      => ['label' => 'Zelle',          'icon' => '⚡', 'desc' => 'Transferencia USD',                 'group' => 'Divisa'],
                        'zinli'      => ['label' => 'Zinli',          'icon' => '🟣', 'desc' => 'Billetera digital USD',             'group' => 'Divisa'],
                        'paypal'     => ['label' => 'PayPal',         'icon' => '🅿️', 'desc' => 'Pagos internacionales',         'group' => 'Divisa'],
                    ];
                    $allCurrencyMeta = [
                        'usd' => ['label' => 'Dólares (USD)', 'icon' => '💵', 'desc' => 'Acepta billetes USD'],
                        'eur' => ['label' => 'Euros (€)',     'icon' => '💶', 'desc' => 'Acepta billetes EUR'],
                    ];
                    $activeBranchList = $branches->where('is_active', true);
                @endphp

                <h3 style="color: #fff; font-size: 18px; font-weight: 600; margin-bottom: 6px;">💳 Medios de Pago</h3>
                <p style="color: #94a3b8; font-size: 13px; margin-bottom: 20px;">
                    @if($plan->id === 1)
                        <span style="color: #f59e0b;">★ Plan OPORTUNIDAD</span> — Muestra <strong style="color:#fff;">Pago Móvil y Efectivo</strong> fijos en tu landing. No se puede modificar.
                    @elseif($plan->id === 2)
                        <span style="color: #22c55e;">★ Plan CRECIMIENTO</span> — Elige cuáles medios mostrar en tu landing
                    @else
                        <span style="color: #a78bfa;">★ Plan VISIÓN</span> — Elige tus medios globales y también por sucursal
                    @endif
                </p>

                @if($plan->id === 1)
                {{-- Plan 1: Fixed — read-only --}}
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                    @foreach(['pagoMovil', 'cash'] as $mkey)
                    @php $m = $allPayMeta[$mkey]; @endphp
                    <div style="background: #0d3320; border: 1px solid #22c55e40; border-radius: 10px; padding: 14px 16px; display: flex; align-items: center; gap: 10px;">
                        <span style="font-size: 20px;">{{ $m['icon'] }}</span>
                        <div>
                            <div style="color: #86efac; font-size: 13px; font-weight: 600;">{{ $m['label'] }}</div>
                            <div style="color: #4ade8060; font-size: 11px;">{{ $m['desc'] }}</div>
                        </div>
                        <span style="margin-left: auto; color: #22c55e; font-size: 16px;">✓</span>
                    </div>
                    @endforeach
                </div>
                <div style="background: #1e2a42; border-left: 3px solid #f59e0b; padding: 10px 14px; border-radius: 6px;">
                    <p style="color: #94a3b8; font-size: 12px; margin: 0;">🔒 Para elegir los medios de pago, mejora al Plan CRECIMIENTO o superior.</p>
                </div>

                @else
                {{-- Plan 2 + 3: Selectable checkboxes (global) --}}
                <div style="margin-bottom: 18px;">
                    <label style="color: #e5e7eb; font-size: 13px; font-weight: 500; display: block; margin-bottom: 10px;">
                        @if($plan->id === 3) Métodos globales (todos los clientes) @else Métodos visibles en tu landing @endif
                    </label>

                    {{-- Grupo: Tradicionales --}}
                    <p style="color: #6b7280; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; margin-bottom: 8px;">Tradicionales</p>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 16px;">
                        @foreach($allPayMeta as $mkey => $m)
                        @if(($m['group'] ?? '') !== 'BNPL')
                        @php $checked = in_array($mkey, $globalEnabled); @endphp
                        <label id="pay-label-{{ $mkey }}" onclick="togglePayMethod('{{ $mkey }}')"
                            style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 10px 14px; border-radius: 10px; border: 1px solid {{ $checked ? '#2B6FFF' : '#1e2a42' }}; background: {{ $checked ? '#1a2f5e' : 'transparent' }}; transition: all .2s; user-select: none;">
                            <input type="checkbox" id="pay-check-{{ $mkey }}" value="{{ $mkey }}" {{ $checked ? 'checked' : '' }} style="display: none;">
                            <span style="font-size: 18px;">{{ $m['icon'] }}</span>
                            <div>
                                <div style="color: #e5e7eb; font-size: 13px; font-weight: 500;">{{ $m['label'] }}</div>
                                <div style="color: #6b7280; font-size: 11px;">{{ $m['desc'] }}</div>
                            </div>
                            <span id="pay-check-icon-{{ $mkey }}" style="margin-left: auto; color: {{ $checked ? '#2B6FFF' : '#1e2a42' }}; font-size: 16px; transition: color .2s;">✓</span>
                        </label>
                        @endif
                        @endforeach
                    </div>

                    {{-- Grupo: BNPL --}}
                    <div style="border-top: 1px solid #1e2a42; padding-top: 14px;">
                        <p style="color: #a78bfa; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; margin-bottom: 8px;">📆 Compra ahora, paga después (BNPL)</p>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            @foreach($allPayMeta as $mkey => $m)
                            @if(($m['group'] ?? '') === 'BNPL')
                            @php $checked = in_array($mkey, $globalEnabled); @endphp
                            <label id="pay-label-{{ $mkey }}" onclick="togglePayMethod('{{ $mkey }}')"
                                style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 10px 14px; border-radius: 10px; border: 1px solid {{ $checked ? '#a78bfa' : '#1e2a42' }}; background: {{ $checked ? '#2d1f5e' : 'transparent' }}; transition: all .2s; user-select: none;">
                                <input type="checkbox" id="pay-check-{{ $mkey }}" value="{{ $mkey }}" {{ $checked ? 'checked' : '' }} style="display: none;">
                                <span style="font-size: 18px;">{{ $m['icon'] }}</span>
                                <div>
                                    <div style="color: #e5e7eb; font-size: 13px; font-weight: 500;">{{ $m['label'] }}</div>
                                    <div style="color: #6b7280; font-size: 11px;">{{ $m['desc'] }}</div>
                                </div>
                                <span id="pay-check-icon-{{ $mkey }}" style="margin-left: auto; color: {{ $checked ? '#a78bfa' : '#1e2a42' }}; font-size: 16px; transition: color .2s;">✓</span>
                            </label>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Denominaciones / Monedas --}}
                <div style="margin-bottom: 18px; padding-top: 18px; border-top: 1px solid #1e2a42;">
                    <label style="color: #e5e7eb; font-size: 13px; font-weight: 500; display: block; margin-bottom: 10px;">
                        💰 Denominaciones Aceptadas
                    </label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        @foreach($allCurrencyMeta as $ckey => $c)
                        @php $checked = in_array($ckey, $currencyEnabled); @endphp
                        <label id="curr-label-{{ $ckey }}" onclick="toggleCurrency('{{ $ckey }}')"
                            style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 10px 14px; border-radius: 10px; border: 1px solid {{ $checked ? '#2B6FFF' : '#1e2a42' }}; background: {{ $checked ? '#1a2f5e' : 'transparent' }}; transition: all .2s; user-select: none;">
                            <input type="checkbox" id="curr-check-{{ $ckey }}" value="{{ $ckey }}" {{ $checked ? 'checked' : '' }} style="display: none;">
                            <span style="font-size: 18px;">{{ $c['icon'] }}</span>
                            <div>
                                <div style="color: #e5e7eb; font-size: 13px; font-weight: 500;">{{ $c['label'] }}</div>
                                <div style="color: #6b7280; font-size: 11px;">{{ $c['desc'] }}</div>
                            </div>
                            <span id="curr-check-icon-{{ $ckey }}" style="margin-left: auto; color: {{ $checked ? '#2B6FFF' : '#1e2a42' }}; font-size: 16px; transition: color .2s;">✓</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- VISTA PREVIA: Cómo se verá en la landing --}}
                <div style="margin-top: 24px; padding: 16px; background: #07101F; border-radius: 10px; border: 1px solid #22c55e40;">
                    <p style="color: #86efac; font-size: 12px; font-weight: 600; margin-bottom: 12px;">👁️ VISTA PREVIA EN LA LANDING:</p>
                    <div id="payment-preview" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 8px; padding: 12px; background: #0f1c32; border-radius: 8px; min-height: 40px; align-items: center;">
                        {{-- Se actualiza dinámicamente con JS --}}
                    </div>
                </div>

                @if($plan->id === 3 && $activeBranchList->isNotEmpty())
                {{-- Plan 3: per-branch assignment --}}
                <div style="margin-bottom: 18px;">
                    <label style="color: #e5e7eb; font-size: 13px; font-weight: 500; display: block; margin-bottom: 4px;">Métodos por Sucursal</label>
                    <p style="color: #6b7280; font-size: 12px; margin-bottom: 12px;">Personaliza los métodos aceptados en cada sucursal</p>
                    <div style="display: flex; flex-direction: column; gap: 14px;">
                        @foreach($activeBranchList as $branch)
                        @php $bEnabled = $branchPayMeta[(string)$branch->id] ?? []; @endphp
                        <div style="background: #07101F; border: 1px solid #1e2a42; border-radius: 10px; padding: 14px 16px;">
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 10px;">
                                <span style="font-size: 15px;">📍</span>
                                <span style="color: #fff; font-size: 14px; font-weight: 600;">{{ $branch->name }}</span>
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                                @foreach($allPayMeta as $mkey => $m)
                                @php $bchecked = in_array($mkey, $bEnabled); @endphp
                                <label id="pay-branch-label-{{ $branch->id }}-{{ $mkey }}"
                                    onclick="toggleBranchPayMethod({{ $branch->id }}, '{{ $mkey }}')"
                                    style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 8px 10px; border-radius: 8px; border: 1px solid {{ $bchecked ? '#a78bfa60' : '#1e2a42' }}; background: {{ $bchecked ? '#2d1f5e' : 'transparent' }}; transition: all .2s; user-select: none;">
                                    <input type="checkbox" id="pay-branch-check-{{ $branch->id }}-{{ $mkey }}" value="{{ $mkey }}" {{ $bchecked ? 'checked' : '' }} style="display: none;">
                                    <span style="font-size: 14px;">{{ $m['icon'] }}</span>
                                    <span style="color: #e5e7eb; font-size: 12px;">{{ $m['label'] }}</span>
                                    <span id="pay-branch-check-icon-{{ $branch->id }}-{{ $mkey }}" style="margin-left: auto; color: {{ $bchecked ? '#a78bfa' : '#1e2a42' }}; font-size: 14px;">✓</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <button type="button" onclick="savePaymentMethods()"
                    style="background: #2B6FFF; color: #fff; border: none; border-radius: 8px; padding: 12px 24px; font-size: 15px; font-weight: 500; cursor: pointer; width: 100%; transition: all .2s;"
                    onmouseover="this.style.background='#1e5ce6'"
                    onmouseout="this.style.background='#2B6FFF'">
                    Guardar Medios de Pago y Denominaciones
                </button>
                @endif
            </div>

            <!-- Section 1: Currency Configuration -->
            <div class="config-section" style="background: #0f1c32; border-radius: 12px; padding: 24px; margin-bottom: 24px;">
                <h3 style="color: #fff; font-size: 18px; font-weight: 600; margin-bottom: 24px;">Configuración de Moneda</h3>
                
                <!-- Currency Symbol Switch -->
                <div style="margin-bottom: 32px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                        <label style="color: #fff; font-size: 15px; font-weight: 500;">Símbolo de Precio</label>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <span id="symbol-ref-label" style="color: #2B6FFF; font-size: 14px; font-weight: 600;">REF</span>
                            <label style="position: relative; display: inline-block; width: 50px; height: 24px;">
                                <input type="checkbox" id="currency-symbol-switch" style="opacity: 0; width: 0; height: 0;">
                                <span style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #1e2a42; border-radius: 24px; transition: .3s;"></span>
                                <span id="currency-slider" style="position: absolute; content: ''; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: #6b7280; border-radius: 50%; transition: .3s;"></span>
                            </label>
                            <span id="symbol-dollar-label" style="color: #6b7280; font-size: 14px; font-weight: 600;">$</span>
                        </div>
                    </div>
                </div>

                <!-- Display Mode Options -->
                <div>
                    <label style="color: #fff; font-size: 15px; font-weight: 500; display: block; margin-bottom: 16px;">Mostrar Precios en</label>
                    
                    @php $savedMode = $tenant->settings['engine_settings']['currency']['display']['saved_display_mode'] ?? 'reference_only'; @endphp
                    
                    <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 16px;">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                            <input type="radio" name="display_mode" value="reference_only" {{ $savedMode === 'reference_only' ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #2B6FFF;">
                            <span style="color: #e5e7eb; font-size: 14px;">Solo Referencia (REF/$)</span>
                        </label>
                        
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                            <input type="radio" name="display_mode" value="bolivares_only" {{ $savedMode === 'bolivares_only' ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #2B6FFF;">
                            <span style="color: #e5e7eb; font-size: 14px;">Solo Bolívares (Bs.)</span>
                        </label>
                        
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                            <input type="radio" name="display_mode" value="both_toggle" {{ $savedMode === 'both_toggle' ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #2B6FFF;">
                            <span style="color: #e5e7eb; font-size: 14px;">Ambos con toggle público</span>
                        </label>
                        
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                            <input type="radio" name="display_mode" value="hidden" {{ $savedMode === 'hidden' ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #2B6FFF;">
                            <span style="color: #e5e7eb; font-size: 14px;">Ocultar precio → activa "Más Info"</span>
                        </label>
                    </div>

                    <div style="background: #1e3a5f; border-left: 3px solid #2B6FFF; padding: 12px 16px; border-radius: 6px; margin-bottom: 16px;">
                        <p style="color: #94a3b8; font-size: 13px; margin: 0;">💡 Si ocultás el precio, el botón cambia a "Más Info"</p>
                    </div>

                    <button type="button" onclick="saveCurrencyConfig()"
                        style="background: #2B6FFF; color: #fff; border: none; border-radius: 8px; padding: 12px 24px; font-size: 15px; font-weight: 500; cursor: pointer; transition: all 0.2s; width: 100%;"
                        onmouseover="this.style.background='#1e5ce6'"
                        onmouseout="this.style.background='#2B6FFF'">
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

    <!-- Mobile Bottom Navigation (visible only on mobile <640px) -->
    <nav class="mobile-bottom-nav" role="navigation" aria-label="Navegación principal">
        <button class="mobile-bottom-nav-item active" data-tab="info" aria-label="Información del negocio">
            <span>📋</span>
            <span class="mobile-bottom-nav-label">Info</span>
        </button>
        <button class="mobile-bottom-nav-item" data-tab="productos" aria-label="Gestión de productos">
            <span>📦</span>
            <span class="mobile-bottom-nav-label">Productos</span>
        </button>
        <button class="mobile-bottom-nav-item" data-tab="servicios" aria-label="Gestión de servicios">
            <span>🛠️</span>
            <span class="mobile-bottom-nav-label">Servicios</span>
        </button>
        <button class="mobile-bottom-nav-item" data-tab="diseno" aria-label="Personalización y diseño">
            <span>🎨</span>
            <span class="mobile-bottom-nav-label">Diseño</span>
        </button>
        <button class="mobile-bottom-nav-item" data-tab="config" aria-label="Más opciones">
            <span>⋯</span>
            <span class="mobile-bottom-nav-label">Más</span>
        </button>
    </nav>

    <script>
        // Tab Navigation
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.nav-tab');
            const mobileNavItems = document.querySelectorAll('.mobile-bottom-nav-item');
            const contents = document.querySelectorAll('.tab-content');

            // Helper function to switch tab
            function switchTab(tabId) {
                // Remove active class from all tabs/nav items and contents
                tabs.forEach(t => t.classList.remove('active'));
                mobileNavItems.forEach(m => m.classList.remove('active'));
                contents.forEach(c => c.classList.remove('active'));

                // Add active class to clicked tab and corresponding content
                document.querySelector(`[data-tab="${tabId}"]`)?.classList.add('active');
                document.querySelector(`.mobile-bottom-nav-item[data-tab="${tabId}"]`)?.classList.add('active');
                document.getElementById('tab-' + tabId).classList.add('active');

                // Init drag & drop the FIRST time the Diseño tab becomes visible
                if (tabId === 'diseno' && !window._sortableReady) {
                    window._sortableReady = true;
                    window.initSortable();
                }
            }

            // Desktop navigation tabs
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');
                    switchTab(tabId);
                });
            });

            // Mobile bottom navigation
            mobileNavItems.forEach(item => {
                item.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');
                    switchTab(tabId);
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
        }

        function closeLimitModal() {
            document.getElementById('limit-modal').classList.remove('show');
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
        }

        function closeProductModal() {
            document.getElementById('product-modal').classList.remove('show');
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
            iBtn.style.background   = serviceModalMode === 'icon'  ? '#2B6FFF' : 'transparent';
            iBtn.style.color        = serviceModalMode === 'icon'  ? '#fff' : 'rgba(255,255,255,0.45)';
            imgBtn.style.background = serviceModalMode === 'image' ? '#2B6FFF' : 'transparent';
            imgBtn.style.color      = serviceModalMode === 'image' ? '#fff' : 'rgba(255,255,255,0.45)';
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

            if (tabIcon) {
                tabIcon.style.background = (mode === 'icon')  ? 'rgba(43,111,255,0.55)' : 'transparent';
                tabIcon.style.color      = (mode === 'icon')  ? '#fff' : 'rgba(255,255,255,0.4)';
            }
            if (tabImg) {
                tabImg.style.background = (mode === 'image') ? 'rgba(43,111,255,0.55)' : 'transparent';
                tabImg.style.color      = (mode === 'image') ? '#fff' : 'rgba(255,255,255,0.4)';
            }

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
                grid.innerHTML = `<div style="grid-column:span 6; text-align:center; color:rgba(255,255,255,0.3); padding:24px; font-size:13px;">Sin resultados para "<em>${filter}</em>"</div>`;
                return;
            }

            filtered.forEach(ic => {
                const selected = iconPickerSelected === ic.n;
                const el = document.createElement('div');
                el.className   = 'icon-pick-item';
                el.title       = ic.l;
                el.dataset.name = ic.n;
                el.style.cssText = `
                    display:flex; flex-direction:column; align-items:center; justify-content:center; gap:8px;
                    padding:14px 10px; border-radius:12px; cursor:pointer; transition:all .2s;
                    background:${selected ? 'rgba(59,130,246,0.2)' : 'rgba(255,255,255,0.03)'};
                    border:2px solid ${selected ? '#3b82f6' : 'transparent'};
                    position:relative;
                `;
                el.addEventListener('mouseenter', function() {
                    if (!selected) {
                        this.style.background = 'rgba(59,130,246,0.1)';
                        this.style.transform = 'scale(1.08)';
                        this.style.borderColor = 'rgba(59,130,246,0.3)';
                    }
                });
                el.addEventListener('mouseleave', function() {
                    if (!selected) {
                        this.style.background = 'rgba(255,255,255,0.03)';
                        this.style.transform = 'scale(1)';
                        this.style.borderColor = 'transparent';
                    }
                });
                el.innerHTML = `
                    <span class="iconify tabler--${ic.n}" style="font-size: 40px; color: #3b82f6;"></span>
                    <span style="font-size:10px;color:rgba(255,255,255,0.4);text-align:center;line-height:1.3;max-width:100%;overflow:hidden;text-overflow:ellipsis; font-weight: 500;">${ic.l}</span>
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
                prevEl.className = 'iconify tabler--' + iconName + ' text-primary';
            }
            if (prevLabel) prevLabel.textContent = iconLabel + '  (' + iconName + ')';
            const searchVal = document.getElementById('icon-search')?.value || '';
            renderIconGrid(searchVal);
        }

        document.addEventListener('DOMContentLoaded', () => { updateGlobalModeBtns(); });

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
        }

        function closeServiceModal() {
            document.getElementById('service-modal').classList.remove('show');
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
            const icon  = document.getElementById('pay-check-icon-' + key);
            if (!check) return;
            check.checked = !check.checked;
            const isDivisa   = divisaKeys.includes(key);
            const activeColor = isDivisa ? '#fbbf24' : '#2B6FFF';
            const activeBg    = isDivisa ? '#2a1f05' : '#1a2f5e';
            label.style.borderColor = check.checked ? activeColor : '#1e2a42';
            label.style.background  = check.checked ? activeBg    : 'transparent';
            icon.style.color        = check.checked ? activeColor : '#1e2a42';
            updatePaymentPreview();
        }

        function toggleCurrency(key) {
            const check = document.getElementById('curr-check-' + key);
            const label = document.getElementById('curr-label-' + key);
            const icon  = document.getElementById('curr-check-icon-' + key);
            if (!check) return;
            check.checked = !check.checked;
            label.style.borderColor = check.checked ? '#2B6FFF' : '#1e2a42';
            label.style.background  = check.checked ? '#1a2f5e' : 'transparent';
            icon.style.color        = check.checked ? '#2B6FFF' : '#1e2a42';
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
            const icon  = document.getElementById('pay-branch-check-icon-' + branchId + '-' + key);
            if (!check) return;
            check.checked = !check.checked;
            label.style.borderColor = check.checked ? '#a78bfa60' : '#1e2a42';
            label.style.background  = check.checked ? '#2d1f5e' : 'transparent';
            icon.style.color        = check.checked ? '#a78bfa' : '#1e2a42';
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
                filter: '.opacity-50',
                preventOnFilter: false,
                onEnd: function() { saveSectionsOrder(); }
            });

            sortableEl.addEventListener('change', function(e) {
                if (!e.target.classList.contains('section-toggle')) return;
                const checked = e.target.checked;
                const item    = e.target.closest('.section-item');
                const thumb   = item && item.querySelector('.toggle-thumb');
                if (thumb) {
                    thumb.style.background = checked ? '#2B6FFF' : 'rgba(255,255,255,0.4)';
                    thumb.style.transform  = checked ? 'translateX(20px)' : 'translateX(0)';
                }
                saveSectionsOrder();
            });

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
    </script>
</body>
</html>