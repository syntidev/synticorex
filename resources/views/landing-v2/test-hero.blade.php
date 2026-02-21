<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Hero {{ ucfirst($heroLayout) }} - {{ $tenant->business_name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-base-200">
    
    {{-- Navbar Component --}}
    @include('landing-v2.partials.navbar')

    {{-- Hero Component (dinámico según $heroLayout) --}}
    @switch($heroLayout)
        @case('split')
            @include('landing-v2.partials.hero-split')
            @break
        @case('gradient')
            @include('landing-v2.partials.hero-gradient')
            @break
        @case('cards')
            @include('landing-v2.partials.hero-cards')
            @break
        @default
            @include('landing-v2.partials.hero-fullscreen')
    @endswitch

    {{-- Info Section --}}
    <div class="container mx-auto px-4 py-12 max-w-4xl">
        
        <div class="card bg-base-100 shadow-xl mb-8">
            <div class="card-body">
                <h2 class="card-title text-3xl text-primary">
                    <span class="icon-[tabler--layout-dashboard] size-8"></span>
                    Hero {{ ucfirst($heroLayout) }}
                </h2>
                
                <div class="divider"></div>

                <div class="grid md:grid-cols-2 gap-6">
                    {{-- Layout Info --}}
                    <div>
                        <h3 class="font-bold text-lg mb-3">Características del Layout</h3>
                        @switch($heroLayout)
                            @case('fullscreen')
                                <ul class="space-y-2 text-sm">
                                    <li>✅ Background CSS completo</li>
                                    <li>✅ Contenido centrado vertical</li>
                                    <li>✅ SVG decorativo en título</li>
                                    <li>✅ Badge con tagline/año</li>
                                    <li>✅ CTA con fallback WhatsApp → Tel → #contact</li>
                                    <li>📊 <strong>0</strong> etiquetas &lt;img&gt;</li>
                                </ul>
                                @break
                            @case('split')
                                <ul class="space-y-2 text-sm">
                                    <li>✅ Layout 50% contenido / 50% imagen</li>
                                    <li>✅ Stats con 3 métricas fijas</li>
                                    <li>✅ Ternario para selección de imagen</li>
                                    <li>✅ Blur decoration en imagen</li>
                                    <li>✅ Responsive (stacked mobile)</li>
                                    <li>📊 <strong>1</strong> etiqueta &lt;img&gt;</li>
                                </ul>
                                @break
                            @case('gradient')
                                <ul class="space-y-2 text-sm">
                                    <li>✅ Gradiente animado (primary → secondary → accent)</li>
                                    <li>✅ 1 floating image (consolidada)</li>
                                    <li>✅ Animación @keyframes gradient-xy + float</li>
                                    <li>✅ Decorative pulsing dots</li>
                                    <li>✅ SVG wave bottom</li>
                                    <li>📊 <strong>1</strong> etiqueta &lt;img&gt;</li>
                                </ul>
                                @break
                            @case('cards')
                                <ul class="space-y-2 text-sm">
                                    <li>✅ Background hero con overlay pattern</li>
                                    <li>✅ 3 cards overlaid (negative margin)</li>
                                    <li>✅ Card 2 scaled 105% + badge "Destacado"</li>
                                    <li>✅ Solo emojis (🎯, ⚡, 💎) + texto</li>
                                    <li>✅ Stats bar con métricas fijas</li>
                                    <li>📊 <strong>0</strong> etiquetas &lt;img&gt;</li>
                                </ul>
                                @break
                        @endswitch
                    </div>

                    {{-- Tenant Context --}}
                    <div>
                        <h3 class="font-bold text-lg mb-3">Contexto del Tenant</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="font-semibold">Negocio:</span>
                                <span>{{ $tenant->business_name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold">Plan:</span>
                                <span class="badge badge-primary badge-sm">{{ ucfirst($tenant->plan->name) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold">Color Palette:</span>
                                <span>{{ $tenant->colorPalette->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold">Año fundación:</span>
                                <span>{{ $tenant->founding_year ?: 'No configurado' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold">Años experiencia:</span>
                                <span>{{ $tenant->years_experience ?: '5+' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-semibold">Hero Image:</span>
                                <span class="text-xs {{ $customization->hero_filename ? 'text-success' : 'text-warning' }}">
                                    {{ $customization->hero_filename ?: 'Placeholder Unsplash' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="divider"></div>

                {{-- Quick Links --}}
                <div class="flex flex-wrap gap-3 justify-center">
                    <a href="/test/hero-fullscreen" class="btn btn-sm {{ $heroLayout === 'fullscreen' ? 'btn-primary' : 'btn-outline' }}">
                        Fullscreen
                    </a>
                    <a href="/test/hero-split" class="btn btn-sm {{ $heroLayout === 'split' ? 'btn-primary' : 'btn-outline' }}">
                        Split
                    </a>
                    <a href="/test/hero-gradient" class="btn btn-sm {{ $heroLayout === 'gradient' ? 'btn-primary' : 'btn-outline' }}">
                        Gradient
                    </a>
                    <a href="/test/hero-cards" class="btn btn-sm {{ $heroLayout === 'cards' ? 'btn-primary' : 'btn-outline' }}">
                        Cards
                    </a>
                </div>
            </div>
        </div>

        {{-- Instructions --}}
        <div class="alert alert-info mb-8">
            <span class="icon-[tabler--bulb] size-6"></span>
            <div>
                <h3 class="font-bold">Pruebas Recomendadas</h3>
                <ul class="text-sm mt-2 space-y-1 list-disc list-inside">
                    <li>Redimensiona la ventana para ver responsive behavior</li>
                    <li>Verifica que el navbar sticky funcione al hacer scroll</li>
                    <li>Revisa que el layout hero se adapte correctamente en mobile</li>
                    <li>Valida la integración navbar + hero (sin espacios extraños)</li>
                    <li>Prueba los CTAs (WhatsApp, teléfono, anclas)</li>
                </ul>
            </div>
        </div>

        {{-- Back button --}}
        <div class="text-center">
            <a href="/test" class="btn btn-outline btn-lg gap-2">
                <span class="icon-[tabler--arrow-left] size-5"></span>
                Volver al Index
            </a>
        </div>

    </div>

</body>
</html>
