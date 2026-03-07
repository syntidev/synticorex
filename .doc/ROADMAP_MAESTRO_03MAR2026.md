# ROADMAP MAESTRO — SYNTIweb / Synticorex
**Fecha de creación:** 03 MAR 2026
**Última actualización:** 05 MAR 2026 — Revisión de productos, precios y límites
**Reemplaza:** `ROADMAP_MAESTRO_03MAR2026.md`
**Repositorio:** `c:\laragon\www\synticorex`
**Stack definitivo (sin cambios):** Laravel 12 + Preline 4.1.2 + Tailwind v4 + MySQL multi-tenant
**Herramientas:** VS Code + Copilot Pro+ | Suite Anthropic como primaria

---

## REGLAS DE CALIDAD — OBLIGATORIAS EN CADA PROMPT

### Auto-auditoría post-ejecución
Después de **CUALQUIER** refactoring de clases CSS, ejecutar siempre:
```bash
grep -rn "input input-bordered\|label-text\|input w-full\|btn btn-\|FlyonUI\|flyonui" resources/views/dashboard/
```
**Si hay resultados → corregir ANTES del build.** No reportar como completado hasta que grep devuelva vacío.

### Stack definitivo (no negociable)
- ✅ Laravel 12
- ✅ Preline 4.1.2
- ✅ Tailwind v4 (@tailwindcss/vite)
- ✅ Iconify via @iconify/tailwind4 en app.css + web components (iconify-icon en HTML)
- ❌ **PROHIBIDO:** FlyonUI, DaisyUI, Tailwind v3 config

### Clases prohibidas (legacy — eliminarlas siempre)
| Clase Legacy | Reemplazo Preline | Ubicación |
|-------------|------------------|-----------|
| `input input-bordered` | `py-1.5 sm:py-2 px-3 block w-full bg-layer border-layer-line shadow-2xs sm:text-sm rounded-lg text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none` | Inputs/textareas/selects |
| `label-text` | `inline-block text-sm font-medium text-foreground mb-1` | Labels |
| `btn btn-primary` | `py-2 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-primary border border-primary-line text-primary-foreground hover:bg-primary-hover focus:outline-hidden focus:bg-primary-focus disabled:opacity-50 disabled:pointer-events-none` | Botones primarios |
| `btn btn-ghost` / secundario | `py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-layer border border-layer-line text-layer-foreground shadow-2xs hover:bg-layer-hover disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-layer-focus` | Botones secundarios |
| `form-control` (solo la clase) | Mantener para semántica, pero agregar clases Preline a inputs dentro | Contenedores de campos |

### Variables CSS obligatorias en app.css
```css
:root {
    --layer: #ffffff;
    --layer-hover: #f8fafc;
    --layer-focus: #f1f5f9;
    --layer-line: #e2e8f0;
    --layer-foreground: #0f172a;
    --primary-focus: #3b6fd4;
    --primary-line: #3b6fd4;
    --primary-hover: #3b6fd4;
    --primary-foreground: #ffffff;
    --muted: #f8fafc;
    --muted-hover: #f1f5f9;
    --muted-foreground-1: #64748b;
    --muted-foreground-2: #94a3b8;
    --shadow-2xs: 0 1px 2px 0 rgba(0,0,0,0.05);
    --surface: #ffffff;
    --border: #e2e8f0;
    --foreground: #0f172a;
    --success: #22c55e;
}
```

---

## VISIÓN DEL PRODUCTO

SYNTIweb es un ecosistema SaaS multi-tenant que da presencia digital profesional a pequeños negocios venezolanos. No es una página web. Es una plataforma completa que compite con Wix, Squarespace y agencias locales — a precio venezolano, con herramientas para Venezuela.

**Ecosistema:**
- `syntiweb.com` — Plataforma y frente comercial
- `synti.dev` — Cara empresarial / corporativa

---

## LOS 3 PRODUCTOS — DEFINICIÓN DEFINITIVA

> **Regla de oro:** Mismo motor. Diferente blueprint. Diferente buyer. No se canibalizan.

### 🌐 SYNTIstudio
**Qué es:** Presencia digital completa. Web profesional con marca, productos, servicios, SEO.
**Para quién:** Freelancer, consultora, barbería, clínica, marca personal, negocio con identidad.
**Compite con:** Wix ($192/año), Squarespace, agencias venezolanas ($150–$300+).
**Gana por:** Precio + localización Venezuela + BCV automático.
**Blueprint:** `studio`

| Plan | Precio anual | Precio mensual | Productos | Servicios | Temas |
|------|-------------|----------------|-----------|-----------|-------|
| Oportunidad | **$99/año** | ~$13/mes | **20** | 3 | 10 paletas |
| Crecimiento | **$149/año** | ~$19/mes | **50** | 6 | 17 paletas |
| Visión | **$199/año** | ~$25/mes | **Ilimitado** | 9 | 17 + custom ∞ |

> ⚠️ Límites actualizados: 6→20 / 12→50 / 18→ilimitado (tarea D.13 ejecutada como decisión de arquitectura)

**Secciones por plan:**
- Oportunidad: hero, products, services, contact, payment_methods, cta, footer
- Crecimiento: + about, testimonials, horarios, analytics tiempo real
- Visión: + faq, branches (hasta 3 sucursales), SEO profundo, schema.org

**Sobre paletas de color (decisión arquitectónica):**
Las 25–30 paletas se mantienen y se AGRUPAN por industria en el wizard. No se reducen.
Ejemplos: Salud (azules/blancos), Energía/Gym (naranjas), Gastronomía (rojos/cálidos), Moda (neutros/dorados), Tecnología (índigos), Legal (azules oscuros), Belleza (rosas/lilas). El cliente ve su industria y elige — no es confusión, es guía.

---

### 🍔 SYNTIfood
**Qué es:** Menú digital híbrido. 1 foto por categoría + lista de platos con precio. Pedido rápido a WhatsApp.
**Para quién:** Restaurante, arepera, pastelería, food truck, negocio de comida a domicilio.
**Compite con:** Levery, Nedify, carta física impresa.
**Gana por:** Lista larga, carga rápida, 1 foto por categoría (no por plato), Pedido→WhatsApp sin carrito complejo.
**Blueprint:** `food`
**Diferenciador técnico vs competencia:** No requiere foto por ítem. 1 foto de categoría + lista tipo restaurante físico. Venezolano ya sabe leerlo.

| Plan | Precio | Fotos | Ítems en lista | Features clave |
|------|--------|-------|----------------|----------------|
| Básico | **$9/mes** | 6 fotos categoría | 50 ítems | Sin categorías, sin pedido rápido |
| Semestral | **$39** (DECOY) | 12 fotos | 100 ítems | Con categorías + BCV + horarios |
| Anual ⭐ | **$69/año** | 18 fotos | 150 ítems | Todo + **Pedido Rápido → WhatsApp** |

**Pedido Rápido (no es carrito):**
El cliente toca [+] en cada ítem → se acumula en bloc de notas → botón "Enviar pedido" construye string WhatsApp estructurado. El restaurante confirma disponibilidad por WhatsApp. Sin checkout, sin dirección, sin Mini Order.

**Estructura híbrida de menú (congelada):**
```
┌─────────────────────────────────────┐
│ 🐟 PESCADOS        [1 foto]         │
├──────────────────────────┬──────────┤
│ Al ajillo            [+] │  $8.00  │
│ A la primavera       [+] │  $9.00  │
│ Frito                [+] │  $7.00  │
└──────────────────────────┴──────────┘
       [Ver pedido (3 items) →]
```

---

### 🛍️ SYNTIcat
**Qué es:** Catálogo visual con carrito y checkout directo a WhatsApp. Mini Order con ID rastreable.
**Para quién:** Tienda de ropa, proveedor, comercio con muchos productos, retail venezolano.
**Compite con:** Cattaly ($97/año sin carrito).
**Gana por:** Precio menor + carrito WhatsApp que Cattaly no tiene.
**Blueprint:** `cat`

| Plan | Precio | Productos | Imágenes | Variantes | Carrito |
|------|--------|-----------|----------|-----------|---------|
| Básico | **$9/mes** | 20 | 1 | Solo simple | ✗ Solo botón WA |
| Semestral | **$39** (DECOY) | 100 | 3 | size + size_color | ✅ Carrito básico |
| Anual ⭐ | **$69/año** | Ilimitado | 6 | Todas + options | ✅ Carrito + Mini Order SC-XXXX |

**Mini Order (solo plan Anual):**
Genera ID `SC-XXXX` → guarda pedido en JSON → construye string WhatsApp → pantalla de confirmación.
Sin pasarela, sin inventario, sin checkout complejo.

**Variantes por plan:**
- Básico: `none` (producto simple)
- Semestral: `size`, `size_color`
- Anual: Todo + `options` (lista libre: extras, personalización, mensaje)

---

## COMPARATIVA RÁPIDA DE LOS 3 PRODUCTOS

| | SYNTIstudio | SYNTIfood | SYNTIcat |
|---|---|---|---|
| Precio entrada anual | $99 | $69 | $69 |
| Precio top | $199/año | $69/año | $69/año |
| Killer feature | Web completa 🌐 | Menú + Pedido 🍔 | Carrito + SC-XXXX 🛒 |
| Compite con | Wix $192 | Levery sin pedido | Cattaly $97 sin carrito |
| Les gana por | Precio + VE | Pedido→WA | Carrito incluido |
| Blueprint | `studio` | `food` | `cat` |

---

## SISTEMA DE MONEDA — DECISIÓN DEFINITIVA

| Modo | Símbolo | Uso |
|------|---------|-----|
| Bolívares | REF | Por defecto (no Bs. por tema político — listo para cambiar) |
| Dólar | $ | Referencial BCV |
| Ambos | REF + $ | Toggle automático |
| Euro | € | Opcional, 4ta opción — poco común en VE pero disponible |

> El sistema ya está implementado y funcional. Solo activar Euro como opción en config.

---

## ESTADO REAL — 05 MAR 2026

### ✅ COMPLETADO Y FUNCIONAL
- Migraciones, seeders, modelos, relaciones (multi-tenant base)
- Middleware `IdentifyTenant` + resolución por dominio/subdominio
- Renderizado dinámico landing via `TenantRendererController`
- Dashboard flotante (Alt+S desktop / long press móvil) + PIN auth
- CRUD productos/servicios con límites por plan
- Upload imágenes → WebP 800px automático
- Sistema de moneda (4 modos: REF, $, ambos, toggle)
- QR dinámico + toggle estado negocio (abierto/cerrado)
- Analytics básico + Shortlink tracking
- Horario de negocio con indicador visual
- Sistema de paletas de color (25+ esquemas categorizados por industria)
- Navbar condicional por plan
- Hero layouts múltiples
- Migración Flyonui → Preline 4.1.2: **100% completada**
- Identidad visual SYNTIweb definida (syntiweb-brand.css)
- Logo navbar + breathing animation
- Normalización estilos dashboard (tokens Preline)
- UX formularios info-section (placeholders, iconos, subtítulos)

### 🔶 EN PROGRESO
- Landing comercial: estructura existe, copy técnico sin traducir a B2H
- Info-section sub-tabs (A.10)

### ❌ PENDIENTE / NO EXISTE AÚN
- Límites de productos en DB actualizados (20/50/ilimitado para Studio)
- Paletas agrupadas por industria en wizard (UX pendiente)
- Página de planes dinámica (desde DB, no hardcodeada)
- Página de marketing completa (blog, nosotros, comparativa)
- Autenticación real (Google OAuth via Laravel Socialite)
- Seguridad aplicada a rutas tenant (hardening middleware)
- Tablero admin multi-configurador
- Blueprint `food` (SYNTIfood)
- Blueprint `cat` (SYNTIcat)
- SEO automatizado
- Analytics avanzado
- Producción: servidor + DNS + SSL + cron
- Bot / agente de soporte (fase futura)

---

## FASES DE EJECUCIÓN

### FASE A — CERRAR LO ABIERTO ✅ 90% COMPLETA
**Fechas:** 03 MAR → 07 MAR 2026
**Objetivo:** Todo lo que está al 90% llega al 100%.

| # | Tarea | Archivo(s) | Estado |
|---|-------|-----------|--------|
| A.1 | Finalizar migración Preline | `config/`, `resources/views/` | ✅ HECHO |
| A.2 | Identidad visual SYNTIweb | `syntiweb-brand.css` | ✅ HECHO |
| A.3 | Aplicar identidad: Landing + Dashboard + Wizard | Global | ✅ HECHO |
| A.4 | Logo navbar + hero breathing animation | `navbar.blade.php`, `hero.blade.php` | ✅ HECHO |
| A.5 | Copy B2H landing | `landing/sections/*` | ⏸ SUSPENDIDO hasta producto completo |
| A.6 | Responsividad completa (375/768/1440px) | Global | ✅ HECHO |
| A.7 | Lighthouse 90+ | Build + assets | ❌ PENDIENTE |
| A.8 | Normalizar estilos dashboard Preline | `resources/views/dashboard/` | ✅ HECHO |
| A.9 | UX formularios dashboard | `dashboard/components/` | ✅ HECHO |
| A.10 | Info-section sub-tabs | `dashboard/components/` | ✅ HECHO |
| A.11 | QR sticker generador | — | ✅ HECHO |
| A.12 | Imagen Acerca De | — | ✅ HECHO |
| A.13 | Panel flotante Preline + gesto móvil | — | ✅ HECHO (desktop) ⚠️ móvil pendiente validación |
| A.14 | Botón tel: | — | ✅ HECHO |
| A.15 | Rediseño sección "Acerca de" en landing | `landing/sections/about.blade.php` | ❌ PENDIENTE |
| A.16 | Banner promocional inferior con marquee | `landing/sections/`, `base.blade.php` | ❌ PENDIENTE |
| A.17 | Secciones vacías no renderizan en landing ni navbar | Global | ❌ PENDIENTE |
| A.18 | Estado vacío elegante en dashboard por sección sin contenido | `dashboard/components/` | ❌ PENDIENTE |
| A.19 | Auditoría visual premium — Chrome extension | Global | ❌ PENDIENTE |

---

### FASE B — FRENTE COMERCIAL
**Fechas:** 08 MAR → 14 MAR 2026
**Objetivo:** syntiweb.com vende sola.

| # | Tarea | Archivo(s) | Prioridad |
|---|-------|-----------|-----------|
| B.1 | Página `/planes` dinámica desde DB — los 3 productos con toggle | `marketing/planes.blade.php` | 🔴 CRÍTICO |
| B.2 | Hero principal: "¿Tu negocio está en Google?" — CTA "Crea gratis" | `marketing/hero.blade.php` | 🔴 CRÍTICO |
| B.3 | Sección 3 productos: showcase Studio + Food + Cat con mockups | `marketing/productos.blade.php` | 🔴 CRÍTICO |
| B.4 | Página `/nosotros`: misión, historia, confianza | `marketing/nosotros.blade.php` | 🟠 ALTO |
| B.5 | Comparativa vs competencia (Wix, Cattaly, agencias) + savings calculator | `marketing/comparativa.blade.php` | 🟠 ALTO |
| B.6 | Blog base: `/blog` listado + single + meta SEO | `marketing/blog/` | 🟠 ALTO |
| B.7 | Footer completo: links, legal, redes, contacto | `marketing/partials/footer.blade.php` | 🟡 MEDIO |
| B.8 | Formulario de contacto funcional | `marketing/contacto.blade.php` | 🟡 MEDIO |

**Nota B.1:** La tabla `plans` ya existe en DB. Nunca hardcodear precios en Blade. La página de planes debe mostrar los 3 productos con switch/tabs: Studio / Food / Cat.

---

### FASE C — AUTENTICACIÓN Y ACCESO
**Fechas:** 15 MAR → 21 MAR 2026
**Objetivo:** Usuarios reales pueden registrarse, entrar y gestionar su tenant.

| # | Tarea | Archivo(s) | Prioridad |
|---|-------|-----------|-----------|
| C.1 | Login + Registro básico (Laravel Breeze) | `auth/` | 🔴 CRÍTICO |
| C.2 | Google OAuth via Laravel Socialite | `config/services.php`, `SocialiteController` | 🔴 CRÍTICO |
| C.3 | Hardening middleware rutas tenant | `app/Http/Middleware/` | 🔴 CRÍTICO |
| C.4 | Wizard onboarding: seleccionar producto (Studio/Food/Cat) + plan + crear tenant | `onboarding/` | 🔴 CRÍTICO |
| C.5 | Flujo pago / activación de plan (manual o integración básica) | `payments/` | 🟠 ALTO |
| C.6 | Email de bienvenida post-registro | `Mail/WelcomeMail.php` | 🟡 MEDIO |
| C.7 | Recuperación de contraseña | `auth/forgot-password` | 🟡 MEDIO |

**Nota C.4:** El wizard pregunta primero: ¿Qué necesitas? → [Web completa] [Menú digital] [Catálogo tienda]. Eso define el blueprint y los planes disponibles.

---

### FASE D — TABLERO ADMIN + LOS 3 PRODUCTOS
**Fechas:** 22 MAR → 04 ABR 2026
**Objetivo:** Los 3 blueprints activos. Operación real del negocio.

| # | Tarea | Prioridad |
|---|-------|-----------|
| D.1 | Tablero admin: gestión tenants (ver, editar, suspender, eliminar) | 🔴 CRÍTICO |
| D.2 | Tablero admin: gestión planes y precios (CRUD desde UI) | 🔴 CRÍTICO |
| D.3 | Tablero admin: gestión de usuarios/clientes | 🔴 CRÍTICO |
| D.4 | Tablero admin: métricas globales (tenants activos, MRR, conversión) | 🟠 ALTO |
| D.5 | Rol vendedor: panel de clientes asignados + comisiones | 🟠 ALTO |
| D.6 | Rol soporte: acceso a tickets + visualización tenant sin editar | 🟠 ALTO |
| D.7 | **SYNTIstudio:** landing específica + blueprint `studio` aplicado | 🟠 ALTO |
| D.8 | **SYNTIstudio:** Actualizar límites en DB: 20 / 50 / ilimitado | 🔴 CRÍTICO |
| D.9 | **SYNTIstudio:** Agrupar paletas por industria en wizard (UX) | 🟠 ALTO |
| D.10 | **SYNTIfood:** landing específica con copy food | 🟠 ALTO |
> 📄 **Referencia de implementación:**
> Ver `.doc/SYNTIcat_SYNTIfood_Plan_05MAR2026.docx`
> para fases detalladas, tareas, entregables y specs técnicos.
| D.11 | **SYNTIfood:** Blueprint `food` — estructura híbrida (1 foto categoría + lista) | 🔴 CRÍTICO |
| D.12 | **SYNTIfood:** Pedido Rápido → WhatsApp (acumulador de ítems, no carrito completo) | 🔴 CRÍTICO |
| D.13 | **SYNTIfood:** Activar variante `options` (extras) desde plan Semestral | 🟠 ALTO |
| D.14 | **SYNTIcat:** landing específica con copy retail | 🟠 ALTO |
| D.15 | **SYNTIcat:** Blueprint `cat` — catálogo + product card + badges | 🔴 CRÍTICO |
| D.16 | **SYNTIcat:** Cart engine (localStorage) + Cart Drawer lateral | 🔴 CRÍTICO |
| D.17 | **SYNTIcat:** Mini Order Engine → genera SC-XXXX → JSON por tenant | 🔴 CRÍTICO |
| D.18 | **SYNTIcat:** WhatsApp Checkout → string estructurado + URL wa.me | 🔴 CRÍTICO |
| D.19 | **SYNTIcat:** Carrito solo en plan Semestral y Anual (plan Básico = solo botón WA) | 🟠 ALTO |
| D.20 | SEO automatizado: meta tags dinámicos por blueprint + schema.org | 🟡 MEDIO |
| D.21 | Analytics avanzado: tracking JS → `analytics_events` + dashboard visual | 🟡 MEDIO |

---

### FASE E — PRODUCCIÓN Y ESCALA
**Fechas:** 05 ABR → 15 ABR 2026
**Objetivo:** Plataforma en vivo. Primeros clientes pagando.

| # | Tarea | Prioridad |
|---|-------|-----------|
| E.1 | Servidor Hostinger configurado (PHP + Laravel + MySQL) | 🔴 CRÍTICO |
| E.2 | DNS, SSL, dominios (syntiweb.com + subdominio wildcard *.syntiweb.com) | 🔴 CRÍTICO |
| E.3 | Cron jobs: tasa BCV, analytics cleanup | 🔴 CRÍTICO |
| E.4 | Variables de entorno producción | 🔴 CRÍTICO |
| E.5 | Pipeline deploy (GitHub Actions o manual) | 🟠 ALTO |
| E.6 | Monitoring básico (errores, uptime) | 🟠 ALTO |
| E.7 | Backup automático DB | 🟠 ALTO |
| E.8 | Onboarding 5 clientes beta (1 por cada tipo: Studio×2, Food×2, Cat×1) | 🔴 CRÍTICO |

---

### FASE F — CRECIMIENTO (POST-LANZAMIENTO)
**Fechas:** Mayo 2026 en adelante
**Objetivo:** Escalar, retener, monetizar mejor.

| # | Tarea | Prioridad |
|---|-------|-----------|
| F.1 | Bot de soporte parametrizable por tenant | 🟠 ALTO |
| F.2 | Zonas de delivery con mapa visual de cobertura y costos | 🟠 ALTO |
| F.3 | Pasarela de pago (Binance Pay / transferencia BCV / Pago móvil) | 🔴 CRÍTICO |
| F.4 | App móvil PWA (dashboard mobile-first) | 🟠 ALTO |
| F.5 | Email marketing sequences | 🟡 MEDIO |
| F.6 | A/B testing landing | 🟡 MEDIO |
| F.7 | Afiliados / referidos (1 mes gratis por referido que pague, máx 6 meses) | 🟡 MEDIO |
| F.8 | API pública para integraciones | 🟡 MEDIO |
| F.9 | SYNTIcat: carrito completo con pasarela Fase F.3 | 🟠 ALTO |
| F.10 | SYNTIfood: dominio propio por tenant | 🟠 ALTO |
| F.11 | SYNTIcat: dominio propio por tenant (plan Anual) | 🟠 ALTO |

---

## ARQUITECTURA TÉCNICA (REFERENCIA RÁPIDA)

**Vía única de renderizado landing:**
- `app/Http/Controllers/TenantRendererController.php`
- `resources/views/landing/base.blade.php`
- `resources/views/landing/partials/*`

**Resolución de tenant:**
- Por subdominio: `{slug}.syntiweb.com`
- Por dominio custom: `www.tunegocio.com`
- Sin tenant válido → 404

**Blueprints disponibles:**
- `studio` → landing web completa
- `food` → menú digital híbrido
- `cat` → catálogo con carrito WhatsApp

**Storage por tenant:**
- Path: `storage/tenants/{tenant_id}/`
- Imágenes: WebP, máx 800px, máx 2MB
- Slots nombrados: `logo.webp`, `hero.webp`, `product_01.webp`
- SYNTIcat orders: `storage/tenants/{tenant_id}/orders/2026/03/SC-XXXX.json`

**Tablas principales:**
`users` → `tenants` → `plans` → `products` / `services` / `tenant_customization` / `analytics_events` / `invoices`

**Tabla `plans` — valores definitivos:**

| slug | nombre | precio_anual | precio_mensual | blueprint | productos | servicios |
|------|--------|-------------|----------------|-----------|-----------|-----------|
| studio-oportunidad | Oportunidad | 99 | 13 | studio | 20 | 3 |
| studio-crecimiento | Crecimiento | 149 | 19 | studio | 50 | 6 |
| studio-vision | Visión | 199 | 25 | studio | null | 9 |
| food-basico | Básico | null | 9 | food | 50_items | — |
| food-semestral | Semestral | 39 | null | food | 100_items | — |
| food-anual | Anual | 69 | null | food | 150_items | — |
| cat-basico | Básico | null | 9 | cat | 20 | — |
| cat-semestral | Semestral | 39 | null | cat | 100 | — |
| cat-anual | Anual | 69 | null | cat | null | — |

**Checklist antes de tocar código:**
- ¿Afecta multi-tenant? → verificar `tenant_id` en toda query
- ¿Afecta planes? → validar contra este roadmap sección Tabla plans
- ¿Afecta schema? → validar contra `03_SCHEMA_DATABASE.md`
- ¿Agrega endpoint? → middleware + validación obligatoria
- ¿Toca imágenes? → WebP + 800px + naming convention
- ¿Afecta blueprint? → validar que no rompe los otros 2

---

## REGLAS DE EJECUCIÓN (NO NEGOCIABLES)

1. **Stack es definitivo.** Laravel 12 + Preline 4.1.2 + Tailwind v4. Sin cambios sin aprobación del arquitecto (Carlos).
2. **Logo SYNTIweb:** Color círculo `#4A80E4` NUNCA cambia.
3. **Cada tarea completada = actualizar este documento.** Sin excepción.
4. **Cada commit referencia la tarea:** `feat: D.11 — blueprint food estructura híbrida`
5. **Sin features fuera de este roadmap.** Si surge algo nuevo → se agrega aquí antes de ejecutar.
6. **Seguridad intencionalmente al final** para no bloquear desarrollo. Se activa en Fase C.
7. **Los 3 productos reutilizan arquitectura base.** No se crea código desde cero. Se segmenta por blueprint.
8. **Planes dinámicos desde DB siempre.** Nunca hardcodear precios, features o límites en Blade o JS.
9. **Blueprints son aditivos.** `food` y `cat` heredan lo funcional de `studio`. No se duplica lógica.

---

## PROGRESS DIARIO

**Regla:** Actualizar esta sección cada noche antes de cerrar VS Code.

### 03 MAR 2026
- [x] Reconfiguración instrucciones Claude Project
- [x] Actualización memoria del proyecto
- [x] Creación ROADMAP_MAESTRO_03MAR2026.md

### 04 MAR 2026
- [x] A.1 — Migración Preline completada
- [x] A.2 — Identidad visual SYNTIweb (syntiweb-brand.css)
- [x] A.3 — Identidad aplicada a todos los módulos
- [x] A.4 — Logo navbar + breathing animation
- [x] A.8 — Normalización estilos dashboard Preline
- [x] A.9 — UX formularios info-section

### 05 MAR 2026
- [x] Revisión arquitectónica completa de los 3 productos
- [x] Definición definitiva de precios (Studio / Food / Cat)
- [x] Actualización límites Studio: 6→20 / 12→50 / 18→ilimitado (pendiente ejecutar en DB)
- [x] SYNTIfood: estructura híbrida y Pedido Rápido definidos
- [x] SYNTIcat: Mini Order SC-XXXX y carrito por plan definidos
- [x] Roadmap maestro actualizado con los 3 productos
- [ ] A.10 — Info-section sub-tabs (en progreso)
- [ ] A.11 — QR sticker generador
- [ ] A.12 — Imagen Acerca De
- [ ] A.13 — Panel flotante Preline + gesto móvil
- [ ] A.14 — Botón tel:

### 06 MAR 2026
- [x] A.6 — Responsividad dashboard completa (FlyonUI legacy eliminado)
- [x] A.10 — Info-section sub-tabs resuelto
- [x] A.11 — QR sticker generador funcional
- [x] A.12 — Imagen Acerca De integrada
- [x] A.13 — Panel flotante desktop funcional (móvil pendiente validación real)
- [x] A.14 — Botón tel: implementado
- [x] landing/partials/ eliminada permanentemente — sections es el único estándar
- [x] Reglas permanentes en copilot-instructions.md + copilot-workspace.yml + SYNTIWEB-CONTEXT.md
- [x] Bug zoom imagen producto en landing — resuelto
- [x] WebP calidad subida a 90 en ImageUploadService
- [x] header_message — campo creado en DB, dashboard y landing (banner no resuelto visualmente — removido)
- [ ] A.15 — Rediseño sección Acerca De
- [ ] A.16 — Banner promocional inferior
- [ ] A.17 — Secciones vacías no renderizan
- [ ] A.18 — Estado vacío elegante dashboard
- [ ] A.19 — Auditoría visual premium

Commit: docs: roadmap actualizado 06 MAR 2026

### 07 MAR 2026
_(actualizar aquí)_

---

**Autor:** Carlos Bolívar (Arquitecto) + Claude (Co-arquitecto y ejecutor)
**Última actualización:** 05 MAR 2026
**Próxima revisión:** Al completar Fase A completa (07 MAR 2026)