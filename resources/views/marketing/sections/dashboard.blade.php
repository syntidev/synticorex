{{-- ═══ DASHBOARD — "Tu negocio se administra solo" ═══════════════════ --}}
<section id="dashboard" class="relative py-20 lg:py-28 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            {{-- ── Text ─────────────────────────────────── --}}
            <div class="mkt-fade-in">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50 text-blue-600 text-sm font-semibold mb-4">
                    <span class="iconify tabler--dashboard size-4"></span>
                    Panel de control
                </div>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-slate-900 mb-4">
                    Edita todo desde <span class="mkt-gradient-text">tu celular</span>
                </h2>
                <p class="text-lg text-slate-500 mb-8 leading-relaxed">
                    Un dashboard secreto que solo tú puedes activar. Cambia precios, sube fotos, edita textos, activa secciones — todo sin salir de tu propia página.
                </p>

                <div class="space-y-4">
                    {{-- Feature 1 --}}
                    <div class="flex items-start gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center shrink-0 mt-0.5">
                            <span class="iconify tabler--lock-access size-5 text-blue-600"></span>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-sm">Acceso con PIN secreto</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Presiona Alt+S en desktop o mantén presionado en el celular. Solo tú conoces tu PIN.</p>
                        </div>
                    </div>
                    {{-- Feature 2 --}}
                    <div class="flex items-start gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center shrink-0 mt-0.5">
                            <span class="iconify tabler--photo-edit size-5 text-indigo-600"></span>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-sm">Edición visual de productos</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Sube fotos, cambia precios, reordena productos. Los cambios se ven al instante.</p>
                        </div>
                    </div>
                    {{-- Feature 3 --}}
                    <div class="flex items-start gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center shrink-0 mt-0.5">
                            <span class="iconify tabler--palette size-5 text-purple-600"></span>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-sm">Diseño personalizable</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Elige colores, cambia el estilo y activa o desactiva secciones sin tocar código.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Dashboard Mockup ──────────────────────── --}}
            <div class="mkt-fade-in relative" style="transition-delay: 0.2s;">
                <div class="relative">
                    {{-- Glow --}}
                    <div class="absolute -inset-4 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-3xl blur-2xl opacity-50"></div>

                    {{-- Dashboard Mock --}}
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl border border-slate-200">
                        {{-- Sidebar --}}
                        <div class="flex">
                            <div class="w-14 bg-slate-800 flex flex-col items-center py-4 gap-3 shrink-0">
                                <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-blue-400 to-indigo-500 mb-2"></div>
                                <div class="w-7 h-7 rounded-lg bg-white/10 flex items-center justify-center">
                                    <span class="iconify tabler--home size-3.5 text-white/60"></span>
                                </div>
                                <div class="w-7 h-7 rounded-lg bg-blue-500/20 flex items-center justify-center ring-1 ring-blue-400/30">
                                    <span class="iconify tabler--box size-3.5 text-blue-400"></span>
                                </div>
                                <div class="w-7 h-7 rounded-lg bg-white/10 flex items-center justify-center">
                                    <span class="iconify tabler--brush size-3.5 text-white/60"></span>
                                </div>
                                <div class="w-7 h-7 rounded-lg bg-white/10 flex items-center justify-center">
                                    <span class="iconify tabler--settings size-3.5 text-white/60"></span>
                                </div>
                            </div>
                            {{-- Main content --}}
                            <div class="flex-1 bg-slate-50">
                                {{-- Top bar --}}
                                <div class="bg-white px-4 py-2.5 border-b border-slate-100 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-slate-800">Productos</span>
                                        <span class="badge badge-xs bg-blue-100 text-blue-600">6 / 6</span>
                                    </div>
                                    <div class="w-16 h-5 bg-blue-500 rounded text-[9px] text-white font-bold flex items-center justify-center">
                                        + Nuevo
                                    </div>
                                </div>
                                {{-- Product grid mock --}}
                                <div class="p-3 grid grid-cols-2 gap-2">
                                    @foreach(['bg-amber-100', 'bg-rose-100', 'bg-emerald-100', 'bg-blue-100'] as $cardColor)
                                        <div class="bg-white rounded-lg p-2 border border-slate-100 shadow-sm">
                                            <div class="w-full h-12 {{ $cardColor }} rounded mb-1.5"></div>
                                            <div class="w-16 h-1.5 bg-slate-200 rounded mb-1"></div>
                                            <div class="w-10 h-1.5 bg-emerald-200 rounded"></div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Mobile badge --}}
                    <div class="absolute -bottom-3 -right-3 bg-white rounded-xl shadow-xl p-2.5 flex items-center gap-2 border border-slate-100">
                        <div class="w-7 h-7 rounded-lg bg-emerald-50 flex items-center justify-center">
                            <span class="iconify tabler--device-mobile size-4 text-emerald-600"></span>
                        </div>
                        <span class="text-xs font-bold text-slate-700">Mobile ready</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
