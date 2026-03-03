{{-- ═══ SOLUCIÓN — "3 pasos y listo" ══════════════════════════════════ --}}
<section id="solucion" class="relative py-20 lg:py-28 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section header --}}
        <div class="text-center mb-16 mkt-fade-in">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-600 text-sm font-semibold mb-4">
                <span class="iconify tabler--sparkles size-4"></span>
                Así de simple
            </div>
            <h2 class="text-3xl lg:text-5xl font-extrabold text-slate-900 mb-4">
                Tu página lista en <span class="mkt-gradient-text">3 pasos</span>
            </h2>
            <p class="text-lg text-slate-500 max-w-2xl mx-auto">
                No necesitas saber de diseño, programación ni marketing. SYNTIweb hace todo el trabajo técnico por ti.
            </p>
        </div>

        {{-- Steps --}}
        <div class="grid lg:grid-cols-3 gap-8 lg:gap-6 relative">
            {{-- Connecting line (desktop only) --}}
            <div class="hidden lg:block absolute top-24 left-[20%] right-[20%] h-0.5 bg-gradient-to-r from-blue-200 via-indigo-200 to-purple-200"></div>

            {{-- Step 1 --}}
            <div class="mkt-fade-in relative">
                <div class="mkt-card rounded-2xl bg-white border border-slate-100 p-8 shadow-sm text-center relative z-10">
                    <div class="w-12 h-12 mx-auto mb-6 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-extrabold text-lg shadow-lg shadow-blue-500/25">1</div>
                    <div class="w-16 h-16 mx-auto mb-5 rounded-2xl bg-blue-50 flex items-center justify-center">
                        <span class="iconify tabler--layout-grid size-8 text-blue-500"></span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Elige tu tipo de negocio</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        Restaurante, peluquería, taller mecánico, consultorio, tienda... Selecciona tu segmento y SYNTIweb se adapta automáticamente.
                    </p>
                    {{-- Segment pills --}}
                    <div class="flex flex-wrap gap-1.5 justify-center mt-5">
                        <span class="inline-flex items-center py-0.5 px-2 rounded-full text-sm font-medium bg-orange-50 text-orange-600 border border-orange-200">Restaurante</span>
                        <span class="inline-flex items-center py-0.5 px-2 rounded-full text-sm font-medium bg-blue-50 text-blue-600 border border-blue-200">Mecánico</span>
                        <span class="inline-flex items-center py-0.5 px-2 rounded-full text-sm font-medium bg-pink-50 text-pink-600 border border-pink-200">Salón</span>
                        <span class="inline-flex items-center py-0.5 px-2 rounded-full text-sm font-medium bg-indigo-50 text-indigo-600 border border-indigo-200">Abogado</span>
                        <span class="inline-flex items-center py-0.5 px-2 rounded-full text-sm font-medium bg-emerald-50 text-emerald-600 border border-emerald-200">Tienda</span>
                    </div>
                </div>
            </div>

            {{-- Step 2 --}}
            <div class="mkt-fade-in relative" style="transition-delay: 0.15s;">
                <div class="mkt-card rounded-2xl bg-white border border-slate-100 p-8 shadow-sm text-center relative z-10">
                    <div class="w-12 h-12 mx-auto mb-6 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white font-extrabold text-lg shadow-lg shadow-indigo-500/25">2</div>
                    <div class="w-16 h-16 mx-auto mb-5 rounded-2xl bg-indigo-50 flex items-center justify-center">
                        <span class="iconify tabler--forms size-8 text-indigo-500"></span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Llena 5 campos</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        Nombre, teléfono, dirección, horario y descripción. Es todo lo que necesitamos para generar tu presencia digital completa.
                    </p>
                    {{-- Fields mock --}}
                    <div class="mt-5 space-y-2 text-left max-w-[200px] mx-auto">
                        <div class="flex items-center gap-2">
                            <span class="iconify tabler--check size-3.5 text-emerald-500"></span>
                            <div class="h-2 flex-1 bg-indigo-100 rounded"></div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="iconify tabler--check size-3.5 text-emerald-500"></span>
                            <div class="h-2 flex-1 bg-indigo-100 rounded"></div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="iconify tabler--check size-3.5 text-emerald-500"></span>
                            <div class="h-2 w-3/4 bg-indigo-100 rounded"></div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="iconify tabler--check size-3.5 text-emerald-500"></span>
                            <div class="h-2 w-4/5 bg-indigo-100 rounded"></div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="iconify tabler--check size-3.5 text-emerald-500"></span>
                            <div class="h-2 w-2/3 bg-indigo-100 rounded"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Step 3 --}}
            <div class="mkt-fade-in relative" style="transition-delay: 0.3s;">
                <div class="mkt-card rounded-2xl bg-white border border-slate-100 p-8 shadow-sm text-center relative z-10">
                    <div class="w-12 h-12 mx-auto mb-6 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white font-extrabold text-lg shadow-lg shadow-purple-500/25">3</div>
                    <div class="w-16 h-16 mx-auto mb-5 rounded-2xl bg-purple-50 flex items-center justify-center">
                        <span class="iconify tabler--rocket size-8 text-purple-500"></span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">¡Tu página está lista!</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        Landing profesional, SEO automático, código QR listo para imprimir, WhatsApp integrado. Todo en tu propio subdominio.
                    </p>
                    {{-- Features --}}
                    <div class="flex flex-wrap gap-1.5 justify-center mt-5">
                        <span class="inline-flex items-center py-0.5 px-2 rounded-full text-sm font-medium bg-purple-50 text-purple-600 border border-purple-200">
                            <span class="iconify tabler--search size-3 mr-0.5"></span> SEO
                        </span>
                        <span class="inline-flex items-center py-0.5 px-2 rounded-full text-sm font-medium bg-purple-50 text-purple-600 border border-purple-200">
                            <span class="iconify tabler--qrcode size-3 mr-0.5"></span> QR
                        </span>
                        <span class="inline-flex items-center py-0.5 px-2 rounded-full text-sm font-medium bg-purple-50 text-purple-600 border border-purple-200">
                            <span class="iconify tabler--brand-whatsapp size-3 mr-0.5"></span> WhatsApp
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom CTA --}}
        <div class="text-center mt-14 mkt-fade-in">
            <a href="{{ route('register') }}" class="inline-flex items-center py-3 px-10 rounded-lg font-bold transition-all bg-gradient-to-r from-blue-500 to-indigo-500 text-white border-0 shadow-lg shadow-blue-500/20 hover:shadow-blue-500/40 hover:scale-[1.02]">
                Empezar ahora — es gratis
            </a>
            <p class="text-sm text-slate-400 mt-3">No requiere tarjeta de crédito</p>
        </div>
    </div>
</section>
