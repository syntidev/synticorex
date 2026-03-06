# SYNTIWEB - Instrucciones para GitHub Copilot

## PROYECTO
SaaS multitenant Laravel 12. Landing pages dinámicas para negocios venezolanos.
Ruta local: C:\laragon\www\synticorex\

## STACK REAL
Laravel 12.51, PHP 8.3, MySQL, Preline 4.1.2, Tailwind 4.2, Alpine.js 3.4.2, Vite 7, Intervention Image 3.11, QR: simplesoftwareio/simple-qrcode, Blade puro, JS vanilla.

## REGLAS CRÍTICAS
- declare(strict_types=1) en TODO archivo PHP
- NUNCA instalar FlyonUI ni DaisyUI — fueron removidos, stack UI es Preline 4.1.2
- NUNCA bajar versión de Preline (4.1.2) ni Tailwind (4.2)
- NUNCA usar asset() → siempre @vite()
- NUNCA lógica compleja en Blade → va en Model/Controller
- NUNCA usar clases DaisyUI: btn, card, modal, badge, drawer, collapse, dropdown, join, stat, toast, divider, menu, alert — usar Tailwind utilitario puro
- Tenant isolation: siempre filtrar por tenant_id
- Early return pattern obligatorio
- Eager loading obligatorio (evitar N+1)
- Imágenes: max 800px, convertir a WebP

## COMPONENTES INTERACTIVOS — SOLO PRELINE
- Modal/Overlay → hs-overlay + data-hs-overlay
- Accordion/Collapse → hs-accordion + hs-accordion-toggle + hs-accordion-content
- Dropdown → hs-dropdown + hs-dropdown-toggle + hs-dropdown-menu
- Tabs → data-hs-tab + hs-tab-active:
- Collapse navbar → hs-collapse + hs-collapse-toggle + data-hs-collapse
- Alpine.js (x-data, x-show, x-on) es compatible con Preline — mantener intacto

## MULTITENANCY
- Un tenant = un subdominio O un dominio personalizado
- Detección por subdomain o custom_domain en middleware IdentifyTenant
- Storage: storage/tenants/{tenant_id}/

## PLANES
- Plan 1 OPORTUNIDAD $99/año: 6 productos, 3 servicios, 10 paletas
- Plan 2 CRECIMIENTO $149/año: 12 productos, 6 servicios, 17 paletas
- Plan 3 VISIÓN $199/año: 18 productos (slider 3 fotos c/u), 9 servicios, 17 paletas + custom infinito

## SECCIONES POR PLAN
- Plan 1: hero, products, services, contact, payment_methods, cta, footer
- Plan 2: + about, testimonials
- Plan 3: + faq, branches
- Lógica en: app/Models/TenantCustomization.php → canAccessSection()
- Secciones ordenables y con visibilidad toggle por tenant
- Variantes por sección: hero(fullscreen), products(grid3), services(cards/spotlight), about(split), faq(accordion), cta(centered)

## TEMAS
- Sistema de temas: CSS variables --brand-* definidas en resources/css/app.css
- --brand-50, --brand-500, --brand-600, --brand-700 son las variables base
- NO usar data-theme de DaisyUI/FlyonUI — sistema eliminado
- Custom palette: usar CSS variables --brand-* directamente

## SISTEMA MONEDA
- Símbolo REF (no $)
- Modos: reference_only / bolivares_only / both_toggle / hidden
- CURRENCY_MODE resuelto en base.blade.php

## DASHBOARD
- Activación: Alt+S desktop / long press móvil
- PIN de acceso
- 6 tabs: Info, Productos, Servicios, Diseño, Analytics, Config

## ARCHIVOS CLAVE
- TenantRendererController.php → renderiza landing
- DashboardController.php → 6 tabs CRUD
- resources/views/landing/base.blade.php → layout principal
- resources/views/dashboard/index.blade.php → dashboard ~2000 líneas

## PROHIBIDO
- Node.js en servidor (solo build local)
- exec() o shell_exec() en producción
- {!! !!} salvo Schema.org generado internamente
- eval() o new Function() en JS
- Cualquier clase DaisyUI/FlyonUI en HTML
- NUNCA usar landing/partials/ — carpeta eliminada permanentemente
- SIEMPRE usar landing/sections/ para todo blade de landing
- @include siempre como @include('landing.sections.NOMBRE')