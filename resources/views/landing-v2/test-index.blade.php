<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing V2 - Testing Suite</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-base-200" data-theme="light">
    <div class="container mx-auto px-4 py-12 max-w-4xl">
        
        {{-- Header --}}
        <div class="text-center mb-12">
            <h1 class="text-5xl font-bold text-base-content mb-4">
                🧪 Landing V2 Testing Suite
            </h1>
            <p class="text-xl text-base-content/70">
                Prueba todos los componentes de Landing V2
            </p>
        </div>

        {{-- Navbar Tests --}}
        <div class="card bg-base-100 shadow-xl mb-8">
            <div class="card-body">
                <h2 class="card-title text-2xl mb-4">
                    <span class="iconify tabler--layout-navbar size-8 text-primary"></span>
                    Navbar
                </h2>
                <div class="grid gap-3">
                    <a href="/test/navbar" class="btn btn-outline btn-lg justify-start gap-3">
                        <span class="iconify tabler--eye size-5"></span>
                        Ver Navbar Standalone
                    </a>
                </div>
            </div>
        </div>

        {{-- Hero Tests --}}
        <div class="card bg-base-100 shadow-xl mb-8">
            <div class="card-body">
                <h2 class="card-title text-2xl mb-4">
                    <span class="iconify tabler--layout-dashboard size-8 text-secondary"></span>
                    Hero Sections (4 Layouts)
                </h2>
                <div class="grid gap-3">
                    <a href="/test/hero-fullscreen" class="btn btn-outline btn-lg justify-start gap-3">
                        <span class="iconify tabler--maximize size-5"></span>
                        <div class="text-left">
                            <div class="font-bold">Hero Fullscreen</div>
                            <div class="text-xs opacity-70">Background completo + contenido centrado</div>
                        </div>
                    </a>
                    
                    <a href="/test/hero-split" class="btn btn-outline btn-lg justify-start gap-3">
                        <span class="iconify tabler--layout-columns size-5"></span>
                        <div class="text-left">
                            <div class="font-bold">Hero Split (50/50)</div>
                            <div class="text-xs opacity-70">Contenido izquierda + imagen derecha</div>
                        </div>
                    </a>

                    <a href="/test/hero-gradient" class="btn btn-outline btn-lg justify-start gap-3">
                        <span class="iconify tabler--color-swatch size-5"></span>
                        <div class="text-left">
                            <div class="font-bold">Hero Gradient</div>
                            <div class="text-xs opacity-70">Gradiente animado + floating image</div>
                        </div>
                    </a>

                    <a href="/test/hero-cards" class="btn btn-outline btn-lg justify-start gap-3">
                        <span class="iconify tabler--cards size-5"></span>
                        <div class="text-left">
                            <div class="font-bold">Hero Cards</div>
                            <div class="text-xs opacity-70">Background + 3 cards overlaid</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        {{-- Info Card --}}
        <div class="alert alert-info">
            <span class="iconify tabler--info-circle size-6"></span>
            <div>
                <h3 class="font-bold">Información</h3>
                <div class="text-sm mt-1">
                    Todas las pruebas usan <strong>Tenant ID 1</strong> con su plan y configuración actual.
                    <br>Los elementos condicionales se muestran según el plan del tenant.
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="text-center mt-12 text-base-content/50">
            <p>SYNTIweb Landing V2 • Laravel 12 + FlyonUI</p>
        </div>

    </div>
</body>
</html>
