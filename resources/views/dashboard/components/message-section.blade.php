        <!-- Tab: Tu Mensaje -->
        <div id="tab-mensaje" class="tab-content">
            <div class="p-6">
@php
    // ── Header Top data ──
    $headerTop = data_get($tenant->settings, 'engine_settings.header_top', []);
    $headerTopEnabled = $headerTop['enabled'] ?? false;
    $headerTopText = $headerTop['text'] ?? '';

    // ── CTA data ──
    $ctaTitle   = $customization->cta_title ?? '';
    $ctaSub     = $customization->cta_subtitle ?? '';
    $ctaBtnText = $customization->cta_button_text ?? '';
    $ctaBtnLink = $customization->cta_button_link ?? '';

    // ── Branches data ──
    $branchesEnabled    = data_get($tenant->settings, 'engine_settings.branches.enabled', false);
    $maxBranches        = 3;
    $currentBranchCount = $branches->count();
    $branchGridClass    = match($currentBranchCount) {
        0, 1    => 'grid-cols-1',
        2       => 'grid-cols-1 sm:grid-cols-2',
        default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
    };
@endphp

{{-- ════════════════════════════════════════════════════════════
     1. ORDEN DE SECCIONES (Drag & Drop)
════════════════════════════════════════════════════════════ --}}
<div class="bg-surface rounded-xl shadow-sm border border-border mb-6">
    <div class="px-5 pt-5 pb-3">
        <div class="flex items-center gap-3">
            <div class="size-9 rounded-lg bg-primary/10 flex items-center justify-center">
                <span class="iconify tabler--list-check size-5 text-primary" aria-hidden="true"></span>
            </div>
            <div>
                <h2 class="text-base font-bold text-foreground">Orden de Secciones</h2>
                <p class="text-xs text-muted-foreground-1">Arrastra para reordenar. Las secciones apagadas no aparecen en tu landing.</p>
            </div>
        </div>
    </div>
    <div class="px-5 pb-5 pt-1">
        <div id="sortable-sections" class="grid grid-cols-1 lg:grid-cols-2 gap-2">
            @php
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

                $availableSections = [];
                if (!empty($currentOrder)) {
                    $orderedKeys = collect($currentOrder)->pluck('name')->toArray();
                    foreach ($orderedKeys as $k) {
                        if (isset($allSections[$k])) {
                            $availableSections[$k] = $allSections[$k];
                        }
                    }
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
                                bg-muted border border-border
                                {{ $hasAccess ? 'cursor-move' : 'cursor-not-allowed' }}
                                transition-colors hover:border-border">

                        @if($hasAccess)
                            <span class="drag-handle select-none flex-shrink-0 active:cursor-grabbing"
                                 style="cursor:grab;color:rgba(148,163,184,0.6)" title="Arrastrar para reordenar">
                                <span class="iconify tabler--grip-vertical size-4"></span>
                            </span>
                        @else
                            <span class="text-warning flex-shrink-0">
                                <span class="iconify tabler--lock size-4"></span>
                            </span>
                        @endif

                        <span class="text-primary flex-shrink-0">
                            <iconify-icon icon="{{ $section['icon'] }}" width="18"></iconify-icon>
                        </span>

                        <span class="flex-1 text-sm font-medium text-foreground">
                            {{ $section['label'] }}
                            @if(!$hasAccess)
                                <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700 ms-1">
                                    Plan {{ $planRequired == 2 ? 'CRECIMIENTO' : 'VISIÓN' }}
                                </span>
                            @endif
                        </span>

                        @if($hasAccess)
                            <input type="checkbox"
                                   class="relative w-[35px] h-[20px] bg-gray-200 checked:bg-primary border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 appearance-none focus:ring-primary-focus focus:ring-2 focus:ring-offset-2 before:inline-block before:size-[16px] before:bg-white before:rounded-full before:transform before:translate-x-0 checked:before:translate-x-full before:transition before:ease-in-out before:duration-200 before:shadow-sm section-toggle"
                                   id="section-{{ $key }}"
                                   @checked($isVisible)
                                   onchange="toggleSection('{{ $key }}', this.checked)">

                            <div class="flex flex-col [&>*]:rounded-none [&>*:first-child]:rounded-t-lg [&>*:last-child]:rounded-b-lg flex-shrink-0">
                                <button type="button"
                                        onclick="moveSection('{{ $key }}', -1)"
                                        class="p-1 text-gray-500 hover:bg-blue-600 hover:text-white rounded transition-colors border border-gray-200"
                                        title="Subir">
                                    <span class="iconify tabler--chevron-up size-3.5" aria-hidden="true"></span>
                                </button>
                                <button type="button"
                                        onclick="moveSection('{{ $key }}', 1)"
                                        class="p-1 text-gray-500 hover:bg-blue-600 hover:text-white rounded transition-colors border border-gray-200"
                                        title="Bajar">
                                    <span class="iconify tabler--chevron-down size-3.5" aria-hidden="true"></span>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════
     2. HEADER TOP (Plan 2+)
════════════════════════════════════════════════════════════ --}}
@if($plan->id >= 2)
<div class="bg-surface rounded-xl shadow-sm border border-border mb-6">
    <div class="px-5 pt-5 pb-3">
        <div class="flex items-center gap-3">
            <div class="size-9 rounded-lg bg-primary/10 flex items-center justify-center">
                <span class="iconify tabler--layout-navbar size-5 text-primary" aria-hidden="true"></span>
            </div>
            <div>
                <h2 class="text-base font-bold text-foreground">Header Top</h2>
                <p class="text-xs text-muted-foreground-1">Barra superior con mensaje destacado
                    <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-blue-100 text-blue-700 ms-1">CRECIMIENTO+</span>
                </p>
            </div>
        </div>
    </div>
    <div class="px-5 pb-5 pt-1">
        <div class="flex items-center gap-3 mb-3">
            <label class="text-xs font-medium text-muted-foreground-1">Visible:</label>
            <input type="checkbox" id="header-top-toggle"
                   class="relative w-[35px] h-[20px] bg-gray-200 checked:bg-primary border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 appearance-none focus:ring-primary-focus focus:ring-2 focus:ring-offset-2 before:inline-block before:size-[16px] before:bg-white before:rounded-full before:transform before:translate-x-0 checked:before:translate-x-full before:transition before:ease-in-out before:duration-200 before:shadow-sm"
                   {{ $headerTopEnabled ? 'checked' : '' }}>
        </div>
        <div class="flex gap-2">
            <input type="text" id="header-top-text"
                   class="py-1.5 sm:py-2 px-3 block flex-1 bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                   placeholder="Ej: Envío gratis en pedidos mayores a 50 REF"
                   value="{{ $headerTopText }}"
                   maxlength="120">
            <button type="button" onclick="saveHeaderTop()" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-primary border border-primary-line text-primary-foreground hover:bg-primary-hover focus:outline-hidden focus:bg-primary-focus disabled:opacity-50 disabled:pointer-events-none">
                <span class="iconify tabler--device-floppy size-4"></span>
                Guardar
            </button>
        </div>
    </div>
</div>
@endif

{{-- ════════════════════════════════════════════════════════════
     3. TESTIMONIOS (Plan 2+)
════════════════════════════════════════════════════════════ --}}
@if($plan->id >= 2)
<div class="bg-surface rounded-xl shadow-sm border border-border mb-6">
    <div class="px-5 pt-5 pb-3 flex items-center justify-between gap-3 flex-wrap">
        <div class="flex items-center gap-3">
            <div class="size-9 rounded-lg bg-primary/10 flex items-center justify-center">
                <span class="iconify tabler--message-star size-5 text-primary" aria-hidden="true"></span>
            </div>
            <div>
                <h2 class="text-base font-bold text-foreground">Testimonios de Clientes</h2>
                <p class="text-xs text-muted-foreground-1">{{ count($savedTestimonials) }} testimonios
                    <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-blue-100 text-blue-700 ms-1">CRECIMIENTO+</span>
                </p>
            </div>
        </div>
    </div>
    <div class="px-5 pb-5 pt-1">
        <div id="testimonials-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($savedTestimonials as $ti => $testim)
            @php $hasContent = !empty($testim['name']) || !empty($testim['text']); @endphp
            <div class="rounded-lg border p-3 transition-all {{ $hasContent ? 'border-primary/20 bg-primary/5' : 'border-border bg-muted/30' }}"
                 data-testimonial-index="{{ $ti }}">
                <div class="flex items-start justify-between mb-2">
                    <span class="text-[10px] font-bold text-muted-foreground-1 uppercase tracking-wider">#{{ $ti + 1 }}</span>
                    <span class="text-sm text-yellow-500">{{ str_repeat('★', $testim['rating'] ?? 5) }}</span>
                </div>
                <h4 class="text-sm font-semibold text-foreground line-clamp-1">{{ $testim['name'] ?? '(vacío)' }}</h4>
                <p class="text-xs text-muted-foreground-1 line-clamp-1">{{ $testim['title'] ?? '(sin cargo)' }}</p>
                <p class="text-xs text-muted-foreground-1 line-clamp-2 mt-1">{{ $testim['text'] ?? '(vacío)' }}</p>
                
                <div class="flex gap-2 mt-3">
                    <button type="button" class="p-1.5 text-sm bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg transition-colors"
                            onclick="editTestimonial({{ $ti }}, '{{ addslashes($testim['name'] ?? '') }}', '{{ addslashes($testim['title'] ?? '') }}', '{{ addslashes($testim['text'] ?? '') }}', {{ $testim['rating'] ?? 5 }})"
                            title="Editar">
                        <span class="iconify tabler--pencil size-4" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="p-1.5 text-sm bg-red-600 text-white hover:bg-red-700 rounded-lg transition-colors"
                            onclick="deleteTestimonial({{ $ti }})"
                            title="Eliminar">
                        <span class="iconify tabler--trash size-4" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
        <div class="flex gap-2 mt-3">
            <button type="button" onclick="addTestimonial()" class="inline-flex items-center py-2 px-4 rounded-lg font-medium transition-colors bg-primary text-primary-foreground hover:bg-primary-hover flex-1 gap-2">
                <span class="iconify tabler--plus size-4"></span>
                Agregar Testimonio
            </button>
            <button type="button" onclick="saveTestimonials()" class="inline-flex items-center py-2 px-4 rounded-lg font-medium transition-colors bg-primary text-primary-foreground hover:bg-primary-hover flex-1 gap-2">
                <span class="iconify tabler--device-floppy size-4"></span>
                Guardar Testimonios
            </button>
        </div>
    </div>
</div>
@endif

{{-- ════════════════════════════════════════════════════════════
     4. FAQ (Plan 3)
════════════════════════════════════════════════════════════ --}}
@if($plan->id >= 3)
<div class="bg-surface rounded-xl shadow-sm border border-border mb-6">
    <div class="px-5 pt-5 pb-3 flex items-center justify-between gap-3 flex-wrap">
        <div class="flex items-center gap-3">
            <div class="size-9 rounded-lg bg-secondary/10 flex items-center justify-center">
                <span class="iconify tabler--help-circle size-5 text-secondary" aria-hidden="true"></span>
            </div>
            <div>
                <h2 class="text-base font-bold text-foreground">Preguntas Frecuentes (FAQ)</h2>
                <p class="text-xs text-muted-foreground-1">{{ count($savedFaq) }} preguntas
                    <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-gray-100 text-gray-700 ms-1">VISIÓN</span>
                </p>
            </div>
        </div>
    </div>
    <div class="px-5 pb-5 pt-1">
        <div id="faq-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($savedFaq as $fi => $fitem)
            @php $hasFaqContent = !empty($fitem['question']) || !empty($fitem['answer']); @endphp
            <div class="rounded-lg border p-3 transition-all {{ $hasFaqContent ? 'border-secondary/20 bg-secondary/5' : 'border-border bg-muted/30' }}"
                 data-faq-index="{{ $fi }}">
                <span class="text-[10px] font-bold text-muted-foreground-1 uppercase tracking-wider">#{{ $fi + 1 }}</span>
                <h4 class="text-sm font-semibold text-foreground mt-1 line-clamp-2">{{ $fitem['question'] ?? '(vacío)' }}</h4>
                <p class="text-xs text-muted-foreground-1 mt-1 line-clamp-2">{{ $fitem['answer'] ?? '(vacío)' }}</p>
                
                <div class="flex gap-2 mt-3">
                    <button type="button" class="p-1.5 text-sm bg-muted text-foreground hover:bg-muted-hover rounded-lg transition-colors"
                            onclick="editFaq({{ $fi }}, '{{ addslashes($fitem['question'] ?? '') }}', '{{ addslashes($fitem['answer'] ?? '') }}')"
                            title="Editar">
                        <span class="iconify tabler--pencil size-4" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="p-1.5 text-sm bg-red-100 text-red-700 hover:bg-red-200 rounded-lg transition-colors"
                            onclick="deleteFaq({{ $fi }})"
                            title="Eliminar">
                        <span class="iconify tabler--trash size-4" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
        <div class="flex gap-2 mt-3">
            <button type="button" onclick="addFaq()" class="inline-flex items-center py-2 px-4 rounded-lg font-medium transition-colors bg-muted text-foreground hover:bg-muted-hover flex-1 gap-2">
                <span class="iconify tabler--plus size-4"></span>
                Agregar Pregunta
            </button>
            <button type="button" onclick="saveFaq()" class="inline-flex items-center py-2 px-4 rounded-lg font-medium transition-colors bg-muted text-foreground hover:bg-muted-hover flex-1 gap-2">
                <span class="iconify tabler--device-floppy size-4"></span>
                Guardar FAQ
            </button>
        </div>
    </div>
</div>
@endif

{{-- ════════════════════════════════════════════════════════════
     5. CTA ESPECIAL (Plan 3)
════════════════════════════════════════════════════════════ --}}
@if($plan->id >= 3)
<div class="bg-surface rounded-xl shadow-sm border border-border mb-6">
    <div class="px-5 pt-5 pb-3">
        <div class="flex items-center gap-3">
            <div class="size-9 rounded-lg bg-accent/10 flex items-center justify-center">
                <span class="iconify tabler--speakerphone size-5 text-accent" aria-hidden="true"></span>
            </div>
            <div>
                <h2 class="text-base font-bold text-foreground">CTA Especial</h2>
                <p class="text-xs text-muted-foreground-1">Sección llamativa con botón personalizado
                    <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-purple-100 text-purple-700 ms-1">VISIÓN</span>
                </p>
            </div>
        </div>
    </div>
    <div class="px-5 pb-5 pt-1">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label class="text-xs font-medium text-muted-foreground-1 mb-1 block">Título</label>
                <input type="text" id="cta-title"
                       class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                       placeholder="¡Contacta con nosotros!"
                       value="{{ $ctaTitle }}"
                       maxlength="100">
            </div>
            <div>
                <label class="text-xs font-medium text-muted-foreground-1 mb-1 block">Subtítulo</label>
                <input type="text" id="cta-subtitle"
                       class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                       placeholder="Estamos listos para atenderte"
                       value="{{ $ctaSub }}"
                       maxlength="200">
            </div>
            <div>
                <label class="text-xs font-medium text-muted-foreground-1 mb-1 block">Texto del Botón</label>
                <input type="text" id="cta-btn-text"
                       class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                       placeholder="Ej: Pedir ahora"
                       value="{{ $ctaBtnText }}"
                       maxlength="50">
            </div>
            <div>
                <label class="text-xs font-medium text-muted-foreground-1 mb-1 block">Enlace del Botón</label>
                <input type="url" id="cta-btn-link"
                       class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                       placeholder="https://..."
                       value="{{ $ctaBtnLink }}">
            </div>
        </div>
        <button type="button" onclick="saveCtaConfig()" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-primary border border-primary-line text-primary-foreground hover:bg-primary-hover focus:outline-hidden focus:bg-primary-focus disabled:opacity-50 disabled:pointer-events-none mt-3">
            <span class="iconify tabler--device-floppy size-4"></span>
            Guardar CTA
        </button>
    </div>
</div>
@endif

{{-- ════════════════════════════════════════════════════════════
     6. SUCURSALES (Plan 3)
════════════════════════════════════════════════════════════ --}}
@if($plan->id >= 3)
<div class="bg-surface rounded-xl shadow-sm border border-border mb-6">
    <div class="px-5 pt-5 pb-3 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <div class="size-9 rounded-lg bg-primary/10 flex items-center justify-center">
                <span class="iconify tabler--map-pin size-5 text-primary" aria-hidden="true"></span>
            </div>
            <div>
                <h2 class="text-base font-bold text-foreground">Sucursales</h2>
                <p class="text-xs text-muted-foreground-1">{{ $currentBranchCount }} de {{ $maxBranches }} sucursales
                    <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-gray-100 text-gray-700 ms-1">VISIÓN</span>
                </p>
            </div>
        </div>
        <input type="checkbox" id="branches-toggle"
               class="relative w-[35px] h-[20px] bg-gray-200 checked:bg-green-500 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 appearance-none focus:ring-green-500 focus:ring-2 focus:ring-offset-2 before:inline-block before:size-[16px] before:bg-white before:rounded-full before:transform before:translate-x-0 checked:before:translate-x-full before:transition before:ease-in-out before:duration-200 before:shadow-sm"
               {{ $branchesEnabled ? 'checked' : '' }}
               onchange="toggleBranchesSection()">
    </div>
    <div class="px-5 pb-5 pt-1">
        <div id="branches-status" class="flex p-4 rounded-lg border gap-3 {{ $branchesEnabled ? 'bg-green-50 border-green-200 text-green-800' : 'bg-blue-50 border-blue-200 text-blue-800' }} mb-3">
            <span class="iconify {{ $branchesEnabled ? 'tabler--check' : 'tabler--pause' }} size-4" aria-hidden="true"></span>
            <p id="branches-status-text" class="text-sm">
                {{ $branchesEnabled ? 'Sección visible en tu landing' : 'Sección oculta en tu landing' }}
            </p>
        </div>
        <div id="branches-content" {{ $branchesEnabled ? '' : 'style="display:none"' }}>
            <div id="branches-list" class="grid {{ $branchGridClass }} gap-3">
                @foreach($branches as $branch)
                <div class="rounded-lg border border-border bg-muted/30 p-4 transition-all hover:border-primary/30 branch-card" id="branch-card-{{ $branch->id }}">
                    <div class="flex items-start gap-3 mb-3">
                        <div class="size-10 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                            <span class="iconify tabler--map-pin size-5 text-primary" aria-hidden="true"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="branch-name text-sm font-semibold text-foreground truncate">{{ $branch->name }}</h3>
                            <p class="branch-address text-xs text-muted-foreground-1 line-clamp-2 mt-0.5">{{ $branch->address }}</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="button"
                                class="p-1.5 text-sm bg-blue-600 text-white hover:bg-blue-700 rounded-lg transition-colors"
                                onclick="editBranch({{ $branch->id }}, '{{ addslashes($branch->name) }}', '{{ addslashes($branch->address) }}')"
                                title="Editar">
                            <span class="iconify tabler--pencil size-5" aria-hidden="true"></span>
                        </button>
                        <button type="button"
                                class="p-1.5 text-sm bg-red-600 text-white hover:bg-red-700 rounded-lg transition-colors"
                                onclick="deleteBranch({{ $branch->id }})"
                                title="Eliminar">
                            <span class="iconify tabler--trash size-5" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
                @endforeach
                @if($currentBranchCount < $maxBranches)
                <div class="rounded-lg border border-border bg-muted/30 p-4 flex items-center justify-center transition-all hover:border-primary/30 cursor-pointer" onclick="openBranchModal()">
                    <div class="text-center">
                        <div class="size-10 rounded-lg bg-primary/10 flex items-center justify-center mx-auto mb-2">
                            <span class="iconify tabler--plus size-5 text-primary" aria-hidden="true"></span>
                        </div>
                        <p class="text-sm font-semibold text-foreground">Agregar Sucursal</p>
                        <p class="text-xs text-muted-foreground-1">{{ $currentBranchCount }} de {{ $maxBranches }} usadas</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

{{-- ── Upsell Plan 1 ──────────────────────────────────── --}}
@if($plan->id === 1)
<div class="flex p-4 rounded-lg border bg-blue-50 border-blue-200 text-blue-800 items-center justify-between gap-4 flex-wrap">
    <div class="flex items-center gap-3">
        <span class="iconify tabler--sparkles size-5 shrink-0" aria-hidden="true"></span>
        <div>
            <p class="font-semibold text-sm">Desbloquea más personalización</p>
            <p class="text-xs opacity-70">Header Top, Acerca de, Testimonios y más desde el Plan CRECIMIENTO</p>
        </div>
    </div>
    <a href="https://syntiweb.com/planes" target="_blank" rel="noopener noreferrer"
       class="inline-flex items-center text-sm py-1.5 px-3 rounded-lg font-medium transition-colors bg-blue-600 text-white hover:bg-blue-700 shrink-0">Ver Planes</a>
</div>
@endif
            </div>{{-- /p-6 --}}
        </div>
