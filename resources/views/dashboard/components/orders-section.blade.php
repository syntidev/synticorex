        <!-- Tab: Pedidos (Mini Order Engine) -->
        <div id="tab-pedidos" class="tab-content">
            <div class="p-6">

            {{-- ── Hero header ──────────────────────────────────────────── --}}
            <div class="mb-5 pb-5 border-b border-border">
                <div class="flex items-center gap-3">
                    <div class="size-10 rounded-xl flex items-center justify-center shrink-0"
                         style="background:linear-gradient(135deg,rgba(77,143,255,.15) 0%,rgba(77,143,255,.05) 100%);border:1px solid rgba(77,143,255,.2)">
                        <span class="iconify tabler--shopping-bag size-5" style="color:#4D8FFF"></span>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-foreground leading-tight" style="font-family:'Plus Jakarta Sans',sans-serif">
                            Pedidos
                        </h2>
                        <p class="text-xs text-muted-foreground-1 mt-0.5">
                            Mini Order Engine
                            @if($isPlanAnual && count($orders) > 0)
                                •
                                @php
                                    $todayCount = collect($orders)->filter(fn($o) => isset($o['date']) && str_starts_with($o['date'], date('Y-m-d')))->count();
                                @endphp
                                <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-medium bg-primary/10 text-primary ml-1">
                                    {{ $todayCount }} hoy
                                </span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- ── Plan gate ────────────────────────────────────────────── --}}
            @if(!$isPlanAnual)
                <div class="rounded-xl border border-dashed border-border bg-muted/40 p-8 text-center">
                    <div class="size-14 rounded-2xl bg-muted flex items-center justify-center mx-auto mb-4">
                        <span class="iconify tabler--lock size-7 text-muted-foreground-2"></span>
                    </div>
                    <h3 class="text-base font-bold text-foreground mb-1">Plan Anual requerido</h3>
                    <p class="text-sm text-muted-foreground-1 max-w-md mx-auto">
                        Esta función requiere el Plan Anual SYNTIcat para generar pedidos con código SC-XXXX.
                    </p>
                </div>

            {{-- ── Empty state ──────────────────────────────────────────── --}}
            @elseif(count($orders) === 0)
                <div class="rounded-xl border border-border bg-surface p-8 text-center">
                    <div class="size-14 rounded-2xl bg-muted flex items-center justify-center mx-auto mb-4">
                        <span class="iconify tabler--shopping-bag size-7 text-muted-foreground-2"></span>
                    </div>
                    <h3 class="text-base font-bold text-foreground mb-1">Aún no tienes pedidos</h3>
                    <p class="text-sm text-muted-foreground-1 max-w-md mx-auto">
                        Cuando un cliente haga un pedido desde tu catálogo, aparecerá aquí con su código SC-XXXX.
                    </p>
                </div>

            {{-- ── Orders table ─────────────────────────────────────────── --}}
            @else
                <div class="rounded-xl border border-border bg-surface overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-border">
                            <thead class="bg-muted/50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-muted-foreground-1 uppercase tracking-wider">ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-muted-foreground-1 uppercase tracking-wider">Fecha</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-muted-foreground-1 uppercase tracking-wider">Cliente</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-muted-foreground-1 uppercase tracking-wider">Items</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-muted-foreground-1 uppercase tracking-wider">Total</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-muted-foreground-1 uppercase tracking-wider">Detalle</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border" x-data="{ expanded: null }">
                                @foreach($orders as $idx => $order)
                                <tr class="hover:bg-muted/30 transition-colors cursor-pointer"
                                    @click="expanded = expanded === {{ $idx }} ? null : {{ $idx }}">
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center py-0.5 px-2 rounded-full text-xs font-bold bg-primary/10 text-primary">
                                            {{ $order['id'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-foreground">
                                        {{ \Carbon\Carbon::parse($order['date'])->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-medium text-foreground">{{ $order['customer']['name'] ?? '—' }}</div>
                                        @if(!empty($order['customer']['location']))
                                            <div class="text-xs text-muted-foreground-1">{{ $order['customer']['location'] }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center text-sm text-foreground">{{ count($order['items'] ?? []) }}</td>
                                    <td class="px-4 py-3 text-right text-sm font-bold text-foreground">
                                        REF {{ number_format($order['subtotal'] ?? 0, 2, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="iconify size-4 transition-transform duration-200 text-muted-foreground-1"
                                              :class="expanded === {{ $idx }} ? 'tabler--chevron-up' : 'tabler--chevron-down'"></span>
                                    </td>
                                </tr>
                                {{-- Inline detail row --}}
                                <tr x-show="expanded === {{ $idx }}" x-collapse>
                                    <td colspan="6" class="px-4 py-4 bg-muted/20">
                                        <div class="space-y-2">
                                            @foreach($order['items'] ?? [] as $item)
                                            <div class="flex items-center justify-between text-sm">
                                                <div class="flex items-center gap-2">
                                                    <span class="iconify tabler--point-filled size-3 text-primary"></span>
                                                    <span class="text-foreground">{{ $item['title'] }}</span>
                                                    @if(!empty($item['variant']))
                                                        <span class="text-xs text-muted-foreground-1">({{ $item['variant'] }})</span>
                                                    @endif
                                                </div>
                                                <div class="text-right text-foreground">
                                                    <span class="text-muted-foreground-1">x{{ $item['qty'] }}</span>
                                                    <span class="ml-2 font-medium">REF {{ number_format($item['qty'] * $item['price'], 2, ',', '.') }}</span>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        <div class="mt-3 pt-3 border-t border-border flex items-center justify-between">
                                            <span class="text-xs text-muted-foreground-1">
                                                <span class="iconify tabler--brand-whatsapp size-3.5 inline-block align-text-bottom mr-1"></span>
                                                Canal: {{ $order['channel'] ?? 'whatsapp' }}
                                            </span>
                                            <span class="text-sm font-bold text-foreground">
                                                Subtotal: REF {{ number_format($order['subtotal'] ?? 0, 2, ',', '.') }}
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
        </div>
