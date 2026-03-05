        // Services CRUD
        let currentServiceId = null;
        const servicesData = @json($services);

        // ── Service Visual Mode ───────────────────────────────────────
        const SVC_MODE_KEY  = 'svc_mode_{{ $tenant->id }}';
        const PLAN_ID       = {{ $plan->id }};
        // Plan 1: icon-only — never read localStorage for mode
        let serviceModalMode = (PLAN_ID === 1) ? 'icon' : (localStorage.getItem(SVC_MODE_KEY) || 'icon');

        function setGlobalServiceMode(mode) {
            if (PLAN_ID === 1) return;   // Plan 1 is always icon-only
            serviceModalMode = mode;
            localStorage.setItem(SVC_MODE_KEY, mode);
            updateGlobalModeBtns();
        }

        function updateGlobalModeBtns() {
            const iBtn   = document.getElementById('global-mode-icon-btn');
            const imgBtn = document.getElementById('global-mode-image-btn');
            if (!iBtn) return;
            iBtn.classList.toggle('seg-active', serviceModalMode === 'icon');
            imgBtn.classList.toggle('seg-active', serviceModalMode === 'image');
        }

        function setServiceModalMode(mode) {
            serviceModalMode = mode;
            localStorage.setItem(SVC_MODE_KEY, mode);
            updateGlobalModeBtns();

            const iconSect = document.getElementById('svc-section-icon');
            const imgSect  = document.getElementById('svc-section-image');
            const tabIcon  = document.getElementById('svc-tab-icon');
            const tabImg   = document.getElementById('svc-tab-image');

            if (iconSect) iconSect.style.display = (mode === 'icon')  ? '' : 'none';
            if (imgSect)  imgSect.style.display  = (mode === 'image') ? '' : 'none';

            if (tabIcon) tabIcon.classList.toggle('seg-active', mode === 'icon');
            if (tabImg)  tabImg.classList.toggle('seg-active',  mode === 'image');

            // Clear icon name input when switching to image mode
            if (mode === 'image') {
                const hiddenInput = document.getElementById('service-icon-name');
                if (hiddenInput) hiddenInput.value = '';
                iconPickerSelected = '';
                const prevEl    = document.getElementById('icon-preview-el');
                const prevLabel = document.getElementById('icon-preview-label');
                if (prevEl) {
                    prevEl.className = 'iconify tabler--settings size-12 text-primary';
                }
                if (prevLabel) prevLabel.textContent = 'Sin ícono seleccionado';
            }
        }

        // ── Icon Picker ───────────────────────────────────────────────
        const ICON_CATALOG = [
            // Negocios
            {n:'briefcase', l:'Portafolio'},      {n:'building-store', l:'Tienda'},
            {n:'award', l:'Premio'},               {n:'certificate', l:'Certificado'},
            {n:'crown', l:'Premium'},              {n:'diamond', l:'Diamante'},
            {n:'rocket', l:'Lanzamiento'},         {n:'target', l:'Objetivo'},
            {n:'trophy', l:'Trofeo'},              {n:'star', l:'Estrella'},
            {n:'heart', l:'Favorito'},             {n:'thumb-up', l:'Recomendado'},
            {n:'shield-check', l:'Seguridad'},     {n:'rosette-discount-check', l:'Verificado'},
            // Servicios físicos
            {n:'tool', l:'Herramienta'},           {n:'hammer', l:'Construcción'},
            {n:'paint', l:'Pintura'},              {n:'scissors', l:'Estética'},
            {n:'needle-thread', l:'Costura'},      {n:'pencil-bolt', l:'Reparación'},
            {n:'bolt', l:'Electricidad'},          {n:'car', l:'Automotriz'},
            {n:'home', l:'Hogar'},                 {n:'building', l:'Inmobiliaria'},
            {n:'bucket', l:'Limpieza'},            {n:'wash', l:'Lavandería'},
            // Tecnología
            {n:'device-desktop', l:'Computadora'},{n:'device-mobile', l:'Móvil'},
            {n:'wifi', l:'Internet'},              {n:'cpu', l:'Hardware'},
            {n:'code', l:'Desarrollo'},            {n:'cloud', l:'Nube'},
            {n:'headset', l:'Soporte'},            {n:'printer', l:'Impresión'},
            // Foto / Medios
            {n:'camera', l:'Fotografía'},          {n:'video', l:'Video'},
            {n:'microphone', l:'Audio/Podcast'},   {n:'palette', l:'Diseño Gráfico'},
            {n:'ballpen', l:'Escritura'},          {n:'photo', l:'Galería'},
            // Salud y Bienestar
            {n:'stethoscope', l:'Medicina'},       {n:'first-aid-kit', l:'Primeros Auxilios'},
            {n:'activity', l:'Salud'},             {n:'bath', l:'Spa/Bienestar'},
            {n:'barbell', l:'Gimnasio'},           {n:'leaf', l:'Natural/Orgánico'},
            {n:'eye', l:'Óptica/Visión'},          {n:'brain', l:'Psicología'},
            // Educación
            {n:'book', l:'Libro/Educación'},       {n:'school', l:'Escuela'},
            {n:'pencil', l:'Enseñanza'},           {n:'flask', l:'Laboratorio'},
            // Comida y Bebida
            {n:'soup', l:'Cocina'},                {n:'pizza', l:'Pizza'},
            {n:'coffee', l:'Café'},                {n:'apple', l:'Nutrición'},
            // Logística
            {n:'shopping-cart', l:'Compras'},      {n:'package', l:'Paquete'},
            {n:'truck', l:'Entrega/Delivery'},     {n:'map-pin', l:'Ubicación'},
            // Comunicación / Agenda
            {n:'phone', l:'Teléfono'},             {n:'mail', l:'Email'},
            {n:'message-circle', l:'Chat'},        {n:'calendar', l:'Agenda'},
            {n:'clock', l:'Horario'},              {n:'users', l:'Clientes/Equipo'},
            {n:'user-check', l:'Verificado'},
        ];

        let iconPickerSelected = '';

        function renderIconGrid(filter = '') {
            const grid = document.getElementById('icon-picker-grid');
            if (!grid) return;

            const term     = filter.toLowerCase().trim();
            const filtered = term
                ? ICON_CATALOG.filter(ic => ic.n.includes(term) || ic.l.toLowerCase().includes(term))
                : ICON_CATALOG;

            grid.innerHTML = '';

            if (filtered.length === 0) {
                grid.innerHTML = `<div class="col-span-6 text-center text-base-content/30 py-6 text-sm">Sin resultados para "<em>${filter}</em>"</div>`;
                return;
            }

            filtered.forEach(ic => {
                const selected = iconPickerSelected === ic.n;
                const el = document.createElement('div');
                el.className   = 'icon-pick-item' + (selected ? ' selected' : '');
                el.title       = ic.l;
                el.dataset.name = ic.n;
                el.innerHTML = `
                    <span class="iconify tabler--${ic.n} size-10 ${selected ? 'text-white' : 'text-primary'}"></span>
                    <span class="text-[10px] text-center leading-tight font-medium truncate w-full">${ic.l}</span>
                `;
                el.addEventListener('click', () => selectIcon(ic.n, ic.l));
                grid.appendChild(el);
            });
        }

        function filterIcons(val) { renderIconGrid(val); }

        function selectIcon(iconName, iconLabel) {
            iconPickerSelected = iconName;
            const hidden = document.getElementById('service-icon-name');
            if (hidden) hidden.value = iconName;
            const prevEl    = document.getElementById('icon-preview-el');
            const prevLabel = document.getElementById('icon-preview-label');
            if (prevEl) {
                prevEl.className = 'iconify tabler--' + iconName + ' size-12 text-primary';
            }
            if (prevLabel) prevLabel.textContent = iconLabel;
            const searchVal = document.getElementById('icon-search')?.value || '';
            renderIconGrid(searchVal);
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateGlobalModeBtns();

            // ── Live Clock ────────────────────────────────────────────
            function updateClock() {
                const el = document.getElementById('live-clock');
                if (!el) return;
                el.textContent = new Date().toLocaleTimeString('es-VE', {
                    hour: '2-digit', minute: '2-digit', second: '2-digit'
                });
            }
            updateClock();
            setInterval(updateClock, 1000);
        });

        function openServiceModal(serviceId = null) {
            const modal = document.getElementById('service-modal');
            const title = document.getElementById('service-modal-title');
            const form  = document.getElementById('service-form');

            form.reset();
            currentServiceId = serviceId;

            if (serviceId) {
                // Edit mode
                title.textContent = 'Editar Servicio';
                const service = servicesData.find(s => s.id === serviceId);

                if (service) {
                    document.getElementById('service-id').value = service.id;
                    document.getElementById('service-name').value = service.name;
                    document.getElementById('service-description').value = service.description || '';
                    document.getElementById('service-is-active').checked = service.is_active == 1;

                    // Restore icon or image mode based on what the service has
                    const hasIcon  = !!service.icon_name;
                    const hasImage = !!service.image_filename;
                    const modeToSet = hasImage && !hasIcon ? 'image' : serviceModalMode;

                    if (hasIcon) {
                        iconPickerSelected = service.icon_name;
                        const hidden = document.getElementById('service-icon-name');
                        if (hidden) hidden.value = service.icon_name;
                        const prevEl    = document.getElementById('icon-preview-el');
                        const prevLabel = document.getElementById('icon-preview-label');
                        if (prevEl) {
                            prevEl.className = 'iconify tabler--' + service.icon_name + ' size-12 text-primary';
                        }
                        if (prevLabel) prevLabel.textContent = service.icon_name;
                    }

                    if (hasImage && modeToSet === 'image') {
                        const preview = document.getElementById('service-image-preview');
                        const img     = document.getElementById('service-image-preview-img');
                        if (preview && img) {
                            img.src = `/storage/tenants/{{ $tenant->id }}/${service.image_filename}`;
                            preview.style.display = 'block';
                        }
                    }

                    setServiceModalMode(modeToSet);
                }
            } else {
                // Add mode — reset picker
                title.textContent = 'Agregar Servicio';
                iconPickerSelected = '';
                const hidden = document.getElementById('service-icon-name');
                if (hidden) hidden.value = '';
                const prevEl    = document.getElementById('icon-preview-el');
                const prevLabel = document.getElementById('icon-preview-label');
                if (prevEl) {
                    prevEl.className = 'iconify tabler--settings size-12 text-primary';
                }
                if (prevLabel) prevLabel.textContent = 'Sin ícono seleccionado';
                const imgPrev = document.getElementById('service-image-preview');
                if (imgPrev) imgPrev.style.display = 'none';
                setServiceModalMode(serviceModalMode);
            }

            // Render icon grid (always)
            const searchInput = document.getElementById('icon-search');
            if (searchInput) searchInput.value = '';
            renderIconGrid('');

            modal.classList.add('show');
            modal.removeAttribute('aria-hidden');
            modal.querySelector('.crud-dialog-close')?.focus();
        }

        function closeServiceModal() {
            const modal = document.getElementById('service-modal');
            modal.classList.remove('show');
            modal.setAttribute('aria-hidden', 'true');
            currentServiceId = null;
        }

        function previewServiceImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('service-image-preview');
                    const img = document.getElementById('service-image-preview-img');
                    const zone = document.getElementById('service-upload-zone');
                    img.src = e.target.result;
                    preview.style.display = 'block';
                    if (zone) zone.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        }

        function cancelServiceImage() {
            document.getElementById('service-image').value = '';
            document.getElementById('service-image-preview').style.display = 'none';
            document.getElementById('service-image-preview-img').src = '';
            const zone = document.getElementById('service-upload-zone');
            if (zone) zone.style.display = '';
        }

        async function saveService(event) {
            event.preventDefault();
            
            const serviceId = document.getElementById('service-id').value;
            const isEdit = serviceId !== '';
            
            // Plan 1 is always icon mode regardless of localStorage
            const currentMode = (PLAN_ID === 1) ? 'icon' : serviceModalMode;
            const iconNameVal = document.getElementById('service-icon-name')?.value?.trim() || null;

            const data = {
                name: document.getElementById('service-name').value,
                description: document.getElementById('service-description').value,
                is_active: document.getElementById('service-is-active').checked ? 1 : 0,
                icon_name: currentMode === 'icon' ? (iconNameVal || null) : null,
            };

            try {
                const url = isEdit 
                    ? `/tenant/{{ $tenant->id }}/services/${serviceId}`
                    : `/tenant/{{ $tenant->id }}/services`;
                
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
                    // Handle image upload if in image mode and a file was selected
                    const imageInput = document.getElementById('service-image');
                    const imageFile  = currentMode === 'image' ? imageInput?.files?.[0] : null;
                    if (imageFile) {
                        const formData = new FormData();
                        formData.append('image', imageFile);
                        
                        await fetch(`/tenant/{{ $tenant->id }}/upload/service/${result.service.id}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                            },
                            body: formData
                        });
                    }
                    
                    alert(`✓ Servicio ${isEdit ? 'actualizado' : 'creado'} correctamente`);
                    closeServiceModal();
                    dashboardReload();
                } else {
                    alert('✗ Error: ' + (result.message || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('✗ Error al guardar el servicio');
            }
        }

        function editService(serviceId) {
            openServiceModal(serviceId);
        }

        async function deleteService(serviceId) {
            if (!confirm('¿Estás seguro de eliminar este servicio?')) {
                return;
            }

            try {
                const response = await fetch(`/tenant/{{ $tenant->id }}/services/${serviceId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    }
                });

                const result = await response.json();

                if (result.success) {
                    alert('✓ Servicio eliminado correctamente');
                    dashboardReload();
                } else {
                    alert('✗ Error al eliminar: ' + (result.message || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('✗ Error al eliminar el servicio');
            }
        }

        // ── Image Uploads: Logo, Hero + Drag & Drop ─────────────
        async function _uploadImage(file, type) {
            if (!file || !file.type.startsWith('image/')) {
                showToast('❌ Selecciona un archivo de imagen válido', 'error');
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                showToast('❌ La imagen no debe superar 5MB', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('image', file);

            const dropzone = document.getElementById(type + '-dropzone');
            if (dropzone) dropzone.style.opacity = '0.5';

            try {
                const response = await fetch('/tenant/{{ $tenant->id }}/upload/' + type, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content },
                    body: formData
                });

                const result = await response.json();
                if (dropzone) dropzone.style.opacity = '1';

                if (result.success) {
                    const previewId = type + '-preview';
                    const placeholderId = type + '-placeholder';
                    const placeholder = document.getElementById(placeholderId);
                    let preview = document.getElementById(previewId);

                    if (placeholder) placeholder.style.display = 'none';

                    const newSrc = result.url + '?t=' + Date.now();
                    const imgClass = type === 'logo' ? 'max-w-full max-h-full object-contain' : 'w-full h-full object-cover';

                    if (preview) {
                        preview.src = newSrc;
                        preview.style.display = 'block';
                    } else {
                        const img = document.createElement('img');
                        img.id = previewId;
                        img.src = newSrc;
                        img.alt = type.charAt(0).toUpperCase() + type.slice(1);
                        img.className = imgClass;
                        const dz = document.getElementById(type + '-dropzone');
                        if (dz) dz.appendChild(img);
                    }
                    showToast('✅ ' + (type === 'logo' ? 'Logo' : 'Imagen hero') + ' actualizado correctamente');
                } else {
                    showToast('❌ Error al subir ' + type + ': ' + (result.error || result.message || 'Error desconocido'), 'error');
                }
            } catch (err) {
                if (dropzone) dropzone.style.opacity = '1';
                console.error('upload ' + type + ':', err);
                showToast('❌ Error de red al subir ' + type, 'error');
            }
        }

        function uploadLogo(event) { _uploadImage(event.target.files[0], 'logo'); }
        function uploadHero(event) { _uploadImage(event.target.files[0], 'hero'); }

        function handleDropUpload(event, type) {
            const file = event.dataTransfer.files[0];
            if (file) _uploadImage(file, type);
        }

