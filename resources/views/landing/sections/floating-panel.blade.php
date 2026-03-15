{{-- ═══════════════════════════════════════════════════════════
     SYNTIweb — Floating Panel v2
     Sin emojis. Tabler Icons. Diseño premium oscuro.
     Preserva toda la lógica JS/PHP existente.
════════════════════════════════════════════════════════════ --}}

@php
$planSlug = $tenant->relationLoaded('plan') && $tenant->plan
    ? $tenant->plan->slug
    : (isset($plan) && $plan ? $plan->slug : '');

$canSeeWhatsappKpi = in_array($planSlug, [
    'studio-crecimiento', 'studio-vision',
    'crecimiento', 'vision',
]);
$canSeeQrKpi       = $canSeeWhatsappKpi;
$canSeeProductsKpi = in_array($planSlug, ['studio-vision', 'vision']);
@endphp

{{-- ── Panel principal ── --}}
<div id="synti-panel" class="synti-panel">

    {{-- Header sticky --}}
    <div class="synti-panel-header">
        <div class="synti-header-brand">
            <img src="{{ asset('brand/syntiweb-logo-negative.svg') }}"
                 alt="SYNTIweb" width="22" height="22">
            <div>
                <div class="synti-logo">SYNTI<em>web</em></div>
                <div class="synti-version">{{ $tenant->business_name }}</div>
            </div>
        </div>
        <button onclick="closeSyntiPanel()" class="synti-close-btn" aria-label="Cerrar panel">
            <iconify-icon icon="tabler:x" width="16"></iconify-icon>
        </button>
    </div>

    {{-- PIN Modal --}}
    <div id="synti-pin-modal" class="synti-pin-modal">
        <div class="synti-pin-content">
            <div class="synti-pin-icon">
                <iconify-icon icon="tabler:lock" width="28"></iconify-icon>
            </div>
            <h3>Acceso propietario</h3>
            <p class="synti-pin-subtitle">Ingresa tu PIN de 4 dígitos</p>
            <div class="synti-pin-inputs">
                <input type="text" maxlength="1" inputmode="numeric" class="synti-pin-digit" id="pin-1" autocomplete="off" />
                <input type="text" maxlength="1" inputmode="numeric" class="synti-pin-digit" id="pin-2" autocomplete="off" />
                <input type="text" maxlength="1" inputmode="numeric" class="synti-pin-digit" id="pin-3" autocomplete="off" />
                <input type="text" maxlength="1" inputmode="numeric" class="synti-pin-digit" id="pin-4" autocomplete="off" />
            </div>
            <button onclick="verifyPin()" class="synti-btn-primary">Entrar</button>
            <div id="synti-pin-error" class="synti-pin-error" style="display:none;">PIN incorrecto</div>
        </div>
    </div>

    {{-- Panel Content --}}
    <div id="synti-panel-content" style="display:none;">

        {{-- ── EL RADAR — KPIs ── --}}
        <div class="synti-section">
            <p class="synti-label">EL RADAR · PULSO DE VENTA</p>

            @php
            $kpiCount = 1
                + ($canSeeWhatsappKpi ? 1 : 0)
                + ($canSeeQrKpi ? 1 : 0)
                + ($canSeeProductsKpi ? 1 : 0);
            @endphp

            <div class="synti-kpi-grid synti-kpi-count-{{ $kpiCount }}">
                <div class="synti-kpi-card">
                    <div class="synti-kpi-value" id="kpi-visits">0</div>
                    <div class="synti-kpi-label">Visitas hoy</div>
                </div>
                @if($canSeeWhatsappKpi)
                <div class="synti-kpi-card">
                    <div class="synti-kpi-value" id="kpi-whatsapp">0</div>
                    <div class="synti-kpi-label">Clics WhatsApp</div>
                </div>
                @endif
                @if($canSeeQrKpi)
                <div class="synti-kpi-card">
                    <div class="synti-kpi-value" id="kpi-qr">0</div>
                    <div class="synti-kpi-label">Escaneos QR</div>
                </div>
                @endif
                @if($canSeeProductsKpi)
                <div class="synti-kpi-card">
                    <div class="synti-kpi-value" id="kpi-products">0</div>
                    <div class="synti-kpi-label">Productos vistos</div>
                </div>
                @endif
            </div>

            @php
            $planDisplayName = match($planSlug) {
                'oportunidad', 'studio-oportunidad' => 'Plan Oportunidad',
                'crecimiento', 'studio-crecimiento' => 'Plan Crecimiento',
                'vision', 'studio-vision'           => 'Plan Visión',
                'food-basico'                        => 'Food Básico',
                'food-semestral'                     => 'Food Semestral',
                'food-anual'                         => 'Food Anual',
                'cat-basico'                         => 'Cat Básico',
                'cat-semestral'                      => 'Cat Semestral',
                'cat-anual'                          => 'Cat Anual',
                default                              => 'Plan Activo',
            };
            $isTopPlan = in_array($planSlug, ['studio-vision', 'vision', 'food-anual', 'cat-anual']);
            @endphp

            <div class="synti-plan-pill">
                <iconify-icon icon="tabler:rosette-discount-check" width="13"></iconify-icon>
                <span>{{ $planDisplayName }}</span>
                @if(!$isTopPlan)
                    <a href="/planes" target="_blank" class="synti-plan-upgrade">Ver planes →</a>
                @endif
            </div>
        </div>

        {{-- ── TASA DEL DÓLAR ── --}}
        <div class="synti-section">
            <p class="synti-label">TASA DEL DÓLAR</p>
            <div class="synti-dollar-row">
                <div>
                    <span class="synti-dollar-value" id="dollar-rate-display">Bs. {{ number_format($dollarRate, 2) }}</span>
                    <span class="synti-dollar-unit">Bs/$</span>
                </div>
                <button onclick="refreshDollarRate()" class="synti-btn-refresh" title="Actualizar tasa" aria-label="Actualizar tasa del dólar">
                    <iconify-icon icon="tabler:refresh" width="16"></iconify-icon>
                    Actualizar
                </button>
            </div>
        </div>

        {{-- ── CÓDIGO DIGITAL ── --}}
        <div class="synti-section">
            <p class="synti-label">CÓDIGO DIGITAL &middot; PERMANENTE</p>
            <div class="synti-qr-container">
                <p class="synti-qr-cta">Apunta tu cámara aquí</p>
                <div class="synti-qr-wrapper" id="qr-floating-display">
                    {!! $trackingQRSmall ?? '' !!}
                </div>
                <p class="synti-qr-hint">Un código. Toda tu presencia digital.</p>
                <button onclick="downloadQRFloating()" class="synti-btn-qr-download">
                    <iconify-icon icon="tabler:download" width="16"></iconify-icon>
                    Descargar código
                </button>
            </div>
        </div>

        {{-- ── INFORMACIÓN DEL NEGOCIO ── --}}
        <div class="synti-section">
            <p class="synti-label">INFORMACIÓN DEL NEGOCIO</p>
            <div class="synti-info-rows">
                @if($tenant->business_hours)
                <div class="synti-info-row">
                    <span class="synti-info-key">Horarios</span>
                    <span class="synti-info-val">
                        @php
                            try {
                                $rawHours = $tenant->business_hours;
                                if (is_string($rawHours)) $rawHours = json_decode($rawHours, true);
                                $days = collect(is_array($rawHours) ? $rawHours : [])
                                    ->filter(fn($h) => !empty($h) && $h !== 'closed')
                                    ->take(2);
                            } catch(\Exception $e) {
                                $days = collect([]);
                            }
                        @endphp
                        @foreach($days as $day => $range)
                            @if(is_string($range))
                                <span class="block">{{ ucfirst($day) }}: {{ $range }}</span>
                            @endif
                        @endforeach
                    </span>
                </div>
                @endif
                @if($tenant->whatsapp_sales)
                <div class="synti-info-row">
                    <span class="synti-info-key">WhatsApp Ventas</span>
                    <span class="synti-info-val synti-info-link">{{ $tenant->whatsapp_sales }}</span>
                </div>
                @endif
                @if($tenant->whatsapp_support)
                <div class="synti-info-row">
                    <span class="synti-info-key">WhatsApp Soporte</span>
                    <span class="synti-info-val synti-info-link">{{ $tenant->whatsapp_support }}</span>
                </div>
                @endif
                @if($tenant->address)
                <div class="synti-info-row">
                    <span class="synti-info-key">Dirección</span>
                    <span class="synti-info-val">{{ $tenant->address }}@if($tenant->city), {{ $tenant->city }}@endif</span>
                </div>
                @endif
            </div>
        </div>

        {{-- ── ESTADO DEL NEGOCIO ── --}}
        <div class="synti-section">
            <p class="synti-label">ESTADO DEL NEGOCIO</p>
            <div class="synti-toggle-row">
                <label class="synti-toggle">
                    <input type="checkbox" id="business-status-toggle"
                           {{ $tenant->is_open ? 'checked' : '' }}
                           onchange="toggleBusinessStatus()">
                    <span class="synti-toggle-slider"></span>
                </label>
                <span id="business-status-text" class="synti-toggle-label {{ $tenant->is_open ? 'open' : 'closed' }}">
                    {{ $tenant->is_open ? 'Abierto' : 'Cerrado' }}
                </span>
            </div>
        </div>

        {{-- ── LÍNEA WHATSAPP ACTIVA — Plan 2 y 3 ── --}}
        @if(isset($plan) && $plan->whatsapp_numbers >= 2)
        <div class="synti-section">
            <p class="synti-label">LÍNEA ACTIVA</p>
            <div class="synti-wa-toggle">
                <button onclick="switchWhatsapp('sales')"
                        id="wa-btn-sales"
                        class="synti-wa-btn {{ $tenant->whatsapp_active === 'sales' ? 'active' : '' }}">
                    <iconify-icon icon="tabler:brand-whatsapp" width="16"></iconify-icon>
                    Principal
                </button>
                <button onclick="switchWhatsapp('support')"
                        id="wa-btn-support"
                        class="synti-wa-btn {{ $tenant->whatsapp_active === 'support' ? 'active' : '' }}">
                    <iconify-icon icon="tabler:brand-whatsapp" width="16"></iconify-icon>
                    Soporte
                </button>
            </div>
            <p class="synti-wa-number" id="wa-active-number">
                {{ $tenant->getActiveWhatsapp() ?? 'Sin número configurado' }}
            </p>
        </div>
        @endif

        {{-- ── IR AL DASHBOARD ── --}}
        <div class="synti-section synti-section-last">
            <a href="/tenant/{{ $tenant->id }}/dashboard" class="synti-btn-dashboard">
                <iconify-icon icon="tabler:layout-dashboard" width="16"></iconify-icon>
                Ir al Dashboard completo
            </a>
        </div>

    </div>{{-- /synti-panel-content --}}
</div>{{-- /synti-panel --}}

{{-- ── Trigger móvil ── --}}
<div id="synti-mobile-trigger"
     onclick="openSyntiPanel()"
     role="button"
     aria-label="Abrir panel de control SYNTIweb">
    <iconify-icon icon="tabler:chart-bar" width="20" style="color:white;"></iconify-icon>
</div>

{{-- ════════════════════════════════════════════════════════════
     ESTILOS
════════════════════════════════════════════════════════════ --}}
<style>
/* ── Panel shell ── */
.synti-panel {
    position: fixed;
    top: 0; right: -360px;
    width: 360px;
    height: 100vh;
    background: #0d1117;
    color: #ffffff;
    z-index: 9999;
    transition: transform 0.3s cubic-bezier(0.4,0,0.2,1);
    overflow-y: auto;
    overflow-x: hidden;
    box-shadow: -8px 0 32px rgba(0,0,0,0.5);
    scrollbar-width: thin;
    scrollbar-color: rgba(255,255,255,0.08) transparent;
}
.synti-panel.open { transform: translateX(-360px); }

/* ── Header ── */
.synti-panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    background: #0a0f16;
    border-bottom: 1px solid rgba(255,255,255,0.06);
    position: sticky;
    top: 0;
    z-index: 10;
}
.synti-header-brand { display: flex; align-items: center; gap: 10px; }
.synti-logo { font-size: 16px; font-weight: 800; color: #4A80E4; letter-spacing: -0.4px; line-height: 1; }
.synti-logo em { color: #ffffff; font-style: normal; }
.synti-version { font-size: 11px; color: rgba(255,255,255,0.35); margin-top: 2px; }
.synti-close-btn {
    width: 30px; height: 30px;
    display: flex; align-items: center; justify-content: center;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 8px;
    color: rgba(255,255,255,0.6);
    cursor: pointer;
    transition: all 0.2s;
}
.synti-close-btn:hover { background: rgba(255,255,255,0.12); color: #fff; }

/* ── Secciones ── */
.synti-section {
    padding: 18px 20px;
    border-bottom: 1px solid rgba(255,255,255,0.05);
}
.synti-section-last { border-bottom: none; }
.synti-label {
    font-size: 10px;
    font-weight: 700;
    color: rgba(255,255,255,0.3);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 14px;
    display: block;
}

/* ── KPIs ── */
.synti-kpi-grid {
    display: grid;
    gap: 8px;
    grid-template-columns: 1fr 1fr;
}
.synti-kpi-count-1 {
    grid-template-columns: 1fr;
}
.synti-kpi-count-3 .synti-kpi-card:first-child {
    grid-column: span 2;
}
.synti-kpi-card {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 12px;
    padding: 14px 10px;
    text-align: center;
}
.synti-kpi-value {
    font-size: 28px;
    font-weight: 800;
    color: #4A80E4;
    line-height: 1;
    font-variant-numeric: tabular-nums;
}
.synti-kpi-label {
    font-size: 10px;
    color: rgba(255,255,255,0.35);
    margin-top: 5px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}
.synti-plan-pill {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 8px;
    font-size: 11px;
    font-weight: 600;
    color: rgba(255,255,255,0.35);
}
.synti-plan-upgrade {
    margin-left: auto;
    color: #4A80E4;
    font-size: 10px;
    font-weight: 700;
    text-decoration: none;
    letter-spacing: 0.02em;
}
.synti-plan-upgrade:hover {
    color: #7aa8ff;
}

/* ── Tasa dólar ── */
.synti-dollar-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: rgba(74,128,228,0.08);
    border: 1px solid rgba(74,128,228,0.2);
    border-radius: 12px;
    padding: 14px 16px;
}
.synti-dollar-value {
    font-size: 26px;
    font-weight: 800;
    color: #ffffff;
    line-height: 1;
}
.synti-dollar-unit {
    font-size: 12px;
    color: rgba(255,255,255,0.4);
    margin-left: 4px;
}
.synti-btn-refresh {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 7px 12px;
    background: #4A80E4;
    border: none;
    border-radius: 8px;
    color: #ffffff;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
    white-space: nowrap;
}
.synti-btn-refresh:hover { background: #3a6fd3; }

/* ── QR ── */
.synti-qr-container { text-align: center; }
.synti-qr-cta {
    font-size: 11px;
    font-weight: 700;
    color: rgba(255,255,255,0.55);
    letter-spacing: 0.08em;
    text-transform: uppercase;
    margin-bottom: 10px;
}
.synti-qr-wrapper {
    background: #ffffff;
    border-radius: 12px;
    padding: 10px;
    display: inline-block;
    margin-bottom: 10px;
}
.synti-qr-wrapper svg,
.synti-qr-wrapper img { width: 120px !important; height: 120px !important; display: block; }
.synti-qr-hint { font-size: 11px; color: rgba(255,255,255,0.3); margin-bottom: 4px; }
.synti-qr-url { font-size: 12px; color: #4A80E4; margin-bottom: 12px; word-break: break-all; }
.synti-btn-qr-download {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 8px;
    color: #ffffff;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.2s;
}
.synti-btn-qr-download:hover { background: rgba(255,255,255,0.12); }

/* ── Info rows ── */
.synti-info-rows { display: flex; flex-direction: column; gap: 10px; }
.synti-info-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
}
.synti-info-key {
    font-size: 12px;
    color: rgba(255,255,255,0.4);
    white-space: nowrap;
    flex-shrink: 0;
}
.synti-info-val {
    font-size: 12px;
    color: rgba(255,255,255,0.85);
    text-align: right;
}
.synti-info-link { color: #4A80E4; }

/* ── Toggle negocio ── */
.synti-toggle-row { display: flex; align-items: center; gap: 12px; }
.synti-toggle { position: relative; display: inline-block; width: 44px; height: 24px; flex-shrink: 0; }
.synti-toggle input { opacity: 0; width: 0; height: 0; }
.synti-toggle-slider {
    position: absolute; inset: 0;
    background: rgba(255,255,255,0.12);
    border-radius: 24px;
    cursor: pointer;
    transition: .25s;
}
.synti-toggle-slider:before {
    content: "";
    position: absolute;
    height: 18px; width: 18px;
    left: 3px; bottom: 3px;
    background: rgba(255,255,255,0.7);
    border-radius: 50%;
    transition: .25s;
}
.synti-toggle input:checked + .synti-toggle-slider { background: #22c55e; }
.synti-toggle input:checked + .synti-toggle-slider:before { transform: translateX(20px); background: #fff; }
.synti-toggle-label { font-size: 15px; font-weight: 700; color: rgba(255,255,255,0.5); }
.synti-toggle-label.open { color: #22c55e; }
.synti-toggle-label.closed { color: #ef4444; }

/* ── WhatsApp dual ── */
.synti-wa-toggle { display: flex; gap: 8px; margin-bottom: 10px; }
.synti-wa-btn {
    flex: 1;
    display: flex; align-items: center; justify-content: center; gap: 6px;
    padding: 9px 12px;
    border-radius: 10px;
    border: 1px solid rgba(255,255,255,0.08);
    background: rgba(255,255,255,0.04);
    color: rgba(255,255,255,0.4);
    font-size: 13px; font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}
.synti-wa-btn.active {
    background: rgba(37,211,102,0.12);
    border-color: rgba(37,211,102,0.35);
    color: #25d366;
}
.synti-wa-number {
    font-size: 13px;
    color: rgba(255,255,255,0.35);
    text-align: center;
}

/* ── Dashboard link ── */
.synti-btn-dashboard {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 11px 16px;
    background: rgba(74,128,228,0.1);
    border: 1px solid rgba(74,128,228,0.25);
    border-radius: 10px;
    color: #4A80E4;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
}
.synti-btn-dashboard:hover {
    background: rgba(74,128,228,0.18);
    color: #6fa0f7;
}

/* ── PIN modal ── */
.synti-pin-modal { padding: 48px 24px; text-align: center; }
.synti-pin-icon {
    width: 56px; height: 56px;
    background: rgba(74,128,228,0.12);
    border: 1px solid rgba(74,128,228,0.25);
    border-radius: 16px;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 20px;
    color: #4A80E4;
}
.synti-pin-content h3 { font-size: 18px; font-weight: 700; color: #ffffff; margin-bottom: 6px; }
.synti-pin-subtitle { font-size: 13px; color: rgba(255,255,255,0.4); margin-bottom: 28px; }
.synti-pin-inputs { display: flex; gap: 10px; justify-content: center; margin-bottom: 20px; }
.synti-pin-digit {
    width: 54px; height: 62px;
    border-radius: 12px;
    border: 1.5px solid rgba(255,255,255,0.1);
    background: rgba(255,255,255,0.05);
    color: #ffffff;
    font-size: 26px; font-weight: 800;
    text-align: center;
    outline: none;
    transition: border-color 0.2s, background 0.2s;
    -webkit-text-security: disc;
}
.synti-pin-digit:focus {
    border-color: #4A80E4;
    background: rgba(74,128,228,0.1);
}
.synti-pin-digit.shake { animation: sw-shake 0.45s ease; }
@keyframes sw-shake {
    0%,100% { transform: translateX(0); }
    20%,60% { transform: translateX(-8px); }
    40%,80% { transform: translateX(8px); }
}
.synti-btn-primary {
    width: 100%; padding: 11px 16px;
    border-radius: 10px; border: none;
    background: #4A80E4; color: #ffffff;
    font-size: 14px; font-weight: 700;
    cursor: pointer; transition: background 0.2s;
}
.synti-btn-primary:hover { background: #3a6fd3; }
.synti-pin-error { color: #f87171; font-size: 13px; margin-top: 10px; }

/* ── Trigger móvil ── */
#synti-mobile-trigger { display: none !important; }
</style>

{{-- ════════════════════════════════════════════════════════════
     SCRIPTS — lógica 100% preservada, sin cambios funcionales
════════════════════════════════════════════════════════════ --}}
<script>
// ── Panel open/close ──
function toggleSyntiPanel() {
    document.getElementById('synti-panel').classList.toggle('open');
}
function openSyntiPanel() {
    loadKPIs();
    document.getElementById('synti-panel').classList.add('open');
}
function closeSyntiPanel() {
    document.getElementById('synti-panel').classList.remove('open');
}

// ── PIN ──
document.addEventListener('DOMContentLoaded', function () {
    const pinInputs = document.querySelectorAll('.synti-pin-digit');
    pinInputs.forEach((input, index) => {
        input.addEventListener('input', function (e) {
            this.value = this.value.replace(/\D/g, '');
            if (this.value.length === 1 && index < pinInputs.length - 1) {
                pinInputs[index + 1].focus();
            }
        });
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                pinInputs[index - 1].focus();
            }
            if (e.key === 'Enter') verifyPin();
        });
    });
});

async function verifyPin() {
    const pin = Array.from(document.querySelectorAll('.synti-pin-digit'))
        .map(i => i.value).join('');
    if (pin.length !== 4) { showPinError(); return; }

    try {
        const res = await fetch('/tenant/{{ $tenant->id }}/verify-pin', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            },
            body: JSON.stringify({ pin })
        });
        const data = await res.json();
        if (data.success) {
            document.getElementById('synti-pin-modal').style.display = 'none';
            document.getElementById('synti-panel-content').style.display = 'block';
            loadKPIs();
            // Colorize QR modules with brand primary color
            setTimeout(function () {
                var primary = getComputedStyle(document.documentElement).getPropertyValue('--color-primary').trim() || '#1a1a1a';
                var svg = document.querySelector('#qr-floating-display svg');
                if (svg) {
                    svg.querySelectorAll('[fill]').forEach(function (el) {
                        var f = el.getAttribute('fill');
                        if (f && (f === '#000000' || f.toLowerCase() === '#000' || f.toLowerCase() === 'black')) {
                            el.setAttribute('fill', primary);
                        }
                    });
                    var wrapper = document.querySelector('.synti-qr-wrapper');
                    if (wrapper) wrapper.style.boxShadow = '0 0 0 3px ' + primary + ', 0 4px 14px rgba(0,0,0,0.3)';
                }
            }, 120);
        } else {
            showPinError();
        }
    } catch (e) { showPinError(); }
}

function showPinError() {
    const inputs = document.querySelectorAll('.synti-pin-digit');
    inputs.forEach(i => { i.classList.add('shake'); i.value = ''; setTimeout(() => i.classList.remove('shake'), 500); });
    const err = document.getElementById('synti-pin-error');
    err.style.display = 'block';
    setTimeout(() => err.style.display = 'none', 3000);
    inputs[0].focus();
}

// ── KPIs ──
async function loadKPIs() {
    try {
        const res = await fetch('/tenant/{{ $tenant->id }}/analytics/today');
        const data = await res.json();
        if (data) {
            document.getElementById('kpi-visits').textContent   = data.visitors_today  ?? 0;
			document.getElementById('kpi-whatsapp').textContent = data.whatsapp_clicks  ?? 0;
			document.getElementById('kpi-qr').textContent       = data.qr_scans         ?? 0;
			document.getElementById('kpi-products').textContent = data.products_viewed  ?? 0;
        }
    } catch (e) {
        ['kpi-visits','kpi-whatsapp','kpi-qr','kpi-products'].forEach(id => {
            document.getElementById(id).textContent = '0';
        });
    }
}

// ── Tasa dólar ──
async function refreshDollarRate() {
    const btn = document.querySelector('.synti-btn-refresh');
    if (btn) btn.style.opacity = '0.5';
    try {
        const res = await fetch('/api/dollar-rate');
        const data = await res.json();
        if (data.rate) {
            document.getElementById('dollar-rate-display').textContent =
                'Bs. ' + parseFloat(data.rate).toFixed(2);
        }
    } catch (e) { console.error('Error tasa dólar:', e); }
    finally { if (btn) btn.style.opacity = '1'; }
}

// ── Estado del negocio ──
async function toggleBusinessStatus() {
    const toggle = document.getElementById('business-status-toggle');
    const label  = document.getElementById('business-status-text');
    const isOpen = toggle.checked;
    try {
        const res = await fetch('/tenant/{{ $tenant->id }}/toggle-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            },
            body: JSON.stringify({ is_open: isOpen })
        });
        const data = await res.json();
        if (data.success) {
            label.textContent = isOpen ? 'Abierto' : 'Cerrado';
            label.className = 'synti-toggle-label ' + (isOpen ? 'open' : 'closed');
        } else {
            toggle.checked = !isOpen;
        }
    } catch (e) { toggle.checked = !isOpen; }
}

// ── WhatsApp dual ──
function switchWhatsapp(type) {
    fetch(`/tenant/{{ $tenant->id }}/toggle-whatsapp`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.querySelectorAll('.synti-wa-btn').forEach(b => b.classList.remove('active'));
            document.getElementById('wa-btn-' + data.active)?.classList.add('active');
            document.getElementById('wa-active-number').textContent = data.number || 'Sin número';
        }
    });
}

// ── QR download ──
function downloadQRFloating() {
    const svgEl = document.querySelector('#qr-floating-display svg');
    if (!svgEl) return;
    const blob = new Blob([new XMLSerializer().serializeToString(svgEl)], { type: 'image/svg+xml' });
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'qr-{{ $tenant->subdomain ?? $tenant->id }}.svg';
    a.click();
}

// ── Atajos de acceso ──
document.addEventListener('keydown', function (e) {
    if (e.altKey && e.key === 's') { e.preventDefault(); toggleSyntiPanel(); }
});

// ── Gesto móvil: long press esquina inferior derecha ──
(function () {
    let t = null;
    document.addEventListener('touchstart', function (e) {
        const touch = e.touches[0];
        if ((window.innerWidth - touch.clientX) < 80 && (window.innerHeight - touch.clientY) < 80) {
            t = setTimeout(() => {
                openSyntiPanel();
                if (navigator.vibrate) navigator.vibrate(60);
            }, 800);
        }
    }, { passive: true });
    document.addEventListener('touchend',    () => clearTimeout(t), { passive: true });
    document.addEventListener('touchmove',   () => clearTimeout(t), { passive: true });
    document.addEventListener('touchcancel', () => clearTimeout(t), { passive: true });
})();
</script>