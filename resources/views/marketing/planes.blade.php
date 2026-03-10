<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Planes y Precios — SYNTIweb</title>
    <meta name="description" content="Elige tu plan SYNTIweb. Precios claros, sin letra pequeña. Landing, menú digital o catálogo para negocios venezolanos.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --sw-studio: #4A80E4;
            --sw-food: #f97316;
            --sw-cat: #10b981;
        }
        body { font-family: 'Geist', ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>
<body class="bg-surface text-foreground antialiased">

    {{-- ═══ HEADER ═══ --}}
    <header class="bg-surface border-b border-border/40">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <img src="{{ asset('brand/syntiweb-logo-positive.svg') }}" width="32" height="32" alt="SYNTIweb">
                <span class="text-lg font-bold tracking-tight">
                    <span style="color:#1a1a1a">SYNTI</span><span style="color:#4A80E4">web</span>
                </span>
            </a>
            <a href="{{ url('/') }}"
               class="text-sm font-medium text-foreground/60 hover:text-foreground transition-colors">
                &larr; Volver al inicio
            </a>
        </div>
    </header>

    {{-- ═══ HERO ═══ --}}
    <section class="bg-surface py-16 lg:py-24 text-center">
        <div class="mx-auto max-w-3xl px-4">
            <h1 class="text-3xl font-bold md:text-4xl lg:text-5xl text-foreground"
                style="text-shadow: 0 4px 24px color-mix(in oklch, var(--color-foreground) 15%, transparent), 0 1px 4px color-mix(in oklch, var(--color-foreground) 8%, transparent);">
                Elige tu plan
            </h1>
            <p class="mt-4 text-lg text-foreground/70 max-w-xl mx-auto">
                Precios claros. Sin letra pequeña. Para negocios venezolanos.
            </p>
        </div>
    </section>

    {{-- ═══ TABS + PANELS ═══ --}}
    <section class="pb-20 lg:pb-32" x-data="{ tab: 'studio' }">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Tab buttons --}}
            <div class="flex justify-center gap-2 mb-12">
                <button @click="tab = 'studio'"
                        :class="tab === 'studio'
                            ? 'text-white shadow-lg'
                            : 'bg-surface border border-border text-foreground/60 hover:text-foreground'"
                        :style="tab === 'studio' ? 'background:var(--sw-studio)' : ''"
                        class="px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-200">
                    <span class="iconify tabler--world size-4"></span> SYNTIstudio
                </button>
                <button @click="tab = 'food'"
                        :class="tab === 'food'
                            ? 'text-white shadow-lg'
                            : 'bg-surface border border-border text-foreground/60 hover:text-foreground'"
                        :style="tab === 'food' ? 'background:var(--sw-food)' : ''"
                        class="px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-200">
                    <span class="iconify tabler--tools-kitchen-2 size-4"></span> SYNTIfood
                </button>
                <button @click="tab = 'cat'"
                        :class="tab === 'cat'
                            ? 'text-white shadow-lg'
                            : 'bg-surface border border-border text-foreground/60 hover:text-foreground'"
                        :style="tab === 'cat' ? 'background:var(--sw-cat)' : ''"
                        class="px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-200">
                    <span class="iconify tabler--shopping-bag size-4"></span> SYNTIcat
                </button>
            </div>

            {{-- ═══ PANEL STUDIO ═══ --}}
            <div x-show="tab === 'studio'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">

                <div class="text-center mb-10">
                    <h2 class="text-xl font-semibold md:text-2xl text-foreground">Tu web completa. Tu negocio en Google.</h2>
                    <p class="mt-2 text-foreground/60">Compite con Wix ($192/año) a precio venezolano.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 max-w-5xl mx-auto">
                    @foreach($studioPlans as $plan)
                    @php
                        $isPopular  = $plan->slug === 'crecimiento';
                        $accent     = '#4A80E4';
                        $tagline    = match($plan->slug) {
                            'oportunidad' => 'Para validar. Cero riesgo.',
                            'crecimiento' => 'Para crecer. Tu competencia está aquí.',
                            'vision'      => 'Para dominar. Eres líder local.',
                            default       => '',
                        };
                        $features = match($plan->slug) {
                            'oportunidad' => [
                                'Landing profesional completa',
                                'Apareces en Google (SEO básico)',
                                'WhatsApp integrado',
                                'Visible en celular',
                                'Horarios y ubicación',
                                'Código QR para compartir',
                                '20 productos · 3 servicios',
                                '10 paletas de color',
                            ],
                            'crecimiento' => [
                                'Todo de Oportunidad +',
                                'Sección Acerca de',
                                'Testimonios de clientes',
                                'Analytics en tiempo real',
                                'Redes sociales conectadas',
                                '50 productos · 6 servicios',
                                '17 paletas de color',
                            ],
                            'vision' => [
                                'Todo de Crecimiento +',
                                'Preguntas frecuentes (FAQ)',
                                'Sucursales múltiples (hasta 3)',
                                'Galería de fotos por producto',
                                'Colores personalizados ilimitados',
                                'Productos ilimitados · 9 servicios',
                            ],
                            default => [],
                        };
                    @endphp
                    <div class="relative bg-white border rounded-2xl shadow-sm p-6 lg:p-8 flex flex-col hover:shadow-[0_8px_30px_rgba(0,0,0,0.10)] hover:-translate-y-1 transition-all duration-300
                                {{ $isPopular ? 'border-transparent' : 'border-border' }}"
                         @if($isPopular) style="box-shadow:0 0 0 2px {{ $accent }}" @endif>

                        @if($isPopular)
                        <span class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 inline-flex items-center px-3 py-1 rounded-full text-xs font-bold text-white"
                              style="background:{{ $accent }}">
                            Más popular
                        </span>
                        @endif

                        <div class="mb-6">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-foreground/50">{{ $plan->name }}</h3>
                            <div class="mt-2 flex items-baseline gap-1">
                                <span class="text-4xl font-bold"
                                      style="color:{{ $accent }};text-shadow:0 4px 24px color-mix(in oklch, {{ $accent }} 20%, transparent)">
                                    ${{ intval($plan->price_usd) }}
                                </span>
                                <span class="text-foreground/50 text-sm">/año</span>
                            </div>
                            <p class="mt-2 text-sm text-foreground/60">{{ $tagline }}</p>
                        </div>

                        <div class="border-t border-border/50 pt-5 mb-6 flex-1">
                            <ul class="space-y-3">
                                @foreach($features as $feat)
                                <li class="flex items-start gap-2 text-sm text-foreground/80">
                                    <span class="mt-0.5 shrink-0" style="color:{{ $accent }}">✓</span>
                                    {{ $feat }}
                                </li>
                                @endforeach
                            </ul>
                        </div>

                        <a href="{{ route('onboarding.studio', ['plan_id' => $plan->id]) }}"
                           class="block w-full text-center py-3 px-4 rounded-lg font-semibold text-white transition-all duration-200 hover:-translate-y-0.5"
                           style="background:{{ $accent }};box-shadow:0 4px 14px 0 color-mix(in oklch, {{ $accent }} 40%, transparent)"
                           onmouseover="this.style.boxShadow='0 6px 20px 0 color-mix(in oklch, {{ $accent }} 50%, transparent)'"
                           onmouseout="this.style.boxShadow='0 4px 14px 0 color-mix(in oklch, {{ $accent }} 40%, transparent)'">
                            Empezar con {{ $plan->name }}
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ═══ PANEL FOOD ═══ --}}
            <div x-show="tab === 'food'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">

                <div class="text-center mb-10">
                    <h2 class="text-xl font-semibold md:text-2xl text-foreground">Tu menú digital. Pedidos por WhatsApp.</h2>
                    <p class="mt-2 text-foreground/60">Sin imprimir cartas. Sin apps complicadas.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 max-w-5xl mx-auto">
                    @foreach($foodPlans as $plan)
                    @php
                        $isRecommended = $plan->slug === 'food-anual';
                        $accent        = '#f97316';
                        $tagline       = match($plan->slug) {
                            'food-basico'    => 'Para empezar. Tu carta en digital.',
                            'food-semestral' => 'Para crecer. Organización total.',
                            'food-anual'     => 'Para dominar. Pedidos directos.',
                            default          => '',
                        };
                        $period = match($plan->slug) {
                            'food-basico'    => '/mes',
                            'food-semestral' => '/semestre',
                            'food-anual'     => '/año',
                            default          => '/año',
                        };
                        $features = match($plan->slug) {
                            'food-basico' => [
                                'Menú digital activo 24/7',
                                'Lista de platos con precio',
                                'Enlace para compartir',
                                'Hasta 50 ítems en menú',
                                '6 fotos de categorías',
                            ],
                            'food-semestral' => [
                                'Todo de Básico +',
                                'Categorías organizadas',
                                'Tasa BCV automática',
                                'Horarios de atención',
                                'Hasta 100 ítems · 12 fotos',
                            ],
                            'food-anual' => [
                                'Todo de Semestral +',
                                'Pedido Rápido → WhatsApp ⭐',
                                'Acumulador de ítems',
                                'String de pedido estructurado',
                                'Hasta 150 ítems · 18 fotos',
                            ],
                            default => [],
                        };
                    @endphp
                    <div class="relative bg-white border rounded-2xl shadow-sm p-6 lg:p-8 flex flex-col hover:shadow-[0_8px_30px_rgba(0,0,0,0.10)] hover:-translate-y-1 transition-all duration-300
                                {{ $isRecommended ? 'border-transparent' : 'border-border' }}"
                         @if($isRecommended) style="box-shadow:0 0 0 2px {{ $accent }}" @endif>

                        @if($isRecommended)
                        <span class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 inline-flex items-center px-3 py-1 rounded-full text-xs font-bold text-white"
                              style="background:{{ $accent }}">
                            Recomendado
                        </span>
                        @endif

                        <div class="mb-6">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-foreground/50">{{ $plan->name }}</h3>
                            <div class="mt-2 flex items-baseline gap-1">
                                <span class="text-4xl font-bold"
                                      style="color:{{ $accent }};text-shadow:0 4px 24px color-mix(in oklch, {{ $accent }} 20%, transparent)">
                                    ${{ intval($plan->price_usd) }}
                                </span>
                                <span class="text-foreground/50 text-sm">{{ $period }}</span>
                            </div>
                            <p class="mt-2 text-sm text-foreground/60">{{ $tagline }}</p>
                        </div>

                        <div class="border-t border-border/50 pt-5 mb-6 flex-1">
                            <ul class="space-y-3">
                                @foreach($features as $feat)
                                <li class="flex items-start gap-2 text-sm text-foreground/80">
                                    <span class="mt-0.5 shrink-0" style="color:{{ $accent }}">✓</span>
                                    {{ $feat }}
                                </li>
                                @endforeach
                            </ul>
                        </div>

                        <a href="{{ route('onboarding.food', ['plan_id' => $plan->id]) }}"
                           class="block w-full text-center py-3 px-4 rounded-lg font-semibold text-white transition-all duration-200 hover:-translate-y-0.5"
                           style="background:{{ $accent }};box-shadow:0 4px 14px 0 color-mix(in oklch, {{ $accent }} 40%, transparent)"
                           onmouseover="this.style.boxShadow='0 6px 20px 0 color-mix(in oklch, {{ $accent }} 50%, transparent)'"
                           onmouseout="this.style.boxShadow='0 4px 14px 0 color-mix(in oklch, {{ $accent }} 40%, transparent)'">
                            Empezar con {{ $plan->name }}
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ═══ PANEL CAT ═══ --}}
            <div x-show="tab === 'cat'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">

                <div class="text-center mb-10">
                    <h2 class="text-xl font-semibold md:text-2xl text-foreground">Tu catálogo con carrito. Vende más.</h2>
                    <p class="mt-2 text-foreground/60">Carrito WhatsApp que Cattaly ($97/año) no tiene.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 max-w-5xl mx-auto">
                    @foreach($catPlans as $plan)
                    @php
                        $isRecommended = $plan->slug === 'cat-anual';
                        $accent        = '#10b981';
                        $tagline       = match($plan->slug) {
                            'cat-basico'    => 'Para empezar. Tu vitrina digital.',
                            'cat-semestral' => 'Para vender. Carrito incluido.',
                            'cat-anual'     => 'Para escalar. Sin límites.',
                            default         => '',
                        };
                        $period = match($plan->slug) {
                            'cat-basico'    => '/mes',
                            'cat-semestral' => '/semestre',
                            'cat-anual'     => '/año',
                            default         => '/año',
                        };
                        $features = match($plan->slug) {
                            'cat-basico' => [
                                'Catálogo visual activo 24/7',
                                'Hasta 20 productos',
                                '1 foto por producto',
                                'Botón WhatsApp directo',
                                'Sin carrito (plan entrada)',
                            ],
                            'cat-semestral' => [
                                'Todo de Básico +',
                                'Carrito básico incluido ✅',
                                'Hasta 100 productos',
                                '3 fotos por producto',
                                'Variantes: talla y color',
                            ],
                            'cat-anual' => [
                                'Todo de Semestral +',
                                'Mini Order ID SC-XXXX ⭐',
                                'Productos ilimitados',
                                '6 fotos por producto',
                                'Todas las variantes + opciones',
                            ],
                            default => [],
                        };
                    @endphp
                    <div class="relative bg-white border rounded-2xl shadow-sm p-6 lg:p-8 flex flex-col hover:shadow-[0_8px_30px_rgba(0,0,0,0.10)] hover:-translate-y-1 transition-all duration-300
                                {{ $isRecommended ? 'border-transparent' : 'border-border' }}"
                         @if($isRecommended) style="box-shadow:0 0 0 2px {{ $accent }}" @endif>

                        @if($isRecommended)
                        <span class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 inline-flex items-center px-3 py-1 rounded-full text-xs font-bold text-white"
                              style="background:{{ $accent }}">
                            Recomendado
                        </span>
                        @endif

                        <div class="mb-6">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-foreground/50">{{ $plan->name }}</h3>
                            <div class="mt-2 flex items-baseline gap-1">
                                <span class="text-4xl font-bold"
                                      style="color:{{ $accent }};text-shadow:0 4px 24px color-mix(in oklch, {{ $accent }} 20%, transparent)">
                                    ${{ intval($plan->price_usd) }}
                                </span>
                                <span class="text-foreground/50 text-sm">{{ $period }}</span>
                            </div>
                            <p class="mt-2 text-sm text-foreground/60">{{ $tagline }}</p>
                        </div>

                        <div class="border-t border-border/50 pt-5 mb-6 flex-1">
                            <ul class="space-y-3">
                                @foreach($features as $feat)
                                <li class="flex items-start gap-2 text-sm text-foreground/80">
                                    <span class="mt-0.5 shrink-0" style="color:{{ $accent }}">✓</span>
                                    {{ $feat }}
                                </li>
                                @endforeach
                            </ul>
                        </div>

                        <a href="{{ route('onboarding.cat', ['plan_id' => $plan->id]) }}"
                           class="block w-full text-center py-3 px-4 rounded-lg font-semibold text-white transition-all duration-200 hover:-translate-y-0.5"
                           style="background:{{ $accent }};box-shadow:0 4px 14px 0 color-mix(in oklch, {{ $accent }} 40%, transparent)"
                           onmouseover="this.style.boxShadow='0 6px 20px 0 color-mix(in oklch, {{ $accent }} 50%, transparent)'"
                           onmouseout="this.style.boxShadow='0 4px 14px 0 color-mix(in oklch, {{ $accent }} 40%, transparent)'">
                            Empezar con {{ $plan->name }}
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </section>

    {{-- ═══ COMPARATIVA / AYUDA ═══ --}}
    <section class="py-12 bg-background border-t border-border/30">
        <div class="mx-auto max-w-2xl px-4 text-center">
            <p class="text-lg font-semibold text-foreground mb-2">¿No sabes cuál elegir?</p>
            <p class="text-foreground/60 mb-6">Te ayudamos a encontrar el plan perfecto para tu negocio.</p>
            <a href="https://wa.me/584120000000?text={{ urlencode('Hola, necesito ayuda para elegir mi plan SYNTIweb') }}"
               target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-2 py-3 px-6 rounded-lg font-semibold text-white transition-all duration-200 hover:-translate-y-0.5"
               style="background:#25d366;box-shadow:0 4px 14px 0 rgba(37,211,102,0.35)">
                <span class="iconify tabler--brand-whatsapp size-5" aria-hidden="true"></span>
                Escríbenos por WhatsApp
            </a>
        </div>
    </section>

    {{-- ═══ FOOTER ═══ --}}
    <footer class="py-8 bg-surface border-t border-border/30">
        <div class="mx-auto max-w-7xl px-4 text-center">
            <a href="{{ url('/') }}" class="text-sm text-foreground/40 hover:text-foreground/60 transition-colors">
                © {{ date('Y') }} SYNTIweb · syntiweb.com
            </a>
        </div>
    </footer>

    {{-- Iconify --}}
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js" defer></script>
<!-- SYNTiA Pública -->
<div id="syntia-widget" style="position:fixed;bottom:24px;right:24px;z-index:9999;font-family:sans-serif;">
  <div id="syntia-box" style="display:none;width:320px;background:#fff;border-radius:16px;box-shadow:0 8px 32px rgba(0,0,0,.15);overflow:hidden;margin-bottom:12px;">
    <div style="background:#4A80E4;padding:14px 16px;display:flex;justify-content:space-between;align-items:center;">
      <span style="color:#fff;font-weight:700;font-size:15px;">SYNTiA</span>
      <span onclick="toggleSyntia()" style="color:#fff;cursor:pointer;font-size:18px;">✕</span>
    </div>
    <div id="syntia-messages" style="height:260px;overflow-y:auto;padding:14px;display:flex;flex-direction:column;gap:10px;">
      <div style="background:#f1f5f9;border-radius:10px;padding:10px;font-size:13px;color:#334155;">
        👋 Hola, soy SYNTiA. ¿Tienes dudas sobre SYNTIweb?
      </div>
    </div>
    <div style="padding:10px;border-top:1px solid #e2e8f0;display:flex;gap:8px;">
      <input id="syntia-input" type="text" placeholder="Escribe tu pregunta..."
        style="flex:1;border:1px solid #e2e8f0;border-radius:8px;padding:8px 10px;font-size:13px;outline:none;"
        onkeydown="if(event.key==='Enter')sendSyntia()">
      <button onclick="sendSyntia()"
        style="background:#4A80E4;color:#fff;border:none;border-radius:8px;padding:8px 14px;cursor:pointer;font-size:13px;">
        →
      </button>
    </div>
  </div>
  <button onclick="toggleSyntia()"
    style="background:#4A80E4;color:#fff;border:none;border-radius:50%;width:52px;height:52px;font-size:22px;cursor:pointer;box-shadow:0 4px 16px rgba(74,128,228,.4);display:flex;align-items:center;justify-content:center;">
    💬
  </button>
</div>

<script>
function toggleSyntia() {
  const box = document.getElementById('syntia-box');
  box.style.display = box.style.display === 'none' ? 'block' : 'none';
}

async function sendSyntia() {
  const input = document.getElementById('syntia-input');
  const msgs  = document.getElementById('syntia-messages');
  const q     = input.value.trim();
  if (!q) return;

  msgs.innerHTML += `<div style="background:#4A80E4;color:#fff;border-radius:10px;padding:10px;font-size:13px;align-self:flex-end;">${q}</div>`;
  msgs.innerHTML += `<div id="typing" style="background:#f1f5f9;border-radius:10px;padding:10px;font-size:13px;color:#94a3b8;">SYNTiA está escribiendo...</div>`;
  input.value = '';
  msgs.scrollTop = msgs.scrollHeight;

  try {
    const res  = await fetch('/api/synti/public-ask', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
      body: JSON.stringify({ question: q })
    });
    const data = await res.json();
    document.getElementById('typing').remove();
    msgs.innerHTML += `<div style="background:#f1f5f9;border-radius:10px;padding:10px;font-size:13px;color:#334155;">${data.answer}</div>`;
  } catch(e) {
    document.getElementById('typing').remove();
    msgs.innerHTML += `<div style="background:#fee2e2;border-radius:10px;padding:10px;font-size:13px;color:#991b1b;">Error al conectar con SYNTiA.</div>`;
  }
  msgs.scrollTop = msgs.scrollHeight;
}
</script>
</body>
</html>
