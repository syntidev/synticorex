{{-- ═══ HERO — "Tu Negocio Merece Estar en Google" ═══════════════════ --}}
@php $section = \App\Models\LandingSection::forKey('hero'); @endphp
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
                    Tu Negocio Existe.<br>
                    <span class="block mt-2 bg-gradient-to-r from-blue-400 via-indigo-400 to-purple-400 bg-clip-text text-transparent">
                        Pero Internet No Lo Sabe.
                    </span>
                </h1>

                <p class="text-lg lg:text-xl text-blue-100/60 mb-10 leading-relaxed max-w-lg mx-auto lg:mx-0">
                    Cada día que no estás en línea, un cliente tuyo le compra a otro.
                    <span class="text-white font-medium">SYNTIweb lo pone en internet esta semana.</span>
                    Tú solo vendes.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('register') }}" class="inline-flex items-center py-3 px-8 rounded-lg font-bold transition-all bg-gradient-to-r from-blue-500 to-indigo-500 text-white border-0 shadow-xl shadow-blue-500/25 hover:shadow-blue-500/40 hover:scale-[1.02] text-base">
                        {{ $section?->content['cta_primary'] ?? 'Crear mi página gratis' }}
                        <span class="iconify tabler--arrow-right size-5"></span>
                    </a>
                    <a href="#solucion" class="inline-flex items-center py-3 px-6 rounded-lg font-medium transition-all border border-white/20 text-white hover:bg-white/10">
                        <span class="iconify tabler--player-play size-5"></span>
                        {{ $section?->content['cta_secondary'] ?? 'Ver cómo funciona' }}
                    </a>
                </div>

                {{-- Trust --}}
                <div class="flex flex-wrap items-center gap-x-6 gap-y-2 mt-10 justify-center lg:justify-start text-blue-200/40 text-sm">
                    <div class="flex items-center gap-1.5">
                        <span class="iconify tabler--shield-check size-4"></span>
                        <span>Sin tarjeta de crédito</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="iconify tabler--clock size-4"></span>
                        <span>Listo en 48 horas</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="iconify tabler--currency-dollar size-4"></span>
                        <span>Tasa BCV automática</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="iconify tabler--qrcode size-4"></span>
                        <span>QR permanente incluido</span>
                    </div>
                </div>
            </div>

            {{-- ── Mockup visual ─────────────────────────── --}}
            <div class="relative hidden lg:flex items-center justify-center">
                {{-- iPhone 14 mockup --}}
                <div class="mkt-float relative" style="width:220px;filter:drop-shadow(0 24px 48px rgba(0,0,0,0.35));">
                    <div style="background:#1a1a2e;border-radius:44px;padding:6px;border:1px solid rgba(255,255,255,0.08);">
                        <div style="background:#f8fafc;border-radius:38px;overflow:hidden;">
                            {{-- Isla dinámica --}}
                            <div style="background:#1a1a2e;height:44px;display:flex;align-items:center;justify-content:space-between;padding:0 18px;position:relative;">
                                <span style="font-size:10px;font-weight:600;color:#fff;">9:41</span>
                                <div style="width:72px;height:20px;background:#000;border-radius:10px;position:absolute;top:12px;left:50%;transform:translateX(-50%);"></div>
                                <div style="display:flex;gap:3px;align-items:flex-end;">
                                    <div style="width:2px;height:4px;background:#fff;border-radius:1px;"></div>
                                    <div style="width:2px;height:6px;background:#fff;border-radius:1px;"></div>
                                    <div style="width:2px;height:8px;background:#fff;border-radius:1px;"></div>
                                    <div style="width:2px;height:10px;background:#fff;border-radius:1px;"></div>
                                </div>
                            </div>
                            {{-- Screen content: landing pública del negocio --}}
                            <div style="padding:0;background:#ffffff;overflow:hidden;">
                                <img src="{{ asset('brand/demo_phone.png') }}"
                                     alt="Demo SYNTIweb"
                                     style="width:100%;display:block;border-radius:0;">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Floating cards --}}
                <div class="absolute top-8 -right-4 bg-white rounded-xl shadow-xl p-3 flex items-center gap-2 mkt-float" style="animation-delay:-2s;">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                        <span class="iconify tabler--search size-4 text-emerald-600"></span>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-800">En Google</div>
                        <div class="text-[10px] text-emerald-600 font-medium">Posición #1</div>
                    </div>
                </div>
                <div class="absolute bottom-8 -left-4 bg-white rounded-xl shadow-xl p-3 flex items-center gap-2 mkt-float" style="animation-delay:-4s;">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                        <span class="iconify tabler--eye size-4 text-blue-600"></span>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-800">Visible 24/7</div>
                        <div class="text-[10px] text-blue-600 font-medium">Siempre online</div>
                    </div>
                </div>
                {{-- Badge URL --}}
                <div class="absolute top-1/2 -right-2 -translate-y-1/2 bg-slate-800 rounded-lg px-3 py-1.5 mkt-float" style="animation-delay:-3s;">
                    <span class="text-[9px] font-mono text-slate-400">tubarberia.<span class="text-blue-400">syntiweb.com</span></span>
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
