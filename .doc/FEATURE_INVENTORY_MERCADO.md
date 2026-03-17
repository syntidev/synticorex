# FEATURE INVENTORY — SYNTIweb
**Generado:** 2026-03-16
**Rama:** main
**Fuente:** Código fuente real — no documentación

---

## RESUMEN EJECUTIVO

| Métrica | Total |
|---|---|
| Rutas web expuestas | 48 |
| Rutas API expuestas | 20 |
| Features con gate de plan 📋 | 19 |
| Columnas en DB sin UI visible ⚠️ | 8 |
| Features no documentadas 🆕 | 8 |
| Código muerto identificado ⚰️ | 2 |
| Comandos artisan (automatizaciones) | 12 |
| Notificaciones del sistema | 7 |
| Modelos de datos | 22 |
| Servicios de negocio | 18 |

---

## 1. MAPA DE RUTAS COMPLETO

### 1.1 Web Routes

| Método | URI | Nombre | Controlador@Método | Middleware | Notas |
|--------|-----|--------|--------------------|-----------|-------|
| GET | / | home | MarketingController@index | public | Página principal SYNTIweb |
| GET | /planes | marketing.planes | MarketingController@planes | public | Página comercial de planes |
| GET | /studio | marketing.studio | MarketingController@studio | public | Landing producto STUDIO |
| GET | /food | marketing.food | MarketingController@food | public | Landing producto FOOD |
| GET | /cat | marketing.cat | MarketingController@cat | public | Landing producto CAT |
| GET | /terminos | marketing.terms | MarketingController@terms | public | Términos y condiciones |
| GET | /privacidad | marketing.privacy | MarketingController@privacy | public | Política de privacidad |
| GET | /nosotros | marketing.about | MarketingController@about | public | Página corporativa |
| GET | /auth/google | auth.google | SocialAuthController@redirectToGoogle | public | OAuth inicio |
| GET | /auth/google/callback | auth.google.callback | SocialAuthController@handleGoogleCallback | public | OAuth callback |
| GET | /onboarding | onboarding.selector | OnboardingController@selector | public | Selector de producto |
| GET | /onboarding/nuevo | onboarding.index | OnboardingController@index | auth | Wizard STUDIO |
| POST | /onboarding/guardar | onboarding.store | OnboardingController@store | auth | Guardar tenant STUDIO |
| GET | /onboarding/subdomain-check | onboarding.subdomain-check | OnboardingController@checkSubdomain | auth | Verificar disponibilidad |
| GET | /onboarding/{tenant}/preview | onboarding.preview | OnboardingController@preview | auth | Vista previa |
| POST | /onboarding/{tenant}/publicar | onboarding.publish | OnboardingController@publish | auth | Publicar tenant |
| POST | /onboarding/studio/guardar | onboarding.store.studio | OnboardingController@store | auth | Alias guardar STUDIO |
| POST | /onboarding/food/guardar | onboarding.store.food | OnboardingController@storeFood | auth | Guardar tenant FOOD |
| POST | /onboarding/cat/guardar | onboarding.store.cat | OnboardingController@storeCat | auth | Guardar tenant CAT |
| GET | /mis-negocios | tenants.index | TenantsController@index | auth, verified | Listado del usuario |
| GET | /blog | blog.index | MarketingController@blog | public | Blog index |
| GET | /blog/{slug} | blog.show | MarketingController@blogPost | public | Artículo individual |
| GET | /{subdomain} | tenant.landing | TenantRendererController@show | tenant | Catch-all landing pública |
| POST | /tenant/{tenantId}/verify-pin | — | TenantRendererController@verifyPin | throttle:5,1 | Autenticación PIN |
| POST | /tenant/{tenantId}/toggle-status | — | TenantRendererController@toggleStatus | public | Abrir/cerrar negocio |
| PATCH | /tenant/{tenantId}/toggle-whatsapp | — | TenantRendererController@toggleWhatsapp | public | Cambiar WhatsApp activo |
| POST | /api/analytics/track | — | AnalyticsController@track | public | Registro de eventos |
| GET | /api/dollar-rate | — | inline (DollarRateService) | public | Tasa USD actual |
| GET | /api/euro-rate | — | inline (DollarRateService) | public | Tasa EUR actual 🆕 |
| POST | /api/dollar-rate/refresh | — | inline (DollarRateService) | auth, EnsureAdmin | Forzar actualización USD |
| POST | /api/euro-rate/refresh | — | inline (DollarRateService) | auth, EnsureAdmin | Forzar actualización EUR 🆕 |
| GET | /tenant/{tenantId}/dashboard | dashboard.edit-tenant | DashboardController@index | auth, verified, tenant.owner | Dashboard principal |
| POST | /tenant/{tenantId}/update-info | — | DashboardController@updateInfo | auth, verified, tenant.owner | Info del negocio |
| POST | /tenant/{tenantId}/update-theme | — | DashboardController@updateTheme | auth, verified, tenant.owner | Cambiar tema |
| POST | /tenant/{tenantId}/update-palette | — | DashboardController@updatePalette | auth, verified, tenant.owner | Cambiar paleta |
| POST | /tenant/{tenantId}/products | — | DashboardController@createProduct | auth, verified, tenant.owner | Crear producto |
| PUT | /tenant/{tenantId}/products/{productId} | — | DashboardController@updateProduct | auth, verified, tenant.owner | Editar producto |
| DELETE | /tenant/{tenantId}/products/{productId} | — | DashboardController@deleteProduct | auth, verified, tenant.owner | Eliminar producto |
| POST | /tenant/{tenantId}/services | — | DashboardController@createService | auth, verified, tenant.owner | Crear servicio |
| PUT | /tenant/{tenantId}/services/{serviceId} | — | DashboardController@updateService | auth, verified, tenant.owner | Editar servicio |
| DELETE | /tenant/{tenantId}/services/{serviceId} | — | DashboardController@deleteService | auth, verified, tenant.owner | Eliminar servicio |
| POST | /tenant/{tenantId}/upload/logo | — | ImageUploadController@uploadLogo | auth, verified, tenant.owner | Logo WebP |
| POST | /tenant/{tenantId}/upload/hero | — | ImageUploadController@uploadHero | auth, verified, tenant.owner | Hero WebP |
| POST | /tenant/{tenantId}/upload/product/{productId} | — | ImageUploadController@uploadProduct | auth, verified, tenant.owner | Imagen producto |
| POST | /tenant/{tenantId}/upload/service/{serviceId} | — | ImageUploadController@uploadService | auth, verified, tenant.owner | Imagen servicio |
| POST | /tenant/{tenantId}/upload/about | — | ImageUploadController@uploadAbout | auth, verified, tenant.owner | Imagen About |
| GET | /tenant/{tenantId}/analytics | — | AnalyticsController@getData | auth, verified, tenant.owner | Datos analytics privados |
| GET | /tenant/{tenantId}/qr/download | — | QRTrackingController@downloadQR | auth, verified, tenant.owner | Descarga QR PNG |
| GET | /tenant/{tenantId}/billing | tenant.billing.data | BillingController@getBillingData | auth, verified, tenant.owner | Datos de facturación |
| POST | /tenant/{tenantId}/billing/report-payment | tenant.billing.report | BillingController@reportPayment | auth, verified, tenant.owner | Reportar pago |
| GET | /tenant/{tenantId}/orders | tenant.orders | OrdersController@index | auth, verified, tenant.owner | Órdenes CAT |
| POST | /{subdomain}/checkout | tenant.checkout | CheckoutController@store | web | Pedido CAT público |
| POST | /{subdomain}/food-checkout | food.checkout | ComandaController@store | web | Comanda FOOD pública |
| GET | /menu/{subdomain} | food.menu.public | MenuController@show | public | Menú FOOD público |
| GET | /admin/stats-badge | admin.stats-badge | inline | auth, EnsureAdmin | Badge stats admin |
| GET | /admin/billing | admin.billing.queue | BillingController@adminQueue | auth, EnsureAdmin | Cola de pagos |
| POST | /admin/billing/{invoiceId}/approve | admin.billing.approve | BillingController@approvePayment | auth, EnsureAdmin | Aprobar pago |
| POST | /admin/billing/{invoiceId}/reject | admin.billing.reject | BillingController@rejectPayment | auth, EnsureAdmin | Rechazar pago |
| GET | /admin/billing/{invoiceId}/receipt | admin.billing.receipt | BillingController@viewReceipt | auth, EnsureAdmin | Ver recibo |
| POST | /admin/tools/cache | admin.tools.cache | AdminToolsController@clearCache | auth, EnsureAdmin | Limpiar caché |
| POST | /admin/tools/migrate | admin.tools.migrate | AdminToolsController@runMigrations | auth, EnsureAdmin | Ejecutar migraciones |
| POST | /admin/tools/suspend-expired | admin.tools.suspend | AdminToolsController@suspendExpiredNow | auth, EnsureAdmin | Suspender expirados ahora |

### 1.2 API Routes

| Método | URI | Controlador@Método | Middleware | Auth Requerida |
|--------|-----|--------------------|-----------|----------------|
| GET | /api/tenants | TenantController@index | public | No |
| GET | /api/tenants/{id} | TenantController@show | public | No |
| GET | /api/tenants/{tenantId}/products | ProductController@index | public | No |
| GET | /api/tenants/{tenantId}/services | ServiceController@index | public | No |
| POST | /api/tenants | TenantController@store | auth:sanctum | Sí |
| PUT | /api/tenants/{id} | TenantController@update | auth:sanctum, tenant.owner | Sí |
| DELETE | /api/tenants/{id} | TenantController@destroy | auth:sanctum, tenant.owner | Sí |
| PATCH | /api/tenants/{id}/toggle-status | TenantController@toggleStatus | auth:sanctum, tenant.owner | Sí |
| POST | /api/tenants/{tenantId}/products | ProductController@store | auth:sanctum, tenant.owner | Sí |
| PATCH | /api/tenants/{tenantId}/products/reorder | ProductController@reorder | auth:sanctum, tenant.owner | Sí |
| PUT | /api/tenants/{tenantId}/products/{id} | ProductController@update | auth:sanctum, tenant.owner | Sí |
| DELETE | /api/tenants/{tenantId}/products/{id} | ProductController@destroy | auth:sanctum, tenant.owner | Sí |
| PATCH | /api/tenants/{tenantId}/products/{id}/toggle-active | ProductController@toggleActive | auth:sanctum, tenant.owner | Sí |
| PATCH | /api/tenants/{tenantId}/products/{id}/toggle-featured | ProductController@toggleFeatured | auth:sanctum, tenant.owner | Sí |
| POST | /api/tenants/{tenantId}/services | ServiceController@store | auth:sanctum, tenant.owner | Sí |
| PATCH | /api/tenants/{tenantId}/services/reorder | ServiceController@reorder | auth:sanctum, tenant.owner | Sí |
| PUT | /api/tenants/{tenantId}/services/{id} | ServiceController@update | auth:sanctum, tenant.owner | Sí |
| DELETE | /api/tenants/{tenantId}/services/{id} | ServiceController@destroy | auth:sanctum, tenant.owner | Sí |
| PATCH | /api/tenants/{tenantId}/services/{id}/toggle-active | ServiceController@toggleActive | auth:sanctum, tenant.owner | Sí |
| POST | /api/synti/public-ask | SyntiHelpController@publicAsk | throttle:10,60 | No (AI pública) |

---

## 2. FEATURES POR PRODUCTO

### 2.1 SYNTIstudio

#### Storefront público (landing)
- Navbar con logo, nombre negocio y horario
- Header-top: banner de mensaje superior 📋 Plan 2+
- Hero sección: layout gradient, fullscreen-v2 o split (configurable)
  - Slot principal + 4 slots adicionales disponibles (hero-slot-2 a hero-slot-5)
- Sección Productos (máx 6 / 12 / 18 según plan 📋)
  - Badge por producto: hot, new, promo
  - Productos destacados (is_featured)
  - Precio en REF con toggle USD/Bs 📋 Plan 2+
- Sección Servicios (máx 3 / 6 / 9 según plan 📋)
  - Icono Iconify tabler por servicio
  - Imagen, texto overlay, CTA personalizado por servicio
- Sección About (texto + imagen) 📋 Plan 2+
- Sección Testimonials 📋 Plan 2+
- Sección FAQ 📋 Plan 3 (VISIÓN)
- Sección Sucursales/Branches 📋 Plan 3 (VISIÓN)
- Sección Métodos de Pago
- Sección CTA personalizable
- Footer con redes sociales y contacto
- Schema.org structured data (tipo Restaurant/Store/etc)

#### Dashboard del dueño
- **Tab Productos:** crear/editar/eliminar, reordenar drag-and-drop, toggle activo/destacado, badge
- **Tab Servicios:** crear/editar/eliminar, icono/imagen/overlay/CTA por servicio
- **Tab Temas:** selector de tema (35 temas disponibles) + paleta de colores 📋
- **Tab Info:** nombre negocio, slogan, descripción, contacto, redes sociales, métodos de pago
- **Tab Imágenes:** upload logo/hero/about (WebP auto-optimizado)
- **Tab Analytics:** métricas pageviews, clics WhatsApp, QR scans 📋 (nivel por plan)
- **Tab Facturación:** facturas, reportar pago, estado suscripción
- **Tab Ajustes:** PIN de acceso móvil, modo moneda, orden de secciones, toggle por sección
- Paleta personalizada 📋 Plan 3 solamente
- Testimonials editor 📋 Plan 2+
- FAQ editor 📋 Plan 3

#### Onboarding / Wizard Studio
- Paso 1: Selector de blueprint/segmento
- Paso 2: Datos básicos del negocio (nombre, descripción, teléfono, ciudad)
- Paso 3: Subdominio (con validación de disponibilidad en tiempo real)
- Paso 4: Imagen hero upload
- Vista previa antes de publicar
- Publicación automática

### 2.2 SYNTIfood

#### Menú público (template food)
- URL pública: `syntiweb.com/{subdomain}` O `{dominio-custom}`
- Menú con categorías + items
- Fotos por item (con límite por plan 📋)
- Precios en REF + toggle USD/Bs 📋 Plan 2+
- Estado abierto/cerrado (is_open) con mensaje configurable
- Filtro por horario de operaciones 📋 Plan 2+
- Footer + contacto

#### Pedido Rápido → WhatsApp
- Botón flotante WhatsApp
- Selector de artículos del menú
- Mensaje pre-formateado hacia WhatsApp del negocio
- Modo sitio (comer aquí), llevar, delivery
- Detección de horario para mostrar/ocultar WhatsApp

#### Sistema de Comandas 📋 cat-anual / food-anual
- Formulario de comanda pública
- ID único formato SF-XXXXXX
- Almacenamiento JSON: `tenants/{id}/comandas/{año}/{mes}/SF-XXXX.json`
- Estados: nuevo → preparando → listo → entregado
- Vista de comandas en dashboard (tiempo real)
- Modalidades: sitio, llevar, delivery

#### Dashboard Food
- **Tab Menú:** gestión de categorías y platos, fotos por item
- **Tab Comandas (food-anual 📋):** listado en tiempo real, cambio de estado
- **Tab Imágenes:** hero slots (hasta 5)
- **Tab Info/Ajustes:** horarios, WhatsApp, moneda

#### Wizard Food
- Selección segmento comida
- Datos básicos del restaurante
- Primera categoría + primer plato con imagen

### 2.3 SYNTIcat

#### Catálogo público (template catalog)
- URL pública similar a studio
- Listado de productos con imagen, precio, badge
- Toggle USD/Bs 📋 Plan 2+
- Categorías/subcategorías de productos (columnas en DB, sin UI de asignación todavía ⚠️)

#### Carrito y variantes
- Módulo de carrito en Blade (Alpine.js)
- Cálculo subtotal/total en tiempo real
- Envío de pedido vía WhatsApp O checkout directo

#### Mini Order SC-XXXX 📋 cat-anual
- ID único formato SC-XXXXXX
- Checkout con datos del cliente
- Almacenamiento JSON: `tenants/{id}/orders/{año}/{mes}/SC-XXXX.json`
- Mensaje al cliente vía WhatsApp con resumen de orden

#### Sistema de Órdenes 📋 cat-anual
- Endpoint privado de órdenes para tenant
- Vista listado en dashboard

#### Dashboard Cat
- **Tab Productos:** crear/editar/eliminar/reordenar, toggle activo/destacado
- **Tab Órdenes (cat-anual 📋):** listado SC-XXXX, cambio de estado
- **Tab Categorías:** gestión de categorías del catálogo
- **Tab Imágenes:** hero + productos
- **Tab Info/Ajustes:** igual que studio

#### Wizard Cat
- Selección segmento retail/servicios
- Datos básicos
- Primer producto con imagen

---

## 3. FEATURES TRANSVERSALES

### 3.1 Sistema de moneda (DollarRateService)

- Tasa USD: fuente primaria `dolarapi.com`, fallback `pydolarve.org` (BCV)
- Tasa EUR: misma lógica, endpoint `/api/euro-rate` 🆕 (no documentado)
- Actualización automática: comando `dollar:update` (cron o manual)
- Fallback configurable: `config/currency.php` (USD: 36.50, EUR: 495.00)
- Cache 1 hora en Redis/file cache
- Propagación a todos los tenants (settings.engine_settings.dollar_rate)
- Modos de display en landing: `reference_only` | `bolivares_only` | `both_toggle` | `hidden`
- Toggle en vivo desde floating panel del tenant

### 3.2 Analytics y tracking (AnalyticsController + AnalyticsEvent)

**Eventos rastreados:**
- `pageview` — Vista de landing
- `click_whatsapp` — Clic en botón WhatsApp
- `click_call` — Clic en teléfono
- `click_toggle_currency` — Toggle USD/Bs
- `time_on_page` — Tiempo en página (en segundos)
- `qr_scan` — Escaneo de código QR (vía shortlink)

**Datos capturados:** IP hash (SHA256, primeros 45 chars), user_agent, referer, date, hour

**Niveles por plan 📋:**
- Plan 1 OPORTUNIDAD: `basic` — vistas y clics
- Plan 2 CRECIMIENTO: `advanced` — + fuentes de tráfico, por hora
- Plan 3 VISIÓN: `full` — todo lo anterior + reportes por email

**Rate limit:** 100 eventos/minuto por tenant

### 3.3 QR y Shortlink (QRService + QRTrackingController)

- URL shortlink: `syntiweb.me/t/{tenantId}/{code}` (código determinístico por tenant)
- Formato: SVG (embebible en landing) o PNG descargable
- Error correction: nivel M
- Evento `qr_scan` registrado al redirigir
- Descarga desde dashboard como PNG

### 3.4 Horario y estado (BusinessHoursService)

- Horarios por día de la semana configurables (open, close)
- Soporte de horarios nocturnos (ej. 20:00 → 04:00)
- Zona horaria: `America/Caracas` (Venezuela fija)
- `isOpen(tenant)` — Retorna bool
- `getNextOpenTime(tenant)` — Texto legible "Abre mañana a las 8:00 AM"
- Acepta formato array o JSON string desde DB
- `whatsapp_hour_filter` 📋 Plan 2+: oculta botón WhatsApp fuera de horario

### 3.5 WhatsApp (WhatsappMessageBuilder)

- Hasta 2 números WhatsApp por negocio 📋 Plan 2+
- `whatsapp_active`: enum `sales` | `support` — controla cuál muestra el botón flotante
- Mensaje pre-formateado con `build(order)` (para FOOD y CAT)
- URL `wa.me/{número}?text={mensaje_encodado}`
- Integración con filtro de horario (Plan 2+)

### 3.6 Imágenes (ImageUploadService)

| Tipo | Máx. Ancho | Compresión | Tamaño Max | Plan |
|------|-----------|------------|-----------|------|
| logo | 400px | WebP 90% | 2 MB | Todos |
| hero | 1600px | WebP 90% | 2 MB | Todos |
| hero-slot-2 a hero-slot-5 | 1600px | WebP 90% | 2 MB | Todos |
| product (main) | 1000px | WebP 90% | 2 MB | Todos |
| product gallery (2 extra) | 1000px | WebP 90% | 2 MB | 📋 Plan 3 |
| service | 1000px | WebP 90% | 2 MB | Todos |
| about | 1000px | WebP 90% | 2 MB | 📋 Plan 2+ |

**Storage:** `storage/app/public/tenants/{tenantId}/{type}_{index}.webp`

### 3.7 SEO y Schema.org (landing/schemas/)

- Nivel SEO básico (meta title, description) — Plan 1+
- Nivel SEO avanzado (keywords, canonical) — Plan 2+ 📋
- Schema.org tipo según blueprint:
  - studio → `LocalBusiness` / `Store` / `ProfessionalService`
  - food → `Restaurant` / `FoodEstablishment`
  - cat → `Store` / `OnlineBusiness`
- Schema.org para FAQ 📋 Plan 3 (faq_schema feature)
- Schema.org para menú FOOD 📋 Plan 2+ (menu_schema feature)
- `{!! !!}` permitido EXCLUSIVAMENTE para Schema.org generado internamente

### 3.8 Temas y paletas (PrelineThemeService + ColorPalette)

**35 temas disponibles (sin gate de plan — todos para todos):**

| Categoría | Temas |
|-----------|-------|
| Original | default, harvest, retro, ocean, bubblegum, autumn, moon, cashmere, olive |
| Food | sabor-tradicional, fuego-urbano, parrilla-moderna, casa-latina |
| Dulces | rosa-vainilla, pistacho-suave, cielo-dulce, chocolate-caramelo |
| Salud | azul-confianza, verde-calma |
| Autoridad | azul-profesional, ejecutivo-oscuro, prestigio-clasico |
| Oficios | industrial-pro, negro-impacto, metal-urbano |
| Belleza | nude-elegante, rosa-studio, barber-clasico |
| Fitness | fuerza-roja, verde-potencia, azul-electrico |
| Educación | azul-academico, verde-progreso, claro-simple |

**Paletas de color 📋:**
- Plan 1: 10 paletas disponibles
- Plan 2: 17 paletas disponibles
- Plan 3: 17 paletas + paleta personalizada (hex libre)

### 3.9 Generación IA de imágenes (ProductImageGeneratorService + ServiceImageGeneratorService)

- `ProductImageGeneratorService`: genera imagen WebP para producto a partir de nombre/descripción
- `ServiceImageGeneratorService`: genera imagen WebP para servicio
- Ambos invocables en batch via `artisan regenerate:product-images`
- Artisan `rename:images-to-standard` para normalizar nombres legacy

### 3.10 SYNTI Asistente IA (AIServiceSwitcher + BytezProvider + GeminiProvider)

**Endpoint público:** `POST /api/synti/public-ask` (throttle 10 req/60s)

**Flujo:**
1. Usuario pregunta en chat embebido
2. `SyntiHelpController@ask()` busca contexto en `AiDoc` via FULLTEXT MySQL
3. `AIServiceSwitcher` elige proveedor por `AI_PROVIDER` env
4. Proveedor genera respuesta ≤200 tokens, en español venezolano, máx 3 líneas
5. Respuesta + rating registrados en `ai_chat_logs`

**Proveedores:**
- `BytezProvider`: Qwen/Qwen3-8B vía `api.bytez.com`
- `GeminiProvider`: gemini-flash-latest vía Google AI API

**Persona:** SYNTiA — asistente IA de SYNTIweb

### 3.11 Dominios personalizados (Domain + DomainEvent + VerifyDomainDns) ⚠️ Parcial

- Modelo `Domain` completo: registrar, auth_code, DNS esperado, nameservers, fechas, estado
- `DomainEvent`: log de auditoría por dominio
- Comando `verify-domain:dns` para verificar registros DNS
- Comando `domains:check-expiry` para alertas de vencimiento
- `DomainExpiringNotification`: email al propietario/admin cuando faltan ≤45 días
- Recurso Filament `DomainResource` con filtros por estado y fecha
- ⚠️ Sin UI en dashboard de tenant para gestionar dominios propios
- ⚠️ `auth_code` y `registrar_login` presentes en DB (datos sensibles)

### 3.12 Legal como servicio

- `GET /terminos` → `MarketingController@terms`
- `GET /privacidad` → `MarketingController@privacy`
- Vistas en `resources/views/marketing/`

### 3.13 PDF Analytics Report (pdf/analytics-report.blade.php)

- Plantilla Blade para generar PDF de reporte de analytics
- Invocado desde `SendAnalyticsReports` command
- Enviado vía `TenantAnalyticsReportNotification` (email)
- 📋 Solo tenants con analytics_level = 'full' (Plan 3)

### 3.14 Google OAuth (SocialAuthController)

- Flujo estándar: `redirectToGoogle()` → consent → `handleGoogleCallback()`
- Crea usuario si no existe (upsert por google_id)
- Almacena `google_id` y `avatar` en tabla users
- Sin scope de email/profile adicional documentado

### 3.15 Notificaciones del sistema

| Notificación | Canal | Disparador | Destinatario |
|---|---|---|---|
| PaymentApprovedNotification | Mail | Invoice status → paid | Dueño del tenant |
| PaymentRejectedNotification | Mail | Invoice status → rejected | Dueño del tenant |
| DomainExpiringNotification | Mail | Dominio ≤45 días para vencer | Admin/Tenant |
| TenantAnalyticsReportNotification | Mail | Comando semanal | Dueño del tenant 📋 Plan 3 |
| NewTicketNotification | Mail | SupportTicket creado | Equipo soporte |
| TicketAnsweredNotification | Mail | admin_reply actualizado | Creador del ticket |
| AlertNotification | Mail + Slack | Alertas del sistema | Admin |

### 3.16 Reporte de analytics por email (SendAnalyticsReports command)

- Genera PDF con métricas del período
- Envía a tenants con analytics_level = 'full'
- Períodos: `weekly`, `monthly` (flag CLI `--period=`)

---

## 4. ADMIN INTERNO (Filament v5)

### 4.1 TenantResource + CopilotTools

**Campos editables:** business_name, plan_id, status, subscription_ends_at, city, phone, whatsapp_sales, email

**Filtros:** status, plan_id, created_at

**Acciones bulk:** Suspend, Restore, Delete

**Relaciones visibles:** plan, user, customization

**CopilotTools (acciones IA para admin):**

| Tool | Acción disponible vía AI |
|------|--------------------------|
| ListTenantsTool | Listar tenants por estado |
| SearchTenantsTool | Buscar por nombre o email |
| SuspendTenantTool | Congelar tenant |
| RestoreTenantTool | Descongelar tenant |

### 4.2 PlanResource

**Campos editables:** slug, name, price_usd, products_limit, services_limit, color_palettes, show_dollar_rate, show_header_top, show_about_section, show_faq, analytics_level, seo_level, whatsapp_numbers, whatsapp_hour_filter

### 4.3 UserResource (roles: admin, vendedor, soporte, cliente)

**Campos editables:** name, email, role, is_admin

**Roles definidos en DB:** `admin` | `vendedor` | `soporte` | `cliente`

**Sistema de comisiones (vendedor) 🆕:**
- `User.vendor_profile` (JSON): perfil de vendedor
- `User.vendor_sales_month`: ventas del mes
- `User.vendor_total_earned`: total comisiones
- `User.referral_code`: código de afiliado
- `getCommissionRate()`: tasa dinámica por nivel de ventas
- ⚠️ Sin UI de gestión de afiliados visible en dashboard

### 4.4 BlogPostResource (SYNTIBlog)

**Campos editables:** title, slug, content (Markdown), featured, status, blog_category_id, tags (JSON), published_at, author, avatar_url, read_time, featured_image

**Estados:** draft | published | archived

**Blog público:** `GET /blog` y `GET /blog/{slug}` (parte de marketing)

### 4.5 DomainResource + EventsRelationManager

**Campos editables:** domain, tld, type, managed_by, registrar, expires_at, auto_renew, dns_status, cost_price, sale_price

**Filtros:** status, managed_by, expires_at (range)

**RelationManager:** DomainEvents (log de auditoría — solo lectura)

### 4.6 LandingSectionResource ⚠️

**Campos editables:** section_key, section_label, content (JSON), is_active, sort_order

**Estado:** Modelo e interfaz Filament existen, sin uso en landing actual. Posible feature futura de secciones globales.

### 4.7 MediaResource ⚠️

**Tabla:** `media` (gestor de archivos Filament)

**Campos:** file_name, size, name

**Estado:** Configuración Filament media library mínima, uso operacional no confirmado.

### 4.8 SupportTicketResource

**Campos editables:** subject, status, category, admin_reply (con sugerencia IA en `ai_suggestion`)

**Categorías:** billing | technical | request | other

**Flujo:** Ticket creado → `NewTicketNotification` → Admin responde → `TicketAnsweredNotification`

### 4.9 InvoiceResource

**Filtros:** status, tenant_id, created_at

**Acciones bulk:** Mark as Paid, Mark as Pending, Delete

**Estados:** pending_review | paid | rejected

**Formato número:** SYNTI-YYYY-XXXXX

### 4.10 Páginas admin

| Página | Función |
|--------|---------|
| Dashboard (Filament) | Revenue, tenants, blueprint distribution, últimos tenants |
| AdminToolsPage | Cache, logs, queue, migrate, suspend, disk usage, log tail |
| CompanySettingsPage | Datos corporativos SYNTIweb, contacto, logo |
| MailSettingsPage | Config SMTP con test de envío integrado |
| PlatformAnalyticsPage | Métricas globales de la plataforma: pageviews, visitas, clics WA, QR |
| SystemHealthPage | Estado de BD, APIs, storage, colas, jobs fallidos, tenants por vencer |

### 4.11 Widgets

| Widget | Datos mostrados |
|--------|-----------------|
| StatsOverviewWidget | Total tenants, activos, trial, revenue total, revenue mes, MRR, churn |
| BlueprintDonutChart | Distribución Studio vs Food vs CAT |
| RevenueLineChart | Tendencia ingresos mensuales (6 meses) |
| LatestTenantsWidget | Últimos 5 tenants nuevos |
| CurrencyRatesWidget | Tasa USD/EUR actual + timestamp última actualización |

---

## 5. AUTOMATIZACIONES (Artisan Commands)

| Comando | Descripción | Trigger | Impacto |
|---------|-------------|---------|---------|
| `dollar:update` | Obtiene tasa USD/EUR de DolarAPI/PyDolarVe y propaga a todos los tenants | Manual / Cron diario | Actualización en cascada |
| `tenants:check-expiry` | Ciclo de vida: activo → frozen → archived según subscription_ends_at | Cron diario | Cambia estado tenants |
| `tenants:suspend-expired` | Suspende (activo → frozen) si vencimiento pasado | Cron diario | Freeze inmediato |
| `ai:index-docs` | Re-indexa fulltext search de AiDoc (MySQL) | Manual vía AdminTools | Afecta respuestas IA |
| `domains:check-expiry` | Notifica dominios a ≤45 días de vencer | Cron semanal | Email a admin/tenant |
| `reports:send` | Genera + envía PDF analytics por email | Cron semanal/mensual | Email a tenants Plan 3 |
| `verify-domain:dns` | Verifica registros DNS para dominios personalizados | Manual CLI | Actualiza dns_status |
| `section:check` | Verifica consistencia de secciones de landing | Manual | Diagnóstico |
| `domains:process-expirations` | Procesa vencimientos de dominios gestionados | Cron | Cambia estado dominios |
| `images:regenerate-products` | Re-genera imágenes de productos con IA | Manual | Batch de imágenes |
| `images:rename-to-standard` | Normaliza nombres de archivo legados | Manual (migración única) | Renombrado bulk |
| `alerts:run-checks` | Dispara `AlertNotification` si hay condiciones críticas | Cron | Email/Slack al admin |

---

## 6. GATES DE PLAN — MAPA REAL

> Extraído del código fuente (columnas en tabla `plans`, trait HasBlueprint, FeatureGate, Blade)

### 6.1 Gates por plan de STUDIO

| Feature | Plan 1 OPORTUNIDAD | Plan 2 CRECIMIENTO | Plan 3 VISIÓN | Enforced By |
|---------|-------------------|-------------------|--------------|-------------|
| Productos máx | 6 | 12 | 18 | `products_limit` column |
| Servicios máx | 3 | 6 | 9 | `services_limit` column |
| Imágenes por producto | 1 (main) | 1 (main) | 3 (main + 2 galería) | `ProductImage`, `images_limit` |
| Paletas de color disponibles | 10 | 17 | 17 | `min_plan_id` en `color_palettes` |
| Paleta personalizada | ❌ | ❌ | ✅ | `isFeatureUnlocked('custom_palette')` |
| Toggle USD/Bs | ❌ | ✅ | ✅ | `show_dollar_rate` |
| Header-top (banner) | ❌ | ✅ | ✅ | `show_header_top` |
| Sección About | ❌ | ✅ | ✅ | `show_about_section` |
| Testimonials | ❌ | ✅ | ✅ | Blade @if plan_id >= 2 |
| FAQ Section | ❌ | ❌ | ✅ | `show_faq` |
| 2 números WhatsApp | ❌ | ✅ | ✅ | `whatsapp_numbers` column |
| Filtro horario WhatsApp | ❌ | ✅ | ✅ | `whatsapp_hour_filter` |
| Galería imágenes producto | ❌ | ❌ | ✅ | `isFeatureUnlocked('gallery_images')` |
| Sucursales (branches) | ❌ | ❌ | ✅ | Blade @if plan_id === 3 |
| SEO básico | ✅ | ✅ | ✅ | `seo_level = basic` |
| SEO avanzado (keywords, canonical) | ❌ | ✅ | ✅ | `seo_level = advanced` |
| Analytics básico | ✅ | — | — | `analytics_level = basic` |
| Analytics avanzado (referers, horas) | — | ✅ | — | `analytics_level = advanced` |
| Analytics completo + reporte email | — | — | ✅ | `analytics_level = full` |

### 6.2 Gates por blueprint (producto)

| Feature | studio | food | cat | Enforced By |
|---------|--------|------|-----|-------------|
| Gestión de menú (categorías + items) | ❌ | ✅ | ❌ | MenuService + blueprint check |
| Sistema de comandas | ❌ | ✅ (plan food-anual) | ❌ | ComandaService + plan slug |
| Checkout WhatsApp comanda | ❌ | ✅ | ❌ | ComandaController |
| Mini Order SC-XXXX | ❌ | ❌ | ✅ (plan cat-anual) | OrderService + plan slug |
| Checkout de productos | ❌ | ❌ | ✅ | CheckoutController |
| Menu público (/menu/{subdomain}) | ❌ | ✅ | ❌ | MenuController |
| Schema.org Restaurant | ❌ | ✅ | ❌ | getBlueprintSchemaType() |

---

## 7. COLUMNAS EN DB SIN UI VISIBLE ⚠️

| Tabla | Columna | Tipo | Estado | Posible Uso / Deuda |
|-------|---------|------|--------|---------------------|
| products | compare_price_usd | decimal(10,2) | ⚠️ Sin UI | Precio tachado / oferta — columna existe, sin input en dashboard |
| products | price_bs | decimal | ⚠️ Calculado | Se deriva de price_usd × tasa. No editable directamente (correcto) |
| products | image_url | string | ⚠️ Sin UI | URL externa de imagen — sin campo de entrada en dashboard |
| products | category_name | string | ⚠️ Sin UI | Categoría del producto — columna existe, sin asignación en UI |
| products | subcategory_name | string | ⚠️ Sin UI | Subcategoría — misma situación |
| users | referral_code | string | ⚠️ Sin UI | Sistema de afiliados skeleton — no hay UI de tracking |
| users | vendor_profile | json | ⚠️ Sin UI pública | Solo admin Filament puede editarlo |
| users | vendor_sales_month / vendor_total_earned | int/decimal | ⚠️ Sin UI | No hay pantalla de comisiones para vendedores |
| invoices | currency | enum | ⚠️ Parcial | Columna existe (multi-moneda), lógica siempre REF |
| domains | auth_code | string | ⚠️ Sensible | Sin UI — dato sensible de transferencia de dominio en DB sin cifrar |
| domains | registrar_login | string | ⚠️ Sensible | Credenciales de registrar — sin cifrado confirmado |
| support_tickets | ai_suggestion | text | ⚠️ Sin UI visible | Campo presente, no se muestra en Filament resource confirmado |
| tenants | demo_product | — | ⚠️ Sin documentar | Columna presente en tenants, uso no encontrado en controllers/views |

---

## 8. FEATURES NO DOCUMENTADAS 🆕

1. **Soporte EUR (Euro):** `DollarRateService` tiene `fetchAndStoreEuro()`, rutas `/api/euro-rate` y `/api/euro-rate/refresh`, tabla `dollar_rates` con `currency_type` enum incluyendo EUR. No documentado en ningún .md del proyecto.

2. **Sistema de Afiliados/Vendedores:** User tiene `referral_code`, `vendor_profile`, `vendor_sales_month`, `vendor_total_earned` y método `getCommissionRate()`. Sin documentación ni UI completa.

3. **Categorías/Subcategorías en Catálogo CAT:** Columnas `category_name` y `subcategory_name` en tabla `products`. Modelo las lista como fillable. Sin UI de asignación en dashboard ni documentación de diseño.

4. **Hero con multi-slots (hasta 5):** TenantCustomization tiene `hero_secondary_filename`, `hero_tertiary_filename`, `hero_image_4_filename`, `hero_image_5_filename`. ImageUploadController tiene `uploadHeroSlot()` para slots 1–5. No documentado como feature de ningún plan.

5. **Precio con descuento (compare_price_usd):** Columna `compare_price_usd` en products permite precio tachado. Sin UI ni documentación de uso.

6. **SYNTIBlog con sistema de categorías:** `BlogCategory` model con color y sort_order, `BlogPost` con tags JSON, views counter, múltiples estados. Más avanzado que la documentación existente describe.

7. **Sistema de Soporte con sugerencia IA:** `SupportTicket.ai_suggestion` — el sistema guarda una sugerencia generada por IA para el admin al responder tickets. No documentado.

8. **Analytics de plataforma (PlatformAnalyticsService):** Métricas agregadas de toda la plataforma (no por tenant). Incluye traffic sources breakdown y top tenants. No aparece en ninguna doc pública.

---

## 9. CÓDIGO MUERTO ⚰️

| Archivo / Método | Evidencia | Acción sugerida |
|---|---|---|
| `app/Http/Controllers/old_SyntiHelpController.php` | No referenciado en ninguna ruta ni otro archivo. `SyntiHelpController.php` está activo y reemplaza esta versión. Usa BytezProvider hardcodeado sin AIServiceSwitcher. | Eliminar |
| `app/Models/LandingSection.php` + tabla `landing_sections` | Modelo definido con interfaz Filament (`LandingSectionResource`), pero ningún `@include` en landing views lo consume. Sin controller que lo sirva al renderizador de la landing. | Clarificar propósito o archivar |
| `app/Models/MediaFile.php` | Modelo definido para tabla `media` (Filament), no confirmado en ningún flujo activo del producto. | Verificar uso con Filament |
| `blog-legacy/` (carpeta raíz) | Contiene admin.php, index.php, post.php — aplicación PHP plana anterior a Laravel. No integrada. | Eliminar (legacy completo) |

---

## 10. SERVICIOS DE NEGOCIO — MÉTODOS PÚBLICOS

### DollarRateService

| Método | Parámetros | Qué hace |
|--------|-----------|----------|
| `getCurrentRate()` | — | Retorna tasa USD activa desde caché/DB |
| `getCurrentEuroRate()` | — | Retorna tasa EUR activa desde caché/DB |
| `fetchAndStore()` | — | Consulta DolarAPI → fallback PyDolarVe, guarda en DB, retorna array resultado |
| `fetchAndStoreEuro()` | — | Igual para EUR |
| `propagateRateToTenants(rate)` | float $rate | Actualiza settings.engine_settings.dollar_rate en todos los tenants activos |
| `propagateEuroRateToTenants(rate)` | float $rate | Igual para EUR |

### MenuService

| Método | Parámetros | Qué hace |
|--------|-----------|----------|
| `getMenu(tenantId)` | int | Lee menu.json del tenant, retorna estructura completa |
| `getCategories(tenantId)` | int | Lista de categorías |
| `getCategory(tenantId, catId)` | int, string | Categoría con sus items |
| `createCategory(tenantId, data)` | int, array | Agrega categoría a menu.json |
| `updateCategory(tenantId, catId, data)` | int, string, array | Modifica categoría |
| `deleteCategory(tenantId, catId)` | int, string | Elimina categoría e items |
| `createItem(tenantId, catId, data)` | int, string, array | Agrega item a categoría |
| `updateItem(tenantId, catId, itemId, data)` | int, string, string, array | Modifica item |
| `deleteItem(tenantId, catId, itemId)` | int, string, string | Elimina item |
| `countItems(tenantId)` | int | Total de items en el menú |
| `limits(planId)` | int | Retorna {items: N, photos: N} según plan |

### OrderService

| Método | Parámetros | Qué hace |
|--------|-----------|----------|
| `generateId(tenantId)` | int | Crea ID único SC-XXXXXX |
| `generate(tenant, customer, items)` | Tenant, array, array | Construye objeto orden completo |
| `save(tenantId, order)` | int, array | Persiste a JSON en storage |

### ComandaService

| Método | Parámetros | Qué hace |
|--------|-----------|----------|
| `generateId(tenantId)` | int | Crea ID único SF-XXXXXX |
| `generate(tenant, customerName, modalidad, items)` | Tenant, string, string, array | Construye comanda con modalidad (sitio/llevar/delivery) |
| `save(tenantId, comanda)` | int, array | Persiste a JSON en storage |

### BusinessHoursService

| Método | Parámetros | Qué hace |
|--------|-----------|----------|
| `isOpen(tenant)` | Tenant | Evalúa horarios vs hora actual VET (America/Caracas) |
| `getNextOpenTime(tenant)` | Tenant | Retorna string humanizado del próximo horario de apertura |

### QRService

| Método | Parámetros | Qué hace |
|--------|-----------|----------|
| `getTrackingShortlink(tenantId)` | int | Retorna URL shortlink determinístico del tenant |
| `generateQR(tenantId, size)` | int, int | Retorna SVG del QR apuntando al shortlink |
| `generateQRPNG(tenantId, size)` | int, int | Retorna binary PNG del QR |
| `verifyUniqueCode(tenantId, code)` | int, string | Valida que el código hash corresponde al tenant |

### WhatsappMessageBuilder

| Método | Parámetros | Qué hace |
|--------|-----------|----------|
| `build(order)` | array | Formatea orden/comanda como texto plano para WhatsApp |
| `url(message, waNumber)` | string, string | Genera URL wa.me con mensaje encodado |

### ImageUploadService

| Método | Parámetros | Qué hace |
|--------|-----------|----------|
| `process(file, tenantId, type, index)` | UploadedFile, int, string, int | Redimensiona, convierte a WebP 90%, guarda en storage |
| `delete(tenantId, filename)` | int, string | Elimina archivo de storage |

### PrelineThemeService

| Método | Parámetros | Qué hace |
|--------|-----------|----------|
| `getAllThemes()` | — | Array con los 35 slugs de temas |
| `getThemesByPlan(planId)` | int | Temas disponibles para el plan (actualmente todos) |
| `isValidTheme(theme, planId)` | string, int | Valida que el tema esté disponible para el plan |
| `getValidationRule(planId)` | int | String para regla de validación Laravel: `in:theme1,...` |
| `getDefaultTheme()` | — | Retorna 'default' |
| `getThemeForSegment(segment)` | string | Mapea segmento de negocio → tema recomendado |

### HealthCheckService

| Método | Parámetros | Qué hace |
|--------|-----------|----------|
| `checkAll()` | — | Ejecuta todos los checks, retorna array de {key, label, status, latency_ms, message} |
| `checkDatabase()` | — | Latencia de conexión MySQL |
| `checkBcvApi()` | — | Disponibilidad DolarAPI/PyDolarVe |
| `checkAnthropicApi()` | — | Ping a Anthropic API |
| `checkGeminiApi()` | — | Ping a Google Gemini API |
| `checkStorageDisk()` | — | Read/write en storage |
| `checkQueueJobs()` | — | Jobs pendientes en cola |
| `checkFailedJobs()` | — | Jobs fallidos acumulados |
| `checkTenantsExpiringSoon()` | — | Tenants con suscripción ≤30 días |
| `checkDiskSpace()` | — | % disco libre |
| `checkLogErrors24h()` | — | Errores en laravel.log últimas 24h |

### AIServiceSwitcher

| Método | Parámetros | Qué hace |
|--------|-----------|----------|
| `getProvider()` | — | Retorna BytezProvider o GeminiProvider según env AI_PROVIDER |

### BytezProvider

| Método | Parámetros | Qué hace |
|--------|-----------|----------|
| `ask(question, context)` | string, string | Llama Qwen3-8B vía api.bytez.com, retorna respuesta ≤200 tokens en español |

### GeminiProvider

| Método | Parámetros | Qué hace |
|--------|-----------|----------|
| `ask(question, context)` | string, string | Llama Gemini Flash vía Google AI API, retorna ≤3 líneas en español |

### TenantBootstrapCat

| Método | Parámetros | Qué hace |
|--------|-----------|----------|
| `bootstrap(tenant)` | Tenant | Crea catalog.json inicial en storage |
| `addInitialProduct(...)` | variadic | Inserta producto de muestra |

### TenantBootstrapFood

| Método | Parámetros | Qué hace |
|--------|-----------|----------|
| `bootstrap(tenant)` | Tenant | Crea menu.json + carpeta fotos inicial |
| `addInitialCategory(...)` | variadic | Crea categoría de muestra |

### PlatformAnalyticsService

| Método | Parámetros | Qué hace |
|--------|-----------|----------|
| `getSummary(period)` | string '7d'\|'30d'\|'90d' | Agrega métricas globales: pageviews, unique_visitors, whatsapp_clicks, qr_scans |
| `getTrafficSources()` | — | Breakdown de referers de tráfico |

---

## 11. INTEGRACIONES EXTERNAS

| Servicio | Proveedor | Endpoint | Auth | Usado En |
|---------|-----------|----------|------|---------|
| Tasas USD | DolarAPI | `https://ve.dolarapi.com/v1/dolares/oficial` | Ninguna | DollarRateService (primario) |
| Tasas USD/EUR | PyDolarVe | `https://pydolarve.org/api/v1/dollar?monitor=bcv` | Ninguna | DollarRateService (fallback) |
| IA Asistente | Bytez | `https://api.bytez.com/models/v2/{model}` | API Key (header) | BytezProvider |
| IA Asistente | Google Gemini | `https://generativelanguage.googleapis.com/v1beta/...` | API Key (query) | GeminiProvider |
| OAuth | Google | `https://accounts.google.com/o/oauth2/auth` | Client ID + Secret | SocialAuthController |
| QR Codes | simplesoftwareio/simple-qrcode | Librería local | — | QRService |
| Imágenes | intervention/image v3 | Librería local | — | ImageUploadService |
| Email | SMTP configurable | Tabla mail_settings | Contraseña cifrada | Todas las Notifications |

---

## 12. MIGRACIONES — TABLAS Y COLUMNAS

### users
`id, name, email, email_verified_at, password, remember_token, google_id, avatar, role, vendor_profile, vendor_sales_month, vendor_total_earned, pago_movil_phone, pago_movil_cedula, pago_movil_bank, referral_code, is_admin`

### tenants
`id, user_id, plan_id, subdomain, base_domain, custom_domain, domain_verified, business_name, business_segment, slogan, description, phone, whatsapp_sales, whatsapp_support, email, address, city, country, business_hours, is_open, edit_pin, currency_display, color_palette_id, meta_title, meta_description, meta_keywords, status, trial_ends_at, subscription_ends_at, plan_activated_at, settings, whatsapp_active, is_demo, demo_product`

### plans
`id, slug, blueprint, name, price_usd, products_limit, services_limit, images_limit, color_palettes, social_networks_limit, show_dollar_rate, show_header_top, show_about_section, show_payment_methods, show_faq, show_cta_special, analytics_level, seo_level, whatsapp_numbers, whatsapp_hour_filter`

### products
`id, tenant_id, name, description, price_usd, compare_price_usd, price_bs, image_filename, image_url, position, is_active, is_featured, badge, category_name, subcategory_name`

### product_images
`id, product_id, image_filename, position`

### services
`id, tenant_id, name, description, icon_name, image_filename, overlay_text, cta_text, cta_link, position, is_active`

### tenant_customization
`id, tenant_id, logo_filename, hero_main_filename, hero_secondary_filename, hero_tertiary_filename, hero_image_4_filename, hero_image_5_filename, hero_layout, theme_slug, social_networks, payment_methods, faq_items, cta_title, cta_subtitle, cta_button_text, cta_button_link, visual_effects, content_blocks, about_text, about_image_filename, header_message`

### analytics_events
`id, tenant_id, event_type, reference_type, reference_id, user_ip, user_agent, referer, event_date, event_hour`
_Nota: sin `updated_at` (UPDATED_AT = null en model)_

### invoices
`id, tenant_id, invoice_number, amount_usd, currency, payment_method, payment_channel, payment_reference, payment_date, pdf_filename, receipt_path, status, admin_notes, reviewed_at, reviewed_by, period_start, period_end`

### dollar_rates
`id, rate, source, currency_type, effective_from, effective_until, is_active`

### color_palettes
`id, name, slug, primary_color, secondary_color, accent_color, background_color, text_color, min_plan_id, category`

### tenant_branches
`id, tenant_id, name, address, is_active`

### ai_docs
`id, slug, title, product, content, source_file`

### ai_chat_logs
`id, tenant_id, product, question, answer, helpful`

### blog_posts
`id, blog_category_id, slug, title, excerpt, content, image_url, featured_image, author, avatar_url, read_time, featured, status, tags, views, published_at`

### blog_categories
`id, name, slug, color, sort_order`

### support_tickets
`id, tenant_id, user_id, subject, message, status, category, admin_reply, ai_suggestion, replied_at`

### domains
`id, tenant_id, domain, tld, type, managed_by, registrar, registrar_account, registrar_login, auth_code, registered_at, expires_at, last_renewed_at, auto_renew, transfer_lock, cost_price, sale_price, billing_cycle, dns_status, dns_verified_at, dns_expected_ip, nameservers, status, notes`

### domain_events
`id, domain_id, performed_by, type, payload, created_at`

### media
`id, file_name, size, name`

### company_settings
`id, company_name, rif, address, phone, whatsapp_support, email_support, website, instagram, twitter, logo_path`

### mail_settings
`id, driver, host, port, encryption, username, password, from_address, from_name, is_active`

### landing_sections
`id, section_key, section_label, content, is_active, sort_order`
