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
                            <label for="branch-name" class="label"><span class="label-text text-sm font-medium">Nombre *</span></label>
                            <input type="text" id="branch-name" class="input input-bordered w-full" required maxlength="150" placeholder="Sede Centro, Sucursal Altamira...">
                        </div>

                        <div class="form-control py-2">
                            <label for="branch-address" class="label"><span class="label-text text-sm font-medium">Dirección *</span></label>
                            <textarea id="branch-address" class="textarea textarea-bordered w-full" rows="2" required maxlength="500" placeholder="Av. Libertador, Torre X, Piso 3..."></textarea>
                        </div>

                        <div class="flex gap-3 pt-6 border-t border-base-content/10 justify-end">
                            <button type="button" class="btn btn-soft" onclick="closeBranchModal()">Cancelar</button>
                            <button type="submit" class="btn btn-primary gap-2 shadow-md shadow-primary/20 hover:shadow-lg">
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
                            <label for="testimonial-name" class="label"><span class="label-text text-sm font-medium">Nombre *</span></label>
                            <input type="text" id="testimonial-name" class="input input-bordered w-full" required maxlength="100" placeholder="Juan Pérez...">
                        </div>

                        <div class="form-control py-2">
                            <label for="testimonial-title" class="label"><span class="label-text text-sm font-medium">Cargo/Rol</span></label>
                            <input type="text" id="testimonial-title" class="input input-bordered w-full" maxlength="100" placeholder="CEO de Empresa...">
                        </div>

                        <div class="form-control py-2">
                            <label for="testimonial-text" class="label"><span class="label-text text-sm font-medium">Testimonio *</span></label>
                            <textarea id="testimonial-text" class="textarea textarea-bordered w-full" rows="3" required maxlength="200" placeholder="Excelente servicio..."></textarea>
                        </div>

                        <div class="form-control py-2">
                            <label for="testimonial-rating" class="label"><span class="label-text text-sm font-medium">Calificación</span></label>
                            <select id="testimonial-rating" class="select select-bordered w-full">
                                <option value="5" selected>★★★★★ Excelente (5)</option>
                                <option value="4">★★★★☆ Muy bueno (4)</option>
                                <option value="3">★★★☆☆ Bueno (3)</option>
                                <option value="2">★★☆☆☆ Aceptable (2)</option>
                                <option value="1">★☆☆☆☆ Pobre (1)</option>
                            </select>
                        </div>

                        <div class="flex gap-3 pt-6 border-t border-base-content/10 justify-end">
                            <button type="button" class="btn btn-soft" onclick="closeTestimonialModal()">Cancelar</button>
                            <button type="submit" class="btn btn-primary gap-2 shadow-md shadow-primary/20 hover:shadow-lg">
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
                            <label for="faq-question" class="label"><span class="label-text text-sm font-medium">Pregunta *</span></label>
                            <input type="text" id="faq-question" class="input input-bordered w-full" required maxlength="150" placeholder="¿Cuáles son tus horarios?...">
                        </div>

                        <div class="form-control py-2">
                            <label for="faq-answer" class="label"><span class="label-text text-sm font-medium">Respuesta *</span></label>
                            <textarea id="faq-answer" class="textarea textarea-bordered w-full" rows="3" required maxlength="300" placeholder="Abierto de lunes a viernes..."></textarea>
                        </div>

                        <div class="flex gap-3 pt-6 border-t border-base-content/10 justify-end">
                            <button type="button" class="btn btn-soft" onclick="closeFaqModal()">Cancelar</button>
                            <button type="submit" class="btn btn-secondary gap-2 shadow-md shadow-secondary/20 hover:shadow-lg">
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
                        <button onclick="closeLimitModal()" class="btn btn-ghost flex-1">Cerrar</button>
                        <div id="limit-modal-cta"></div>
                    </div>
                </div>
            </div>
        </div>

