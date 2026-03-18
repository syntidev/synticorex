{{-- ═══ ESTADÍSTICAS — "Los números no mienten" ════════════════════════ --}}
<section id="estadisticas" class="relative py-20 lg:py-28 overflow-hidden mkt-gradient-hero">
    {{-- Background effects --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute inset-0 opacity-5" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.3) 1px, transparent 0); background-size: 40px 40px;"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section header --}}
        <div class="text-center mb-16 mkt-fade-in">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/5 border border-white/10 text-blue-200 text-sm font-semibold mb-4">
                <span class="iconify tabler--chart-bar size-4"></span>
                En números
            </div>
            <h2 class="text-3xl lg:text-5xl font-extrabold text-white mb-4">
                Los números <span class="bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent">no mienten</span>
            </h2>
            <p class="text-lg text-blue-100/50 max-w-2xl mx-auto">
                Cada negocio con SYNTIweb obtiene estas ventajas automáticamente, sin configuración adicional.
            </p>
        </div>

        {{-- Stats grid --}}
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($stats as $index => $stat)
                @php
                    $gradients = [
                        'from-blue-500 to-blue-600',
                        'from-indigo-500 to-violet-600',
                        'from-emerald-500 to-teal-600',
                        'from-amber-500 to-orange-600',
                    ];
                    $borderColors = [
                        'border-blue-400/20',
                        'border-indigo-400/20',
                        'border-emerald-400/20',
                        'border-amber-400/20',
                    ];
                    $glowColors = ['blue', 'indigo', 'emerald', 'amber'];
                @endphp
                <div class="mkt-card mkt-fade-in relative rounded-2xl bg-white/5 backdrop-blur-sm border {{ $borderColors[$index] }} p-6 text-center group"
                     style="transition-delay: {{ $index * 0.1 }}s;">
                    {{-- Value --}}
                    <div class="text-4xl lg:text-5xl font-extrabold bg-gradient-to-br {{ $gradients[$index] }} bg-clip-text text-transparent mb-2 group-hover:scale-110 transition-transform">
                        {{ $stat['value'] }}
                    </div>
                    <p class="text-sm text-white/80 font-medium mb-1">{{ $stat['label'] }}</p>
                    <p class="text-xs text-blue-200/40">{{ $stat['description'] ?? '' }}</p>
                </div>
            @endforeach
        </div>

        {{-- Included features bar --}}
        <div class="mkt-fade-in mt-16 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10 p-6 lg:p-8">
            <h3 class="text-center text-lg font-bold text-white mb-6">Todo esto viene incluido en cada plan:</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 lg:gap-6">
                <div class="flex items-center gap-3 text-sm">
                    <div class="w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center shrink-0">
                        <span class="iconify tabler--search size-4 text-blue-400"></span>
                    </div>
                    <span class="text-white/80">Google te entiende</span>
                </div>
                <div class="flex items-center gap-3 text-sm">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/20 flex items-center justify-center shrink-0">
                        <span class="iconify tabler--brand-whatsapp size-4 text-emerald-400"></span>
                    </div>
                    <span class="text-white/80">WhatsApp directo</span>
                </div>
                <div class="flex items-center gap-3 text-sm">
                    <div class="w-8 h-8 rounded-lg bg-purple-500/20 flex items-center justify-center shrink-0">
                        <span class="iconify tabler--qrcode size-4 text-purple-400"></span>
                    </div>
                    <span class="text-white/80">Código QR gratis</span>
                </div>
                <div class="flex items-center gap-3 text-sm">
                    <div class="w-8 h-8 rounded-lg bg-amber-500/20 flex items-center justify-center shrink-0">
                        <span class="iconify tabler--device-mobile size-4 text-amber-400"></span>
                    </div>
                    <span class="text-white/80">100% responsive</span>
                </div>
                <div class="flex items-center gap-3 text-sm">
                    <div class="w-8 h-8 rounded-lg bg-indigo-500/20 flex items-center justify-center shrink-0">
                        <span class="iconify tabler--palette size-4 text-indigo-400"></span>
                    </div>
                    <span class="text-white/80">Temas premium</span>
                </div>
                <div class="flex items-center gap-3 text-sm">
                    <div class="w-8 h-8 rounded-lg bg-rose-500/20 flex items-center justify-center shrink-0">
                        <span class="iconify tabler--photo size-4 text-rose-400"></span>
                    </div>
                    <span class="text-white/80">Fotos optimizadas</span>
                </div>
                <div class="flex items-center gap-3 text-sm">
                    <div class="w-8 h-8 rounded-lg bg-cyan-500/20 flex items-center justify-center shrink-0">
                        <span class="iconify tabler--clock size-4 text-cyan-400"></span>
                    </div>
                    <span class="text-white/80">Horarios inteligentes</span>
                </div>
                <div class="flex items-center gap-3 text-sm">
                    <div class="w-8 h-8 rounded-lg bg-teal-500/20 flex items-center justify-center shrink-0">
                        <span class="iconify tabler--map-pin size-4 text-teal-400"></span>
                    </div>
                    <span class="text-white/80">Mapa de ubicación</span>
                </div>
            </div>
        </div>
    </div>
</section>
