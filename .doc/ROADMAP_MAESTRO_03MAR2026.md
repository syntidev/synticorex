# ROADMAP MAESTRO — SYNTIweb / Synticorex
**Fecha de creación:** 03 MAR 2026  
**Reemplaza:** `01_ROADMAP_MVP__checklist_.md` + `NEXT_SESSION.md` + `PROGRESS.md`  
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

**Los 3 productos (misma arquitectura base, segmentación por blueprint):**
- **SYNTIstudio** — Landing profesional para marcas, freelancers, consultores, proyectos
- **SYNTIfood** — Menú digital para negocios de comida (restaurantes, areperas, postres, etc.)
- **SYNTIcat** — Catálogo visual con botón directo a WhatsApp para comercios y tiendas

**Planes por producto:**
| Plan | Precio | Productos | Servicios | Imágenes | Temas |
|------|--------|-----------|-----------|----------|-------|
| OPORTUNIDAD | $99/año | 6 | 3 | 10 paletas | — |
| CRECIMIENTO | $149/año | 12 | 6 | 17 paletas | — |
| VISIÓN | $199/año | 18 | 9 | 17 paletas + custom infinito | — |

**Secciones por plan:**
- Plan 1: hero, products, services, contact, payment_methods, cta, footer
- Plan 2: + about, testimonials
- Plan 3: + faq, branches

---

## ESTADO REAL HOY — 03 MAR 2026

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
- Sistema de paletas de color (25 esquemas categorizados)
- Navbar condicional por plan (documentado)
- Hero layouts múltiples (documentado)
- Migración Flyonui → Preline 4.1.2: **90% completada**

### 🔶 EN PROGRESO
- Migración Flyonui → Preline: falta 10% (detalle de tema/empaque DB)
- Landing comercial: estructura existe, copy técnico sin traducir a B2H
- Logo SYNTIweb: archivo local listo, falta integrar en navbar + hero

### ❌ PENDIENTE / NO EXISTE AÚN
- Identidad visual SYNTIweb definida y aplicada a toda la plataforma
- Página de planes dinámica (desde DB, no hardcodeada)
- Página de marketing completa (blog, nosotros, comparativa)
- Autenticación real (Google OAuth via Laravel Socialite)
- Seguridad aplicada a rutas tenant (hardening middleware)
- Tablero admin multi-configurador
- Gestión de clientes, vendedores, soporte
- SEO automatizado (meta tags dinámicos por blueprint)
- Analytics avanzado (tracking JS → analytics_events)
- Producción: servidor + DNS + SSL + cron
- Los 3 productos segmentados con su propio landing específico
- Bot / agente de soporte (fase futura)

---

## FASES DE EJECUCIÓN

### FASE A — CERRAR LO ABIERTO
**Fechas:** 03 MAR → 07 MAR 2026  
**Objetivo:** Todo lo que está al 90% llega al 100%. Cero deuda visual abierta.

**Tareas:**

| # | Tarea | Archivo(s) | Prioridad |
|---|-------|-----------|-----------|
| A.1 | Finalizar migración Preline: resolver detalle tema/empaque DB | `config/`, `resources/views/` | 🔴 CRÍTICO |
| A.2 | Definir identidad visual SYNTIweb (token de colores, tipografía, espaciado) | `tailwind.config.js`, `resources/css/` | 🔴 CRÍTICO |
| A.3 | Aplicar identidad visual a: Landing + Dashboard + Wizard + Landing comercial | Global | 🔴 CRÍTICO |
| A.4 | Integrar logo en navbar (fijo) + hero (grande, breathing animation) | `landing/partials/navbar.blade.php`, `landing/sections/hero.blade.php` | 🔴 CRÍTICO |
| A.5 | Traducir todo el copy de la landing a B2H (sin jerga técnica) | `landing/sections/*` | 🟠 ALTO |
| A.6 | Validar responsividad completa (mobile 375px / tablet 768px / desktop 1440px) | Global | 🟠 ALTO |
| A.7 | Lighthouse 90+ en todas las categorías | Build + assets | 🟡 MEDIO |
| A.8 | Normalizar estilos dashboard: aplicar tokens Preline 4.1.2 a todas las secciones (cards, iconos, formularios, badges, inputs). Eliminar clases legacy FlyonUI/Tailwind hardcodeadas. | `resources/views/dashboard/` | 🔴 CRÍTICO |
| A.9 | UX formularios dashboard: placeholders descriptivos, iconos por sección, subtítulos guía, accesibilidad (aria-label, tab-order). Prioridad: info-section.blade.php | `resources/views/dashboard/components/` | 🟠 ALTO |


### FASE B — FRENTE COMERCIAL
**Fechas:** 08 MAR → 14 MAR 2026  
**Objetivo:** syntiweb.com es una vitrina que vende sola. Sin diseño hermoso no hay cierre.

**Tareas:**

| # | Tarea | Archivo(s) | Prioridad |
|---|-------|-----------|-----------|
| B.1 | Página `/planes` dinámica desde DB (tabla `plans`) con cards expandibles + feature matrix + CTA por plan | `marketing/planes.blade.php` | 🔴 CRÍTICO |
| B.2 | Hero principal landing comercial: "¿Tu negocio está en Google?" — CTA "Crea gratis" | `marketing/hero.blade.php` | 🔴 CRÍTICO |
| B.3 | Sección productos: showcase de SYNTIstudio + SYNTIfood + SYNTIcat con mockups reales | `marketing/productos.blade.php` | 🔴 CRÍTICO |
| B.4 | Página `/nosotros`: equipo, misión, historia, confianza | `marketing/nosotros.blade.php` | 🟠 ALTO |
| B.5 | Sección comparativa vs competencia (Wix, Squarespace, agencias) con savings calculator | `marketing/comparativa.blade.php` | 🟠 ALTO |
| B.6 | Blog base: estructura `/blog` con listado + single post + meta SEO | `marketing/blog/` | 🟠 ALTO |
| B.7 | Footer completo: links, legal, redes, contacto | `marketing/partials/footer.blade.php` | 🟡 MEDIO |
| B.8 | Formulario de contacto funcional (Formspree o similar) | `marketing/contacto.blade.php` | 🟡 MEDIO |

**Nota B.1:** La tabla `plans` ya existe en DB. La página lee de ahí. Si cambias precio en DB → página se actualiza sola. Nunca hardcodear precios en Blade.

---

### FASE C — AUTENTICACIÓN Y ACCESO
**Fechas:** 15 MAR → 21 MAR 2026  
**Objetivo:** Usuarios reales pueden registrarse, entrar y gestionar su tenant. Esto abre monetización.

**Tareas:**

| # | Tarea | Archivo(s) | Prioridad |
|---|-------|-----------|-----------|
| C.1 | Login + Registro básico (Laravel Breeze o equivalente) | `auth/` | 🔴 CRÍTICO |
| C.2 | Google OAuth via Laravel Socialite | `config/services.php`, `SocialiteController` | 🔴 CRÍTICO |
| C.3 | Hardening middleware en rutas tenant (auth + ownership check) | `app/Http/Middleware/` | 🔴 CRÍTICO |
| C.4 | Wizard de onboarding post-registro: seleccionar producto + plan + crear tenant | `onboarding/` | 🔴 CRÍTICO |
| C.5 | Flujo de pago / activación de plan (manual o integración básica) | `payments/` | 🟠 ALTO |
| C.6 | Email de bienvenida post-registro | `Mail/WelcomeMail.php` | 🟡 MEDIO |
| C.7 | Recuperación de contraseña | `auth/forgot-password` | 🟡 MEDIO |

**Nota C.2:** Google OAuth con Socialite es ~4-6h de trabajo. Es la forma más fácil para el usuario venezolano. Si hay bloqueador técnico, se implementa solo email/password y Google va a v1.1.

---

### FASE D — TABLERO ADMIN + PRODUCTOS SEGMENTADOS
**Fechas:** 22 MAR → 04 ABR 2026  
**Objetivo:** Operación real del negocio. Gestión, soporte, métricas. Los 3 productos activos.

**Tareas:**

| # | Tarea | Prioridad |
|---|-------|-----------|
| D.1 | Tablero admin: gestión de tenants (ver, editar, suspender, eliminar) | 🔴 CRÍTICO |
| D.2 | Tablero admin: gestión de planes y precios (CRUD desde UI, no solo DB) | 🔴 CRÍTICO |
| D.3 | Tablero admin: gestión de usuarios / clientes | 🔴 CRÍTICO |
| D.4 | Tablero admin: métricas globales (tenants activos, MRR, conversión) | 🟠 ALTO |
| D.5 | Rol vendedor: acceso a panel de clientes asignados, comisiones | 🟠 ALTO |
| D.6 | Rol soporte: acceso a tickets, visualización de tenant sin editar | 🟠 ALTO |
| D.7 | SYNTIstudio: landing específica + blueprint aplicado | 🟠 ALTO |
| D.8 | SYNTIfood: landing específica + blueprint menú digital | 🟠 ALTO |
| D.9 | SYNTIcat: landing específica + blueprint catálogo WhatsApp | 🟠 ALTO |
| D.10 | SEO automatizado: meta tags dinámicos por blueprint + schema.org | 🟡 MEDIO |
| D.11 | Analytics avanzado: tracking JS → `analytics_events` + dashboard visual | 🟡 MEDIO |
| D.12 | SYNTIcat: normalizar product-card con mini-carrito (cantidad + total) para Plan 2 y 3. Plan 1 solo botón WhatsApp. Hereda conversión de moneda existente. Sin pasarela por ahora. | 🟠 ALTO |
| D.13 | Revisar y aumentar límites de productos por plan en tabla `plans` DB: OPORTUNIDAD 6→20, CRECIMIENTO 12→50, VISIÓN 18→ilimitado. Validar competencia venezolana antes de ejecutar. | 🟡 MEDIO |

### FASE E — PRODUCCIÓN Y ESCALA
**Fechas:** 05 ABR → 15 ABR 2026  
**Objetivo:** Plataforma en vivo, estable, con primeros clientes pagando.

**Tareas:**

| # | Tarea | Prioridad |
|---|-------|-----------|
| E.1 | Servidor Hostinger configurado (PHP + Laravel + MySQL) | 🔴 CRÍTICO |
| E.2 | DNS, SSL, dominios (syntiweb.com + subdominio wildcard) | 🔴 CRÍTICO |
| E.3 | Cron jobs: tasa BCV, analytics cleanup | 🔴 CRÍTICO |
| E.4 | Variables de entorno producción | 🔴 CRÍTICO |
| E.5 | Pipeline deploy (GitHub Actions o manual) | 🟠 ALTO |
| E.6 | Monitoring básico (errores, uptime) | 🟠 ALTO |
| E.7 | Backup automático DB | 🟠 ALTO |
| E.8 | Onboarding de 5 clientes beta | 🔴 CRÍTICO |

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
| F.7 | Afiliados / referidos | 🟡 MEDIO |
| F.8 | API pública para integraciones | 🟡 MEDIO |
| F.9 | Carrito completo SYNTIcat con pasarela | 🟠 ALTO |

---

## REGLAS DE EJECUCIÓN (NO NEGOCIABLES)

1. **Stack es definitivo.** Laravel 12 + Preline 4.1.2 + Tailwind v4. Sin cambios de framework sin aprobación explícita del arquitecto (Carlos).
2. **Logo SYNTIweb:** Color círculo `#4A80E4` NUNCA cambia. Ver `CURSOR_RULES_UNIFIED.md`.
3. **Cada tarea completada = actualizar este documento.** Sin excepción.
4. **Cada commit referencia la tarea:** `feat: A.4 — logo integrado navbar + hero animation`
5. **Sin features fuera de este roadmap.** Si surge algo nuevo → se agrega aquí antes de ejecutar.
6. **Seguridad es lo último intencionalmente** para no bloquear desarrollo con login en cada cambio. Se activa en Fase C.
7. **Los 3 productos reutilizan la arquitectura base.** No se crea código nuevo desde cero. Se segmenta por blueprint.
8. **Planes dinámicos desde DB siempre.** Nunca hardcodear precios, features o límites en Blade o JS.

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

**Storage por tenant:**
- Path: `storage/tenants/{tenant_id}/`
- Imágenes: WebP, máx 800px, máx 2MB
- Slots nombrados: `logo.webp`, `hero.webp`, `product_01.webp`...

**Tablas principales:**
`users` → `tenants` → `plans` → `products` / `services` / `tenant_customization` / `analytics_events` / `invoices`

**Checklist antes de tocar código:**
- ¿Afecta multi-tenant? → verificar `tenant_id` en toda query
- ¿Afecta planes? → validar contra `02_MATRIZ_FEATURES_DEFINITIVA.md`
- ¿Afecta schema? → validar contra `03_SCHEMA_DATABASE.md`
- ¿Agrega endpoint? → middleware + validación obligatoria
- ¿Toca imágenes? → WebP + 800px + naming convention

---

## PROGRESS DIARIO

**Regla:** Actualizar esta sección cada noche antes de cerrar VS Code.

### 03 MAR 2026
- [x] Reconfiguración instrucciones Claude Project (ejecutor, no arquitecto)
- [x] Actualización memoria del proyecto
- [x] Creación ROADMAP_MAESTRO_03MAR2026.md
- [ ] A.1 — Finalizar migración Preline (en progreso)

### 04 MAR 2026
 - [x] A.1 — Migración Preline completada (temas, custom palette, hero layouts)
 - [x] A.2 — Identidad visual SYNTIweb definida (syntiweb-brand.css)
 - [x] A.3 — Identidad aplicada: Dashboard + Marketing + Wizard + Landing tenant
 - [x] A.4 — Logo navbar + breathing animation
 - [x] A.8 — Normalizar estilos dashboard (tokens Preline en todos los archivos)
 - [x] A.9 — UX formularios info-section (placeholders, iconos, subtítulos)
 - [ ] A.5 — Copy B2H SUSPENDIDO hasta producto completo
 - [ ] A.6 — Responsividad (pendiente)
 - [ ] A.7 — Lighthouse (pendiente)
 - [ ] A.10 — Info-section sub-tabs (en ejecución)
 - [ ] A.11 — QR sticker generador (pendiente)
 - [ ] A.12 — Imagen Acerca De (pendiente)
 - [ ] A.13 — Panel flotante Preline + gesto móvil (pendiente)
 - [ ] A.14 — Botón tel: (pendiente)
**Última actualización:** 04 MAR 2026
### 05 MAR 2026
_(actualizar aquí)_

### 06 MAR 2026
_(actualizar aquí)_

### 07 MAR 2026
_(actualizar aquí)_

---

## DOCUMENTOS QUE REEMPLAZA ESTE ARCHIVO

| Documento viejo | Estado |
|----------------|--------|
| `01_ROADMAP_MVP__checklist_.md` | OBSOLETO — reemplazado por este |
| `NEXT_SESSION.md` | OBSOLETO — reemplazado por este |
| `PROGRESS.md` | OBSOLETO — reemplazado por este |
| `TASKS_STATUS.md` | OBSOLETO — integrado en este |

---

**Autor:** Carlos Bolívar (Arquitecto) + Claude (Asesor)  
**Última actualización:** 03 MAR 2026  
**Próxima revisión:** Al completar Fase A (07 MAR 2026)
