        <!-- Tab: Facturación -->
        <div id="tab-billing" class="tab-content">
            <div class="p-6" x-data="billingSection({{ $tenant->id }})" x-init="loadBilling()">

            {{-- ── Section header ──────────────────────────────── --}}
            <div class="mb-6 flex items-center gap-3">
                <div class="size-10 rounded-xl flex items-center justify-center"
                     style="background: linear-gradient(135deg, rgba(74,128,228,.15) 0%, rgba(74,128,228,.05) 100%); border: 1px solid rgba(74,128,228,.2)">
                    <span class="iconify tabler--receipt size-5" style="color:#4A80E4" aria-hidden="true"></span>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-foreground" style="font-family:'Plus Jakarta Sans',sans-serif">Facturación</h2>
                    <p class="text-xs text-muted-foreground-1 mt-0.5">Tu plan, pagos y renovación</p>
                </div>
            </div>

            {{-- ── Error banner de carga ────────────────────────── --}}
            <template x-if="loadError">
                <div class="mb-6 flex items-center gap-3 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700">
                    <span class="iconify tabler--alert-triangle size-6 shrink-0" aria-hidden="true"></span>
                    <div>
                        <p class="font-semibold text-sm" x-text="loadError"></p>
                        <button @click="loadBilling()" type="button"
                                class="text-xs underline mt-1 cursor-pointer hover:opacity-70 transition">
                            Reintentar
                        </button>
                    </div>
                </div>
            </template>

            {{-- ══════════════════════════════════════════════════════════════
                 ESTADO DEL PLAN (siempre visible arriba)
            ══════════════════════════════════════════════════════════════ --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
                {{-- Card: Plan actual --}}
                <div class="bg-surface rounded-xl border border-border p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="iconify tabler--crown size-5 text-yellow-500" aria-hidden="true"></span>
                        <span class="text-sm font-semibold text-foreground" x-text="plan.name || 'Cargando...'"></span>
                    </div>
                    <div class="text-2xl font-bold text-foreground">
                        REF <span x-text="plan.price_usd || '—'"></span>
                    </div>
                    <p class="text-xs text-muted-foreground-1 mt-1">por año</p>
                </div>

                {{-- Card: Vencimiento --}}
                <div class="bg-surface rounded-xl border border-border p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="iconify tabler--calendar size-5 text-blue-500" aria-hidden="true"></span>
                        <span class="text-sm font-semibold text-foreground">Vencimiento</span>
                    </div>
                    <div class="text-2xl font-bold" :class="plan.is_frozen ? 'text-red-600' : (plan.is_expiring_soon ? 'text-yellow-600' : 'text-foreground')">
                        <span x-text="plan.subscription_ends || 'Sin fecha'"></span>
                    </div>
                    <p class="text-xs mt-1"
                       :class="plan.is_frozen ? 'text-red-500' : (plan.is_expiring_soon ? 'text-yellow-500' : 'text-muted-foreground-1')">
                        <template x-if="plan.is_frozen">
                            <span>Plan vencido — renueva para reactivar</span>
                        </template>
                        <template x-if="plan.is_expiring_soon && !plan.is_frozen">
                            <span>Vence en <strong x-text="plan.days_until_expiry"></strong> días</span>
                        </template>
                        <template x-if="!plan.is_frozen && !plan.is_expiring_soon && plan.days_until_expiry !== null">
                            <span>Faltan <strong x-text="plan.days_until_expiry"></strong> días</span>
                        </template>
                    </p>
                </div>

                {{-- Card: Estado último pago --}}
                <div class="bg-surface rounded-xl border border-border p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="iconify tabler--circle-check size-5 text-green-500" aria-hidden="true"></span>
                        <span class="text-sm font-semibold text-foreground">Último Pago</span>
                    </div>
                    <template x-if="invoices.length > 0">
                        <div>
                            <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium"
                                  :class="invoices[0].status_color"
                                  x-text="invoices[0].status_label"></span>
                            <p class="text-xs text-muted-foreground-1 mt-2" x-text="invoices[0].created_at"></p>
                        </div>
                    </template>
                    <template x-if="invoices.length === 0">
                        <p class="text-sm text-muted-foreground-1">Sin pagos registrados</p>
                    </template>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════════════
                 BOTÓN REPORTAR PAGO (solo si no hay pending_review)
            ══════════════════════════════════════════════════════════════ --}}
            <template x-if="!hasPendingReview">
                <div class="mb-6">
                    <button @click="showPaymentForm = true" type="button"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 py-3 px-6 rounded-xl text-sm font-semibold text-white transition-colors cursor-pointer"
                            style="background: #4A80E4"
                            @mouseenter="$el.style.background='#3d6ec7'"
                            @mouseleave="$el.style.background='#4A80E4'">
                        <span class="iconify tabler--credit-card size-5" aria-hidden="true"></span>
                        Reportar Pago / Renovar Plan
                    </button>
                </div>
            </template>

            {{-- Banner: pago en revisión --}}
            <template x-if="hasPendingReview">
                <div class="mb-6 flex items-center gap-3 p-4 rounded-xl bg-yellow-50 border border-yellow-200 text-yellow-800">
                    <span class="iconify tabler--clock size-6 shrink-0" aria-hidden="true"></span>
                    <div>
                        <p class="font-semibold text-sm">Tu pago está en revisión</p>
                        <p class="text-xs opacity-80 mt-0.5">Recibirás confirmación en las próximas 24 horas. No necesitas reportar otro pago.</p>
                    </div>
                </div>
            </template>

            {{-- ══════════════════════════════════════════════════════════════
                 FORMULARIO DE REPORTE DE PAGO (toggled)
            ══════════════════════════════════════════════════════════════ --}}
            <div x-show="showPaymentForm" x-cloak x-transition class="mb-6">
                <div class="bg-surface rounded-xl border border-border overflow-hidden">
                    <div class="px-5 py-4 border-b border-border flex items-center justify-between">
                        <h3 class="text-base font-bold text-foreground">Reportar Pago</h3>
                        <button @click="showPaymentForm = false" type="button"
                                class="size-8 rounded-lg flex items-center justify-center text-muted-foreground-1 hover:bg-muted-hover transition-colors cursor-pointer">
                            <span class="iconify tabler--x size-5" aria-hidden="true"></span>
                        </button>
                    </div>

                    <form @submit.prevent="submitPayment" class="p-5 space-y-5">
                        {{-- Paso 1: Seleccionar canal de pago --}}
                        <div>
                            <label class="block text-sm font-semibold text-foreground mb-3">¿Cómo pagaste?</label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <template x-for="(channel, key) in channels" :key="key">
                                    <button type="button"
                                            @click="form.payment_channel = key"
                                            :class="form.payment_channel === key
                                                ? 'border-[#4A80E4] bg-blue-50 ring-1 ring-[#4A80E4]'
                                                : 'border-border hover:border-gray-300'"
                                            class="relative p-4 rounded-xl border-2 text-left transition-all cursor-pointer">
                                        <div class="flex items-center gap-3">
                                            <span class="iconify size-6" :class="channel.icon"
                                                  :style="form.payment_channel === key ? 'color:#4A80E4' : 'color:#64748b'"
                                                  aria-hidden="true"></span>
                                            <span class="text-sm font-semibold text-foreground" x-text="channel.label"></span>
                                        </div>
                                        {{-- Mostrar datos de SYNTIweb para ese canal --}}
                                        <div class="mt-3 text-xs text-muted-foreground-1 space-y-1">
                                            <template x-for="(val, detailKey) in channel.details" :key="detailKey">
                                                <div class="flex justify-between gap-2">
                                                    <span class="capitalize" x-text="detailKey + ':'"></span>
                                                    <span class="font-mono font-medium text-foreground select-all" x-text="val"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </button>
                                </template>
                            </div>
                            <p x-show="errors.payment_channel" class="mt-1 text-xs text-red-500" x-text="errors.payment_channel"></p>
                        </div>

                        {{-- Paso 2: Datos del pago --}}
                        <div x-show="form.payment_channel" x-transition class="space-y-4">
                            {{-- Referencia --}}
                            <div>
                                <label for="billing-ref" class="block text-sm font-medium text-foreground mb-1">
                                    Número de referencia / confirmación
                                </label>
                                <input type="text" id="billing-ref"
                                       x-model="form.payment_reference"
                                       placeholder="Ej: 0412001234 o ID de transacción"
                                       class="w-full rounded-lg border border-border bg-background text-foreground px-3 py-2.5 text-sm focus:ring-2 focus:ring-[#4A80E4]/30 focus:border-[#4A80E4] transition-colors"
                                       maxlength="100">
                                <p x-show="errors.payment_reference" class="mt-1 text-xs text-red-500" x-text="errors.payment_reference"></p>
                            </div>

                            {{-- Fecha del pago --}}
                            <div>
                                <label for="billing-date" class="block text-sm font-medium text-foreground mb-1">
                                    Fecha del pago
                                </label>
                                <input type="date" id="billing-date"
                                       x-model="form.payment_date"
                                       :max="new Date().toISOString().split('T')[0]"
                                       class="w-full rounded-lg border border-border bg-background text-foreground px-3 py-2.5 text-sm focus:ring-2 focus:ring-[#4A80E4]/30 focus:border-[#4A80E4] transition-colors">
                                <p x-show="errors.payment_date" class="mt-1 text-xs text-red-500" x-text="errors.payment_date"></p>
                            </div>

                            {{-- Comprobante --}}
                            <div>
                                <label class="block text-sm font-medium text-foreground mb-1">
                                    Comprobante de pago
                                </label>
                                <div class="relative">
                                    <input type="file" id="billing-receipt"
                                           @change="form.receipt = $event.target.files[0]"
                                           accept=".jpg,.jpeg,.png,.webp,.pdf"
                                           class="w-full text-sm text-muted-foreground-1 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-[#4A80E4] hover:file:bg-blue-100 file:cursor-pointer cursor-pointer">
                                </div>
                                <p class="text-xs text-muted-foreground-1 mt-1">JPG, PNG, WebP o PDF — máx 5 MB</p>
                                <p x-show="errors.receipt" class="mt-1 text-xs text-red-500" x-text="errors.receipt"></p>
                            </div>
                        </div>

                        {{-- Monto (informativo) --}}
                        <div x-show="form.payment_channel" x-transition
                             class="flex items-center justify-between p-4 rounded-xl bg-gray-50 border border-gray-100">
                            <div>
                                <p class="text-sm font-medium text-foreground">Monto a verificar</p>
                                <p class="text-xs text-muted-foreground-1">Renovación anual — <span x-text="plan.name"></span></p>
                            </div>
                            <div class="text-right">
                                <p class="text-xl font-bold text-foreground">REF <span x-text="plan.price_usd"></span></p>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div x-show="form.payment_channel" x-transition class="flex items-center gap-3">
                            <button type="submit"
                                    :disabled="submitting"
                                    class="inline-flex items-center gap-2 py-2.5 px-6 rounded-xl text-sm font-semibold text-white transition-colors cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                                    style="background: #4A80E4"
                                    @mouseenter="!submitting && ($el.style.background='#3d6ec7')"
                                    @mouseleave="$el.style.background='#4A80E4'">
                                <span x-show="!submitting" class="iconify tabler--send size-4" aria-hidden="true"></span>
                                <span x-show="submitting" class="iconify tabler--loader-2 size-4 animate-spin" aria-hidden="true"></span>
                                <span x-text="submitting ? 'Enviando...' : 'Enviar Reporte de Pago'"></span>
                            </button>
                            <button @click="showPaymentForm = false" type="button"
                                    class="inline-flex items-center py-2.5 px-4 rounded-xl text-sm font-medium text-muted-foreground-1 hover:bg-muted-hover transition-colors cursor-pointer">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════════════
                 HISTORIAL DE FACTURAS
            ══════════════════════════════════════════════════════════════ --}}
            <div class="bg-surface rounded-xl border border-border overflow-hidden">
                <div class="px-5 py-4 border-b border-border">
                    <h3 class="text-base font-bold text-foreground">Historial de Pagos</h3>
                </div>
                <template x-if="invoices.length === 0 && !loading">
                    <div class="p-8 text-center">
                        <span class="iconify tabler--receipt-off size-12 text-gray-300 mx-auto mb-3" aria-hidden="true"></span>
                        <p class="text-sm text-muted-foreground-1">No hay pagos registrados aún</p>
                    </div>
                </template>
                <template x-if="loading">
                    <div class="p-8 text-center">
                        <span class="iconify tabler--loader-2 size-8 text-gray-300 animate-spin mx-auto" aria-hidden="true"></span>
                    </div>
                </template>
                <template x-if="invoices.length > 0">
                    <div class="divide-y divide-border">
                        <template x-for="inv in invoices" :key="inv.id">
                            <div class="px-5 py-3.5 flex items-center gap-4 flex-wrap">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="text-sm font-semibold text-foreground font-mono" x-text="inv.invoice_number"></span>
                                        <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium"
                                              :class="inv.status_color"
                                              x-text="inv.status_label"></span>
                                    </div>
                                    <div class="flex items-center gap-3 mt-1 text-xs text-muted-foreground-1">
                                        <span x-text="inv.channel_label"></span>
                                        <span>•</span>
                                        <span x-text="inv.created_at"></span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-foreground">REF <span x-text="inv.amount_usd"></span></p>
                                    <p class="text-xs text-muted-foreground-1" x-text="inv.period_start + ' → ' + inv.period_end"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            {{-- ══════════════════════════════════════════════════════════════
                 SUCCESS TOAST
            ══════════════════════════════════════════════════════════════ --}}
            <div x-show="successMessage" x-transition x-cloak
                 class="fixed bottom-6 right-6 z-50 flex items-center gap-3 py-3 px-5 rounded-xl bg-green-600 text-white shadow-lg">
                <span class="iconify tabler--circle-check size-5" aria-hidden="true"></span>
                <span class="text-sm font-medium" x-text="successMessage"></span>
            </div>

            </div>
        </div>

<script>
function billingSection(tenantId) {
    return {
        loading: true,
        showPaymentForm: false,
        submitting: false,
        successMessage: '',
        loadError: '',
        channels: {},
        invoices: [],
        plan: {},
        form: {
            payment_channel: '',
            payment_reference: '',
            payment_date: new Date().toISOString().split('T')[0],
            receipt: null,
        },
        errors: {},

        get hasPendingReview() {
            return this.invoices.some(inv => inv.status === 'pending_review');
        },

        async loadBilling() {
            this.loading = true;
            this.loadError = '';
            try {
                const res = await fetch(`/tenant/${tenantId}/billing`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (!res.ok) {
                    const status = res.status;
                    if (status === 401) {
                        this.loadError = 'Sesión expirada. Recarga la página e inicia sesión.';
                    } else if (status === 403) {
                        this.loadError = 'No tienes permiso para ver la facturación de este negocio.';
                    } else if (status === 404) {
                        this.loadError = 'No se encontró información de facturación.';
                    } else {
                        this.loadError = 'Error al cargar facturación. Intenta de nuevo.';
                    }
                    return;
                }
                const data = await res.json();
                if (data.success) {
                    this.channels = data.channels;
                    this.invoices = data.invoices;
                    this.plan = data.plan;
                } else {
                    this.loadError = data.message || 'Error al cargar datos de facturación.';
                }
            } catch (e) {
                console.error('Error loading billing data:', e);
                this.loadError = 'Error de conexión. Verifica tu internet e intenta de nuevo.';
            } finally {
                this.loading = false;
            }
        },

        async submitPayment() {
            this.errors = {};
            this.submitting = true;

            const fd = new FormData();
            fd.append('payment_channel', this.form.payment_channel);
            fd.append('payment_reference', this.form.payment_reference);
            fd.append('payment_date', this.form.payment_date);
            if (this.form.receipt) {
                fd.append('receipt', this.form.receipt);
            }

            try {
                const res = await fetch(`/tenant/${tenantId}/billing/report-payment`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: fd,
                });
                const data = await res.json();

                if (data.success) {
                    this.successMessage = data.message;
                    this.showPaymentForm = false;
                    this.form = {
                        payment_channel: '',
                        payment_reference: '',
                        payment_date: new Date().toISOString().split('T')[0],
                        receipt: null,
                    };
                    // Reset file input
                    const fileInput = document.getElementById('billing-receipt');
                    if (fileInput) fileInput.value = '';
                    // Recargar datos
                    await this.loadBilling();
                    // Auto-hide toast
                    setTimeout(() => { this.successMessage = ''; }, 5000);
                } else if (data.errors) {
                    // Validation errors from Laravel
                    for (const [key, msgs] of Object.entries(data.errors)) {
                        this.errors[key] = Array.isArray(msgs) ? msgs[0] : msgs;
                    }
                } else if (data.message) {
                    this.errors.payment_channel = data.message;
                }
            } catch (e) {
                console.error('Error submitting payment:', e);
                this.errors.payment_channel = 'Error de conexión. Intenta de nuevo.';
            } finally {
                this.submitting = false;
            }
        },
    };
}
</script>
