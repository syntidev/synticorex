{{-- ══ Servicios (dentro del tab "Qué Vendes") ══════════════════════════ --}}
            @php
                $maxServices = (int) ($plan->services_limit ?? 3);
                $currentServiceCount = $services->count();
            @endphp

            {{-- Global display mode selector (Plan 2/3 only) --}}
            @if($plan->id !== 1)
            <div class="flex p-4 rounded-lg border bg-blue-50 border-blue-200 text-blue-800 mb-4 items-center justify-between gap-4 flex-wrap">
                <div>
                    <p class="font-semibold text-sm">Modo visual de servicios</p>
                    <p class="text-xs opacity-70">Elige entre íconos o imágenes — mantén la coherencia estética</p>
                </div>
                <div class="flex [&>*]:rounded-none [&>*:first-child]:rounded-l-lg [&>*:last-child]:rounded-r-lg">
                    <button type="button" id="global-mode-icon-btn"
                            onclick="setGlobalServiceMode('icon')"
                            class="inline-flex items-center text-sm py-1.5 px-3 font-medium transition-colors bg-blue-600 text-white hover:bg-blue-700">
                        <span class="iconify tabler--palette size-4" aria-hidden="true"></span> Ícono
                    </button>
                    <button type="button" id="global-mode-image-btn"
                            onclick="setGlobalServiceMode('image')"
                            class="inline-flex items-center text-sm py-1.5 px-3 font-medium transition-colors text-foreground hover:bg-muted-hover">
                        <span class="iconify tabler--photo size-4" aria-hidden="true"></span> Imagen
                    </button>
                </div>
            </div>
            @endif

            {{-- ── Servicios card ──────────────────────────────────── --}}
            <div class="p-6">
            <div class="bg-surface rounded-xl shadow-sm border border-border">
                <div class="px-6 pt-6 pb-4 flex items-center justify-between gap-3 flex-wrap">
                    <div>
                        <h2 class="font-semibold text-foreground flex items-center gap-2">
                            <span class="iconify tabler--tool size-5 text-secondary" aria-hidden="true"></span>
                            Servicios
                        </h2>
                        <p class="text-xs text-muted-foreground-1 mt-0.5">{{ $currentServiceCount }} de {{ $maxServices }} servicios</p>
                    </div>
                    <button class="inline-flex items-center text-sm py-1.5 px-3 rounded-lg font-medium transition-colors bg-primary text-white hover:bg-primary-hover gap-1.5"
                            onclick="checkAndOpenServiceModal()"
                            title="Agregar nuevo servicio">
                        <span class="iconify tabler--plus size-4" aria-hidden="true"></span>
                        Agregar Servicio
                    </button>
                </div>
                <div class="border-t border-border"></div>
                <div class="px-6 pb-6">
                @if($currentServiceCount >= $maxServices)
                <div class="flex p-4 rounded-lg border bg-blue-50 border-blue-200 text-blue-800 mb-2 items-center justify-between gap-4 flex-wrap">
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
                       class="inline-flex items-center text-sm py-1.5 px-3 rounded-lg font-medium transition-colors bg-blue-600 text-white hover:bg-blue-700 shrink-0">Ver Planes ↗</a>
                </div>
                @endif

                @if($services->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($services as $service)
                        <div class="rounded-xl border border-border bg-surface p-4 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 group">
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
                                        <span class="iconify tabler--tool size-6 text-muted-foreground-2" aria-hidden="true"></span>
                                    @endif
                                </div>
                                {{-- Name + Status --}}
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-semibold text-foreground truncate">{{ $service->name }}</h4>
                                    <p class="text-xs text-muted-foreground-1 line-clamp-2 mt-0.5">
                                        {{ $service->description ? Str::limit($service->description, 80) : '—' }}
                                    </p>
                                </div>
                                @if(!$service->is_active)
                                    <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-red-100 text-red-700 shrink-0">Off</span>
                                @endif
                            </div>
                            {{-- Actions --}}
                            <div class="flex gap-2 mt-3 pt-3 border-t border-border">
                                <button onclick="editService({{ $service->id }})"
                                        class="inline-flex items-center text-sm py-1.5 px-3 rounded-lg font-medium transition-colors bg-primary text-white hover:bg-primary-hover flex-1 gap-1.5" title="Editar">
                                    <span class="iconify tabler--pencil size-4" aria-hidden="true"></span> Editar
                                </button>
                                <button onclick="deleteService({{ $service->id }})"
                                        class="p-1.5 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Eliminar">
                                    <span class="iconify tabler--trash size-4" aria-hidden="true"></span>
                                </button>
                            </div>
                        </div>
                        @endforeach
                </div>
                @else
                <x-dashboard.empty-state
                    icon="tools"
                    title="Sin servicios aún"
                    message="Agrega un servicio para mostrarlo a tus clientes." />
                @endif
                </div>{{-- /px-6 pb-6 --}}
            </div>
            </div>{{-- /p-6 --}}

        </div>{{-- /tab-productos --}}

