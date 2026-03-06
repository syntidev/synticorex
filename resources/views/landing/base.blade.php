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
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js" defer></script>
    @stack('scripts')
</body>
</html>