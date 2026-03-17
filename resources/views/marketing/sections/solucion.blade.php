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
        <div class="max-w-3xl mx-auto">
    <div class="flex items-start gap-0">

        {{-- Step 1 --}}
        <div class="flex-1 flex flex-col items-center relative">
            <div class="flex items-center w-full">
                <div class="flex-1"></div>
                <div class="w-10 h-10 rounded-full bg-[#2B6FFF] border-2 border-[#2B6FFF] flex items-center justify-center font-bold text-white text-sm z-10 shrink-0">
                    <span class="iconify tabler--check size-5"></span>
                </div>
                <div class="flex-1 h-px bg-[#2B6FFF]"></div>
            </div>
            <div class="mt-4 text-center px-2">
                <div class="text-xs font-bold uppercase tracking-wider text-[#2B6FFF] mb-1">Paso 01</div>
                <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-blue-50 flex items-center justify-center">
                    <span class="iconify tabler--layout-grid size-6 text-blue-500"></span>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-2">Elige tu tipo de negocio</h3>
                <p class="text-sm text-slate-500 leading-relaxed">Restaurante, peluquería, taller, consultorio, tienda... SYNTIweb se adapta automáticamente.</p>
            </div>
        </div>

        {{-- Step 2 --}}
        <div class="flex-1 flex flex-col items-center relative">
            <div class="flex items-center w-full">
                <div class="flex-1 h-px bg-[#2B6FFF]"></div>
                <div class="w-10 h-10 rounded-full bg-white border-2 border-[#2B6FFF] flex items-center justify-center font-bold text-[#2B6FFF] text-sm z-10 shrink-0 shadow-[0_0_0_4px_rgba(43,111,255,0.12)]">2</div>
                <div class="flex-1 h-px bg-slate-200"></div>
            </div>
            <div class="mt-4 text-center px-2">
                <div class="text-xs font-bold uppercase tracking-wider text-[#2B6FFF] mb-1">Paso 02</div>
                <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-indigo-50 flex items-center justify-center">
                    <span class="iconify tabler--forms size-6 text-indigo-500"></span>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-2">Llena 5 campos</h3>
                <p class="text-sm text-slate-500 leading-relaxed">Nombre, teléfono, dirección, horario y descripción. En 5 minutos ya está todo enviado.</p>
            </div>
        </div>

        {{-- Step 3 --}}
        <div class="flex-1 flex flex-col items-center relative">
            <div class="flex items-center w-full">
                <div class="flex-1 h-px bg-slate-200"></div>
                <div class="w-10 h-10 rounded-full bg-white border-2 border-slate-200 flex items-center justify-center font-bold text-slate-400 text-sm z-10 shrink-0">3</div>
                <div class="flex-1"></div>
            </div>
            <div class="mt-4 text-center px-2">
                <div class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Paso 03</div>
                <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-purple-50 flex items-center justify-center">
                    <span class="iconify tabler--rocket size-6 text-purple-500"></span>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-2">¡Tu página está lista!</h3>
                <p class="text-sm text-slate-500 leading-relaxed">Landing profesional, SEO automático, QR listo para imprimir y WhatsApp integrado.</p>
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
