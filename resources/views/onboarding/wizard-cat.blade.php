<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu catálogo online — SYNTIcat</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Geist', ui-sans-serif, system-ui, sans-serif; }
        .wiz-gradient-header { background: #fff; border-bottom: 1px solid #e2e8f0; }
        .wiz-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 1rem; box-shadow: 0 4px 24px -4px rgba(0,0,0,0.08); }
        .wiz-btn-primary { background: #10b981; color: #fff; border: 0; border-radius: 0.625rem; font-weight: 700; transition: all .2s; box-shadow: 0 4px 14px -4px rgba(16,185,129,0.4); }
        .wiz-btn-primary:hover:not(:disabled) { background: #059669; transform: translateY(-1px); box-shadow: 0 8px 20px -4px rgba(16,185,129,0.45); }
        .wiz-btn-primary:disabled { opacity: 0.5; cursor: not-allowed; }
        .wiz-step-dot { width: 2rem; height: 2rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .75rem; font-weight: 700; transition: all .3s; }
        .wiz-step-active { background: #10b981; color: #fff; box-shadow: 0 4px 12px -2px rgba(16,185,129,0.4); }
        .wiz-step-done { background: #10b981; color: #fff; }
        .wiz-step-pending { background: #e2e8f0; color: #94a3b8; }
        .wiz-type-card:hover { border-color: #10b981; background: #ecfdf5; }
        .wiz-type-card.selected { border-color: #10b981; background: #ecfdf5; color: #047857; font-weight: 600; }
        .wiz-plan-card:hover { border-color: #10b981; }
        .wiz-plan-card.selected { border-color: #10b981; background: #ecfdf5; box-shadow: 0 0 0 2px rgba(16,185,129,0.2); }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">

<div
    x-data="{
        step: 1,
        totalSteps: 5,
        submitting: false,

        /* ── campos del formulario ── */
        business_name: '',
        store_type: '',
        whatsapp_sales: '',
        first_product_name: '',
        first_product_price: '',
        first_product_description: '',
        plan_id: '',
        subdomain: '',
        subdomainRaw: '',
        subdomainChecking: false,
        subdomainAvailable: null,
        subdomainTimer: null,

        /* ── planes (PHP → JS) ── */
        plans: {{ Js::from($plans->map(fn($p) => ['id' => $p->id, 'slug' => $p->slug, 'name' => $p->name, 'price_usd' => $p->price_usd, 'products_limit' => $p->products_limit])) }},

        /* ── killer features por slug ── */
        planFeature(slug) {
            const map = {
                'cat-basico':    'Catálogo simple · 20 productos · Botón WA directo',
                'cat-semestral': 'Carrito básico · 100 productos · Variantes',
                'cat-anual':     'Carrito + Mini Order SC-XXXX · Ilimitados ⭐'
            };
            return map[slug] || '';
        },

        /* ── periodo legible ── */
        planPeriod(slug) {
            if (slug === 'cat-basico') return '/mes';
            if (slug === 'cat-semestral') return '/semestre';
            return '/año';
        },

        /* ── placeholder dinámico producto ── */
        get productPlaceholder() {
            const map = {
                clothing:  'Ej: Vestido floral talla M',
                supplier:  'Ej: Caja de tornillos 50u',
                tech:      'Ej: Audífonos Bluetooth',
                home:      'Ej: Lámpara de escritorio',
                other:     'Ej: Mi primer producto',
            };
            return map[this.store_type] || 'Ej: Producto';
        },

        /* ── progreso ── */
        get progress() {
            return Math.round(((this.step - 1) / (this.totalSteps - 1)) * 100);
        },

        /* ── puede avanzar? ── */
        get canProceed() {
            if (this.step === 1) return true;
            if (this.step === 2) return this.store_type !== '';
            if (this.step === 3) return this.whatsapp_sales.length >= 10;
            if (this.step === 4) return this.first_product_name.trim() !== '' && this.first_product_price !== '';
            if (this.step === 5) return this.subdomain.trim() !== '' && this.plan_id !== '' && this.subdomainAvailable === true;
            return true;
        },

        /* ── plan seleccionado ── */
        get selectedPlan() {
            return this.plans.find(p => p.id == this.plan_id) ?? null;
        },

        /* ── check subdominio debounce 600ms ── */
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

    {{-- ── HEADER ── --}}
    <header class="wiz-gradient-header px-6 py-3.5 flex items-center gap-3 sticky top-0 z-50">
        <a href="/" class="flex items-center gap-2">
            <img src="{{ asset('brand/syntiweb-logo-positive.svg') }}" alt="SYNTIweb" width="32" height="32">
            <span class="font-bold text-lg tracking-tight">
                <span style="color:#1a1a1a">SYNTI</span><span style="color:#4A80E4">web</span>
            </span>
        </a>
        <span class="ml-auto text-sm text-slate-400">Paso <span x-text="step" class="text-slate-700 font-semibold"></span> de <span x-text="totalSteps" class="text-slate-400"></span></span>
    </header>

    {{-- ── PROGRESS BAR ── --}}
    <div class="h-1 bg-slate-200">
        <div class="h-1 transition-all duration-500 bg-[#10b981]" :style="'width:' + progress + '%'"></div>
    </div>

    {{-- ── STEPS INDICATOR ── --}}
    <div class="bg-white border-b border-slate-100 px-6 py-5 overflow-x-auto">
        <div class="flex items-center justify-center gap-0 min-w-[480px] max-w-2xl mx-auto">
            @foreach(['Bienvenida','Tu tienda','WhatsApp','Productos','Activar'] as $i => $label)
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
                     :class="{{ $i + 1 }} < step ? 'bg-[#10b981]' : 'bg-slate-200'"></div>
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
                action="{{ route('onboarding.store.cat') }}"
                x-ref="wizardForm"
                @submit.prevent="submitting = true; $refs.wizardForm.submit()"
            >
                @csrf

                {{-- Campos hidden --}}
                <input type="hidden" name="business_name" :value="business_name">
                <input type="hidden" name="store_type" :value="store_type">
                <input type="hidden" name="whatsapp_sales" :value="whatsapp_sales">
                <input type="hidden" name="first_product_name" :value="first_product_name">
                <input type="hidden" name="first_product_price" :value="first_product_price">
                <input type="hidden" name="first_product_description" :value="first_product_description">
                <input type="hidden" name="subdomain" :value="subdomain">
                <input type="hidden" name="plan_id" :value="plan_id">

                {{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
                     PASO 1 — Bienvenida Cat
                ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
                <div x-show="step === 1" x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0">

                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-slate-900 mb-1">Tu tienda online en 8 minutos</h1>
                        <p class="text-slate-500 text-sm">Catálogo visual con carrito. Tus clientes piden por WhatsApp.</p>
                    </div>

                    <div class="wiz-card p-8" style="background:#f0fdf4">
                        <div class="space-y-5">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 bg-emerald-100">
                                    <span class="iconify tabler--photo size-5 text-emerald-600"></span>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-900 text-sm">Fotos profesionales de tus productos</p>
                                    <p class="text-xs text-slate-500 mt-0.5">Sube imágenes y tu catálogo luce increíble al instante.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 bg-emerald-100">
                                    <span class="iconify tabler--shopping-cart size-5 text-emerald-600"></span>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-900 text-sm">Carrito completo con pedido estructurado</p>
                                    <p class="text-xs text-slate-500 mt-0.5">Tus clientes arman su carrito y envían el pedido organizado.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 bg-emerald-100">
                                    <span class="iconify tabler--brand-whatsapp size-5 text-emerald-600"></span>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-900 text-sm">Cada pedido llega organizado a tu WhatsApp</p>
                                    <p class="text-xs text-slate-500 mt-0.5">Recibes nombre, productos, cantidades y total. Listo para despachar.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
                     PASO 2 — Tipo de tienda
                ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
                <div x-show="step === 2" x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0">

                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-slate-900 mb-1">¿Qué tipo de tienda tienes?</h1>
                        <p class="text-slate-500 text-sm">Esto nos ayuda a personalizar tu catálogo desde el primer momento.</p>
                    </div>

                    <div class="wiz-card p-6">
                        <div class="grid grid-cols-2 gap-3">
                            @php
                                $storeTypes = [
                                    'clothing' => ['icon' => 'tabler--hanger',         'label' => 'Ropa y accesorios'],
                                    'supplier' => ['icon' => 'tabler--package',        'label' => 'Proveedor / Mayorista'],
                                    'tech'     => ['icon' => 'tabler--device-laptop',  'label' => 'Tecnología y electrónica'],
                                    'home'     => ['icon' => 'tabler--home',           'label' => 'Hogar y decoración'],
                                ];
                            @endphp
                            @foreach($storeTypes as $key => $type)
                            <label class="cursor-pointer">
                                <input type="radio" value="{{ $key }}" x-model="store_type" class="sr-only peer">
                                <div class="wiz-type-card border-2 border-slate-200 rounded-xl p-4 text-center text-sm font-medium transition-all select-none flex flex-col items-center gap-2"
                                     :class="store_type === '{{ $key }}' ? 'selected' : ''">
                                    <span class="iconify {{ $type['icon'] }} size-7 text-slate-500" :class="store_type === '{{ $key }}' ? '!text-[#10b981]' : ''"></span>
                                    <span>{{ $type['label'] }}</span>
                                </div>
                            </label>
                            @endforeach
                            {{-- "Otro" centrado --}}
                            <label class="cursor-pointer col-span-2 max-w-[calc(50%-0.375rem)] mx-auto">
                                <input type="radio" value="other" x-model="store_type" class="sr-only peer">
                                <div class="wiz-type-card border-2 border-slate-200 rounded-xl p-4 text-center text-sm font-medium transition-all select-none flex flex-col items-center gap-2 w-full"
                                     :class="store_type === 'other' ? 'selected' : ''">
                                    <span class="iconify tabler--dots size-7 text-slate-500" :class="store_type === 'other' ? '!text-[#10b981]' : ''"></span>
                                    <span>Otro tipo de tienda</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
                     PASO 3 — WhatsApp
                ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
                <div x-show="step === 3" x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0">

                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-slate-900 mb-1">¿A qué número llegan los pedidos?</h1>
                        <p class="text-slate-500 text-sm">Cada pedido del carrito llegará organizado a este número.</p>
                    </div>

                    <div class="wiz-card p-6">
                        <div>
                            <label class="text-sm font-semibold text-slate-800 block mb-1" for="s3-whatsapp">
                                WhatsApp de pedidos <span class="text-red-500">*</span>
                            </label>
                            <div class="flex">
                                <span class="flex items-center px-3 text-sm text-slate-500 bg-slate-50 border border-r-0 border-slate-200 rounded-l-lg font-medium">+58</span>
                                <input id="s3-whatsapp"
                                       type="tel"
                                       class="py-2 px-3 block w-full border border-slate-200 rounded-r-lg text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#10b981] focus:ring-1 focus:ring-[#10b981] focus:outline-none bg-white"
                                       placeholder="4141234567"
                                       x-model="whatsapp_sales"
                                       @input="whatsapp_sales = whatsapp_sales.replace(/[^0-9]/g, '')"
                                       maxlength="10">
                            </div>
                            <p class="text-xs text-slate-400 mt-2">Solo números. Mínimo 10 dígitos.</p>

                            {{-- Link prueba --}}
                            <div x-show="whatsapp_sales.length >= 10" x-transition class="mt-3">
                                <a :href="'https://wa.me/58' + whatsapp_sales" target="_blank" rel="noopener"
                                   class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-600 hover:text-emerald-700 hover:underline">
                                    <span class="iconify tabler--brand-whatsapp size-4"></span>
                                    Probar → wa.me/58<span x-text="whatsapp_sales"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
                     PASO 4 — Primer producto
                ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
                <div x-show="step === 4" x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0">

                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-slate-900 mb-1">Agrega tu primer producto</h1>
                        <p class="text-slate-500 text-sm">Con esto tu catálogo ya tiene vida.</p>
                    </div>

                    {{-- Nombre de la tienda --}}
                    <div class="wiz-card p-6 mb-4">
                        <label class="text-sm font-semibold text-slate-800 block mb-1" for="s4-name">
                            ¿Cómo se llama tu tienda?
                        </label>
                        <input id="s4-name"
                               type="text"
                               class="py-2 px-3 block w-full border border-slate-200 rounded-lg text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#10b981] focus:ring-1 focus:ring-[#10b981] focus:outline-none bg-white text-lg"
                               placeholder="Ej: Tienda Moda Caracas"
                               x-model="business_name"
                               maxlength="255">
                    </div>

                    {{-- Primer producto --}}
                    <div class="wiz-card p-6">
                        <label class="text-sm font-semibold text-slate-800 block mb-1">
                            Primer producto <span class="text-red-500">*</span>
                        </label>
                        <p class="text-xs text-slate-400 italic mb-4">Puedes agregar más productos desde tu panel.</p>

                        <div class="space-y-3">
                            <div>
                                <label class="text-xs text-slate-500 block mb-1" for="s4-product-name">Nombre del producto</label>
                                <input id="s4-product-name"
                                       type="text"
                                       class="py-2 px-3 block w-full border border-slate-200 rounded-lg text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#10b981] focus:ring-1 focus:ring-[#10b981] focus:outline-none bg-white"
                                       :placeholder="productPlaceholder"
                                       x-model="first_product_name"
                                       maxlength="255">
                            </div>
                            <div>
                                <label class="text-xs text-slate-500 block mb-1" for="s4-product-price">Precio (USD)</label>
                                <input id="s4-product-price"
                                       type="number"
                                       class="py-2 px-3 block w-full border border-slate-200 rounded-lg text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#10b981] focus:ring-1 focus:ring-[#10b981] focus:outline-none bg-white"
                                       placeholder="0.00"
                                       step="0.01"
                                       min="0"
                                       x-model="first_product_price">
                            </div>
                            <div>
                                <label class="text-xs text-slate-500 block mb-1" for="s4-product-desc">Descripción <span class="text-slate-400">(opcional)</span></label>
                                <textarea id="s4-product-desc"
                                          class="py-2 px-3 block w-full border border-slate-200 rounded-lg text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#10b981] focus:ring-1 focus:ring-[#10b981] focus:outline-none bg-white resize-none"
                                          placeholder="Descripción opcional (talla, color, material...)"
                                          rows="2"
                                          x-model="first_product_description"
                                          maxlength="500"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
                     PASO 5 — Subdominio + Plan
                ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
                <div x-show="step === 5" x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0">

                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-slate-900 mb-1">¿Cómo te encuentran?</h1>
                        <p class="text-slate-500 text-sm">Tu dirección web y el plan que activas hoy.</p>
                    </div>

                    {{-- Subdominio --}}
                    <div class="wiz-card mb-4 p-6">
                        <label class="text-sm font-semibold text-slate-800 block mb-1" for="s5-subdomain">
                            ¿Qué subdominio quieres? <span class="text-red-500">*</span>
                        </label>
                        <p class="text-xs text-slate-400 italic mb-3">Tu catálogo estará en: <strong x-text="subdomain ? subdomain + '.syntiweb.com' : 'tutienda.syntiweb.com'"></strong></p>

                        <div class="flex">
                            <input id="s5-subdomain"
                                   type="text"
                                   class="py-2 px-3 block w-full border border-slate-200 rounded-l-lg text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#10b981] focus:ring-1 focus:ring-[#10b981] focus:outline-none bg-white"
                                   :class="{'!border-emerald-500 !ring-1 !ring-emerald-500': subdomainAvailable === true, '!border-red-500 !ring-1 !ring-red-500': subdomainAvailable === false}"
                                   placeholder="mitienda"
                                   x-model="subdomainRaw"
                                   @input="onSubdomainInput()"
                                   maxlength="63">
                            <span class="flex items-center px-3 text-sm text-slate-400 bg-slate-50 border border-l-0 border-slate-200 rounded-r-lg whitespace-nowrap">.syntiweb.com</span>
                        </div>

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

                    {{-- Plan --}}
                    <div class="wiz-card mb-4 p-6">
                        <h3 class="font-semibold text-slate-800 mb-4 flex items-center gap-2">
                            <span class="iconify tabler--diamond size-4.5 text-[#10b981]"></span>
                            ¿Qué plan activas? <span class="text-red-500">*</span>
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <template x-for="plan in [...plans].sort((a, b) => parseFloat(a.price_usd) - parseFloat(b.price_usd))" :key="plan.id">
                                <label class="cursor-pointer">
                                    <input type="radio" :value="plan.id" x-model="plan_id" class="sr-only peer">
                                    <div class="wiz-plan-card border-2 border-slate-200 p-4 rounded-xl transition-all select-none h-full cursor-pointer relative"
                                         :class="{
                                             'selected': plan_id == plan.id,
                                             '!border-[#10b981]': plan.slug === 'cat-anual' && plan_id != plan.id
                                         }">
                                        {{-- Badge recomendado para plan anual --}}
                                        <template x-if="plan.slug === 'cat-anual'">
                                            <span class="absolute -top-2.5 left-1/2 -translate-x-1/2 inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#10b981] text-white whitespace-nowrap">
                                                Recomendado
                                            </span>
                                        </template>
                                        <div class="text-xs font-black uppercase tracking-widest text-[#10b981] mb-2" x-text="plan.name"></div>
                                        <div class="text-2xl font-bold text-slate-900 mb-1">$<span x-text="plan.price_usd"></span></div>
                                        <div class="text-xs text-slate-400 mb-3" x-text="planPeriod(plan.slug)"></div>
                                        <div class="text-xs text-slate-600 leading-relaxed" x-text="planFeature(plan.slug)"></div>
                                    </div>
                                </label>
                            </template>
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

                    {{-- Siguiente (pasos 1–4) --}}
                    <button type="button"
                            @click="nextStep()"
                            x-show="step < 5"
                            :disabled="!canProceed"
                            class="wiz-btn-primary flex items-center gap-2 px-6 py-2.5 rounded-xl ml-auto"
                            x-text="step === 1 ? 'Comenzar →' : 'Siguiente'"
                    ></button>

                    {{-- Crear (paso 5) --}}
                    <button type="submit"
                            x-show="step === 5"
                            :disabled="submitting || !canProceed"
                            class="wiz-btn-primary flex items-center gap-2 px-8 py-3 rounded-xl text-base ml-auto">
                        <span x-show="!submitting" class="flex items-center gap-2">
                            <span class="iconify tabler--shopping-bag size-5"></span>
                            Crear mi catálogo
                        </span>
                        <span x-show="submitting" class="flex items-center gap-2">
                            <span class="inline-block w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                            Creando tu catálogo...
                        </span>
                    </button>
                </div>

            </form>
        </div>
    </main>

</div>

</body>
</html>
