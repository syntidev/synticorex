{{-- ═══ HERO — "Tu Negocio Merece Estar en Google" ═══════════════════ --}}
<section id="hero" class="relative min-h-screen flex items-center overflow-hidden mkt-gradient-hero">
    {{-- Decorative blobs --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 -left-20 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl mkt-blob"></div>
        <div class="absolute bottom-10 right-0 w-96 h-96 bg-indigo-500/8 rounded-full blur-3xl mkt-blob" style="animation-delay: -3s;"></div>
        <div class="absolute top-1/3 right-1/4 w-64 h-64 bg-purple-500/5 rounded-full blur-3xl mkt-blob" style="animation-delay: -5s;"></div>
        {{-- Grid pattern --}}
        <div class="absolute inset-0 opacity-5" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.3) 1px, transparent 0); background-size: 40px 40px;"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-16 lg:pt-32 lg:pb-24">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            {{-- ── Copy ──────────────────────────────────── --}}
            <div class="text-center lg:text-left">
                {{-- Badge --}}
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-500/10 border border-blue-400/20 mb-8">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-sm text-blue-200 font-medium">Funciona ahora mismo</span>
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-[1.1] mb-6">
                    Tu Negocio Merece
                    <span class="block mt-2 bg-gradient-to-r from-blue-400 via-indigo-400 to-purple-400 bg-clip-text text-transparent">Estar en Google</span>
                </h1>

                <p class="text-lg lg:text-xl text-blue-100/60 mb-10 leading-relaxed max-w-lg mx-auto lg:mx-0">
                    No importa si eres panadero, mecánico o abogado.
                    <span class="text-white font-medium">SYNTIweb genera tu presencia digital automáticamente.</span>
                    En 5 minutos. Sin diseñador. Sin programador.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('register') }}" class="inline-flex items-center py-3 px-8 rounded-lg font-bold transition-all bg-gradient-to-r from-blue-500 to-indigo-500 text-white border-0 shadow-xl shadow-blue-500/25 hover:shadow-blue-500/40 hover:scale-[1.02] text-base">
                        Crear mi página gratis
                        <span class="iconify tabler--arrow-right size-5"></span>
                    </a>
                    <a href="#solucion" class="inline-flex items-center py-3 px-6 rounded-lg font-medium transition-all border border-white/20 text-white hover:bg-white/10">
                        <span class="iconify tabler--player-play size-5"></span>
                        Ver cómo funciona
                    </a>
                </div>

                {{-- Trust --}}
                <div class="flex items-center gap-6 mt-10 justify-center lg:justify-start text-blue-200/40 text-sm">
                    <div class="flex items-center gap-1.5">
                        <span class="iconify tabler--shield-check size-4"></span>
                        <span>Sin tarjeta</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="iconify tabler--clock size-4"></span>
                        <span>5 min setup</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="iconify tabler--device-mobile size-4"></span>
                        <span>100% responsive</span>
                    </div>
                </div>
            </div>

            {{-- ── Mockup visual ─────────────────────────── --}}
            <div class="relative hidden lg:flex items-center justify-center">
                {{-- Main browser mockup --}}
                <div class="mkt-float relative w-full max-w-md">
                    <div class="rounded-2xl overflow-hidden shadow-2xl shadow-black/30 border border-white/10">
                        {{-- Browser bar --}}
                        <div class="bg-slate-800 px-4 py-2.5 flex items-center gap-2">
                            <div class="flex gap-1.5">
                                <div class="w-2.5 h-2.5 rounded-full bg-red-400/80"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-amber-400/80"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-emerald-400/80"></div>
                            </div>
                            <div class="flex-1 ml-3 bg-slate-700/60 rounded-md px-3 py-1 text-xs text-slate-400 font-mono">
                                tubarberia.syntiweb.com
                            </div>
                        </div>
                        {{-- Landing mock content --}}
                        <div class="bg-gradient-to-b from-slate-50 to-white p-6 space-y-4">
                            {{-- Hero mock --}}
                            <div class="bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl p-5 text-white">
                                <div class="w-16 h-2 bg-white/40 rounded mb-2"></div>
                                <div class="w-32 h-3 bg-white/70 rounded mb-1"></div>
                                <div class="w-24 h-2 bg-white/40 rounded"></div>
                                <div class="mt-3 w-20 h-6 bg-white/30 rounded-lg"></div>
                            </div>
                            {{-- Products mock --}}
                            <div class="grid grid-cols-3 gap-2">
                                <div class="bg-slate-100 rounded-lg p-2">
                                    <div class="w-full h-10 bg-slate-200 rounded mb-1.5"></div>
                                    <div class="w-12 h-1.5 bg-slate-300 rounded"></div>
                                    <div class="w-8 h-1.5 bg-emerald-300 rounded mt-1"></div>
                                </div>
                                <div class="bg-slate-100 rounded-lg p-2">
                                    <div class="w-full h-10 bg-slate-200 rounded mb-1.5"></div>
                                    <div class="w-14 h-1.5 bg-slate-300 rounded"></div>
                                    <div class="w-10 h-1.5 bg-emerald-300 rounded mt-1"></div>
                                </div>
                                <div class="bg-slate-100 rounded-lg p-2">
                                    <div class="w-full h-10 bg-slate-200 rounded mb-1.5"></div>
                                    <div class="w-10 h-1.5 bg-slate-300 rounded"></div>
                                    <div class="w-6 h-1.5 bg-emerald-300 rounded mt-1"></div>
                                </div>
                            </div>
                            {{-- Contact mock --}}
                            <div class="flex items-center gap-3">
                                <div class="w-16 h-7 bg-emerald-500 rounded-lg flex items-center justify-center">
                                    <span class="iconify tabler--brand-whatsapp size-4 text-white"></span>
                                </div>
                                <div>
                                    <div class="w-16 h-1.5 bg-slate-200 rounded"></div>
                                    <div class="w-24 h-1.5 bg-slate-200 rounded mt-1"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Floating accent cards --}}
                <div class="absolute -top-4 -right-4 bg-white rounded-xl shadow-xl p-3 flex items-center gap-2 mkt-float" style="animation-delay: -2s;">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                        <span class="iconify tabler--search size-4 text-emerald-600"></span>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-800">En Google</div>
                        <div class="text-[10px] text-emerald-600 font-medium">Posición #1</div>
                    </div>
                </div>
                <div class="absolute -bottom-2 -left-6 bg-white rounded-xl shadow-xl p-3 flex items-center gap-2 mkt-float" style="animation-delay: -4s;">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                        <span class="iconify tabler--eye size-4 text-blue-600"></span>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-800">Visible 24/7</div>
                        <div class="text-[10px] text-blue-600 font-medium">Siempre online</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Scroll indicator --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-white/30 animate-bounce">
        <span class="text-xs font-medium">Descubre más</span>
        <span class="iconify tabler--chevron-down size-5"></span>
    </div>
</section>
