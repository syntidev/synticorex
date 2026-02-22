# 🧠 ONBOARDING COMPLETO - SYNTIWEB
**Para:** Claude (nueva sesión) + Agentes Cursor
**Versión:** 2026-02-22

---

## 👤 QUIÉN SOY

Soy el arquitecto de SYNTIweb. No programador tradicional — pensador sistémico y ejecutor decidido. 5 meses sin trabajo apostando todo a este producto. Venezuela, trabajo con VPN. Aprendo rápido. El tiempo y el dinero corren.

---

## 🎯 QUÉ ES SYNTIWEB

SaaS multitenant: landing pages dinámicas para negocios venezolanos pequeños. Un negocio → un subdominio → una vitrina digital → contacto por WhatsApp. El dueño gestiona todo con PIN desde su celular.

**Stack:** Laravel 12, PHP 8.3, MySQL, FlyonUI v2.4.1 NPM, Blade puro, CSS puro landing, JS vanilla, Intervention Image v3, Vite.

---

## 🤝 MODELO DE INTERACCIÓN (LO QUE NOS FUNCIONA)

### Los 3 actores:
```
[YO - Arquitecto]  ←→  [CLAUDE - claude.ai]  ←→  [AGENTES - Cursor/VS]
   Humano               Consultor/Revisor          Ejecutores de código
```

### Flujo bidireccional SIEMPRE:
```
1. YO describo el objetivo/problema
2. CLAUDE genera el prompt exacto para el agente
3. YO ejecuto el prompt en Cursor
4. YO traigo el resultado (código/screenshot/error)
5. CLAUDE evalúa, certifica o corrige
6. Si hay error → CLAUDE genera fix → vuelve al paso 3
7. Si está OK → commit → siguiente tarea
```

### Reglas de comunicación:
- Yo traigo outputs COMPLETOS, no resúmenes
- Claude responde CONCISO — qué hacer, por qué, qué sigue
- Si Claude hace algo y luego lo cambia → me avisa explícitamente
- Preguntas con opciones → usar widget de selección
- Nunca suponer — si hay duda, preguntar antes de generar

---

## 🤖 JERARQUÍA DE AGENTES EN CURSOR

### Prioridad por tipo de tarea:

**Claude Haiku 4.5** → Fixes rápidos (0.33x tokens)
- Un archivo, cambio puntual
- Corrección de una línea
- Rename, mover archivo
- Verificar que algo existe
- Ejemplo: "Cambiar índice [4] por [3] en línea 991"

**Claude Sonnet 4.5** → Features medianas/grandes (1x tokens)
- CRUD completo
- Nuevo controller o service
- Vista completa con JS
- Integración de sistemas
- Ejemplo: "Crear Tab Diseño con 17 temas FlyonUI"

**Claude Opus 4.5** → Arquitectura y decisiones complejas (3x tokens)
- Rediseño estructural
- Sistema nuevo complejo
- Debugging profundo multi-archivo
- Solo cuando Sonnet falla 2+ veces
- Ejemplo: "Resolver conflicto PHP/JS en sistema moneda"

**GPT-5.2-Codex / GPT-5.3-Codex** → Auditorías y exploración (1x)
- Solo lectura, sin modificar
- Comparar código vs documentación
- Contexto enorme (272K) para leer todo el proyecto
- Ejemplo: "Auditar rutas vs controllers vs documentación"

### Regla de oro:
```
Haiku primero → si falla → Sonnet → si falla 2x → Opus
```

---

## 📋 ESTRUCTURA DE UN PROMPT PARA AGENTE

```
[CONTEXTO]
Archivo(s) a tocar: ruta/exacta/archivo.php
Estado actual: qué hace ahora

[TAREA]
Qué hacer exactamente — específico y concreto

[RESTRICCIONES]
- NO tocar: lista de lo que no debe cambiar
- NO instalar: paquetes que no debe agregar
- Si intenta cambiar versión de Tailwind/FlyonUI → ignorar y mantener v2.4.1

[RESULTADO ESPERADO]
Qué debe retornar/crear/modificar
```

### Ejemplo real:
```
CONTEXTO:
Archivo: resources/views/dashboard/index.blade.php
El <head> solo tiene Google Fonts, falta FlyonUI CSS

TAREA:
Agregar @vite(['resources/css/app.css','resources/js/app.js'])
después de la línea de Google Fonts en el <head>

RESTRICCIONES:
- NO tocar nada más en el archivo
- NO agregar otras dependencias

RESULTADO:
El <head> con la línea @vite agregada
```

---

## 🏗️ PROYECTO - DATOS TÉCNICOS

### Rutas locales:
```
Proyecto:   C:\laragon\www\synticorex\
Landing:    http://127.0.0.1:8000/techstart
Dashboard:  http://127.0.0.1:8000/tenant/1/dashboard
Progreso:   http://synticorex:8080/.doc/dashboard.php
Puerto:     8080 (Laragon Apache)
```

### Git:
```
Repo:   https://github.com/syntidev/synticorex
Rama:   feature/limpieza-frankenstein
Commit: 4fb3632
```

### Tenant prueba:
```
Subdominio: techstart | ID: 1 | PIN: 1234
```

### Archivos clave:
```
app/Http/Controllers/
  TenantRendererController.php  ← Landing renderer
  DashboardController.php       ← Dashboard 6 tabs
  ImageUploadController.php     ← Upload WebP

app/Services/
  DollarRateService.php         ← API BCV + fallback 36.50
  ImageUploadService.php        ← WebP resize

resources/views/
  landing/
    base.blade.php              ← Layout + CURRENCY_MODE JS
    partials/ (11 archivos)
  dashboard/
    index.blade.php             ← Dashboard ~2000 líneas

routes/web.php                  ← Todas las rutas
```

### Sistema de temas FlyonUI:
```php
// Leer tema activo:
$tenant->settings['engine_settings']['visual']['theme']['flyonui_theme']

// Aplicar en landing (base.blade.php):
<html data-theme="{{ $flyonuiTheme ?? 'light' }}">

// Compilar assets:
@vite(['resources/css/app.css', 'resources/js/app.js'])
// NUNCA: asset('build/assets/app.css') ← no funciona con hash Vite
```

### Sistema de moneda:
```
saved_display_mode opciones:
  reference_only → Solo REF
  bolivares_only → Solo Bs.
  both_toggle    → Toggle público REF ↔ Bs.
  hidden         → Oculta precio → botón "Más Info"

CURRENCY_MODE en base.blade.php resuelve conflicto PHP/JS
```

### Planes:
```
Plan 1 OPORTUNIDAD:  6 productos,  3 servicios
Plan 2 CRECIMIENTO: 18 productos,  6 servicios
Plan 3 VISIÓN:      40 productos, 15 servicios
```

---

## 📊 ESTADO ACTUAL (22 Feb 2026)

```
S1 Fundación:     ████████████████████ 100% ✅
S2 Template:      ████████████████████ 100% ✅
S3 Dashboard:     ███████████████████░  98% ✅
S4 Demos+Polish:  ░░░░░░░░░░░░░░░░░░░░   0% 🔥
```

### Funcionando HOY:
- Landing multitenant dinámica
- 17 temas FlyonUI con preview colores reales
- Sistema moneda 4 modos
- Dashboard 6 tabs CRUD completo
- Panel flotante PIN + QR + Radar
- Upload imágenes WebP

### Pendiente inmediato:
1. Tenants demo: pizzería (gourmet), barbería (luxury), boutique (soft)
2. Flujo landing sección por sección con FlyonUI
3. Limpieza: LEGACY updatePalette, centralizar temas, middleware
4. Analytics real, SEO, onboarding, producción

---

## 🚨 LECCIONES APRENDIDAS

1. **@vite() no asset()** → asset() no resuelve hash de Vite
2. **data-theme anidado no funciona** → CSS variables FlyonUI no se heredan en divs hijos → solución: hardcodear colores
3. **Agentes bajan versiones** → si intenta Tailwind v3 → forzar directriz de mantener FlyonUI v2.4.1
4. **Commits antes de tocar** → siempre checkpoint antes de feature nueva
5. **Haiku primero** → no gastar Sonnet en fixes de una línea
6. **Traer output completo** → no resúmenes, el agente necesita contexto real

---

## 💰 CONTEXTO NEGOCIO

- Venezuela, mercado dolarizado de facto
- Símbolo REF (no $) por restricciones legales 2026
- Estrategia venta: demo en vivo + visita presencial
- Objetivo: 3 primeros clientes esta semana
- Presupuesto API restante: ~$39.50

---

## 📋 PROMPT PARA NUEVA SESIÓN

```
Hola Claude. Soy el arquitecto de SYNTIweb.

Lee NEXT_SESSION.md y ONBOARDING_AGENTE.md 
del proyecto (base de conocimiento).

ESTADO: S1✅ S2✅ S3✅98% S4🔥
Commit: 4fb3632 | Rama: feature/limpieza-frankenstein

FLUJO DE TRABAJO:
- Tú = arquitecto/consultor
- Yo ejecuto prompts en Cursor (Haiku fixes / Sonnet features / Opus arquitectura)
- Traigo resultados/screenshots completos
- Validamos juntos antes de commit

OBJETIVO HOY: [ESCRIBE AQUÍ]
```

---

**¡A vender! 🚀🇻🇪**
