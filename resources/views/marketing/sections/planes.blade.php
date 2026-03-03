{{-- ═══ PLANES — "Elige tu impulso" ════════════════════════════════════ --}}
<section id="planes" class="relative py-20 lg:py-28 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section header --}}
        <div class="text-center mb-16 mkt-fade-in">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-50 text-amber-600 text-sm font-semibold mb-4">
                <span class="iconify tabler--crown size-4"></span>
                Planes accesibles
            </div>
            <h2 class="text-3xl lg:text-5xl font-extrabold text-slate-900 mb-4">
                Elige tu <span class="mkt-gradient-text">impulso</span>
            </h2>
            <p class="text-lg text-slate-500 max-w-2xl mx-auto">
                Desde menos de lo que cuesta un café al día. Todos los planes incluyen SEO automático, WhatsApp y código QR.
            </p>
        </div>

        {{-- Pricing cards --}}
        <div class="grid lg:grid-cols-3 gap-6 lg:gap-8 items-start max-w-5xl mx-auto">
            @foreach($plans as $plan)
                @php
                    $isHighlighted = $plan['highlight'] ?? false;
                @endphp
                <div class="mkt-fade-in relative {{ $isHighlighted ? 'lg:-mt-4 lg:mb-4' : '' }}"
                     style="transition-delay: {{ $loop->index * 0.1 }}s;">

                    {{-- Popular badge --}}
                    @if($isHighlighted)
                        <div class="absolute -top-3 left-1/2 -translate-x-1/2 z-10">
                            <div class="px-4 py-1 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 text-white text-xs font-bold shadow-lg shadow-blue-500/25">
                                Más elegido
                            </div>
                        </div>
                    @endif

                    <div class="mkt-card rounded-2xl border {{ $isHighlighted ? 'bg-white border-blue-200 shadow-xl shadow-blue-500/10 ring-1 ring-blue-100' : 'bg-white border-slate-100 shadow-sm' }} overflow-hidden">
                        {{-- Header --}}
                        <div class="p-6 pb-4 {{ $isHighlighted ? 'bg-gradient-to-b from-blue-50 to-white' : '' }}">
                            <h3 class="text-lg font-extrabold text-slate-900 mb-1">{{ $plan['name'] }}</h3>
                            <p class="text-xs text-slate-400 mb-4">{{ $plan['tagline'] }}</p>

                            {{-- Price --}}
                            <div class="flex items-baseline gap-1">
                                <span class="text-4xl font-extrabold {{ $isHighlighted ? 'mkt-gradient-text' : 'text-slate-900' }}">${{ $plan['price'] }}</span>
                                <span class="text-sm text-slate-400 font-medium">/ año</span>
                            </div>
                            <p class="text-xs text-slate-400 mt-1">~${{ number_format($plan['price'] / 12, 0) }}/mes — menos que un café diario</p>
                        </div>

                        {{-- Features --}}
                        <div class="px-6 pb-6">
                            <div class="border-t border-slate-100 pt-5 space-y-3">
                                @foreach($plan['features'] as $feature)
                                    <div class="flex items-start gap-2.5">
                                        <span class="iconify tabler--circle-check-filled size-4.5 {{ $isHighlighted ? 'text-blue-500' : 'text-emerald-500' }} mt-0.5 shrink-0"></span>
                                        <span class="text-sm text-slate-600">{{ $feature }}</span>
                                    </div>
                                @endforeach
                            </div>

                            {{-- CTA --}}
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center py-2 px-4 rounded-lg font-bold transition-all w-full mt-6 {{ $isHighlighted ? 'bg-gradient-to-r from-blue-500 to-indigo-500 text-white border-0 shadow-lg shadow-blue-500/20 hover:shadow-blue-500/40' : 'border border-slate-200 text-slate-700 hover:bg-slate-50' }} hover:scale-[1.02]">
                                {{ $plan['cta'] }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Guarantee --}}
        <div class="mt-12 text-center mkt-fade-in">
            <div class="inline-flex items-center gap-6 flex-wrap justify-center">
                <div class="flex items-center gap-2 text-sm text-slate-500">
                    <span class="iconify tabler--shield-check size-5 text-emerald-500"></span>
                    <span>Garantía de satisfacción</span>
                </div>
                <div class="flex items-center gap-2 text-sm text-slate-500">
                    <span class="iconify tabler--credit-card-off size-5 text-blue-500"></span>
                    <span>Prueba sin tarjeta de crédito</span>
                </div>
                <div class="flex items-center gap-2 text-sm text-slate-500">
                    <span class="iconify tabler--clock size-5 text-indigo-500"></span>
                    <span>Activo en 5 minutos</span>
                </div>
            </div>
        </div>
    </div>
</section>
