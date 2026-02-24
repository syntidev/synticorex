<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Navbar - {{ $tenant->business_name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-base-200 min-h-screen">
    
    {{-- Navbar Component --}}
    @include('landing-v2.partials.navbar')

    {{-- Content Area para mostrar el navbar en contexto --}}
    <div class="container mx-auto px-4 py-12 max-w-6xl">
        
        {{-- Info Card --}}
        <div class="card bg-base-100 shadow-xl mb-8">
            <div class="card-body">
                <h2 class="card-title text-3xl">
                    <span class="iconify tabler--layout-navbar size-8 text-primary"></span>
                    Navbar Testing
                </h2>
                <p class="text-lg text-base-content/70">
                    Visualización del navbar con datos reales del tenant
                </p>
            </div>
        </div>

        {{-- Tenant Info --}}
        <div class="grid md:grid-cols-2 gap-6 mb-8">
            <div class="card bg-base-100 shadow-lg">
                <div class="card-body">
                    <h3 class="card-title text-primary">
                        <span class="iconify tabler--building-store size-6"></span>
                        Información del Negocio
                    </h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="font-semibold">Nombre:</span>
                            <span>{{ $tenant->business_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold">Plan:</span>
                            <span class="badge badge-primary">{{ ucfirst($tenant->plan->name) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold">WhatsApp:</span>
                            <span>{{ $tenant->whatsapp ?: 'No configurado' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold">Estado:</span>
                            <span class="badge {{ $tenant->is_open ? 'badge-success' : 'badge-error' }}">
                                {{ $tenant->is_open ? '🟢 Abierto' : '🔴 Cerrado' }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold">Delivery:</span>
                            <span>{{ $tenant->has_delivery ? '✅ Sí' : '❌ No' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-lg">
                <div class="card-body">
                    <h3 class="card-title text-secondary">
                        <span class="iconify tabler--palette size-6"></span>
                        Elementos Visibles (Condicionales)
                    </h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center gap-2">
                            <span class="{{ $customization->logo_filename ? 'text-success' : 'text-base-content/40' }}">
                                {{ $customization->logo_filename ? '✅' : '⬜' }}
                            </span>
                            <span>Logo ({{ $customization->logo_filename ? 'Imagen' : 'Inicial' }})</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="{{ $tenant->plan->slug !== 'oportunidad' ? 'text-success' : 'text-base-content/40' }}">
                                {{ $tenant->plan->slug !== 'oportunidad' ? '✅' : '⬜' }}
                            </span>
                            <span>Link "Nosotros" (no Oportunidad)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="{{ $tenant->plan->slug !== 'oportunidad' && $tenant->has_delivery ? 'text-success' : 'text-base-content/40' }}">
                                {{ $tenant->plan->slug !== 'oportunidad' && $tenant->has_delivery ? '✅' : '⬜' }}
                            </span>
                            <span>Icono Delivery (no Oportunidad + delivery activo)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="{{ $tenant->plan->slug === 'vision' ? 'text-success' : 'text-base-content/40' }}">
                                {{ $tenant->plan->slug === 'vision' ? '✅' : '⬜' }}
                            </span>
                            <span>Link "FAQ" (solo Visión)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="{{ $tenant->saved_display_mode === 'both_toggle' ? 'text-success' : 'text-base-content/40' }}">
                                {{ $tenant->saved_display_mode === 'both_toggle' ? '✅' : '⬜' }}
                            </span>
                            <span>Toggle Moneda (privado - both_toggle)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Instructions --}}
        <div class="alert alert-info">
            <span class="iconify tabler--info-circle size-6"></span>
            <div>
                <h3 class="font-bold">Instrucciones de Testing</h3>
                <ul class="text-sm mt-2 space-y-1 list-disc list-inside">
                    <li>Prueba el <strong>collapse mobile</strong> redimensionando la ventana</li>
                    <li>Verifica que los <strong>links condicionales</strong> se muestren según el plan</li>
                    <li>Si existe toggle moneda, prueba la <strong>persistencia en localStorage</strong></li>
                    <li>Revisa el <strong>estado abierto/cerrado</strong> con animación</li>
                    <li>Click en WhatsApp para validar el link</li>
                </ul>
            </div>
        </div>

        {{-- Dummy Content para scroll --}}
        <div class="mt-12 space-y-8">
            <section id="home" class="card bg-base-100 shadow-lg">
                <div class="card-body">
                    <h3 class="card-title text-2xl">🏠 Home</h3>
                    <p class="text-base-content/70">Sección Home (ancla)</p>
                </div>
            </section>

            <section id="products" class="card bg-base-100 shadow-lg">
                <div class="card-body">
                    <h3 class="card-title text-2xl">🛍️ Productos</h3>
                    <p class="text-base-content/70">Sección Productos (ancla)</p>
                </div>
            </section>

            <section id="services" class="card bg-base-100 shadow-lg">
                <div class="card-body">
                    <h3 class="card-title text-2xl">⚙️ Servicios</h3>
                    <p class="text-base-content/70">Sección Servicios (ancla)</p>
                </div>
            </section>

            @if($tenant->plan->slug !== 'oportunidad')
            <section id="about" class="card bg-base-100 shadow-lg">
                <div class="card-body">
                    <h3 class="card-title text-2xl">ℹ️ Nosotros</h3>
                    <p class="text-base-content/70">Sección Nosotros (condicional)</p>
                </div>
            </section>
            @endif

            @if($tenant->plan->slug === 'vision')
            <section id="faq" class="card bg-base-100 shadow-lg">
                <div class="card-body">
                    <h3 class="card-title text-2xl">❓ FAQ</h3>
                    <p class="text-base-content/70">Sección FAQ (condicional - solo Visión)</p>
                </div>
            </section>
            @endif
        </div>

        {{-- Back button --}}
        <div class="text-center mt-12">
            <a href="/test" class="btn btn-outline btn-lg gap-2">
                <span class="iconify tabler--arrow-left size-5"></span>
                Volver al Index
            </a>
        </div>

    </div>

</body>
</html>
