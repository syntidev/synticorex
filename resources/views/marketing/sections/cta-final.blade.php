{{-- ═══ CTA FINAL — "Tu negocio merece ser encontrado" ═════════════════ --}}
<section id="cta-final" class="relative py-20 lg:py-28 overflow-hidden">
    {{-- Full gradient bg --}}
    <div class="absolute inset-0 mkt-gradient-cta"></div>
    {{-- Pattern --}}
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.4) 1px, transparent 0); background-size: 32px 32px;"></div>
    {{-- Blobs --}}
    <div class="absolute top-10 left-10 w-64 h-64 bg-white/5 rounded-full blur-3xl mkt-blob"></div>
    <div class="absolute bottom-10 right-10 w-80 h-80 bg-white/5 rounded-full blur-3xl mkt-blob" style="animation-delay: -4s;"></div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="mkt-fade-in">
            {{-- Icon --}}
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/20 shadow-xl mb-8">
                <span class="iconify tabler--rocket size-8 text-white"></span>
            </div>

            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-tight mb-6">
                Tu negocio merece ser
                <span class="block mt-2 bg-gradient-to-r from-blue-200 via-indigo-200 to-purple-200 bg-clip-text text-transparent">encontrado</span>
            </h2>

            <p class="text-xl text-white/70 mb-10 leading-relaxed max-w-2xl mx-auto">
                Cada día que pasa sin presencia digital es un día que tus clientes encuentran a otro.
                <span class="text-white font-semibold">Hoy puedes cambiarlo en 5 minutos.</span>
            </p>

            {{-- CTAs --}}
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-10">
                <a href="{{ route('register') }}" class="btn btn-lg bg-white text-blue-700 border-0 shadow-xl shadow-black/10 hover:shadow-black/20 hover:scale-[1.02] transition-all font-extrabold text-base px-10">
                    Crear mi página ahora
                    <span class="iconify tabler--arrow-right size-5"></span>
                </a>
                <a href="#planes" class="btn btn-lg btn-outline border-white/30 text-white hover:bg-white/10 font-medium">
                    <span class="iconify tabler--eye size-5"></span>
                    Ver los planes
                </a>
            </div>

            {{-- Trust signals --}}
            <div class="flex flex-wrap items-center justify-center gap-x-8 gap-y-3 text-white/40 text-sm">
                <div class="flex items-center gap-1.5">
                    <span class="iconify tabler--shield-check size-4"></span>
                    <span>Sin tarjeta de crédito</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="iconify tabler--clock size-4"></span>
                    <span>Listo en 5 minutos</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="iconify tabler--heart size-4"></span>
                    <span>Hecho en Venezuela</span>
                </div>
            </div>

            {{-- Social proof mock --}}
            <div class="mt-12 inline-flex items-center gap-3 px-4 py-2.5 rounded-full bg-white/10 backdrop-blur-sm border border-white/10">
                <div class="flex -space-x-2">
                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-blue-400 to-cyan-400 ring-2 ring-white/20 flex items-center justify-center text-white text-[10px] font-bold">JR</div>
                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-indigo-400 to-purple-400 ring-2 ring-white/20 flex items-center justify-center text-white text-[10px] font-bold">ML</div>
                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-emerald-400 to-teal-400 ring-2 ring-white/20 flex items-center justify-center text-white text-[10px] font-bold">CP</div>
                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-amber-400 to-orange-400 ring-2 ring-white/20 flex items-center justify-center text-white text-[10px] font-bold">AG</div>
                </div>
                <span class="text-sm text-white/70">Negocios ya confían en SYNTIweb</span>
            </div>
        </div>
    </div>
</section>
