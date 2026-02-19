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

        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-overlay.show {
            display: flex;
        }

        .modal {
            background: #0f1c32;
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 600;
        }

        .modal-close {
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

        .modal-close:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }

        .modal-body {
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

        /* Responsive */
        @media (max-width: 768px) {
            .dashboard-header {
                flex-wrap: wrap;
                gap: 12px;
            }

            .header-left {
                flex-wrap: wrap;
            }

            .business-name {
                padding-left: 0;
                border-left: none;
                width: 100%;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }
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

    <!-- Navigation Tabs -->
    <nav class="dashboard-nav">
        <ul class="nav-tabs">
            <li class="nav-tab active" data-tab="info">📋 Info</li>
            <li class="nav-tab" data-tab="productos">📦 Productos</li>
            <li class="nav-tab" data-tab="servicios">🛠️ Servicios</li>
            <li class="nav-tab" data-tab="diseno">🎨 Diseño</li>
            <li class="nav-tab" data-tab="analytics">📊 Analytics</li>
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
                $productLimits = [1 => 6, 2 => 18, 3 => 40];
                $maxProducts = $productLimits[$plan->id] ?? 6;
                $currentCount = $products->count();
                $canAddMore = $currentCount < $maxProducts;
            @endphp

            <div class="table-header">
                <div>
                    <h2 class="table-title">📦 Productos</h2>
                    <p class="table-subtitle">{{ $currentCount }} de {{ $maxProducts }} productos</p>
                </div>
                <button 
                    class="btn-add" 
                    onclick="openProductModal()" 
                    {{ !$canAddMore ? 'disabled' : '' }}
                    title="{{ !$canAddMore ? 'Has alcanzado el límite de productos de tu plan' : 'Agregar nuevo producto' }}">
                    + Agregar Producto
                </button>
            </div>

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
                            <td>
                                @if($product->image_filename)
                                    <img src="{{ asset('storage/tenants/' . $tenant->id . '/' . $product->image_filename) }}" 
                                         alt="{{ $product->name }}" 
                                         class="product-image">
                                @else
                                    <div class="product-image" style="background: #374151;"></div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $product->name }}</strong>
                                @if($product->is_featured)
                                    <span style="color: #ffd700; margin-left: 4px;">⭐</span>
                                @endif
                            </td>
                            <td>${{ number_format($product->price_usd, 2) }}</td>
                            <td>
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
                            <td>
                                <span class="status-indicator">
                                    <span class="status-dot {{ $product->is_active ? 'active' : 'inactive' }}"></span>
                                    {{ $product->is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td>
                                <button class="btn-icon" onclick="editProduct({{ $product->id }})" title="Editar">
                                    ✏️
                                </button>
                                <button class="btn-icon btn-danger" onclick="deleteProduct({{ $product->id }})" title="Eliminar">
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
        <div id="product-modal" class="modal-overlay">
            <div class="modal">
                <div class="modal-header">
                    <h3 class="modal-title" id="product-modal-title">Agregar Producto</h3>
                    <button class="modal-close" onclick="closeProductModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="product-form" onsubmit="saveProduct(event)">
                        <input type="hidden" id="product-id">

                        <div class="image-preview" id="product-image-preview" style="display: none;">
                            <img id="product-image-preview-img" src="" alt="Preview">
                        </div>

                        <div class="form-section">
                            <label for="product-image" class="form-label">Imagen del Producto</label>
                            <input type="file" id="product-image" accept="image/*" class="form-input" onchange="previewProductImage(event)">
                            <p style="font-size: 12px; color: rgba(255,255,255,0.5); margin-top: 4px;">
                                Máx. 2MB, se redimensionará a 800px
                            </p>
                        </div>

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
                $serviceLimits = [1 => 3, 2 => 6, 3 => 15];
                $maxServices = $serviceLimits[$plan->id] ?? 3;
                $currentServiceCount = $services->count();
                $canAddMoreServices = $currentServiceCount < $maxServices;
            @endphp

            <div class="table-header">
                <div>
                    <h2 class="table-title">🛠️ Servicios</h2>
                    <p class="table-subtitle">{{ $currentServiceCount }} de {{ $maxServices }} servicios</p>
                </div>
                <button 
                    class="btn-add" 
                    onclick="openServiceModal()" 
                    {{ !$canAddMoreServices ? 'disabled' : '' }}
                    title="{{ !$canAddMoreServices ? 'Has alcanzado el límite de servicios de tu plan' : 'Agregar nuevo servicio' }}">
                    + Agregar Servicio
                </button>
            </div>

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
                            <td>
                                @if($service->image_filename)
                                    <img src="{{ asset('storage/tenants/' . $tenant->id . '/' . $service->image_filename) }}" 
                                         alt="{{ $service->name }}" 
                                         class="product-image">
                                @else
                                    <div class="product-image" style="background: #374151;"></div>
                                @endif
                            </td>
                            <td><strong>{{ $service->name }}</strong></td>
                            <td>
                                @if($service->description)
                                    {{ Str::limit($service->description, 60) }}
                                @else
                                    <span style="color: rgba(255,255,255,0.3);">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="status-indicator">
                                    <span class="status-dot {{ $service->is_active ? 'active' : 'inactive' }}"></span>
                                    {{ $service->is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td>
                                <button class="btn-icon" onclick="editService({{ $service->id }})" title="Editar">
                                    ✏️
                                </button>
                                <button class="btn-icon btn-danger" onclick="deleteService({{ $service->id }})" title="Eliminar">
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
        <div id="service-modal" class="modal-overlay">
            <div class="modal">
                <div class="modal-header">
                    <h3 class="modal-title" id="service-modal-title">Agregar Servicio</h3>
                    <button class="modal-close" onclick="closeServiceModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="service-form" onsubmit="saveService(event)">
                        <input type="hidden" id="service-id">

                        <div class="image-preview" id="service-image-preview" style="display: none;">
                            <img id="service-image-preview-img" src="" alt="Preview">
                        </div>

                        <div class="form-section">
                            <label for="service-image" class="form-label">Imagen del Servicio</label>
                            <input type="file" id="service-image" accept="image/*" class="form-input" onchange="previewServiceImage(event)">
                            <p style="font-size: 12px; color: rgba(255,255,255,0.5); margin-top: 4px;">
                                Máx. 2MB, se redimensionará a 800px
                            </p>
                        </div>

                        <div class="form-section">
                            <label for="service-name" class="form-label">Nombre *</label>
                            <input type="text" id="service-name" class="form-input" required maxlength="100">
                        </div>

                        <div class="form-section">
                            <label for="service-description" class="form-label">Descripción</label>
                            <textarea id="service-description" class="form-input" rows="3" maxlength="500"></textarea>
                        </div>

                        <div class="form-section">
                            <label class="form-label">Servicio Activo</label>
                            <label class="toggle-switch">
                                <input type="checkbox" id="service-is-active" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn-secondary" onclick="closeServiceModal()">Cancelar</button>
                            <button type="submit" class="btn-primary">Guardar Servicio</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tab: Diseño -->
        <div id="tab-diseno" class="tab-content">
            <!-- Sección: Paleta de Colores -->
            <div class="form-section">
                <h2 class="table-title">🎨 Paleta de Colores</h2>
                <p class="table-subtitle" style="margin-bottom: 16px;">Selecciona el estilo visual de tu landing page</p>
                
                <div id="palette-success-message" style="display: none; padding: 12px; background: rgba(0,204,102,0.2); border-radius: 8px; margin-bottom: 16px; color: #00cc66; font-size: 14px;">
                    ✓ Paleta actualizada correctamente
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 16px;">
                    @foreach($colorPalettes as $palette)
                    <div 
                        class="palette-card" 
                        data-id="{{ $palette->id }}"
                        onclick="updatePalette({{ $palette->id }})"
                        style="cursor: pointer; padding: 12px; background: #0f1c32; border-radius: 8px; border: 2px solid {{ $tenant->color_palette_id == $palette->id ? '#2B6FFF' : 'transparent' }}; transition: all 0.2s; position: relative;">
                        <!-- Color Preview -->
                        <div style="display: flex; height: 80px; border-radius: 6px; overflow: hidden; margin-bottom: 8px;">
                            <div style="flex: 1; background: {{ $palette->primary_color }};"></div>
                            <div style="flex: 1; background: {{ $palette->secondary_color }};"></div>
                            <div style="flex: 1; background: {{ $palette->background_color }};"></div>
                        </div>
                        
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <span style="font-size: 14px; font-weight: 500;">{{ $palette->name }}</span>
                            @if($tenant->color_palette_id == $palette->id)
                                <span class="palette-check" style="color: #2B6FFF; font-size: 18px;">✓</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

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

        <!-- Tab: Config -->
        <div id="tab-config" class="tab-content">
            <div class="placeholder-content">
                <h3>🚧 Módulo de Configuración</h3>
                <p>En construcción...</p>
            </div>
        </div>

    </main>

    <script>
        // Tab Navigation
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.nav-tab');
            const contents = document.querySelectorAll('.tab-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');

                    // Remove active class from all tabs and contents
                    tabs.forEach(t => t.classList.remove('active'));
                    contents.forEach(c => c.classList.remove('active'));

                    // Add active class to clicked tab and corresponding content
                    this.classList.add('active');
                    document.getElementById('tab-' + tabId).classList.add('active');
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

        function openProductModal(productId = null) {
            const modal = document.getElementById('product-modal');
            const title = document.getElementById('product-modal-title');
            const form = document.getElementById('product-form');
            
            form.reset();
            currentProductId = productId;
            
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
                }
            } else {
                // Add mode
                title.textContent = 'Agregar Producto';
                document.getElementById('product-image-preview').style.display = 'none';
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
                    // Handle image upload if file selected
                    const imageFile = document.getElementById('product-image').files[0];
                    if (imageFile) {
                        const formData = new FormData();
                        formData.append('image', imageFile);
                        
                        await fetch(`/tenant/{{ $tenant->id }}/upload/product/${result.product.id}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                            },
                            body: formData
                        });
                    }
                    
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

        function openServiceModal(serviceId = null) {
            const modal = document.getElementById('service-modal');
            const title = document.getElementById('service-modal-title');
            const form = document.getElementById('service-form');
            
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
                    
                    if (service.image_filename) {
                        const preview = document.getElementById('service-image-preview');
                        const img = document.getElementById('service-image-preview-img');
                        img.src = `/storage/tenants/{{ $tenant->id }}/${service.image_filename}`;
                        preview.style.display = 'block';
                    }
                }
            } else {
                // Add mode
                title.textContent = 'Agregar Servicio';
                document.getElementById('service-image-preview').style.display = 'none';
            }
            
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
            
            const data = {
                name: document.getElementById('service-name').value,
                description: document.getElementById('service-description').value,
                is_active: document.getElementById('service-is-active').checked ? 1 : 0
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
                    // Handle image upload if file selected
                    const imageFile = document.getElementById('service-image').files[0];
                    if (imageFile) {
                        const formData = new FormData();
                        formData.append('image', imageFile);
                        
                        await fetch(`/tenant/{{ $tenant->id }}/upload/service/${result.service.id}`, {
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

        // Design Tab: Palette Update
        function updatePalette(paletteId) {
            const tenantId = {{ $tenant->id }};
            
            fetch(`/tenant/${tenantId}/update-palette`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({palette_id: paletteId})
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    // Limpiar TODOS los checks y bordes
                    document.querySelectorAll('.palette-card').forEach(card => {
                        card.style.border = '2px solid transparent';
                        const chk = card.querySelector('.palette-check');
                        if (chk) chk.remove();
                    });
                    // Activar la seleccionada
                    const selected = document.querySelector(
                        `.palette-card[data-id="${paletteId}"]`
                    );
                    if (selected) {
                        selected.style.border = '2px solid #2B6FFF';
                        const chk = document.createElement('span');
                        chk.className = 'palette-check';
                        chk.textContent = '✓';
                        chk.style.cssText = 'position:absolute;top:6px;right:8px;color:#2B6FFF;font-weight:700;font-size:14px;';
                        selected.appendChild(chk);
                    }
                    // Mensaje éxito
                    const msg = document.getElementById('palette-success-message');
                    if (msg) { msg.style.display='block'; setTimeout(()=>msg.style.display='none', 3000); }
                }
            })
            .catch(e => console.error('Error:', e));
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

        // Reset Form
        function resetForm(formId) {
            document.getElementById(formId).reset();
        }
    </script>
</body>
</html>
