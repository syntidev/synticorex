        <!-- Tab: Diseño -->
        <div id="tab-diseno" class="tab-content">
@php
// activeTheme ya viene desde el controller

// Colores hardcodeados de cada tema FlyonUI (primary, secondary, accent, neutral, base)
$flyonuiThemes = [
    // DEFAULT
    ['slug'=>'light', 'name'=>'Light', 'category'=>'Default', 'colors'=>['#570df8','#f000b8','#37cdbe','#ffffff']],
    ['slug'=>'dark', 'name'=>'Dark', 'category'=>'Default', 'colors'=>['#661ae6','#d926a9','#1fb2a6','#2a303c']],
    // DARK MODES
    ['slug'=>'black', 'name'=>'Black', 'category'=>'Dark Modes', 'font'=>'Geist', 'colors'=>['#ffffff','#ffffff','#ffffff','#000000']],
    ['slug'=>'spotify', 'name'=>'Spotify', 'category'=>'Dark Modes', 'font'=>'Montserrat', 'colors'=>['#1db954','#1ed760','#1db954','#121212']],
    ['slug'=>'valorant', 'name'=>'Valorant', 'category'=>'Dark Modes', 'font'=>'Syne', 'colors'=>['#ff4655','#bd3944','#ff4655','#0f1923']],
    // PROFESSIONAL
    ['slug'=>'claude', 'name'=>'Claude', 'category'=>'Professional', 'font'=>'Lato', 'colors'=>['#da7756','#a0785a','#e8c9a0','#f5f0e8']],
    ['slug'=>'corporate', 'name'=>'Corporate', 'category'=>'Professional', 'font'=>'Inter', 'colors'=>['#4b6bfb','#7b92b2','#67cba0','#ffffff']],
    ['slug'=>'gourmet', 'name'=>'Gourmet', 'category'=>'Professional', 'font'=>'Montserrat', 'colors'=>['#9b2335','#d4a76a','#c8a97e','#fdfaf5']],
    ['slug'=>'luxury', 'name'=>'Luxury', 'category'=>'Professional', 'font'=>'Rubik', 'colors'=>['#ffffff','#a08740','#c5a028','#09090b']],
    // CREATIVE
    ['slug'=>'ghibli', 'name'=>'Ghibli', 'category'=>'Creative', 'font'=>'Amaranth', 'colors'=>['#6b7c5c','#c49a6c','#e8a87c','#faf6f0']],
    ['slug'=>'pastel', 'name'=>'Pastel', 'category'=>'Creative', 'font'=>'Open Sans', 'colors'=>['#d1c1f7','#f7d6c1','#c1f7d6','#ffffff']],
    ['slug'=>'soft', 'name'=>'Soft', 'category'=>'Creative', 'font'=>'Rubik', 'colors'=>['#6b21a8','#db2777','#0891b2','#ffffff']],
    // TECH
    ['slug'=>'mintlify', 'name'=>'Mintlify', 'category'=>'Tech', 'font'=>'Work Sans', 'colors'=>['#0ea474','#7c3aed','#0ea5e9','#ffffff']],
    ['slug'=>'perplexity', 'name'=>'Perplexity', 'category'=>'Tech', 'font'=>'Inter', 'colors'=>['#20b8cd','#1a9ab0','#15808f','#16191d']],
    ['slug'=>'shadcn', 'name'=>'Shadcn', 'category'=>'Tech', 'font'=>'Geist', 'colors'=>['#18181b','#f4f4f5','#18181b','#ffffff']],
    ['slug'=>'slack', 'name'=>'Slack', 'category'=>'Tech', 'font'=>'Lato', 'colors'=>['#4a154b','#1264a3','#ecb22e','#3f0e40']],
    ['slug'=>'vscode', 'name'=>'VS Code', 'category'=>'Tech', 'font'=>'DM Mono', 'colors'=>['#007acc','#6a9955','#569cd6','#1e1e1e']],
    ['slug'=>'perplexity', 'name'=>'Perplexity', 'category'=>'Tech',         'colors'=>['#20b2aa','#5f9ea0','#48d1cc','#708090','#f0f8ff']],
    ['slug'=>'shadcn',     'name'=>'Shadcn',     'category'=>'Tech',         'colors'=>['#18181b','#52525b','#3b82f6','#27272a','#fafafa']],
    ['slug'=>'slack',      'name'=>'Slack',      'category'=>'Tech',         'font'=>'Lato',       'colors'=>['#611f69','#36c5f0','#2eb67d','#1d1c1d','#1a1d21']],
    ['slug'=>'vscode',     'name'=>'VS Code',    'category'=>'Tech',         'font'=>'Fira Code',  'colors'=>['#007acc','#6c9ef8','#4ec9b0','#3c3c3c','#1e1e1e']],
];

// Filter to only the themes available for this tenant's plan (from DB)
$allowedSlugs = $palettes->pluck('slug')->toArray();
$flyonuiThemes = array_values(array_filter($flyonuiThemes, fn($t) => in_array($t['slug'], $allowedSlugs)));
$themesByCategory = collect($flyonuiThemes)->groupBy('category');
@endphp

{{-- ── Temas FlyonUI ─────────────────────────────────── --}}
<div class="card bg-base-100 shadow-sm border border-base-content/10 mb-4">
    <div class="card-header">
        <h2 class="card-title flex items-center gap-2">
            <span class="iconify tabler--palette size-5 text-primary" aria-hidden="true"></span>
            Tema Visual
        </h2>
        <p class="text-xs text-base-content/50 mt-0.5">Elige el tema que mejor represente tu marca</p>
    </div>
    <div class="card-body pt-2">
        <div id="theme-success-message" class="alert alert-success mb-3" style="display:none;">
            <span class="iconify tabler--check size-4"></span>
            <span class="text-sm">Tema actualizado correctamente</span>
        </div>

        <div class="max-h-[350px] overflow-y-auto pr-1 space-y-3">
            @foreach($themesByCategory as $category => $themes)
            <div>
                <h3 class="text-[10px] font-bold text-base-content/40 mb-2 uppercase tracking-wider sticky top-0 bg-base-100 py-1 z-10">
                    {{ $category }}
                </h3>
                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-2">
                    @foreach($themes as $theme)
                    @php
                        $isActive = $currentTheme === $theme['slug'];
                        $bg      = $theme['colors'][3];
                        $isDark  = in_array($theme['slug'], ['dark','black','spotify','valorant','luxury','perplexity','slack','vscode']);
                    @endphp
                    <div class="theme-card cursor-pointer rounded-lg overflow-hidden border-2 transition-all hover:scale-105 {{ $isActive ? 'border-primary ring-2 ring-primary/25' : 'border-base-content/10 hover:border-base-content/25' }}"
                         data-slug="{{ $theme['slug'] }}"
                         onclick="updateTheme('{{ $theme['slug'] }}')"
                         style="background:{{ $bg }}">
                        <div class="flex h-8">
                            @foreach(array_slice($theme['colors'], 0, 4) as $color)
                            <div class="flex-1" style="background:{{ $color }}"></div>
                            @endforeach
                        </div>
                        <div class="px-2 py-1.5 flex items-center justify-between gap-1">
                            <span class="text-[11px] font-semibold truncate" style="color:{{ $isDark ? 'rgba(255,255,255,.9)' : 'rgba(0,0,0,.85)' }}">{{ $theme['name'] }}</span>
                            @if($isActive)<span class="iconify tabler--check size-3.5 text-primary shrink-0"></span>@endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@if($tenant->plan_id === 3)
{{-- ── Paleta Personalizada (Plan VISIÓN) ──────────── --}}
<div class="card bg-base-100 shadow-sm border border-base-content/10 mb-4">
    <div class="card-header">
        <h3 class="card-title flex items-center gap-2">
            <span class="iconify tabler--color-swatch size-5 text-primary" aria-hidden="true"></span>
            Paleta Personalizada
            <span class="badge badge-soft badge-info badge-xs">Plan VISIÓN</span>
        </h3>
    </div>
    <div class="card-body">
        @php
        $customPalette = $tenant->settings['engine_settings']['visual']['custom_palette'] ?? [
            'primary' => '#570DF8', 'secondary' => '#F000B9', 'accent' => '#1DCDBC', 'base' => '#FFFFFF'
        ];
        @endphp
        <div class="grid grid-cols-4 gap-3">
            @foreach(['primary','secondary','accent','base'] as $colorKey)
            <div class="form-control">
                <label class="label pb-1"><span class="label-text text-xs font-medium capitalize">{{ $colorKey }}</span></label>
                <input type="color" id="custom-{{ $colorKey }}" class="w-full h-10 rounded-lg border border-base-content/10 cursor-pointer" value="{{ $customPalette[$colorKey] }}">
            </div>
            @endforeach
        </div>
        <button onclick="applyCustomPalette()" class="btn btn-primary btn-sm w-full gap-2 mt-3">
            <span class="iconify tabler--palette size-4"></span>
            Aplicar Paleta Custom
        </button>
    </div>
</div>
@endif

            {{-- ══════════════════════════════════════════════════════════════
                 SECCIÓN: Orden de Secciones (Drag & Drop)
            ══════════════════════════════════════════════════════════════ --}}
            <div class="card bg-base-100 shadow-sm border border-base-content/10 mb-4">
                <div class="card-header">
                    <h2 class="card-title flex items-center gap-2">
                        <span class="iconify tabler--list-check size-5 text-primary" aria-hidden="true"></span>
                        Orden de Secciones
                    </h2>
                    <p class="text-xs text-base-content/50 mt-0.5">Arrastra para reordenar. Las secciones apagadas no aparecen en tu landing.</p>
                </div>
                <div class="card-body pt-2">

                <div id="sortable-sections" class="grid grid-cols-1 lg:grid-cols-2 gap-2">
                    @php
                        // Get all 9 available sections from the Tenant model (based on plan access)
                        $allSections = [
                            'products'        => ['label' => 'Productos',        'icon' => 'tabler:shopping-cart',      'plan' => 1],
                            'services'        => ['label' => 'Servicios',        'icon' => 'tabler:tool',               'plan' => 1],
                            'contact'         => ['label' => 'Contacto',         'icon' => 'tabler:map-pin',            'plan' => 1],
                            'payment_methods' => ['label' => 'Medios de Pago',   'icon' => 'tabler:credit-card',        'plan' => 1],
                            'cta'             => ['label' => 'Llamado a Acción', 'icon' => 'tabler:send',               'plan' => 1],
                            'about'           => ['label' => 'Acerca de',        'icon' => 'tabler:info-circle',        'plan' => 2],
                            'testimonials'    => ['label' => 'Testimonios',      'icon' => 'tabler:message-star',       'plan' => 2],
                            'faq'             => ['label' => 'FAQ',              'icon' => 'tabler:help-circle',        'plan' => 3],
                            'branches'        => ['label' => 'Sucursales',       'icon' => 'tabler:building-bank',      'plan' => 3],
                        ];

                        $currentOrder = $customization->visual_effects['sections_order'] ?? [];

                        // Ordenar $allSections según $currentOrder
                        $availableSections = [];
                        if (!empty($currentOrder)) {
                            $orderedKeys = collect($currentOrder)->pluck('name')->toArray();
                            foreach ($orderedKeys as $k) {
                                if (isset($allSections[$k])) {
                                    $availableSections[$k] = $allSections[$k];
                                }
                            }
                            // Agregar las que no están en el orden guardado al final
                            foreach ($allSections as $k => $v) {
                                if (!isset($availableSections[$k])) {
                                    $availableSections[$k] = $v;
                                }
                            }
                        } else {
                            $availableSections = $allSections;
                        }
                    @endphp

                    @foreach($availableSections as $key => $section)
                        @php
                            $sectionData  = collect($currentOrder)->firstWhere('name', $key);
                            $isVisible    = $sectionData['visible'] ?? true;
                            $planRequired = $section['plan'];
                            $hasAccess    = $tenant->plan_id >= $planRequired;
                        @endphp

                        <div class="section-item {{ $hasAccess ? '' : 'no-drag opacity-40 pointer-events-none' }}"
                             data-section="{{ $key }}"
                             data-plan="{{ $planRequired }}">
                            <div class="flex items-center gap-2 px-2.5 py-2 rounded-lg
                                        bg-base-200 border border-base-content/10
                                        {{ $hasAccess ? 'cursor-move' : 'cursor-not-allowed' }}
                                        transition-colors hover:border-base-content/20">

                                {{-- Handle / Lock --}}
                                @if($hasAccess)
                                    <span class="drag-handle text-base-content/40 hover:text-base-content/70 cursor-grab select-none flex-shrink-0 active:cursor-grabbing">
                                        <span class="iconify tabler--grip-vertical text-base"></span>
                                    </span>
                                @else
                                    <span class="text-warning flex-shrink-0">
                                        <span class="iconify tabler--lock size-4"></span>
                                    </span>
                                @endif

                                {{-- Icon --}}
                                <span class="text-primary flex-shrink-0">
                                    <iconify-icon icon="{{ $section['icon'] }}" width="18"></iconify-icon>
                                </span>

                                {{-- Label --}}
                                <span class="flex-1 text-sm font-medium text-base-content">
                                    {{ $section['label'] }}
                                    @if(!$hasAccess)
                                        <span class="badge badge-warning badge-soft badge-xs ms-1">
                                            Plan {{ $planRequired == 2 ? 'CRECIMIENTO' : 'VISIÓN' }}
                                        </span>
                                    @endif
                                </span>

                                {{-- Toggle de visibilidad --}}
                                @if($hasAccess)
                                    <input type="checkbox"
                                           class="toggle toggle-primary toggle-sm section-toggle"
                                           id="section-{{ $key }}"
                                           @checked($isVisible)
                                           onchange="toggleSection('{{ $key }}', this.checked)">

                                    {{-- Flechas orden (alternativa al D&D) --}}
                                    <div class="flex flex-col gap-0 flex-shrink-0">
                                        <button type="button"
                                                onclick="moveSection('{{ $key }}', -1)"
                                                class="flex items-center justify-center w-6 h-5 rounded-t bg-base-300 hover:bg-primary hover:text-white text-base-content border border-base-content/20 transition-all"
                                                title="Subir">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
                                        </button>
                                        <button type="button"
                                                onclick="moveSection('{{ $key }}', 1)"
                                                class="flex items-center justify-center w-6 h-5 rounded-b bg-base-300 hover:bg-primary hover:text-white text-base-content border border-base-content/20 border-t-0 transition-all"
                                                title="Bajar">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                </div>
            </div>

            {{-- SortableJS se inicializa al abrir la pestaña Diseño (ver script global al pie del body) --}}

            {{-- ═══════════════════════════════════════════════════════
                 EDITOR TESTIMONIOS (Plan 2+)
            ═══════════════════════════════════════════════════════ --}}
            @if($plan->id >= 2)
            {{-- $savedTestimonials is provided by DashboardController --}}
            <div class="card bg-base-100 shadow-sm border border-base-content/10 mt-4">
                <div class="card-header flex items-center justify-between gap-3 flex-wrap">
                    <div>
                        <h3 class="card-title flex items-center gap-2">
                            <span class="iconify tabler--message-star size-5 text-primary"></span>
                            Testimonios de Clientes
                            <span class="badge badge-soft badge-primary badge-xs ms-1">Plan CRECIMIENTO+</span>
                        </h3>
                        <p class="text-base-content/50 text-xs mt-0.5">Agrega, edita y elimina los testimonios que desees.</p>
                    </div>
                </div>
                <div class="card-body pt-2">
                    <div id="testimonials-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($savedTestimonials as $ti => $testim)
                    @php $hasContent = !empty($testim['name']) || !empty($testim['text']); @endphp
                    <div class="rounded-lg border p-3 transition-all"
                         data-testimonial-index="{{ $ti }}"
                        {{ $hasContent ? 'class="border-primary/20 bg-primary/5"' : 'class="border-base-content/10 bg-base-200/30"' }}>
                        <div class="flex items-start justify-between mb-2">
                            <span class="text-[10px] font-bold text-base-content/40 uppercase tracking-wider">#{{ $ti + 1 }}</span>
                            <span class="text-sm text-yellow-500">{{ str_repeat('★', $testim['rating'] ?? 5) }}</span>
                        </div>
                        <h4 class="text-sm font-semibold text-base-content line-clamp-1">{{ $testim['name'] ?? '(vacío)' }}</h4>
                        <p class="text-xs text-base-content/50 line-clamp-1">{{ $testim['title'] ?? '(sin cargo)' }}</p>
                        <p class="text-xs text-base-content/60 line-clamp-2 mt-1">{{ $testim['text'] ?? '(vacío)' }}</p>
                        
                        <div class="flex gap-2 mt-3">
                            <button type="button" class="btn btn-primary btn-sm btn-square"
                                    onclick="editTestimonial({{ $ti }}, '{{ addslashes($testim['name'] ?? '') }}', '{{ addslashes($testim['title'] ?? '') }}', '{{ addslashes($testim['text'] ?? '') }}', {{ $testim['rating'] ?? 5 }})"
                                    title="Editar">
                                <span class="iconify tabler--pencil size-4" aria-hidden="true"></span>
                            </button>
                            <button type="button" class="btn btn-error btn-sm btn-square"
                                    onclick="deleteTestimonial({{ $ti }})"
                                    title="Eliminar">
                                <span class="iconify tabler--trash size-4" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>
                    @endforeach
                    </div>
                    <div class="flex gap-2 mt-3">
                        <button type="button" onclick="addTestimonial()" class="btn btn-primary flex-1 gap-2">
                            <span class="iconify tabler--plus size-4"></span>
                            Agregar Testimonio
                        </button>
                        <button type="button" onclick="saveTestimonials()" class="btn btn-primary flex-1 gap-2">
                            <span class="iconify tabler--device-floppy size-4"></span>
                            Guardar Testimonios
                        </button>
                    </div>
                </div>
            </div>
            @endif

            {{-- ═══════════════════════════════════════════════════════
                 EDITOR FAQ (Plan 3)
            ═══════════════════════════════════════════════════════ --}}
            @if($plan->id >= 3)
            @php
                $savedFaq = data_get($tenant->settings, 'business_info.faq', []);
            @endphp
            <div class="card bg-base-100 shadow-sm border border-base-content/10 mt-4">
                <div class="card-header flex items-center justify-between gap-3 flex-wrap">
                    <div>
                        <h3 class="card-title flex items-center gap-2">
                            <span class="iconify tabler--help-circle size-5 text-primary"></span>
                            Preguntas Frecuentes (FAQ)
                            <span class="badge badge-soft badge-secondary badge-xs ms-1">Plan VISIÓN</span>
                        </h3>
                        <p class="text-base-content/50 text-xs mt-0.5">Agrega, edita y elimina las preguntas frecuentes que desees.</p>
                    </div>
                </div>
                <div class="card-body pt-2">
                    <div id="faq-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($savedFaq as $fi => $fitem)
                        @php $hasFaqContent = !empty($fitem['question']) || !empty($fitem['answer']); @endphp
                        <div class="rounded-lg border p-3 transition-all"
                             data-faq-index="{{ $fi }}"
                            {{ $hasFaqContent ? 'style="border-color: var(--fallback-sc, oklch(var(--sc)/var(--tw-border-opacity)))" (class="border-secondary/20 bg-secondary/5")' : 'class="border-base-content/10 bg-base-200/30"' }}>
                            <span class="text-[10px] font-bold text-base-content/40 uppercase tracking-wider">#{{ $fi + 1 }}</span>
                            <h4 class="text-sm font-semibold text-base-content mt-1 line-clamp-2">{{ $fitem['question'] ?? '(vacío)' }}</h4>
                            <p class="text-xs text-base-content/50 mt-1 line-clamp-2">{{ $fitem['answer'] ?? '(vacío)' }}</p>
                            
                            <div class="flex gap-2 mt-3">
                                <button type="button" class="btn btn-secondary btn-sm btn-square"
                                        onclick="editFaq({{ $fi }}, '{{ addslashes($fitem['question'] ?? '') }}', '{{ addslashes($fitem['answer'] ?? '') }}')"
                                        title="Editar">
                                    <span class="iconify tabler--pencil size-4" aria-hidden="true"></span>
                                </button>
                                <button type="button" class="btn btn-error btn-sm btn-square"
                                        onclick="deleteFaq({{ $fi }})"
                                        title="Eliminar">
                                    <span class="iconify tabler--trash size-4" aria-hidden="true"></span>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="flex gap-2 mt-3">
                        <button type="button" onclick="addFaq()" class="btn btn-secondary flex-1 gap-2">
                            <span class="iconify tabler--plus size-4"></span>
                            Agregar Pregunta
                        </button>
                        <button type="button" onclick="saveFaq()" class="btn btn-secondary flex-1 gap-2">
                            <span class="iconify tabler--device-floppy size-4"></span>
                            Guardar FAQ
                        </button>
                    </div>
                </div>
            </div>
            @endif

            @if($plan->id === 1)
            <div class="alert alert-info mt-4 flex items-center justify-between gap-4 flex-wrap">
                <div class="flex items-center gap-3">
                    <span class="iconify tabler--sparkles size-5 shrink-0" aria-hidden="true"></span>
                    <div>
                        <p class="font-semibold text-sm">Desbloquea más personalización</p>
                        <p class="text-xs opacity-70">Header Top + Sección Acerca de disponibles desde el Plan CRECIMIENTO</p>
                    </div>
                </div>
                <a href="https://syntiweb.com/planes" target="_blank" rel="noopener noreferrer"
                   class="btn btn-primary btn-sm shrink-0">Ver Planes</a>
            </div>
            @endif

        </div>

