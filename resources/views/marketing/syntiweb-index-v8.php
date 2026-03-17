<!DOCTYPE html>
<html lang="es" data-theme="light" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SYNTIweb — Tu Negocio Visible en Google en 5 Minutos</title>
    <meta name="description" content="SYNTIweb genera tu presencia digital automáticamente. Landing profesional, SEO automático, WhatsApp integrado. Para restaurantes, mecánicos, abogados y más.">
    <meta name="keywords" content="landing page Venezuela, presencia digital, negocio en Google, SYNTIweb, página web para negocios">

    <meta property="og:title" content="SYNTIweb — Tu Negocio Visible en Google">
    <meta property="og:description" content="Genera tu presencia digital automáticamente. En 5 minutos. Sin diseñador. Sin programador.">
    <meta property="og:type" content="website">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --mkt-blue: #1e40af;
            --mkt-indigo: #4f46e5;
            --mkt-purple: #7c3aed;
        }
        body { font-family: 'Inter', system-ui, sans-serif; }
        .mkt-gradient-text {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .mkt-gradient-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 40%, #1e3a5f 100%);
        }
        .mkt-gradient-cta {
            background: linear-gradient(135deg, #1e40af 0%, #4f46e5 50%, #7c3aed 100%);
        }
        .mkt-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .mkt-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -12px rgba(0,0,0,0.15);
        }
        .mkt-fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }
        .mkt-fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .mkt-blob {
            border-radius: 42% 58% 36% 64% / 45% 55% 45% 55%;
            animation: mkt-morph 8s ease-in-out infinite;
        }
        @keyframes mkt-morph {
            0%,100% { border-radius: 42% 58% 36% 64% / 45% 55% 45% 55%; }
            33% { border-radius: 55% 45% 60% 40% / 35% 65% 35% 65%; }
            66% { border-radius: 35% 65% 45% 55% / 55% 45% 55% 45%; }
        }
        .mkt-float { animation: mkt-float 6s ease-in-out infinite; }
        @keyframes mkt-float {
            0%,100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
        }
        .mkt-mockup {
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            border: 1px solid rgba(0,0,0,0.08);
        }
        /* Nav scroll state */
        .nav-scrolled { background: rgba(255,255,255,0.95) !important; backdrop-filter: blur(12px); box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
        .nav-scrolled .nav-link-light { color: #1e293b !important; }
        .nav-scrolled .nav-logo-light { color: #1e40af !important; }
        .nav-scrolled .nav-logo-accent { color: #64748b !important; }
    </style>
</head>
<body class="bg-white text-slate-800 antialiased" x-data="marketingApp()">

    {{-- ═══ NAVBAR ═══════════════════════════════════════════════════════ --}}
    <nav id="mkt-nav" class="fixed top-0 inset-x-0 z-50 transition-all duration-300 bg-[var(--sw-white)] border-b border-[var(--sw-border)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-20">
                {{-- Logo --}}
                <a href="#hero" class="flex items-center gap-2 group">
                    <img src="{{ asset('brand/syntiweb-logo-positive.svg') }}" 
                         alt="SYNTIweb" width="36" height="36" class="sw-logo-breathe">
                    <span class="text-xl font-extrabold tracking-tight">
                        <span class="text-[#1a1a1a] transition-colors">SYNTI</span><span class="text-[#4A80E4] transition-colors">web</span>
                    </span>
                </a>

                {{-- Desktop Nav --}}
                <div class="hidden md:flex items-center gap-8">
                    <a href="#solucion" class="text-sm font-medium transition-colors text-[var(--sw-text)] hover:text-[#4A80E4]">Cómo funciona</a>
                    <a href="#segmentos" class="text-sm font-medium transition-colors text-[var(--sw-text)] hover:text-[#4A80E4]">Segmentos</a>
                    <a href="#planes" class="text-sm font-medium transition-colors text-[var(--sw-text)] hover:text-[#4A80E4]">Planes</a>
                </div>

                {{-- CTA --}}
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}" class="hidden sm:inline-flex text-sm font-medium transition-colors text-[var(--sw-text)] hover:text-[#4A80E4]">Iniciar sesión</a>
                    <a href="{{ route('register') }}" class="inline-flex items-center text-sm py-1.5 px-3 lg:py-2 lg:px-4 rounded-lg font-medium transition-all bg-[#4A80E4] text-white border-0 shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40 hover:scale-105">
                        Crear gratis
                    </a>
                    {{-- Mobile menu --}}
                    <button @click="mobileNav = !mobileNav" class="md:hidden p-2 rounded-lg transition-colors text-[var(--sw-text)] hover:bg-[var(--sw-bg)]">
                        <span class="iconify tabler--menu-2 size-5"></span>
                    </button>
                </div>
            </div>
        </div>
        {{-- Mobile Menu --}}
        <div x-show="mobileNav" x-transition.opacity class="md:hidden bg-[var(--sw-white)] border-t border-[var(--sw-border)] shadow-xl">
            <div class="px-4 py-4 space-y-2">
                <a @click="mobileNav=false" href="#solucion" class="block px-4 py-2.5 text-sm font-medium text-[var(--sw-text)] rounded-lg hover:bg-[var(--sw-bg)]">Cómo funciona</a>
                <a @click="mobileNav=false" href="#segmentos" class="block px-4 py-2.5 text-sm font-medium text-[var(--sw-text)] rounded-lg hover:bg-[var(--sw-bg)]">Segmentos</a>
                <a @click="mobileNav=false" href="#planes" class="block px-4 py-2.5 text-sm font-medium text-[var(--sw-text)] rounded-lg hover:bg-[var(--sw-bg)]">Planes</a>
                <hr class="border-[var(--sw-border)]">
                <a href="{{ route('login') }}" class="block px-4 py-2.5 text-sm font-medium text-[var(--sw-text-muted)]">Iniciar sesión</a>
                <a href="{{ route('register') }}" class="block px-4 py-2.5 text-sm font-bold text-[#4A80E4]">Crear gratis →</a>
            </div>
        </div>
    </nav>

    {{-- ═══ SECTIONS ════════════════════════════════════════════════════ --}}
    @include('marketing.sections.hero')
    @include('marketing.sections.problema')
    @include('marketing.sections.solucion')
    @include('marketing.sections.segmentos')
    @include('marketing.sections.dashboard')
    @include('marketing.sections.valor')
    @include('marketing.sections.planes')
    @include('marketing.sections.estadisticas')
    @include('marketing.sections.configuracion')
    @include('marketing.sections.conversion')
    @include('marketing.sections.cta-final')

    {{-- ═══ FOOTER ══════════════════════════════════════════════════════ --}}
    <footer class="bg-[var(--sw-navy)] text-[var(--sw-text-muted)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                {{-- Brand --}}
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <img src="{{ asset('brand/syntiweb-logo-negative.svg') }}" 
                             alt="SYNTIweb" width="32" height="32">
                        <span class="text-xl font-extrabold text-white"><span style="color:#4A80E4">SYNTI</span><span style="color:#FFFFFF">web</span></span>
                    </div>
                    <p class="text-sm leading-relaxed max-w-sm">Tu negocio merece estar en Google. SYNTIweb genera tu presencia digital automáticamente, para que tú te enfoques en lo que mejor haces.</p>
                </div>
                {{-- Links --}}
                <div>
                    <h4 class="text-white font-semibold text-sm mb-4">Producto</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#solucion" class="hover:text-white transition-colors">Cómo funciona</a></li>
                        <li><a href="#segmentos" class="hover:text-white transition-colors">Segmentos</a></li>
                        <li><a href="#planes" class="hover:text-white transition-colors">Planes</a></li>
                    </ul>
                </div>
                {{-- Legal --}}
                <div>
                    <h4 class="text-white font-semibold text-sm mb-4">Legal</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">Privacidad</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Términos</a></li>
                        <li><a href="mailto:soporte@syntiweb.com" class="hover:text-white transition-colors">soporte@syntiweb.com</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-white/10 mt-12 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs">&copy; {{ date('Y') }} SYNTIweb — Todos los derechos reservados.</p>
                <p class="text-xs">Hecho con <span class="text-red-400">&#10084;</span> en Venezuela</p>
            </div>
        </div>
    </footer>

    {{-- ═══ SCRIPTS ═════════════════════════════════════════════════════ --}}
    <script>
        function marketingApp() {
            return {
                scrolled: false,
                mobileNav: false,
                init() {
                    const onScroll = () => { this.scrolled = window.scrollY > 60; };
                    window.addEventListener('scroll', onScroll, { passive: true });
                    onScroll();

                    // Intersection Observer for fade-in
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                entry.target.classList.add('visible');
                            }
                        });
                    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
                    document.querySelectorAll('.mkt-fade-in').forEach(el => observer.observe(el));
                }
            };
        }
    </script>
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js" defer></script>
<x-syntia-widget />
</body>
</html>
