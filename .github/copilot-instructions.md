# SYNTIWEB — INSTRUCCIONES MAESTRAS PARA AGENTES IA
# Versión: 4.0 | Actualizado: MAR 2026
# ⚠️ LEER COMPLETO ANTES DE GENERAR CUALQUIER RESPUESTA

---

## 🚦 GOBERNANZA — LEER PRIMERO, SIEMPRE

### Modos de operación (el usuario los activa con la palabra clave)

| MODO       | Palabra clave | Qué hacer                              | PROHIBIDO                              |
|------------|---------------|----------------------------------------|----------------------------------------|
| CONSULTA   | [CONSULTA]    | Responder en ≤5 líneas, sin código     | Abrir archivos, escribir código        |
| DISEÑO     | [DISEÑO]      | Proponer arquitectura/estructura       | Implementar, tocar archivos            |
| EJECUCIÓN  | [EJECUTA]     | Implementar lo acordado exactamente    | Inferir cambios fuera del scope pedido |
| REVISIÓN   | [REVISA]      | Auditar código existente               | Proponer refactors no solicitados      |
| DEBUG      | [DEBUG]       | Diagnosticar SOLO el error reportado   | Tocar código fuera del scope           |

**Si el modo no está declarado → preguntar: "¿Modo CONSULTA, DISEÑO o EJECUCIÓN?"**
**NUNCA asumir modo EJECUCIÓN por defecto.**

---

### Protocolo anti-deriva (irrompible)

Antes de cada respuesta, verificar internamente:
1. ¿Me pidieron código? → Solo entonces escribo código
2. ¿El scope es claro? → Si no, preguntar en UNA línea y parar
3. ¿Voy a modificar algo fuera de lo pedido? → PARAR
4. ¿Encontré un bug fuera del scope? → Reportar en 1 línea, NO corregir

**Límites duros:**
- NUNCA abrir archivos adicionales sin permiso explícito
- NUNCA proponer "ya que estoy aquí, también arreglé..."
- NUNCA continuar después de completar el pedido
- Máximo 1 archivo modificado por request salvo instrucción explícita
- Si no puedo responder sin más evidencia → decir exactamente qué necesito y PARAR

### Formato de respuesta ejecutiva (modos CONSULTA y DISEÑO)
```
1. Qué entendí: [1 línea]
2. Respuesta: [máximo 5 líneas o el entregable pedido]
3. Próximo paso: [1 línea, solo si es obvio y útil]
```

---

## 🏗️ PROYECTO

SaaS multitenant Venezuela. Landing pages dinámicas para negocios pequeños.
Tenant = subdominio O dominio personalizado. PIN desde celular. WhatsApp.
Moneda: REF (no $). Ruta local: `C:\laragon\www\synticorex\`

---

## 🔧 STACK

```
Laravel 12.51, PHP 8.3, MySQL
Preline 4.1.2, Tailwind 4.2, Alpine.js 3.4.2
Vite 7, Intervention Image 3.11
QR: simplesoftwareio/simple-qrcode
Blade puro, JS vanilla
```

---

## 🚨 REGLAS CRÍTICAS — NUNCA VIOLAR

### PHP
- `declare(strict_types=1)` en TODO archivo PHP
- Early return pattern obligatorio
- Eager loading obligatorio (evitar N+1)
- NUNCA lógica compleja en Blade
- Tenant isolation: siempre filtrar por `tenant_id`
- Imágenes: max 800px, convertir a WebP
- NUNCA `asset()` → siempre `@vite()`
- NUNCA `exec()`, `shell_exec()`, `eval()`, `new Function()`
- NUNCA `{!! !!}` salvo Schema.org generado internamente

### UI / CSS
- NUNCA instalar FlyonUI ni DaisyUI (removidos permanentemente)
- NUNCA bajar versiones de Preline (4.1.2) ni Tailwind (4.2)
- NUNCA clases DaisyUI: `btn card modal badge drawer collapse dropdown join stat toast divider menu alert`
- Tailwind utilitario puro para todo estilo
- Íconos: `@iconify-json/tabler` únicamente — NUNCA emojis como íconos UI

### Blade
- NUNCA usar `landing/partials/` — carpeta eliminada permanentemente
- SIEMPRE `landing/sections/` para todo blade de landing
- `@include` siempre como `@include('landing.sections.NOMBRE')`
- NUNCA Node.js en servidor (solo build local)

---

## ⚠️ FRONTERA CRÍTICA — DOS MUNDOS SEPARADOS

```
MUNDO INTERNO (SyntiWeb)     MUNDO TENANT (público)
─────────────────────────    ──────────────────────
dashboard/, wizard/          landing/
marketing/, auth/, docs/     landing/sections/
→ branding SyntiWeb          → JAMÁS logo/colores SyntiWeb
  (#4A80E4, logo SyntiWeb)     usar SOLO variables --brand-* del tenant
```

---

## 🧱 COMPONENTES INTERACTIVOS — SOLO PRELINE

```
Modal/Overlay   → hs-overlay + data-hs-overlay
Accordion       → hs-accordion + hs-accordion-toggle + hs-accordion-content
Dropdown        → hs-dropdown + hs-dropdown-toggle + hs-dropdown-menu
Tabs            → data-hs-tab + hs-tab-active:
Navbar collapse → hs-collapse + hs-collapse-toggle + data-hs-collapse
Alpine.js (x-data, x-show, x-on) es compatible con Preline — mantener intacto
```

---

## 💰 PLANES

```
Plan 1 OPORTUNIDAD  $99/año:  6 productos, 3 servicios, 10 paletas
Plan 2 CRECIMIENTO $149/año: 12 productos, 6 servicios, 17 paletas
Plan 3 VISIÓN      $199/año: 18 productos (slider 3 fotos), 9 servicios, 17 paletas + custom
```

---

## 💱 MONEDA

```
Símbolo: REF (nunca $)
Modos: reference_only / bolivares_only / both_toggle / hidden
Lógica resuelta en base.blade.php — no duplicar
```

---

## ✅ CHECKLIST ANTES DE ENTREGAR CÓDIGO

- [ ] declare(strict_types=1) en PHP
- [ ] Sin clases DaisyUI en HTML
- [ ] Sin overflow-hidden en contenedores padre
- [ ] cursor-pointer en elementos clickeables
- [ ] Touch targets ≥ 44px
- [ ] @vite() en lugar de asset()
- [ ] tenant_id validado en queries
- [ ] Sin logo SyntiWeb en vistas de tenant
- [ ] Blade en landing/sections/ nunca en landing/partials/