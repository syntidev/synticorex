<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo negocio — SYNTIweb</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        .wiz-gradient-header { background: var(--sw-white); border-bottom: 1px solid var(--sw-border); }
        .wiz-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 1rem; box-shadow: 0 4px 24px -4px rgba(0,0,0,0.08); }
        .wiz-input { border-radius: 0.625rem; border: 1px solid #e2e8f0; transition: border-color .2s, box-shadow .2s; }
        .wiz-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.12); outline: none; }
        .wiz-btn-primary { background: linear-gradient(135deg, #3b82f6, #4f46e5); color: #fff; border: 0; border-radius: 0.625rem; font-weight: 700; transition: all .2s; box-shadow: 0 4px 14px -4px rgba(59,130,246,0.4); }
        .wiz-btn-primary:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 8px 20px -4px rgba(59,130,246,0.45); }
        .wiz-btn-primary:disabled { opacity: 0.5; cursor: not-allowed; }
        .wiz-step-dot { width: 2rem; height: 2rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .75rem; font-weight: 700; transition: all .3s; }
        .wiz-step-active { background: linear-gradient(135deg, #3b82f6, #4f46e5); color: #fff; box-shadow: 0 4px 12px -2px rgba(59,130,246,0.4); }
        .wiz-step-done { background: #10b981; color: #fff; }
        .wiz-step-pending { background: #e2e8f0; color: #94a3b8; }
        .wiz-segment-card:hover { border-color: #3b82f6; background: #eff6ff; }
        .wiz-segment-card.selected { border-color: #3b82f6; background: #eff6ff; color: #1d4ed8; font-weight: 600; }
        .wiz-plan-card:hover { border-color: #3b82f6; }
        .wiz-plan-card.selected { border-color: #3b82f6; background: linear-gradient(135deg, #eff6ff, #eef2ff); box-shadow: 0 0 0 2px rgba(59,130,246,0.2); }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">

{{-- ──────────────────────────────────────────────────────────
     MODO ADMIN: acceso directo sin auth (Fase 1)
     ──────────────────────────────────────────────────────── --}}
@if($mode === 'admin')

<div
    x-data="{
        step: 1,
        totalSteps: 5,
        submitting: false,

        /* ── campos del formulario ── */
        business_name: '',
        business_segment: '',
        city: '',
        slogan: '',
        hero_subtitle: '',
        about_text: '',
        value_prop_1: '',
        value_prop_2: '',
        value_prop_3: '',
        whatsapp_sales: '',
        phone: '',
        email: '',
        subdomain: '',
        plan_id: '',

        /* ── estado check subdominio ── */
        subdomainChecking: false,
        subdomainAvailable: null,
        subdomainRaw: '',
        subdomainTimer: null,

        /* ── planes (PHP → JS) ── */
        plans: {{ Js::from($plans->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'price_usd' => $p->price_usd, 'products_limit' => $p->products_limit, 'services_limit' => $p->services_limit])) }},

        /* ── segmentos (PHP → JS) ── */
        segments: {{ Js::from($segments) }},

        /* ── progreso ── */
        get progress() {
            return Math.round(((this.step - 1) / (this.totalSteps - 1)) * 100);
        },

        /* ── puede avanzar al paso siguiente? ── */
        get canProceed() {
            if (this.step === 1) return this.business_name.trim() !== '' && this.business_segment !== '';
            if (this.step === 2) return this.slogan.trim() !== '';
            if (this.step === 3) return this.value_prop_1.trim() !== '';
            if (this.step === 4) return this.subdomain.trim() !== '' && this.plan_id !== '' && this.subdomainAvailable === true;
            return true;
        },

        /* ── resumen legible del segmento ──*/
        get segmentLabel() {
            return this.segments[this.business_segment] ?? this.business_segment;
        },

        /* ── nombre del plan seleccionado ── */
        get selectedPlan() {
            return this.plans.find(p => p.id == this.plan_id) ?? null;
        },

        /* ── check subdominio con debounce 600ms ── */
        onSubdomainInput() {
            clearTimeout(this.subdomainTimer);
            this.subdomainAvailable = null;
            const raw = this.subdomainRaw.toLowerCase().replace(/[^a-z0-9-]/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
            this.subdomain = raw;
            if (raw.length < 3) return;
            this.subdomainChecking = true;
            this.subdomainTimer = setTimeout(async () => {
                try {
                    const res = await fetch(`/onboarding/subdomain-check?subdomain=${encodeURIComponent(raw)}`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const data = await res.json();
                    this.subdomainAvailable = data.available;
                } catch(e) {
                    this.subdomainAvailable = null;
                } finally {
                    this.subdomainChecking = false;
                }
            }, 600);
        },

        nextStep() {
            if (this.step < this.totalSteps && this.canProceed) this.step++;
        },

        prevStep() {
            if (this.step > 1) this.step--;
        },
    }"
    class="min-h-screen flex flex-col"
>

    <header class="wiz-gradient-header px-6 py-3.5 flex items-center gap-3 sticky top-0 z-50 shadow-lg">
        <a href="/" class="flex items-center gap-2">
            <img src="{{ asset('brand/syntiweb-logo-positive.svg') }}" alt="SYNTIweb" width="32" height="32">
            <span class="font-bold text-lg tracking-tight">
                <span style="color:#1a1a1a">SYNTI</span><span style="color:#4A80E4">web</span>
            </span>
        </a>
        <div class="flex items-center gap-1.5 ml-3 px-2.5 py-1 rounded-full text-xs font-semibold" style="background:rgba(251,191,36,0.2);color:#fbbf24;border:1px solid rgba(251,191,36,0.3)">
            <span class="iconify tabler--shield-check size-3.5"></span>
            Modo Admin
        </div>
        <span class="ml-auto text-sm" style="color:var(--sw-text-muted)">Paso <span x-text="step" style="color:var(--sw-text);font-weight:600"></span> de <span x-text="totalSteps" style="color:var(--sw-text-muted)"></span></span>
    </header>

    {{-- ── PROGRESS BAR ── --}}
    <div class="h-1 bg-slate-200">
        <div class="h-1 transition-all duration-500" style="background:linear-gradient(90deg,#3b82f6,#4f46e5)"
             :style="'width:' + progress + '%'"></div>
    </div>

    {{-- ── STEPS INDICATOR ── --}}
    <div class="bg-white border-b border-slate-100 px-6 py-5 overflow-x-auto shadow-sm">
        <div class="flex items-center justify-center gap-0 min-w-[480px] max-w-2xl mx-auto">
            @foreach(['Cuéntame de ti','Tu primera impresión','Por qué te eligen','Cómo te encuentran','Listo para publicar'] as $i => $label)
            <div class="flex items-center" @if($i < 4) style="flex:1" @endif>
                <div class="flex flex-col items-center gap-1.5">
                    <div class="wiz-step-dot"
                         :class="{
                             'wiz-step-active': {{ $i + 1 }} === step,
                             'wiz-step-done':   {{ $i + 1 }} < step,
                             'wiz-step-pending':{{ $i + 1 }} > step
                         }">
                        <template x-if="{{ $i + 1 }} < step">
                            <span class="iconify tabler--check size-3.5"></span>
                        </template>
                        <template x-if="{{ $i + 1 }} >= step">
                            <span>{{ $i + 1 }}</span>
                        </template>
                    </div>
                    <span class="text-xs font-medium whitespace-nowrap"
                          :class="{{ $i + 1 }} <= step ? 'text-slate-700' : 'text-slate-400'">{{ $label }}</span>
                </div>
                @if($i < 4)
                <div class="flex-1 h-px mx-2 mt-[-18px]"
                     :class="{{ $i + 1 }} < step ? 'bg-blue-400' : 'bg-slate-200'"></div>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    {{-- ── MAIN CONTENT ── --}}
    <main class="flex-1 flex items-center justify-center py-10 px-4">
        <div class="w-full max-w-2xl space-y-5">

            <form
                method="POST"
                action="{{ route('onboarding.store') }}"
                x-ref="wizardForm"
                @submit.prevent="submitting = true; $refs.wizardForm.submit()"
            >
                @csrf

                {{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
                     PASO 1 — Cuéntame de ti
                ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
                <div x-show="step === 1" x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0">

                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-slate-900 mb-1">Cuéntame de ti</h1>
                        <p class="text-slate-500 text-sm">Vamos a crear la página de tu negocio. Empieza por lo más básico.</p>
                    </div>

                    {{-- Nombre del negocio --}}
                    <div class="wiz-card p-6 mb-4">
                        <div class="form-control">
                            <label class="label pb-1" for="s1-business-name">
                                <span class="text-sm font-semibold text-slate-800">¿Cómo se llama tu negocio?</span>
                            </label>
                            <input id="s1-business-name"
                                   type="text"
                                   class="py-2 px-3 block w-full border border-slate-200 rounded-lg text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none disabled:opacity-50 disabled:pointer-events-none bg-white text-lg"
                                   placeholder="Ej: Pizzería Don Pepe"
                                   name="business_name"
                                   x-model="business_name"
                                   maxlength="255"
                                   autofocus>
                        </div>
                    </div>

                    {{-- Segmento --}}
                    <div class="wiz-card p-6 mb-4">
                        <div class="form-control">
                            <label class="label pb-2" for="s1-segment">
                                <span class="text-sm font-semibold text-slate-800">¿A qué te dedicas?</span>
                            </label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                @php
                                    $segmentIcons = [
                                        'restaurante' => ['icon' => 'tabler--tools-kitchen-2', 'label' => 'Restaurante / Comida'],
                                        'retail'      => ['icon' => 'tabler--shopping-bag',     'label' => 'Tienda / Comercio'],
                                        'salud'       => ['icon' => 'tabler--heart-rate-monitor',      'label' => 'Salud & Belleza'],
                                        'profesional' => ['icon' => 'tabler--briefcase',        'label' => 'Servicios Profesionales'],
                                        'tecnico'     => ['icon' => 'tabler--tool',             'label' => 'Servicios Técnicos'],
                                        'educacion'   => ['icon' => 'tabler--school',           'label' => 'Educación / Academia'],
                                        'transporte'  => ['icon' => 'tabler--truck-delivery',   'label' => 'Transporte / Delivery'],
                                    ];
                                @endphp
                                @foreach($segmentIcons as $key => $seg)
                                <label class="cursor-pointer">
                                    <input type="radio" name="business_segment"
                                           value="{{ $key }}"
                                           x-model="business_segment"
                                           class="sr-only peer">
                                    <div class="wiz-segment-card border-2 border-slate-200 rounded-xl p-3 text-center text-sm font-medium transition-all select-none flex flex-col items-center gap-1.5"
                                     :class="business_segment === '{{ $key }}' ? 'selected' : ''">
                                        <span class="iconify {{ $seg['icon'] }} size-6 text-slate-500" :class="business_segment === '{{ $key }}' ? 'text-blue-600' : ''"></span>
                                        <span>{{ $seg['label'] }}</span>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Ciudad --}}
                    <div class="wiz-card p-6 mb-4">
                        <div class="form-control">
                            <label class="label pb-1" for="s1-city">
                                <span class="text-sm font-semibold text-slate-800">¿Dónde estás ubicado?</span>
                                <span class="text-xs text-slate-400">Opcional</span>
                            </label>
                            <input id="s1-city"
                                   type="text"
                                   class="py-2 px-3 block w-full border border-slate-200 rounded-lg text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none disabled:opacity-50 disabled:pointer-events-none bg-white"
                                   placeholder="Ej: Maracaibo"
                                   name="city"
                                   x-model="city"
                                   maxlength="100">
                        </div>
                    </div>
                </div>

                {{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
                     PASO 2 — Tu primera impresión
                ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
                <div x-show="step === 2" x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0">

                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-slate-900 mb-1">Tu primera impresión</h1>
                        <p class="text-slate-500 text-sm">Lo primero que ve tu cliente cuando entra a tu página. Hazlo memorable.</p>
                    </div>

                    {{-- Eslogan --}}
                    <div class="wiz-card p-6 mb-4">
                        <div class="form-control">
                            <label class="label pb-1" for="s2-slogan">
                                <span class="text-sm font-semibold text-slate-800">Si un cliente te pregunta qué haces, ¿qué le respondes en una frase?</span>
                            </label>
                            <p class="text-xs text-slate-400 italic mb-3">Aparece grande en tu página. <em>"La mejor pizza de Maracaibo"</em></p>
                            <input id="s2-slogan"
                                   type="text"
                                   class="py-2 px-3 block w-full border border-slate-200 rounded-lg text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none disabled:opacity-50 disabled:pointer-events-none bg-white text-lg"
                                   placeholder="Ej: La mejor barber shop del centro"
                                   name="slogan"
                                   x-model="slogan"
                                   maxlength="80">
                            <div class="flex justify-end mt-1">
                                <span class="text-xs text-slate-400" x-text="slogan.length + '/80'"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Subtítulo hero --}}
                    <div class="wiz-card p-6 mb-4">
                        <div class="form-control">
                            <label class="label pb-1" for="s2-subtitle">
                                <span class="text-sm font-semibold text-slate-800">¿Qué te hace diferente o especial?</span>
                                <span class="text-xs text-slate-400">Opcional</span>
                            </label>
                            <p class="text-xs text-slate-400 italic mb-3">Complementa la frase anterior. No la repitas.</p>
                            <input id="s2-subtitle"
                                   type="text"
                                   class="py-2 px-3 block w-full border border-slate-200 rounded-lg text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none disabled:opacity-50 disabled:pointer-events-none bg-white"
                                   placeholder="Ej: Cortes modernos, cero espera, precios justos"
                                   name="content_blocks[hero][subtitle]"
                                   x-model="hero_subtitle"
                                   maxlength="150">
                            <div class="flex justify-end mt-1">
                                <span class="text-xs text-slate-400" x-text="hero_subtitle.length + '/150'"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Acerca de --}}
                    <div class="wiz-card p-6 mb-4">
                        <div class="form-control">
                            <label class="label pb-1" for="s2-about">
                                <span class="text-sm font-semibold text-slate-800">¿Cómo describirías tu negocio a alguien que no te conoce?</span>
                                <span class="text-xs text-slate-400">Opcional</span>
                            </label>
                            <p class="text-xs text-slate-400 italic mb-3">Aparece en la sección "Acerca de" de tu página.</p>
                            <textarea id="s2-about"
                                      class="py-2 px-3 block w-full border border-slate-200 rounded-lg text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none resize-none bg-white"
                                      rows="3"
                                      placeholder="Ej: Somos una barbería familiar con más de 8 años en el mercado. Ofrecemos cortes modernos y clásicos en un ambiente cómodo y acogedor..."
                                      name="about_text"
                                      x-model="about_text"
                                      maxlength="1000"></textarea>
                        </div>
                    </div>
                </div>

                {{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
                     PASO 3 — ¿Por qué te eligen a ti?
                ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
                <div x-show="step === 3" x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0">

                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-slate-900 mb-1">¿Por qué te eligen a ti?</h1>
                        <p class="text-slate-500 text-sm">Escribe 3 cosas que hacen que un cliente prefiera tu negocio sobre otro.</p>
                    </div>

                    <div class="wiz-card p-6 space-y-5">
                        <div class="form-control">
                            <label class="label pb-1" for="s3-vp1">
                                <span class="text-sm font-semibold text-slate-800">Razón 1 <span class="text-red-500">*</span></span>
                            </label>
                            <input id="s3-vp1"
                                   type="text"
                                   class="py-2 px-3 block w-full border border-slate-200 rounded-lg text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none disabled:opacity-50 disabled:pointer-events-none bg-white"
                                   placeholder="Ej: «Atención personalizada, te tratamos como familia»"
                                   name="value_prop_1"
                                   x-model="value_prop_1"
                                   maxlength="100">
                        </div>

                        <div class="form-control">
                            <label class="label pb-1" for="s3-vp2">
                                <span class="text-sm font-semibold text-slate-800">Razón 2</span>
                                <span class="text-xs text-slate-400">Opcional</span>
                            </label>
                            <input id="s3-vp2"
                                   type="text"
                                   class="py-2 px-3 block w-full border border-slate-200 rounded-lg text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none disabled:opacity-50 disabled:pointer-events-none bg-white"
                                   placeholder="Ej: «Llevamos 8 años en el mercado»"
                                   name="value_prop_2"
                                   x-model="value_prop_2"
                                   maxlength="100">
                        </div>

                        <div class="form-control">
                            <label class="label pb-1" for="s3-vp3">
                                <span class="text-sm font-semibold text-slate-800">Razón 3</span>
                                <span class="text-xs text-slate-400">Opcional</span>
                            </label>
                            <input id="s3-vp3"
                                   type="text"
                                   class="py-2 px-3 block w-full border border-slate-200 rounded-lg text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none disabled:opacity-50 disabled:pointer-events-none bg-white"
                                   placeholder="Ej: «Precio justo, calidad garantizada»"
                                   name="value_prop_3"
                                   x-model="value_prop_3"
                                   maxlength="100">
                        </div>
                    </div>
                </div>

                {{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
                     PASO 4 — ¿Cómo te encuentran?
                ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
                <div x-show="step === 4" x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0">

                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-slate-900 mb-1">¿Cómo te encuentran?</h1>
                        <p class="text-slate-500 text-sm">Datos de contacto, tu dirección web y el plan que activas hoy.</p>
                    </div>

                    {{-- Contacto --}}
                    <div class="wiz-card mb-4 p-6 space-y-4">
                        <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                            <span class="iconify tabler--phone size-4.5 text-blue-500"></span>
                            ¿Cómo te contactan?
                        </h3>

                        <div class="form-control">
                            <label class="label pb-1" for="s4-wa">
                                <span class="text-sm font-medium text-slate-700">WhatsApp</span>
                                <span class="text-xs text-slate-400">Opcional</span>
                            </label>
                            <div class="flex">
                                <span class="flex items-center px-3 text-sm text-slate-500 bg-slate-50 border border-r-0 border-slate-200 rounded-l-lg">+58</span>
                                <input id="s4-wa"
                                       type="tel"
                                       class="py-2 px-3 block w-full border border-l-0 border-slate-200 rounded-r-lg text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none disabled:opacity-50 disabled:pointer-events-none bg-white"
                                       placeholder="4141234567"
                                       name="whatsapp_sales"
                                       x-model="whatsapp_sales"
                                       maxlength="20">
                            </div>
                        </div>

                        <div class="form-control">
                            <label class="label pb-1" for="s4-phone">
                                <span class="text-sm font-medium text-slate-700">Teléfono adicional</span>
                                <span class="text-xs text-slate-400">Opcional</span>
                            </label>
                            <input id="s4-phone"
                                   type="tel"
                                   class="py-2 px-3 block w-full border border-slate-200 rounded-lg text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none disabled:opacity-50 disabled:pointer-events-none bg-white"
                                   placeholder="02616001234"
                                   name="phone"
                                   x-model="phone"
                                   maxlength="20">
                        </div>

                        <div class="form-control">
                            <label class="label pb-1" for="s4-email">
                                <span class="text-sm font-medium text-slate-700">Correo electrónico</span>
                                <span class="text-xs text-slate-400">Opcional</span>
                            </label>
                            <input id="s4-email"
                                   type="email"
                                   class="py-2 px-3 block w-full border border-slate-200 rounded-lg text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none disabled:opacity-50 disabled:pointer-events-none bg-white"
                                   placeholder="negocio@correo.com"
                                   name="email"
                                   x-model="email">
                        </div>
                    </div>

                    {{-- Subdominio --}}
                    <div class="wiz-card mb-4 p-6">
                        <div class="form-control">
                            <label class="label pb-1" for="s4-subdomain">
                                <span class="text-sm font-semibold text-slate-800">¿Qué subdominio quieres? <span class="text-red-500">*</span></span>
                            </label>
                            <p class="text-xs text-slate-400 italic mb-3">Tu página será: <strong x-text="subdomain ? subdomain + '.syntiweb.com' : 'tudominio.syntiweb.com'"></strong></p>

                            <div class="flex">
                                <input id="s4-subdomain"
                                       type="text"
                                       class="py-2 px-3 block w-full border border-slate-200 rounded-l-lg text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none bg-white"
                                       :class="{'border-emerald-500 ring-1 ring-emerald-500': subdomainAvailable === true, 'border-red-500 ring-1 ring-red-500': subdomainAvailable === false}"
                                       placeholder="mipizzeria"
                                       x-model="subdomainRaw"
                                       @input="onSubdomainInput()"
                                       maxlength="63">
                                <span class="flex items-center px-3 text-sm text-slate-400 bg-slate-50 border border-l-0 border-slate-200 rounded-r-lg whitespace-nowrap">.syntiweb.com</span>
                            </div>
                            <input type="hidden" name="subdomain" :value="subdomain">

                            <div class="mt-2 h-5 flex items-center gap-1.5 text-xs">
                                <template x-if="subdomainChecking">
                                    <span class="text-slate-400 flex items-center gap-1">
                                        <span class="inline-block w-3.5 h-3.5 border-2 border-slate-300 border-t-slate-600 rounded-full animate-spin"></span>
                                        Verificando...
                                    </span>
                                </template>
                                <template x-if="!subdomainChecking && subdomainAvailable === true">
                                    <span class="text-emerald-600 flex items-center gap-1">
                                        <span class="iconify tabler--circle-check size-3.5"></span>
                                        ¡Disponible!
                                    </span>
                                </template>
                                <template x-if="!subdomainChecking && subdomainAvailable === false">
                                    <span class="text-red-600 flex items-center gap-1">
                                        <span class="iconify tabler--circle-x size-3.5"></span>
                                        Ya está en uso, prueba otro
                                    </span>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Plan --}}
                    <div class="wiz-card mb-4 p-6">
                        <h3 class="font-semibold text-slate-800 mb-4 flex items-center gap-2">
                            <span class="iconify tabler--diamond size-4.5 text-blue-500"></span>
                            ¿Qué plan activas? <span class="text-red-500">*</span>
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <template x-for="plan in plans" :key="plan.id">
                                <label class="cursor-pointer">
                                    <input type="radio" name="plan_id"
                                           :value="plan.id"
                                           x-model="plan_id"
                                           class="sr-only peer">
                                    <div class="wiz-plan-card border-2 border-slate-200 p-4 rounded-xl transition-all select-none h-full cursor-pointer"
                                         :class="plan_id == plan.id ? 'selected' : ''">
                                        <div class="text-xs font-black uppercase tracking-widest text-blue-600 mb-2" x-text="plan.name"></div>
                                        <div class="text-2xl font-bold text-slate-900 mb-1">$<span x-text="plan.price_usd"></span></div>
                                        <div class="text-xs text-slate-400 mb-3">por año</div>
                                        <div class="space-y-1.5 text-xs text-slate-600">
                                            <div class="flex items-center gap-1.5">
                                                <span class="iconify tabler--package size-3 text-blue-500 shrink-0"></span>
                                                <span>hasta <span x-text="plan.products_limit"></span> productos</span>
                                            </div>
                                            <div class="flex items-center gap-1.5">
                                                <span class="iconify tabler--tool size-3 text-blue-500 shrink-0"></span>
                                                <span>hasta <span x-text="plan.services_limit"></span> servicios</span>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
                     PASO 5 — Todo listo — revisa antes de publicar
                ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
                <div x-show="step === 5" x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0">

                    <div class="mb-6">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:linear-gradient(135deg,#10b981,#059669)">
                                <span class="iconify tabler--rocket size-5 text-white"></span>
                            </div>
                            <h1 class="text-2xl font-bold text-slate-900">¡Tu negocio ya tiene todo para brillar!</h1>
                        </div>
                        <p class="text-slate-500 text-sm ml-[52px]">Revisa que los datos estén correctos. En segundos tendrás una página web real. Siempre puedes editar desde tu panel.</p>
                    </div>

                    <div class="wiz-card p-6 space-y-4">

                        {{-- Negocio --}}
                        <div class="flex justify-between items-start border-b border-slate-100 pb-3">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-widest text-blue-600 mb-0.5">Negocio</p>
                                <p class="font-semibold text-slate-900" x-text="business_name || '—'"></p>
                                <p class="text-sm text-slate-500" x-text="segmentLabel + (city ? ' · ' + city : '')"></p>
                            </div>
                            <button type="button" @click="step = 1" class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                                <span class="iconify tabler--edit size-4"></span>
                            </button>
                        </div>

                        {{-- Mensaje --}}
                        <div class="flex justify-between items-start border-b border-slate-100 pb-3">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-widest text-blue-600 mb-0.5">Primera impresión</p>
                                <p class="font-semibold text-slate-900 italic" x-text="slogan ? '«' + slogan + '»' : '—'"></p>
                                <p class="text-sm text-slate-500 mt-1" x-text="hero_subtitle || ''"></p>
                            </div>
                            <button type="button" @click="step = 2" class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                                <span class="iconify tabler--edit size-4"></span>
                            </button>
                        </div>

                        {{-- Diferenciadores --}}
                        <div class="flex justify-between items-start border-b border-slate-100 pb-3">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-widest text-blue-600 mb-0.5">Por qué te eligen</p>
                                <ul class="text-sm text-slate-600 space-y-0.5 mt-1">
                                    <li x-text="'✓ ' + (value_prop_1 || '—')"></li>
                                    <li x-show="value_prop_2" x-text="'✓ ' + value_prop_2"></li>
                                    <li x-show="value_prop_3" x-text="'✓ ' + value_prop_3"></li>
                                </ul>
                            </div>
                            <button type="button" @click="step = 3" class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                                <span class="iconify tabler--edit size-4"></span>
                            </button>
                        </div>

                        {{-- Contacto y plan --}}
                        <div class="flex justify-between items-start">
                            <div class="w-full">
                                <p class="text-xs font-bold uppercase tracking-widest text-blue-600 mb-1">Datos clave</p>
                                <div class="grid grid-cols-2 gap-x-6 gap-y-1 text-sm text-slate-600">
                                    <span><span class="text-slate-400">Subdominio:</span> <span x-text="subdomain + '.syntiweb.com'"></span></span>
                                    <span x-show="whatsapp_sales"><span class="text-slate-400">WhatsApp:</span> <span x-text="'+58 ' + whatsapp_sales"></span></span>
                                    <span x-show="email"><span class="text-slate-400">Email:</span> <span x-text="email"></span></span>
                                    <span x-show="selectedPlan"><span class="text-slate-400">Plan:</span> <strong x-text="selectedPlan?.name"></strong> — $<span x-text="selectedPlan?.price_usd"></span>/año</span>
                                </div>
                            </div>
                            <button type="button" @click="step = 4" class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors ml-2 shrink-0">
                                <span class="iconify tabler--edit size-4"></span>
                            </button>
                        </div>
                    </div>

                    {{-- Lo que recibirás --}}
                    <div class="mt-5 rounded-2xl overflow-hidden" style="border:1px solid #e2e8f0">
                        <div class="px-5 py-3 text-xs font-bold uppercase tracking-widest text-white" style="background:linear-gradient(135deg,#0f172a,#1e3a5f)">
                            <span class="iconify tabler--gift size-3.5 mr-1"></span>
                            Lo que vas a recibir al hacer clic
                        </div>
                        <div class="divide-y divide-slate-100">

                            {{-- URL pública local/dev --}}
                            <div class="flex items-center gap-3 px-5 py-3.5">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0" style="background:#eff6ff">
                                    <span class="iconify tabler--world size-4" style="color:#3b82f6"></span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-slate-500 mb-0.5">Tu sitio web público</p>
                                    <p class="text-sm font-mono text-blue-600 truncate">{{ config('app.url') }}/<span x-text="subdomain || 'tu-negocio'"></span></p>
                                </div>
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full shrink-0" style="background:#dcfce7;color:#16a34a">ACTIVO</span>
                            </div>

                            {{-- URL producción --}}
                            <div class="flex items-center gap-3 px-5 py-3.5">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0" style="background:#f0fdf4">
                                    <span class="iconify tabler--rocket size-4" style="color:#10b981"></span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-slate-500 mb-0.5">URL definitiva en producción</p>
                                    <p class="text-sm font-mono text-emerald-700 truncate"><span x-text="subdomain || 'tu-negocio'"></span>.syntiweb.com</p>
                                </div>
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full shrink-0" style="background:#fef9c3;color:#a16207">Pronto</span>
                            </div>

                            {{-- Dashboard --}}
                            <div class="flex items-center gap-3 px-5 py-3.5 bg-slate-50">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0" style="background:#eef2ff">
                                    <span class="iconify tabler--layout-dashboard size-4" style="color:#4f46e5"></span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-slate-500 mb-0.5">Tu panel de administración</p>
                                    <p class="text-xs text-slate-400">Agrega productos, cambia fotos, personaliza colores y mucho más.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── BOTONES DE NAVEGACIÓN ── --}}
                <div class="flex justify-between mt-6 gap-3">
                    <button type="button"
                            @click="prevStep()"
                            x-show="step > 1"
                            class="flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-slate-600 bg-white border border-slate-200 hover:border-slate-300 hover:bg-slate-50 transition-all">
                        <span class="iconify tabler--arrow-left size-4.5"></span>
                        Anterior
                    </button>
                    <div x-show="step === 1" class="flex-1"></div>

                    {{-- Siguiente (pasos 1-4) --}}
                    <button type="button"
                            @click="nextStep()"
                            x-show="step < 5"
                            :disabled="!canProceed"
                            class="wiz-btn-primary flex items-center gap-2 px-6 py-2.5 rounded-xl ml-auto">
                        Siguiente
                        <span class="iconify tabler--arrow-right size-4.5"></span>
                    </button>

                    {{-- Crear (paso 5) --}}
                    <button type="submit"
                            x-show="step === 5"
                            :disabled="submitting"
                            class="wiz-btn-primary flex items-center gap-2 px-8 py-3 rounded-xl text-base ml-auto">
                        <span x-show="!submitting" class="flex items-center gap-2">
                            <span class="iconify tabler--sparkles size-5"></span>
                            Crear mi página web
                        </span>
                        <span x-show="submitting" class="flex items-center gap-2">
                            <span class="inline-block w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                            Generando tu presencia digital...
                        </span>
                    </button>
                </div>

            </form>
        </div>
    </main>

</div>

@else
{{-- Modo público — redirigir a registro (Fase 2 — SYNTICAT) --}}
<script>window.location.href = '/register';</script>
@endif

</body>
</html>
