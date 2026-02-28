        <!-- Tab: Productos -->
        <div id="tab-productos" class="tab-content">
            @php
                $maxProducts = (int) ($plan->products_limit ?? 6);
                $currentCount = $products->count();
            @endphp

            {{-- ── Productos card ─────────────────────────────────── --}}
            <div class="card bg-base-100 shadow-sm border border-base-content/10 mb-4">
                <div class="card-header flex items-center justify-between gap-3 flex-wrap">
                    <div>
                        <h2 class="card-title flex items-center gap-2">
                            <span class="iconify tabler--package size-5 text-primary" aria-hidden="true"></span>
                            Productos
                        </h2>
                        <p class="text-xs text-base-content/50 mt-0.5">{{ $currentCount }} de {{ $maxProducts }} productos</p>
                    </div>
                    <button class="btn btn-primary btn-sm gap-1.5"
                            onclick="checkAndOpenProductModal()"
                            title="Agregar nuevo producto">
                        <span class="iconify tabler--plus size-4" aria-hidden="true"></span>
                        Agregar Producto
                    </button>
                </div>

                @if($currentCount >= $maxProducts)
                <div class="alert alert-info mx-4 mb-2 flex items-center justify-between gap-4 flex-wrap">
                    <div class="flex items-center gap-3">
                        <span class="iconify tabler--info-circle size-5 shrink-0" aria-hidden="true"></span>
                        <div>
                            <p class="font-semibold text-sm">Límite alcanzado ({{ $maxProducts }}/{{ $maxProducts }} productos)</p>
                            <p class="text-xs opacity-70">
                                @if($plan->id === 1)Plan CRECIMIENTO: hasta 12 · Plan VISIÓN: hasta 18
                                @else Plan VISIÓN: hasta 18 productos @endif
                            </p>
                        </div>
                    </div>
                    <a href="https://syntiweb.com/planes" target="_blank" rel="noopener noreferrer"
                       class="btn btn-primary btn-sm shrink-0">Ver Planes ↗</a>
                </div>
                @endif

                @if($products->count() > 0)
                <div class="card-body pt-2">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($products as $product)
                        <div class="group relative rounded-lg border border-base-content/10 bg-base-200/30 overflow-hidden transition-all hover:border-primary/30 hover:shadow-sm">
                            {{-- Imagen thumbnail --}}
                            <div class="h-32 bg-base-200 flex items-center justify-center overflow-hidden">
                                @if($product->image_filename)
                                    <img src="{{ asset('storage/tenants/' . $tenant->id . '/' . $product->image_filename) }}"
                                         alt="{{ $product->name }}"
                                         class="w-full h-full object-cover"
                                         loading="lazy" decoding="async">
                                @else
                                    <span class="iconify tabler--package size-10 text-base-content/20" aria-hidden="true"></span>
                                @endif
                            </div>
                            {{-- Info --}}
                            <div class="p-3">
                                <div class="flex items-start justify-between gap-2 mb-1">
                                    <h4 class="text-sm font-semibold text-base-content truncate flex-1">{{ $product->name }}</h4>
                                    @if(!$product->is_active)
                                        <span class="badge badge-soft badge-error badge-xs shrink-0">Off</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-base font-bold text-primary">${{ number_format($product->price_usd, 2) }}</span>
                                    @if($product->badge === 'hot')
                                        <span class="badge badge-soft badge-error badge-xs">🔥 Hot</span>
                                    @elseif($product->badge === 'new')
                                        <span class="badge badge-soft badge-success badge-xs">✨ New</span>
                                    @elseif($product->badge === 'promo')
                                        <span class="badge badge-soft badge-warning badge-xs">🎉 Promo</span>
                                    @endif
                                    @if($product->is_featured)
                                        <span class="badge badge-soft badge-warning badge-xs">⭐</span>
                                    @endif
                                </div>
                                {{-- Actions --}}
                                <div class="flex gap-2">
                                    <button onclick="editProduct({{ $product->id }})"
                                            class="btn btn-primary btn-sm btn-square" title="Editar">
                                        <span class="iconify tabler--pencil size-5" aria-hidden="true"></span>
                                    </button>
                                    <button onclick="deleteProduct({{ $product->id }})"
                                            class="btn btn-error btn-sm btn-square" title="Eliminar">
                                        <span class="iconify tabler--trash size-5" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="card-body flex flex-col items-center justify-center py-10 text-center">
                    <span class="iconify tabler--package size-10 text-base-content/20 mb-2" aria-hidden="true"></span>
                    <h3 class="font-semibold text-sm text-base-content/60 mb-1">No hay productos aún</h3>
                    <p class="text-xs text-base-content/40">Comienza agregando tu primer producto</p>
                </div>
                @endif
            </div>
        </div>

