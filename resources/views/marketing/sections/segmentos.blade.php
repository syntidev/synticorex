{{-- ═══ SEGMENTOS — "Un sistema que entiende tu negocio" ═══════════════ --}}
<section id="segmentos" class="relative py-20 lg:py-28 bg-slate-50 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section header --}}
        <div class="text-center mb-16 mkt-fade-in">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-indigo-50 text-indigo-600 text-sm font-semibold mb-4">
                <span class="iconify tabler--category size-4"></span>
                Segmentos inteligentes
            </div>
            <h2 class="text-3xl lg:text-5xl font-extrabold text-slate-900 mb-4">
                Un sistema que <span class="mkt-gradient-text">entiende</span> tu negocio
            </h2>
            <p class="text-lg text-slate-500 max-w-2xl mx-auto">
                No es lo mismo vender empanadas que ofrecer consultas legales. SYNTIweb adapta todo —textos, estructura, SEO— al tipo de negocio que tienes.
            </p>
        </div>

        {{-- Segment cards --}}
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6" x-data="{ active: null }">
            @foreach($segments as $key => $segment)
                @php
                    $colors = [
                        'orange' => ['bg-orange-50', 'text-orange-600', 'border-orange-200', 'from-orange-400 to-amber-500', 'bg-orange-500', 'shadow-orange-500/20'],
                        'blue'   => ['bg-blue-50', 'text-blue-600', 'border-blue-200', 'from-blue-400 to-cyan-500', 'bg-blue-500', 'shadow-blue-500/20'],
                        'indigo' => ['bg-indigo-50', 'text-indigo-600', 'border-indigo-200', 'from-indigo-400 to-violet-500', 'bg-indigo-500', 'shadow-indigo-500/20'],
                        'pink'   => ['bg-pink-50', 'text-pink-600', 'border-pink-200', 'from-pink-400 to-rose-500', 'bg-pink-500', 'shadow-pink-500/20'],
                        'emerald' => ['bg-emerald-50', 'text-emerald-600', 'border-emerald-200', 'from-emerald-400 to-teal-500', 'bg-emerald-500', 'shadow-emerald-500/20'],
                    ];
                    $c = $colors[$segment['color']] ?? $colors['blue'];
                @endphp
                <div class="mkt-card mkt-fade-in rounded-2xl bg-white border border-slate-100 overflow-hidden shadow-sm group cursor-pointer"
                     style="transition-delay: {{ $key * 0.08 }}s;"
                     @mouseenter="active = {{ $key }}" @mouseleave="active = null">
                    {{-- Color stripe top --}}
                    <div class="h-1 bg-gradient-to-r {{ $c[3] }}"></div>
                    <div class="p-6">
                        <div class="flex items-start gap-4 mb-4">
                            <div class="w-12 h-12 rounded-xl {{ $c[0] }} flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                <span class="iconify tabler--{{ $segment['icon'] }} size-6 {{ $c[1] }}"></span>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">{{ $segment['name'] }}</h3>
                                <span class="text-xs {{ $c[1] }} font-medium">Segmento especializado</span>
                            </div>
                        </div>
                        {{-- Features --}}
                        <ul class="space-y-2">
                            @foreach($segment['features'] as $feature)
                                <li class="flex items-center gap-2 text-sm text-slate-600">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $c[4] }} shrink-0"></span>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                        {{-- Hover reveal --}}
                        <div class="mt-4 pt-4 border-t border-slate-50 flex items-center gap-2 text-sm font-medium {{ $c[1] }} opacity-0 group-hover:opacity-100 transition-opacity">
                            <span>Ver ejemplo</span>
                            <span class="iconify tabler--arrow-right size-4 group-hover:translate-x-1 transition-transform"></span>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Card 6 — segmento personalizado --}}
            <div class="mkt-card mkt-fade-in rounded-2xl bg-white border border-dashed border-slate-200 overflow-hidden shadow-sm flex flex-col items-center justify-center text-center p-8 min-h-[200px]">
                <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-slate-50 flex items-center justify-center">
                    <span class="iconify tabler--user-question size-7 text-slate-400"></span>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">¿Tu negocio no aparece aquí?</h3>
                <p class="text-sm text-slate-400 leading-relaxed mb-5">Cada negocio es único. Cuéntanos qué haces y construimos algo hecho exactamente para ti.</p>
                <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                    Háblanos de tu negocio
                    <span class="iconify tabler--arrow-right size-4"></span>
                </a>
            </div>
        </div>

        {{-- "Y muchos más" --}}
        <div class="text-center mt-10 mkt-fade-in">
            <p class="text-slate-400 text-sm mb-3">Y próximamente más segmentos...</p>
            <div class="flex flex-wrap gap-2 justify-center">
                <span class="inline-flex items-center py-0.5 px-2 rounded-full text-sm font-medium bg-slate-100 text-slate-500">Gimnasio</span>
                <span class="inline-flex items-center py-0.5 px-2 rounded-full text-sm font-medium bg-slate-100 text-slate-500">Veterinaria</span>
                <span class="inline-flex items-center py-0.5 px-2 rounded-full text-sm font-medium bg-slate-100 text-slate-500">Farmacia</span>
                <span class="inline-flex items-center py-0.5 px-2 rounded-full text-sm font-medium bg-slate-100 text-slate-500">Academia</span>
                <span class="inline-flex items-center py-0.5 px-2 rounded-full text-sm font-medium bg-slate-100 text-slate-500">+ más</span>
            </div>
        </div>
    </div>
</section>
