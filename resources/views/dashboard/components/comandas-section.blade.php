        <!-- Tab: Comandas (SYNTIfood Order Engine) -->
        <div id="tab-comandas" class="tab-content">
            <div class="p-6">

            {{-- ── Hero header ──────────────────────────────────────────── --}}
            <div class="mb-5 pb-5 border-b border-border">
                <div class="flex items-center gap-3">
                    <div class="size-10 rounded-xl flex items-center justify-center shrink-0"
                         style="background:linear-gradient(135deg,rgba(255,140,50,.15) 0%,rgba(255,140,50,.05) 100%);border:1px solid rgba(255,140,50,.2)">
                        <span class="iconify tabler--bowl-chopsticks size-5" style="color:#FF8C32"></span>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-foreground leading-tight" style="font-family:'Plus Jakarta Sans',sans-serif">
                            Comandas
                        </h2>
                        <p class="text-xs text-muted-foreground-1 mt-0.5">
                            SYNTIfood Order Engine
                            @if($isFoodAnual && count($comandas) > 0)
                                •
                                @php
                                    $todayCount = collect($comandas)->filter(fn($c) => isset($c['date']) && str_starts_with($c['date'], date('Y-m-d')))->count();
                                @endphp
                                <span data-comandas-today class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-primary/10 text-primary ml-1">
                                    {{ $todayCount }} hoy
                                </span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- ── Plan gate ────────────────────────────────────────────── --}}
            @if(!$isFoodAnual)
                <div class="rounded-xl border border-dashed border-border bg-muted/40 p-8 text-center">
                    <div class="size-14 rounded-2xl bg-muted flex items-center justify-center mx-auto mb-4">
                        <span class="iconify tabler--lock size-7 text-muted-foreground-2"></span>
                    </div>
                    <h3 class="text-base font-bold text-foreground mb-1">Plan Visión requerido</h3>
                    <p class="text-sm text-muted-foreground-1 max-w-md mx-auto">
                        Esta función requiere el Plan Visión de SYNTIfood para guardar comandas con código SF-XXXX.
                    </p>
                </div>

            {{-- ── Empty state ──────────────────────────────────────────── --}}
            @elseif(count($comandas) === 0)
                <div class="rounded-xl border border-border bg-surface p-8 text-center">
                    <div class="size-14 rounded-2xl bg-muted flex items-center justify-center mx-auto mb-4">
                        <span class="iconify tabler--bowl-chopsticks size-7 text-muted-foreground-2"></span>
                    </div>
                    <h3 class="text-base font-bold text-foreground mb-1">Aún no tienes comandas</h3>
                    <p class="text-sm text-muted-foreground-1 max-w-md mx-auto">
                        Cuando un cliente haga un pedido desde tu menú, aparecerá aquí con su código SF-XXXX.
                    </p>
                </div>

            {{-- ── Comandas table ───────────────────────────────────────── --}}
            @else
                <div class="rounded-xl border border-border bg-surface overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-border">
                            <thead class="bg-muted/50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-muted-foreground-1 uppercase tracking-wider">ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-muted-foreground-1 uppercase tracking-wider">Hora</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-muted-foreground-1 uppercase tracking-wider">Cliente</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-muted-foreground-1 uppercase tracking-wider">Ítems</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-muted-foreground-1 uppercase tracking-wider">Total REF</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-muted-foreground-1 uppercase tracking-wider">Estado</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-muted-foreground-1 uppercase tracking-wider">Acción</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-muted-foreground-1 uppercase tracking-wider">Detalle</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border" x-data="{ expanded: null }">
                                @foreach($comandas as $idx => $comanda)
                                @php
                                    $status = $comanda['status'] ?? 'pending';
                                @endphp
                                <tr class="hover:bg-muted/30 transition-colors cursor-pointer {{ $status !== 'pending' ? 'opacity-70' : '' }}"
                                    @click="expanded = expanded === {{ $idx }} ? null : {{ $idx }}">
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-bold bg-primary/10 text-primary">
                                            {{ $comanda['id'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-foreground">
                                        {{ \Carbon\Carbon::parse($comanda['date'])->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-medium text-foreground">{{ $comanda['customer']['name'] ?? ($comanda['customer_name'] ?? '—') }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-center text-sm text-foreground">{{ count($comanda['items'] ?? []) }}</td>
                                    <td class="px-4 py-3 text-right text-sm font-bold text-foreground">
                                        REF {{ number_format($comanda['total'] ?? 0, 2, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($status === 'attended')
                                            <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">Atendida</span>
                                        @else
                                            <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-amber-100 text-amber-700">Pendiente</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center" @click.stop>
                                        <div class="flex items-center justify-center gap-2">
                                            @if($status === 'pending')
                                                <button type="button"
                                                        class="inline-flex items-center justify-center size-8 rounded-lg bg-emerald-100 text-emerald-700 hover:bg-emerald-200 transition-colors cursor-pointer"
                                                        title="Marcar atendida"
                                                        onclick="handleComandaAction('{{ $comanda['id'] }}', 'attended', this)">
                                                    <span class="iconify tabler--check size-4"></span>
                                                </button>
                                            @endif
                                            <button type="button"
                                                    class="inline-flex items-center justify-center size-8 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 transition-colors cursor-pointer"
                                                    title="Eliminar comanda"
                                                    onclick="handleComandaAction('{{ $comanda['id'] }}', 'delete', this)">
                                                <span class="iconify tabler--trash size-4"></span>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="iconify size-4 transition-transform duration-200 text-muted-foreground-1"
                                              :class="expanded === {{ $idx }} ? 'tabler--chevron-up' : 'tabler--chevron-down'"></span>
                                    </td>
                                </tr>
                                {{-- Inline detail row --}}
                                <tr x-show="expanded === {{ $idx }}" x-collapse>
                                    <td colspan="8" class="px-4 py-4 bg-muted/20">
                                        <div class="space-y-2">
                                            @foreach($comanda['items'] ?? [] as $item)
                                            <div class="flex items-center justify-between text-sm">
                                                <div class="flex items-center gap-2">
                                                    <span class="iconify tabler--point-filled size-3 text-primary"></span>
                                                    <span class="text-foreground">{{ $item['nombre'] }}</span>
                                                </div>
                                                <div class="text-right text-foreground">
                                                    <span class="text-muted-foreground-1">x{{ $item['qty'] }}</span>
                                                    <span class="ml-2 font-medium">REF {{ number_format($item['qty'] * $item['precio'], 2, ',', '.') }}</span>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        <div class="mt-3 pt-3 border-t border-border flex items-center justify-between">
                                            <span class="text-xs text-muted-foreground-1">
                                                <span class="iconify tabler--brand-whatsapp size-3.5 inline-block align-text-bottom mr-1"></span>
                                                Canal: {{ $comanda['channel'] ?? 'whatsapp' }}
                                                @if(($comanda['status'] ?? 'pending') === 'attended')
                                                    • Atendida
                                                @endif
                                            </span>
                                            <span class="text-sm font-bold text-foreground">
                                                Total: REF {{ number_format($comanda['total'] ?? 0, 2, ',', '.') }}
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            </div>

            {{-- ── Auto-refresh cada 60s ──────────────────────────────── --}}
            @if($isFoodAnual)
            <script>
            (function(){
                var refreshInterval = 60000;
                var tenantId = @json($tenant->id);
                var container = document.getElementById('tab-comandas');
                if (!container) return;

                function formatDate(iso) {
                    var d = new Date(iso);
                    var dd = String(d.getDate()).padStart(2,'0');
                    var mm = String(d.getMonth()+1).padStart(2,'0');
                    var yyyy = d.getFullYear();
                    var hh = String(d.getHours()).padStart(2,'0');
                    var mi = String(d.getMinutes()).padStart(2,'0');
                    return dd+'/'+mm+'/'+yyyy+' '+hh+':'+mi;
                }
                function formatRef(n) {
                    return 'REF ' + Number(n).toFixed(2).replace('.',',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                }

                function refreshComandas() {
                    fetch('/tenant/' + tenantId + '/comandas-json', {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(function(r){ return r.json(); })
                    .then(function(data){
                        if (!data.comandas) return;

                        // Update badge count
                        var badge = container.querySelector('[data-comandas-today]');
                        if (badge) badge.textContent = data.today + ' hoy';

                        // Update table body
                        var tbody = container.querySelector('tbody');
                        if (!tbody || !data.comandas.length) return;

                        var html = '';
                        data.comandas.forEach(function(c, idx){
                            var name = (c.customer && c.customer.name) ? c.customer.name : (c.customer_name || '\u2014');
                            var itemCount = c.items ? c.items.length : 0;
                            html += '<tr class="hover:bg-muted/30 transition-colors cursor-pointer" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display===\'none\'?\'\':\'none\'">';
                            html += '<td class="px-4 py-3"><span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-bold bg-primary/10 text-primary">' + c.id + '</span></td>';
                            html += '<td class="px-4 py-3 text-sm text-foreground">' + formatDate(c.date) + '</td>';
                            html += '<td class="px-4 py-3"><div class="text-sm font-medium text-foreground">' + name + '</div></td>';
                            html += '<td class="px-4 py-3 text-center text-sm text-foreground">' + itemCount + '</td>';
                            html += '<td class="px-4 py-3 text-right text-sm font-bold text-foreground">' + formatRef(c.total || 0) + '</td>';
                            var status = c.status || 'pending';
                            var statusBadge = status === 'attended'
                                ? '<span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">Atendida</span>'
                                : '<span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-amber-100 text-amber-700">Pendiente</span>';
                            html += '<td class="px-4 py-3 text-center">' + statusBadge + '</td>';
                            html += '<td class="px-4 py-3 text-center"><div class="flex items-center justify-center gap-2">';
                            if (status === 'pending') {
                                html += '<button type="button" class="inline-flex items-center justify-center size-8 rounded-lg bg-emerald-100 text-emerald-700 hover:bg-emerald-200 transition-colors cursor-pointer" title="Marcar atendida" onclick="handleComandaAction(\'' + c.id + '\', \'attended\', this)"><span class="iconify tabler--check size-4"></span></button>';
                            }
                            html += '<button type="button" class="inline-flex items-center justify-center size-8 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 transition-colors cursor-pointer" title="Eliminar comanda" onclick="handleComandaAction(\'' + c.id + '\', \'delete\', this)"><span class="iconify tabler--trash size-4"></span></button>';
                            html += '</div></td>';
                            html += '<td class="px-4 py-3 text-center"><span class="iconify tabler--chevron-down size-4 text-muted-foreground-1"></span></td>';
                            html += '</tr>';
                            // Detail row
                            html += '<tr style="display:none"><td colspan="8" class="px-4 py-4 bg-muted/20"><div class="space-y-2">';
                            (c.items || []).forEach(function(item){
                                html += '<div class="flex items-center justify-between text-sm">';
                                html += '<div class="flex items-center gap-2"><span class="iconify tabler--point-filled size-3 text-primary"></span><span class="text-foreground">' + item.nombre + '</span></div>';
                                html += '<div class="text-right text-foreground"><span class="text-muted-foreground-1">x' + item.qty + '</span><span class="ml-2 font-medium">' + formatRef(item.qty * item.precio) + '</span></div>';
                                html += '</div>';
                            });
                            html += '</div><div class="mt-3 pt-3 border-t border-border flex items-center justify-between">';
                            html += '<span class="text-xs text-muted-foreground-1"><span class="iconify tabler--brand-whatsapp size-3.5 inline-block align-text-bottom mr-1"></span>Canal: ' + (c.channel || 'whatsapp') + (status === 'attended' ? ' • Atendida' : '') + '</span>';
                            html += '<span class="text-sm font-bold text-foreground">Total: ' + formatRef(c.total || 0) + '</span>';
                            html += '</div></td></tr>';
                        });
                        tbody.innerHTML = html;
                    })
                    .catch(function(){});
                }

                window.handleComandaAction = function(comandaId, action, triggerEl) {
                    if (!comandaId || !action) {
                        return;
                    }

                    if (action === 'delete' && !confirm('¿Eliminar esta comanda? Esta acción no se puede deshacer.')) {
                        return;
                    }

                    fetch('/tenant/' + tenantId + '/comandas/' + encodeURIComponent(comandaId) + '/action', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ action: action })
                    })
                    .then(function(r){ return r.json(); })
                    .then(function(data){
                        if (!data.success) {
                            alert(data.message || 'No se pudo procesar la acción.');
                            return;
                        }

                        // Keep table fully in sync after local removal.
                        refreshComandas();
                    })
                    .catch(function(){
                        alert('Error de conexión al procesar la comanda.');
                    });
                };

                setInterval(refreshComandas, refreshInterval);
            })();
            </script>
            @endif
        </div>
