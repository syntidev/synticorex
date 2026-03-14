        <!-- Tab: Pedidos (Mini Order Engine) -->
        <div id="tab-pedidos" class="tab-content">
            <div class="p-6">
            @php
                $paymentMethods = (array) data_get($customization?->payment_methods, 'global', []);
                $paymentCurrencies = (array) data_get($customization?->payment_methods, 'currency', []);
                $paymentDetails = (array) data_get($customization?->payment_methods, 'details', []);

                $paymentLabels = [
                    'pagoMovil' => 'Pago Movil',
                    'cash' => 'Efectivo',
                    'puntoventa' => 'Punto de Venta',
                    'biopago' => 'Biopago',
                    'cashea' => 'Cashea',
                    'krece' => 'Krece',
                    'wepa' => 'Wepa',
                    'lysto' => 'Lysto',
                    'chollo' => 'Chollo',
                    'wally' => 'Wally',
                    'kontigo' => 'Kontigo',
                    'zelle' => 'Zelle',
                    'paypal' => 'PayPal',
                    'zinli' => 'Zinli',
                    'airtm' => 'AirTM',
                    'reserve' => 'Reserve (RSV)',
                    'binancepay' => 'Binance Pay',
                    'usdt' => 'USDT',
                    'usd' => 'USD',
                    'eur' => 'EUR',
                ];

                $paymentText = collect(array_merge($paymentMethods, $paymentCurrencies))
                    ->map(fn(string $code): string => $paymentLabels[$code] ?? strtoupper($code))
                    ->filter()
                    ->values()
                    ->implode(', ');

                if ($paymentText === '') {
                    $paymentText = 'Pago Movil, Transferencia o Divisas';
                }
            @endphp

            {{-- ── Hero header ──────────────────────────────────────────── --}}
            <div class="mb-5 pb-5 border-b border-border">
                <div class="flex items-center gap-3">
                    <div class="size-10 rounded-xl flex items-center justify-center shrink-0"
                         style="background:linear-gradient(135deg,rgba(77,143,255,.15) 0%,rgba(77,143,255,.05) 100%);border:1px solid rgba(77,143,255,.2)">
                        <span class="iconify tabler--shopping-bag size-5" style="color:#4D8FFF"></span>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-foreground leading-tight" style="font-family:'Plus Jakarta Sans',sans-serif">
                            Ordenes CAT
                        </h2>
                        <p class="text-xs text-muted-foreground-1 mt-0.5">
                            Carrito y ordenes por WhatsApp
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
                    <h3 class="text-base font-bold text-foreground mb-1">Plan Visión requerido</h3>
                    <p class="text-sm text-muted-foreground-1 max-w-md mx-auto">
                        Esta función requiere el Plan Visión de SYNTIcat para generar pedidos con código SC-XXXX.
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
                                    <th class="px-4 py-3 text-left text-xs font-bold text-muted-foreground-1 uppercase tracking-wider">Telefono</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-muted-foreground-1 uppercase tracking-wider">Items</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-muted-foreground-1 uppercase tracking-wider">Total</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-muted-foreground-1 uppercase tracking-wider">Estado</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-muted-foreground-1 uppercase tracking-wider">Accion</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-muted-foreground-1 uppercase tracking-wider">Pago</th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-muted-foreground-1 uppercase tracking-wider">Detalle</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border" x-data="{ expanded: null }">
                                @foreach($orders as $idx => $order)
                                @php
                                    $status = $order['status'] ?? 'pending';
                                @endphp
                                <tr class="hover:bg-muted/30 transition-colors cursor-pointer"
                                    @click="expanded = expanded === {{ $idx }} ? null : {{ $idx }}">
                                    @php
                                        $customerPhoneRaw = preg_replace('/\D+/', '', (string) data_get($order, 'customer.phone', ''));

                                        if (str_starts_with($customerPhoneRaw, '0')) {
                                            $customerPhone = '58' . substr($customerPhoneRaw, 1);
                                        } elseif (str_starts_with($customerPhoneRaw, '58')) {
                                            $customerPhone = $customerPhoneRaw;
                                        } elseif (strlen($customerPhoneRaw) === 10 && str_starts_with($customerPhoneRaw, '4')) {
                                            $customerPhone = '58' . $customerPhoneRaw;
                                        } else {
                                            $customerPhone = '';
                                        }

                                        $paymentBlocks = [];

                                        if (in_array('pagoMovil', $paymentMethods, true)) {
                                            $pagoMovilConfig = (array) ($paymentDetails['pagoMovil'] ?? []);
                                            $pagoMovilPhone = (bool) ($pagoMovilConfig['use_business_whatsapp'] ?? true)
                                                ? trim((string) ($tenant->getActiveWhatsapp() ?? ''))
                                                : trim((string) ($pagoMovilConfig['phone'] ?? ''));
                                            $pagoMovilDocument = trim(
                                                trim((string) ($pagoMovilConfig['document_type'] ?? ''))
                                                . (((string) ($pagoMovilConfig['document_type'] ?? '')) !== '' && ((string) ($pagoMovilConfig['document_number'] ?? '')) !== '' ? '-' : '')
                                                . trim((string) ($pagoMovilConfig['document_number'] ?? ''))
                                            );
                                            $pagoMovilLines = array_filter([
                                                '1. Pago Movil',
                                                !empty($pagoMovilConfig['bank']) ? 'Banco: ' . $pagoMovilConfig['bank'] : null,
                                                $pagoMovilPhone !== '' ? 'Telefono: ' . $pagoMovilPhone : null,
                                                $pagoMovilDocument !== '' ? 'Documento: ' . $pagoMovilDocument : null,
                                                !empty($pagoMovilConfig['account_holder']) ? 'Titular: ' . $pagoMovilConfig['account_holder'] : null,
                                            ]);
                                            if (count($pagoMovilLines) > 1) {
                                                $paymentBlocks[] = implode("\n", $pagoMovilLines);
                                            }
                                        }

                                        if (in_array('paypal', $paymentMethods, true)) {
                                            $paypalConfig = (array) ($paymentDetails['paypal'] ?? []);
                                            $paypalLines = array_filter([
                                                '2. PayPal',
                                                !empty($paypalConfig['email']) ? 'Correo: ' . $paypalConfig['email'] : null,
                                                !empty($paypalConfig['account_holder']) ? 'Titular: ' . $paypalConfig['account_holder'] : null,
                                            ]);
                                            if (count($paypalLines) > 1) {
                                                $paymentBlocks[] = implode("\n", $paypalLines);
                                            }
                                        }

                                        if (in_array('zinli', $paymentMethods, true)) {
                                            $zinliConfig = (array) ($paymentDetails['zinli'] ?? []);
                                            $zinliLines = array_filter([
                                                '3. Zinli',
                                                !empty($zinliConfig['email']) ? 'Correo: ' . $zinliConfig['email'] : null,
                                                !empty($zinliConfig['account_holder']) ? 'Titular: ' . $zinliConfig['account_holder'] : null,
                                            ]);
                                            if (count($zinliLines) > 1) {
                                                $paymentBlocks[] = implode("\n", $zinliLines);
                                            }
                                        }

                                        $otherPaymentText = collect(array_merge($paymentMethods, $paymentCurrencies))
                                            ->reject(fn(string $code): bool => in_array($code, ['pagoMovil', 'paypal', 'zinli'], true))
                                            ->map(fn(string $code): string => $paymentLabels[$code] ?? strtoupper($code))
                                            ->filter()
                                            ->values()
                                            ->implode(', ');

                                        $paymentReplyMessage = "Hola {$order['customer']['name']}, recibimos tu orden {$order['id']}.";

                                        if ($paymentBlocks !== []) {
                                            $paymentReplyMessage .= "\n\nFormas de pago disponibles:\n\n" . implode("\n\n", $paymentBlocks);
                                        } else {
                                            $paymentReplyMessage .= "\n\nFormas de pago disponibles: {$paymentText}.";
                                        }

                                        if ($otherPaymentText !== '') {
                                            $paymentReplyMessage .= "\n\nOtros medios disponibles: {$otherPaymentText}.";
                                        }

                                        $paymentReplyMessage .= "\n\nCuando pagues, envia el comprobante por este chat para confirmar tu pedido.";
                                    @endphp
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
                                    <td class="px-4 py-3 text-sm text-foreground">{{ $customerPhone !== '' ? $customerPhone : '—' }}</td>
                                    <td class="px-4 py-3 text-center text-sm text-foreground">{{ count($order['items'] ?? []) }}</td>
                                    <td class="px-4 py-3 text-right text-sm font-bold text-foreground">
                                        REF {{ number_format($order['subtotal'] ?? 0, 2, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-center" @click.stop>
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
                                                        onclick="handleOrderAction('{{ $order['id'] }}', 'attended', this)">
                                                    <span class="iconify tabler--check size-4"></span>
                                                </button>
                                            @endif
                                            <button type="button"
                                                    class="inline-flex items-center justify-center size-8 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 transition-colors cursor-pointer"
                                                    title="Eliminar orden"
                                                    onclick="handleOrderAction('{{ $order['id'] }}', 'delete', this)">
                                                <span class="iconify tabler--trash size-4"></span>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center" @click.stop>
                                        @if($customerPhone !== '')
                                            <a href="https://wa.me/{{ $customerPhone }}?text={{ rawurlencode($paymentReplyMessage) }}"
                                               target="_blank"
                                               rel="noopener"
                                               class="inline-flex h-9 min-w-11 items-center justify-center rounded-lg px-2.5 text-xs font-semibold bg-emerald-500 text-white hover:bg-emerald-600 transition-colors cursor-pointer">
                                                Enviar pack
                                            </a>
                                        @else
                                            <span class="inline-flex h-9 min-w-11 items-center justify-center rounded-lg px-2.5 text-xs font-semibold bg-muted text-muted-foreground-1">Sin numero</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="iconify size-4 transition-transform duration-200 text-muted-foreground-1"
                                              :class="expanded === {{ $idx }} ? 'tabler--chevron-up' : 'tabler--chevron-down'"></span>
                                    </td>
                                </tr>
                                {{-- Inline detail row --}}
                                <tr x-show="expanded === {{ $idx }}" x-collapse>
                                    <td colspan="10" class="px-4 py-4 bg-muted/20">
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

            @if($isPlanAnual)
            <script>
            (function(){
                var tenantId = @json($tenant->id);

                window.handleOrderAction = function(orderId, action) {
                    if (!orderId || !action) {
                        return;
                    }

                    if (action === 'delete' && !confirm('¿Eliminar esta orden? Esta acción no se puede deshacer.')) {
                        return;
                    }

                    fetch('/tenant/' + tenantId + '/orders/' + encodeURIComponent(orderId) + '/action', {
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

                        window.location.reload();
                    })
                    .catch(function(){
                        alert('Error de conexión al procesar la orden.');
                    });
                };
            })();
            </script>
            @endif
        </div>
