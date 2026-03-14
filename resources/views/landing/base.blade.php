{{-- ═══════════════════════════════════════════════════════════════════════════════
     SYNTIweb — Layout maestro para landing pages
     Preline 4.1.2 + Tailwind v4
     NO contiene secciones — solo estructura HTML, head, scripts globales
═══════════════════════════════════════════════════════════════════════════════ --}}
<!DOCTYPE html>
<html data-theme="{{ $customization?->theme_slug === 'custom' ? '' : 'theme-'.($themeSlug ?? 'default') }}" lang="es" class="scroll-smooth" style="scroll-padding-top:64px">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $meta['title'] }}</title>
    <meta name="description" content="{{ $meta['description'] }}">
    @if($meta['keywords'])
    <meta name="keywords" content="{{ $meta['keywords'] }}">
    @endif
    <link rel="canonical" href="{{ $meta['canonical'] }}">
    
    <meta property="og:title" content="{{ $meta['og_title'] }}">
    <meta property="og:description" content="{{ $meta['og_description'] }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $meta['canonical'] }}">
    @if($meta['og_image'])
    <meta property="og:image" content="{{ asset('storage/tenants/' . $tenant->id . '/' . $meta['og_image']) }}">
    @endif
    
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    @php
        $customPalette = data_get($tenant->settings, 'engine_settings.visual.custom_palette');
    @endphp
    @if($customPalette && $customization?->theme_slug === 'custom')
    <style>
        :root {
            --primary: {{ $customPalette['primary'] ?? '#2563eb' }};
            --primary-hover: {{ $customPalette['primary'] ?? '#2563eb' }};
            --primary-500: {{ $customPalette['primary'] ?? '#2563eb' }};
            --primary-600: {{ $customPalette['primary'] ?? '#2563eb' }};
            --secondary: {{ $customPalette['secondary'] ?? '#1f2937' }};
            --border: {{ $customPalette['base'] ?? '#e5e7eb' }};
        }
    </style>
    @endif

    {{-- ═══ Schema.org automático según Blueprint ═══ --}}
    @php $schemaType = ($blueprint['schema_type'] ?? null) ?: $tenant->getSchemaType(); @endphp
    @switch($schemaType)
        @case('Restaurant')
            @include('landing.schemas.restaurant', compact('tenant'))
            @break
        @case('Store')
            @include('landing.schemas.store', compact('tenant'))
            @break
        @case('HealthAndBeautyBusiness')
            @include('landing.schemas.health', compact('tenant'))
            @break
        @case('ProfessionalService')
            @include('landing.schemas.professional', compact('tenant'))
            @break
        @default
            @include('landing.schemas.local-business', compact('tenant'))
    @endswitch
</head>

<body class="min-h-screen bg-background text-foreground antialiased transition-colors duration-500">

    <div class="fixed inset-0 z-[9999] opacity-[0.02] pointer-events-none bg-[url('https://grainy-gradients.vercel.app/noise.svg')]"></div>

    @yield('content')

    @if(isset($plan) && $plan->id >= 2 && isset($customization) && $customization->header_message)
        @include('landing.sections.header-top')
    @endif

    @include('landing.sections.floating-panel')

    {{-- ═══ Toast aviso de negocio cerrado (compartido por todos los templates) ═══ --}}
    @if(!($isOpen ?? true))
    <div id="closed-toast" class="fixed top-4 left-1/2 -translate-x-1/2 z-[9998] max-w-sm w-[calc(100%-2rem)] pointer-events-none opacity-0 -translate-y-4 transition-all duration-300" role="alert">
        <div class="bg-red-50 border border-red-200 rounded-2xl shadow-lg px-5 py-4 flex items-start gap-3 pointer-events-auto">
            <span class="shrink-0 mt-0.5 size-5 rounded-full bg-red-500 flex items-center justify-center">
                <span class="iconify tabler--clock-off size-3 text-white"></span>
            </span>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-red-700">Estamos cerrados</p>
                <p class="text-xs text-red-500 mt-0.5 leading-snug">{{ $closedMessage ?? 'Te responderemos durante nuestro horario de atención.' }}</p>
            </div>
            <button onclick="hideClosedToast()" class="shrink-0 p-1 rounded-full hover:bg-red-100 transition-colors cursor-pointer">
                <span class="iconify tabler--x size-4 text-red-400"></span>
            </button>
        </div>
    </div>

    {{-- Modal confirmación cerrado (cuando hay redirectUrl) --}}
    <div id="closed-confirm-modal" class="fixed inset-0 z-[10000] hidden items-center justify-center bg-black/40 p-4">
        <div class="bg-background rounded-2xl shadow-2xl max-w-sm w-full p-6 space-y-5">
            <div class="flex items-start gap-3">
                <div class="size-10 rounded-2xl bg-red-100 flex items-center justify-center text-red-600 shrink-0">
                    <span class="iconify tabler--clock-off size-5"></span>
                </div>
                <div class="flex-1">
                    <p class="text-lg font-black text-red-700">Estamos cerrados</p>
                    <p class="text-sm text-foreground/60">Igual puedes enviar tu mensaje. Te responderemos cuando abramos.</p>
                </div>
            </div>
            <div class="flex gap-3">
                <button id="closed-confirm-send" class="py-3 px-4 rounded-xl font-black transition-colors bg-primary text-primary-foreground hover:bg-primary/90 flex-1 cursor-pointer">Enviar de todas formas</button>
                <button id="closed-confirm-cancel" class="py-3 px-4 rounded-xl font-medium transition-colors text-foreground/80 hover:bg-surface cursor-pointer">Cancelar</button>
            </div>
        </div>
    </div>

    <script>
        (function(){
            var toast = document.getElementById('closed-toast');
            var modal = document.getElementById('closed-confirm-modal');
            var sendBtn = document.getElementById('closed-confirm-send');
            var cancelBtn = document.getElementById('closed-confirm-cancel');
            var pendingUrl = null;
            var pendingCallback = null;
            var cooldown = false;

            window.showClosedToast = function(redirectUrlOrCallback) {
                if (redirectUrlOrCallback) {
                    if (typeof redirectUrlOrCallback === 'function') {
                        pendingCallback = redirectUrlOrCallback;
                        pendingUrl = null;
                    } else {
                        pendingUrl = redirectUrlOrCallback;
                        pendingCallback = null;
                    }
                    modal.style.display = 'flex';
                    modal.classList.remove('hidden');
                    return false;
                }

                if (cooldown || !toast) return;
                cooldown = true;
                toast.style.opacity = '1';
                toast.style.transform = 'translateX(-50%) translateY(0)';
                setTimeout(function(){ hideClosedToast(); }, 5000);
                setTimeout(function(){ cooldown = false; }, 8000);
            };

            sendBtn.addEventListener('click', function() {
                var url = pendingUrl;
                var cb = pendingCallback;
                pendingUrl = null;
                pendingCallback = null;
                modal.style.display = 'none';
                modal.classList.add('hidden');
                if (cb) cb();
                else if (url) window.open(url, '_blank');
            });

            cancelBtn.addEventListener('click', function() {
                pendingUrl = null;
                pendingCallback = null;
                modal.style.display = 'none';
                modal.classList.add('hidden');
            });

            window.hideClosedToast = function() {
                if (!toast) return;
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(-50%) translateY(-1rem)';
            };
            window.__tenantIsOpen = false;
        })();
    </script>
    @else
    <script>window.__tenantIsOpen = true; window.showClosedToast = function(){};</script>
    @endif

    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js" defer></script>
    @stack('scripts')
</body>
</html>