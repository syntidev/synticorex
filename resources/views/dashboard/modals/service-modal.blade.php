        <!-- Modal: Servicio -->
        <div id="service-modal" class="crud-overlay"
             role="dialog" aria-modal="true" aria-labelledby="service-modal-title" aria-hidden="true">
            <div class="crud-dialog max-w-lg">
                <div class="crud-dialog-header">
                    <h3 class="crud-dialog-title flex items-center gap-2" id="service-modal-title">
                        <span class="iconify tabler--briefcase size-4 opacity-80" aria-hidden="true"></span>
                        Agregar Servicio
                    </h3>
                    <button class="crud-dialog-close" onclick="closeServiceModal()" aria-label="Cerrar modal">
                        <span class="iconify tabler--x size-4" aria-hidden="true"></span>
                    </button>
                </div>
                <div class="crud-dialog-body">
                    <form id="service-form" onsubmit="saveService(event)" class="flex flex-col gap-3">
                        <input type="hidden" id="service-id">
                        <input type="hidden" id="service-icon-name">

                        {{-- Mode tabs: Plan 2/3 only --}}
                        @if($plan->id !== 1)
                        <div class="svc-segment w-full" role="tablist" aria-label="Modo de representación del servicio">
                            <button type="button" id="svc-tab-icon" role="tab"
                                class="seg-active flex-1 flex items-center justify-center gap-1.5 py-2 px-3 rounded-lg text-sm font-bold transition-all"
                                onclick="setServiceModalMode('icon')">
                                <span class="iconify tabler--color-picker size-4" aria-hidden="true"></span> Ícono
                            </button>
                            <button type="button" id="svc-tab-image" role="tab"
                                class="flex-1 flex items-center justify-center gap-1.5 py-2 px-3 rounded-lg text-sm font-bold transition-all"
                                onclick="setServiceModalMode('image')">
                                <span class="iconify tabler--photo-up size-4" aria-hidden="true"></span> Imagen
                            </button>
                        </div>
                        @endif

                        {{-- ICON PICKER --}}
                        <div id="svc-section-icon" class="flex flex-col gap-2">
                            <label class="inline-block text-xs font-semibold text-muted-foreground-1 uppercase tracking-wide">Ícono del Servicio</label>

                            {{-- Compact selection preview --}}
                            <div class="flex items-center gap-3 p-3 rounded-xl border" style="background:linear-gradient(135deg,var(--synti-soft) 0%,transparent 60%);border-color:var(--synti-bdr);">
                                <div class="size-12 rounded-xl flex items-center justify-center shrink-0 border-2" style="background:var(--synti-soft);border-color:var(--synti-bdr);">
                                    <span id="icon-preview-el" class="iconify tabler--settings size-7 text-primary"></span>
                                </div>
                                <div class="min-w-0">
                                    <p id="icon-preview-label" class="text-sm font-semibold text-foreground truncate">Sin ícono seleccionado</p>
                                    <p class="text-xs text-muted-foreground-1">Busca y selecciona un ícono abajo</p>
                                </div>
                            </div>

                            {{-- Search --}}
                            <input type="text" id="icon-search" class="py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus"
                                placeholder="Busca: scissors, camera, truck..."
                                oninput="filterIcons(this.value)" autocomplete="off">

                            {{-- Icon Grid --}}
                            <div id="icon-picker-grid" class="grid grid-cols-7 gap-2 max-h-44 overflow-y-auto p-2.5 rounded-lg bg-layer border border-border"></div>
                            <p class="text-xs text-muted-foreground-1 text-center">60+ iconos disponibles</p>
                            
                            {{-- Hidden div to force Tailwind to generate icon classes --}}
                            <div class="hidden">
                                <span class="iconify tabler--briefcase"></span><span class="iconify tabler--building-store"></span><span class="iconify tabler--award"></span>
                                <span class="iconify tabler--certificate"></span><span class="iconify tabler--crown"></span><span class="iconify tabler--diamond"></span>
                                <span class="iconify tabler--rocket"></span><span class="iconify tabler--target"></span><span class="iconify tabler--trophy"></span>
                                <span class="iconify tabler--star"></span><span class="iconify tabler--heart"></span><span class="iconify tabler--thumb-up"></span>
                                <span class="iconify tabler--shield-check"></span><span class="iconify tabler--rosette-discount-check"></span>
                                <span class="iconify tabler--tool"></span><span class="iconify tabler--hammer"></span><span class="iconify tabler--paint"></span>
                                <span class="iconify tabler--scissors"></span><span class="iconify tabler--needle-thread"></span><span class="iconify tabler--pencil-bolt"></span>
                                <span class="iconify tabler--bolt"></span><span class="iconify tabler--car"></span><span class="iconify tabler--home"></span>
                                <span class="iconify tabler--building"></span><span class="iconify tabler--bucket"></span><span class="iconify tabler--wash"></span>
                                <span class="iconify tabler--device-desktop"></span><span class="iconify tabler--device-mobile"></span><span class="iconify tabler--wifi"></span>
                                <span class="iconify tabler--cpu"></span><span class="iconify tabler--code"></span><span class="iconify tabler--cloud"></span>
                                <span class="iconify tabler--headset"></span><span class="iconify tabler--printer"></span>
                                <span class="iconify tabler--camera"></span><span class="iconify tabler--video"></span><span class="iconify tabler--microphone"></span>
                                <span class="iconify tabler--palette"></span><span class="iconify tabler--ballpen"></span><span class="iconify tabler--photo"></span>
                                <span class="iconify tabler--stethoscope"></span><span class="iconify tabler--first-aid-kit"></span><span class="iconify tabler--activity"></span>
                                <span class="iconify tabler--bath"></span><span class="iconify tabler--barbell"></span><span class="iconify tabler--leaf"></span>
                                <span class="iconify tabler--eye"></span><span class="iconify tabler--brain"></span>
                                <span class="iconify tabler--book"></span><span class="iconify tabler--school"></span><span class="iconify tabler--pencil"></span><span class="iconify tabler--flask"></span>
                                <span class="iconify tabler--soup"></span><span class="iconify tabler--pizza"></span><span class="iconify tabler--coffee"></span><span class="iconify tabler--apple"></span>
                                <span class="iconify tabler--shopping-cart"></span><span class="iconify tabler--package"></span><span class="iconify tabler--truck"></span><span class="iconify tabler--map-pin"></span>
                                <span class="iconify tabler--phone"></span><span class="iconify tabler--mail"></span><span class="iconify tabler--message-circle"></span>
                                <span class="iconify tabler--calendar"></span><span class="iconify tabler--clock"></span><span class="iconify tabler--users"></span><span class="iconify tabler--user-check"></span>
                                <span class="iconify tabler--settings"></span><span class="iconify tabler--tool"></span>
                            </div>
                        </div>

                        {{-- IMAGE UPLOAD (Plan 2/3 only; hidden by default) --}}
                        @if($plan->id !== 1)
                        <div id="svc-section-image" class="flex flex-col gap-2" style="display: none;">
                            <label class="inline-block text-xs font-semibold text-muted-foreground-1 uppercase tracking-wide">Imagen del Servicio</label>
                            <div class="image-preview" id="service-image-preview" style="display: none;">
                                <img id="service-image-preview-img" src="" alt="Preview">
                                <button type="button" onclick="cancelServiceImage()" class="p-1 rounded-full transition-colors text-foreground hover:bg-muted-hover absolute top-1 right-1">
                                    <span class="iconify tabler--x size-3.5" aria-hidden="true"></span>
                                </button>
                            </div>
                            <div id="service-upload-zone"
                                 class="border-2 border-dashed border-border rounded-lg p-3.5 text-center cursor-pointer hover:border-primary hover:bg-primary/5 transition-all"
                                 onclick="document.getElementById('service-image').click()"
                                 ondragover="event.preventDefault(); this.classList.add('border-primary','bg-primary/5')"
                                 ondragleave="this.classList.remove('border-primary','bg-primary/5')"
                                 ondrop="event.preventDefault(); this.classList.remove('border-primary','bg-primary/5'); document.getElementById('service-image').files = event.dataTransfer.files; previewServiceImage({target: document.getElementById('service-image')})">
                                <span class="iconify tabler--cloud-upload size-7 mx-auto text-muted-foreground-1 mb-1"></span>
                                <p class="text-sm font-medium text-foreground/70">Arrastra o <span class="text-primary font-bold">elige imagen</span></p>
                                <p class="text-xs text-muted-foreground-1 mt-0.5">PNG, JPG, WebP · Máx 2MB</p>
                            </div>
                            <input type="file" id="service-image" accept="image/*" class="hidden" onchange="previewServiceImage(event)">
                        </div>
                        @endif

                        <div class="grid grid-cols-2 gap-3">
                            <div class="col-span-2 sm:col-span-1">
                                <label for="service-name" class="inline-block text-xs font-semibold text-muted-foreground-1 uppercase tracking-wide mb-1.5">Nombre *</label>
                                <input type="text" id="service-name" class="py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50" required maxlength="100" placeholder="Nombre del servicio">
                            </div>
                            <label class="col-span-2 sm:col-span-1 flex flex-col gap-1.5 cursor-pointer p-2.5 rounded-lg bg-layer border border-layer-line hover:bg-layer-hover transition-colors justify-center">
                                <span class="text-xs font-semibold text-muted-foreground-1 uppercase tracking-wide">Activo</span>
                                <span class="flex items-center gap-2">
                                    <input type="checkbox" id="service-is-active"
                                           class="size-4 rounded-sm border-border text-primary focus:ring-primary/20 cursor-pointer" checked>
                                    <span class="text-xs text-foreground">Mostrar en landing</span>
                                </span>
                            </label>
                        </div>

                        <div>
                            <label for="service-description" class="inline-block text-xs font-semibold text-muted-foreground-1 uppercase tracking-wide mb-1.5">Descripción</label>
                            <textarea id="service-description" class="py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50" rows="2" maxlength="500" placeholder="Qué incluye este servicio..."></textarea>
                        </div>

                        <div class="flex gap-2.5 pt-3 border-t border-border mt-1">
                            <button type="button"
                                    class="py-2 px-3 inline-flex items-center justify-center gap-2 text-sm font-medium rounded-lg bg-layer border border-layer-line text-layer-foreground shadow-2xs hover:bg-layer-hover focus:outline-hidden"
                                    onclick="closeServiceModal()">Cancelar</button>
                            <button type="submit"
                                    class="py-2 px-4 inline-flex items-center justify-center gap-2 text-sm font-medium rounded-lg bg-primary border border-primary-line text-primary-foreground hover:bg-primary-hover focus:outline-hidden disabled:opacity-50 flex-1 shadow-sm">
                                <span class="iconify tabler--device-floppy size-4" aria-hidden="true"></span>
                                Guardar Servicio
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

