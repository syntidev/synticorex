        <!-- Modal: Agregar/Editar Sucursal -->
        <div id="branch-modal" class="crud-overlay"
             role="dialog" aria-modal="true" aria-labelledby="branch-modal-title" aria-hidden="true">
            <div class="crud-dialog max-w-md">
                <div class="crud-dialog-header">
                    <h3 class="crud-dialog-title flex items-center gap-2" id="branch-modal-title">
                        <span class="iconify tabler--map-pin size-4 opacity-80" aria-hidden="true"></span>
                        Agregar Sucursal
                    </h3>
                    <button class="crud-dialog-close" onclick="closeBranchModal()" aria-label="Cerrar modal">
                        <span class="iconify tabler--x size-4" aria-hidden="true"></span>
                    </button>
                </div>
                <div class="crud-dialog-body">
                    <input type="hidden" id="branch-edit-id" value="">
                    <form id="branch-form" onsubmit="saveBranch(event)" class="flex flex-col gap-3">
                        <div>
                            <label for="branch-name" class="inline-block text-xs font-semibold text-muted-foreground-1 uppercase tracking-wide mb-1.5">Nombre *</label>
                            <input type="text" id="branch-name" class="py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50" required maxlength="150" placeholder="Sede Centro, Sucursal Altamira...">
                        </div>

                        <div>
                            <label for="branch-address" class="inline-block text-xs font-semibold text-muted-foreground-1 uppercase tracking-wide mb-1.5">Dirección *</label>
                            <textarea id="branch-address" class="py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50" rows="2" required maxlength="500" placeholder="Av. Libertador, Torre X, Piso 3..."></textarea>
                        </div>

                        <div class="flex gap-2.5 pt-3 border-t border-border">
                            <button type="button" class="py-2 px-3 inline-flex items-center justify-center text-sm font-medium rounded-lg bg-layer border border-layer-line text-layer-foreground shadow-2xs hover:bg-layer-hover focus:outline-hidden" onclick="closeBranchModal()">Cancelar</button>
                            <button type="submit" class="py-2 px-4 inline-flex items-center justify-center gap-2 text-sm font-medium rounded-lg bg-primary border border-primary-line text-primary-foreground hover:bg-primary-hover focus:outline-hidden flex-1 shadow-sm">
                                <span class="iconify tabler--device-floppy size-4" aria-hidden="true"></span>
                                Guardar Sucursal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal: Editar Testimonial -->
        <div id="testimonial-modal" class="crud-overlay"
             role="dialog" aria-modal="true" aria-labelledby="testimonial-modal-title" aria-hidden="true">
            <div class="crud-dialog max-w-md">
                <div class="crud-dialog-header">
                    <h3 class="crud-dialog-title flex items-center gap-2" id="testimonial-modal-title">
                        <span class="iconify tabler--quote size-4 opacity-80" aria-hidden="true"></span>
                        Editar Testimonial
                    </h3>
                    <button class="crud-dialog-close" onclick="closeTestimonialModal()" aria-label="Cerrar modal">
                        <span class="iconify tabler--x size-4" aria-hidden="true"></span>
                    </button>
                </div>
                <div class="crud-dialog-body">
                    <input type="hidden" id="testimonial-edit-index" value="">
                    <form id="testimonial-form" onsubmit="saveTestimonialItem(event)" class="flex flex-col gap-3">

                        {{-- Name + Cargo 2-col --}}
                        <div class="grid grid-cols-2 gap-3">
                            <div class="col-span-2 sm:col-span-1">
                                <label for="testimonial-name" class="inline-block text-xs font-semibold text-muted-foreground-1 uppercase tracking-wide mb-1.5">Nombre *</label>
                                <input type="text" id="testimonial-name" class="py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50" required maxlength="100" placeholder="Juan Pérez">
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label for="testimonial-title" class="inline-block text-xs font-semibold text-muted-foreground-1 uppercase tracking-wide mb-1.5">Cargo/Rol</label>
                                <input type="text" id="testimonial-title" class="py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50" maxlength="100" placeholder="CEO de Empresa">
                            </div>
                        </div>

                        <div>
                            <label for="testimonial-text" class="inline-block text-xs font-semibold text-muted-foreground-1 uppercase tracking-wide mb-1.5">Testimonio *</label>
                            <textarea id="testimonial-text" class="py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50" rows="3" required maxlength="200" placeholder="Excelente servicio..."></textarea>
                        </div>

                        <div>
                            <label for="testimonial-rating" class="inline-block text-xs font-semibold text-muted-foreground-1 uppercase tracking-wide mb-1.5">Calificación</label>
                            <select id="testimonial-rating" class="py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs text-sm rounded-lg text-foreground focus:border-primary-focus focus:ring-primary-focus">
                                <option value="5" selected>★★★★★ Excelente (5)</option>
                                <option value="4">★★★★☆ Muy bueno (4)</option>
                                <option value="3">★★★☆☆ Bueno (3)</option>
                                <option value="2">★★☆☆☆ Aceptable (2)</option>
                                <option value="1">★☆☆☆☆ Pobre (1)</option>
                            </select>
                        </div>

                        <div class="flex gap-2.5 pt-3 border-t border-border">
                            <button type="button" class="py-2 px-3 inline-flex items-center justify-center text-sm font-medium rounded-lg bg-layer border border-layer-line text-layer-foreground shadow-2xs hover:bg-layer-hover focus:outline-hidden" onclick="closeTestimonialModal()">Cancelar</button>
                            <button type="submit" class="py-2 px-4 inline-flex items-center justify-center gap-2 text-sm font-medium rounded-lg bg-primary border border-primary-line text-primary-foreground hover:bg-primary-hover focus:outline-hidden flex-1 shadow-sm">
                                <span class="iconify tabler--device-floppy size-4" aria-hidden="true"></span>
                                Guardar Testimonio
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal: Editar FAQ -->
        <div id="faq-modal" class="crud-overlay"
             role="dialog" aria-modal="true" aria-labelledby="faq-modal-title" aria-hidden="true">
            <div class="crud-dialog max-w-md">
                <div class="crud-dialog-header">
                    <h3 class="crud-dialog-title flex items-center gap-2" id="faq-modal-title">
                        <span class="iconify tabler--help-circle size-4 opacity-80" aria-hidden="true"></span>
                        Editar Pregunta
                    </h3>
                    <button class="crud-dialog-close" onclick="closeFaqModal()" aria-label="Cerrar modal">
                        <span class="iconify tabler--x size-4" aria-hidden="true"></span>
                    </button>
                </div>
                <div class="crud-dialog-body">
                    <input type="hidden" id="faq-edit-index" value="">
                    <form id="faq-form" onsubmit="saveFaqItem(event)" class="flex flex-col gap-3">
                        <div>
                            <label for="faq-question" class="inline-block text-xs font-semibold text-muted-foreground-1 uppercase tracking-wide mb-1.5">Pregunta *</label>
                            <input type="text" id="faq-question" class="py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50" required maxlength="150" placeholder="¿Cuáles son tus horarios?">
                        </div>

                        <div>
                            <label for="faq-answer" class="inline-block text-xs font-semibold text-muted-foreground-1 uppercase tracking-wide mb-1.5">Respuesta *</label>
                            <textarea id="faq-answer" class="py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50" rows="3" required maxlength="300" placeholder="Abierto de lunes a viernes..."></textarea>
                        </div>

                        <div class="flex gap-2.5 pt-3 border-t border-border">
                            <button type="button" class="py-2 px-3 inline-flex items-center justify-center text-sm font-medium rounded-lg bg-layer border border-layer-line text-layer-foreground shadow-2xs hover:bg-layer-hover focus:outline-hidden" onclick="closeFaqModal()">Cancelar</button>
                            <button type="submit" class="py-2 px-4 inline-flex items-center justify-center gap-2 text-sm font-medium rounded-lg bg-primary border border-primary-line text-primary-foreground hover:bg-primary-hover focus:outline-hidden flex-1 shadow-sm">
                                <span class="iconify tabler--device-floppy size-4" aria-hidden="true"></span>
                                Guardar Pregunta
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal: Límite de Plan -->
        <div id="limit-modal" class="crud-overlay"
             role="dialog" aria-modal="true" aria-labelledby="limit-modal-title" aria-hidden="true">
            <div class="crud-dialog max-w-sm">
                <div class="crud-dialog-header" style="background: #f59e0b;">
                    <h3 class="crud-dialog-title flex items-center gap-2" id="limit-modal-title">
                        <span class="iconify tabler--alert-triangle size-4 opacity-90" aria-hidden="true"></span>
                        Límite Alcanzado
                    </h3>
                    <button class="crud-dialog-close" onclick="closeLimitModal()" aria-label="Cerrar modal">
                        <span class="iconify tabler--x size-4" aria-hidden="true"></span>
                    </button>
                </div>
                <div class="crud-dialog-body">
                    <p id="limit-modal-message" class="text-sm text-foreground/80 leading-relaxed mb-4"></p>
                    <div id="limit-modal-actions" class="flex gap-2.5">
                        <button onclick="closeLimitModal()" class="py-2 px-4 rounded-lg text-sm font-medium transition-colors text-foreground hover:bg-muted-hover border border-border flex-1">Cerrar</button>
                        <div id="limit-modal-cta" class="flex-1"></div>
                    </div>
                </div>
            </div>
        </div>

