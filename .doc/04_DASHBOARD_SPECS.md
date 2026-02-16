# 🎛️ ESPECIFICACIONES DEL DASHBOARD - SYNTIWEB

**Componente:** Panel flotante de edición  
**Target:** Usuario final (dueño del negocio)  
**Filosofía:** Zero-code, edición visual instantánea

---

## 🎨 DISEÑO VISUAL

### Estructura:
```
┌─────────────────────────────────────────────┐
│  SYNTIWEB DASHBOARD          🔐 PIN: ****  │ ← Header
├─────────────────────────────────────────────┤
│ [Info] [Productos] [Servicios] [Diseño]    │ ← Tabs
│        [Analytics] [Config]                  │
├─────────────────────────────────────────────┤
│                                             │
│          [CONTENIDO DEL TAB ACTIVO]         │ ← Body
│                                             │
│                                             │
├─────────────────────────────────────────────┤
│  [Guardar Cambios]  [Cancelar]  [Cerrar]   │ ← Footer
└─────────────────────────────────────────────┘
```

### Dimensiones:
- **Desktop:** 400px ancho, 80vh alto
- **Tablet:** 350px ancho, 70vh alto
- **Móvil:** Fullscreen (con header sticky)

### Posicionamiento:
- **Desktop/Tablet:** Side drawer desde la derecha
- **Móvil:** Modal fullscreen desde abajo

### Colores:
```css
--dashboard-bg: #FFFFFF;
--dashboard-border: #E0E0E0;
--dashboard-header: #1E3A8A; /* Azul oscuro */
--dashboard-accent: #3B82F6; /* Azul brillante */
--dashboard-text: #1F2937;
--dashboard-muted: #6B7280;
```

---

## 🔓 SISTEMA DE ACTIVACIÓN

### Triggers:

#### Desktop:
```javascript
document.addEventListener('keydown', (e) => {
    if (e.altKey && e.key === 's') {
        e.preventDefault();
        showDashboard();
    }
});
```

#### Móvil:
```javascript
let longPressTimer;
const synt

iwebLogo = document.querySelector('.syntiweb-logo');

syntiwebLogo.addEventListener('touchstart', (e) => {
    longPressTimer = setTimeout(() => {
        showDashboard();
        navigator.vibrate(50); // Feedback háptico
    }, 3000); // 3 segundos
});

syntiwebLogo.addEventListener('touchend', () => {
    clearTimeout(longPressTimer);
});
```

### Modal de Autenticación:
```html
<div id="pin-modal" class="modal">
  <h3>🔐 Ingrese su PIN</h3>
  <input type="password" maxlength="4" pattern="[0-9]*" 
         inputmode="numeric" id="pin-input">
  <button onclick="verifyPin()">Ingresar</button>
  <a href="#" onclick="recoverPin()">¿Olvidaste tu PIN?</a>
</div>
```

**Lógica de verificación:**
```javascript
async function verifyPin() {
    const pin = document.getElementById('pin-input').value;
    
    const response = await fetch('/dashboard/verify-pin', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ pin })
    });
    
    if (response.ok) {
        sessionStorage.setItem('dashboard_access', 'true');
        showDashboardContent();
    } else {
        showError('PIN incorrecto');
    }
}
```

---

## 📑 TABS DETALLADOS

### 🏠 TAB 1: INFO

**Campos:**

| Campo | Tipo | Obligatorio | Validación |
|-------|------|-------------|------------|
| Nombre del Negocio | Text | Sí | Max 255 chars |
| Slogan | Textarea | No | Max 500 chars |
| Descripción | Textarea | No | Max 1000 chars |
| Teléfono | Tel | No | Formato internacional |
| Email | Email | No | Valid email |
| Dirección | Textarea | No | Max 500 chars |
| Ciudad | Select | Sí | Lista predefinida |
| Segmento | Select | Sí | Ver lista abajo |
| Status | Toggle | - | Abierto/Cerrado |

**Horarios (Expandible):**
```
┌─ HORARIOS DE ATENCIÓN ────────────────┐
│ Lunes     [09:00] a [18:00]  ☑ Activo │
│ Martes    [09:00] a [18:00]  ☑ Activo │
│ Miércoles [09:00] a [18:00]  ☑ Activo │
│ Jueves    [09:00] a [18:00]  ☑ Activo │
│ Viernes   [09:00] a [18:00]  ☑ Activo │
│ Sábado    [10:00] a [16:00]  ☑ Activo │
│ Domingo   [  -  ] a [  -  ]  ☐ Cerrado│
└───────────────────────────────────────┘
```

**Segmentos disponibles:**
1. Restaurante / Comida
2. Barbería / Belleza
3. Servicios Técnicos (Plomería, Electricidad, etc.)
4. Retail / Tienda
5. Salud / Fitness
6. Educación
7. Automotriz
8. Hogar / Decoración
9. Entretenimiento
10. Otros

---

### 📦 TAB 2: PRODUCTOS

**Vista lista:**
```
┌─ PRODUCTOS (6/6 usados) ──────────────────┐
│                                           │
│ [+ Nuevo Producto]         🔍 Buscar...   │
│                                           │
│ ┌─────────────────────────────────────┐  │
│ │ 🍔 Hamburguesa Clásica         $5.00 │  │
│ │ ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │  │
│ │ [✏️ Editar] [🗑️ Eliminar] [👁️ Ver] │  │
│ └─────────────────────────────────────┘  │
│                                           │
│ ┌─────────────────────────────────────┐  │
│ │ 🍟 Papas Fritas                $2.50 │  │
│ │ ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │  │
│ │ [✏️ Editar] [🗑️ Eliminar] [👁️ Ver] │  │
│ └─────────────────────────────────────┘  │
│                                           │
└───────────────────────────────────────────┘
```

**Modal de edición:**
```html
<form id="product-form">
  <input type="text" name="name" placeholder="Nombre del producto" required>
  <textarea name="description" placeholder="Descripción (opcional)"></textarea>
  
  <div class="price-group">
    <input type="number" name="price_usd" step="0.01" placeholder="Precio USD" required>
    <span class="badge">Bs. calculado automáticamente</span>
  </div>
  
  <div class="image-upload">
    <label for="image">Imagen del producto</label>
    <input type="file" id="image" accept="image/*" max="2MB">
    <div class="preview"></div>
  </div>
  
  <select name="badge">
    <option value="">Sin badge</option>
    <option value="hot">🔥 Hot</option>
    <option value="new">✨ Nuevo</option>
    <option value="promo">💰 Promo</option>
  </select>
  
  <label>
    <input type="checkbox" name="is_featured">
    Destacar este producto
  </label>
  
  <button type="submit">Guardar Producto</button>
</form>
```

**Límites por plan:**
- OPORTUNIDAD: 6 productos max
- CRECIMIENTO: 18 productos max
- VISIÓN: 40 productos max

**Validaciones:**
- Nombre: Obligatorio, max 255 chars
- Descripción: Opcional, max 1000 chars
- Precio: Obligatorio, > 0
- Imagen: Max 2MB, formatos: jpg, png, webp

---

### ⚙️ TAB 3: SERVICIOS

**Vista lista:**
```
┌─ SERVICIOS (3/3 usados) ──────────────────┐
│                                           │
│ [+ Nuevo Servicio]         🔍 Buscar...   │
│                                           │
│ ┌─────────────────────────────────────┐  │
│ │ ✂️ Corte Clásico                     │  │
│ │ ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━  │  │
│ │ [✏️ Editar] [🗑️ Eliminar] [↕️ Orden] │  │
│ └─────────────────────────────────────┘  │
│                                           │
└───────────────────────────────────────────┘
```

**Modal de edición (varía por plan):**

#### Plan OPORTUNIDAD:
```html
<form id="service-form">
  <input type="text" name="name" placeholder="Nombre del servicio" required>
  <textarea name="description" placeholder="Descripción"></textarea>
  
  <select name="icon">
    <option value="scissors">✂️ Tijeras</option>
    <option value="wrench">🔧 Llave</option>
    <option value="burger">🍔 Comida</option>
    <option value="car">🚗 Auto</option>
    <!-- 20+ iconos disponibles -->
  </select>
  
  <button type="submit">Guardar</button>
</form>
```

#### Plan CRECIMIENTO:
```html
<!-- Opción adicional: -->
<div class="visual-choice">
  <label>
    <input type="radio" name="visual_type" value="icon" checked>
    Usar icono
  </label>
  <label>
    <input type="radio" name="visual_type" value="image">
    Subir imagen
  </label>
</div>

<div id="image-upload" style="display:none">
  <input type="file" accept="image/*" max="2MB">
</div>
```

#### Plan VISIÓN:
```html
<!-- Funcionalidad adicional: -->
<div class="overlay-config">
  <label>Texto sobre imagen (opcional)</label>
  <input type="text" name="overlay_text" maxlength="100" 
         placeholder="Ej: Desde $25">
  
  <select name="overlay_position">
    <option value="bottom-left">Abajo izquierda</option>
    <option value="bottom-right">Abajo derecha</option>
    <option value="center">Centro</option>
  </select>
</div>
```

---

### 🎨 TAB 4: DISEÑO

**Sección: Identidad Visual**
```
┌─ IDENTIDAD VISUAL ────────────────────────┐
│                                           │
│ Logo del Negocio:                         │
│ ┌─────────────┐                           │
│ │   [LOGO]    │ [Cambiar Logo]            │
│ └─────────────┘                           │
│                                           │
│ Imagen Hero (Banner Principal):          │
│ ┌───────────────────────────────────────┐ │
│ │         [HERO IMAGE]                  │ │
│ │                                       │ │
│ └───────────────────────────────────────┘ │
│ [Cambiar Hero]                            │
│                                           │
└───────────────────────────────────────────┘
```

**Sección: Paleta de Colores**
```
┌─ PALETA DE COLORES (5 disponibles) ───────┐
│                                           │
│ ┌──────┐ ┌──────┐ ┌──────┐ ┌──────┐     │
│ │ 🔵📄 │ │ 🟠📄 │ │ 🟢📄 │ │ ⚫📄 │ ... │
│ └──────┘ └──────┘ └──────┘ └──────┘     │
│ Clásico  Calidez  Natural  Elegante       │
│                                           │
│ [VISTA PREVIA EN VIVO]                    │
│                                           │
└───────────────────────────────────────────┘
```

**Sección: Efectos (Solo Plan VISIÓN)**
```
┌─ EFECTOS VISUALES ────────────────────────┐
│                                           │
│ ☑️ Parallax en Hero                       │
│ ☑️ Fade-in al hacer scroll                │
│ ☐ Animaciones de hover                    │
│ ☐ Gradientes en botones                   │
│                                           │
└───────────────────────────────────────────┘
```

---

### 📊 TAB 5: ANALYTICS

#### Plan OPORTUNIDAD:
```
┌─ PULSO DE VENTA ──────────────────────────┐
│                                           │
│  Clicks este mes                          │
│  ┌───────────────────────────────────┐   │
│  │           127                     │   │
│  └───────────────────────────────────┘   │
│                                           │
│  Última actualización: Hace 1 hora        │
│                                           │
└───────────────────────────────────────────┘
```

#### Plan CRECIMIENTO:
```
┌─ PULSO DE VENTA ──────────────────────────┐
│                                           │
│  📅 Visitas hoy: 23                       │
│  📆 Visitas este mes: 487                 │
│  💬 Clicks WhatsApp: 34                   │
│                                           │
│  [Ver histórico →]                        │
│                                           │
└───────────────────────────────────────────┘
```

#### Plan VISIÓN:
```
┌─ PULSO DE VENTA ──────────────────────────┐
│                                           │
│  📊 Visitas (últimos 7 días)              │
│  ┌───────────────────────────────────┐   │
│  │     ╭─╮                           │   │
│  │   ╭─╯ ╰╮  ╭╮                      │   │
│  │ ╭─╯    ╰──╯╰─╮                    │   │
│  └───────────────────────────────────┘   │
│                                           │
│  🔥 Top 3 Productos:                      │
│  1️⃣ Hamburguesa Clásica (45 clicks)      │
│  2️⃣ Papas Fritas (32 clicks)             │
│  3️⃣ Refresco (28 clicks)                 │
│                                           │
│  ⏰ Horario pico: 12:00 - 14:00           │
│                                           │
└───────────────────────────────────────────┘
```

---

### ⚙️ TAB 6: CONFIG

**Sección: Configuración de Precios**
```
┌─ MOSTRAR PRECIOS EN: ─────────────────────┐
│                                           │
│ ⚪ Solo Dólares ($)                       │
│ ⚪ Solo Bolívares (Bs.)                   │
│ 🔵 Ambos ($ y Bs.)                        │
│                                           │
│ 💵 Tasa actual: Bs. 36.50 por $1          │
│ (Actualizado hace 15 min)                 │
│                                           │
└───────────────────────────────────────────┘
```

**Sección: Redes Sociales**
```
┌─ REDES SOCIALES ──────────────────────────┐
│                                           │
│ Instagram:  [@joseburguer]      ☑️        │
│ Facebook:   [JoseBurguerOficial] ☐        │
│ TikTok:     [@joseburguer]       ☐        │
│ LinkedIn:   [                  ] ☐        │
│                                           │
│ * Plan OPORTUNIDAD: Solo 1 activa         │
└───────────────────────────────────────────┘
```

**Sección: Medios de Pago (Plan CRECIMIENTO+)**
```
┌─ MEDIOS DE PAGO ACEPTADOS ────────────────┐
│                                           │
│ ☑️ Zelle                                  │
│ ☑️ Cashea                                 │
│ ☑️ Pago Móvil                             │
│ ☐ BinancePay                              │
│ ☑️ Punto de Venta                         │
│ ☑️ Efectivo                               │
│                                           │
└───────────────────────────────────────────┘
```

**Sección: Seguridad**
```
┌─ SEGURIDAD ───────────────────────────────┐
│                                           │
│ Cambiar PIN:                              │
│ [PIN actual] [Nuevo PIN] [Confirmar]      │
│                                           │
│ [Cambiar PIN]                             │
│                                           │
└───────────────────────────────────────────┘
```

---

## 💾 SISTEMA DE GUARDADO

### Estrategia:
1. **Auto-save:** Cada cambio se guarda automáticamente (debounce 2s)
2. **Botón manual:** Disponible para confirmar cambios críticos
3. **Feedback visual:** Toast notification en cada guardado

### Ejemplo de petición AJAX:
```javascript
async function saveChanges(tab, data) {
    showLoader();
    
    const response = await fetch(`/dashboard/${tab}/save`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(data)
    });
    
    hideLoader();
    
    if (response.ok) {
        showToast('✅ Cambios guardados', 'success');
        reloadSection(tab); // Recarga solo la sección afectada
    } else {
        showToast('❌ Error al guardar', 'error');
    }
}
```

---

## 🔒 SEGURIDAD

### Validaciones Backend:
```php
// TenantDashboardController.php
public function updateInfo(Request $request)
{
    $tenant = app('tenant');
    
    // Verificar que el usuario tiene acceso
    if (session('dashboard_access') !== true) {
        abort(403);
    }
    
    // Validar según plan
    $validated = $request->validate([
        'business_name' => 'required|max:255',
        'slogan' => 'nullable|max:500',
        'description' => 'nullable|max:1000',
        // ...
    ]);
    
    // Verificar límites del plan
    if ($request->has('products')) {
        $productsCount = count($request->products);
        $limit = $tenant->plan->products_limit;
        
        if ($productsCount > $limit) {
            return response()->json([
                'error' => "Tu plan permite máximo {$limit} productos"
            ], 422);
        }
    }
    
    $tenant->update($validated);
    
    return response()->json(['success' => true]);
}
```

---

## 📱 RESPONSIVE

### Móvil:
- Dashboard ocupa 100% del viewport
- Tabs en scrollable horizontal
- Formularios simplificados (campos grandes)
- Upload de imágenes con cámara directa

### Tablet:
- Dashboard en drawer lateral (350px)
- Tabs visibles todos
- Balance entre desktop y móvil

### Desktop:
- Dashboard en drawer lateral (400px)
- Tabs con iconos + texto
- Formularios con múltiples columnas

---

## 🎯 UX PRINCIPLES

1. **Zero Learning Curve:** Todo debe ser intuitivo
2. **Instant Feedback:** Cada acción tiene respuesta visual
3. **Forgiving:** Fácil deshacer cambios
4. **Progressive Disclosure:** Mostrar opciones avanzadas solo cuando necesario
5. **Mobile-First:** Diseñado primero para móvil

---

**FIN DE ESPECIFICACIONES**  
Última actualización: 2026-02-15
