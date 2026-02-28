{{-- ═══ CONFIGURACIÓN — "Editar es tan fácil como un WhatsApp" ═════════ --}}
<section id="configuracion" class="relative py-20 lg:py-28 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            {{-- ── Mockup ────────────────────────────────── --}}
            <div class="mkt-fade-in relative order-2 lg:order-1">
                <div class="relative">
                    {{-- Glow --}}
                    <div class="absolute -inset-4 bg-gradient-to-br from-purple-100 to-indigo-100 rounded-3xl blur-2xl opacity-40"></div>

                    {{-- Phone mockup --}}
                    <div class="relative max-w-[260px] mx-auto">
                        <div class="rounded-[2rem] overflow-hidden shadow-2xl border-4 border-slate-800 bg-slate-800">
                            {{-- Status bar --}}
                            <div class="bg-slate-800 px-4 py-1.5 flex items-center justify-between">
                                <span class="text-[10px] text-white/60 font-medium">9:41</span>
                                <div class="w-16 h-4 bg-slate-700 rounded-full"></div>
                                <div class="flex gap-1">
                                    <div class="w-3 h-3 bg-white/30 rounded-sm"></div>
                                    <div class="w-3 h-3 bg-white/30 rounded-sm"></div>
                                </div>
                            </div>
                            {{-- App content --}}
                            <div class="bg-slate-50 p-3 space-y-3">
                                {{-- Nav --}}
                                <div class="bg-white rounded-xl p-2.5 flex items-center justify-between shadow-sm">
                                    <span class="text-[10px] font-bold text-slate-800">Dashboard</span>
                                    <div class="w-10 h-4 bg-blue-500 rounded text-[8px] text-white font-bold flex items-center justify-center">
                                        Guardar
                                    </div>
                                </div>
                                {{-- Edit form mock --}}
                                <div class="bg-white rounded-xl p-3 shadow-sm space-y-2">
                                    <div class="text-[9px] font-bold text-slate-600 uppercase tracking-wide">Editar producto</div>
                                    <div class="space-y-1.5">
                                        <div>
                                            <div class="text-[8px] text-slate-400 mb-0.5">Nombre</div>
                                            <div class="h-4 bg-blue-50 border border-blue-200 rounded px-1.5 flex items-center">
                                                <span class="text-[8px] text-slate-700">Hamburguesa Especial</span>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-[8px] text-slate-400 mb-0.5">Precio</div>
                                            <div class="h-4 bg-blue-50 border border-blue-200 rounded px-1.5 flex items-center">
                                                <span class="text-[8px] text-emerald-600 font-bold">REF 8.50</span>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-[8px] text-slate-400 mb-0.5">Foto</div>
                                            <div class="h-10 bg-amber-50 border border-amber-200 rounded flex items-center justify-center">
                                                <span class="iconify tabler--camera size-4 text-amber-400"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Success toast --}}
                                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-2 flex items-center gap-2">
                                    <span class="iconify tabler--check size-3.5 text-emerald-500"></span>
                                    <span class="text-[9px] text-emerald-700 font-medium">¡Producto actualizado!</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Floating badges --}}
                    <div class="absolute -top-2 -right-2 bg-white rounded-xl shadow-xl p-2 flex items-center gap-1.5 mkt-float">
                        <span class="iconify tabler--bolt size-4 text-amber-500"></span>
                        <span class="text-[10px] font-bold text-slate-700">Instantáneo</span>
                    </div>
                </div>
            </div>

            {{-- ── Text ─────────────────────────────────── --}}
            <div class="mkt-fade-in order-1 lg:order-2">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-purple-50 text-purple-600 text-sm font-semibold mb-4">
                    <span class="iconify tabler--pencil size-4"></span>
                    Fácil de editar
                </div>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-slate-900 mb-4">
                    Editar es tan fácil como
                    <span class="mkt-gradient-text">escribir un WhatsApp</span>
                </h2>
                <p class="text-lg text-slate-500 mb-8 leading-relaxed">
                    No necesitas llamar a nadie. No necesitas ser técnico. Abres tu dashboard secreto, cambias lo que quieras y los clientes ven los cambios al instante.
                </p>

                <div class="space-y-5">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-sm shadow-md shadow-blue-500/25 shrink-0">1</div>
                        <div>
                            <h4 class="font-bold text-slate-800 mb-0.5">Abre tu dashboard secreto</h4>
                            <p class="text-sm text-slate-500">Presiona Alt+S en tu computadora o mantén presionado en tu celular</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-md shadow-indigo-500/25 shrink-0">2</div>
                        <div>
                            <h4 class="font-bold text-slate-800 mb-0.5">Edita lo que necesites</h4>
                            <p class="text-sm text-slate-500">Precios, fotos, textos, colores, secciones — todo visual y directo</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-md shadow-purple-500/25 shrink-0">3</div>
                        <div>
                            <h4 class="font-bold text-slate-800 mb-0.5">Tus clientes ven el cambio ya</h4>
                            <p class="text-sm text-slate-500">Sin esperas, sin aprobaciones— publicas al instante</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
