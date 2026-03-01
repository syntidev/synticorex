        <!-- Tab: Tu Mensaje -->
        <div id="tab-mensaje" class="tab-content">
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
<div class="card bg-base-100 shadow-md border border-base-content/8 card-elevated mb-6">
    <div class="card-header px-5 pt-5 pb-3">
        <div class="flex items-center gap-3">
            <div class="size-9 rounded-lg bg-primary/10 flex items-center justify-center">
                <span class="iconify tabler--list-check size-5 text-primary" aria-hidden="true"></span>
            </div>
            <div>
                <h2 class="text-base font-bold text-base-content">Orden de Secciones</h2>
                <p class="text-xs text-base-content/50">Arrastra para reordenar. Las secciones apagadas no aparecen en tu landing.</p>
            </div>
        </div>
    </div>
    <div class="card-body px-5 pb-5 pt-1">
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
                                bg-base-200 border border-base-content/10
                                {{ $hasAccess ? 'cursor-move' : 'cursor-not-allowed' }}
                                transition-colors hover:border-base-content/20">

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

                        <span class="flex-1 text-sm font-medium text-base-content">
                            {{ $section['label'] }}
                            @if(!$hasAccess)
                                <span class="badge badge-warning badge-soft badge-xs ms-1">
                                    Plan {{ $planRequired == 2 ? 'CRECIMIENTO' : 'VISIÓN' }}
                                </span>
                            @endif
                        </span>

                        @if($hasAccess)
                            <input type="checkbox"
                                   class="toggle toggle-primary toggle-sm section-toggle"
                                   id="section-{{ $key }}"
                                   @checked($isVisible)
                                   onchange="toggleSection('{{ $key }}', this.checked)">

                            <div class="join join-vertical flex-shrink-0">
                                <button type="button"
                                        onclick="moveSection('{{ $key }}', -1)"
                                        class="join-item btn btn-xs btn-square btn-ghost border border-base-content/10 hover:btn-primary transition-all"
                                        title="Subir">
                                    <span class="iconify tabler--chevron-up size-3.5" aria-hidden="true"></span>
                                </button>
                                <button type="button"
                                        onclick="moveSection('{{ $key }}', 1)"
                                        class="join-item btn btn-xs btn-square btn-ghost border border-base-content/10 hover:btn-primary transition-all"
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
<div class="card bg-base-100 shadow-md border border-base-content/8 card-elevated mb-6">
    <div class="card-header px-5 pt-5 pb-3">
        <div class="flex items-center gap-3">
            <div class="size-9 rounded-lg bg-primary/10 flex items-center justify-center">
                <span class="iconify tabler--layout-navbar size-5 text-primary" aria-hidden="true"></span>
            </div>
            <div>
                <h2 class="text-base font-bold text-base-content">Header Top</h2>
                <p class="text-xs text-base-content/50">Barra superior con mensaje destacado
                    <span class="badge badge-soft badge-primary badge-xs ms-1">CRECIMIENTO+</span>
                </p>
            </div>
        </div>
    </div>
    <div class="card-body px-5 pb-5 pt-1">
        <div class="flex items-center gap-3 mb-3">
            <label class="text-xs font-medium text-base-content/70">Visible:</label>
            <input type="checkbox" id="header-top-toggle"
                   class="toggle toggle-primary toggle-sm"
                   {{ $headerTopEnabled ? 'checked' : '' }}>
        </div>
        <div class="flex gap-2">
            <input type="text" id="header-top-text"
                   class="input input-bordered input-sm flex-1"
                   placeholder="Ej: Envío gratis en pedidos mayores a 50 REF"
                   value="{{ $headerTopText }}"
                   maxlength="120">
            <button type="button" onclick="saveHeaderTop()" class="btn btn-primary btn-sm gap-1">
                <span class="iconify tabler--device-floppy size-4"></span>
                Guardar
            </button>
        </div>
    </div>
</div>
@endif

{{-- ════════════════════════════════════════════════════════════
     3. ACERCA DE (Plan 2+)
════════════════════════════════════════════════════════════ --}}
@if($plan->id >= 2)
<div class="card bg-base-100 shadow-md border border-base-content/8 card-elevated mb-6">
    <div class="card-header px-5 pt-5 pb-3">
        <div class="flex items-center gap-3">
            <div class="size-9 rounded-lg bg-primary/10 flex items-center justify-center">
                <span class="iconify tabler--info-circle size-5 text-primary" aria-hidden="true"></span>
            </div>
            <div>
                <h2 class="text-base font-bold text-base-content">Acerca de</h2>
                <p class="text-xs text-base-content/50">Texto de la sección "Nosotros" de tu landing
                    <span class="badge badge-soft badge-primary badge-xs ms-1">CRECIMIENTO+</span>
                </p>
            </div>
        </div>
    </div>
    <div class="card-body px-5 pb-5 pt-1">
        <textarea id="about-text"
                  class="textarea textarea-bordered w-full text-sm"
                  rows="4"
                  placeholder="Cuenta la historia de tu negocio..."
                  maxlength="1000"
                  oninput="document.getElementById('about-char-count').textContent = this.value.length + '/1000'">{{ $tenant->description ?? '' }}</textarea>
        <div class="flex justify-between items-center mt-2">
            <span class="text-[10px] text-base-content/40" id="about-char-count">{{ strlen($tenant->description ?? '') }}/1000</span>
            <button type="button" onclick="saveAboutText()" class="btn btn-primary btn-sm gap-1">
                <span class="iconify tabler--device-floppy size-4"></span>
                Guardar
            </button>
        </div>
    </div>
</div>
@endif

{{-- ════════════════════════════════════════════════════════════
     4. TESTIMONIOS (Plan 2+)
════════════════════════════════════════════════════════════ --}}
@if($plan->id >= 2)
<div class="card bg-base-100 shadow-md border border-base-content/8 card-elevated mb-6">
    <div class="card-header px-5 pt-5 pb-3 flex items-center justify-between gap-3 flex-wrap">
        <div class="flex items-center gap-3">
            <div class="size-9 rounded-lg bg-primary/10 flex items-center justify-center">
                <span class="iconify tabler--message-star size-5 text-primary" aria-hidden="true"></span>
            </div>
            <div>
                <h2 class="text-base font-bold text-base-content">Testimonios de Clientes</h2>
                <p class="text-xs text-base-content/50">{{ count($savedTestimonials) }} testimonios
                    <span class="badge badge-soft badge-primary badge-xs ms-1">CRECIMIENTO+</span>
                </p>
            </div>
        </div>
    </div>
    <div class="card-body px-5 pb-5 pt-1">
        <div id="testimonials-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($savedTestimonials as $ti => $testim)
            @php $hasContent = !empty($testim['name']) || !empty($testim['text']); @endphp
            <div class="rounded-lg border p-3 transition-all {{ $hasContent ? 'border-primary/20 bg-primary/5' : 'border-base-content/10 bg-base-200/30' }}"
                 data-testimonial-index="{{ $ti }}">
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

{{-- ════════════════════════════════════════════════════════════
     5. FAQ (Plan 3)
════════════════════════════════════════════════════════════ --}}
@if($plan->id >= 3)
<div class="card bg-base-100 shadow-md border border-base-content/8 card-elevated mb-6">
    <div class="card-header px-5 pt-5 pb-3 flex items-center justify-between gap-3 flex-wrap">
        <div class="flex items-center gap-3">
            <div class="size-9 rounded-lg bg-secondary/10 flex items-center justify-center">
                <span class="iconify tabler--help-circle size-5 text-secondary" aria-hidden="true"></span>
            </div>
            <div>
                <h2 class="text-base font-bold text-base-content">Preguntas Frecuentes (FAQ)</h2>
                <p class="text-xs text-base-content/50">{{ count($savedFaq) }} preguntas
                    <span class="badge badge-soft badge-secondary badge-xs ms-1">VISIÓN</span>
                </p>
            </div>
        </div>
    </div>
    <div class="card-body px-5 pb-5 pt-1">
        <div id="faq-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($savedFaq as $fi => $fitem)
            @php $hasFaqContent = !empty($fitem['question']) || !empty($fitem['answer']); @endphp
            <div class="rounded-lg border p-3 transition-all {{ $hasFaqContent ? 'border-secondary/20 bg-secondary/5' : 'border-base-content/10 bg-base-200/30' }}"
                 data-faq-index="{{ $fi }}">
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

{{-- ════════════════════════════════════════════════════════════
     6. CTA ESPECIAL (Plan 3)
════════════════════════════════════════════════════════════ --}}
@if($plan->id >= 3)
<div class="card bg-base-100 shadow-md border border-base-content/8 card-elevated mb-6">
    <div class="card-header px-5 pt-5 pb-3">
        <div class="flex items-center gap-3">
            <div class="size-9 rounded-lg bg-accent/10 flex items-center justify-center">
                <span class="iconify tabler--speakerphone size-5 text-accent" aria-hidden="true"></span>
            </div>
            <div>
                <h2 class="text-base font-bold text-base-content">CTA Especial</h2>
                <p class="text-xs text-base-content/50">Sección llamativa con botón personalizado
                    <span class="badge badge-soft badge-accent badge-xs ms-1">VISIÓN</span>
                </p>
            </div>
        </div>
    </div>
    <div class="card-body px-5 pb-5 pt-1">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label class="text-xs font-medium text-base-content/70 mb-1 block">Título</label>
                <input type="text" id="cta-title"
                       class="input input-bordered input-sm w-full"
                       placeholder="¡Contacta con nosotros!"
                       value="{{ $ctaTitle }}"
                       maxlength="100">
            </div>
            <div>
                <label class="text-xs font-medium text-base-content/70 mb-1 block">Subtítulo</label>
                <input type="text" id="cta-subtitle"
                       class="input input-bordered input-sm w-full"
                       placeholder="Estamos listos para atenderte"
                       value="{{ $ctaSub }}"
                       maxlength="200">
            </div>
            <div>
                <label class="text-xs font-medium text-base-content/70 mb-1 block">Texto del Botón</label>
                <input type="text" id="cta-btn-text"
                       class="input input-bordered input-sm w-full"
                       placeholder="Ej: Pedir ahora"
                       value="{{ $ctaBtnText }}"
                       maxlength="50">
            </div>
            <div>
                <label class="text-xs font-medium text-base-content/70 mb-1 block">Enlace del Botón</label>
                <input type="url" id="cta-btn-link"
                       class="input input-bordered input-sm w-full"
                       placeholder="https://..."
                       value="{{ $ctaBtnLink }}">
            </div>
        </div>
        <button type="button" onclick="saveCtaConfig()" class="btn btn-primary btn-sm gap-1 mt-3">
            <span class="iconify tabler--device-floppy size-4"></span>
            Guardar CTA
        </button>
    </div>
</div>
@endif

{{-- ════════════════════════════════════════════════════════════
     7. SUCURSALES (Plan 3)
════════════════════════════════════════════════════════════ --}}
@if($plan->id >= 3)
<div class="card bg-base-100 shadow-md border border-base-content/8 card-elevated mb-6">
    <div class="card-header px-5 pt-5 pb-3 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <div class="size-9 rounded-lg bg-primary/10 flex items-center justify-center">
                <span class="iconify tabler--map-pin size-5 text-primary" aria-hidden="true"></span>
            </div>
            <div>
                <h2 class="text-base font-bold text-base-content">Sucursales</h2>
                <p class="text-xs text-base-content/50">{{ $currentBranchCount }} de {{ $maxBranches }} sucursales
                    <span class="badge badge-soft badge-secondary badge-xs ms-1">VISIÓN</span>
                </p>
            </div>
        </div>
        <input type="checkbox" id="branches-toggle"
               class="toggle toggle-success toggle-sm"
               {{ $branchesEnabled ? 'checked' : '' }}
               onchange="toggleBranchesSection()">
    </div>
    <div class="card-body px-5 pb-5 pt-1">
        <div id="branches-status" class="alert {{ $branchesEnabled ? 'alert-success' : 'alert-info' }} mb-3">
            <span class="iconify {{ $branchesEnabled ? 'tabler--check' : 'tabler--pause' }} size-4" aria-hidden="true"></span>
            <p id="branches-status-text" class="text-sm">
                {{ $branchesEnabled ? 'Sección visible en tu landing' : 'Sección oculta en tu landing' }}
            </p>
        </div>
        <div id="branches-content" {{ $branchesEnabled ? '' : 'style="display:none"' }}>
            <div id="branches-list" class="grid {{ $branchGridClass }} gap-3">
                @foreach($branches as $branch)
                <div class="rounded-lg border border-base-content/10 bg-base-200/30 p-4 transition-all hover:border-primary/30 branch-card" id="branch-card-{{ $branch->id }}">
                    <div class="flex items-start gap-3 mb-3">
                        <div class="size-10 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                            <span class="iconify tabler--map-pin size-5 text-primary" aria-hidden="true"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="branch-name text-sm font-semibold text-base-content truncate">{{ $branch->name }}</h3>
                            <p class="branch-address text-xs text-base-content/50 line-clamp-2 mt-0.5">{{ $branch->address }}</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="button"
                                class="btn btn-primary btn-sm btn-square"
                                onclick="editBranch({{ $branch->id }}, '{{ addslashes($branch->name) }}', '{{ addslashes($branch->address) }}')"
                                title="Editar">
                            <span class="iconify tabler--pencil size-5" aria-hidden="true"></span>
                        </button>
                        <button type="button"
                                class="btn btn-error btn-sm btn-square"
                                onclick="deleteBranch({{ $branch->id }})"
                                title="Eliminar">
                            <span class="iconify tabler--trash size-5" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
                @endforeach
                @if($currentBranchCount < $maxBranches)
                <div class="rounded-lg border border-base-content/10 bg-base-200/30 p-4 flex items-center justify-center transition-all hover:border-primary/30 cursor-pointer" onclick="openBranchModal()">
                    <div class="text-center">
                        <div class="size-10 rounded-lg bg-primary/10 flex items-center justify-center mx-auto mb-2">
                            <span class="iconify tabler--plus size-5 text-primary" aria-hidden="true"></span>
                        </div>
                        <p class="text-sm font-semibold text-base-content">Agregar Sucursal</p>
                        <p class="text-xs text-base-content/50">{{ $currentBranchCount }} de {{ $maxBranches }} usadas</p>
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
<div class="alert alert-info flex items-center justify-between gap-4 flex-wrap">
    <div class="flex items-center gap-3">
        <span class="iconify tabler--sparkles size-5 shrink-0" aria-hidden="true"></span>
        <div>
            <p class="font-semibold text-sm">Desbloquea más personalización</p>
            <p class="text-xs opacity-70">Header Top, Acerca de, Testimonios y más desde el Plan CRECIMIENTO</p>
        </div>
    </div>
    <a href="https://syntiweb.com/planes" target="_blank" rel="noopener noreferrer"
       class="btn btn-primary btn-sm shrink-0">Ver Planes</a>
</div>
@endif

        </div>
