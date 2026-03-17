{{-- ═══ SYNTiA Widget — componente global ═══════════════════════════ --}}
<div id="syntia-widget" style="position:fixed;bottom:24px;right:24px;z-index:9999;">

    {{-- Chat box --}}
    <div id="syntia-box" style="display:none;width:340px;margin-bottom:12px;">
        <div style="background:var(--color-background-primary,#fff);border:0.5px solid rgba(0,0,0,0.08);border-radius:16px;overflow:hidden;box-shadow:0 8px 32px rgba(0,0,0,0.10);">

            {{-- Header --}}
            <div style="padding:14px 16px;display:flex;align-items:center;justify-content:space-between;border-bottom:0.5px solid rgba(0,0,0,0.06);">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:38px;height:38px;background:#2B6FFF;border-radius:11px;display:flex;align-items:center;justify-content:center;position:relative;flex-shrink:0;">
                        <svg width="20" height="20" viewBox="0 0 80 80" fill="none">
                            <path d="M40 12 L50 40 L40 68 L30 40 Z" fill="rgba(255,255,255,0.95)" style="animation:sw-spin 7s linear infinite;transform-origin:40px 40px;"/>
                            <path d="M40 20 L54 40 L40 60 L26 40 Z" fill="none" stroke="rgba(255,255,255,0.25)" stroke-width="1.2" style="animation:sw-spin 4s linear infinite reverse;transform-origin:40px 40px;"/>
                            <g style="animation:sw-spark 2s ease-in-out infinite;transform-origin:60px 18px;">
                                <path d="M60 13 L61.8 18 L60 23 L58.2 18 Z" fill="white"/>
                                <path d="M55 18 L60 16.2 L65 18 L60 19.8 Z" fill="white"/>
                            </g>
                            <g style="animation:sw-spark 2s ease-in-out infinite .6s;transform-origin:20px 62px;">
                                <path d="M20 58 L21.3 62 L20 66 L18.7 62 Z" fill="rgba(255,255,255,0.7)"/>
                                <path d="M16 62 L20 60.7 L24 62 L20 63.3 Z" fill="rgba(255,255,255,0.7)"/>
                            </g>
                            <circle cx="64" cy="44" r="2.5" fill="rgba(255,255,255,0.5)" style="animation:sw-spark 3s ease-in-out infinite 1s;"/>
                        </svg>
                        <div style="position:absolute;bottom:-2px;right:-2px;width:10px;height:10px;background:#00C47A;border-radius:50%;border:2px solid #fff;"></div>
                    </div>
                    <div>
                        <div style="font-size:14px;font-weight:600;color:#0f172a;">SYNTiA</div>
                        <div style="font-size:11px;color:#64748b;">Asistente SYNTIweb</div>
                    </div>
                </div>
                <button onclick="toggleSyntia()" style="width:28px;height:28px;border-radius:8px;background:transparent;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#94a3b8;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none"><path d="M3 3l10 10M13 3L3 13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                </button>
            </div>

            {{-- Messages --}}
            <div id="syntia-messages" style="height:260px;overflow-y:auto;padding:14px;display:flex;flex-direction:column;gap:10px;background:#f8fafc;">
                <div style="display:flex;gap:8px;align-items:flex-start;">
                    <div style="width:26px;height:26px;background:#2B6FFF;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="13" height="13" viewBox="0 0 80 80" fill="none"><path d="M40 12 L50 40 L40 68 L30 40 Z" fill="rgba(255,255,255,0.95)"/></svg>
                    </div>
                    <div style="background:#fff;border:0.5px solid rgba(0,0,0,0.06);border-radius:4px 12px 12px 12px;padding:10px 12px;font-size:13px;color:#334155;line-height:1.6;max-width:260px;">
                        Hola, soy SYNTiA. ¿Tienes dudas sobre SYNTIweb?
                    </div>
                </div>
            </div>

            {{-- Input --}}
            <div style="padding:10px 12px;border-top:0.5px solid rgba(0,0,0,0.06);display:flex;gap:8px;align-items:center;">
                <input id="syntia-input" type="text" placeholder="Escribe tu pregunta..."
                    style="flex:1;border:0.5px solid #e2e8f0;border-radius:9px;padding:9px 12px;font-size:13px;outline:none;background:#f8fafc;color:#334155;transition:border-color .15s;"
                    onfocus="this.style.borderColor='#2B6FFF'" onblur="this.style.borderColor='#e2e8f0'"
                    onkeydown="if(event.key==='Enter')sendSyntia()">
                <button onclick="sendSyntia()"
                    style="width:34px;height:34px;background:#2B6FFF;color:#fff;border:none;border-radius:9px;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none"><path d="M2 8l12-6-5 6 5 6z" fill="#fff"/></svg>
                </button>
            </div>
            <div style="padding:4px 12px 8px;text-align:center;font-size:10px;color:#cbd5e1;">Alt+H para abrir/cerrar</div>
        </div>
    </div>

    {{-- FAB --}}
    <button onclick="toggleSyntia()"
        style="width:52px;height:52px;background:#2B6FFF;border:none;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 16px rgba(43,111,255,0.4);position:relative;transition:transform .2s;"
        onmouseover="this.style.transform='scale(1.06)'" onmouseout="this.style.transform='scale(1)'">
        <svg width="24" height="24" viewBox="0 0 80 80" fill="none">
            <path d="M40 12 L50 40 L40 68 L30 40 Z" fill="rgba(255,255,255,0.95)" style="animation:sw-spin 7s linear infinite;transform-origin:40px 40px;"/>
            <g style="animation:sw-spark 2s ease-in-out infinite;transform-origin:60px 18px;">
                <path d="M60 13 L61.8 18 L60 23 L58.2 18 Z" fill="white"/>
                <path d="M55 18 L60 16.2 L65 18 L60 19.8 Z" fill="white"/>
            </g>
        </svg>
        <div style="position:absolute;top:2px;right:2px;width:12px;height:12px;background:#00C47A;border-radius:50%;border:2.5px solid #2B6FFF;"></div>
    </button>
</div>

<style>
@keyframes sw-spin{from{transform:rotate(0deg);}to{transform:rotate(360deg);}}
@keyframes sw-spark{0%,100%{opacity:.25;transform:scale(0.75);}50%{opacity:1;transform:scale(1.15);}}
</style>

<script>
function toggleSyntia(){
    const box = document.getElementById('syntia-box');
    box.style.display = box.style.display === 'none' ? 'block' : 'none';
}
document.addEventListener('keydown', e => {
    if(e.altKey && e.key.toLowerCase() === 'h'){ e.preventDefault(); toggleSyntia(); }
});
async function sendSyntia(){
    const input = document.getElementById('syntia-input');
    const msgs = document.getElementById('syntia-messages');
    const q = input.value.trim();
    if(!q) return;
    msgs.innerHTML += `<div style="display:flex;justify-content:flex-end;"><div style="background:#2B6FFF;color:#fff;border-radius:12px 4px 12px 12px;padding:10px 12px;font-size:13px;line-height:1.6;max-width:220px;">${q}</div></div>`;
    msgs.innerHTML += `<div style="display:flex;gap:8px;align-items:flex-start;"><div style="width:26px;height:26px;background:#2B6FFF;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><svg width="13" height="13" viewBox="0 0 80 80" fill="none"><path d="M40 12 L50 40 L40 68 L30 40 Z" fill="rgba(255,255,255,0.95)"/></svg></div><div id="typing" style="background:#fff;border:0.5px solid rgba(0,0,0,0.06);border-radius:4px 12px 12px 12px;padding:10px 12px;font-size:13px;color:#94a3b8;line-height:1.6;">SYNTiA está escribiendo...</div></div>`;
    input.value = '';
    msgs.scrollTop = msgs.scrollHeight;
    try {
        const res = await fetch('/api/synti/public-ask', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
            body: JSON.stringify({question: q})
        });
        const data = await res.json();
        document.getElementById('typing').remove();
        msgs.innerHTML += `<div style="display:flex;gap:8px;align-items:flex-start;"><div style="width:26px;height:26px;background:#2B6FFF;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><svg width="13" height="13" viewBox="0 0 80 80" fill="none"><path d="M40 12 L50 40 L40 68 L30 40 Z" fill="rgba(255,255,255,0.95)"/></svg></div><div style="background:#fff;border:0.5px solid rgba(0,0,0,0.06);border-radius:4px 12px 12px 12px;padding:10px 12px;font-size:13px;color:#334155;line-height:1.6;max-width:260px;">${data.answer}</div></div>`;
    } catch(e) {
        document.getElementById('typing').remove();
        msgs.innerHTML += `<div style="background:#fee2e2;border-radius:12px;padding:10px 12px;font-size:13px;color:#991b1b;">Error al conectar con SYNTiA.</div>`;
    }
    msgs.scrollTop = msgs.scrollHeight;
}
</script>
