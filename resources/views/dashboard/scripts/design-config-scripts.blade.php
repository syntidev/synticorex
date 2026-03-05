        function downloadQRSVG() {
            const qrContainer = document.getElementById('qr-display');
            if (!qrContainer) return;
            const svgEl = qrContainer.querySelector('svg');
            if (!svgEl) {
                showToast('❌ No se encontró el SVG del QR', 'error');
                return;
            }
            const svgData = new XMLSerializer().serializeToString(svgEl);
            const blob = new Blob([svgData], { type: 'image/svg+xml;charset=utf-8' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'qr-{{ $tenant->subdomain }}.svg';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }
        // ── End Image Uploads ────────────────────────────────────────

        // Design Tab: Custom Palette (Plan 3)
        function applyCustomPalette() {
            const color = document.getElementById('custom-primary')?.value;
            if (!color) return;
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch(`/tenant/{{ $tenant->id }}/dashboard/save-custom-palette`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({ primary: color, secondary: color, accent: color, base: '#ffffff' })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    document.documentElement.style.setProperty('--primary', color);
                    document.documentElement.style.setProperty('--primary-hover', color);
                    document.documentElement.style.setProperty('--primary-500', color);
                    document.documentElement.style.setProperty('--primary-600', color);
                    showToast('✅ Color aplicado');
                    setTimeout(() => dashboardReload(), 1500);
                } else {
                    showToast('❌ ' + (data.message || 'Error'), 'error');
                }
            })
            .catch(err => showToast('❌ ' + err.message, 'error'));
        }

        // Design Tab: Theme Update (Preline)
        function updateTheme(theme) {
            fetch(`/tenant/{{ $tenant->id }}/update-theme`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ theme_slug: theme })
            })
            .then(r => {
                if (!r.ok) {
                    return r.json().catch(() => ({ success: false, message: 'Error HTTP ' + r.status })).then(errData => {
                        throw new Error(errData.message || 'Error ' + r.status);
                    });
                }
                return r.json();
            })
            .then(data => {
                if (data.success) {
                    document.documentElement.setAttribute('data-theme', 'theme-' + theme);
                    showToast('✅ Tema ' + theme + ' aplicado');
                    setTimeout(() => dashboardReload(), 1000);
                } else {
                    showToast('❌ ' + (data.message || 'No se pudo aplicar el tema'), 'error');
                    console.error('Theme update failed:', data);
                }
            })
            .catch(err => {
                showToast('❌ ' + err.message, 'error');
                console.error('Theme update error:', err);
            });
        }

        // (Duplicates removed — unified upload functions are above)

        // ══════════════════════════════════════════════════════════════
        // Analytics Tab: Load Analytics Data
        // ══════════════════════════════════════════════════════════════
        let analyticsChart = null;

        async function loadAnalytics() {
            const kpiIds = ['visitors-today','visitors-week','whatsapp-clicks','qr-scans','call-clicks','currency-toggles','avg-time'];

            // Loading state — Resiliencia Visual
            kpiIds.forEach(id => {
                const el = document.getElementById(id);
                if (el) { el.innerHTML = '<span class="loading loading-dots loading-xs"></span>'; }
            });

            try {
                const response = await fetch('/tenant/{{ $tenant->id }}/analytics');
                if (!response.ok) throw new Error('HTTP ' + response.status);
                const result = await response.json();

                if (result.success) {
                    const data = result.data;

                    document.getElementById('visitors-today').textContent = data.visitors_today || 0;
                    document.getElementById('visitors-week').textContent = data.visitors_week || 0;
                    document.getElementById('whatsapp-clicks').textContent = data.whatsapp_clicks || 0;
                    document.getElementById('qr-scans').textContent = data.qr_scans || 0;
                    document.getElementById('call-clicks').textContent = data.call_clicks || 0;
                    document.getElementById('currency-toggles').textContent = data.currency_toggles || 0;
                    document.getElementById('avg-time').textContent = data.avg_time_on_page || 0;

                    renderAnalyticsChart(data.last_7_days);
                } else {
                    // Error state
                    kpiIds.forEach(id => {
                        const el = document.getElementById(id);
                        if (el) el.textContent = '—';
                    });
                }
            } catch (error) {
                console.error('Error loading analytics:', error);
                // Error state — Resiliencia Visual
                kpiIds.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.textContent = '—';
                });
                showToast('❌ Error al cargar analytics', 'error');
            }
        }

        function renderAnalyticsChart(last7Days) {
            const ctx = document.getElementById('analytics-chart');
            if (!ctx) return;

            // Destruir gráfico anterior si existe
            if (analyticsChart) {
                analyticsChart.destroy();
            }

            const labels = last7Days.map(d => {
                const date = new Date(d.date);
                return date.toLocaleDateString('es-ES', { weekday: 'short', day: 'numeric' });
            });
            const visitors = last7Days.map(d => d.visitors);

            analyticsChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Visitantes',
                        data: visitors,
                        backgroundColor: 'rgba(87, 13, 248, 0.1)',
                        borderColor: 'rgba(87, 13, 248, 1)',
                        borderWidth: 2,
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        // Cargar analytics cuando se abre el tab
        document.addEventListener('DOMContentLoaded', function() {
            const analyticsTab = document.getElementById('tab-analytics-btn');
            if (analyticsTab) {
                analyticsTab.addEventListener('click', function() {
                    setTimeout(() => loadAnalytics(), 100);
                });
            }
        });

        // Config Tab: Update Dollar Rate (USD + EUR)
        async function updateDollarRate() {
            try {
                const csrf = document.querySelector('meta[name="csrf-token"]').content;
                const [usdRes, eurRes] = await Promise.all([
                    fetch('/api/dollar-rate/refresh', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf } }),
                    fetch('/api/euro-rate/refresh',   { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf } }),
                ]);
                const usd = await usdRes.json();
                const eur = await eurRes.json();

                if (usd.success && usd.rate) {
                    const formatted = usd.rate.toFixed(2);
                    const dvEl = document.getElementById('dollar-rate-value');
                    const hdEl = document.getElementById('header-dollar-rate');
                    if (dvEl) dvEl.textContent = formatted;
                    if (hdEl) hdEl.textContent = formatted;
                }

                if (eur.success && eur.rate) {
                    const eurEl = document.getElementById('euro-rate-value');
                    if (eurEl) eurEl.textContent = eur.rate.toFixed(2);
                }

                if (usd.success) {
                    const eurMsg = eur.success ? ` | € ${eur.rate?.toFixed(2)}` : '';
                    showToast('✅ USD Bs. ' + usd.rate.toFixed(2) + eurMsg, 'success');
                } else {
                    showToast('❌ No se pudo actualizar la tasa', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('❌ Error al actualizar la tasa', 'error');
            }
        }

        // Analytics Tab: Toggle Business Status (Large)
        async function toggleBusinessStatusLarge() {
            const toggle = document.getElementById('status-toggle-large');
            const tenantId = {{ $tenant->id }};
            
            try {
                const response = await fetch(`/tenant/${tenantId}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const result = await response.json();

                if (result.success) {
                    // Actualizar UI
                    const statusText = document.querySelector('#tab-analytics span[style*="color: #00cc66"]');
                    if (statusText) {
                        const isOpen = toggle.checked;
                        statusText.textContent = isOpen ? '🟢 Abierto' : '🔴 Cerrado';
                    }
                } else {
                    // Revertir el toggle si falla
                    toggle.checked = !toggle.checked;
                    alert('✗ Error al cambiar estado');
                }
            } catch (error) {
                console.error('Error:', error);
                toggle.checked = !toggle.checked;
                alert('✗ Error al cambiar el estado');
            }
        }

        // Config Tab: Currency Symbol Toggle UI Only
        function updateCurrencySymbolUI() {
            const toggle = document.getElementById('currency-symbol-switch');
            const slider = document.getElementById('currency-slider');
            const refLabel = document.getElementById('symbol-ref-label');
            const dollarLabel = document.getElementById('symbol-dollar-label');
            
            // Update UI only
            if (toggle.checked) {
                slider.style.transform = 'translateX(26px)';
                slider.style.backgroundColor = '#2B6FFF';
                slider.parentElement.children[1].style.backgroundColor = '#2B6FFF';
                refLabel.style.color = '#6b7280';
                dollarLabel.style.color = '#2B6FFF';
            } else {
                slider.style.transform = 'translateX(0)';
                slider.style.backgroundColor = '#6b7280';
                slider.parentElement.children[1].style.backgroundColor = '#1e2a42';
                refLabel.style.color = '#2B6FFF';
                dollarLabel.style.color = '#6b7280';
            }
        }

        // Config Tab: Save Complete Currency Configuration
        async function saveCurrencyConfig() {
            const symbol       = document.getElementById('currency-symbol-switch').checked ? '$' : 'REF';
            const display_mode = document.querySelector('input[name="display_mode"]:checked')?.value;
            const tenantId     = {{ $tenant->id }};
            
            console.log('Payload moneda:', {symbol, display_mode});
            
            if (!display_mode) {
                alert('✗ Seleccioná un modo de visualización');
                return;
            }
            
            try {
                const response = await fetch(`/tenant/${tenantId}/update-currency-config`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ 
                        symbol:       symbol,
                        display_mode: display_mode
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('✓ Configuración de moneda guardada correctamente');
                } else {
                    alert('✗ ' + (data.message || 'Error al guardar configuración'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('✗ Error al guardar configuración');
            }
        }

        // Config Tab: Update PIN
        async function updatePin() {
            const currentPin = document.getElementById('current-pin').value;
            const newPin = document.getElementById('new-pin').value;
            const confirmPin = document.getElementById('confirm-pin').value;
            const tenantId = {{ $tenant->id }};
            
            // Validation
            if (!currentPin || !newPin || !confirmPin) {
                alert('✗ Todos los campos son obligatorios');
                return;
            }
            
            if (!/^\d{4}$/.test(currentPin) || !/^\d{4}$/.test(newPin)) {
                alert('✗ El PIN debe tener exactamente 4 dígitos');
                return;
            }
            
            if (newPin !== confirmPin) {
                alert('✗ El PIN nuevo y la confirmación no coinciden');
                return;
            }
            
            if (currentPin === newPin) {
                alert('✗ El PIN nuevo debe ser diferente al actual');
                return;
            }
            
            try {
                const response = await fetch(`/tenant/${tenantId}/update-pin`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        current_pin: currentPin,
                        new_pin: newPin,
                        new_pin_confirmation: confirmPin
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('✓ PIN actualizado correctamente');
                    document.getElementById('pin-form').reset();
                } else {
                    alert('✗ ' + (data.message || 'Error al actualizar PIN'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('✗ Error al actualizar PIN');
            }
        }

        // Reset Form
        function resetForm(formId) {
            document.getElementById(formId).reset();
        }

        // ── Social Networks ──────────────────────────────────────────
        @php
            $plan1NetworksList = ['instagram', 'facebook', 'tiktok', 'linkedin'];
            $allNetworksList   = ['instagram', 'facebook', 'tiktok', 'linkedin', 'youtube', 'x'];
        @endphp
        let selectedSocialNetwork = '{{ $plan1Selected ?? '' }}';

        function selectSocialNetwork(key) {
            // Update selected state
            selectedSocialNetwork = key;

            // Update radio labels visually using FlyonUI classes
            @foreach($plan1NetworksList as $k)
            const el_{{ $k }} = document.getElementById('social-radio-label-{{ $k }}');
            if (key === '{{ $k }}') {
                el_{{ $k }}.className = 'btn btn-sm gap-1.5 btn-primary cursor-pointer';
            } else {
                el_{{ $k }}.className = 'btn btn-sm gap-1.5 btn-ghost border border-base-content/20 cursor-pointer';
            }
            @endforeach

            // Update label and placeholder
            const meta = {
                instagram: { label: 'Instagram',   placeholder: '@tuusuario' },
                facebook:  { label: 'Facebook',    placeholder: '@pagina o URL' },
                tiktok:    { label: 'TikTok',      placeholder: '@tuusuario' },
                linkedin:  { label: 'LinkedIn',    placeholder: 'URL o usuario' },
            };
            const networkLabel = document.getElementById('social-plan1-network-label');
            const handleInput  = document.getElementById('social-plan1-handle');
            if (networkLabel) networkLabel.textContent = '(' + (meta[key]?.label || '') + ')';
            if (handleInput) {
                handleInput.placeholder = meta[key]?.placeholder || '';
                handleInput.disabled = false;
            }
        }

        async function saveSocialNetworks() {
            const tenantId = {{ $tenant->id }};
            const plan = {{ $plan->id }};
            let payload = {};

            if (plan === 1) {
                if (!selectedSocialNetwork) {
                    alert('✗ Selecciona una red social primero');
                    return;
                }
                const handle = document.getElementById('social-plan1-handle')?.value?.trim();
                if (!handle) {
                    alert('✗ Ingresa el usuario o enlace de tu red social');
                    return;
                }
                payload[selectedSocialNetwork] = handle;
            } else {
                @foreach($allNetworksList as $k)
                const val_{{ $k }} = document.getElementById('social-{{ $k }}')?.value?.trim();
                if (val_{{ $k }}) payload['{{ $k }}'] = val_{{ $k }};
                @endforeach
            }

            try {
                const response = await fetch(`/tenant/${tenantId}/update-social-networks`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();
                if (result.success) {
                    alert('✓ Redes sociales guardadas correctamente');
                } else {
                    alert('✗ ' + (result.message || 'Error al guardar'));
                }
            } catch (err) {
                console.error('Error:', err);
                alert('✗ Error al guardar redes sociales');
            }
        }
        // ── End Social Networks ─────────────────────────────────────

        // ── Payment Methods ──────────────────────────────────────────
        @if($plan->id !== 1)
        const payAllKeys  = @json(array_keys($allPayMeta));
        const currAllKeys = @json(array_keys($allCurrencyMeta));
        const allPayMetaData  = @json($allPayMeta);
        const allCurrMetaData = @json($allCurrencyMeta);

        // Usa onchange en el <input> (no onclick en el <label>).
        // Cuando onchange dispara, el browser YA toggleó el checkbox.
        // check.checked refleja el estado NUEVO correcto.
        function togglePayMethod(key) {
            const check = document.getElementById('pay-check-' + key);
            const label = document.getElementById('pay-label-' + key);
            const checkIcon = document.getElementById('pay-check-icon-' + key);
            if (!check || !label) return;
            const on = check.checked; // onchange: browser ya toggleó
            label.classList.remove(
                'bg-primary/15', 'border-primary/40',
                'bg-muted/40', 'border-border'
            );
            if (on) {
                label.classList.add('bg-primary/15', 'border-primary/40');
            } else {
                label.classList.add('bg-muted/40', 'border-border');
            }
            if (checkIcon) {
                checkIcon.classList.toggle('opacity-100', on);
                checkIcon.classList.toggle('text-primary', on);
                checkIcon.classList.toggle('opacity-0', !on);
            }
            // Icono del método
            const methodIcon = label.querySelector('.iconify:not([id])');
            if (methodIcon) {
                methodIcon.classList.remove('text-primary', 'text-muted-foreground-1');
                methodIcon.classList.add(on ? 'text-primary' : 'text-muted-foreground-1');
            }
            const txtSpan = label.querySelector('.flex-1');
            if (txtSpan) {
                txtSpan.classList.remove('text-primary', 'text-foreground');
                txtSpan.classList.add(on ? 'text-primary' : 'text-foreground');
            }
            updatePaymentPreview();
        }

        function toggleCurrency(key) {
            const check = document.getElementById('curr-check-' + key);
            const label = document.getElementById('curr-label-' + key);
            const checkIcon = document.getElementById('curr-check-icon-' + key);
            if (!check || !label) return;
            const on = check.checked; // onchange: browser ya toggleó
            label.classList.remove(
                'bg-primary/15', 'border-primary/40',
                'bg-muted/40', 'border-border'
            );
            if (on) {
                label.classList.add('bg-primary/15', 'border-primary/40');
            } else {
                label.classList.add('bg-muted/40', 'border-border');
            }
            if (checkIcon) {
                checkIcon.classList.toggle('opacity-100', on);
                checkIcon.classList.toggle('text-primary', on);
                checkIcon.classList.toggle('opacity-0', !on);
            }
            // Icono de divisa
            const currIcon = label.querySelector('.iconify:not([id])');
            if (currIcon) {
                currIcon.classList.remove('text-primary', 'text-muted-foreground-1');
                currIcon.classList.add(on ? 'text-primary' : 'text-muted-foreground-1');
            }
            const txtSpan = label.querySelector('.flex-1');
            if (txtSpan) {
                txtSpan.classList.remove('text-primary', 'text-foreground');
                txtSpan.classList.add(on ? 'text-primary' : 'text-foreground');
            }
            updatePaymentPreview();
        }

        function updatePaymentPreview() {
            const preview = document.getElementById('payment-preview');
            if (!preview) return;
            const selected = [];
            payAllKeys.forEach(k => {
                const el = document.getElementById('pay-check-' + k);
                if (el && el.checked) {
                    selected.push({ key: k, ...allPayMetaData[k], type: 'method' });
                }
            });
            currAllKeys.forEach(k => {
                const el = document.getElementById('curr-check-' + k);
                if (el && el.checked) {
                    selected.push({ key: k, ...allCurrMetaData[k], type: 'currency' });
                }
            });
            if (selected.length === 0) {
                preview.innerHTML = '<span class="text-foreground/40 text-xs">Selecciona métodos para ver la previa</span>';
                return;
            }
            preview.innerHTML = selected.map(item =>
                `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-primary/10 text-primary border border-primary/20">
                    <span class="iconify ${item.icon} size-3" aria-hidden="true"></span> ${item.label}
                </span>`
            ).join('');
        }

        // Inicializar previa al cargar
        document.addEventListener('DOMContentLoaded', updatePaymentPreview);

        @if($plan->id === 3)
        function toggleBranchPayMethod(branchId, key) {
            const check = document.getElementById('pay-branch-check-' + branchId + '-' + key);
            const label = document.getElementById('pay-branch-label-' + branchId + '-' + key);
            const checkIcon = document.getElementById('pay-branch-check-icon-' + branchId + '-' + key);
            if (!check || !label) return;
            const on = check.checked; // onchange: browser ya toggleó
            label.classList.remove(
                'bg-primary/15', 'border-primary/40',
                'bg-surface', 'border-border'
            );
            if (on) {
                label.classList.add('bg-primary/15', 'border-primary/40');
            } else {
                label.classList.add('bg-surface', 'border-border');
            }
            if (checkIcon) {
                checkIcon.classList.toggle('opacity-100', on);
                checkIcon.classList.toggle('text-primary', on);
                checkIcon.classList.toggle('opacity-0', !on);
            }
            // Icono del método
            const methodIcon = label.querySelector('.iconify:not([id])');
            if (methodIcon) {
                methodIcon.classList.remove('text-primary', 'text-muted-foreground-1');
                methodIcon.classList.add(on ? 'text-primary' : 'text-muted-foreground-1');
            }
            const txtSpan = label.querySelector('.flex-1');
            if (txtSpan) {
                txtSpan.classList.remove('text-primary', 'text-foreground');
                txtSpan.classList.add(on ? 'text-primary' : 'text-foreground');
            }
        }
        @endif

        async function savePaymentMethods() {
            const tenantId = {{ $tenant->id }};
            const globalSelected = payAllKeys.filter(k => {
                const el = document.getElementById('pay-check-' + k);
                return el && el.checked;
            });
            const currencySelected = currAllKeys.filter(k => {
                const el = document.getElementById('curr-check-' + k);
                return el && el.checked;
            });
            const payload = { global: globalSelected, currency: currencySelected };

            @if($plan->id === 3)
            const branchData = {};
            @foreach($activeBranchList as $branch)
            const bMethods_{{ $branch->id }} = payAllKeys.filter(k => {
                const el = document.getElementById('pay-branch-check-{{ $branch->id }}-' + k);
                return el && el.checked;
            });
            if (bMethods_{{ $branch->id }}.length > 0) {
                branchData['{{ $branch->id }}'] = bMethods_{{ $branch->id }};
            }
            @endforeach
            payload.branches = branchData;
            @endif

            try {
                const response = await fetch('/tenant/' + tenantId + '/update-payment-methods', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    },
                    body: JSON.stringify(payload)
                });
                const result = await response.json();
                if (result.success) {
                    alert('✓ Medios de pago y denominaciones guardados correctamente');
                } else {
                    alert('✗ ' + (result.message || 'Error al guardar'));
                }
            } catch (err) {
                console.error('Error:', err);
                alert('✗ Error al guardar medios de pago');
            }
        }
        @endif
        // ── End Payment Methods ──────────────────────────────────────

        // ── Branches (Plan 3 / VISIÓN) ──────────────────────────────
        @if($plan->id === 3)
        let branchCount = {{ $branches->count() }};

        async function toggleBranchesSection() {
            const enabled = document.getElementById('branches-toggle').checked;

            try {
                const response = await fetch('/tenant/{{ $tenant->id }}/branches/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    },
                    body: JSON.stringify({ enabled })
                });

                const result = await response.json();

                if (result.success) {
                    const content = document.getElementById('branches-content');
                    const status = document.getElementById('branches-status');
                    const statusText = document.getElementById('branches-status-text');

                    content.style.display = enabled ? '' : 'none';
                    status.className = enabled ? 'alert alert-success' : 'alert alert-info';
                    statusText.textContent = enabled
                        ? 'Sección visible en tu landing pública'
                        : 'Sección oculta en tu landing pública';
                } else {
                    // Revert toggle
                    document.getElementById('branches-toggle').checked = !enabled;
                    alert('✗ ' + (result.message || 'Error'));
                }
            } catch (err) {
                document.getElementById('branches-toggle').checked = !enabled;
                console.error('Error:', err);
                alert('✗ Error al cambiar estado de sucursales');
            }
        }

        function openBranchModal() {
            document.getElementById('branch-modal-title').textContent = '+ Agregar Sucursal';
            document.getElementById('branch-edit-id').value = '';
            document.getElementById('branch-form').reset();
            document.getElementById('branch-modal').style.display = 'flex';
        }

        function editBranch(id, name, address) {
            document.getElementById('branch-modal-title').textContent = '✏️ Editar Sucursal';
            document.getElementById('branch-edit-id').value = id;
            document.getElementById('branch-name').value = name;
            document.getElementById('branch-address').value = address;
            document.getElementById('branch-modal').style.display = 'flex';
        }

        function closeBranchModal() {
            document.getElementById('branch-modal').style.display = 'none';
            document.getElementById('branch-form').reset();
            document.getElementById('branch-edit-id').value = '';
        }

        async function saveBranch(event) {
            event.preventDefault();
            
            const name = document.getElementById('branch-name').value.trim();
            const address = document.getElementById('branch-address').value.trim();
            const editId = document.getElementById('branch-edit-id').value;

            if (!name || !address) {
                alert('✗ Nombre y dirección son obligatorios');
                return;
            }

            const payload = { name, address, is_active: true };
            if (editId) payload.id = parseInt(editId);

            try {
                const response = await fetch('/tenant/{{ $tenant->id }}/branches', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (result.success) {
                    closeBranchModal();
                    alert('✓ ' + result.message);
                    dashboardReload();
                } else {
                    alert('✗ ' + (result.message || 'Error desconocido'));
                }
            } catch (err) {
                console.error('Error:', err);
                alert('✗ Error al guardar sucursal');
            }
        }

        async function deleteBranch(branchId) {
            if (!confirm('¿Eliminar esta sucursal?')) return;

            try {
                const response = await fetch(`/tenant/{{ $tenant->id }}/branches/${branchId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    }
                });

                const result = await response.json();

                if (result.success) {
                    alert('✓ Sucursal eliminada');
                    dashboardReload();
                } else {
                    alert('✗ ' + (result.message || 'Error'));
                }
            } catch (err) {
                console.error('Error:', err);
                alert('✗ Error al eliminar sucursal');
            }
        }
        @endif
        // ── End Branches ────────────────────────────────────────────

        // ── Header Top (Plan 2+) ────────────────────────────────────
        async function saveHeaderTop() {
            const enabled = document.getElementById('header-top-toggle')?.checked ?? false;
            const text = document.getElementById('header-top-text')?.value?.trim() ?? '';
            try {
                const res = await fetch(`/tenant/{{ $tenant->id }}/update-header-top`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ enabled, text })
                });
                const result = await res.json();
                if (result.success) {
                    showToast('✓ Header Top actualizado', 'success');
                } else {
                    showToast('✗ ' + (result.message || 'Error'), 'error');
                }
            } catch (err) {
                console.error('Error:', err);
                showToast('✗ Error al guardar Header Top', 'error');
            }
        }

        // ── Acerca de (description) ────────────────────────────────
        async function saveAboutText() {
            const description = document.getElementById('about-text')?.value?.trim() ?? '';
            try {
                const res = await fetch(`/tenant/{{ $tenant->id }}/update-info`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ description })
                });
                const result = await res.json();
                if (result.success) {
                    showToast('✓ Descripción actualizada', 'success');
                } else {
                    showToast('✗ ' + (result.message || 'Error'), 'error');
                }
            } catch (err) {
                console.error('Error:', err);
                showToast('✗ Error al guardar descripción', 'error');
            }
        }

        // ── CTA Especial (Plan 3) ──────────────────────────────────
        async function saveCtaConfig() {
            const data = {
                cta_title: document.getElementById('cta-title')?.value?.trim() ?? '',
                cta_subtitle: document.getElementById('cta-subtitle')?.value?.trim() ?? '',
                cta_button_text: document.getElementById('cta-btn-text')?.value?.trim() ?? '',
                cta_button_link: document.getElementById('cta-btn-link')?.value?.trim() ?? ''
            };
            try {
                const res = await fetch(`/tenant/{{ $tenant->id }}/update-cta`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify(data)
                });
                const result = await res.json();
                if (result.success) {
                    showToast('✓ CTA actualizado', 'success');
                } else {
                    showToast('✗ ' + (result.message || 'Error'), 'error');
                }
            } catch (err) {
                console.error('Error:', err);
                showToast('✗ Error al guardar CTA', 'error');
            }
        }
    </script>
