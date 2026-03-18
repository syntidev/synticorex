{{-- ═══ PLANES — 3 productos ══════════════════════════════════════════ --}}
<section id="planes" class="relative py-20 lg:py-28 bg-white overflow-hidden" x-data="currencyToggle()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-10 mkt-fade-in">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-50 text-amber-600 text-sm font-semibold mb-4">
                <span class="iconify tabler--crown size-4"></span>
                Planes accesibles
            </div>
            <h2 class="text-3xl lg:text-5xl font-extrabold text-slate-900 mb-4">
                Uno para cada <span class="mkt-gradient-text">tipo de negocio</span>
            </h2>
            <p class="text-lg text-slate-500 max-w-2xl mx-auto">
                No vendemos lo mismo para todos. Cada producto está construido para un negocio específico.
            </p>

            {{-- Toggle USD/Bs --}}
            <div class="flex items-center justify-center gap-3 mt-8 mb-2">
                <span class="text-sm font-semibold" :class="currency === 'usd' ? 'text-slate-900' : 'text-slate-400'">USD $</span>
                <button @click="toggleCurrency()"
                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none"
                        :class="currency === 'bs' ? 'bg-blue-500' : 'bg-slate-200'">
                    <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow-sm transition-transform"
                          :class="currency === 'bs' ? 'translate-x-6' : 'translate-x-1'"></span>
                </button>
                <span class="text-sm font-semibold" :class="currency === 'bs' ? 'text-slate-900' : 'text-slate-400'">Bs.</span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 items-start max-w-5xl mx-auto">

            {{-- STUDIO --}}
            <div class="mkt-fade-in mkt-card rounded-2xl border bg-white border-slate-100 shadow-sm overflow-hidden">
                <div class="h-1 bg-[#2B6FFF]"></div>
                <div class="p-6 pb-4">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                            <span class="iconify tabler--world size-4 text-blue-600"></span>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-wider text-blue-600">SYNTIstudio</span>
                    </div>
                    <h3 class="text-lg font-extrabold text-slate-900 mb-1">Tu web completa</h3>
                    <p class="text-xs text-slate-400 mb-4">Para servicios, marcas y negocios locales</p>
                    <div class="flex items-baseline gap-1">
                        <span class="text-4xl font-extrabold text-slate-900"
                              x-text="currency === 'bs' ? formatBs(99) : '$99'">$99</span>
                        <span class="text-sm text-slate-400">/año</span>
                    </div>
                    <p class="text-xs text-slate-400 mt-1"
                       x-text="currency === 'bs' ? formatBsMes(99) : '~$8/mes · menos que un café'">
                        ~$8/mes · menos que un café
                    </p>
                    <p class="text-xs text-slate-500 mt-2 font-medium">tu-negocio.oficio.vip</p>
                </div>
                <div class="px-6 pb-6 border-t border-slate-100 pt-4 space-y-2">
                    <div class="flex items-center gap-2 text-sm text-slate-600"><span class="iconify tabler--circle-check-filled size-4 text-blue-500"></span>Landing profesional autogestionable</div>
                    <div class="flex items-center gap-2 text-sm text-slate-600"><span class="iconify tabler--circle-check-filled size-4 text-blue-500"></span>SEO automático para Google</div>
                    <div class="flex items-center gap-2 text-sm text-slate-600"><span class="iconify tabler--circle-check-filled size-4 text-blue-500"></span>BCV automático · QR permanente</div>
                    <div class="flex items-center gap-2 text-sm text-slate-600"><span class="iconify tabler--circle-check-filled size-4 text-blue-500"></span>WhatsApp directo por producto</div>
                </div>
                <div class="px-6 pb-6">
                    <a href="{{ route('onboarding.studio') }}" class="block w-full text-center py-3 px-4 rounded-lg font-semibold text-white transition-all hover:-translate-y-0.5" style="background:#4A80E4;box-shadow:0 4px 14px 0 color-mix(in oklch,#4A80E4 40%,transparent)">
                        Ver planes Studio →
                    </a>
                </div>
            </div>

            {{-- FOOD --}}
            <div class="mkt-fade-in mkt-card rounded-2xl border bg-white border-slate-100 shadow-sm overflow-hidden">
                <div class="h-1 bg-[#F97316]"></div>
                <div class="p-6 pb-4 bg-gradient-to-b from-orange-50 to-white">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center">
                            <span class="iconify tabler--tools-kitchen-2 size-4 text-orange-600"></span>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-wider text-orange-600">SYNTIfood</span>
                    </div>
                    <h3 class="text-lg font-extrabold text-slate-900 mb-1">Tu menú digital</h3>
                    <p class="text-xs text-slate-400 mb-4">Para restaurantes, areperas y food trucks</p>
                    <div class="flex items-baseline gap-1">
                        <span class="text-4xl font-extrabold text-orange-500"
                              x-text="currency === 'bs' ? formatBs(69) : '$69'">$69</span>
                        <span class="text-sm text-slate-400">/año</span>
                    </div>
                    <p class="text-xs text-slate-400 mt-1"
                       x-text="currency === 'bs' ? formatBsMes(69) : '~$5.75/mes · o $12/mes para probar'">
                        ~$5.75/mes · o $12/mes para probar
                    </p>
                    <p class="text-xs text-slate-500 mt-2 font-medium">tu-restaurante.aqui.menu</p>
                </div>
                <div class="px-6 pb-6 border-t border-slate-100 pt-4 space-y-2">
                    <div class="flex items-center gap-2 text-sm text-slate-600"><span class="iconify tabler--circle-check-filled size-4 text-orange-500"></span>Menú con fotos por categoría</div>
                    <div class="flex items-center gap-2 text-sm text-slate-600"><span class="iconify tabler--circle-check-filled size-4 text-orange-500"></span>Pedido Rápido → WhatsApp</div>
                    <div class="flex items-center gap-2 text-sm text-slate-600"><span class="iconify tabler--circle-check-filled size-4 text-orange-500"></span>Comandas SF-XXXXXX rastreables</div>
                    <div class="flex items-center gap-2 text-sm text-slate-600"><span class="iconify tabler--circle-check-filled size-4 text-orange-500"></span>BCV automático · horario inteligente</div>
                </div>
                <div class="px-6 pb-6">
                    <a href="{{ route('onboarding.food') }}" class="inline-flex items-center justify-center py-2 px-4 rounded-lg font-bold transition-all w-full bg-gradient-to-r from-orange-500 to-amber-500 text-white shadow-lg shadow-orange-500/20 hover:shadow-orange-500/40 hover:scale-[1.02]">
                        Ver planes Food →
                    </a>
                </div>
            </div>

            {{-- CAT --}}
            <div class="mkt-fade-in mkt-card rounded-2xl border bg-white border-slate-100 shadow-sm overflow-hidden">
                <div class="h-1 bg-[#10B981]"></div>
                <div class="p-6 pb-4">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                            <span class="iconify tabler--shopping-bag size-4 text-emerald-600"></span>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">SYNTIcat</span>
                    </div>
                    <h3 class="text-lg font-extrabold text-slate-900 mb-1">Tu catálogo con carrito</h3>
                    <p class="text-xs text-slate-400 mb-4">Para tiendas, boutiques y proveedores</p>
                    <div class="flex items-baseline gap-1">
                        <span class="text-4xl font-extrabold text-slate-900"
                              x-text="currency === 'bs' ? formatBs(69) : '$69'">$69</span>
                        <span class="text-sm text-slate-400">/año</span>
                    </div>
                    <p class="text-xs text-slate-400 mt-1"
                       x-text="currency === 'bs' ? formatBsMes(69) : '~$5.75/mes · carrito incluido'">
                        ~$5.75/mes · carrito incluido
                    </p>
                    <p class="text-xs text-slate-500 mt-2 font-medium">tu-tienda.vitrini.app</p>
                </div>
                <div class="px-6 pb-6 border-t border-slate-100 pt-4 space-y-2">
                    <div class="flex items-center gap-2 text-sm text-slate-600"><span class="iconify tabler--circle-check-filled size-4 text-emerald-500"></span>250 productos · 6 fotos c/u</div>
                    <div class="flex items-center gap-2 text-sm text-slate-600"><span class="iconify tabler--circle-check-filled size-4 text-emerald-500"></span>Carrito + checkout WhatsApp</div>
                    <div class="flex items-center gap-2 text-sm text-slate-600"><span class="iconify tabler--circle-check-filled size-4 text-emerald-500"></span>Mini Order SC-XXXX rastreable</div>
                    <div class="flex items-center gap-2 text-sm text-slate-600"><span class="iconify tabler--circle-check-filled size-4 text-emerald-500"></span>Variantes · precio tachado · BCV</div>
                </div>
                <div class="px-6 pb-6">
                    <a href="{{ route('onboarding.cat') }}" class="block w-full text-center py-3 px-4 rounded-lg font-semibold text-white transition-all hover:-translate-y-0.5" style="background:#10b981;box-shadow:0 4px 14px 0 color-mix(in oklch,#10b981 40%,transparent)">
                        Ver planes Cat →
                    </a>
                </div>
            </div>

        </div>

        {{-- Garantía --}}
        <div class="mt-12 text-center mkt-fade-in">
            <div class="inline-flex items-center gap-6 flex-wrap justify-center">
                <div class="flex items-center gap-2 text-sm text-slate-500">
                    <span class="iconify tabler--shield-check size-5 text-emerald-500"></span>
                    <span>15 días de prueba</span>
                </div>
                <div class="flex items-center gap-2 text-sm text-slate-500">
                    <span class="iconify tabler--credit-card-off size-5 text-blue-500"></span>
                    <span>Sin tarjeta de crédito</span>
                </div>
                <div class="flex items-center gap-2 text-sm text-slate-500">
                    <span class="iconify tabler--clock size-5 text-indigo-500"></span>
                    <span>Listo en 48 horas</span>
                </div>
            </div>
        </div>
    </div>

    <script>
function currencyToggle() {
    return {
        currency: 'usd',
        rate: 0,
        async init() {
            try {
                const res = await fetch('/api/parallel-rate');
                const data = await res.json();
                this.rate = data.rate ?? 0;
            } catch(e) { this.rate = 0; }
        },
        toggleCurrency() {
            this.currency = this.currency === 'usd' ? 'bs' : 'usd';
        },
        formatBs(usd) {
            if (!this.rate) return '...';
            const bs = usd * this.rate;
            return 'Bs. ' + Math.round(bs).toLocaleString('es-VE');
        },
        formatBsMes(usd) {
            if (!this.rate) return '...';
            const bs = Math.round((usd / 12) * this.rate);
            return '~Bs. ' + bs.toLocaleString('es-VE') + '/mes';
        }
    }
}
    </script>
</section>
