<template>
  <div style="position:fixed;bottom:28px;right:28px;z-index:9999;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;">

    <!-- Panel -->
    <div v-if="open" style="width:340px;background:#ffffff;border-radius:12px;border:1px solid #e2e8f0;box-shadow:0 4px 24px rgba(0,0,0,0.08);margin-bottom:12px;overflow:hidden;">

      <!-- Header -->
      <div style="padding:14px 16px;border-bottom:1px solid #f1f5f9;display:flex;justify-content:space-between;align-items:center;">
        <div style="display:flex;align-items:center;gap:8px;">
          <svg width="16" height="16" viewBox="0 0 16 16" fill="#4A80E4" xmlns="http://www.w3.org/2000/svg">
            <path d="M7.657 6.247c.11-.33.576-.33.686 0l.645 1.937a2.89 2.89 0 0 0 1.829 1.828l1.936.645c.33.11.33.576 0 .686l-1.937.645a2.89 2.89 0 0 0-1.828 1.829l-.645 1.936a.361.361 0 0 1-.686 0l-.645-1.937a2.89 2.89 0 0 0-1.828-1.828l-1.937-.645a.361.361 0 0 1 0-.686l1.937-.645a2.89 2.89 0 0 0 1.828-1.828zM3.794 1.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387A1.73 1.73 0 0 0 4.593 5.69l-.387 1.162a.217.217 0 0 1-.412 0L3.407 5.69A1.73 1.73 0 0 0 2.31 4.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387A1.73 1.73 0 0 0 3.407 2.31zM10.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.16 1.16 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.16 1.16 0 0 0-.732-.732L9.1 2.137a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732z"/>
          </svg>
          <span style="font-weight:600;font-size:14px;color:#0f172a;">SYNT<em style="font-style:italic;">iA</em></span>
          <span v-if="producto" style="background:#eff6ff;color:#4A80E4;font-size:11px;padding:2px 8px;border-radius:6px;font-weight:500;">{{ productoLabel }}</span>
        </div>
        <div style="display:flex;align-items:center;gap:12px;">
          <span v-if="producto" @click="resetProducto" style="font-size:11px;color:#94a3b8;cursor:pointer;">← cambiar</span>
          <span @click="open=false" style="color:#94a3b8;cursor:pointer;font-size:16px;line-height:1;">✕</span>
        </div>
      </div>

      <!-- Selector de producto -->
      <div v-if="!producto" style="padding:16px;display:flex;flex-direction:column;gap:8px;">
        <p style="font-size:12px;color:#64748b;margin:0 0 4px;">¿Sobre qué necesitas ayuda?</p>
        <button v-for="p in productos" :key="p.value" @click="seleccionarProducto(p.value)"
          style="background:#fafafa;border:1px solid #e2e8f0;border-radius:8px;padding:10px 12px;cursor:pointer;text-align:left;display:flex;align-items:center;gap:10px;transition:border-color .2s;"
          onmouseover="this.style.borderColor='#4A80E4'" onmouseout="this.style.borderColor='#e2e8f0'">
          <span style="font-size:16px;">{{ p.icon }}</span>
          <div>
            <div style="font-size:13px;font-weight:600;color:#0f172a;">{{ p.label }}</div>
            <div style="font-size:11px;color:#94a3b8;margin-top:1px;">{{ p.desc }}</div>
          </div>
        </button>
      </div>

      <!-- Chat -->
      <div v-if="producto">
        <div ref="msgs" style="height:240px;overflow-y:auto;padding:14px;display:flex;flex-direction:column;gap:8px;">
          <div v-for="(m,i) in messages" :key="i"
            :style="m.role==='user'
              ? 'background:#4A80E4;color:#fff;border-radius:8px 8px 2px 8px;padding:10px 12px;font-size:13px;align-self:flex-end;max-width:85%;line-height:1.5;'
              : 'background:#f8fafc;border:1px solid #f1f5f9;border-radius:8px 8px 8px 2px;padding:10px 12px;font-size:13px;color:#334155;max-width:85%;line-height:1.5;'"
            v-html="m.text">
          </div>
          <div v-if="loading" style="display:flex;gap:4px;align-items:center;padding:4px 0;">
            <span style="width:6px;height:6px;background:#cbd5e1;border-radius:50%;animation:pulse 1s infinite;"></span>
            <span style="width:6px;height:6px;background:#cbd5e1;border-radius:50%;animation:pulse 1s infinite .2s;"></span>
            <span style="width:6px;height:6px;background:#cbd5e1;border-radius:50%;animation:pulse 1s infinite .4s;"></span>
          </div>
        </div>
        <div style="padding:10px;border-top:1px solid #f1f5f9;display:flex;gap:8px;">
          <input v-model="question" @keydown.enter="send" placeholder="Escribe tu pregunta..."
            style="flex:1;border:1px solid #e2e8f0;border-radius:8px;padding:8px 10px;font-size:13px;outline:none;color:#0f172a;background:#fff;"
            onfocus="this.style.borderColor='#4A80E4'" onblur="this.style.borderColor='#e2e8f0'">
          <button @click="send"
            style="background:#4A80E4;color:#fff;border:none;border-radius:8px;width:36px;height:36px;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <line x1="22" y1="2" x2="11" y2="13"></line>
              <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
            </svg>
          </button>
        </div>
      </div>

      <!-- Footer -->
      <div style="padding:8px 16px;font-size:11px;color:#cbd5e1;text-align:center;border-top:1px solid #f8fafc;">
        Las respuestas pueden contener errores · <strong style="color:#4A80E4;">SYNTiCore</strong>
      </div>
    </div>

    <!-- Botón flotante -->
    <button @click="open=true" v-if="!open"
      style="background:#4A80E4;color:#fff;border:none;border-radius:10px;padding:10px 16px;cursor:pointer;box-shadow:0 2px 12px rgba(74,128,228,.3);display:flex;align-items:center;gap:8px;font-size:13px;font-weight:600;">
      <svg width="16" height="16" viewBox="0 0 16 16" fill="white" xmlns="http://www.w3.org/2000/svg">
        <path d="M7.657 6.247c.11-.33.576-.33.686 0l.645 1.937a2.89 2.89 0 0 0 1.829 1.828l1.936.645c.33.11.33.576 0 .686l-1.937.645a2.89 2.89 0 0 0-1.828 1.829l-.645 1.936a.361.361 0 0 1-.686 0l-.645-1.937a2.89 2.89 0 0 0-1.828-1.828l-1.937-.645a.361.361 0 0 1 0-.686l1.937-.645a2.89 2.89 0 0 0 1.828-1.828zM3.794 1.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387A1.73 1.73 0 0 0 4.593 5.69l-.387 1.162a.217.217 0 0 1-.412 0L3.407 5.69A1.73 1.73 0 0 0 2.31 4.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387A1.73 1.73 0 0 0 3.407 2.31zM10.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.16 1.16 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.16 1.16 0 0 0-.732-.732L9.1 2.137a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732z"/>
      </svg>
      <span style="letter-spacing:0;">SYNT<i>iA</i></span>
    </button>

  </div>
</template>

<script setup>
import { ref, nextTick } from 'vue'

const open          = ref(false)
const question      = ref('')
const loading       = ref(false)
const producto      = ref(null)
const productoLabel = ref('')
const messages      = ref([])
const msgs          = ref(null)

const productos = [
  { value: 'studio', label: 'SYNTIstudio', icon: '🌐', desc: 'Página web para tu negocio' },
  { value: 'food',   label: 'SYNTIfood',   icon: '🍔', desc: 'Menú digital para restaurantes' },
  { value: 'cat',    label: 'SYNTIcat',    icon: '🛍️', desc: 'Catálogo con carrito WhatsApp' },
  { value: 'shared', label: 'General',     icon: '💡', desc: 'Planes, cuenta y preguntas generales' },
]

function seleccionarProducto(value) {
  producto.value      = value
  productoLabel.value = productos.find(p => p.value === value)?.label
  messages.value      = [{ role: 'bot', text: '👋 Hola, soy SYNTiA. ¿En qué te ayudo con <strong>' + productoLabel.value + '</strong>?' }]
}

function resetProducto() {
  producto.value      = null
  productoLabel.value = ''
  messages.value      = []
}

async function send() {
  const q = question.value.trim()
  if (!q || loading.value) return
  messages.value.push({ role: 'user', text: q })
  question.value = ''
  loading.value  = true
  await nextTick()
  if (msgs.value) msgs.value.scrollTop = msgs.value.scrollHeight

  try {
    const res = await fetch('http://localhost:8000/api/synti/public-ask', {
      method:  'POST',
      headers: { 'Content-Type': 'application/json' },
      body:    JSON.stringify({ question: q, product: producto.value })
    })
    const data = await res.json()
    messages.value.push({ role: 'bot', text: data.answer })
  } catch {
    messages.value.push({ role: 'bot', text: 'Error al conectar con SYNTiA. Intenta de nuevo.' })
  }

  loading.value = false
  await nextTick()
  if (msgs.value) msgs.value.scrollTop = msgs.value.scrollHeight
}
</script>

<style>
@keyframes pulse {
  0%, 100% { opacity: 0.3; transform: scale(0.8); }
  50%       { opacity: 1;   transform: scale(1);   }
}
</style>