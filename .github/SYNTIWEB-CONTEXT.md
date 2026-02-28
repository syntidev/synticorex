# SYNTIWEB - Contexto del Proyecto

## NEGOCIO
SaaS multitenant Venezuela. Landing pages dinámicas para negocios pequeños.
Cada tenant = subdominio O dominio personalizado propio.
Dueño gestiona todo con PIN desde celular. Contacto por WhatsApp.
Moneda: REF (no $) — mercado dolarizado Venezuela 2026.

## ESTRUCTURA PROYECTO
```
C:\laragon\www\synticorex\
├── app\
│   ├── Http\Controllers\
│   │   ├── TenantRendererController.php  ← renderiza landing
│   │   ├── DashboardController.php       ← 6 tabs CRUD
│   │   └── ImageUploadController.php     ← WebP upload
│   ├── Services\
│   │   ├── DollarRateService.php         ← API BCV + fallback
│   │   └── ImageUploadService.php        ← resize + WebP
│   └── Models\
│       └── Tenant.php                    ← modelo principal
├── resources\views\
│   ├── landing\
│   │   ├── base.blade.php               ← layout + CURRENCY_MODE
│   │   └── partials\                    ← 11 componentes
│   └── dashboard\
│       └── index.blade.php              ← dashboard ~2000 líneas
├── routes\web.php                        ← todas las rutas
└── .github\
    ├── copilot-instructions.md          ← reglas globales
    └── copilot-workspace.yml            ← skills agente
```

## TENANTS DEMO ACTIVOS
- techstart → Plan 3 VISIÓN | PIN: 1234
- pizzería → Plan 2 CRECIMIENTO
- barbería → Plan 1 OPORTUNIDAD

## ESTADO
- S1 Fundación: 100% ✅
- S2 Template: 100% ✅  
- S3 Dashboard: 100% ✅
- S4 Polish: 40% 🔥

## PENDIENTE INMEDIATO
1. Contenido textual demos
2. Imágenes profesionales demos
3. Limpieza legacy updatePalette
4. Middleware autenticación rutas tenant
5. Analytics tracking JS
6. SEO meta tags dinámicos
7. Producción: servidor + DNS + SSL