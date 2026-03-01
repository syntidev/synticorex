    <script>
        // Tab Navigation — FlyonUI Sidebar
        document.addEventListener('DOMContentLoaded', function() {
            const tabs     = document.querySelectorAll('#layout-sidebar [role="tab"]');
            const contents = document.querySelectorAll('.tab-content');

            function switchTab(tabId) {
                // Reset all sidebar tabs
                tabs.forEach(t => {
                    t.classList.remove('menu-active');
                    t.setAttribute('aria-selected', 'false');
                    t.setAttribute('tabindex', '-1');
                });
                // Reset all content panels
                contents.forEach(c => c.classList.remove('active'));

                // Activate selected button + panel
                const activeBtn     = document.querySelector(`#layout-sidebar [data-tab="${tabId}"]`);
                const activeContent = document.getElementById('tab-' + tabId);

                activeBtn?.classList.add('menu-active');
                activeBtn?.setAttribute('aria-selected', 'true');
                activeBtn?.setAttribute('tabindex', '0');

                activeContent?.classList.add('active');

                // Auto-close mobile sidebar drawer after navigation (via FlyonUI API)
                if (window.innerWidth < 1024) {
                    if (window.HSOverlay) {
                        window.HSOverlay.close('#layout-sidebar');
                    } else {
                        const sb = document.getElementById('layout-sidebar');
                        if (sb) { sb.classList.remove('open', 'opened'); }
                    }
                }

                // Re-init SortableJS cada vez que se abre el tab Tu Mensaje
                if (tabId === 'mensaje') {
                    requestAnimationFrame(function() { window.initSortable(); });
                }
            }

            // Bind click events to all sidebar nav buttons
            tabs.forEach((tab, index) => {
                tab.addEventListener('click', function() {
                    switchTab(this.getAttribute('data-tab'));
                });

                // Keyboard: ArrowUp/Down for vertical sidebar navigation
                tab.addEventListener('keydown', function(e) {
                    let nextTab = null;
                    if (e.key === 'ArrowDown') {
                        nextTab = tabs[index + 1] || tabs[0];
                        e.preventDefault();
                    } else if (e.key === 'ArrowUp') {
                        nextTab = tabs[index - 1] || tabs[tabs.length - 1];
                        e.preventDefault();
                    } else if (e.key === 'Home') {
                        nextTab = tabs[0];
                        e.preventDefault();
                    } else if (e.key === 'End') {
                        nextTab = tabs[tabs.length - 1];
                        e.preventDefault();
                    }
                    if (nextTab) {
                        nextTab.focus();
                        switchTab(nextTab.getAttribute('data-tab'));
                    }
                });
            });
        });

        // Toggle Hours Indicator Fields
        function toggleHoursIndicatorFields() {
            const toggle = document.getElementById('show-hours-toggle');
            const fields = document.getElementById('hours-indicator-fields');
            
            if (toggle.checked) {
                fields.classList.remove('hidden');
                updateCharCount();
                updatePreview();
            } else {
                fields.classList.add('hidden');
            }
        }

        // Update character count for closed message
        function updateCharCount() {
            const textarea = document.getElementById('closed-message-input');
            const charCount = document.getElementById('char-count');
            if (textarea && charCount) {
                charCount.textContent = `${textarea.value.length} / 150`;
            }
        }

        // Update preview message in real-time
        function updatePreview() {
            const textarea = document.getElementById('closed-message-input');
            const preview = document.getElementById('preview-message');
            if (textarea && preview) {
                preview.textContent = textarea.value || 'Estamos cerrados. Te responderemos durante nuestro horario de atención.';
            }
        }

        // Initialize character count on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCharCount();
            updatePreview();
        });

        // ── Business Hours ────────────────────────────────────────
        function toggleDayClosed(dayKey, isClosed) {
            const openInput = document.getElementById('bh-' + dayKey + '-open');
            const closeInput = document.getElementById('bh-' + dayKey + '-close');
            if (openInput) openInput.disabled = isClosed;
            if (closeInput) closeInput.disabled = isClosed;
        }

        // ── Hours mode switcher (Rápido / Por día) ───────────────────
        let hoursMode = '{{ data_get($tenant->settings, "engine_settings.hours_mode", "simple") }}';
        // Auto-detect from DOM initial state
        (function() {
            const simpleEl = document.getElementById('hours-simple-mode');
            if (simpleEl && !simpleEl.classList.contains('hidden')) hoursMode = 'simple';
            else hoursMode = 'custom';
        })();

        function setHoursMode(mode) {
            hoursMode = mode;
            const simpleEl = document.getElementById('hours-simple-mode');
            const customEl = document.getElementById('hours-custom-mode');
            const btnSimple = document.getElementById('hours-mode-simple');
            const btnCustom = document.getElementById('hours-mode-custom');

            if (mode === 'simple') {
                simpleEl.classList.remove('hidden');
                customEl.classList.add('hidden');
                btnSimple.classList.add('bg-primary', 'text-primary-content', 'shadow-sm');
                btnSimple.classList.remove('text-base-content/60');
                btnCustom.classList.remove('bg-primary', 'text-primary-content', 'shadow-sm');
                btnCustom.classList.add('text-base-content/60');
            } else {
                customEl.classList.remove('hidden');
                simpleEl.classList.add('hidden');
                btnCustom.classList.add('bg-primary', 'text-primary-content', 'shadow-sm');
                btnCustom.classList.remove('text-base-content/60');
                btnSimple.classList.remove('bg-primary', 'text-primary-content', 'shadow-sm');
                btnSimple.classList.add('text-base-content/60');
            }
        }

        async function saveBusinessHours() {
            const days = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
            const payload = {};

            if (hoursMode === 'simple') {
                // Weekdays (Mon-Fri) get same hours
                const wdOpen  = document.getElementById('bh-simple-wd-open')?.value || '08:00';
                const wdClose = document.getElementById('bh-simple-wd-close')?.value || '18:00';
                ['monday','tuesday','wednesday','thursday','friday'].forEach(d => {
                    payload[d] = { open: wdOpen, close: wdClose };
                });
                // Saturday
                if (document.getElementById('bh-simple-sat-closed')?.checked) {
                    payload.saturday = { closed: true };
                } else {
                    payload.saturday = {
                        open: document.getElementById('bh-simple-sat-open')?.value || '09:00',
                        close: document.getElementById('bh-simple-sat-close')?.value || '17:00'
                    };
                }
                // Sunday
                if (document.getElementById('bh-simple-sun-closed')?.checked) {
                    payload.sunday = { closed: true };
                } else {
                    payload.sunday = {
                        open: document.getElementById('bh-simple-sun-open')?.value || '09:00',
                        close: document.getElementById('bh-simple-sun-close')?.value || '14:00'
                    };
                }
            } else {
                // Custom: per day
                days.forEach(day => {
                    const closedToggle = document.getElementById('bh-' + day + '-closed');
                    const openInput = document.getElementById('bh-' + day + '-open');
                    const closeInput = document.getElementById('bh-' + day + '-close');

                    if (closedToggle && closedToggle.checked) {
                        payload[day] = { closed: true };
                    } else {
                        payload[day] = {
                            open: openInput ? openInput.value : '08:00',
                            close: closeInput ? closeInput.value : '18:00'
                        };
                    }
                });
            }

            try {
                const response = await fetch('/tenant/{{ $tenant->id }}/update-business-hours', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();
                if (result.success) {
                    refreshHoursSummary(payload);
                    window.showToast ? window.showToast('✅ Horario guardado', 'success') : alert('✓ Horario guardado');
                } else {
                    window.showToast ? window.showToast('❌ ' + (result.message || 'Error'), 'error') : alert('✗ ' + (result.message || 'Error'));
                }
            } catch (error) {
                console.error('Error:', error);
                window.showToast ? window.showToast('❌ Error de red', 'error') : alert('✗ Error al guardar horario');
            }
        }

        // Rebuild compact summary line from saved payload
        function refreshHoursSummary(payload) {
            const shortNames = {monday:'Lun',tuesday:'Mar',wednesday:'Mié',thursday:'Jue',friday:'Vie',saturday:'Sáb',sunday:'Dom'};
            const order = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
            const grouped = [];
            let prev = null;
            order.forEach(day => {
                const d = payload[day] || {};
                const key = d.closed ? 'closed' : ((d.open || '08:00') + '-' + (d.close || '18:00'));
                if (key === prev && grouped.length) {
                    grouped[grouped.length - 1].days.push(day);
                } else {
                    grouped.push({ days: [day], key: key, closed: !!d.closed, open: d.open || '08:00', close: d.close || '18:00' });
                }
                prev = key;
            });
            const parts = grouped.map(g => {
                const first = shortNames[g.days[0]];
                const last  = shortNames[g.days[g.days.length - 1]];
                const range = g.days.length > 1 ? first + '-' + last : first;
                return range + ': ' + (g.closed ? 'Cerrado' : g.open + ' - ' + g.close);
            });
            const el = document.getElementById('hours-summary-line');
            if (el) el.textContent = parts.join('  •  ') || 'Sin horario configurado';
        }

        // Save Info Form
        async function saveInfo(event) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch('/tenant/{{ $tenant->id }}/update-info', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    alert('✓ Información actualizada correctamente');
                } else {
                    alert('✗ Error al actualizar: ' + (result.message || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('✗ Error al guardar los cambios');
            }
        }

        // Products CRUD
        let currentProductId = null;
        const productsData = @json($products);

        // ── Plan limits exposed from PHP ─────────────────────────────
        const planId      = {{ (int) $plan->id }};
        const planName    = '{{ addslashes($plan->name) }}';
        const productsMax = {{ (int) ($plan->products_limit ?? 6) }};
        const servicesMax = {{ (int) ($plan->services_limit ?? 3) }};
        const NEXT_PLAN   = { 1: { name:'CRECIMIENTO', prods:12, svcs:6 }, 2: { name:'VISIÓN', prods:18, svcs:9 } };
        const SUPPORT_WA  = 'https://wa.me/584120000000'; // ← actualizar con número de soporte real

        // ── Limit-check wrappers (called by "Agregar" buttons) ───────
        function checkAndOpenProductModal() {
            if (productsData.length >= productsMax) {
                openLimitModal('producto');
                return;
            }
            openProductModal();
        }

        function checkAndOpenServiceModal() {
            if (servicesData.length >= servicesMax) {
                openLimitModal('servicio');
                return;
            }
            openServiceModal();
        }

        // ── Limit-reached modal ──────────────────────────────────────
        function openLimitModal(type) {
            const next  = NEXT_PLAN[planId];
            const modal = document.getElementById('limit-modal');
            const title = document.getElementById('limit-modal-title');
            const msg   = document.getElementById('limit-modal-message');
            const cta   = document.getElementById('limit-modal-cta');
            const max   = type === 'producto' ? productsMax : servicesMax;
            const noun  = type === 'producto' ? 'productos' : 'servicios';

            title.textContent = `⚠️ Límite de ${noun} alcanzado`;

            if (next) {
                const nextQty = type === 'producto' ? next.prods : next.svcs;
                msg.innerHTML =
                    `<strong class="text-base-content">Has alcanzado el máximo de ${max} ${noun}</strong> del Plan <em>${planName}</em>.<br><br>` +
                    `Actualiza al Plan <strong class="text-success">${next.name}</strong> y gestiona hasta ` +
                    `<strong class="text-base-content">${nextQty} ${noun}</strong> en tu landing.`;
                cta.innerHTML =
                    `<a href="https://syntiweb.com/planes" target="_blank" rel="noopener noreferrer" class="btn btn-success btn-sm gap-2">` +
                    `🚀 Quiero el Plan ${next.name}</a>`;
            } else {
                // Plan 3 — last plan → contact support
                msg.innerHTML =
                    `<strong class="text-base-content">Has alcanzado el máximo de ${max} ${noun}</strong> del Plan <em>${planName}</em>.<br><br>` +
                    `Para necesidades especiales, nuestro equipo puede diseñar una solución ` +
                    `personalizada para tu negocio. Contáctanos directamente.`;
                cta.innerHTML =
                    `<a href="${SUPPORT_WA}?text=${encodeURIComponent('Hola, soy cliente del Plan VISIÓN y necesito soporte personalizado.')}" ` +
                    `target="_blank" class="btn btn-success btn-sm gap-2">` +
                    `💬 Contactar Soporte</a>`;
            }

            modal.classList.add('show');
            modal.removeAttribute('aria-hidden');
            modal.querySelector('.crud-dialog-close')?.focus();
        }

        function closeLimitModal() {
            const modal = document.getElementById('limit-modal');
            modal.classList.remove('show');
            modal.setAttribute('aria-hidden', 'true');
        }
        // ────────────────────────────────────────────────────────────

        function openProductModal(productId = null) {
            const modal = document.getElementById('product-modal');
            const title = document.getElementById('product-modal-title');
            const form = document.getElementById('product-form');
            
            form.reset();
            currentProductId = productId;

            // Reset gallery UI (Plan 3)
            resetGalleryUI();
            
            if (productId) {
                // Edit mode
                title.textContent = 'Editar Producto';
                const product = productsData.find(p => p.id === productId);
                
                if (product) {
                    document.getElementById('product-id').value = product.id;
                    document.getElementById('product-name').value = product.name;
                    document.getElementById('product-description').value = product.description || '';
                    document.getElementById('product-price').value = product.price_usd;
                    document.getElementById('product-badge').value = product.badge || '';
                    document.getElementById('product-is-active').checked = product.is_active == 1;
                    document.getElementById('product-is-featured').checked = product.is_featured == 1;
                    
                    if (product.image_filename) {
                        const preview = document.getElementById('product-image-preview');
                        const img = document.getElementById('product-image-preview-img');
                        img.src = `/storage/tenants/{{ $tenant->id }}/${product.image_filename}`;
                        preview.style.display = 'block';
                    }

                    // Populate gallery (Plan 3)
                    populateGalleryUI(product);
                }
            } else {
                // Add mode
                title.textContent = 'Agregar Producto';
                document.getElementById('product-image-preview').style.display = 'none';
                showGallerySlots(0);
            }
            
            modal.classList.add('show');
            modal.removeAttribute('aria-hidden');
            modal.querySelector('.crud-dialog-close')?.focus();
        }

        function closeProductModal() {
            const modal = document.getElementById('product-modal');
            modal.classList.remove('show');
            modal.setAttribute('aria-hidden', 'true');
            currentProductId = null;
        }

        function previewProductImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('product-image-preview');
                    const img = document.getElementById('product-image-preview-img');
                    const zone = document.getElementById('product-upload-zone');
                    img.src = e.target.result;
                    preview.style.display = 'block';
                    if (zone) zone.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        }

        function cancelProductImage() {
            document.getElementById('product-image').value = '';
            document.getElementById('product-image-preview').style.display = 'none';
            document.getElementById('product-image-preview-img').src = '';
            const zone = document.getElementById('product-upload-zone');
            if (zone) zone.style.display = '';
        }

        // ── Gallery Functions (Plan 3 / VISIÓN) ──────────────────────
        @if($plan->id === 3)
        /**
         * Reset gallery UI to clean state.
         */
        function resetGalleryUI() {
            const thumbsContainer = document.getElementById('product-gallery-thumbs');
            const previewsContainer = document.getElementById('product-gallery-previews');
            const existingContainer = document.getElementById('product-gallery-existing');
            
            if (thumbsContainer) thumbsContainer.innerHTML = '';
            if (previewsContainer) previewsContainer.innerHTML = '';
            if (existingContainer) existingContainer.classList.add('hidden');
            
            // Reset file inputs
            const g1 = document.getElementById('product-gallery-1');
            const g2 = document.getElementById('product-gallery-2');
            if (g1) g1.value = '';
            if (g2) g2.value = '';
        }

        /**
         * Populate gallery thumbnails from existing product data (edit mode).
         */
        function populateGalleryUI(product) {
            const galleryImages = product.gallery_images || [];
            const thumbsContainer = document.getElementById('product-gallery-thumbs');
            const existingContainer = document.getElementById('product-gallery-existing');

            if (!thumbsContainer || !existingContainer) return;

            thumbsContainer.innerHTML = '';

            if (galleryImages.length > 0) {
                existingContainer.classList.remove('hidden');
                
                galleryImages.forEach(img => {
                    const thumb = document.createElement('div');
                    thumb.className = 'gallery-thumb';
                    thumb.id = `gallery-thumb-${img.id}`;
                    thumb.innerHTML = `
                        <img src="/storage/tenants/{{ $tenant->id }}/${img.image_filename}" alt="Gallery">
                        <button type="button" class="gallery-thumb-delete" onclick="deleteGalleryImage(${product.id}, ${img.id})" title="Eliminar">&times;</button>
                    `;
                    thumbsContainer.appendChild(thumb);
                });
            }

            // Show available upload slots (max 2 total gallery)
            showGallerySlots(galleryImages.length);
        }

        /**
         * Show/hide gallery file upload slots based on how many gallery images exist.
         */
        function showGallerySlots(existingCount) {
            const slot1 = document.getElementById('gallery-slot-1');
            const slot2 = document.getElementById('gallery-slot-2');
            if (!slot1 || !slot2) return;

            const availableSlots = 2 - existingCount;
            slot1.classList.toggle('hidden', availableSlots < 1);
            slot2.classList.toggle('hidden', availableSlots < 2);
        }

        /**
         * Preview a gallery image file before upload.
         */
        function previewGalleryImage(event, slotNumber) {
            const file = event.target.files[0];
            if (!file) return;

            const previewsContainer = document.getElementById('product-gallery-previews');
            
            // Remove existing preview for this slot
            const existingPreview = document.getElementById(`gallery-preview-${slotNumber}`);
            if (existingPreview) existingPreview.remove();

            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'gallery-preview-thumb';
                div.id = `gallery-preview-${slotNumber}`;
                div.innerHTML = `<img src="${e.target.result}" alt="Preview galería ${slotNumber}">`;
                previewsContainer.appendChild(div);
            };
            reader.readAsDataURL(file);
        }

        /**
         * Delete an existing gallery image via API.
         */
        async function deleteGalleryImage(productId, imageId) {
            if (!confirm('¿Eliminar esta imagen de la galería?')) return;

            try {
                const response = await fetch(`/tenant/{{ $tenant->id }}/upload/product/${productId}/gallery/${imageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    }
                });

                const result = await response.json();

                if (result.success) {
                    // Remove thumbnail from DOM
                    const thumb = document.getElementById(`gallery-thumb-${imageId}`);
                    if (thumb) thumb.remove();

                    // Update productsData locally
                    const product = productsData.find(p => p.id === productId);
                    if (product && product.gallery_images) {
                        product.gallery_images = product.gallery_images.filter(gi => gi.id !== imageId);
                        showGallerySlots(product.gallery_images.length);
                        
                        if (product.gallery_images.length === 0) {
                            document.getElementById('product-gallery-existing').classList.add('hidden');
                        }
                    }
                } else {
                    alert('✗ Error: ' + (result.error || 'No se pudo eliminar'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('✗ Error al eliminar imagen de galería');
            }
        }

        /**
         * Upload pending gallery files after product save.
         */
        async function uploadPendingGalleryImages(productId) {
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
            const slots = [
                document.getElementById('product-gallery-1'),
                document.getElementById('product-gallery-2')
            ];

            for (const input of slots) {
                if (input && input.files && input.files[0]) {
                    const formData = new FormData();
                    formData.append('image', input.files[0]);

                    try {
                        const res = await fetch(`/tenant/{{ $tenant->id }}/upload/product/${productId}/gallery`, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrf },
                            body: formData
                        });

                        const result = await res.json();
                        if (!result.success) {
                            console.warn('Gallery upload failed:', result.error);
                        }
                    } catch (err) {
                        console.error('Gallery upload error:', err);
                    }
                }
            }
        }
        @else
        // Plans 1 & 2: no-op gallery functions
        function resetGalleryUI() {}
        function populateGalleryUI() {}
        function showGallerySlots() {}
        @endif
        // ── End Gallery Functions ────────────────────────────────────

        async function saveProduct(event) {
            event.preventDefault();
            
            const productId = document.getElementById('product-id').value;
            const isEdit = productId !== '';
            
            const data = {
                name: document.getElementById('product-name').value,
                description: document.getElementById('product-description').value,
                price_usd: parseFloat(document.getElementById('product-price').value),
                badge: document.getElementById('product-badge').value || null,
                is_active: document.getElementById('product-is-active').checked ? 1 : 0,
                is_featured: document.getElementById('product-is-featured').checked ? 1 : 0
            };

            try {
                const url = isEdit 
                    ? `/tenant/{{ $tenant->id }}/products/${productId}`
                    : `/tenant/{{ $tenant->id }}/products`;
                
                const response = await fetch(url, {
                    method: isEdit ? 'PUT' : 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    const savedProductId = result.product.id;

                    // Handle main image upload if file selected
                    const imageFile = document.getElementById('product-image').files[0];
                    if (imageFile) {
                        const formData = new FormData();
                        formData.append('image', imageFile);
                        
                        await fetch(`/tenant/{{ $tenant->id }}/upload/product/${savedProductId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                            },
                            body: formData
                        });
                    }

                    // Handle gallery image uploads (Plan 3 only)
                    @if($plan->id === 3)
                    await uploadPendingGalleryImages(savedProductId);
                    @endif
                    
                    alert(`✓ Producto ${isEdit ? 'actualizado' : 'creado'} correctamente`);
                    closeProductModal();
                    location.reload();
                } else {
                    alert('✗ Error: ' + (result.message || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('✗ Error al guardar el producto');
            }
        }

        function editProduct(productId) {
            openProductModal(productId);
        }

        async function deleteProduct(productId) {
            if (!confirm('¿Estás seguro de eliminar este producto?')) {
                return;
            }

            try {
                const response = await fetch(`/tenant/{{ $tenant->id }}/products/${productId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    }
                });

                const result = await response.json();

                if (result.success) {
                    alert('✓ Producto eliminado correctamente');
                    location.reload();
                } else {
                    alert('✗ Error al eliminar: ' + (result.message || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('✗ Error al eliminar el producto');
            }
        }

        /**
         * Share product via Web Share API with WhatsApp fallback
         */
        function shareProduct(productId, productName, priceUsd) {
            const tenantName = @json($tenant->business_name);
            const siteUrl = window.location.origin + '/{{ $tenant->subdomain }}';
            const text = `${productName} — $${Number(priceUsd).toFixed(2)} en ${tenantName}`;
            const url = siteUrl;

            if (navigator.share) {
                navigator.share({ title: productName, text: text, url: url }).catch(() => {});
            } else {
                // Fallback: copy to clipboard or open WhatsApp
                const waText = encodeURIComponent(text + '\n' + url);
                window.open('https://wa.me/?text=' + waText, '_blank');
            }
        }

