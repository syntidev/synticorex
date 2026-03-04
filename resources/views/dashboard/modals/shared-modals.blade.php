        <!-- Modal: Agregar/Editar Sucursal -->
        <div id="branch-modal" class="crud-overlay"
             role="dialog" aria-modal="true" aria-labelledby="branch-modal-title" aria-hidden="true">
            <div class="crud-dialog max-w-md">
                <div class="crud-dialog-header">
                    <h3 class="crud-dialog-title" id="branch-modal-title">+ Agregar Sucursal</h3>
                    <button class="crud-dialog-close" onclick="closeBranchModal()" aria-label="Cerrar modal">&times;</button>
                </div>
                <div class="crud-dialog-body">
                    <input type="hidden" id="branch-edit-id" value="">
                    <form id="branch-form" onsubmit="saveBranch(event)" class="flex flex-col gap-3">
                        <div class="form-control py-2">
                            <label for="branch-name" class="inline-block text-sm font-medium text-foreground mb-1">Nombre *</label>
                            <input type="text" id="branch-name" class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none" required maxlength="150" placeholder="Sede Centro, Sucursal Altamira...">
                        </div>

                        <div class="form-control py-2">
                            <label for="branch-address" class="inline-block text-sm font-medium text-foreground mb-1">Dirección *</label>
                            <textarea id="branch-address" class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none" rows="2" required maxlength="500" placeholder="Av. Libertador, Torre X, Piso 3..."></textarea>
                        </div>

                        <div class="flex gap-3 pt-6 border-t border-border justify-end">
                            <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-layer border border-layer-line text-layer-foreground shadow-2xs hover:bg-layer-hover disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-layer-focus" onclick="closeBranchModal()">Cancelar</button>
                            <button type="submit" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-primary border border-primary-line text-primary-foreground hover:bg-primary-hover focus:outline-hidden focus:bg-primary-focus disabled:opacity-50 disabled:pointer-events-none shadow-sm">
                                <span class="iconify tabler--device-floppy size-4" aria-hidden="true"></span>
                                Guardar
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
                    <h3 class="crud-dialog-title" id="testimonial-modal-title">Editar Testimonial</h3>
                    <button class="crud-dialog-close" onclick="closeTestimonialModal()" aria-label="Cerrar modal">&times;</button>
                </div>
                <div class="crud-dialog-body">
                    <input type="hidden" id="testimonial-edit-index" value="">
                    <form id="testimonial-form" onsubmit="saveTestimonialItem(event)" class="flex flex-col gap-3">
                        <div class="form-control py-2">
                            <label for="testimonial-name" class="inline-block text-sm font-medium text-foreground mb-1">Nombre *</label>
                            <input type="text" id="testimonial-name" class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none" required maxlength="100" placeholder="Juan Pérez...">
                        </div>

                        <div class="form-control py-2">
                            <label for="testimonial-title" class="inline-block text-sm font-medium text-foreground mb-1">Cargo/Rol</label>
                            <input type="text" id="testimonial-title" class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none" maxlength="100" placeholder="CEO de Empresa...">
                        </div>

                        <div class="form-control py-2">
                            <label for="testimonial-text" class="inline-block text-sm font-medium text-foreground mb-1">Testimonio *</label>
                            <textarea id="testimonial-text" class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none" rows="3" required maxlength="200" placeholder="Excelente servicio..."></textarea>
                        </div>

                        <div class="form-control py-2">
                            <label for="testimonial-rating" class="inline-block text-sm font-medium text-foreground mb-1">Calificación</label>
                            <select id="testimonial-rating" class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none">
                                <option value="5" selected>★★★★★ Excelente (5)</option>
                                <option value="4">★★★★☆ Muy bueno (4)</option>
                                <option value="3">★★★☆☆ Bueno (3)</option>
                                <option value="2">★★☆☆☆ Aceptable (2)</option>
                                <option value="1">★☆☆☆☆ Pobre (1)</option>
                            </select>
                        </div>

                        <div class="flex gap-3 pt-6 border-t border-border justify-end">
                            <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-layer border border-layer-line text-layer-foreground shadow-2xs hover:bg-layer-hover disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-layer-focus" onclick="closeTestimonialModal()">Cancelar</button>
                            <button type="submit" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-primary border border-primary-line text-primary-foreground hover:bg-primary-hover focus:outline-hidden focus:bg-primary-focus disabled:opacity-50 disabled:pointer-events-none shadow-sm">
                                <span class="iconify tabler--device-floppy size-4" aria-hidden="true"></span>
                                Guardar
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
                    <h3 class="crud-dialog-title" id="faq-modal-title">Editar Pregunta</h3>
                    <button class="crud-dialog-close" onclick="closeFaqModal()" aria-label="Cerrar modal">&times;</button>
                </div>
                <div class="crud-dialog-body">
                    <input type="hidden" id="faq-edit-index" value="">
                    <form id="faq-form" onsubmit="saveFaqItem(event)" class="flex flex-col gap-3">
                        <div class="form-control py-2">
                            <label for="faq-question" class="inline-block text-sm font-medium text-foreground mb-1">Pregunta *</label>
                            <input type="text" id="faq-question" class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none" required maxlength="150" placeholder="¿Cuáles son tus horarios?...">
                        </div>

                        <div class="form-control py-2">
                            <label for="faq-answer" class="inline-block text-sm font-medium text-foreground mb-1">Respuesta *</label>
                            <textarea id="faq-answer" class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none" rows="3" required maxlength="300" placeholder="Abierto de lunes a viernes..."></textarea>
                        </div>

                        <div class="flex gap-3 pt-6 border-t border-border justify-end">
                            <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-layer border border-layer-line text-layer-foreground shadow-2xs hover:bg-layer-hover disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-layer-focus" onclick="closeFaqModal()">Cancelar</button>
                            <button type="submit" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-primary border border-primary-line text-primary-foreground hover:bg-primary-hover focus:outline-hidden focus:bg-primary-focus disabled:opacity-50 disabled:pointer-events-none shadow-sm">
                                <span class="iconify tabler--device-floppy size-4" aria-hidden="true"></span>
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal: Límite de Plan -->
        <div id="limit-modal" class="crud-overlay"
             role="dialog" aria-modal="true" aria-labelledby="limit-modal-title" aria-hidden="true">
            <div class="crud-dialog max-w-md">
                <div class="crud-dialog-header">
                    <h3 class="crud-dialog-title" id="limit-modal-title">⚠️ Límite Alcanzado</h3>
                    <button class="crud-dialog-close" onclick="closeLimitModal()" aria-label="Cerrar modal">&times;</button>
                </div>
                <div class="crud-dialog-body">
                    <p id="limit-modal-message" class="text-base-content/80 leading-relaxed mb-5"></p>
                    <div id="limit-modal-actions" class="flex gap-3 flex-wrap">
                        <button onclick="closeLimitModal()" class="py-2 px-4 rounded-lg font-medium transition-colors text-foreground hover:bg-muted-hover flex-1">Cerrar</button>
                        <div id="limit-modal-cta"></div>
                    </div>
                </div>
            </div>
        </div>

