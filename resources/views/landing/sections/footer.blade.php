{{-- ═══════════════════════════════════════════════════════════════════════════════
     SYNTIweb — Footer Section
     Preline 4.1.2 + Tailwind v4 | Nav dinámica + social por plan
     Compatible: studio.blade.php + catalog.blade.php
═══════════════════════════════════════════════════════════════════════════════ --}}
@php
    $blueprint = (string) ($blueprint ?? $tenant->blueprint ?? 'studio');

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
    $allowedFooterSections = match ($blueprint) {
        'cat' => ['products'],
        'food' => ['products', 'payment_methods'],
        default => array_keys($footerNavMap),
    };
    $footerCategoryTags = collect();
    if ($blueprint === 'cat' && isset($categories)) {
        $footerCategoryTags = collect($categories)
            ->filter(fn ($category) => !empty($category->id) && !empty($category->name))
            ->values();
    }
    $footerLinks = [];
    foreach ($customization->getSectionsOrder() as $sec) {
        $k = $sec['name'] ?? '';
        if (($sec['visible'] ?? true)
            && in_array($k, $allowedFooterSections, true)
            && $customization->canAccessSection($k, $tenant->plan_id)
            && isset($footerNavMap[$k])) {
            if (!collect($footerLinks)->contains('anchor', $footerNavMap[$k]['anchor'])) {
                $footerLinks[] = $footerNavMap[$k];
            }
        }
    }

    // ── Redes sociales con límite por plan ───────────────────────────────────
    $planSlug = (string) ($tenant->plan->slug ?? 'studio-oportunidad');
    $snLimit = match (true) {
        in_array($planSlug, ['studio-oportunidad', 'food-basico', 'cat-basico'], true) => 2,
        in_array($planSlug, ['studio-crecimiento', 'food-semestral', 'cat-semestral'], true) => 4,
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

    $showLegalLinks = (bool) data_get($customization->content_blocks ?? [], 'legal_links.enabled', false);
@endphp

<footer id="footer" class="relative overflow-hidden bg-footer border-t border-border">

    {{-- Líneas decorativas (estilo Preline) --}}
    <div class="pointer-events-none absolute inset-0" aria-hidden="true">
        <svg class="absolute inset-0 h-full w-full" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
            <path d="M -50 60  Q 300 10  700 70  T 1400 40" stroke="currentColor" stroke-width="1" fill="none" class="stroke-black/5 dark:stroke-white/5"/>
            <path d="M -50 100 Q 400 50  800 110 T 1500 80" stroke="currentColor" stroke-width="1" fill="none" class="stroke-black/5 dark:stroke-white/5"/>
        </svg>
    </div>

    <div class="relative mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">

        {{-- ── Fila principal ── --}}
        <div class="flex flex-col gap-4 py-5 sm:flex-row sm:items-center sm:justify-between">

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

            {{-- Nav dinámica / categorías CAT --}}
            @if($blueprint === 'cat' && $footerCategoryTags->isNotEmpty())
            <div class="flex flex-wrap items-center justify-center gap-2 sm:flex-1 sm:px-6">
                <button type="button"
                        onclick="window.location.hash='productos'; if (window.filterCategory) window.filterCategory('all');"
                        class="inline-flex min-h-11 items-center rounded-full border border-border px-4 py-2 text-sm font-medium text-muted-foreground-1 transition-colors hover:border-foreground/20 hover:text-foreground cursor-pointer">
                    Todos
                </button>
                @foreach($footerCategoryTags as $category)
                <button type="button"
                        onclick="window.location.hash='productos'; if (window.filterCategory) window.filterCategory('{{ $category->id }}');"
                        class="inline-flex min-h-11 items-center rounded-full border border-border px-4 py-2 text-sm font-medium text-muted-foreground-1 transition-colors hover:border-foreground/20 hover:text-foreground cursor-pointer">
                    {{ $category->name }}
                </button>
                @endforeach
            </div>
            @elseif(count($footerLinks) > 0)
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

        {{-- ── Métodos de pago CAT y Food ── --}}
        @if(in_array($blueprint ?? '', ['cat', 'food']) && !empty($visiblePay))
        <div class="border-t border-border py-4">
            <p class="text-[10px] font-black uppercase tracking-widest text-muted-foreground-1 mb-3 text-center">Métodos de pago</p>
            <div class="flex flex-wrap items-center justify-center gap-2">
                @foreach($visiblePay as $key => $pm)
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-surface text-xs font-medium text-muted-foreground-1 border border-border">
                    <span class="iconify tabler--{{ $pm['icon'] }} size-3.5"></span>
                    {{ $pm['label'] }}
                </span>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ── Barra de copyright ── --}}
        <div class="flex flex-col items-center justify-between gap-2 border-t border-border py-3 sm:flex-row">
            <p class="text-xs text-muted-foreground-1">
                &copy; {{ date('Y') }} {{ $tenant->business_name }}. Todos los derechos reservados.
            </p>
            @if($showLegalLinks)
            <p class="text-xs text-muted-foreground-1 flex items-center gap-2">
                <a href="{{ route('marketing.privacy') }}" target="_blank" rel="noopener noreferrer" class="hover:text-foreground transition-colors">Privacidad</a>
                <span aria-hidden="true">•</span>
                <a href="{{ route('marketing.terms') }}" target="_blank" rel="noopener noreferrer" class="hover:text-foreground transition-colors">Términos</a>
            </p>
            @endif
            @if(!($tenant->white_label ?? false))
            <p class="text-xs text-muted-foreground-1">
                Potenciado por
                <a href="https://syntiweb.com" target="_blank" rel="noopener noreferrer"
                   class="font-semibold text-foreground hover:text-primary transition-colors">
                    SYNTIweb
                </a>
            </p>
            @endif
        </div>

    </div>
</footer>
