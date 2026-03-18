{{-- ═══════════════════════════════════════════════════════════════════════════
     SYNTIweb — Footer Storefront (CAT + FOOD)
     Compacto, mobile-first, informal. NO usar en Studio.
     Reglas de plan aplicadas: chips MP, redes sociales, white label.
════════════════════════════════════════════════════════════════════════════ --}}
@php
    // ── Plan ─────────────────────────────────────────────────────────────────
    $sfPlanSlug = (string) ($tenant->plan->slug ?? '');
    $sfPlan1    = in_array($sfPlanSlug, ['cat-basico',    'food-basico'],                       true);
    $sfPlan2    = in_array($sfPlanSlug, ['cat-semestral', 'food-semestral', 'food-crecimiento'], true);
    $sfPlan3    = !$sfPlan1 && !$sfPlan2; // anual / vision / cualquier superior

    // ── Métodos de pago ───────────────────────────────────────────────────────
    $sfPayRaw     = $customization->payment_methods ?? [];
    $sfGlobal     = $sfPayRaw['global']   ?? [];
    $sfCurrency   = $sfPayRaw['currency'] ?? [];

    if ($sfPlan1) {
        // Plan 1: forzado, no configurable
        $sfGlobal   = ['pagoMovil', 'cash'];
        $sfCurrency = [];
    }

    $sfAllMeta = [
        'pagoMovil'  => ['label' => 'Pago Móvil',    'icon' => 'device-mobile'],
        'cash'       => ['label' => 'Efectivo',       'icon' => 'cash'],
        'puntoventa' => ['label' => 'Punto de Venta', 'icon' => 'credit-card'],
        'biopago'    => ['label' => 'Biopago',        'icon' => 'fingerprint'],
        'cashea'     => ['label' => 'Cashea',         'icon' => 'wallet'],
        'krece'      => ['label' => 'Krece',          'icon' => 'trending-up'],
        'wepa'       => ['label' => 'Wepa',           'icon' => 'shopping-cart'],
        'lysto'      => ['label' => 'Lysto',          'icon' => 'calendar-dollar'],
        'chollo'     => ['label' => 'Chollo',         'icon' => 'discount-2'],
        'wally'      => ['label' => 'Wally',          'icon' => 'send-2'],
        'kontigo'    => ['label' => 'Kontigo',        'icon' => 'file-invoice'],
        'zelle'      => ['label' => 'Zelle',          'icon' => 'bolt'],
        'paypal'     => ['label' => 'PayPal',         'icon' => 'brand-paypal'],
        'zinli'      => ['label' => 'Zinli',          'icon' => 'moneybag'],
        'airtm'      => ['label' => 'AirTM',          'icon' => 'exchange'],
        'reserve'    => ['label' => 'Reserve (RSV)',  'icon' => 'shield-dollar'],
        'binancepay' => ['label' => 'Binance Pay',    'icon' => 'currency-bitcoin'],
        'usdt'       => ['label' => 'USDT',           'icon' => 'coin'],
    ];
    $sfCurrMeta = [
        'usd' => ['label' => 'USD', 'icon' => 'currency-dollar'],
        'eur' => ['label' => 'EUR', 'icon' => 'currency-euro'],
    ];

    $sfVisible = array_merge(
        array_filter($sfAllMeta, fn($k) => in_array($k, $sfGlobal),   ARRAY_FILTER_USE_KEY),
        array_filter($sfCurrMeta, fn($k) => in_array($k, $sfCurrency), ARRAY_FILTER_USE_KEY)
    );

    // Límite por plan
    if ($sfPlan1) $sfVisible = array_slice($sfVisible, 0, 2,  true);
    if ($sfPlan2) $sfVisible = array_slice($sfVisible, 0, 5,  true);
    // Plan 3: sin límite

    // ── Redes sociales ────────────────────────────────────────────────────────
    $sfSnLimit  = $sfPlan1 ? 0 : ($sfPlan2 ? 2 : 999);
    $sfRawSn    = is_array($customization->social_networks ?? [])
                    ? ($customization->social_networks ?? [])
                    : [];
    $sfSnOrder  = ['instagram','facebook','tiktok','twitter','youtube','linkedin'];
    $sfSocials  = [];
    foreach ($sfSnOrder as $net) {
        if (count($sfSocials) >= $sfSnLimit) break;
        $h = trim($sfRawSn[$net] ?? '');
        if (!$h) continue;
        $isUrl = str_starts_with($h, 'http');
        $hc    = ltrim($h, '@');
        $sfSocials[] = [
            'url'   => match($net) {
                'instagram' => $isUrl ? $h : 'https://instagram.com/' . $hc,
                'facebook'  => $isUrl ? $h : 'https://facebook.com/' . $hc,
                'tiktok'    => $isUrl ? $h : 'https://tiktok.com/@' . $hc,
                'twitter'   => $isUrl ? $h : 'https://x.com/' . $hc,
                'youtube'   => $isUrl ? $h : 'https://youtube.com/@' . $hc,
                'linkedin'  => $isUrl ? $h : 'https://linkedin.com/company/' . $hc,
                default     => '#',
            },
            'icon'  => match($net) {
                'instagram' => 'brand-instagram',
                'facebook'  => 'brand-facebook',
                'tiktok'    => 'brand-tiktok',
                'twitter'   => 'brand-x',
                'youtube'   => 'brand-youtube',
                'linkedin'  => 'brand-linkedin',
                default     => 'link',
            },
            'hover' => match($net) {
                'instagram' => 'hover:text-pink-500',
                'facebook'  => 'hover:text-blue-500',
                'youtube'   => 'hover:text-red-500',
                default     => 'hover:text-foreground',
            },
            'label' => ucfirst($net),
        ];
    }

    // ── WhatsApp (siempre, no cuenta contra límite) ───────────────────────────
    $sfWa = $tenant->getActiveWhatsapp() ?? null;

    // ── Legal ─────────────────────────────────────────────────────────────────
    $sfLegal = (bool) data_get($customization->content_blocks ?? [], 'legal_links.enabled', false);
@endphp

<footer id="footer" class="bg-footer border-t border-border">
    <div class="mx-auto max-w-[1280px] px-4">

        {{-- ── Fila: Logo + Redes ── --}}
        <div class="flex items-center justify-between gap-3 py-3">

            {{-- Logo + nombre --}}
            <a href="#" class="flex items-center gap-2 shrink-0 min-w-0">
                @if($customization->logo_filename ?? null)
                    <img src="{{ asset('storage/tenants/' . $tenant->id . '/' . $customization->logo_filename) }}"
                         alt="{{ $tenant->business_name }}"
                         class="h-7 w-auto object-contain shrink-0">
                @endif
                <span class="text-sm font-bold tracking-tight text-foreground truncate">
                    {{ $tenant->business_name }}
                </span>
            </a>

            {{-- Redes sociales (Plan 2+) --}}
            @if(!empty($sfSocials) || $sfWa)
            <div class="flex items-center gap-2.5 shrink-0">
                @if($sfWa)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $sfWa) }}"
                   target="_blank" rel="noopener noreferrer"
                   aria-label="WhatsApp"
                   class="text-muted-foreground-1 hover:text-success transition-colors">
                    <span class="iconify tabler--brand-whatsapp size-4.5"></span>
                </a>
                @endif
                @foreach($sfSocials as $sl)
                <a href="{{ $sl['url'] }}" target="_blank" rel="noopener noreferrer"
                   aria-label="{{ $sl['label'] }}"
                   class="text-muted-foreground-1 {{ $sl['hover'] }} transition-colors">
                    <span class="iconify tabler--{{ $sl['icon'] }} size-4.5"></span>
                </a>
                @endforeach
            </div>
            @endif

        </div>

        {{-- ── Chips de Métodos de Pago ── --}}
        @if(!empty($sfVisible))
        <div class="border-t border-border py-2.5">
            <div class="flex flex-wrap items-center justify-center gap-1.5">
                @foreach($sfVisible as $pm)
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full
                             bg-surface text-[11px] font-medium text-muted-foreground-1
                             border border-border whitespace-nowrap">
                    <span class="iconify tabler--{{ $pm['icon'] }} size-3"></span>
                    {{ $pm['label'] }}
                </span>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ── Barra inferior: copyright + legal + marca ── --}}
        <div class="border-t border-border py-2.5 flex flex-wrap items-center justify-center gap-x-3 gap-y-1">

            <p class="text-[11px] text-muted-foreground-1">
                &copy; {{ date('Y') }} {{ $tenant->business_name }}.
            </p>

            @if($sfLegal)
            <span class="text-[11px] text-muted-foreground-1 flex items-center gap-2">
                <a href="{{ route('marketing.privacy') }}" target="_blank" rel="noopener noreferrer"
                   class="hover:text-foreground transition-colors">Privacidad</a>
                <span aria-hidden="true">·</span>
                <a href="{{ route('marketing.terms') }}" target="_blank" rel="noopener noreferrer"
                   class="hover:text-foreground transition-colors">Términos</a>
            </span>
            @endif

            @if(!($tenant->white_label ?? false))
            <p class="text-[11px] text-muted-foreground-1">
                Potenciado por
                <a href="https://syntiweb.com" target="_blank" rel="noopener noreferrer"
                   class="font-semibold text-foreground hover:text-primary transition-colors">SYNTIweb</a>
            </p>
            @endif

        </div>

    </div>
</footer>
