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
            <div class="placeholder-content">
                <h3>🚧 Módulo de Productos</h3>
                <p>En construcción...</p>
            </div>
        </div>

        <!-- Tab: Servicios -->
        <div id="tab-servicios" class="tab-content">
            <div class="placeholder-content">
                <h3>🚧 Módulo de Servicios</h3>
                <p>En construcción...</p>
            </div>
        </div>

        <!-- Tab: Diseño -->
        <div id="tab-diseno" class="tab-content">
            <div class="placeholder-content">
                <h3>🚧 Módulo de Diseño</h3>
                <p>En construcción...</p>
            </div>
        </div>

        <!-- Tab: Analytics -->
        <div id="tab-analytics" class="tab-content">
            <div class="placeholder-content">
                <h3>🚧 Módulo de Analytics</h3>
                <p>En construcción...</p>
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

        // Reset Form
        function resetForm(formId) {
            document.getElementById(formId).reset();
        }
    </script>
</body>
</html>
