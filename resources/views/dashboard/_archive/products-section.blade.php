        <!-- Tab: {{ $itemLabel ?? 'Productos' }} -->
        <div id="tab-productos" class="tab-content">
            @php
                $maxProducts = $maxItems ?? (int) ($plan->products_limit ?? 6);
                $currentCount = $products->count();
                $dynLabel = $itemLabel ?? 'Productos';
                $dynSingular = $itemSingular ?? 'Producto';
            @endphp

            {{-- ── Productos card ─────────────────────────────────── --}}
            <div class="card bg-base-100 shadow-md border border-base-content/8 mb-6 card-elevated">
                <div class="card-header px-6 pt-6 pb-4 flex items-center justify-between gap-3 flex-wrap">
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <div class="size-10 rounded-xl bg-primary/10 flex items-center justify-center">
                                <span class="iconify tabler--package size-5 text-primary" aria-hidden="true"></span>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-base-content">{{ $dynLabel }}</h2>
                                <p class="text-xs text-base-content/50">{{ $currentCount }} de {{ $maxProducts }} {{ strtolower($dynLabel) }} activos</p>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-sm gap-1.5 shadow-md shadow-primary/20 hover:shadow-lg hover:shadow-primary/30 transition-all"
                            onclick="checkAndOpenProductModal()"
                            title="Agregar nuevo producto">
                        <span class="iconify tabler--plus size-4" aria-hidden="true"></span>
                        Agregar {{ $dynSingular }}
                    </button>
                </div>

                @if($currentCount >= $maxProducts)
                <div class="alert alert-info mx-4 mb-2 flex items-center justify-between gap-4 flex-wrap">
                    <div class="flex items-center gap-3">
                        <span class="iconify tabler--info-circle size-5 shrink-0" aria-hidden="true"></span>
                        <div>
                            <p class="font-semibold text-sm">Límite alcanzado ({{ $maxProducts }}/{{ $maxProducts }} {{ strtolower($dynLabel) }})</p>
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
                <div class="card-body px-6 pt-2 pb-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($products as $product)
                        <div class="group relative rounded-xl border border-base-content/8 bg-base-100 overflow-hidden shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                            {{-- Imagen thumbnail con overlay --}}
                            <figure class="relative h-40 bg-base-200 overflow-hidden">
                                @if($product->image_filename)
                                    <img src="{{ asset('storage/tenants/' . $tenant->id . '/' . $product->image_filename) }}"
                                         alt="{{ $product->name }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                         loading="lazy" decoding="async">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="iconify tabler--package size-12 text-base-content/15" aria-hidden="true"></span>
                                    </div>
                                @endif
                                {{-- Hover overlay con acciones --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-3 gap-2">
                                    <button onclick="editProduct({{ $product->id }})"
                                            class="btn btn-sm btn-primary shadow-lg" title="Editar">
                                        <span class="iconify tabler--pencil size-4" aria-hidden="true"></span> Editar
                                    </button>
                                    <button onclick="deleteProduct({{ $product->id }})"
                                            class="btn btn-sm btn-error shadow-lg" title="Eliminar">
                                        <span class="iconify tabler--trash size-4" aria-hidden="true"></span>
                                    </button>
                                </div>
                                {{-- Badges flotantes --}}
                                @if($product->is_featured)
                                <div class="absolute top-2 left-2">
                                    <span class="badge badge-warning badge-sm shadow-md">⭐ Destacado</span>
                                </div>
                                @endif
                                @if(!$product->is_active)
                                <div class="absolute top-2 right-2">
                                    <span class="badge badge-error badge-sm shadow-md">Off</span>
                                </div>
                                @endif
                            </figure>
                            {{-- Info --}}
                            <div class="p-4">
                                <h4 class="text-sm font-bold text-base-content line-clamp-2 mb-1.5">{{ $product->name }}</h4>
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-extrabold text-primary">${{ number_format($product->price_usd, 2) }}</span>
                                    <div class="flex gap-1">
                                        @if($product->badge === 'hot')
                                            <span class="badge badge-soft badge-error badge-xs">🔥 Hot</span>
                                        @elseif($product->badge === 'new')
                                            <span class="badge badge-soft badge-success badge-xs">✨ New</span>
                                        @elseif($product->badge === 'promo')
                                            <span class="badge badge-soft badge-warning badge-xs">🎉 Promo</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="card-body flex flex-col items-center justify-center py-16 text-center">
                    <div class="size-16 rounded-2xl bg-primary/5 flex items-center justify-center mb-4">
                        <span class="iconify tabler--package size-8 text-primary/30" aria-hidden="true"></span>
                    </div>
                    <h3 class="font-bold text-base text-base-content/70 mb-1">No hay {{ strtolower($dynLabel) }} aún</h3>
                    <p class="text-sm text-base-content/40 mb-4">Comienza agregando tu primer {{ strtolower($dynSingular) }}</p>
                    <button onclick="checkAndOpenProductModal()" class="btn btn-primary btn-sm gap-1.5 shadow-md">
                        <span class="iconify tabler--plus size-4"></span> Agregar {{ $dynSingular }}
                    </button>
                </div>
                @endif
            </div>
        </div>

