{{-- ═══════════════════════════════════════════════════════════════════════════════
     SYNTIweb — Layout interno (back-office)
     Branding SyntiWeb: #4A80E4, Geist, logo positive
     NO aplicar a vistas de tenant / landing
═══════════════════════════════════════════════════════════════════════════════ --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SYNTIweb — Gestión de negocios">
    <meta name="author" content="SYNTIweb">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">

    <title>{{ $title ?? 'SYNTIweb' }}</title>

    {{-- SYNTIweb Favicon Kit --}}
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="manifest" href="/site.webmanifest">
    <meta name="theme-color" content="#4A80E4">

    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <style>
        :root {
            --sw-blue: #4A80E4;
            --sw-navy: #1a1a1a;
            --sw-bg: #F8FAFF;
            --sw-surface: #FFFFFF;
            --sw-border: #E2E8F4;
            --sw-text: #1a1a1a;
            --sw-text-muted: #64748b;
        }
        body { font-family: 'Geist', ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>
<body class="min-h-screen" style="background: var(--sw-bg);">

    {{-- ── Navbar ────────────────────────────────────────────────────────── --}}
    <nav class="sticky top-0 z-50 border-b" style="background: var(--sw-surface); border-color: var(--sw-border);">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Logo SyntiWeb (fondo claro) --}}
                <a href="{{ route('tenants.index') }}" class="flex items-center gap-2.5 shrink-0">
                    <img src="/brand/syntiweb-logo-positive.svg" alt="SYNTIweb" width="32" height="32">
                    <span class="font-bold text-lg tracking-tight">
                        <span style="color: #1a1a1a;">SYNTI</span><span style="color: #4A80E4;">web</span>
                    </span>
                </a>

                {{-- Nav links (desktop) --}}
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('tenants.index') }}"
                       class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('tenants.index') ? 'text-[#4A80E4] bg-[#4A80E4]/10' : 'text-[#64748b] hover:text-[#1a1a1a] hover:bg-gray-100' }}">
                        <span class="iconify tabler--building-store size-4 inline-block align-[-3px] mr-1"></span>
                        Mis Negocios
                    </a>
                    <a href="{{ route('onboarding.selector') }}"
                       class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('onboarding.*') ? 'text-[#4A80E4] bg-[#4A80E4]/10' : 'text-[#64748b] hover:text-[#1a1a1a] hover:bg-gray-100' }}">
                        <span class="iconify tabler--plus size-4 inline-block align-[-3px] mr-1"></span>
                        Crear Negocio
                    </a>
                </div>

                {{-- User menu --}}
                <div class="flex items-center gap-3" x-data="{ open: false }">
                    {{-- Mobile nav toggle --}}
                    <a href="{{ route('tenants.index') }}" class="md:hidden p-2 rounded-lg text-[#64748b] hover:text-[#1a1a1a] hover:bg-gray-100 transition-colors cursor-pointer" aria-label="Mis Negocios">
                        <span class="iconify tabler--building-store size-5"></span>
                    </a>

                    <button @click="open = !open"
                            class="flex items-center gap-2 px-3 py-1.5 rounded-lg border transition-colors cursor-pointer hover:bg-gray-50"
                            style="border-color: var(--sw-border);">
                        <div class="size-7 rounded-full flex items-center justify-center text-xs font-bold text-white" style="background: #4A80E4;">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <span class="hidden sm:inline text-sm font-medium" style="color: var(--sw-text);">
                            {{ auth()->user()->name ?? 'Usuario' }}
                        </span>
                        <span class="iconify tabler--chevron-down size-4" style="color: var(--sw-text-muted);"></span>
                    </button>

                    {{-- Dropdown --}}
                    <div x-show="open" x-cloak @click.outside="open = false"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-4 top-14 w-56 rounded-xl border shadow-lg py-1 z-50"
                         style="background: var(--sw-surface); border-color: var(--sw-border);">
                        <div class="px-4 py-3 border-b" style="border-color: var(--sw-border);">
                            <p class="text-sm font-semibold" style="color: var(--sw-text);">{{ auth()->user()->name ?? 'Usuario' }}</p>
                            <p class="text-xs" style="color: var(--sw-text-muted);">{{ auth()->user()->email ?? '' }}</p>
                        </div>
                        <a href="{{ route('tenants.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm transition-colors hover:bg-gray-50 cursor-pointer" style="color: var(--sw-text);">
                            <span class="iconify tabler--building-store size-4" style="color: var(--sw-text-muted);"></span>
                            Mis Negocios
                        </a>
                        <div class="border-t my-1" style="border-color: var(--sw-border);"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors cursor-pointer">
                                <span class="iconify tabler--logout size-4"></span>
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </nav>

    {{-- ── Flash messages ────────────────────────────────────────────────── --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-4">
        @if ($errors->any())
        <div class="mb-4 p-4 rounded-xl border border-red-200 bg-red-50">
            <div class="flex items-start gap-3">
                <span class="iconify tabler--alert-triangle size-5 text-red-600 shrink-0 mt-0.5"></span>
                <ul class="text-sm text-red-700 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        @if (session('success'))
        <div class="mb-4 p-4 rounded-xl border border-green-200 bg-green-50">
            <div class="flex items-center gap-3">
                <span class="iconify tabler--circle-check size-5 text-green-600 shrink-0"></span>
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        @if (session('error'))
        <div class="mb-4 p-4 rounded-xl border border-red-200 bg-red-50">
            <div class="flex items-center gap-3">
                <span class="iconify tabler--alert-circle size-5 text-red-600 shrink-0"></span>
                <p class="text-sm text-red-700">{{ session('error') }}</p>
            </div>
        </div>
        @endif
    </div>

    {{-- ── Page content (via $slot for <x-app-layout>) ───────────────────── --}}
    <main>
        {{ $slot }}
    </main>

    {{-- ── Footer mínimo ─────────────────────────────────────────────────── --}}
    <footer class="mt-16 border-t py-6" style="border-color: var(--sw-border);">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-xs" style="color: var(--sw-text-muted);">
                &copy; {{ date('Y') }} SYNTIweb. Todos los derechos reservados.
            </p>
            <div class="flex items-center gap-4 text-xs" style="color: var(--sw-text-muted);">
                <a href="{{ route('marketing.terms') }}" class="hover:text-[#4A80E4] transition-colors">Términos</a>
                <a href="{{ route('marketing.privacy') }}" class="hover:text-[#4A80E4] transition-colors">Privacidad</a>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
