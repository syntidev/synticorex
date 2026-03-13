@extends('layouts.admin')

@section('content')
<div x-data="adminBilling()" x-init="loadQueue()">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h2 class="mono text-lg uppercase tracking-widest text-white font-bold">Cola de Pagos</h2>
            <p class="mono text-xs text-slate-500 mt-1">
                <span x-text="pendingCount"></span> pendientes de revisión
            </p>
        </div>

        {{-- Filtros --}}
        <div class="flex gap-2">
            <button @click="filter = 'pending_review'; loadQueue()"
                    :class="filter === 'pending_review' ? 'bg-blue-500/20 text-blue-400 border-blue-500/30' : 'text-slate-500 border-white/5 hover:border-white/10'"
                    class="mono text-[11px] uppercase tracking-widest px-4 py-2 rounded border transition cursor-pointer">
                Pendientes
            </button>
            <button @click="filter = 'reviewed'; loadQueue()"
                    :class="filter === 'reviewed' ? 'bg-blue-500/20 text-blue-400 border-blue-500/30' : 'text-slate-500 border-white/5 hover:border-white/10'"
                    class="mono text-[11px] uppercase tracking-widest px-4 py-2 rounded border transition cursor-pointer">
                Revisados
            </button>
            <button @click="filter = 'all'; loadQueue()"
                    :class="filter === 'all' ? 'bg-blue-500/20 text-blue-400 border-blue-500/30' : 'text-slate-500 border-white/5 hover:border-white/10'"
                    class="mono text-[11px] uppercase tracking-widest px-4 py-2 rounded border transition cursor-pointer">
                Todos
            </button>
        </div>
    </div>

    {{-- Loading --}}
    <div x-show="loading" class="text-center py-20">
        <div class="inline-block w-6 h-6 border-2 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
        <p class="mono text-xs text-slate-500 mt-3">Cargando...</p>
    </div>

    {{-- Empty --}}
    <div x-show="!loading && invoices.length === 0" class="text-center py-20">
        <p class="mono text-sm text-slate-600">No hay pagos en esta cola.</p>
    </div>

    {{-- Invoices List --}}
    <div x-show="!loading && invoices.length > 0" class="space-y-4">
        <template x-for="inv in invoices" :key="inv.id">
            <div class="glass-panel rounded-lg p-5">
                {{-- Row header --}}
                <div class="flex flex-col lg:flex-row justify-between gap-4">
                    {{-- Left: Invoice + Tenant info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="mono text-xs text-blue-400" x-text="inv.invoice_number"></span>
                            <span class="text-[10px] px-2 py-0.5 rounded-full uppercase tracking-wider font-bold"
                                  :class="inv.status_color" x-text="inv.status_label"></span>
                        </div>
                        <p class="text-sm text-white font-semibold" x-text="inv.tenant.name"></p>
                        <p class="mono text-[11px] text-slate-500">
                            <span x-text="inv.tenant.subdomain"></span>.syntiweb.com &mdash;
                            Plan <span x-text="inv.tenant.plan"></span>
                        </p>
                    </div>

                    {{-- Center: Payment details --}}
                    <div class="flex-1 min-w-0">
                        <div class="grid grid-cols-2 gap-x-6 gap-y-1 text-xs">
                            <div>
                                <span class="text-slate-600">Canal:</span>
                                <span class="text-slate-300 ml-1" x-text="inv.channel_label"></span>
                            </div>
                            <div>
                                <span class="text-slate-600">Monto:</span>
                                <span class="text-white font-bold ml-1">REF <span x-text="inv.amount_usd"></span></span>
                            </div>
                            <div>
                                <span class="text-slate-600">Ref:</span>
                                <span class="mono text-slate-300 ml-1" x-text="inv.payment_reference"></span>
                            </div>
                            <div>
                                <span class="text-slate-600">Fecha pago:</span>
                                <span class="text-slate-300 ml-1" x-text="inv.payment_date"></span>
                            </div>
                            <div>
                                <span class="text-slate-600">Período:</span>
                                <span class="text-slate-300 ml-1" x-text="inv.period_start + ' — ' + inv.period_end"></span>
                            </div>
                            <div>
                                <span class="text-slate-600">Reportado:</span>
                                <span class="text-slate-300 ml-1" x-text="inv.created_at"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Right: Actions --}}
                    <div class="flex items-start gap-2 shrink-0">
                        {{-- View receipt --}}
                        <a x-show="inv.receipt_path"
                           :href="'/admin/billing/' + inv.id + '/receipt'"
                           target="_blank"
                           class="px-3 py-2 text-[11px] mono uppercase tracking-widest rounded border border-white/10 text-slate-400 hover:text-white hover:border-white/20 transition cursor-pointer">
                            Comprobante
                        </a>

                        {{-- Approve --}}
                        <button x-show="inv.status === 'pending_review'"
                                @click="selectedInvoice = inv; action = 'approve'; showModal = true"
                                class="px-3 py-2 text-[11px] mono uppercase tracking-widest rounded bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 hover:bg-emerald-500/30 transition cursor-pointer">
                            Aprobar
                        </button>

                        {{-- Reject --}}
                        <button x-show="inv.status === 'pending_review'"
                                @click="selectedInvoice = inv; action = 'reject'; showModal = true; adminNotes = ''"
                                class="px-3 py-2 text-[11px] mono uppercase tracking-widest rounded bg-red-500/20 text-red-400 border border-red-500/30 hover:bg-red-500/30 transition cursor-pointer">
                            Rechazar
                        </button>

                        {{-- Already reviewed info --}}
                        <template x-if="inv.status !== 'pending_review' && inv.admin_notes">
                            <div class="text-[11px] text-slate-500 max-w-[200px]">
                                <span class="text-slate-600">Nota:</span>
                                <span x-text="inv.admin_notes"></span>
                                <br>
                                <span class="text-slate-700" x-text="inv.reviewed_at"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </template>
    </div>

    {{-- Confirm Modal --}}
    <div x-show="showModal" x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center"
         @keydown.escape.window="showModal = false">
        <div class="absolute inset-0 bg-black/70" @click="showModal = false"></div>
        <div class="relative glass-panel rounded-xl p-6 w-full max-w-md mx-4 border border-white/10"
             @click.stop>
            <h3 class="mono text-sm uppercase tracking-widest text-white font-bold mb-4"
                x-text="action === 'approve' ? '¿Aprobar pago?' : '¿Rechazar pago?'"></h3>

            <div class="mb-4 text-xs text-slate-400">
                <p><span class="text-slate-600">Factura:</span> <span class="mono text-blue-400" x-text="selectedInvoice?.invoice_number"></span></p>
                <p><span class="text-slate-600">Tenant:</span> <span class="text-white" x-text="selectedInvoice?.tenant?.name"></span></p>
                <p><span class="text-slate-600">Monto:</span> <span class="text-white font-bold">REF <span x-text="selectedInvoice?.amount_usd"></span></span></p>
            </div>

            {{-- Notes field --}}
            <div class="mb-4">
                <label class="mono text-[10px] text-slate-600 uppercase tracking-widest block mb-1"
                       x-text="action === 'reject' ? 'Motivo del rechazo (obligatorio)' : 'Nota (opcional)'"></label>
                <textarea x-model="adminNotes" rows="3"
                          class="w-full bg-white/5 border border-white/10 rounded px-3 py-2 text-sm text-white placeholder:text-slate-600 focus:border-blue-500/50 focus:outline-none"
                          :placeholder="action === 'reject' ? 'Ej: Referencia no coincide con el monto...' : 'Nota interna...'"></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <button @click="showModal = false"
                        class="px-4 py-2 text-xs mono uppercase tracking-widest text-slate-500 hover:text-white transition cursor-pointer">
                    Cancelar
                </button>
                <button @click="processAction()"
                        :disabled="processing || (action === 'reject' && !adminNotes.trim())"
                        :class="action === 'approve' ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30 hover:bg-emerald-500/30' : 'bg-red-500/20 text-red-400 border-red-500/30 hover:bg-red-500/30'"
                        class="px-4 py-2 text-xs mono uppercase tracking-widest rounded border transition cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="!processing" x-text="action === 'approve' ? 'Confirmar Aprobación' : 'Confirmar Rechazo'"></span>
                    <span x-show="processing">Procesando...</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Toast --}}
    <div x-show="toastMessage" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed bottom-6 right-6 z-[110] glass-panel rounded-lg px-5 py-3 border"
         :class="toastType === 'success' ? 'border-emerald-500/30 text-emerald-400' : 'border-red-500/30 text-red-400'">
        <p class="mono text-xs" x-text="toastMessage"></p>
    </div>
</div>

<script>
function adminBilling() {
    return {
        loading: true,
        filter: 'pending_review',
        invoices: [],
        pendingCount: 0,

        showModal: false,
        selectedInvoice: null,
        action: '',
        adminNotes: '',
        processing: false,

        toastMessage: '',
        toastType: 'success',

        async loadQueue() {
            this.loading = true;
            try {
                const res = await fetch(`/admin/billing?filter=${this.filter}`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();
                if (data.success) {
                    this.invoices = data.invoices;
                    this.pendingCount = data.pending_count;
                }
            } catch (e) {
                console.error('Error loading queue:', e);
            } finally {
                this.loading = false;
            }
        },

        async processAction() {
            if (this.processing || !this.selectedInvoice) return;
            this.processing = true;

            const url = `/admin/billing/${this.selectedInvoice.id}/${this.action}`;
            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ admin_notes: this.adminNotes })
                });
                const data = await res.json();

                if (data.success) {
                    this.showToast(data.message, 'success');
                    this.showModal = false;
                    this.adminNotes = '';
                    await this.loadQueue();
                } else {
                    this.showToast(data.message || 'Error al procesar', 'error');
                }
            } catch (e) {
                this.showToast('Error de conexión', 'error');
            } finally {
                this.processing = false;
            }
        },

        showToast(message, type = 'success') {
            this.toastMessage = message;
            this.toastType = type;
            setTimeout(() => { this.toastMessage = ''; }, 5000);
        }
    };
}
</script>
@endsection
