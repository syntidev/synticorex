        <!-- Tab: Servicios -->
        <div id="tab-servicios" class="tab-content">
            @php
                $maxServices = (int) ($plan->services_limit ?? 3);
                $currentServiceCount = $services->count();
            @endphp

            {{-- Global display mode selector (Plan 2/3 only) --}}
            @if($plan->id !== 1)
            <div class="alert alert-info mb-4 flex items-center justify-between gap-4 flex-wrap">
                <div>
                    <p class="font-semibold text-sm">Modo visual de servicios</p>
                    <p class="text-xs opacity-70">Elige entre íconos o imágenes — mantén la coherencia estética</p>
                </div>
                <div class="join">
                    <button type="button" id="global-mode-icon-btn"
                            onclick="setGlobalServiceMode('icon')"
                            class="btn btn-sm join-item btn-primary">
                        <span class="iconify tabler--palette size-4" aria-hidden="true"></span> Ícono
                    </button>
                    <button type="button" id="global-mode-image-btn"
                            onclick="setGlobalServiceMode('image')"
                            class="btn btn-sm join-item btn-ghost">
                        <span class="iconify tabler--photo size-4" aria-hidden="true"></span> Imagen
                    </button>
                </div>
            </div>
            @endif

            {{-- ── Servicios card ──────────────────────────────────── --}}
            <div class="card bg-base-100 shadow-sm border border-base-content/10 mb-4">
                <div class="card-header flex items-center justify-between gap-3 flex-wrap">
                    <div>
                        <h2 class="card-title flex items-center gap-2">
                            <span class="iconify tabler--tool size-5 text-secondary" aria-hidden="true"></span>
                            Servicios
                        </h2>
                        <p class="text-xs text-base-content/50 mt-0.5">{{ $currentServiceCount }} de {{ $maxServices }} servicios</p>
                    </div>
                    <button class="btn btn-secondary btn-sm gap-1.5"
                            onclick="checkAndOpenServiceModal()"
                            title="Agregar nuevo servicio">
                        <span class="iconify tabler--plus size-4" aria-hidden="true"></span>
                        Agregar Servicio
                    </button>
                </div>

                @if($currentServiceCount >= $maxServices)
                <div class="alert alert-info mx-4 mb-2 flex items-center justify-between gap-4 flex-wrap">
                    <div class="flex items-center gap-3">
                        <span class="iconify tabler--info-circle size-5 shrink-0" aria-hidden="true"></span>
                        <div>
                            <p class="font-semibold text-sm">Límite alcanzado ({{ $currentServiceCount }}/{{ $maxServices }} servicios)</p>
                            <p class="text-xs opacity-70">
                                @if($plan->id === 1)Plan CRECIMIENTO: hasta 6 · Plan VISIÓN: hasta 9
                                @else Plan VISIÓN: hasta 9 servicios @endif
                            </p>
                        </div>
                    </div>
                    <a href="https://syntiweb.com/planes" target="_blank" rel="noopener noreferrer"
                       class="btn btn-primary btn-sm shrink-0">Ver Planes ↗</a>
                </div>
                @endif

                @if($services->count() > 0)
                <div class="card-body pt-2">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($services as $service)
                        <div class="rounded-lg border border-base-content/10 bg-base-200/30 p-3 transition-all hover:border-secondary/30 hover:shadow-sm">
                            <div class="flex items-start gap-3 mb-2">
                                {{-- Icon/Image --}}
                                <div class="size-12 rounded-lg shrink-0 flex items-center justify-center overflow-hidden
                                    {{ $service->image_filename ? '' : 'bg-secondary/10 border border-secondary/20' }}">
                                    @if($service->image_filename)
                                        <img src="{{ asset('storage/tenants/' . $tenant->id . '/' . $service->image_filename) }}"
                                             alt="{{ $service->name }}"
                                             class="w-full h-full object-cover rounded-lg"
                                             loading="lazy" decoding="async">
                                    @elseif($service->icon_name)
                                        <span class="iconify tabler--{{ str_replace('_', '-', $service->icon_name) }} text-secondary text-xl"></span>
                                    @else
                                        <span class="iconify tabler--tool size-6 text-base-content/30" aria-hidden="true"></span>
                                    @endif
                                </div>
                                {{-- Name + Status --}}
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-semibold text-base-content truncate">{{ $service->name }}</h4>
                                    <p class="text-xs text-base-content/50 line-clamp-2 mt-0.5">
                                        {{ $service->description ? Str::limit($service->description, 80) : '—' }}
                                    </p>
                                </div>
                                @if(!$service->is_active)
                                    <span class="badge badge-soft badge-error badge-xs shrink-0">Off</span>
                                @endif
                            </div>
                            {{-- Actions --}}
                            <div class="flex gap-2">
                                <button onclick="editService({{ $service->id }})"
                                        class="btn btn-secondary btn-sm btn-square" title="Editar">
                                    <span class="iconify tabler--pencil size-5" aria-hidden="true"></span>
                                </button>
                                <button onclick="deleteService({{ $service->id }})"
                                        class="btn btn-error btn-sm btn-square" title="Eliminar">
                                    <span class="iconify tabler--trash size-5" aria-hidden="true"></span>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="card-body flex flex-col items-center justify-center py-10 text-center">
                    <span class="iconify tabler--tool size-10 text-base-content/20 mb-2" aria-hidden="true"></span>
                    <h3 class="font-semibold text-sm text-base-content/60 mb-1">No hay servicios aún</h3>
                    <p class="text-xs text-base-content/40">Comienza agregando tu primer servicio</p>
                </div>
                @endif
            </div>
        </div>

