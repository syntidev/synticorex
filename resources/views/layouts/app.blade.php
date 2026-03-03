<!DOCTYPE html>
<html lang="es" data-theme="{{ auth()->user()?->tenant?->customization?->theme_slug ?? 'light' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SYNTIweb Dashboard - Sistema multitenant">
    <meta name="author" content="SYNTIweb">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'SYNTIweb Dashboard')</title>
    
    @php($settings = $settings ?? auth()->user()?->tenant?->customization)
    <style>
        :root {
            --brand-50: {{ $settings->color_light ?? '#eff6ff' }};
            --brand-500: {{ $settings->color_main ?? '#3b82f6' }};
            --brand-600: {{ $settings->color_hover ?? '#2563eb' }};
            --brand-700: {{ $settings->color_dark ?? '#1d4ed8' }};
        }
    </style>

    <!-- FlyonUI + Tailwind CSS Compilado -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Additional CSS -->
    @stack('styles')
</head>
<body class="bg-gray-50" x-data>
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-gray-900">SYNTIweb</h1>
                </div>
                
                <!-- Nav Items -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#" class="text-gray-700 hover:text-gray-900 transition">Dashboard</a>
                    <a href="#" class="text-gray-700 hover:text-gray-900 transition">Tenants</a>
                    <a href="#" class="text-gray-700 hover:text-gray-900 transition">Productos</a>
                    <a href="#" class="text-gray-700 hover:text-gray-900 transition">Servicios</a>
                </div>
                
                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="text-gray-700 hover:text-gray-900 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        
                        <div x-show="open" @click.outside="open = false" 
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Perfil</a>
                            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Configuración</a>
                            <hr class="my-2">
                            <form method="POST" action="#">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    Cerrar sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <!-- Messages / Alerts -->
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <h3 class="text-red-800 font-semibold mb-2">Errores encontrados:</h3>
                <ul class="text-red-700 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-800">✓ {{ session('success') }}</p>
            </div>
        @endif
        
        @if (session('error'))
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-red-800">✗ {{ session('error') }}</p>
            </div>
        @endif
        
        <!-- Page Content -->
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-16">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-4 gap-8 mb-8">
                <!-- About -->
                <div>
                    <h4 class="font-semibold text-gray-900 mb-4">SYNTIweb</h4>
                    <p class="text-gray-600 text-sm">Sistema multitenant para gestión de negocios.</p>
                </div>
                
                <!-- Links -->
                <div>
                    <h4 class="font-semibold text-gray-900 mb-4">Producto</h4>
                    <ul class="space-y-2 text-gray-600 text-sm">
                        <li><a href="#" class="hover:text-gray-900">Características</a></li>
                        <li><a href="#" class="hover:text-gray-900">Documentación</a></li>
                        <li><a href="#" class="hover:text-gray-900">API</a></li>
                    </ul>
                </div>
                
                <!-- Company -->
                <div>
                    <h4 class="font-semibold text-gray-900 mb-4">Empresa</h4>
                    <ul class="space-y-2 text-gray-600 text-sm">
                        <li><a href="#" class="hover:text-gray-900">Nosotros</a></li>
                        <li><a href="#" class="hover:text-gray-900">Blog</a></li>
                        <li><a href="#" class="hover:text-gray-900">Contacto</a></li>
                    </ul>
                </div>
                
                <!-- Legal -->
                <div>
                    <h4 class="font-semibold text-gray-900 mb-4">Legal</h4>
                    <ul class="space-y-2 text-gray-600 text-sm">
                        <li><a href="#" class="hover:text-gray-900">Privacidad</a></li>
                        <li><a href="#" class="hover:text-gray-900">Términos</a></li>
                        <li><a href="#" class="hover:text-gray-900">Cookies</a></li>
                    </ul>
                </div>
            </div>
            
            <!-- Bottom Footer -->
            <div class="border-t border-gray-200 pt-8">
                <div class="flex justify-between items-center">
                    <p class="text-gray-600 text-sm">&copy; 2026 SYNTIweb. Todos los derechos reservados.</p>
                    <p class="text-gray-600 text-sm">Made with ❤️ by SYNTIweb Team</p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Additional JS -->
    @stack('scripts')
</body>
</html>
