{{-- Floating Panel Partial --}}
<div id="synti-panel" class="synti-panel">
    <!-- Header del panel -->
    <div class="synti-panel-header">
        <div>
            <span class="synti-logo">SYNTI<em>web</em></span>
            <span class="synti-version">Panel · {{ $tenant->subdomain }}</span>
        </div>
        <button onclick="closeSyntiPanel()" class="synti-close-btn">✕</button>
    </div>

    <!-- PIN Modal -->
    <div id="synti-pin-modal" class="synti-pin-modal">
        <div class="synti-pin-content">
            <h3>Acceso propietario</h3>
            <p class="synti-pin-subtitle">Ingrese su PIN de 4 dígitos</p>
            
            <div class="synti-pin-inputs">
                <input type="text" maxlength="1" class="synti-pin-digit" id="pin-1" autocomplete="off" />
                <input type="text" maxlength="1" class="synti-pin-digit" id="pin-2" autocomplete="off" />
                <input type="text" maxlength="1" class="synti-pin-digit" id="pin-3" autocomplete="off" />
                <input type="text" maxlength="1" class="synti-pin-digit" id="pin-4" autocomplete="off" />
            </div>
            
            <button onclick="verifyPin()" class="synti-btn-primary">Entrar</button>
            <div id="synti-pin-error" class="synti-pin-error" style="display:none;">PIN incorrecto</div>
        </div>
    </div>

    <!-- Panel Content (oculto hasta validar PIN) -->
    <div id="synti-panel-content" style="display:none;">
        
        <!-- Radar / KPIs -->
        <div class="synti-section">
            <h4 class="synti-section-title">📊 Radar</h4>
            <div class="synti-kpi-grid">
                <div class="synti-kpi-card">
                    <div class="synti-kpi-value" id="kpi-visits">-</div>
                    <div class="synti-kpi-label">Visitas hoy</div>
                </div>
                <div class="synti-kpi-card">
                    <div class="synti-kpi-value" id="kpi-whatsapp">-</div>
                    <div class="synti-kpi-label">Clicks WhatsApp</div>
                </div>
                <div class="synti-kpi-card">
                    <div class="synti-kpi-value" id="kpi-qr">-</div>
                    <div class="synti-kpi-label">Escaneos QR</div>
                </div>
            </div>
        </div>

        <!-- Tasa Dólar -->
        <div class="synti-section">
            <div class="synti-flex-between">
                <h4 class="synti-section-title">💵 Tasa del Dólar</h4>
                <button onclick="refreshDollarRate()" class="synti-btn-icon" title="Actualizar">
                    <svg class="synti-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>
            </div>
            <div class="synti-dollar-display">
                <span id="dollar-rate-display">Bs. {{ number_format($dollarRate, 2) }}</span>
            </div>
        </div>

        <!-- Estado del negocio -->
        <div class="synti-section">
            <h4 class="synti-section-title">🏪 Estado del Negocio</h4>
            <div class="synti-toggle-container">
                <label class="synti-toggle">
                    <input type="checkbox" id="business-status-toggle" {{ $tenant->is_open ? 'checked' : '' }} onchange="toggleBusinessStatus()">
                    <span class="synti-toggle-slider"></span>
                </label>
                <span id="business-status-text" class="synti-toggle-text">
                    {{ $tenant->is_open ? 'Abierto' : 'Cerrado' }}
                </span>
            </div>
        </div>

        <!-- QR Code Tracking -->
        <div class="synti-section">
            <h4 class="synti-section-title">📱 QR de tu Vitrina</h4>
            <div class="synti-qr-container">
                <div id="qr-floating-display" class="synti-qr-wrapper">
                    {!! $trackingQRSmall !!}
                </div>
                <p class="synti-qr-url">{{ $trackingShortlink }}</p>
                <p class="synti-qr-description">Escaneos registrados en Analytics</p>
                <button onclick="downloadQRFloating()" class="synti-btn-download">
                    <svg class="synti-icon-inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Descargar
                </button>
            </div>
        </div>

        <!-- Dashboard Link -->
        <div class="synti-section">
            <a href="/tenant/{{ $tenant->id }}/dashboard" class="synti-btn-secondary">
                Ir al Dashboard completo →
            </a>
        </div>
    </div>
</div>

<style>
/* Synti Panel Styles */
.synti-panel {
    position: fixed;
    top: 0;
    right: -320px;
    width: 320px;
    height: 100vh;
    background: #07101F;
    color: #ffffff;
    z-index: 9999;
    transition: transform 0.3s ease-in-out;
    overflow-y: auto;
    box-shadow: -4px 0 20px rgba(0, 0, 0, 0.5);
}

.synti-panel.open {
    transform: translateX(-320px);
}

.synti-panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    background: #0a1628;
}

.synti-logo {
    font-size: 18px;
    font-weight: 700;
    color: #2B6FFF;
}

.synti-logo em {
    color: #ffffff;
    font-style: normal;
}

.synti-version {
    display: block;
    font-size: 11px;
    color: rgba(255, 255, 255, 0.5);
    margin-top: 2px;
}

.synti-close-btn {
    background: none;
    border: none;
    color: #ffffff;
    font-size: 24px;
    cursor: pointer;
    padding: 0;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    transition: background 0.2s;
}

.synti-close-btn:hover {
    background: rgba(255, 255, 255, 0.1);
}

/* PIN Modal */
.synti-pin-modal {
    padding: 40px 20px;
    text-align: center;
}

.synti-pin-content h3 {
    font-size: 18px;
    margin-bottom: 8px;
    color: #ffffff;
}

.synti-pin-subtitle {
    font-size: 13px;
    color: rgba(255, 255, 255, 0.6);
    margin-bottom: 24px;
}

.synti-pin-inputs {
    display: flex;
    gap: 12px;
    justify-content: center;
    margin-bottom: 20px;
}

.synti-pin-digit {
    width: 50px;
    height: 60px;
    background: #0f1c32;
    border: 2px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    color: #ffffff;
    font-size: 24px;
    text-align: center;
    font-weight: 600;
    outline: none;
    transition: border-color 0.2s;
}

.synti-pin-digit:focus {
    border-color: #2B6FFF;
}

.synti-pin-digit.shake {
    animation: shake 0.5s;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-8px); }
    75% { transform: translateX(8px); }
}

.synti-pin-error {
    color: #ff4444;
    font-size: 13px;
    margin-top: 12px;
}

/* Panel Content */
#synti-panel-content {
    padding: 20px;
}

.synti-section {
    margin-bottom: 24px;
    padding-bottom: 24px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.synti-section:last-child {
    border-bottom: none;
}

.synti-section-title {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 12px;
    color: rgba(255, 255, 255, 0.9);
}

.synti-flex-between {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* KPIs */
.synti-kpi-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 8px;
}

.synti-kpi-card {
    background: #0f1c32;
    border-radius: 8px;
    padding: 12px 8px;
    text-align: center;
}

.synti-kpi-value {
    font-size: 20px;
    font-weight: 700;
    color: #2B6FFF;
    margin-bottom: 4px;
}

.synti-kpi-label {
    font-size: 10px;
    color: rgba(255, 255, 255, 0.6);
    line-height: 1.2;
}

/* Dollar Rate */
.synti-dollar-display {
    background: #0f1c32;
    border-radius: 8px;
    padding: 16px;
    text-align: center;
    font-size: 24px;
    font-weight: 700;
    color: #2B6FFF;
}

/* Toggle Switch */
.synti-toggle-container {
    display: flex;
    align-items: center;
    gap: 12px;
}

.synti-toggle {
    position: relative;
    display: inline-block;
    width: 52px;
    height: 28px;
}

.synti-toggle input {
    opacity: 0;
    width: 0;
    height: 0;
}

.synti-toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #374151;
    transition: 0.3s;
    border-radius: 28px;
}

.synti-toggle-slider:before {
    position: absolute;
    content: "";
    height: 20px;
    width: 20px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: 0.3s;
    border-radius: 50%;
}

.synti-toggle input:checked + .synti-toggle-slider {
    background-color: #2B6FFF;
}

.synti-toggle input:checked + .synti-toggle-slider:before {
    transform: translateX(24px);
}

.synti-toggle-text {
    font-size: 14px;
    font-weight: 600;
}

/* QR Code */
.synti-qr-container {
    text-align: center;
    padding: 16px;
    background: #0f1c32;
    border-radius: 8px;
}

.synti-qr-wrapper {
    background: white;
    padding: 8px;
    border-radius: 8px;
    display: inline-block;
    margin: 0 auto 12px;
}

.synti-qr-wrapper svg {
    display: block;
    width: 150px;
    height: 150px;
}

.synti-qr-image {
    margin: 0 auto 12px;
    display: block;
}

.synti-qr-url {
    font-size: 11px;
    color: rgba(255, 255, 255, 0.6);
    word-break: break-all;
    margin-bottom: 4px;
}

.synti-qr-description {
    font-size: 10px;
    color: rgba(255, 255, 255, 0.4);
    margin-bottom: 12px;
}

.synti-btn-download {
    background: #2B6FFF;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s;
    margin-top: 8px;
}

.synti-btn-download:hover {
    background: #1e5beb;
}

.synti-icon-inline {
    width: 16px;
    height: 16px;
}

/* Buttons */
.synti-btn-primary,
.synti-btn-secondary {
    display: block;
    width: 100%;
    padding: 12px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
}

.synti-btn-primary {
    background: #2B6FFF;
    color: #ffffff;
    border: none;
}

.synti-btn-primary:hover {
    background: #1e5beb;
}

.synti-btn-secondary {
    background: #0f1c32;
    color: #2B6FFF;
    border: 1px solid #2B6FFF;
}

.synti-btn-secondary:hover {
    background: #2B6FFF;
    color: #ffffff;
}

.synti-btn-icon {
    background: none;
    border: none;
    color: rgba(255, 255, 255, 0.6);
    cursor: pointer;
    padding: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    transition: all 0.2s;
}

.synti-btn-icon:hover {
    color: #2B6FFF;
    background: rgba(43, 111, 255, 0.1);
}

.synti-icon {
    width: 20px;
    height: 20px;
}

/* Scrollbar */
.synti-panel::-webkit-scrollbar {
    width: 6px;
}

.synti-panel::-webkit-scrollbar-track {
    background: #07101F;
}

.synti-panel::-webkit-scrollbar-thumb {
    background: #2B6FFF;
    border-radius: 3px;
}
</style>

<script>
// Toggle Panel
function toggleSyntiPanel() {
    const panel = document.getElementById('synti-panel');
    panel.classList.toggle('open');
}

function closeSyntiPanel() {
    const panel = document.getElementById('synti-panel');
    panel.classList.remove('open');
}

// PIN Management
document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus next input
    const pinInputs = document.querySelectorAll('.synti-pin-digit');
    pinInputs.forEach((input, index) => {
        input.addEventListener('input', function(e) {
            if (e.target.value.length === 1 && index < pinInputs.length - 1) {
                pinInputs[index + 1].focus();
            }
        });
        
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                pinInputs[index - 1].focus();
            }
            if (e.key === 'Enter') {
                verifyPin();
            }
        });
    });
});

async function verifyPin() {
    const pin = Array.from(document.querySelectorAll('.synti-pin-digit'))
        .map(input => input.value)
        .join('');
    
    if (pin.length !== 4) {
        showPinError();
        return;
    }
    
    try {
        const response = await fetch('/tenant/{{ $tenant->id }}/verify-pin', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            },
            body: JSON.stringify({ pin })
        });
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('synti-pin-modal').style.display = 'none';
            document.getElementById('synti-panel-content').style.display = 'block';
            loadKPIs();
        } else {
            showPinError();
        }
    } catch (error) {
        console.error('Error verifying PIN:', error);
        showPinError();
    }
}

function showPinError() {
    const inputs = document.querySelectorAll('.synti-pin-digit');
    const errorMsg = document.getElementById('synti-pin-error');
    
    inputs.forEach(input => {
        input.classList.add('shake');
        input.value = '';
        setTimeout(() => input.classList.remove('shake'), 500);
    });
    
    errorMsg.style.display = 'block';
    setTimeout(() => errorMsg.style.display = 'none', 3000);
    
    inputs[0].focus();
}

// Long press on status badge
let longPressTimer;
const statusBadge = document.querySelector('#open-status-badge');
if (statusBadge) {
    statusBadge.addEventListener('touchstart', function(e) {
        longPressTimer = setTimeout(() => {
            toggleSyntiPanel();
        }, 5000);
    });
    
    statusBadge.addEventListener('touchend', function(e) {
        clearTimeout(longPressTimer);
    });
    
    statusBadge.addEventListener('touchmove', function(e) {
        clearTimeout(longPressTimer);
    });
}

// Keyboard shortcut Alt + S
document.addEventListener('keydown', function(e) {
    if (e.altKey && e.key === 's') {
        e.preventDefault();
        toggleSyntiPanel();
    }
});

// Load KPIs
async function loadKPIs() {
    // Placeholder - implement with real API
    document.getElementById('kpi-visits').textContent = '0';
    document.getElementById('kpi-whatsapp').textContent = '0';
    document.getElementById('kpi-qr').textContent = '0';
}

// Refresh Dollar Rate
async function refreshDollarRate() {
    try {
        const response = await fetch('/api/dollar-rate');
        const data = await response.json();
        
        if (data.rate) {
            document.getElementById('dollar-rate-display').textContent = 
                'Bs. ' + parseFloat(data.rate).toFixed(2);
        }
    } catch (error) {
        console.error('Error refreshing dollar rate:', error);
    }
}

// Toggle Business Status
async function toggleBusinessStatus() {
    const toggle = document.getElementById('business-status-toggle');
    const statusText = document.getElementById('business-status-text');
    const isOpen = toggle.checked;
    
    try {
        const response = await fetch('/tenant/{{ $tenant->id }}/toggle-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            },
            body: JSON.stringify({ is_open: isOpen })
        });
        
        const data = await response.json();
        
        if (data.success) {
            statusText.textContent = isOpen ? 'Abierto' : 'Cerrado';
        } else {
            toggle.checked = !isOpen;
        }
    } catch (error) {
        console.error('Error toggling status:', error);
        toggle.checked = !isOpen;
    }
}

// Download QR from Floating Panel
function downloadQRFloating() {
    const svgElement = document.querySelector('#qr-floating-display svg');
    if (!svgElement) {
        alert('Error: No se encontró el código QR');
        return;
    }

    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    const svgData = new XMLSerializer().serializeToString(svgElement);
    const img = new Image();
    
    img.onload = function() {
        canvas.width = 150;
        canvas.height = 150;
        ctx.fillStyle = 'white';
        ctx.fillRect(0, 0, 150, 150);
        ctx.drawImage(img, 0, 0, 150, 150);
        
        canvas.toBlob(function(blob) {
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = '{{ $tenant->subdomain }}_qr.png';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }, 'image/png');
    };
    
    img.src = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(svgData)));
}
</script>
