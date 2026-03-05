
    {{-- ═══ SortableJS CDN + global drag-and-drop init ═══ --}}
    <style>
        .sortable-ghost { opacity: 0.3 !important; }
        .sortable-drag  { box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5) !important; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        // ── Toast global ──────────────────────────────────────────────
        window.showToast = function(message, type) {
            const toast = document.createElement('div');
            const bg    = type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6';
            toast.textContent = message;
            Object.assign(toast.style, {
                position: 'fixed', bottom: '24px', right: '24px', zIndex: '99999',
                background: bg, color: '#fff', padding: '12px 20px',
                borderRadius: '10px', fontSize: '14px', fontWeight: '600',
                boxShadow: '0 4px 20px rgba(0,0,0,0.3)', opacity: '0',
                transition: 'opacity 0.3s ease', maxWidth: '320px'
            });
            document.body.appendChild(toast);
            requestAnimationFrame(() => { toast.style.opacity = '1'; });
            setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 2500);
            // Anunciar mensaje a lectores de pantalla via aria-live
            const announcer = document.getElementById('toast-announcer');
            if (announcer) { announcer.textContent = ''; requestAnimationFrame(() => { announcer.textContent = message; }); }
        };

        // ── Sortable init (se llama cada vez que abre tab Diseño) ────
        window._sortableInstance = null;
        window.initSortable = function() {
            if (typeof Sortable === 'undefined') {
                console.error('❌ SortableJS no cargó — D&D no disponible');
                return;
            }
            const sortableEl = document.getElementById('sortable-sections');
            if (!sortableEl) { console.error('❌ sortable-sections no encontrado'); return; }

            // Destruir instancia anterior si existe
            if (window._sortableInstance) {
                try { window._sortableInstance.destroy(); } catch(e) {}
                window._sortableInstance = null;
            }

            window._sortableInstance = new Sortable(sortableEl, {
                handle: '.drag-handle',
                animation: 200,
                ghostClass: 'sortable-ghost',
                dragClass:  'sortable-drag',
                forceFallback: false,
                onEnd: function() { saveSectionsOrder(); }
            });

            console.log('✅ SortableJS listo — ' + sortableEl.children.length + ' secciones');
        };

        function saveSectionsOrder() {
            const sortableEl = document.getElementById('sortable-sections');
            if (!sortableEl) return;
            const sections = [];
            sortableEl.querySelectorAll('.section-item').forEach((item, index) => {
                const name    = item.dataset.section;
                const toggle  = item.querySelector('.section-toggle');
                const visible = toggle ? toggle.checked : true;
                sections.push({ name, visible, order: index });
            });
            fetch(`/tenant/{{ $tenant->id }}/dashboard/save-section-order`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ sections_order: sections })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) { window.showToast('\u2705 Orden guardado', 'success'); }
                else              { window.showToast('\u274c Error al guardar', 'error'); }
            })
            .catch(() => window.showToast('\u274c Error de red', 'error'));
        }

        // ── Mover sección con flechas ▲▼ ─────────────────────────────
        function moveSection(key, direction) {
            const container = document.getElementById('sortable-sections');
            const items     = Array.from(container.querySelectorAll('.section-item'));
            const idx       = items.findIndex(el => el.dataset.section === key);
            const target    = idx + direction;
            if (target < 0 || target >= items.length) return;
            // Reordenar en DOM
            if (direction === -1) {
                container.insertBefore(items[idx], items[target]);
            } else {
                container.insertBefore(items[target], items[idx]);
            }
            saveSectionsOrder();
        }

        // ── Testimonials Global Storage ────────────────────────────────
        let testimonialData = [
            @foreach($savedTestimonials as $ti => $testim)
            { name: '{{ addslashes($testim['name'] ?? '') }}', title: '{{ addslashes($testim['title'] ?? '') }}', text: '{{ addslashes($testim['text'] ?? '') }}', rating: {{ $testim['rating'] ?? 5 }} },
            @endforeach
        ];

        function renderTestimonialsUI() {
            const grid = document.getElementById('testimonials-grid');
            if (!grid) return;
            grid.innerHTML = testimonialData.map((t, i) => {
                const stars = '★'.repeat(t.rating || 5);
                const has = t.name || t.text;
                return `
                <div class="rounded-lg border p-3 transition-all ${has ? 'border-primary/20 bg-primary/5' : 'border-border bg-muted/30'}"
                     data-testimonial-index="${i}">
                    <div class="flex items-start justify-between mb-2">
                        <span class="text-[10px] font-bold text-muted-foreground-1 uppercase tracking-wider">#${i + 1}</span>
                        <span class="text-sm text-yellow-500">${stars}</span>
                    </div>
                    <h4 class="text-sm font-semibold text-foreground line-clamp-1">${escHtml(t.name) || '(vacío)'}</h4>
                    <p class="text-xs text-muted-foreground-1 line-clamp-1">${escHtml(t.title) || '(sin cargo)'}</p>
                    <p class="text-xs text-muted-foreground-1 line-clamp-2 mt-1">${escHtml(t.text) || '(vacío)'}</p>
                    <div class="flex gap-2 mt-3">
                        <button type="button" class="p-1.5 rounded-lg bg-primary text-primary-foreground hover:bg-primary-hover transition-colors" onclick="editTestimonial(${i})" title="Editar">
                            <span class="iconify tabler--pencil size-4" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="p-1.5 rounded-lg bg-red-600 text-white hover:bg-red-700 transition-colors" onclick="deleteTestimonial(${i})" title="Eliminar">
                            <span class="iconify tabler--trash size-4" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>`;
            }).join('');
        }

        function escHtml(str) {
            if (!str) return '';
            return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }

        function editTestimonial(index) {
            const t = testimonialData[index];
            if (!t) return;
            document.getElementById('testimonial-edit-index').value = index;
            document.getElementById('testimonial-name').value = t.name || '';
            document.getElementById('testimonial-title').value = t.title || '';
            document.getElementById('testimonial-text').value = t.text || '';
            document.getElementById('testimonial-rating').value = t.rating || 5;
            document.getElementById('testimonial-modal').style.display = 'flex';
        }

        function closeTestimonialModal() {
            document.getElementById('testimonial-modal').style.display = 'none';
            document.getElementById('testimonial-form').reset();
            document.getElementById('testimonial-edit-index').value = '';
        }

        function saveTestimonialItem(event) {
            event.preventDefault();
            const indexStr = document.getElementById('testimonial-edit-index').value;
            const name = document.getElementById('testimonial-name').value.trim();
            const title = document.getElementById('testimonial-title').value.trim();
            const text = document.getElementById('testimonial-text').value.trim();
            const rating = parseInt(document.getElementById('testimonial-rating').value);

            if (!name || !text) {
                alert('✗ Nombre y testimonio son obligatorios');
                return;
            }

            if (indexStr === '') {
                // Nuevo item
                testimonialData.push({ name, title, text, rating });
            } else {
                // Editar item existente
                const index = parseInt(indexStr);
                testimonialData[index] = { name, title, text, rating };
            }
            renderTestimonialsUI();
            closeTestimonialModal();
        }

        function deleteTestimonial(index) {
            if (!confirm('¿Eliminar este testimonial?')) return;
            testimonialData.splice(index, 1);
            renderTestimonialsUI();
        }

        function addTestimonial() {
            // Abre el modal en modo "crear nuevo" (sin índice)
            document.getElementById('testimonial-edit-index').value = '';
            document.getElementById('testimonial-name').value = '';
            document.getElementById('testimonial-title').value = '';
            document.getElementById('testimonial-text').value = '';
            document.getElementById('testimonial-rating').value = '5';
            document.getElementById('testimonial-modal').style.display = 'flex';
        }

        // ── Guardar Testimonios ────────────────────────────────────────
        function saveTestimonials() {
            // Ya no hay items vacíos porque los removemos con splice()
            fetch(`/tenant/{{ $tenant->id }}/update-testimonials`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ testimonials: testimonialData })
            })
            .then(r => r.json())
            .then(d => {
                if (d.success) {
                    window.showToast('✅ Testimonios guardados', 'success');
                    dashboardReload();
                } else {
                    window.showToast('✗ Error', 'error');
                }
            })
            .catch(() => window.showToast('✗ Error de red', 'error'));
        }

        // ── FAQ Global Storage ────────────────────────────────────────
        let faqData = [
            @foreach($savedFaq as $fi => $fitem)
            { question: '{{ addslashes($fitem['question'] ?? '') }}', answer: '{{ addslashes($fitem['answer'] ?? '') }}' },
            @endforeach
        ];

        function renderFaqUI() {
            const grid = document.getElementById('faq-grid');
            if (!grid) return;
            grid.innerHTML = faqData.map((f, i) => {
                const has = f.question || f.answer;
                return `
                <div class="rounded-lg border p-3 transition-all ${has ? 'border-secondary/20 bg-secondary/5' : 'border-border bg-muted/30'}"
                     data-faq-index="${i}">
                    <span class="text-[10px] font-bold text-muted-foreground-1 uppercase tracking-wider">#${i + 1}</span>
                    <h4 class="text-sm font-semibold text-foreground mt-1 line-clamp-2">${escHtml(f.question) || '(vacío)'}</h4>
                    <p class="text-xs text-muted-foreground-1 mt-1 line-clamp-2">${escHtml(f.answer) || '(vacío)'}</p>
                    <div class="flex gap-2 mt-3">
                        <button type="button" class="p-1.5 rounded-lg bg-primary text-primary-foreground hover:bg-primary-hover transition-colors" onclick="editFaq(${i})" title="Editar">
                            <span class="iconify tabler--pencil size-4" aria-hidden="true"></span>
                        </button>
                        <button type="button" class="p-1.5 rounded-lg bg-red-600 text-white hover:bg-red-700 transition-colors" onclick="deleteFaq(${i})" title="Eliminar">
                            <span class="iconify tabler--trash size-4" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>`;
            }).join('');
        }

        function editFaq(index) {
            const f = faqData[index];
            if (!f) return;
            document.getElementById('faq-edit-index').value = index;
            document.getElementById('faq-question').value = f.question || '';
            document.getElementById('faq-answer').value = f.answer || '';
            document.getElementById('faq-modal').style.display = 'flex';
        }

        function closeFaqModal() {
            document.getElementById('faq-modal').style.display = 'none';
            document.getElementById('faq-form').reset();
            document.getElementById('faq-edit-index').value = '';
        }

        function saveFaqItem(event) {
            event.preventDefault();
            const indexStr = document.getElementById('faq-edit-index').value;
            const question = document.getElementById('faq-question').value.trim();
            const answer = document.getElementById('faq-answer').value.trim();

            if (!question || !answer) {
                alert('✗ Pregunta y respuesta son obligatorias');
                return;
            }

            if (indexStr === '') {
                // Nuevo item
                faqData.push({ question, answer });
            } else {
                // Editar item existente
                const index = parseInt(indexStr);
                faqData[index] = { question, answer };
            }
            renderFaqUI();
            closeFaqModal();
        }

        function deleteFaq(index) {
            if (!confirm('¿Eliminar esta pregunta?')) return;
            faqData.splice(index, 1);
            renderFaqUI();
        }

        function addFaq() {
            // Abre el modal en modo "crear nuevo" (sin índice)
            document.getElementById('faq-edit-index').value = '';
            document.getElementById('faq-question').value = '';
            document.getElementById('faq-answer').value = '';
            document.getElementById('faq-modal').style.display = 'flex';
        }

        // ── Guardar FAQ ───────────────────────────────────────────────
        function saveFaq() {
            // Ya no hay items vacíos porque los removemos con splice()
            fetch(`/tenant/{{ $tenant->id }}/update-faq`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ faq: faqData })
            })
            .then(r => r.json())
            .then(d => {
                if (d.success) {
                    window.showToast('✅ FAQ guardado', 'success');
                    dashboardReload();
                } else {
                    window.showToast('✗ Error', 'error');
                }
            })
            .catch(() => window.showToast('✗ Error de red', 'error'));
        }

        // ── Toggle individual de sección ──────────────────────────────
        function toggleSection(section, visible) {
            fetch(`/tenant/{{ $tenant->id }}/dashboard/toggle-section`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ section, visible })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    window.showToast('✅ ' + (visible ? 'Sección activada' : 'Sección desactivada'), 'success');
                } else {
                    window.showToast('❌ Error al guardar', 'error');
                }
            })
            .catch(() => window.showToast('❌ Error de red', 'error'));
        }
    </script>
