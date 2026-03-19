        <!-- Tab: Catálogo de Productos (SYNTIcat) -->
        <div id="tab-productos" class="tab-content">
            @php
                $maxProducts = $maxItems ?? (int) ($plan->products_limit ?? 20);
                $currentCount = $products->count();
                $dynLabel = 'Productos';
                $dynSingular = 'Producto';
                $maxImages = match($plan->slug ?? '') {
                    'cat-crecimiento' => 3,
                    'cat-vision'     => 6,
                    default         => 1,
                };
            @endphp

            {{-- ── Categorías CAT ──────────────────────────────────── --}}
            <div class="p-6 pb-0">
            <div class="bg-surface rounded-xl shadow-sm border border-border mb-4">
                <div class="px-6 py-4 flex items-center justify-between gap-3 flex-wrap">
                    <div class="flex items-center gap-3">
                        <div class="size-9 rounded-xl bg-primary/10 flex items-center justify-center">
                            <span class="iconify tabler--tag size-4 text-primary" aria-hidden="true"></span>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-foreground">Categorías</h3>
                            <p class="text-xs text-muted-foreground-1">Organiza tus productos por categoría y subcategoría</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="text" id="cat-new-category-input"
                               class="py-1.5 px-3 text-sm bg-layer border border-layer-line rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus w-44"
                               maxlength="60" placeholder="Nueva categoría..."
                               onkeydown="if(event.key==='Enter'){event.preventDefault();addCatCategory();}">
                        <button onclick="addCatCategory()"
                                class="inline-flex items-center text-sm py-1.5 px-3 rounded-lg font-medium bg-primary text-primary-foreground hover:bg-primary-hover gap-1 cursor-pointer">
                            <span class="iconify tabler--plus size-4" aria-hidden="true"></span>
                            Agregar
                        </button>
                    </div>
                </div>

                <div id="cat-categories-list" class="px-6 pb-4 space-y-2">
                    @forelse($catCategories as $cat)
                    <div class="cat-group" data-cat-id="{{ $cat['id'] }}" data-cat-name="{{ $cat['name'] }}">
                        {{-- Categoría padre --}}
                        <div class="flex items-center gap-2 flex-wrap" data-cat-row="1">
                            <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-semibold bg-primary/10 text-primary border border-primary/20">
                                <span class="iconify tabler--folder size-3" aria-hidden="true"></span>
                                {{ $cat['name'] }}
                                <button type="button" onclick="deleteCatCategory('{{ $cat['id'] }}')"
                                        class="size-3.5 flex items-center justify-center rounded-full hover:bg-primary/20 cursor-pointer transition-colors"
                                        title="Eliminar categoría">
                                    <span class="iconify tabler--x size-3"></span>
                                </button>
                            </span>

                            {{-- Subcategorías --}}
                            @foreach($cat['subcategories'] ?? [] as $sub)
                            <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-medium bg-layer text-foreground border border-layer-line"
                                  data-sub-id="{{ $sub['id'] }}" data-sub-name="{{ $sub['name'] }}">
                                {{ $sub['name'] }}
                                <button type="button" onclick="deleteCatCategory('{{ $sub['id'] }}')"
                                        class="size-3.5 flex items-center justify-center rounded-full hover:bg-red-100 cursor-pointer transition-colors"
                                        title="Eliminar subcategoría">
                                    <span class="iconify tabler--x size-3"></span>
                                </button>
                            </span>
                            @endforeach

                            {{-- Mini input para agregar subcategoría inline --}}
                            <span class="inline-flex items-center gap-1" data-sub-input-wrap="1">
                                <input type="text" placeholder="+ sub"
                                       class="py-0.5 px-2 text-xs bg-transparent border border-dashed border-layer-line rounded-full text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus w-24"
                                       maxlength="60"
                                       onkeydown="if(event.key==='Enter'){event.preventDefault();addCatSubcategory('{{ $cat['id'] }}', this);}">
                            </span>
                        </div>
                    </div>
                    @empty
                    <p class="text-xs text-muted-foreground-1 py-1" id="cat-no-categories-msg">Sin categorías. Agrega la primera.</p>
                    @endforelse
                </div>
            </div>
            </div>

            {{-- ── Productos card ─────────────────────────────────── --}}
            <div class="p-6">
            <div class="bg-surface rounded-xl shadow-sm border border-border">
                <div class="px-6 pt-6 pb-4 flex items-center justify-between gap-3 flex-wrap">
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <div class="size-10 rounded-xl bg-primary/10 flex items-center justify-center">
                                <span class="iconify tabler--shopping-bag size-5 text-primary" aria-hidden="true"></span>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-foreground">Catálogo</h2>
                                <p class="text-xs text-muted-foreground-1">{{ $currentCount }} de {{ $maxProducts }} productos activos</p>
                            </div>
                        </div>
                    </div>
                    <button class="inline-flex items-center text-sm py-1.5 px-3 rounded-lg font-medium transition-all bg-blue-600 text-white hover:bg-blue-700 gap-1.5 shadow-sm hover:shadow-lg"
                            onclick="checkAndOpenCatProductModal()"
                            title="Agregar nuevo producto">
                        <span class="iconify tabler--plus size-4" aria-hidden="true"></span>
                        Agregar Producto
                    </button>
                </div>
                <div class="border-t border-border"></div>
                {{-- Barra búsqueda + toggle vista --}}
                <div class="px-6 pt-4 pb-2 flex items-center gap-2 flex-wrap">
                    <div class="relative flex-1 min-w-[180px]">
                        <span class="iconify tabler--search size-4 absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground-1 pointer-events-none"></span>
                        <input type="text" id="cat-search-input"
                               placeholder="Buscar producto..."
                               class="pl-9 pr-3 py-1.5 text-sm w-full bg-layer border border-layer-line rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus"
                               oninput="catFilterProducts(this.value)">
                    </div>
                    <div class="flex rounded-lg border border-layer-line overflow-hidden">
                        <button id="cat-view-grid" onclick="catSetView('grid')"
                                class="px-2.5 py-1.5 bg-primary text-primary-foreground transition-colors cursor-pointer"
                                title="Vista cuadrícula">
                            <span class="iconify tabler--layout-grid size-4"></span>
                        </button>
                        <button id="cat-view-list" onclick="catSetView('list')"
                                class="px-2.5 py-1.5 bg-layer text-muted-foreground-1 hover:bg-layer-hover transition-colors cursor-pointer"
                                title="Vista lista">
                            <span class="iconify tabler--list size-4"></span>
                        </button>
                    </div>
                </div>
                <div class="px-6 pb-6">
                @if($currentCount >= $maxProducts)
                <div class="flex p-4 rounded-lg border bg-blue-50 border-blue-200 text-blue-800 mb-4 items-center justify-between gap-4 flex-wrap">
                    <div class="flex items-center gap-3">
                        <span class="iconify tabler--info-circle size-5 shrink-0" aria-hidden="true"></span>
                        <div>
                            <p class="font-semibold text-sm">Límite alcanzado ({{ $maxProducts }}/{{ $maxProducts }} productos)</p>
                            <p class="text-xs opacity-70">Actualiza tu plan para agregar más</p>
                        </div>
                    </div>
                    <a href="https://syntiweb.com/planes" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center text-sm py-1.5 px-3 rounded-lg font-medium transition-colors bg-blue-600 text-white hover:bg-blue-700 shrink-0">Ver Planes ↗</a>
                </div>
                @endif

                @if($products->count() > 0)
                {{-- Vista grid --}}
                <div id="cat-grid-view" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($products as $product)
                    <div class="group relative rounded-xl border border-border bg-surface overflow-hidden shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                        {{-- Imagen thumbnail con overlay --}}
                        <figure class="relative h-44 bg-layer overflow-hidden">
                            @if($product->image_filename)
                                <img src="{{ asset('storage/tenants/' . $tenant->id . '/' . $product->image_filename) }}"
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 cursor-zoom-in"
                                     loading="lazy" decoding="async"
                                     onclick="openImgPreview('{{ asset('storage/tenants/' . $tenant->id . '/' . $product->image_filename) }}')">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <span class="iconify tabler--shopping-bag size-12 text-foreground/15" aria-hidden="true"></span>
                                </div>
                            @endif
                            {{-- Hover overlay --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-3 gap-2">
                                <button onclick="editCatProduct({{ $product->id }})"
                                        class="inline-flex items-center text-sm py-1.5 px-3 rounded-lg font-medium transition-colors bg-blue-600 text-white hover:bg-blue-700 shadow-lg">
                                    <span class="iconify tabler--pencil size-4 mr-1" aria-hidden="true"></span> Editar
                                </button>
                                <button onclick="deleteProduct({{ $product->id }})"
                                        class="inline-flex items-center text-sm py-1.5 px-3 rounded-lg font-medium transition-colors bg-red-600 text-white hover:bg-red-700 shadow-lg">
                                    <span class="iconify tabler--trash size-4" aria-hidden="true"></span>
                                </button>
                            </div>
                            {{-- Badges --}}
                            @if($product->is_featured)
                            <div class="absolute top-2 left-2">
                                <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700 shadow-md">⭐ Especial</span>
                            </div>
                            @endif
                            @if(!$product->is_active)
                            <div class="absolute top-2 right-2">
                                <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-red-100 text-red-700 shadow-md">Off</span>
                            </div>
                            @endif
                            {{-- Variantes badge --}}
                            @if(!empty($product->variants))
                            <div class="absolute bottom-2 right-2">
                                <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-white/90 text-foreground/70 shadow-sm">
                                    {{ count($product->variants) }} variante{{ count($product->variants) > 1 ? 's' : '' }}
                                </span>
                            </div>
                            @endif
                        </figure>
                        {{-- Info --}}
                        <div class="p-4">
                            {{-- Categoría --}}
                            @if(!empty($product->category_name))
                            <p class="text-[10px] font-black uppercase tracking-widest text-muted-foreground-1 mb-1">{{ $product->category_name }}</p>
                            @endif
                            <h4 class="text-sm font-bold text-foreground line-clamp-2 mb-1.5">{{ $product->name }}</h4>
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-extrabold text-primary">REF {{ number_format($product->price_usd, 2) }}</span>
                                @if($product->badge)
                                <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-foreground/5 text-foreground/60">
                                    {{ ucfirst($product->badge) }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Vista lista (server-side) --}}
                <div id="cat-list-view" style="display:none" class="border border-border rounded-xl overflow-hidden divide-y divide-border">
                    @foreach($products as $product)
                    <div class="px-4 py-2.5 flex items-center justify-between gap-3 hover:bg-layer/30 transition-colors">
                        <div class="flex items-center gap-3 min-w-0 flex-1">
                            @if($product->image_filename)
                                <img src="{{ asset('storage/tenants/' . $tenant->id . '/' . $product->image_filename) }}"
                                     alt="{{ $product->name }}"
                                     class="size-10 rounded-lg object-cover shrink-0 border border-border">
                            @else
                                <span class="inline-flex items-center justify-center size-10 rounded-lg bg-layer shrink-0">
                                    <span class="iconify tabler--shopping-bag size-5 text-muted-foreground-1"></span>
                                </span>
                            @endif
                            <div class="min-w-0">
                                <div class="flex items-center gap-1.5">
                                    @if($product->is_featured)
                                        <span class="text-yellow-500 shrink-0">⭐</span>
                                    @endif
                                    <span class="text-sm text-foreground truncate font-medium">{{ $product->name }}</span>
                                </div>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    @if($product->category_name)
                                        <span class="text-xs text-muted-foreground-1">{{ $product->category_name }}</span>
                                    @endif
                                    @if($product->badge)
                                        <span class="inline-flex items-center gap-1 py-0.5 px-1.5 rounded-full text-[10px] font-semibold bg-primary/10 text-primary">{{ ucfirst($product->badge) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <span class="text-sm font-bold text-primary">REF {{ number_format($product->price_usd, 2) }}</span>
                            <button onclick="editCatProduct({{ $product->id }})"
                                    class="inline-flex items-center justify-center size-8 rounded-lg text-blue-600 hover:bg-blue-50 transition-colors cursor-pointer">
                                <span class="iconify tabler--pencil size-4"></span>
                            </button>
                            <button onclick="deleteProduct({{ $product->id }})"
                                    class="inline-flex items-center justify-center size-8 rounded-lg text-red-500 hover:bg-red-50 transition-colors cursor-pointer">
                                <span class="iconify tabler--trash size-4"></span>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Paginación --}}
                <div id="cat-pagination" class="flex items-center justify-between gap-3 mt-4 flex-wrap">
                    <p id="cat-page-info" class="text-xs text-muted-foreground-1"></p>
                    <div class="flex items-center gap-1">
                        <button id="cat-prev-btn" onclick="catChangePage(-1)"
                                class="px-3 py-1.5 text-xs rounded-lg border border-layer-line bg-layer text-foreground hover:bg-layer-hover disabled:opacity-40 cursor-pointer">← Anterior</button>
                        <span id="cat-page-num" class="px-3 py-1.5 text-xs font-bold text-foreground"></span>
                        <button id="cat-next-btn" onclick="catChangePage(1)"
                                class="px-3 py-1.5 text-xs rounded-lg border border-layer-line bg-layer text-foreground hover:bg-layer-hover disabled:opacity-40 cursor-pointer">Siguiente →</button>
                    </div>
                </div>
                @else
                <x-dashboard.empty-state
                    icon="shopping-bag"
                    title="Sin productos aún"
                    message="Agrega tu primer producto para que aparezca en tu catálogo." />
                @endif
                </div>{{-- /px-6 pb-6 --}}
            </div>
            </div>{{-- /p-6 --}}

        </div>{{-- /tab-productos --}}

        {{-- ══════════════════════════════════════════════════════════════
             MODAL: Producto catálogo (con variantes + categoría + multi-img)
        ══════════════════════════════════════════════════════════════════ --}}
        <div id="cat-product-modal" class="crud-overlay"
             role="dialog" aria-modal="true" aria-labelledby="cat-product-modal-title" aria-hidden="true">
            <div class="crud-dialog">
                <div class="crud-dialog-header">
                    <h3 class="crud-dialog-title flex items-center gap-2" id="cat-product-modal-title">
                        <span class="iconify tabler--shopping-bag size-4 opacity-80" aria-hidden="true"></span>
                        Agregar Producto
                    </h3>
                    <button class="crud-dialog-close" onclick="closeCatProductModal()" aria-label="Cerrar modal">
                        <span class="iconify tabler--x size-4" aria-hidden="true"></span>
                    </button>
                </div>
                <div class="crud-dialog-body">
                    <form id="cat-product-form" onsubmit="saveCatProduct(event)" class="flex flex-col gap-3">
                        <input type="hidden" id="cat-product-id">
                        <input type="hidden" id="cat-variants-json" name="variants">

                        {{-- Imagen principal --}}
                        <div class="image-preview rounded-xl overflow-hidden border border-border bg-layer" id="cat-product-image-preview" style="display:none">
                            <img id="cat-product-image-preview-img" src="" alt="Preview" class="w-full max-w-none h-48 sm:h-56 object-cover">
                            <button type="button" onclick="cancelCatProductImage()" class="p-1 rounded-full transition-colors text-foreground hover:bg-muted-hover absolute top-1 right-1">
                                <span class="iconify tabler--x size-3.5" aria-hidden="true"></span>
                            </button>
                        </div>
                        <div>
                            <label class="inline-block text-xs font-semibold text-muted-foreground-1 uppercase tracking-wide mb-1.5">
                                Imagen Principal
                            </label>
                            <div id="cat-product-upload-zone"
                                 class="border-2 border-dashed border-border rounded-lg p-3.5 text-center cursor-pointer hover:border-primary hover:bg-primary/5 transition-all"
                                 onclick="document.getElementById('cat-product-image').click()"
                                 ondragover="event.preventDefault(); this.classList.add('border-primary','bg-primary/5')"
                                 ondragleave="this.classList.remove('border-primary','bg-primary/5')"
                                 ondrop="event.preventDefault(); this.classList.remove('border-primary','bg-primary/5'); document.getElementById('cat-product-image').files = event.dataTransfer.files; previewCatProductImage({target: document.getElementById('cat-product-image')})">
                                <span class="iconify tabler--cloud-upload size-7 mx-auto text-muted-foreground-1 mb-1"></span>
                                <p class="text-sm font-medium text-foreground/70">Arrastra o <span class="text-primary font-bold">elige imagen</span></p>
                                <p class="text-xs text-muted-foreground-1 mt-0.5">PNG, JPG, WebP · Máx 2MB</p>
                            </div>
                            <input type="file" id="cat-product-image" accept="image/*" capture="environment" class="hidden" onchange="previewCatProductImage(event)">
                        </div>

                        {{-- Imágenes adicionales según plan --}}
                        @if($maxImages > 1)
                        <div>
                            <label class="inline-flex items-center gap-1.5 text-xs font-semibold text-muted-foreground-1 uppercase tracking-wide mb-1.5">
                                <span class="iconify tabler--photo-scan size-3.5 text-primary" aria-hidden="true"></span>
                                Imágenes adicionales
                                <span id="cat-gallery-counter" class="font-normal normal-case">0 / {{ $maxImages - 1 }} en galería</span>
                            </label>
                            <div class="flex gap-2 flex-wrap" id="cat-extra-images-area">
                                @for($i = 2; $i <= $maxImages; $i++)
                                <div class="flex flex-col gap-1">
                                    <div id="cat-img-preview-{{ $i }}" class="size-14 rounded-lg bg-layer border border-border overflow-hidden hidden">
                                        <img id="cat-img-preview-img-{{ $i }}" src="" alt="" class="w-full h-full object-cover">
                                    </div>
                                    <input type="file" id="cat-product-image-{{ $i }}" accept="image/*"
                                           class="py-1 px-2 block text-xs bg-layer border border-layer-line rounded-lg text-foreground w-32"
                                           onchange="previewCatExtraImage(event, {{ $i }})">
                                </div>
                                @endfor
                            </div>
                        </div>
                        @endif

                        {{-- Nombre --}}
                        <div>
                            <label for="cat-product-name" class="inline-block text-xs font-semibold text-muted-foreground-1 uppercase tracking-wide mb-1.5">Nombre *</label>
                            <input type="text" id="cat-product-name" class="py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus" required maxlength="100" placeholder="Nombre del producto">
                        </div>

                        {{-- Categoría + Subcategoría --}}
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="cat-product-category" class="inline-block text-xs font-semibold text-muted-foreground-1 uppercase tracking-wide mb-1.5">Categoría</label>
                                <select id="cat-product-category" onchange="updateSubcategorySelect()" class="py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs text-sm rounded-lg text-foreground focus:border-primary-focus focus:ring-primary-focus">
                                    <option value="">Sin categoría</option>
                                    @foreach($catCategories as $cat)
                                    <option value="{{ $cat['name'] }}" data-cat-id="{{ $cat['id'] }}" data-subs="{{ json_encode($cat['subcategories'] ?? []) }}">{{ $cat['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="cat-product-subcategory" class="inline-block text-xs font-semibold text-muted-foreground-1 uppercase tracking-wide mb-1.5">Subcategoría</label>
                                <select id="cat-product-subcategory" class="py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs text-sm rounded-lg text-foreground focus:border-primary-focus focus:ring-primary-focus">
                                    <option value="">Sin subcategoría</option>
                                </select>
                            </div>
                        </div>

                        {{-- Precio + Precio original --}}
                        <div class="grid grid-cols-2 gap-3">
                            <div class="col-span-2 sm:col-span-1">
                                <label for="cat-product-price" class="inline-block text-xs font-semibold text-muted-foreground-1 uppercase tracking-wide mb-1.5">Precio de venta *</label>
                                <input type="number" id="cat-product-price" class="py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus" required step="0.01" min="0" placeholder="0.00">
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label for="cat-product-compare-price" class="inline-block text-xs font-semibold text-muted-foreground-1 uppercase tracking-wide mb-1.5">Precio original</label>
                                <input type="number" id="cat-product-compare-price" class="py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus" step="0.01" min="0" placeholder="Opcional">
                            </div>
                        </div>

                        {{-- Descripción --}}
                        <div>
                            <label for="cat-product-description" class="inline-block text-xs font-semibold text-muted-foreground-1 uppercase tracking-wide mb-1.5">Descripción</label>
                            <textarea id="cat-product-description" class="py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus" rows="2" maxlength="500" placeholder="Descripción breve del producto..."></textarea>
                        </div>

                        {{-- ── VARIANTES ─────────────────────────────────── --}}
                        <div>
                            <label class="inline-flex items-center gap-1.5 text-xs font-semibold text-muted-foreground-1 uppercase tracking-wide mb-1.5">
                                <span class="iconify tabler--adjustments-horizontal size-3.5 text-primary" aria-hidden="true"></span>
                                Variantes
                                <span class="font-normal normal-case text-foreground/40">(Talla, Color, etc.)</span>
                            </label>
                            <div id="cat-variants-list" class="flex flex-col gap-2 mb-2"></div>
                            <button type="button" onclick="addCatVariantRow()"
                                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary hover:text-primary/80 transition-colors cursor-pointer">
                                <span class="iconify tabler--plus size-3.5" aria-hidden="true"></span>
                                Agregar variante
                            </button>
                        </div>

                        {{-- Badge + Active + Featured --}}
                        <div class="grid grid-cols-3 gap-2.5 items-end">
                            <div class="col-span-3 sm:col-span-1">
                                <label for="cat-product-badge" class="inline-block text-xs font-semibold text-muted-foreground-1 uppercase tracking-wide mb-1.5">Badge</label>
                                <select id="cat-product-badge" class="py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs text-sm rounded-lg text-foreground focus:border-primary-focus focus:ring-primary-focus">
                                    <option value="">Sin badge</option>
                                    <option value="popular">Popular</option>
                                    <option value="nuevo">Nuevo</option>
                                    <option value="promo">Promo</option>
                                    <option value="destacado">Recomendado</option>
                                </select>
                            </div>
                            <label class="col-span-3 sm:col-span-1 flex flex-col gap-1.5 cursor-pointer p-2.5 rounded-lg bg-layer border border-layer-line hover:bg-layer-hover transition-colors h-full justify-center">
                                <span class="text-xs font-semibold text-muted-foreground-1 uppercase tracking-wide">Activo</span>
                                <span class="flex items-center gap-2">
                                    <input type="checkbox" id="cat-product-is-active" class="size-4 rounded-sm border-border text-primary focus:ring-primary/20 cursor-pointer" checked>
                                    <span class="text-xs text-foreground">Visible en catálogo</span>
                                </span>
                            </label>
                            <label class="col-span-3 sm:col-span-1 flex flex-col gap-1.5 cursor-pointer p-2.5 rounded-lg bg-layer border border-layer-line hover:bg-layer-hover transition-colors h-full justify-center">
                                <span class="text-xs font-semibold text-muted-foreground-1 uppercase tracking-wide flex items-center gap-1">
                                    <span class="iconify tabler--star-filled size-3 text-amber-400" aria-hidden="true"></span> Destacado
                                </span>
                                <span class="flex items-center gap-2">
                                    <input type="checkbox" id="cat-product-is-featured" class="size-4 rounded-sm border-border text-primary focus:ring-primary/20 cursor-pointer">
                                    <span class="text-xs text-foreground">Marcar como especial</span>
                                </span>
                            </label>
                        </div>

                        {{-- Actions --}}
                        <div class="flex gap-2.5 pt-3 border-t border-border mt-1">
                            <button type="button"
                                    class="py-2 px-3 inline-flex items-center justify-center gap-2 text-sm font-medium rounded-lg bg-layer border border-layer-line text-layer-foreground shadow-2xs hover:bg-layer-hover flex-1"
                                    onclick="closeCatProductModal()">Cancelar</button>
                            <button type="submit"
                                    class="py-2 px-4 inline-flex items-center justify-center gap-2 text-sm font-medium rounded-lg bg-primary border border-primary-line text-primary-foreground hover:bg-primary-hover disabled:opacity-50 flex-1 shadow-sm">
                                <span class="iconify tabler--device-floppy size-4" aria-hidden="true"></span>
                                Guardar Producto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>{{-- /cat-product-modal --}}

<script>
(function() {
    'use strict';

    var _catMaxProducts = {{ $maxProducts }};
    var _catCurrentCount = {{ $currentCount }};
    var _catVariantRowIdx = 0;
    var catGalleryStaging = {}; // stage files at preview-time, not read-time
    var _catGalleryCount  = 0;

    // ── Categorías ──────────────────────────────────────────
    window.updateSubcategorySelect = function() {
        var catSel = document.getElementById('cat-product-category');
        var subSel = document.getElementById('cat-product-subcategory');
        subSel.innerHTML = '<option value="">Sin subcategoría</option>';
        var opt = catSel.options[catSel.selectedIndex];
        if (!opt || !opt.dataset.subs) return;
        try {
            var subs = JSON.parse(opt.dataset.subs);
            subs.forEach(function(s) {
                var o = document.createElement('option');
                o.value = s.name; o.textContent = s.name;
                subSel.appendChild(o);
            });
        } catch(e) {}
    };

    window.addCatCategory = function() {
        var input = document.getElementById('cat-new-category-input');
        var name = (input.value || '').trim();
        if (!name) { input.focus(); return; }

        fetch('/tenant/{{ $tenant->id }}/cat-categories', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ name: name })
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (!res.success) { alert(res.message || 'Error al agregar'); return; }
            var cat = res.data;
            input.value = '';
            var noMsg = document.getElementById('cat-no-categories-msg');
            if (noMsg) noMsg.remove();
            var list = document.getElementById('cat-categories-list');
            var group = document.createElement('div');
            group.className = 'cat-group';
            group.dataset.catId = cat.id;
            group.dataset.catName = cat.name;
            group.innerHTML =
                '<div class="flex items-center gap-2 flex-wrap" data-cat-row="1">' +
                '  <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-semibold bg-primary/10 text-primary border border-primary/20">' +
                '    <span class="iconify tabler--folder size-3"></span>' +
                '    ' + cat.name +
                '    <button type="button" onclick="deleteCatCategory(\'' + cat.id + '\')" class="size-3.5 flex items-center justify-center rounded-full hover:bg-primary/20 cursor-pointer transition-colors" title="Eliminar categoría"><span class="iconify tabler--x size-3"></span></button>' +
                '  </span>' +
                '  <span class="inline-flex items-center gap-1" data-sub-input-wrap="1">' +
                '    <input type="text" placeholder="+ sub" class="py-0.5 px-2 text-xs bg-transparent border border-dashed border-layer-line rounded-full text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus w-24" maxlength="60" onkeydown="if(event.key===\'Enter\'){event.preventDefault();addCatSubcategory(\'' + cat.id + '\', this);}">' +
                '  </span>' +
                '</div>';
            list.appendChild(group);
            // Añadir al select
            var select = document.getElementById('cat-product-category');
            if (select) {
                var opt = document.createElement('option');
                opt.value = cat.name; opt.textContent = cat.name;
                opt.dataset.catId = cat.id;
                opt.dataset.subs = '[]';
                select.appendChild(opt);
            }
        })
        .catch(function() { alert('Error de conexión'); });
    };

    window.addCatSubcategory = function(parentId, inputEl) {
        var name = (inputEl.value || '').trim();
        if (!name) return;

        fetch('/tenant/{{ $tenant->id }}/cat-categories', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ name: name, parent_id: parentId })
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (!res.success) { alert(res.message || 'Error al agregar subcategoría'); return; }
            var sub = res.data;
            inputEl.value = '';
            // Insertar chip antes del input (dentro del flex-wrap del grupo)
            var group = document.querySelector('.cat-group[data-cat-id="' + parentId + '"]');
            if (!group) return;
            var wrap = group.querySelector('[data-cat-row]');
            if (!wrap) return;
            var inputWrap = wrap.querySelector('[data-sub-input-wrap]');
            if (!inputWrap) return;
            var chip = document.createElement('span');
            chip.className = 'inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-medium bg-layer text-foreground border border-layer-line';
            chip.dataset.subId = sub.id; chip.dataset.subName = sub.name;
            chip.innerHTML = sub.name +
                '<button type="button" onclick="deleteCatCategory(\'' + sub.id + '\')" class="size-3.5 flex items-center justify-center rounded-full hover:bg-red-100 cursor-pointer transition-colors" title="Eliminar subcategoría"><span class="iconify tabler--x size-3"></span></button>';
            wrap.insertBefore(chip, inputWrap);
            // Actualizar subs en el option del select de categoría
            var catSelect = document.getElementById('cat-product-category');
            if (catSelect) {
                var opt = catSelect.querySelector('option[data-cat-id="' + parentId + '"]');
                if (opt) {
                    try {
                        var subs = JSON.parse(opt.dataset.subs || '[]');
                        subs.push({ id: sub.id, name: sub.name });
                        opt.dataset.subs = JSON.stringify(subs);
                    } catch(e) {}
                }
            }
        })
        .catch(function() { alert('Error de conexión'); });
    };

    window.deleteCatCategory = function(catId) {
        if (!confirm('¿Eliminar?')) return;
        fetch('/tenant/{{ $tenant->id }}/cat-categories/' + catId, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (!res.success) { alert(res.message || 'Error al eliminar'); return; }
            // Remover grupo padre completo si es categoría raíz
            var group = document.querySelector('.cat-group[data-cat-id="' + catId + '"]');
            if (group) {
                group.remove();
                // Quitar del select
                var catSelect = document.getElementById('cat-product-category');
                if (catSelect) {
                    var opt = catSelect.querySelector('option[data-cat-id="' + catId + '"]');
                    if (opt) opt.remove();
                }
            } else {
                // Es subcategoría
                var subChip = document.querySelector('[data-sub-id="' + catId + '"]');
                if (subChip) {
                    // Actualizar data-subs del parent option
                    var parentGroup = subChip.closest('.cat-group');
                    if (parentGroup) {
                        var pId = parentGroup.dataset.catId;
                        var catSelect = document.getElementById('cat-product-category');
                        if (catSelect) {
                            var opt = catSelect.querySelector('option[data-cat-id="' + pId + '"]');
                            if (opt) {
                                try {
                                    var subs = JSON.parse(opt.dataset.subs || '[]');
                                    subs = subs.filter(function(s) { return s.id !== catId; });
                                    opt.dataset.subs = JSON.stringify(subs);
                                } catch(e) {}
                            }
                        }
                    }
                    subChip.remove();
                }
            }
            // Si no quedan categorías
            var list = document.getElementById('cat-categories-list');
            if (list && !list.querySelector('.cat-group')) {
                list.innerHTML = '<p class="text-xs text-muted-foreground-1 py-1" id="cat-no-categories-msg">Sin categorías. Agrega la primera.</p>';
            }
        })
        .catch(function() { alert('Error de conexión'); });
    };

    // ── Límite ──────────────────────────────────────────────
    window.checkAndOpenCatProductModal = function() {
        if (_catCurrentCount >= _catMaxProducts) {
            alert('Límite de ' + _catMaxProducts + ' productos alcanzado. Actualiza tu plan para agregar más.');
            return;
        }
        openCatProductModal();
    };

    // ── Abrir / Cerrar modal ─────────────────────────────────
    window.openCatProductModal = function() {
        document.getElementById('cat-product-modal-title').innerHTML =
            '<span class="iconify tabler--shopping-bag size-4 opacity-80"></span> Agregar Producto';
        document.getElementById('cat-product-id').value = '';
        document.getElementById('cat-product-form').reset();
        catGalleryStaging = {}; // clear staged files for new product
        @if($tenant->isVision()) updateCatGalleryCounter(0); @endif
        document.getElementById('cat-product-image-preview').style.display = 'none';
        document.getElementById('cat-product-upload-zone').style.display = '';
        document.getElementById('cat-variants-list').innerHTML = '';
        document.getElementById('cat-variants-json').value = '[]';
        _catVariantRowIdx = 0;
        var modal = document.getElementById('cat-product-modal');
        modal.style.display = 'flex';
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    };

    window.closeCatProductModal = function() {
        var modal = document.getElementById('cat-product-modal');
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    };

    // ── Imagen principal ─────────────────────────────────────
    window.previewCatProductImage = function(e) {
        var file = e.target.files[0];
        if (!file) return;
        var reader = new FileReader();
        reader.onload = function(ev) {
            document.getElementById('cat-product-image-preview-img').src = ev.target.result;
            document.getElementById('cat-product-image-preview').style.display = '';
            document.getElementById('cat-product-upload-zone').style.display = 'none';
        };
        reader.readAsDataURL(file);
    };

    window.cancelCatProductImage = function() {
        document.getElementById('cat-product-image').value = '';
        document.getElementById('cat-product-image-preview').style.display = 'none';
        document.getElementById('cat-product-upload-zone').style.display = '';
    };

    // ── Imágenes extra ───────────────────────────────────────
    window.previewCatExtraImage = function(e, slot) {
        var file = e.target.files[0];
        if (!file) return;
        catGalleryStaging[slot] = file; // stage immediately at select-time
        var reader = new FileReader();
        reader.onload = function(ev) {
            var wrap = document.getElementById('cat-img-preview-' + slot);
            var img  = document.getElementById('cat-img-preview-img-' + slot);
            if (wrap && img) { img.src = ev.target.result; wrap.classList.remove('hidden'); }
        };
        reader.readAsDataURL(file);
    };

    // ── Variantes ────────────────────────────────────────────
    window.addCatVariantRow = function(name, options) {
        _catVariantRowIdx++;
        var idx = _catVariantRowIdx;
        var list = document.getElementById('cat-variants-list');
        var row = document.createElement('div');
        row.className = 'flex gap-2 items-center';
        row.id = 'cat-variant-row-' + idx;
        row.innerHTML =
            '<input type="text" placeholder="Nombre (Talla, Color...)" value="' + (name || '') + '"' +
            '       class="py-1.5 px-2 text-sm bg-layer border border-layer-line rounded-lg text-foreground placeholder:text-muted-foreground-1 flex-1" ' +
            '       id="cat-variant-name-' + idx + '" onchange="serializeCatVariants()">' +
            '<input type="text" placeholder="Opciones: S,M,L,XL" value="' + (options || '') + '"' +
            '       class="py-1.5 px-2 text-sm bg-layer border border-layer-line rounded-lg text-foreground placeholder:text-muted-foreground-1 flex-1" ' +
            '       id="cat-variant-options-' + idx + '" onchange="serializeCatVariants()">' +
            '<button type="button" onclick="removeCatVariantRow(' + idx + ')" ' +
            '        class="shrink-0 size-7 flex items-center justify-center rounded-lg text-red-500 hover:bg-red-50 transition-colors cursor-pointer">' +
            '    <span class="iconify tabler--trash size-4"></span>' +
            '</button>';
        list.appendChild(row);
    };

    window.removeCatVariantRow = function(idx) {
        var row = document.getElementById('cat-variant-row-' + idx);
        if (row) row.remove();
        serializeCatVariants();
    };

    window.serializeCatVariants = function() {
        var list = document.getElementById('cat-variants-list');
        var rows = list.querySelectorAll('[id^="cat-variant-row-"]');
        var result = [];
        rows.forEach(function(row) {
            var id = row.id.replace('cat-variant-row-', '');
            var nameEl    = document.getElementById('cat-variant-name-' + id);
            var optionsEl = document.getElementById('cat-variant-options-' + id);
            if (!nameEl || !optionsEl) return;
            var name    = nameEl.value.trim();
            var options = optionsEl.value.split(',').map(function(o) { return o.trim(); }).filter(Boolean);
            if (name) result.push({ name: name, options: options });
        });
        document.getElementById('cat-variants-json').value = JSON.stringify(result);
    };

    // ── Editar producto existente ─────────────────────────────
    window.editCatProduct = function(id) {
        fetch('/api/tenants/{{ $tenant->id }}/products/' + id, {
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            var p = res.data || res; // API returns { success, data: {...} }
            document.getElementById('cat-product-modal-title').innerHTML =
                '<span class="iconify tabler--pencil size-4 opacity-80"></span> Editar Producto';
            document.getElementById('cat-product-id').value = p.id;
            catGalleryStaging = {}; // clear staged files when opening edit mode
            @if($tenant->isVision())
            updateCatGalleryCounter((p.gallery_images || []).length || p.gallery_count || 0);
            @endif
            document.getElementById('cat-product-name').value = p.name || '';
            document.getElementById('cat-product-category').value = p.category_name || '';
            updateSubcategorySelect();
            document.getElementById('cat-product-subcategory').value = p.subcategory_name || '';
            document.getElementById('cat-product-price').value = p.price_usd || '';
            document.getElementById('cat-product-compare-price').value = p.compare_price_usd || '';
            document.getElementById('cat-product-description').value = p.description || '';
            document.getElementById('cat-product-badge').value = p.badge || '';
            document.getElementById('cat-product-is-active').checked = !!p.is_active;
            document.getElementById('cat-product-is-featured').checked = !!p.is_featured;

            // Imagen
            if (p.image_filename) {
                document.getElementById('cat-product-image-preview-img').src =
                    '/storage/tenants/{{ $tenant->id }}/' + p.image_filename;
                document.getElementById('cat-product-image-preview-img').style.cursor = 'pointer';
                document.getElementById('cat-product-image-preview-img').onclick = function() {
                    document.getElementById('cat-product-image').click();
                };
                document.getElementById('cat-product-image-preview').style.display = '';
                document.getElementById('cat-product-upload-zone').style.display = 'none';
            } else {
                document.getElementById('cat-product-image-preview').style.display = 'none';
                document.getElementById('cat-product-upload-zone').style.display = '';
            }

            // Variantes
            var list = document.getElementById('cat-variants-list');
            list.innerHTML = '';
            _catVariantRowIdx = 0;
            var variants = p.variants || [];
            variants.forEach(function(v) {
                addCatVariantRow(v.name, (v.options || []).join(','));
            });
            serializeCatVariants();

            var modal = document.getElementById('cat-product-modal');
            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        })
        .catch(function(e) { console.error('editCatProduct error', e); });
    };

    // ── Galería extra (solo Plan Visión) ───────────────────
    @if($tenant->isVision())
    function updateCatGalleryCounter(count) {
        _catGalleryCount = count;
        var maxGallery = {{ $maxImages - 1 }};
        var counter = document.getElementById('cat-gallery-counter');
        var area    = document.getElementById('cat-extra-images-area');
        if (counter) {
            if (count >= maxGallery) {
                counter.textContent = 'Límite alcanzado (' + maxGallery + '/' + maxGallery + ')';
                counter.classList.add('text-amber-600');
                counter.classList.remove('text-muted-foreground-1');
            } else {
                counter.textContent = count + ' / ' + maxGallery + ' en galería';
                counter.classList.remove('text-amber-600');
            }
        }
        if (area) {
            area.querySelectorAll('input[type="file"]').forEach(function(inp) {
                inp.disabled = (count >= maxGallery);
                var wrap = inp.closest('div.flex.flex-col');
                if (wrap) wrap.style.opacity = (count >= maxGallery) ? '0.4' : '';
            });
        }
    }

    async function uploadCatGalleryImages(productId) {
        console.log('[CAT gallery] uploadCatGalleryImages called — productId:', productId, '| staged slots:', Object.keys(catGalleryStaging));
        var csrf = document.querySelector('meta[name="csrf-token"]').content;
        @for($i = 2; $i <= $maxImages; $i++)
        var _file{{ $i }} = catGalleryStaging[{{ $i }}] || null;
        console.log('[CAT gallery] slot {{ $i }} staged file:', _file{{ $i }} ? _file{{ $i }}.name + ' (' + _file{{ $i }}.size + ' bytes)' : 'none');
        if (_file{{ $i }}) {
            var _fd{{ $i }} = new FormData();
            _fd{{ $i }}.append('image', _file{{ $i }});
            var _url{{ $i }} = '/tenant/{{ $tenant->id }}/upload/product/' + productId + '/gallery';
            try {
                var _res{{ $i }} = await fetch(_url{{ $i }}, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf },
                    body: _fd{{ $i }}
                });
                var _result{{ $i }} = await _res{{ $i }}.json();
                console.log('[CAT gallery] slot {{ $i }}:', _res{{ $i }}.status, _result{{ $i }});
                if (_result{{ $i }}.success) {
                    if (typeof _result{{ $i }}.gallery_count !== 'undefined') {
                        updateCatGalleryCounter(_result{{ $i }}.gallery_count);
                    }
                } else {
                    console.warn('CAT gallery upload failed slot {{ $i }}:', _result{{ $i }}.error);
                }
            } catch (_err{{ $i }}) {
                console.error('CAT gallery upload error slot {{ $i }}:', _err{{ $i }});
            }
        }
        @endfor
        catGalleryStaging = {}; // clear after upload
        console.log('[CAT gallery] uploadCatGalleryImages done');
    }
    @endif

    // ── Guardar (create/update) ──────────────────────────────
    window.saveCatProduct = function(e) {
        e.preventDefault();
        serializeCatVariants();

        var id      = document.getElementById('cat-product-id').value;
        var formData = new FormData();
        formData.append('name',             document.getElementById('cat-product-name').value);
        formData.append('category_name',    document.getElementById('cat-product-category').value);
        formData.append('subcategory_name', document.getElementById('cat-product-subcategory').value);
        formData.append('price_usd',        document.getElementById('cat-product-price').value);
        formData.append('compare_price_usd',document.getElementById('cat-product-compare-price').value);
        formData.append('description',      document.getElementById('cat-product-description').value);
        formData.append('badge',            document.getElementById('cat-product-badge').value);
        formData.append('is_active',        document.getElementById('cat-product-is-active').checked ? '1' : '0');
        formData.append('is_featured',      document.getElementById('cat-product-is-featured').checked ? '1' : '0');
        formData.append('variants',         document.getElementById('cat-variants-json').value);

        var mainImg = document.getElementById('cat-product-image');
        if (mainImg && mainImg.files[0]) formData.append('image', mainImg.files[0]);

        // Extra images
        @for($i = 2; $i <= $maxImages; $i++)
        var img{{ $i }} = document.getElementById('cat-product-image-{{ $i }}');
        if (img{{ $i }} && img{{ $i }}.files[0]) formData.append('gallery_{{ $i }}', img{{ $i }}.files[0]);
        @endfor

        if (id) formData.append('_method', 'PUT');
        var url = id
            ? '/tenant/{{ $tenant->id }}/products/' + id
            : '/tenant/{{ $tenant->id }}/products';
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

        var btn = e.target.querySelector('[type="submit"]');
        btn.disabled = true;

        fetch(url, { method: 'POST', body: formData })
        .then(function(r) {
            return r.text().then(function(text) {
                var data;
                try { data = JSON.parse(text); } catch (e) { data = { success: false, message: text || 'Respuesta inválida del servidor' }; }
                if (!r.ok) {
                    data.success = false;
                }
                return data;
            });
        })
        .then(async function(data) {
            if (data.success) {
                @if($tenant->isVision())
                var _savedId = (data.product && data.product.id) ? data.product.id : parseInt(id);
                // Upload imagen principal si hay archivo nuevo
                var _mainImg = document.getElementById('cat-product-image');
                if (_mainImg && _mainImg.files[0]) {
                    var _mainFd = new FormData();
                    _mainFd.append('image', _mainImg.files[0]);
                    _mainFd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                    await fetch('/tenant/{{ $tenant->id }}/upload/product/' + _savedId, {
                        method: 'POST',
                        body: _mainFd
                    });
                }
                uploadCatGalleryImages(_savedId).then(function() {
                    closeCatProductModal();
                    dashboardReload ? dashboardReload() : location.reload();
                });
                @else
                var _savedId = (data.product && data.product.id) ? data.product.id : parseInt(id);
                var _mainImg = document.getElementById('cat-product-image');
                if (_mainImg && _mainImg.files[0]) {
                    var _mainFd = new FormData();
                    _mainFd.append('image', _mainImg.files[0]);
                    _mainFd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                    await fetch('/tenant/{{ $tenant->id }}/upload/product/' + _savedId, {
                        method: 'POST',
                        body: _mainFd
                    });
                }
                closeCatProductModal();
                dashboardReload ? dashboardReload() : location.reload();
                @endif
            } else {
                alert(data.message || 'Error al guardar');
                btn.disabled = false;
            }
        })
        .catch(function() { alert('Error de red'); btn.disabled = false; });
    };

    // ── Cerrar con Escape ─────────────────────────────────────
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeCatProductModal();
    });

    // ── Vista lista/grid + paginación + búsqueda ────────────
    var _catView = localStorage.getItem('cat-view') || 'grid';
    var _catPage = 1;
    var _catPerPage = 25;
    var _catSearch = '';
    var _catAllCards = [];

    function catInitProducts() {
        _catAllCards = Array.from(document.querySelectorAll('#cat-grid-view > .group'));
        catSetView(_catView, false);
        catRenderPage();
    }

    window.catSetView = function(view, save) {
        _catView = view;
        if (save !== false) localStorage.setItem('cat-view', view);
        var gridView = document.getElementById('cat-grid-view');
        var listView = document.getElementById('cat-list-view');
        var btnG = document.getElementById('cat-view-grid');
        var btnL = document.getElementById('cat-view-list');
        if (view === 'list') {
            if (gridView) gridView.style.display = 'none';
            if (listView) listView.style.display = '';
            if (btnG) btnG.className = btnG.className.replace('bg-primary text-primary-foreground', 'bg-layer text-muted-foreground-1 hover:bg-layer-hover');
            if (btnL) btnL.className = btnL.className.replace('bg-layer text-muted-foreground-1 hover:bg-layer-hover', 'bg-primary text-primary-foreground');
        } else {
            if (gridView) gridView.style.display = '';
            if (listView) listView.style.display = 'none';
            if (btnG) btnG.className = btnG.className.replace('bg-layer text-muted-foreground-1 hover:bg-layer-hover', 'bg-primary text-primary-foreground');
            if (btnL) btnL.className = btnL.className.replace('bg-primary text-primary-foreground', 'bg-layer text-muted-foreground-1 hover:bg-layer-hover');
        }
        _catPage = 1;
        catRenderPage();
    };

    window.catFilterProducts = function(q) {
        _catSearch = q.toLowerCase();
        _catPage = 1;
        catRenderPage();
    };

    window.catChangePage = function(dir) {
        var isGrid = _catView !== 'list';
        var cards = isGrid
            ? Array.from(document.querySelectorAll('#cat-grid-view > .group'))
            : Array.from(document.querySelectorAll('#cat-list-view > div'));
        var filtered = cards.filter(function(c) {
            if (!_catSearch) return true;
            var nameEl = c.querySelector('span.text-sm, h4');
            return nameEl && nameEl.textContent.toLowerCase().includes(_catSearch);
        });
        var total = Math.ceil(filtered.length / _catPerPage) || 1;
        _catPage = Math.min(Math.max(_catPage + dir, 1), total);
        catRenderPage();
    };

    function catRenderPage() {
        var isGrid = _catView !== 'list';
        var cards = isGrid
            ? Array.from(document.querySelectorAll('#cat-grid-view > .group'))
            : Array.from(document.querySelectorAll('#cat-list-view > div'));
        var filtered = cards.filter(function(c) {
            if (!_catSearch) return true;
            var nameEl = c.querySelector('span.text-sm, h4');
            return nameEl && nameEl.textContent.toLowerCase().includes(_catSearch);
        });
        var total = Math.ceil(filtered.length / _catPerPage) || 1;
        var start = (_catPage - 1) * _catPerPage;
        cards.forEach(function(c) { c.style.display = 'none'; });
        filtered.slice(start, start + _catPerPage).forEach(function(c) { c.style.display = ''; });
        var info = document.getElementById('cat-page-info');
        var num  = document.getElementById('cat-page-num');
        var prev = document.getElementById('cat-prev-btn');
        var next = document.getElementById('cat-next-btn');
        if (info) info.textContent = 'Mostrando ' + Math.min(start+1, filtered.length) + '-' + Math.min(start+_catPerPage, filtered.length) + ' de ' + filtered.length;
        if (num)  num.textContent  = _catPage + ' / ' + total;
        if (prev) prev.disabled = _catPage <= 1;
        if (next) next.disabled = _catPage >= total;
        var pag = document.getElementById('cat-pagination');
        if (pag) pag.style.display = filtered.length <= _catPerPage ? 'none' : 'flex';
    }

    // Inicializar cuando el tab productos es visible
    (function() {
        var _initialized = false;
        function tryInit() {
            var tab = document.getElementById('tab-productos');
            if (!tab || tab.classList.contains('hidden') || (!tab.classList.contains('active') && tab.offsetParent === null)) return;
            if (_initialized) return;
            _initialized = true;
            catInitProducts();
        }
        // Intentar inmediato + observer para cuando el tab se activa
        setTimeout(tryInit, 100);
        var observer = new MutationObserver(tryInit);
        observer.observe(document.body, { attributes: true, subtree: true, attributeFilter: ['style','class'] });
    })();
})();
</script>
