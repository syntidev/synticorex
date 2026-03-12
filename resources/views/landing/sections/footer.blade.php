{{-- ═══════════════════════════════════════════════════════════════════════════════
     SYNTIweb — Footer Section
     Preline 4.1.2 + Tailwind v4 | Nav dinámica + social por plan
     Compatible: studio.blade.php + catalog.blade.php
═══════════════════════════════════════════════════════════════════════════════ --}}
@php
    // ── Nav dinámica (espeja lógica del header) ───────────────────────────────
    $footerNavMap = [
        'products'        => ['label' => 'Productos',   'anchor' => '#products'],
        'services'        => ['label' => 'Servicios',   'anchor' => '#services'],
        'about'           => ['label' => 'Nosotros',    'anchor' => '#about'],
        'contact'         => ['label' => 'Contacto',    'anchor' => '#contact'],
        'testimonials'    => ['label' => 'Testimonios', 'anchor' => '#testimonials'],
        'faq'             => ['label' => 'FAQ',         'anchor' => '#faq'],
        'branches'        => ['label' => 'Sucursales',  'anchor' => '#branches'],
        'payment_methods' => ['label' => 'Pagos',       'anchor' => '#payment_methods'],
    ];
    $footerLinks = [];
    foreach ($customization->getSectionsOrder() as $sec) {
        $k = $sec['name'] ?? '';
        if (($sec['visible'] ?? true)
            && $customization->canAccessSection($k, $tenant->plan_id)
            && isset($footerNavMap[$k])) {
            if (!collect($footerLinks)->contains('anchor', $footerNavMap[$k]['anchor'])) {
                $footerLinks[] = $footerNavMap[$k];
            }
        }
    }

    // ── Redes sociales con límite por plan ───────────────────────────────────
    // OPORTUNIDAD(1)=2 redes, CRECIMIENTO(2)=4 redes, VISIÓN(3)=ilimitadas
    $snLimit = match((int)($tenant->plan_id ?? 1)) {
        1 => 2,
        2 => 4,
        default => 999,
    };
    $rawSn   = $customization->social_networks ?? [];
    $sn      = is_array($rawSn) ? $rawSn : [];
    $snOrder = ['instagram', 'facebook', 'tiktok', 'twitter', 'linkedin', 'youtube'];
    $footerSocials = [];
    foreach ($snOrder as $network) {
        if (count($footerSocials) >= $snLimit) break;
        $handle = trim($sn[$network] ?? '');
        if (!$handle) continue;
        $isUrl = str_starts_with($handle, 'http');
        $h = ltrim($handle, '@');
        $footerSocials[] = [
            'network'    => $network,
            'url'        => match($network) {
                'instagram' => $isUrl ? $handle : 'https://instagram.com/' . $h,
                'facebook'  => $isUrl ? $handle : 'https://facebook.com/' . $h,
                'tiktok'    => $isUrl ? $handle : 'https://tiktok.com/@' . $h,
                'twitter'   => $isUrl ? $handle : 'https://x.com/' . $h,
                'linkedin'  => $isUrl ? $handle : 'https://linkedin.com/company/' . $h,
                'youtube'   => $isUrl ? $handle : 'https://youtube.com/@' . $h,
                default     => '#',
            },
            'icon'       => match($network) {
                'instagram' => 'tabler--brand-instagram',
                'facebook'  => 'tabler--brand-facebook',
                'tiktok'    => 'tabler--brand-tiktok',
                'twitter'   => 'tabler--brand-x',
                'linkedin'  => 'tabler--brand-linkedin',
                'youtube'   => 'tabler--brand-youtube',
                default     => 'tabler--link',
            },
            'hover'      => match($network) {
                'instagram' => 'hover:text-pink-500',
                'facebook'  => 'hover:text-blue-500',
                'youtube'   => 'hover:text-red-500',
                'linkedin'  => 'hover:text-blue-600',
                default     => 'hover:text-foreground',
            },
        ];
    }
@endphp

<footer id="footer" class="relative overflow-hidden bg-footer border-t border-base-200">

    {{-- Líneas decorativas (estilo Preline) --}}
    <div class="pointer-events-none absolute inset-0" aria-hidden="true">
        <svg class="absolute inset-0 h-full w-full" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
            <path d="M -50 60  Q 300 10  700 70  T 1400 40" stroke="currentColor" stroke-width="1" fill="none" class="stroke-black/5 dark:stroke-white/5"/>
            <path d="M -50 100 Q 400 50  800 110 T 1500 80" stroke="currentColor" stroke-width="1" fill="none" class="stroke-black/5 dark:stroke-white/5"/>
        </svg>
    </div>

    <div class="relative mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">

        {{-- ── Fila principal ── --}}
        <div class="flex flex-col gap-6 py-8 sm:flex-row sm:items-center sm:justify-between">

            {{-- Logo + Nombre del negocio --}}
            <a href="#home" class="flex items-center gap-3 shrink-0">
                @if($customization && $customization->logo_filename)
                    <img src="{{ asset('storage/tenants/' . $tenant->id . '/' . $customization->logo_filename) }}"
                         alt="{{ $tenant->business_name }}"
                         class="h-8 w-auto object-contain">
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="32" height="32" aria-hidden="true">
                        <path d="M 30,22 L 78,22 L 78,70 Q 78,78 70,78 L 62,78 L 62,38 L 22,38 L 22,30 Q 22,22 30,22 Z" fill="#1a1a1a"/>
                        <circle cx="38" cy="63" r="14" fill="#4A80E4"/>
                    </svg>
                @endif
                <span class="text-base font-bold tracking-tight text-foreground">{{ $tenant->business_name }}</span>
            </a>

            {{-- Nav dinámica --}}
            @if(count($footerLinks) > 0)
            <nav class="flex flex-wrap items-center gap-x-5 gap-y-2">
                @foreach($footerLinks as $link)
                    <a href="{{ $link['anchor'] }}"
                       class="text-sm font-medium text-muted-foreground-1 hover:text-foreground transition-colors">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>
            @endif

            {{-- Redes sociales --}}
            <div class="flex items-center gap-3">
                {{-- WhatsApp: siempre visible si configurado (no cuenta para el límite) --}}
                @if($tenant->getActiveWhatsapp())
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tenant->getActiveWhatsapp()) }}"
                   target="_blank" rel="noopener noreferrer"
                   aria-label="WhatsApp"
                   class="text-muted-foreground-1 hover:text-success transition-colors">
                    <span class="iconify tabler--brand-whatsapp size-5"></span>
                </a>
                @endif

                @foreach($footerSocials as $sl)
                <a href="{{ $sl['url'] }}"
                   target="_blank" rel="noopener noreferrer"
                   aria-label="{{ ucfirst($sl['network']) }}"
                   class="text-muted-foreground-1 {{ $sl['hover'] }} transition-colors">
                    <span class="iconify {{ $sl['icon'] }} size-5"></span>
                </a>
                @endforeach
            </div>

        </div>

        <div class="flex flex-wrap items-center justify-center gap-x-4 gap-y-2 border-t border-base-200 pt-4 text-xs text-muted-foreground-1">
            <a href="{{ route('marketing.about') }}" class="hover:text-foreground transition-colors">Nosotros</a>
            <span class="opacity-50">•</span>
            <a href="{{ route('marketing.privacy') }}" class="hover:text-foreground transition-colors">Privacidad</a>
            <span class="opacity-50">•</span>
            <a href="{{ route('marketing.terms') }}" class="hover:text-foreground transition-colors">Terminos</a>
        </div>

        {{-- ── Barra de copyright ── --}}
        <div class="flex flex-col items-center justify-between gap-2 border-t border-base-200 py-4 sm:flex-row">
            <p class="text-xs text-muted-foreground-1">
                &copy; {{ date('Y') }} {{ $tenant->business_name }}. Todos los derechos reservados.
            </p>
            <p class="text-xs text-muted-foreground-1">
                Potenciado por
                <a href="https://syntiweb.com" target="_blank" rel="noopener noreferrer"
                   class="font-semibold text-foreground hover:text-primary transition-colors">
                    SYNTIweb
                </a>
            </p>
        </div>

    </div>
</footer>