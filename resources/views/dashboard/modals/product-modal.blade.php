        <!-- Modal: Producto -->
        <div id="product-modal" class="crud-overlay"
             role="dialog" aria-modal="true" aria-labelledby="product-modal-title" aria-hidden="true">
            <div class="crud-dialog">
                <div class="crud-dialog-header">
                    <h3 class="crud-dialog-title" id="product-modal-title">Agregar Producto</h3>
                    <button class="crud-dialog-close" onclick="closeProductModal()" aria-label="Cerrar modal">&times;</button>
                </div>
                <div class="crud-dialog-body">
                    <form id="product-form" onsubmit="saveProduct(event)">
                        <input type="hidden" id="product-id">

                        <div class="image-preview" id="product-image-preview" style="display: none;">
                            <img id="product-image-preview-img" src="" alt="Preview">
                            <button type="button" onclick="cancelProductImage()" class="p-1 rounded-full transition-colors text-foreground hover:bg-muted-hover absolute top-1 right-1">
                                <span class="iconify tabler--x size-3.5" aria-hidden="true"></span>
                            </button>
                        </div>

                        <div class="form-control py-2">
                            <label class="inline-block text-sm font-medium text-foreground mb-1">Imagen Principal del Producto</label>
                            <div id="product-upload-zone"
                                 class="border-2 border-dashed border-base-content/20 rounded-lg p-5 text-center cursor-pointer hover:border-primary hover:bg-primary/5 transition-all"
                                 onclick="document.getElementById('product-image').click()"
                                 ondragover="event.preventDefault(); this.classList.add('border-primary','bg-primary/5')"
                                 ondragleave="this.classList.remove('border-primary','bg-primary/5')"
                                 ondrop="event.preventDefault(); this.classList.remove('border-primary','bg-primary/5'); document.getElementById('product-image').files = event.dataTransfer.files; previewProductImage({target: document.getElementById('product-image')})">
                                <span class="iconify tabler--cloud-upload size-10 mx-auto text-base-content/30 mb-1.5"></span>
                                <p class="text-sm font-medium text-base-content/60">Arrastra imagen aquí o <span class="text-primary font-bold">clickea para elegir</span></p>
                                <p class="text-xs text-base-content/40 mt-1">PNG, JPG, WebP · Máx 2MB · Se redimensiona a 800px</p>
                            </div>
                            <input type="file" id="product-image" accept="image/*" class="hidden" onchange="previewProductImage(event)">
                        </div>

                        {{-- Gallery Section — Plan 3 (VISIÓN) only --}}
                        @if($plan->id === 3)
                        <div class="form-control py-2" id="product-gallery-section">
                            <label class="label flex items-center gap-2">
                                <span class="iconify tabler--photo-scan size-4 text-primary" aria-hidden="true"></span>
                                Galería Adicional
                                <span class="text-xs text-base-content/50 font-normal">(máx. 2 fotos extra — Plan Visión)</span>
                            </label>

                            {{-- Existing gallery images container --}}
                            <div id="product-gallery-existing" class="hidden mb-3">
                                <div id="product-gallery-thumbs" class="flex gap-2.5 flex-wrap"></div>
                            </div>

                            {{-- Upload new gallery images --}}
                            <div id="product-gallery-upload-area" class="flex gap-2.5 flex-wrap mt-2">
                                <div id="gallery-slot-1" class="gallery-upload-slot hidden">
                                    <input type="file" id="product-gallery-1" accept="image/*" class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none" onchange="previewGalleryImage(event, 1)">
                                </div>
                                <div id="gallery-slot-2" class="gallery-upload-slot hidden">
                                    <input type="file" id="product-gallery-2" accept="image/*" class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none" onchange="previewGalleryImage(event, 2)">
                                </div>
                            </div>

                            {{-- Gallery previews --}}
                            <div id="product-gallery-previews" class="flex gap-2.5 mt-2 flex-wrap"></div>

                            <p class="text-xs text-base-content/50 mt-1.5">
                                Las imágenes de galería se suben al guardar el producto. Total: 1 principal + 2 galería = 3 fotos.
                            </p>
                        </div>
                        @endif

                        <div class="form-control py-2">
                            <label for="product-name" class="inline-block text-sm font-medium text-foreground mb-1">Nombre *</label>
                            <input type="text" id="product-name" class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none" required maxlength="100">
                        </div>

                        <div class="form-control py-2">
                            <label for="product-description" class="inline-block text-sm font-medium text-foreground mb-1">Descripción</label>
                            <textarea id="product-description" class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none" rows="3" maxlength="500"></textarea>
                        </div>

                        <div class="form-control py-2">
                            <label for="product-price" class="inline-block text-sm font-medium text-foreground mb-1">Precio USD *</label>
                            <input type="number" id="product-price" class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none" required step="0.01" min="0">
                        </div>

                        <div class="form-control py-2">
                            <label for="product-badge" class="inline-block text-sm font-medium text-foreground mb-1">Badge</label>
                            <select id="product-badge" class="py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none">
                                <option value="">Sin badge</option>
                                <option value="hot">🔥 Hot</option>
                                <option value="new">✨ New</option>
                                <option value="promo">🎉 Promo</option>
                            </select>
                        </div>

                        <div class="form-control py-2">
                            <label class="inline-block text-sm font-medium text-foreground mb-1">Producto Activo</label>
                            <input type="checkbox" id="product-is-active" class="toggle toggle-success" checked>
                        </div>

                        <div class="form-control py-2">
                            <label class="inline-block text-sm font-medium text-foreground mb-1">Producto Destacado ⭐</label>
                            <input type="checkbox" id="product-is-featured" class="toggle toggle-warning">
                        </div>

                        <div class="flex gap-3 pt-5 border-t border-border mt-3">
                            <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-layer border border-layer-line text-layer-foreground shadow-2xs hover:bg-layer-hover disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-layer-focus flex-1" onclick="closeProductModal()">Cancelar</button>
                            <button type="submit" class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-primary border border-primary-line text-primary-foreground hover:bg-primary-hover focus:outline-hidden focus:bg-primary-focus disabled:opacity-50 disabled:pointer-events-none flex-1 shadow-sm">
                                <span class="iconify tabler--device-floppy size-4" aria-hidden="true"></span>
                                Guardar Producto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

