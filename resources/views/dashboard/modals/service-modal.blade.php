        <!-- Modal: Servicio -->
        <div id="service-modal" class="crud-overlay"
             role="dialog" aria-modal="true" aria-labelledby="service-modal-title" aria-hidden="true">
            <div class="crud-dialog max-w-2xl">
                <div class="crud-dialog-header">
                    <h3 class="crud-dialog-title" id="service-modal-title">Agregar Servicio</h3>
                    <button class="crud-dialog-close" onclick="closeServiceModal()" aria-label="Cerrar modal">
                        <span class="iconify tabler--x size-5" aria-hidden="true"></span>
                    </button>
                </div>
                <div class="crud-dialog-body p-7">
                    <form id="service-form" onsubmit="saveService(event)">
                        <input type="hidden" id="service-id">
                        <input type="hidden" id="service-icon-name">

                        {{-- Mode tabs: Plan 2/3 only --}}
                        @if($plan->id !== 1)
                        <div class="svc-segment w-full mb-6" role="tablist" aria-label="Modo de representación del servicio">
                            <button type="button" id="svc-tab-icon" role="tab"
                                class="seg-active flex-1 flex items-center justify-center gap-2 py-2.5 px-4 rounded-btn text-sm font-bold transition-all"
                                onclick="setServiceModalMode('icon')">
                                <span class="iconify tabler--color-picker size-4" aria-hidden="true"></span> Ícono
                            </button>
                            <button type="button" id="svc-tab-image" role="tab"
                                class="flex-1 flex items-center justify-center gap-2 py-2.5 px-4 rounded-btn text-sm font-bold transition-all"
                                onclick="setServiceModalMode('image')">
                                <span class="iconify tabler--photo-up size-4" aria-hidden="true"></span> Imagen
                            </button>
                        </div>
                        @endif

                        {{-- ICON PICKER (Plan 1: always; Plan 2/3: when icon mode) --}}
                        <div id="svc-section-icon" class="form-control py-2 mb-4">
                            <label class="label"><span class="label-text text-sm font-semibold">Ícono del Servicio</span></label>

                            {{-- Current selection preview --}}
                            <div class="flex flex-col items-center p-7 rounded-2xl mb-6 border" style="background:linear-gradient(135deg,var(--synti-soft) 0%,transparent 60%);border-color:var(--synti-bdr);">
                                <div class="size-20 rounded-full flex items-center justify-center shrink-0 mb-4 border-2" style="background:var(--synti-soft);border-color:var(--synti-bdr);">
                                    <span id="icon-preview-el" class="iconify tabler--settings size-12 text-primary"></span>
                                </div>
                                <p id="icon-preview-label" class="text-sm font-semibold text-base-content text-center mb-0">Sin ícono seleccionado</p>
                                <p class="text-xs text-base-content/40 text-center mt-1">Busca y selecciona un ícono</p>
                            </div>

                            {{-- Search --}}
                            <input type="text" id="icon-search" class="input input-sm w-full mb-4"
                                placeholder="Busca: scissors, camera, truck, heart..."
                                oninput="filterIcons(this.value)" autocomplete="off">

                            {{-- Icon Grid --}}
                            <div id="icon-picker-grid" class="grid grid-cols-6 gap-3 max-h-80 overflow-y-auto p-3 rounded-xl bg-base-200/60 border border-base-content/8"></div>
                            <p class="text-xs text-base-content/30 mt-3 text-center">60+ iconos disponibles</p>
                            
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
                        <div id="svc-section-image" class="form-control py-2" style="display: none;">
                            <label class="label"><span class="label-text text-sm font-medium">Imagen del Servicio</span></label>
                            <div class="image-preview" id="service-image-preview" style="display: none;">
                                <img id="service-image-preview-img" src="" alt="Preview">
                                <button type="button" onclick="cancelServiceImage()" class="btn btn-xs btn-ghost btn-circle absolute top-1 right-1">
                                    <span class="iconify tabler--x size-3.5" aria-hidden="true"></span>
                                </button>
                            </div>
                            <div id="service-upload-zone"
                                 class="border-2 border-dashed border-base-content/20 rounded-lg p-5 text-center cursor-pointer hover:border-primary hover:bg-primary/5 transition-all"
                                 onclick="document.getElementById('service-image').click()"
                                 ondragover="event.preventDefault(); this.classList.add('border-primary','bg-primary/5')"
                                 ondragleave="this.classList.remove('border-primary','bg-primary/5')"
                                 ondrop="event.preventDefault(); this.classList.remove('border-primary','bg-primary/5'); document.getElementById('service-image').files = event.dataTransfer.files; previewServiceImage({target: document.getElementById('service-image')})">
                                <span class="iconify tabler--cloud-upload size-10 mx-auto text-base-content/30 mb-1.5"></span>
                                <p class="text-sm font-medium text-base-content/60">Arrastra imagen aquí o <span class="text-primary font-bold">clickea para elegir</span></p>
                                <p class="text-xs text-base-content/40 mt-1">PNG, JPG, WebP · Máx 2MB · Se redimensiona a 800px</p>
                            </div>
                            <input type="file" id="service-image" accept="image/*" class="hidden" onchange="previewServiceImage(event)">
                        </div>
                        @endif

                        <div class="form-control py-2">
                            <label for="service-name" class="label"><span class="label-text text-sm font-medium">Nombre *</span></label>
                            <input type="text" id="service-name" class="input input-bordered w-full" required maxlength="100">
                        </div>

                        <div class="form-control py-2">
                            <label for="service-description" class="label"><span class="label-text text-sm font-medium">Descripción</span></label>
                            <textarea id="service-description" class="textarea textarea-bordered w-full" rows="3" maxlength="500"></textarea>
                        </div>

                        <div class="form-control py-2">
                            <label class="label"><span class="label-text text-sm font-medium">Servicio Activo</span></label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" id="service-is-active" class="toggle toggle-success" checked>
                                <span class="text-sm text-base-content/60">Mostrar en landing page</span>
                            </label>
                        </div>

                        <div class="flex gap-3 pt-5 border-t border-base-content/10 justify-end">
                            <button type="button" class="btn btn-soft" onclick="closeServiceModal()">Cancelar</button>
                            <button type="submit" class="btn btn-primary gap-2 shadow-md shadow-primary/20 hover:shadow-lg">
                                <span class="iconify tabler--device-floppy size-4" aria-hidden="true"></span>
                                Guardar Servicio
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

