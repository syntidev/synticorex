        <!-- Tab: Qué Vendes (Productos + Servicios) -->
        <div id="tab-productos" class="tab-content">
            @php
                $maxProducts = $maxItems ?? (int) ($plan->products_limit ?? 6);
                $currentCount = $products->count();
                $dynLabel = $itemLabel ?? 'Productos';
                $dynSingular = $itemSingular ?? 'Producto';
            @endphp

            {{-- ── Productos card ─────────────────────────────────── --}}
            <div class="p-6">
            <div class="bg-surface rounded-xl shadow-sm border border-border">
                <div class="px-6 pt-6 pb-4 flex items-center justify-between gap-3 flex-wrap">
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <div class="size-10 rounded-xl bg-primary/10 flex items-center justify-center">
                                <span class="iconify tabler--package size-5 text-primary" aria-hidden="true"></span>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-foreground">{{ $dynLabel }}</h2>
                                <p class="text-xs text-muted-foreground-1">{{ $currentCount }} de {{ $maxProducts }} {{ strtolower($dynLabel) }} activos</p>
                            </div>
                        </div>
                    </div>
                    <button class="inline-flex items-center text-sm py-1.5 px-3 rounded-lg font-medium transition-all bg-blue-600 text-white hover:bg-blue-700 gap-1.5 shadow-sm hover:shadow-lg"
                            onclick="checkAndOpenProductModal()"
                            title="Agregar nuevo producto">
                        <span class="iconify tabler--plus size-4" aria-hidden="true"></span>
                        Agregar {{ $dynSingular }}
                    </button>
                </div>
                <div class="border-t border-border"></div>
                <div class="px-6 pb-6">
                @if($currentCount >= $maxProducts)
                <div class="flex p-4 rounded-lg border bg-blue-50 border-blue-200 text-blue-800 mb-2 items-center justify-between gap-4 flex-wrap">
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
                       class="inline-flex items-center text-sm py-1.5 px-3 rounded-lg font-medium transition-colors bg-blue-600 text-white hover:bg-blue-700 shrink-0">Ver Planes ↗</a>
                </div>
                @endif

                @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($products as $product)
                        <div class="group relative rounded-xl border border-border bg-surface overflow-hidden shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                            {{-- Imagen thumbnail con overlay --}}
                            <figure class="relative h-40 bg-layer overflow-hidden">
                                @if($product->image_filename)
                                    <img src="{{ asset('storage/tenants/' . $tenant->id . '/' . $product->image_filename) }}"
                                         alt="{{ $product->name }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                         loading="lazy" decoding="async">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="iconify tabler--package size-12 text-foreground/15" aria-hidden="true"></span>
                                    </div>
                                @endif
                                {{-- Hover overlay con acciones --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-3 gap-2">
                                    <button onclick="editProduct({{ $product->id }})"
                                            class="inline-flex items-center text-sm py-1.5 px-3 rounded-lg font-medium transition-colors bg-blue-600 text-white hover:bg-blue-700 shadow-lg" title="Editar">
                                        <span class="iconify tabler--pencil size-4" aria-hidden="true"></span> Editar
                                    </button>
                                    <button onclick="deleteProduct({{ $product->id }})"
                                            class="inline-flex items-center text-sm py-1.5 px-3 rounded-lg font-medium transition-colors bg-red-600 text-white hover:bg-red-700 shadow-lg" title="Eliminar">
                                        <span class="iconify tabler--trash size-4" aria-hidden="true"></span>
                                    </button>
                                </div>
                                {{-- Badges flotantes --}}
                                @if($product->is_featured)
                                <div class="absolute top-2 left-2">
                                    <span class="inline-flex items-center py-0.5 px-2 rounded-full text-sm font-medium bg-yellow-100 text-yellow-700 shadow-md">⭐ Destacado</span>
                                </div>
                                @endif
                                @if(!$product->is_active)
                                <div class="absolute top-2 right-2">
                                    <span class="inline-flex items-center py-0.5 px-2 rounded-full text-sm font-medium bg-red-100 text-red-700 shadow-md">Off</span>
                                </div>
                                @endif
                            </figure>
                            {{-- Info --}}
                            <div class="p-4">
                                <h4 class="text-sm font-bold text-foreground line-clamp-2 mb-1.5">{{ $product->name }}</h4>
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-extrabold text-primary">${{ number_format($product->price_usd, 2) }}</span>
                                    <div class="flex gap-1">
                                        @if($product->badge === 'hot')
                                            <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-red-100 text-red-700">🔥 Hot</span>
                                        @elseif($product->badge === 'new')
                                            <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-green-100 text-green-700">✨ New</span>
                                        @elseif($product->badge === 'promo')
                                            <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">🎉 Promo</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                </div>
                @else
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="size-16 rounded-2xl bg-primary/5 flex items-center justify-center mb-4">
                        <span class="iconify tabler--package size-8 text-primary/30" aria-hidden="true"></span>
                    </div>
                    <h3 class="font-bold text-base text-muted-foreground-1 mb-1">No hay {{ strtolower($dynLabel) }} aún</h3>
                    <p class="text-sm text-muted-foreground-1 mb-4">Comienza agregando tu primer {{ strtolower($dynSingular) }}</p>
                    <button onclick="checkAndOpenProductModal()" class="inline-flex items-center text-sm py-1.5 px-3 rounded-lg font-medium transition-colors bg-blue-600 text-white hover:bg-blue-700 gap-1.5 shadow-sm">
                        <span class="iconify tabler--plus size-4"></span> Agregar {{ $dynSingular }}
                    </button>
                </div>
                @endif
                </div>{{-- /px-6 pb-6 --}}
            </div>
            </div>{{-- /p-6 --}}

{{-- ══════════════════════════════════════════════════════════════════════
     Divider visual between Products and Services (same tab)
══════════════════════════════════════════════════════════════════════ --}}

