{{-- ═══ Tab: Tu Menú (SYNTIfood) ═══ --}}
<div id="tab-productos" class="tab-content">
    @php
        $planId      = (int) ($plan->id ?? 1);
        $limits      = \App\Services\MenuService::limits($planId);
        $maxItems    = $limits['items'];
        $totalItems  = collect($menu)->sum(fn($c) => count($c['items'] ?? []));
        $pct         = $maxItems > 0 ? round(($totalItems / $maxItems) * 100) : 0;
        $barColor    = $pct >= 90 ? 'bg-red-500' : ($pct >= 70 ? 'bg-yellow-500' : 'bg-green-500');
    @endphp

    <div class="p-6">
        {{-- ── Header Card ─────────────────────────────────────── --}}
        <div class="bg-surface rounded-xl shadow-sm border border-border">
            <div class="px-6 pt-6 pb-4 flex items-center justify-between gap-3 flex-wrap">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <div class="size-10 rounded-xl bg-primary/10 flex items-center justify-center">
                            <span class="iconify tabler--tools-kitchen-2 size-5 text-primary" aria-hidden="true"></span>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-foreground">Tu Menú</h2>
                            <p class="text-xs text-muted-foreground-1">{{ count($menu) }} categorías · {{ $totalItems }} de {{ $maxItems }} platos</p>
                        </div>
                    </div>
                </div>
                <button class="inline-flex items-center text-sm py-1.5 px-3 rounded-lg font-medium transition-all bg-blue-600 text-white hover:bg-blue-700 gap-1.5 shadow-sm hover:shadow-lg"
                        onclick="MenuAdmin.openCategoryModal()"
                        title="Agregar categoría">
                    <span class="iconify tabler--plus size-4" aria-hidden="true"></span>
                    Agregar Categoría
                </button>
            </div>

            {{-- ── Usage bar ──────────────────────────────────── --}}
            <div class="px-6 pb-4">
                <div class="flex items-center justify-between text-xs text-muted-foreground-1 mb-1">
                    <span>{{ $totalItems }}/{{ $maxItems }} platos</span>
                    <span>{{ $pct }}%</span>
                </div>
                <div class="w-full h-2 rounded-full bg-layer overflow-hidden">
                    <div class="{{ $barColor }} h-full rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                </div>
                @if($pct >= 90)
                <p class="text-xs text-red-600 mt-1">Estás cerca del límite de tu plan.
                    <a href="https://syntiweb.com/planes" target="_blank" rel="noopener noreferrer" class="underline font-medium">Mejorar plan ↗</a>
                </p>
                @endif
            </div>

            <div class="border-t border-border"></div>

            {{-- ── Categories + Items ─────────────────────────── --}}
            <div class="px-6 pb-6 space-y-4 mt-4" id="menu-categories-container">
                @if(count($menu) > 0)
                    @foreach($menu as $cat)
                    <div class="rounded-xl border border-border bg-surface overflow-hidden" x-data="{ open: true }">
                        {{-- Category header --}}
                        <div class="px-4 py-3 flex items-center justify-between gap-3 bg-layer/50">
                            <button type="button" @click="open = !open" class="flex items-center gap-3 flex-1 text-left min-w-0">
                                <span class="iconify tabler--chevron-right size-4 text-muted-foreground-1 transition-transform duration-200"
                                      :class="open && 'rotate-90'" aria-hidden="true"></span>
                                <span class="font-semibold text-sm text-foreground truncate">{{ $cat['nombre'] ?? $cat['name'] ?? '' }}</span>
                                <span class="text-xs text-muted-foreground-1 shrink-0">{{ count($cat['items'] ?? []) }} ítems</span>
                                @if(!($cat['activo'] ?? $cat['active'] ?? true))
                                    <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-red-100 text-red-700">Inactiva</span>
                                @endif
                            </button>
                            <div class="flex items-center gap-1 shrink-0">
                                <button onclick="MenuAdmin.openItemModal('{{ $cat['id'] }}', '{{ e($cat['nombre'] ?? $cat['name'] ?? '') }}')"
                                        class="inline-flex items-center justify-center size-8 rounded-lg text-blue-600 hover:bg-blue-50 transition-colors"
                                        title="Agregar ítem">
                                    <span class="iconify tabler--plus size-4" aria-hidden="true"></span>
                                </button>
                                <button onclick="MenuAdmin.openCategoryModal('{{ $cat['id'] }}', '{{ e($cat['nombre'] ?? $cat['name'] ?? '') }}', {{ ($cat['activo'] ?? $cat['active'] ?? true) ? 'true' : 'false' }})"
                                        class="inline-flex items-center justify-center size-8 rounded-lg text-foreground/60 hover:bg-layer transition-colors"
                                        title="Editar categoría">
                                    <span class="iconify tabler--pencil size-4" aria-hidden="true"></span>
                                </button>
                                <button onclick="MenuAdmin.deleteCategory('{{ $cat['id'] }}', '{{ e($cat['nombre'] ?? $cat['name'] ?? '') }}')"
                                        class="inline-flex items-center justify-center size-8 rounded-lg text-red-500 hover:bg-red-50 transition-colors"
                                        title="Eliminar categoría">
                                    <span class="iconify tabler--trash size-4" aria-hidden="true"></span>
                                </button>
                            </div>
                        </div>
                        {{-- Items table --}}
                        <div x-show="open" x-collapse>
                            @if(count($cat['items'] ?? []) > 0)
                            <div class="border-t border-border divide-y divide-border">
                                @foreach($cat['items'] as $item)
                                <div class="px-4 py-2.5 flex items-center justify-between gap-3 hover:bg-layer/30 transition-colors">
                                    <div class="flex items-center gap-3 min-w-0 flex-1">
                                        @if(!empty($item['image_path']))
                                            <img src="{{ asset('storage/tenants/' . $tenant->id . '/' . $item['image_path']) }}"
                                                 alt="" class="size-10 rounded-lg object-cover shrink-0 border border-border" loading="lazy">
                                        @else
                                            <span class="inline-flex items-center justify-center size-10 rounded-lg bg-layer shrink-0">
                                                <span class="iconify tabler--bowl-chopsticks size-5 text-muted-foreground-1" aria-hidden="true"></span>
                                            </span>
                                        @endif
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-1.5">
                                                @if($item['is_featured'] ?? false)
                                                    <span class="text-yellow-500 shrink-0" title="Destacado">⭐</span>
                                                @endif
                                                <span class="text-sm text-foreground truncate">{{ $item['nombre'] ?? $item['name'] ?? '' }}</span>
                                            </div>
                                            <div class="flex items-center gap-1.5 mt-0.5">
                                                @if(!empty($item['badge']))
                                                    @php
                                                        $b = $item['badge'];
                                                        $badgeCfg = match($b) {
                                                            'popular'   => ['icon' => 'tabler--star-filled', 'bg' => 'bg-amber-100',  'text' => 'text-amber-700',  'label' => 'Popular'],
                                                            'nuevo'     => ['icon' => 'tabler--sparkles',    'bg' => 'bg-green-100',  'text' => 'text-green-700',  'label' => 'Nuevo'],
                                                            'promo'     => ['icon' => 'tabler--tag',         'bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'label' => 'Promo'],
                                                            'destacado' => ['icon' => 'tabler--bolt',        'bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'label' => 'Recomendado'],
                                                            default     => ['icon' => 'tabler--star',        'bg' => 'bg-gray-100',   'text' => 'text-gray-700',   'label' => $b],
                                                        };
                                                    @endphp
                                                    <span class="inline-flex items-center gap-1 py-0.5 px-1.5 rounded-full text-xs font-medium {{ $badgeCfg['bg'] }} {{ $badgeCfg['text'] }}">
                                                        <span class="iconify {{ $badgeCfg['icon'] }} size-3"></span>
                                                        {{ $badgeCfg['label'] }}
                                                    </span>
                                                @endif
                                                @if(!($item['activo'] ?? $item['active'] ?? true))
                                                    <span class="inline-flex items-center py-0.5 px-1.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Off</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 shrink-0">
                                        <span class="text-sm font-bold text-primary">REF {{ number_format($item['precio'] ?? $item['price'] ?? 0, 2) }}</span>
                                        <button onclick="MenuAdmin.openItemModal('{{ $cat['id'] }}', '{{ e($cat['nombre'] ?? $cat['name'] ?? '') }}', '{{ $item['id'] }}', {{ json_encode(['nombre' => $item['nombre'] ?? $item['name'] ?? '', 'precio' => $item['precio'] ?? $item['price'] ?? 0, 'activo' => $item['activo'] ?? $item['active'] ?? true, 'descripcion' => $item['descripcion'] ?? $item['description'] ?? '', 'badge' => $item['badge'] ?? '', 'is_featured' => $item['is_featured'] ?? false, 'image_url' => !empty($item['image_path']) ? asset('storage/tenants/' . $tenant->id . '/' . $item['image_path']) : ''], JSON_HEX_APOS | JSON_HEX_QUOT) }})"
                                                class="inline-flex items-center justify-center size-7 rounded-lg text-foreground/60 hover:bg-layer transition-colors"
                                                title="Editar ítem">
                                            <span class="iconify tabler--pencil size-3.5" aria-hidden="true"></span>
                                        </button>
                                        <button onclick="MenuAdmin.deleteItem('{{ $cat['id'] }}', '{{ $item['id'] }}', '{{ e($item['nombre'] ?? $item['name'] ?? '') }}')"
                                                class="inline-flex items-center justify-center size-7 rounded-lg text-red-500 hover:bg-red-50 transition-colors"
                                                title="Eliminar ítem">
                                            <span class="iconify tabler--trash size-3.5" aria-hidden="true"></span>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="border-t border-border px-4 py-6 text-center">
                                <span class="iconify tabler--bowl size-8 text-foreground/15 mx-auto mb-2" aria-hidden="true"></span>
                                <p class="text-sm text-muted-foreground-1">Sin ítems en esta categoría</p>
                                <button onclick="MenuAdmin.openItemModal('{{ $cat['id'] }}', '{{ e($cat['nombre'] ?? $cat['name'] ?? '') }}')"
                                        class="mt-2 text-sm text-blue-600 hover:underline font-medium">+ Agregar ítem</button>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="py-12 text-center">
                        <span class="iconify tabler--tools-kitchen-2 size-12 text-foreground/15 mx-auto mb-3" aria-hidden="true"></span>
                        <p class="text-foreground font-semibold mb-1">Sin categorías aún</p>
                        <p class="text-sm text-muted-foreground-1 mb-4">Crea tu primera categoría para organizar los platos del menú.</p>
                        <button onclick="MenuAdmin.openCategoryModal()"
                                class="inline-flex items-center text-sm py-1.5 px-4 rounded-lg font-medium bg-blue-600 text-white hover:bg-blue-700 gap-1.5">
                            <span class="iconify tabler--plus size-4" aria-hidden="true"></span>
                            Agregar Categoría
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ═══ MODAL: Categoría (crear / editar) ═══ --}}
    <div id="menu-cat-modal" class="hidden fixed inset-0 z-[80] overflow-y-auto">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="MenuAdmin.closeCatModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-md bg-surface rounded-xl shadow-xl border border-border">
                <div class="px-6 pt-5 pb-4 border-b border-border flex items-center justify-between">
                    <h3 id="menu-cat-modal-title" class="text-lg font-bold text-foreground">Nueva Categoría</h3>
                    <button onclick="MenuAdmin.closeCatModal()" class="size-8 inline-flex items-center justify-center rounded-lg text-foreground/60 hover:bg-layer transition-colors">
                        <span class="iconify tabler--x size-4" aria-hidden="true"></span>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <input type="hidden" id="menu-cat-edit-id" value="">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1.5">Nombre de la categoría</label>
                        <input type="text" id="menu-cat-nombre" maxlength="120" placeholder="Ej: Entradas, Postres…"
                               class="w-full rounded-lg border border-border bg-surface px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground-1 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div id="menu-cat-activo-wrap" class="hidden">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" id="menu-cat-activo" checked
                                   class="size-4 rounded border-border text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-foreground">Categoría activa</span>
                        </label>
                    </div>
                </div>
                <div class="px-6 pb-5 flex justify-end gap-2">
                    <button onclick="MenuAdmin.closeCatModal()"
                            class="inline-flex items-center text-sm py-2 px-4 rounded-lg font-medium border border-border text-foreground hover:bg-layer transition-colors">
                        Cancelar
                    </button>
                    <button onclick="MenuAdmin.saveCat()"
                            class="inline-flex items-center text-sm py-2 px-4 rounded-lg font-medium bg-blue-600 text-white hover:bg-blue-700 transition-colors gap-1.5">
                        <span class="iconify tabler--device-floppy size-4" aria-hidden="true"></span>
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ MODAL: Plato (crear / editar) ═══ --}}
    <div id="menu-item-modal" class="hidden fixed inset-0 z-[80] overflow-y-auto">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="MenuAdmin.closeItemModal()"></div>
        <div class="flex min-h-full items-start justify-center pt-16 px-4 pb-4">
            <div class="relative w-full max-w-md bg-surface rounded-xl shadow-xl border border-border">
                <div class="px-6 pt-5 pb-4 border-b border-border flex items-center justify-between">
                    <div class="min-w-0 flex-1">
                        <h3 id="menu-item-modal-title" class="text-base font-bold text-foreground leading-tight">Nuevo ítem</h3>
                        <p id="menu-item-modal-cat" class="text-xs text-muted-foreground-1 mt-0.5 truncate"></p>
                    </div>
                    <button onclick="MenuAdmin.closeItemModal()" class="ml-2 shrink-0 size-8 inline-flex items-center justify-center rounded-lg text-foreground/60 hover:bg-layer transition-colors">
                        <span class="iconify tabler--x size-4" aria-hidden="true"></span>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <input type="hidden" id="menu-item-cat-id" value="">
                    <input type="hidden" id="menu-item-edit-id" value="">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1.5">Nombre del ítem</label>
                        <input type="text" id="menu-item-nombre" maxlength="200" placeholder="Ej: Hamburguesa, Coca Cola, Torta de Chocolate…"
                               class="w-full rounded-lg border border-border bg-surface px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground-1 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1.5">Descripción <span class="text-muted-foreground-1 font-normal">(opcional)</span></label>
                        <textarea id="menu-item-descripcion" maxlength="200" rows="2" placeholder="Ingredientes, preparación, tamaño..."
                                  class="w-full rounded-lg border border-border bg-surface px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground-1 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                                  oninput="document.getElementById('menu-item-desc-count').textContent = this.value.length"></textarea>
                        <p class="text-xs text-muted-foreground-1 mt-1"><span id="menu-item-desc-count">0</span>/200</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1.5">Precio (REF)</label>
                        <input type="number" id="menu-item-precio" min="0" step="0.01" placeholder="0.00"
                               class="w-full rounded-lg border border-border bg-surface px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground-1 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1.5">Foto <span class="text-muted-foreground-1 font-normal">(opcional)</span></label>
                        <div id="menu-item-foto-preview" class="hidden mb-2 relative inline-block">
                            <img id="menu-item-foto-thumb" src="" alt="" class="size-20 rounded-lg object-cover border border-border"
                                 onerror="this.style.display='none'; this.closest('#menu-item-foto-preview').classList.add('hidden');">
                            <button type="button" onclick="MenuAdmin.removeItemPhoto()"
                                    class="absolute -top-1.5 -right-1.5 size-5 rounded-full bg-red-500 text-white flex items-center justify-center hover:bg-red-600 transition-colors shadow-sm"
                                    title="Quitar foto">
                                <span class="iconify tabler--x size-3" aria-hidden="true"></span>
                            </button>
                        </div>
                        <input type="file" id="menu-item-imagen" accept="image/jpeg,image/png,image/webp" capture="environment"
                               class="block w-full text-sm text-foreground file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 file:cursor-pointer"
                               onchange="MenuAdmin.previewItemPhoto(this)">
                        <input type="hidden" id="menu-item-remove-image" value="0">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1.5">Badge</label>
                        <select id="menu-item-badge"
                                class="w-full rounded-lg border border-border bg-surface px-3 py-2 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Sin badge</option>
                            <option value="popular">Popular</option>
                            <option value="nuevo">Nuevo</option>
                            <option value="promo">Promo</option>
                            <option value="destacado">Recomendado</option>
                        </select>
                    </div>

                    {{-- EXTRAS / OPTIONS SECTION --}}
                    @php 
                        $plan = $tenant->plan ?? null;
                        $canUseExtras = $plan && \Illuminate\Support\Str::contains($plan->slug ?? '', ['food-semestral', 'food-anual']);
                    @endphp

                    @if($canUseExtras)
                    <div class="border-t border-border pt-4">
                        <button type="button" onclick="MenuAdmin.toggleExtrasSection()"
                                class="w-full flex items-center justify-between text-sm font-medium text-foreground mb-3 p-2 rounded-lg hover:bg-layer transition-colors">
                            <span class="flex items-center gap-2">
                                <span class="iconify tabler--plus-circle size-4"></span>
                                Extras / Opciones
                            </span>
                            <span id="menu-item-extras-toggle" class="iconify tabler--chevron-down size-4 transition-transform" style="transform: rotate(0deg);"></span>
                        </button>
                        <div id="menu-item-extras-container" class="hidden space-y-2 p-3 rounded-lg bg-layer/30 border border-layer-line">
                            <div id="menu-item-extras-list" class="space-y-2"></div>
                            <button type="button" id="menu-item-add-extra-btn" onclick="MenuAdmin.addExtraRow()"
                                    class="w-full text-sm py-2 px-3 rounded-lg border border-dashed border-primary text-primary font-medium hover:bg-primary/5 transition-colors">
                                + Agregar extra
                            </button>
                        </div>
                    </div>
                    @else
                    <div class="rounded-lg border border-amber-200 bg-amber-50 p-3">
                        <p class="text-xs font-medium text-amber-700 flex items-center gap-1.5">
                            <span class="iconify tabler--lock size-3.5"></span>
                            Extras disponibles desde plan Semestral
                            <a href="https://syntiweb.com/food" target="_blank" class="underline ml-1 hover:no-underline">Mejorar →</a>
                        </p>
                    </div>
                    @endif

                    <div>
                        <label class="flex items-center gap-2 cursor-pointer p-2.5 rounded-lg bg-layer border border-border hover:bg-layer/80 transition-colors">
                            <input type="checkbox" id="menu-item-featured"
                                   class="size-4 rounded border-border text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-foreground">⭐ Marcar como destacado</span>
                        </label>
                    </div>
                    <div id="menu-item-activo-wrap" class="hidden">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" id="menu-item-activo" checked
                                   class="size-4 rounded border-border text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-foreground">Plato activo</span>
                        </label>
                    </div>
                </div>
                <div class="px-6 pb-5 flex justify-end gap-2">
                    <button onclick="MenuAdmin.closeItemModal()"
                            class="inline-flex items-center text-sm py-2 px-4 rounded-lg font-medium border border-border text-foreground hover:bg-layer transition-colors">
                        Cancelar
                    </button>
                    <button onclick="MenuAdmin.saveItem()"
                            class="inline-flex items-center text-sm py-2 px-4 rounded-lg font-medium bg-blue-600 text-white hover:bg-blue-700 transition-colors gap-1.5">
                        <span class="iconify tabler--device-floppy size-4" aria-hidden="true"></span>
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══ IIFE: Menu Admin JS ═══ --}}
<script>
(function() {
    'use strict';

    var TENANT_ID = {{ (int) $tenant->id }};
    var BASE      = '/tenant/' + TENANT_ID + '/food';
    var CSRF      = document.querySelector('meta[name="csrf-token"]')?.content || '';

    function toast(msg, type) {
        if (window.showToast) { window.showToast(msg, type); return; }
        alert(msg);
    }

    function reload() {
        if (window.__dashboardSaveState) { window.__dashboardSaveState(); }
        window.location.reload();
    }

    async function api(url, method, body) {
        var opts = {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'X-Requested-With': 'XMLHttpRequest'
            }
        };
        if (body) { opts.body = JSON.stringify(body); }

        var res = await fetch(url, opts);
        var data = await res.json();

        if (!res.ok) {
            var errMsg = data.error || data.message || 'Error inesperado';
            if (data.error === 'item_limit_reached') {
                errMsg = 'Límite de platos alcanzado (' + data.limit + '). Mejora tu plan.';
            }
            if (data.error === 'photo_limit_reached') {
                errMsg = 'Límite de fotos alcanzado (' + data.limit + ').';
            }
            toast(errMsg, 'error');
            return null;
        }
        return data;
    }

    // ── Category Modal ──────────────────────────────────────────

    function openCategoryModal(catId, nombre, activo) {
        var isEdit = !!catId;
        document.getElementById('menu-cat-modal-title').textContent = isEdit ? 'Editar Categoría' : 'Nueva Categoría';
        document.getElementById('menu-cat-edit-id').value = catId || '';
        document.getElementById('menu-cat-nombre').value = nombre || '';
        document.getElementById('menu-cat-activo').checked = activo !== false;
        document.getElementById('menu-cat-activo-wrap').classList.toggle('hidden', !isEdit);
        document.getElementById('menu-cat-modal').classList.remove('hidden');
        setTimeout(function() { document.getElementById('menu-cat-nombre').focus(); }, 100);
    }

    function closeCatModal() {
        document.getElementById('menu-cat-modal').classList.add('hidden');
    }

    async function saveCat() {
        var nombre = document.getElementById('menu-cat-nombre').value.trim();
        if (!nombre) { toast('Ingresa un nombre para la categoría', 'error'); return; }

        var catId = document.getElementById('menu-cat-edit-id').value;
        var isEdit = !!catId;

        var body = { nombre: nombre };
        if (isEdit) {
            body.activo = document.getElementById('menu-cat-activo').checked;
        }

        var url = isEdit ? BASE + '/categories/' + catId : BASE + '/categories';
        var method = isEdit ? 'PUT' : 'POST';

        var data = await api(url, method, body);
        if (!data) { return; }

        toast(isEdit ? 'Categoría actualizada' : 'Categoría creada', 'success');
        closeCatModal();
        reload();
    }

    async function deleteCategory(catId, nombre) {
        if (!confirm('¿Eliminar la categoría "' + nombre + '" y todos sus platos?')) { return; }

        var data = await api(BASE + '/categories/' + catId, 'DELETE');
        if (!data) { return; }

        toast('Categoría eliminada', 'success');
        reload();
    }

    // ── Item Modal ──────────────────────────────────────────────

    function openItemModal(catId, catNombre, itemId, itemData) {
        var isEdit = !!itemId;
        var d = itemData || {};
        document.getElementById('menu-item-modal-title').textContent = isEdit ? 'Editar ítem' : 'Nuevo ítem';
        document.getElementById('menu-item-modal-cat').textContent = catNombre ? 'en ' + catNombre : '';
        document.getElementById('menu-item-cat-id').value = catId || '';
        document.getElementById('menu-item-edit-id').value = itemId || '';
        document.getElementById('menu-item-nombre').value = d.nombre || '';
        document.getElementById('menu-item-descripcion').value = d.descripcion || '';
        document.getElementById('menu-item-desc-count').textContent = (d.descripcion || '').length;
        document.getElementById('menu-item-precio').value = d.precio || '';
        document.getElementById('menu-item-badge').value = d.badge || '';
        document.getElementById('menu-item-featured').checked = !!d.is_featured;
        document.getElementById('menu-item-activo').checked = d.activo !== false;
        document.getElementById('menu-item-activo-wrap').classList.toggle('hidden', !isEdit);
        // Foto
        document.getElementById('menu-item-imagen').value = '';
        document.getElementById('menu-item-remove-image').value = '0';
        var previewWrap = document.getElementById('menu-item-foto-preview');
        var previewImg  = document.getElementById('menu-item-foto-thumb');
        if (d.image_url) {
            previewImg.src = d.image_url;
            previewWrap.classList.remove('hidden');
        } else {
            previewImg.src = '';
            previewWrap.classList.add('hidden');
        }
        // Initialize extras
        MenuAdmin.initExtras(d.options || []);
        document.getElementById('menu-item-modal').classList.remove('hidden');
        setTimeout(function() { document.getElementById('menu-item-nombre').focus(); }, 100);
    }

    function closeItemModal() {
        document.getElementById('menu-item-modal').classList.add('hidden');
    }

    async function saveItem() {
        var nombre = document.getElementById('menu-item-nombre').value.trim();
        var precio = parseFloat(document.getElementById('menu-item-precio').value);

        if (!nombre) { toast('Ingresa un nombre para el plato', 'error'); return; }
        if (isNaN(precio) || precio < 0) { toast('Ingresa un precio válido', 'error'); return; }

        var catId  = document.getElementById('menu-item-cat-id').value;
        var itemId = document.getElementById('menu-item-edit-id').value;
        var isEdit = !!itemId;

        var fd = new FormData();
        fd.append('nombre', nombre);
        fd.append('precio', precio);
        fd.append('descripcion', document.getElementById('menu-item-descripcion').value.trim());
        fd.append('badge', document.getElementById('menu-item-badge').value);
        fd.append('is_featured', document.getElementById('menu-item-featured').checked ? '1' : '0');
        
        // Add options if extras section exists
        var extrasContainer = document.getElementById('menu-item-extras-container');
        if (extrasContainer && !extrasContainer.classList.contains('hidden')) {
            fd.append('options', JSON.stringify(MenuAdmin.getExtras()));
        } else if (window.MenuAdmin && window.MenuAdmin.menuItemOptions) {
            fd.append('options', JSON.stringify(window.MenuAdmin.menuItemOptions));
        }

        if (isEdit) {
            fd.append('activo', document.getElementById('menu-item-activo').checked ? '1' : '0');
            fd.append('_method', 'PUT');
        }

        var fileInput = document.getElementById('menu-item-imagen');
        if (fileInput.files[0]) {
            fd.append('imagen', fileInput.files[0]);
        }

        if (document.getElementById('menu-item-remove-image').value === '1') {
            fd.append('remove_image', '1');
        }

        var url = isEdit
            ? BASE + '/categories/' + catId + '/items/' + itemId
            : BASE + '/categories/' + catId + '/items';

        try {
            var res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: fd
            });
            var data = await res.json();

            if (!res.ok) {
                var errMsg = data.error || data.message || 'Error inesperado';
                if (data.error === 'item_limit_reached') {
                    errMsg = 'Límite de platos alcanzado (' + data.limit + '). Mejora tu plan.';
                }
                toast(errMsg, 'error');
                return;
            }

            toast(isEdit ? 'Plato actualizado' : 'Plato agregado', 'success');
            if (data.warning) { setTimeout(function() { toast(data.warning, 'warning'); }, 500); }
            closeItemModal();
            reload();
        } catch (err) {
            toast('Error de conexión', 'error');
        }
    }

    async function deleteItem(catId, itemId, nombre) {
        if (!confirm('¿Eliminar el plato "' + nombre + '"?')) { return; }

        var data = await api(BASE + '/categories/' + catId + '/items/' + itemId, 'DELETE');
        if (!data) { return; }

        toast('Plato eliminado', 'success');
        reload();
    }

    // ── Public API ──────────────────────────────────────────────

    function previewItemPhoto(input) {
        var previewWrap = document.getElementById('menu-item-foto-preview');
        var previewImg  = document.getElementById('menu-item-foto-thumb');
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewWrap.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
            document.getElementById('menu-item-remove-image').value = '0';
        }
    }

    function removeItemPhoto() {
        document.getElementById('menu-item-foto-preview').classList.add('hidden');
        document.getElementById('menu-item-foto-thumb').src = '';
        document.getElementById('menu-item-imagen').value = '';
        document.getElementById('menu-item-remove-image').value = '1';
    }

    // ── EXTRAS SYSTEM ────────────────────────────────────────────

    var menuItemOptions = [];

    function initExtras(options) {
        menuItemOptions = Array.isArray(options) ? JSON.parse(JSON.stringify(options)) : [];
        renderExtrasRows();
    }

    function getExtras() {
        return menuItemOptions;
    }

    function toggleExtrasSection() {
        var container = document.getElementById('menu-item-extras-container');
        var toggle = document.getElementById('menu-item-extras-toggle');
        if (container.classList.contains('hidden')) {
            container.classList.remove('hidden');
            toggle.style.transform = 'rotate(180deg)';
        } else {
            container.classList.add('hidden');
            toggle.style.transform = 'rotate(0deg)';
        }
    }

    function renderExtrasRows() {
        var list = document.getElementById('menu-item-extras-list');
        list.innerHTML = '';

        menuItemOptions.forEach(function(opt, idx) {
            var row = document.createElement('div');
            row.className = 'flex gap-2 items-end';
            row.innerHTML = 
                '<input type="text" maxlength="80" value="' + (opt.label || '').replace(/"/g, '&quot;') + '" ' +
                '       placeholder="Ej: Extra queso" class="flex-1 text-xs rounded-lg border border-border bg-surface px-2 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" ' +
                '       onchange="MenuAdmin.updateExtraLabel(' + idx + ', this.value)">' +
                '<input type="number" min="0" max="50" step="0.01" value="' + (opt.price_add || 0) + '" ' +
                '       placeholder="0.00" class="w-20 text-xs rounded-lg border border-border bg-surface px-2 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" ' +
                '       onchange="MenuAdmin.updateExtraPrice(' + idx + ', this.value)">' +
                '<button type="button" onclick="MenuAdmin.removeExtraRow(' + idx + ')" ' +
                '        class="size-8 rounded-lg border border-red-300 text-red-600 flex items-center justify-center hover:bg-red-50 transition-colors">' +
                '  <span class="iconify tabler--trash size-4"></span>' +
                '</button>';
            list.appendChild(row);
        });

        // Update button disabled state if at max
        var addBtn = document.getElementById('menu-item-add-extra-btn');
        if (addBtn) {
            addBtn.disabled = menuItemOptions.length >= 8;
            addBtn.style.opacity = menuItemOptions.length >= 8 ? '0.5' : '1';
            addBtn.title = menuItemOptions.length >= 8 ? 'Máximo 8 extras' : '';
        }
    }

    function addExtraRow() {
        if (menuItemOptions.length >= 8) {
            toast('Máximo 8 extras por plato', 'error');
            return;
        }
        menuItemOptions.push({ label: '', price_add: 0 });
        renderExtrasRows();
    }

    function updateExtraLabel(idx, label) {
        if (menuItemOptions[idx]) {
            menuItemOptions[idx].label = label.trim();
        }
    }

    function updateExtraPrice(idx, price) {
        if (menuItemOptions[idx]) {
            menuItemOptions[idx].price_add = parseFloat(price) || 0;
        }
    }

    function removeExtraRow(idx) {
        menuItemOptions.splice(idx, 1);
        renderExtrasRows();
    }

    window.MenuAdmin = {
        openCategoryModal: openCategoryModal,
        closeCatModal:     closeCatModal,
        saveCat:           saveCat,
        deleteCategory:    deleteCategory,
        openItemModal:     openItemModal,
        closeItemModal:    closeItemModal,
        saveItem:          saveItem,
        deleteItem:        deleteItem,
        previewItemPhoto:  previewItemPhoto,
        removeItemPhoto:   removeItemPhoto,
        initExtras:        initExtras,
        getExtras:         getExtras,
        toggleExtrasSection: toggleExtrasSection,
        addExtraRow:       addExtraRow,
        updateExtraLabel:  updateExtraLabel,
        updateExtraPrice:  updateExtraPrice,
        removeExtraRow:    removeExtraRow,
        menuItemOptions:   menuItemOptions
    };

})();
</script>
