{{-- ═══ PROBLEMA — "El 95% de los negocios son invisibles" ════════════ --}}
@php $section = \App\Models\LandingSection::forKey('problema'); @endphp
<section id="problema" class="relative py-20 lg:py-28 bg-slate-50 overflow-hidden">
    {{-- Subtle pattern --}}
    <div class="absolute inset-0 opacity-30" style="background-image: radial-gradient(circle at 1px 1px, rgba(148,163,184,0.15) 1px, transparent 0); background-size: 32px 32px;"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section header --}}
        <div class="text-center mb-16 mkt-fade-in">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-red-50 text-red-600 text-sm font-semibold mb-4">
                <span class="iconify tabler--alert-triangle size-4"></span>
                El problema real
            </div>
            <h2 class="text-3xl lg:text-5xl font-extrabold text-slate-900 mb-4">
                {{ $section?->content['headline'] ?? 'Tu negocio es invisible en internet' }}
            </h2>
            <p class="text-lg text-slate-500 max-w-2xl mx-auto">
                Mientras buscas clientes boca a boca, tu competencia aparece en Google, WhatsApp y redes. Esto es lo que pasa cuando no tienes presencia digital:
            </p>
        </div>

        {{-- 6 dolores reales --}}
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-16">

            <div class="mkt-card mkt-fade-in rounded-2xl bg-white border border-slate-100 p-6 shadow-sm">
                <div class="text-2xl mb-3">📱</div>
                <h4 class="font-bold text-slate-800 text-sm mb-2 leading-snug">
                    "¿Y cómo te busco para ver tus productos?"
                </h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Te preguntan por Instagram, les mandas fotos por WhatsApp, se confunden. O peor: se van sin comprar porque no encontraron lo que buscaban.
                </p>
            </div>

            <div class="mkt-card mkt-fade-in rounded-2xl bg-white border border-slate-100 p-6 shadow-sm" style="transition-delay:.08s">
                <div class="text-2xl mb-3">💸</div>
                <h4 class="font-bold text-slate-800 text-sm mb-2 leading-snug">
                    "Una agencia me cobró $300 y ni sé cómo actualizar mi página"
                </h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Pagaste, te entregaron algo bonito, pero cada cambio es otro cobro. Los precios cambiaron y la página sigue mostrando lo viejo.
                </p>
            </div>

            <div class="mkt-card mkt-fade-in rounded-2xl bg-white border border-slate-100 p-6 shadow-sm" style="transition-delay:.16s">
                <div class="text-2xl mb-3">💱</div>
                <h4 class="font-bold text-slate-800 text-sm mb-2 leading-snug">
                    "¿Cuánto es eso en bolívares?" — te lo preguntan 30 veces al día
                </h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Calculas, respondes, calculas de nuevo. La tasa cambió. El cliente ya se fue. Necesitas una vitrina que haga ese trabajo sola.
                </p>
            </div>

            <div class="mkt-card mkt-fade-in rounded-2xl bg-white border border-slate-100 p-6 shadow-sm" style="transition-delay:.24s">
                <div class="text-2xl mb-3">🕐</div>
                <h4 class="font-bold text-slate-800 text-sm mb-2 leading-snug">
                    "Llegan mensajes a las 2am preguntando si estás abierto"
                </h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Clientes que no saben tu horario, que te escriben cuando duermes. Tu negocio debería responder eso solo, sin que tú estés despierto.
                </p>
            </div>

            <div class="mkt-card mkt-fade-in rounded-2xl bg-white border border-slate-100 p-6 shadow-sm" style="transition-delay:.32s">
                <div class="text-2xl mb-3">📊</div>
                <h4 class="font-bold text-slate-800 text-sm mb-2 leading-snug">
                    "No sé qué producto le gusta más a la gente"
                </h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Vendes sin datos. No sabes qué ven más, a qué hora llegan, desde dónde te encuentran. Estás manejando de noche sin luces.
                </p>
            </div>

            <div class="mkt-card mkt-fade-in rounded-2xl bg-white border border-slate-100 p-6 shadow-sm" style="transition-delay:.40s">
                <div class="text-2xl mb-3">😤</div>
                <h4 class="font-bold text-slate-800 text-sm mb-2 leading-snug">
                    "Mi competencia ya tiene página web y yo sigo en grupos de WhatsApp"
                </h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Mientras tú mandas listas de precios por mensaje, ellos aparecen en Google. Cada día que pasa, la brecha se hace más grande.
                </p>
            </div>

        </div>

        {{-- Pain point cards --}}
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
            {{-- Card 1 --}}
            <div class="mkt-card mkt-fade-in rounded-2xl bg-white border border-slate-100 p-6 text-center shadow-sm">
                <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-red-50 flex items-center justify-center">
                    <span class="iconify tabler--eye-off size-7 text-red-500"></span>
                </div>
                <div class="text-4xl font-extrabold text-slate-900 mb-1">{{ $stats[0]['value'] }}</div>
                <p class="text-sm text-slate-500 leading-snug">de negocios en Venezuela no tienen presencia digital</p>
            </div>
            {{-- Card 2 --}}
            <div class="mkt-card mkt-fade-in rounded-2xl bg-white border border-slate-100 p-6 text-center shadow-sm" style="transition-delay: 0.1s;">
                <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-amber-50 flex items-center justify-center">
                    <span class="iconify tabler--users-minus size-7 text-amber-500"></span>
                </div>
                <div class="text-4xl font-extrabold text-slate-900 mb-1">7 de 10</div>
                <p class="text-sm text-slate-500 leading-snug">personas buscan en Google antes de visitar un negocio local</p>
            </div>
            {{-- Card 3 --}}
            <div class="mkt-card mkt-fade-in rounded-2xl bg-white border border-slate-100 p-6 text-center shadow-sm" style="transition-delay: 0.2s;">
                <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-slate-100 flex items-center justify-center">
                    <span class="iconify tabler--mood-sad size-7 text-slate-400"></span>
                </div>
                <div class="text-4xl font-extrabold text-slate-900 mb-1">$0</div>
                <p class="text-sm text-slate-500 leading-snug">ingresos de clientes que nunca te encontraron en internet</p>
            </div>
            {{-- Card 4 --}}
            <div class="mkt-card mkt-fade-in rounded-2xl bg-white border border-slate-100 p-6 text-center shadow-sm" style="transition-delay: 0.3s;">
                <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-purple-50 flex items-center justify-center">
                    <span class="iconify tabler--clock-x size-7 text-purple-500"></span>
                </div>
                <div class="text-4xl font-extrabold text-slate-900 mb-1">Cada día</div>
                <p class="text-sm text-slate-500 leading-snug">que pasa sin presencia digital, pierdes clientes potenciales</p>
            </div>
        </div>

        {{-- Emotional pull --}}
        <div class="mkt-fade-in max-w-3xl mx-auto text-center">
            <div class="rounded-2xl bg-gradient-to-r from-slate-900 to-slate-800 p-8 lg:p-12 text-white relative overflow-hidden">

                <span class="iconify tabler--quote size-10 text-blue-400/30 mb-4 block"></span>
                <p class="text-lg lg:text-xl font-medium leading-relaxed mb-4 relative">
                    Imagina que alguien busca <span class="text-blue-400 font-bold">"panadería cerca de mí"</span> y tu negocio
                    <span class="text-red-400 font-bold">no aparece</span>. Ese cliente va a tu competencia. Eso pasa todos los días.
                </p>
                <p class="text-blue-200/60 text-sm">
                    — La realidad de no tener presencia digital en {{ date('Y') }}
                </p>
            </div>
        </div>
    </div>
</section>
