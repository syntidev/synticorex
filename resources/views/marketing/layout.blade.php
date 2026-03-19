<!DOCTYPE html>
<html lang="es" data-theme="light" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Stack --}}
    @stack('seo')

    {{-- Meta bases SEO --}}
    <meta property="og:locale" content="es_VE">
    <meta property="og:site_name" content="SYNTIweb">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="canonical" href="{{ url()->current() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
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
        .mkt-float { animation: mkt-float 6s ease-in-out infinite; }
        @keyframes mkt-float {
            0%,100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
        }
    </style>
    @stack('head')
</head>
<body class="bg-white text-slate-800 antialiased">

    {{-- ═══ NAVBAR ════════════════════════════════════════════════════════ --}}
    <header class="flex flex-wrap lg:justify-start lg:flex-nowrap z-50 w-full bg-white border-b border-border sticky top-0">
        <nav class="relative max-w-7xl w-full mx-auto flex flex-wrap basis-full items-center justify-between px-4 sm:px-6 lg:px-8 py-3">

            {{-- Logo --}}
            <a href="/" class="flex items-center gap-2">
                <img src="{{ asset('brand/syntiweb-logo-positive.svg') }}" alt="SYNTIweb" width="34" height="34">
                <span class="text-xl font-extrabold tracking-tight">
                    <span style="color:#1a1a1a">SYNTI</span><span style="color:#4A80E4">web</span>
                </span>
            </a>

            {{-- Mobile toggle --}}
            <div class="flex items-center gap-2 lg:hidden">
                <button type="button"
                    class="hs-collapse-toggle p-2 rounded-lg text-slate-600 hover:bg-slate-100 transition"
                    data-hs-collapse="#mkt-layout-navbar-collapse"
                    aria-controls="mkt-layout-navbar-collapse"
                    aria-label="Toggle navigation">
                    <span class="iconify tabler--menu-2 size-5 hs-collapse-open:hidden"></span>
                    <span class="iconify tabler--x size-5 hidden hs-collapse-open:block"></span>
                </button>
            </div>

            {{-- Desktop + Collapse container --}}
            <div id="mkt-layout-navbar-collapse"
                 class="hs-collapse hidden overflow-hidden transition-all duration-300 basis-full lg:block lg:basis-auto lg:overflow-visible">
                <div class="flex flex-col gap-1 mt-4 lg:mt-0 lg:flex-row lg:items-center lg:gap-1">

                    {{-- Mega Productos --}}
                    <div class="hs-dropdown [--strategy:absolute] [--adaptive:none]">
                        <button type="button"
                            class="hs-dropdown-toggle flex items-center gap-1 text-sm font-medium text-slate-700 hover:bg-slate-100 px-3 py-2 rounded-lg transition cursor-pointer">
                            Productos
                            <span class="iconify tabler--chevron-down size-4 transition hs-dropdown-open:rotate-180"></span>
                        </button>
                        <div class="hs-dropdown-menu hs-dropdown-open:opacity-100 hidden opacity-0 z-10 mt-2 min-w-[320px] bg-white border border-border rounded-xl shadow-lg p-2 transition-[opacity,margin]">
                            <a href="{{ route('marketing.studio') }}"
                               class="flex items-start gap-3 p-3 rounded-lg hover:bg-slate-50 transition group">
                                <div class="mt-0.5 flex-shrink-0 w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center">
                                    <iconify-icon icon="tabler:layout-dashboard" width="18" height="18" class="text-blue-600"></iconify-icon>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">SYNTIstudio</p>
                                    <p class="text-xs text-slate-500">Web profesional con SEO automático</p>
                                </div>
                            </a>
                            <a href="{{ route('marketing.food') }}"
                               class="flex items-start gap-3 p-3 rounded-lg hover:bg-slate-50 transition group">
                                <div class="mt-0.5 flex-shrink-0 w-9 h-9 rounded-lg bg-orange-50 flex items-center justify-center">
                                    <iconify-icon icon="tabler:tools-kitchen-2" width="18" height="18" class="text-orange-500"></iconify-icon>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">SYNTIfood</p>
                                    <p class="text-xs text-slate-500">Menú digital con pedido a WhatsApp</p>
                                </div>
                            </a>
                            <a href="{{ route('marketing.cat') }}"
                               class="flex items-start gap-3 p-3 rounded-lg hover:bg-slate-50 transition group">
                                <div class="mt-0.5 flex-shrink-0 w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center">
                                    <iconify-icon icon="tabler:shopping-bag" width="18" height="18" class="text-emerald-600"></iconify-icon>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">SYNTIcat</p>
                                    <p class="text-xs text-slate-500">Catálogo visual con carrito WhatsApp</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <a href="{{ route('marketing.planes') }}" class="text-sm font-medium text-slate-700 hover:bg-slate-100 px-3 py-2 rounded-lg transition">Planes</a>
                    <a href="{{ route('marketing.demos') }}" class="text-sm font-medium text-slate-700 hover:bg-slate-100 px-3 py-2 rounded-lg transition">Demos</a>
                    <a href="{{ route('blog.index') }}" class="text-sm font-medium text-slate-700 hover:bg-slate-100 px-3 py-2 rounded-lg transition">Blog</a>
                    <a href="https://docs.syntiweb.com" target="_blank" rel="noopener" class="text-sm font-medium text-slate-700 hover:bg-slate-100 px-3 py-2 rounded-lg transition">Docs</a>
                    <a href="{{ route('marketing.contacto') }}" class="text-sm font-medium text-slate-700 hover:bg-slate-100 px-3 py-2 rounded-lg transition">Contacto</a>

                    {{-- Divider + CTA (desktop) --}}
                    <div class="hidden lg:flex items-center gap-2 ml-2 pl-4 border-l border-border">
                        <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:bg-slate-100 px-3 py-2 rounded-lg transition">
                            Iniciar sesión
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center text-sm py-2 px-4 rounded-lg font-semibold bg-[#4A80E4] text-white hover:bg-[#3a6fd4] transition shadow-sm shadow-blue-500/20">
                            Crear gratis
                        </a>
                    </div>

                    {{-- Mobile CTA --}}
                    <div class="lg:hidden border-t border-border mt-2 pt-3 flex flex-col gap-2">
                        <a href="{{ route('login') }}" class="block px-3 py-2.5 text-sm font-medium text-slate-600 rounded-lg hover:bg-slate-50 text-center">Iniciar sesión</a>
                        <a href="{{ route('register') }}" class="block px-3 py-2.5 text-sm font-bold text-white bg-[#4A80E4] rounded-lg text-center">Crear gratis →</a>
                    </div>

                </div>
            </div>
        </nav>
    </header>

    {{-- ═══ CONTENT ════════════════════════════════════════════════════════ --}}
    @yield('content')

    {{-- ═══ FOOTER ════════════════════════════════════════════════════════ --}}
    @include('marketing.sections.footer-mkt')

    {{-- ═══ SCRIPTS ════════════════════════════════════════════════════════ --}}
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js" defer></script>
    <x-syntia-widget />
    @stack('scripts')

</body>
</html>
