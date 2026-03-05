        <!-- Modal: Producto -->
        <div id="product-modal" class="crud-overlay"
             role="dialog" aria-modal="true" aria-labelledby="product-modal-title" aria-hidden="true">
            <div class="crud-dialog">
                <div class="crud-dialog-header">
                    <h3 class="crud-dialog-title flex items-center gap-2" id="product-modal-title">
                        <span class="iconify tabler--package size-4 opacity-80" aria-hidden="true"></span>
                        Agregar Producto
                    </h3>
                    <button class="crud-dialog-close" onclick="closeProductModal()" aria-label="Cerrar modal">
                        <span class="iconify tabler--x size-4" aria-hidden="true"></span>
                    </button>
                </div>
                <div class="crud-dialog-body">
                    <form id="product-form" onsubmit="saveProduct(event)" class="flex flex-col gap-3">
                        <input type="hidden" id="product-id">

                        <div class="image-preview" id="product-image-preview" style="display: none;">
                            <img id="product-image-preview-img" src="" alt="Preview">
                            <button type="button" onclick="cancelProductImage()" class="p-1 rounded-full transition-colors text-foreground hover:bg-muted-hover absolute top-1 right-1">
                                <span class="iconify tabler--x size-3.5" aria-hidden="true"></span>
                            </button>
                        </div>

                        <div>
                            <label class="inline-block text-xs font-semibold text-muted-foreground-1 uppercase tracking-wide mb-1.5">Imagen Principal</label>
                            <div id="product-upload-zone"
                                 class="border-2 border-dashed border-border rounded-lg p-3.5 text-center cursor-pointer hover:border-primary hover:bg-primary/5 transition-all"
                                 onclick="document.getElementById('product-image').click()"
                                 ondragover="event.preventDefault(); this.classList.add('border-primary','bg-primary/5')"
                                 ondragleave="this.classList.remove('border-primary','bg-primary/5')"
                                 ondrop="event.preventDefault(); this.classList.remove('border-primary','bg-primary/5'); document.getElementById('product-image').files = event.dataTransfer.files; previewProductImage({target: document.getElementById('product-image')})">
                                <span class="iconify tabler--cloud-upload size-7 mx-auto text-muted-foreground-1 mb-1"></span>
                                <p class="text-sm font-medium text-foreground/70">Arrastra o <span class="text-primary font-bold">elige imagen</span></p>
                                <p class="text-xs text-muted-foreground-1 mt-0.5">PNG, JPG, WebP · Máx 2MB</p>
                            </div>
                            <input type="file" id="product-image" accept="image/*" class="hidden" onchange="previewProductImage(event)">
                        </div>

                        {{-- Gallery Section — Plan 3 (VISIÓN) only --}}
                        @if($plan->id === 3)
                        <div id="product-gallery-section">
                            <label class="inline-flex items-center gap-1.5 text-xs font-semibold text-muted-foreground-1 uppercase tracking-wide mb-1.5">
                                <span class="iconify tabler--photo-scan size-3.5 text-primary" aria-hidden="true"></span>
                                Galería extra <span class="font-normal normal-case">(máx. 2 — Plan Visión)</span>
                            </label>

                            <div id="product-gallery-existing" class="hidden mb-2">
                                <div id="product-gallery-thumbs" class="flex gap-2 flex-wrap"></div>
                            </div>
                            <div id="product-gallery-upload-area" class="flex gap-2 flex-wrap mt-1">
                                <div id="gallery-slot-1" class="gallery-upload-slot hidden">
                                    <input type="file" id="product-gallery-1" accept="image/*" class="py-1.5 px-3 block w-full bg-layer border-layer-line shadow-2xs text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus" onchange="previewGalleryImage(event, 1)">
                                </div>
                                <div id="gallery-slot-2" class="gallery-upload-slot hidden">
                                    <input type="file" id="product-gallery-2" accept="image/*" class="py-1.5 px-3 block w-full bg-layer border-layer-line shadow-2xs text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus" onchange="previewGalleryImage(event, 2)">
                                </div>
                            </div>
                            <div id="product-gallery-previews" class="flex gap-2 mt-1.5 flex-wrap"></div>
                        </div>
                        @endif

                        {{-- Name + Price 2-col --}}
                        <div class="grid grid-cols-2 gap-3">
                            <div class="col-span-2 sm:col-span-1">
                                <label for="product-name" class="inline-block text-xs font-semibold text-muted-foreground-1 uppercase tracking-wide mb-1.5">Nombre *</label>
                                <input type="text" id="product-name" class="py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50" required maxlength="100" placeholder="Nombre del producto">
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label for="product-price" class="inline-block text-xs font-semibold text-muted-foreground-1 uppercase tracking-wide mb-1.5">Precio USD *</label>
                                <input type="number" id="product-price" class="py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50" required step="0.01" min="0" placeholder="0.00">
                            </div>
                        </div>

                        {{-- Description --}}
                        <div>
                            <label for="product-description" class="inline-block text-xs font-semibold text-muted-foreground-1 uppercase tracking-wide mb-1.5">Descripción</label>
                            <textarea id="product-description" class="py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50" rows="2" maxlength="500" placeholder="Descripción breve del producto..."></textarea>
                        </div>

                        {{-- Badge + Active + Featured 3-col --}}
                        <div class="grid grid-cols-3 gap-2.5 items-end">
                            <div class="col-span-3 sm:col-span-1">
                                <label for="product-badge" class="inline-block text-xs font-semibold text-muted-foreground-1 uppercase tracking-wide mb-1.5">Badge</label>
                                <select id="product-badge" class="py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs text-sm rounded-lg text-foreground focus:border-primary-focus focus:ring-primary-focus">
                                    <option value="">Sin badge</option>
                                    <option value="hot">🔥 Hot</option>
                                    <option value="new">✨ New</option>
                                    <option value="promo">🎉 Promo</option>
                                </select>
                            </div>
                            <label class="col-span-3 sm:col-span-1 flex flex-col gap-1.5 cursor-pointer p-2.5 rounded-lg bg-layer border border-layer-line hover:bg-layer-hover transition-colors h-full justify-center">
                                <span class="text-xs font-semibold text-muted-foreground-1 uppercase tracking-wide">Activo</span>
                                <span class="flex items-center gap-2">
                                    <input type="checkbox" id="product-is-active" class="size-4 rounded-sm border-border text-primary focus:ring-primary/20 cursor-pointer" checked>
                                    <span class="text-xs text-foreground">Visible en landing</span>
                                </span>
                            </label>
                            <label class="col-span-3 sm:col-span-1 flex flex-col gap-1.5 cursor-pointer p-2.5 rounded-lg bg-layer border border-layer-line hover:bg-layer-hover transition-colors h-full justify-center">
                                <span class="text-xs font-semibold text-muted-foreground-1 uppercase tracking-wide">Destacado ⭐</span>
                                <span class="flex items-center gap-2">
                                    <input type="checkbox" id="product-is-featured" class="size-4 rounded-sm border-border text-primary focus:ring-primary/20 cursor-pointer">
                                    <span class="text-xs text-foreground">Marcar como especial</span>
                                </span>
                            </label>
                        </div>

                        {{-- Actions --}}
                        <div class="flex gap-2.5 pt-3 border-t border-border mt-1">
                            <button type="button"
                                    class="py-2 px-3 inline-flex items-center justify-center gap-2 text-sm font-medium rounded-lg bg-layer border border-layer-line text-layer-foreground shadow-2xs hover:bg-layer-hover focus:outline-hidden flex-1"
                                    onclick="closeProductModal()">Cancelar</button>
                            <button type="submit"
                                    class="py-2 px-4 inline-flex items-center justify-center gap-2 text-sm font-medium rounded-lg bg-primary border border-primary-line text-primary-foreground hover:bg-primary-hover focus:outline-hidden disabled:opacity-50 flex-1 shadow-sm">
                                <span class="iconify tabler--device-floppy size-4" aria-hidden="true"></span>
                                Guardar Producto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

