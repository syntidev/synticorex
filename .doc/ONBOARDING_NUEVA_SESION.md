# 🧠 GUÍA DE CONTEXTO - NUEVA SESIÓN SYNTIWEB
**Para:** Claude (nueva sesión)  
**Proyecto:** SYNTIweb — Plataforma multitenant de landing pages para negocios venezolanos  
**Fecha:** 20 Feb 2026 | **Commit:** daf1ac9

---

## 👤 QUIÉN SOY

Soy el arquitecto de SYNTIweb. No soy programador tradicional — soy pensador sistémico y ejecutor decidido. Llevo 5 meses sin trabajo apostando todo a este producto. Trabajo desde Venezuela con VPN superando limitaciones. Aprendo rápido y resuelvo problemas. El tiempo corre — tengo APIs y planes pagados con 30 días corriendo.

---

## 🎯 QUÉ ES SYNTIWEB

Plataforma SaaS multitenant que genera landing pages dinámicas para negocios venezolanos pequeños. Un negocio escanea un QR → ve su vitrina digital → contacta por WhatsApp. El dueño gestiona todo desde un dashboard con PIN.

**Stack:** Laravel 12, PHP 8.3, MySQL, Blade puro, CSS puro, JS vanilla, Intervention Image v3.

**Repositorio:** `C:\laragon\www\synticorex`  
**URL local:** `http://127.0.0.1:8000`  
**Tenant prueba:** `http://127.0.0.1:8000/techstart` (PIN: 1234)  
**Dashboard:** `http://127.0.0.1:8000/tenant/1/dashboard`

---

## 🤝 CÓMO TRABAJAMOS

### El flujo de sesión:
1. **Yo traigo contexto** → Leo NEXT_SESSION.md del proyecto
2. **Tú lees archivos clave** → Project knowledge en Claude.ai
3. **Definimos objetivo** → Una cosa concreta por sesión
4. **Generamos prompts** → Para Sonnet (features) o Haiku (fixes)
5. **Yo ejecuto en Cursor/VS Code** → Pego el prompt al agente
6. **Traigo resultado** → Output del agente o screenshot
7. **Tú evalúas y ajustas** → Siguiente prompt o fix
8. **Commit al terminar** → Siempre con mensaje descriptivo

### Herramientas que uso:
- **Cursor IDE** con agentes AI integrados (Sonnet, Haiku)
- **VS Code** como respaldo
- **Laragon** como servidor local (Apache en 8080)
- **Git/GitHub** para versionado
- **Claude.ai** (tú) como arquitecto y consultor
- **PowerShell** para comandos del sistema

### Agentes AI que uso en Cursor:
- **Claude Sonnet** → Features medianas y grandes (CRUD, controllers, vistas completas)
- **Claude Haiku** → Fixes rápidos, cambios de una línea, debug
- **Copilot Pro** → Autocompletado mientras escribo

### Cómo formulo prompts para los agentes:
```
[Contexto breve de qué archivo tocar]
[Qué hacer exactamente con código de ejemplo]
[Qué NO tocar]
[Qué retornar/crear]
```

Los prompts van al agente en Cursor. El agente ejecuta y me da resultado. Yo te traigo el resultado aquí.

---

## 📁 ESTRUCTURA DEL PROYECTO

```
C:\laragon\www\synticorex\
├── app/
│   ├── Http/Controllers/
│   │   ├── TenantRendererController.php  ← Landing page
│   │   ├── DashboardController.php       ← Dashboard 6 tabs
│   │   └── ImageUploadController.php     ← Upload WebP
│   └── Services/
│       ├── DollarRateService.php         ← API BCV
│       └── ImageUploadService.php        ← WebP resize
├── resources/views/
│   ├── landing/
│   │   ├── base.blade.php               ← Layout + CURRENCY_MODE JS
│   │   └── partials/ (11 archivos)
│   └── dashboard/
│       └── index.blade.php              ← Dashboard 6 tabs (~2000 líneas)
├── routes/web.php                       ← Todas las rutas
├── storage/app/public/tenants/{id}/     ← Imágenes WebP
├── PROGRESS.md                          ← Progreso del proyecto
├── NEXT_SESSION.md                      ← Contexto para próxima sesión
└── .doc/
    └── dashboard.php                    ← Dashboard visual de progreso
```

---

## 📊 ESTADO ACTUAL (20 Feb 2026)

```
SEMANA 1: ████████████████████ 100% ✅ Fundación
SEMANA 2: ████████████████████ 100% ✅ Template + Motor
SEMANA 3: ███████████████████░  95% ✅ Dashboard Admin
SEMANA 4: ░░░░░░░░░░░░░░░░░░░░   0% 🔥 EN CURSO
```

### Lo que funciona HOY:
- Landing page dinámica multitenant
- Sistema de paletas de color (CSS variables)
- Sistema de moneda: REF / Bs. / Ambos / Ocultar
- Panel flotante: Alt+S → PIN → Radar + QR + Toggle estado
- Dashboard completo 6 tabs:
  - Info: edición datos negocio
  - Productos: CRUD + límites por plan
  - Servicios: CRUD + límites por plan  
  - Diseño: selector paletas + upload logo/hero
  - Analytics: KPIs + tasa dólar
  - Config: moneda + PIN + info plan
- Upload imágenes → WebP automático
- Tasa BCV en tiempo real (fallback 36.50)

### Pendiente inmediato:
1. Tenants demo: pizzería, barbería, boutique
2. Sistema onboarding nuevos clientes
3. Panel admin para gestionar tenants
4. Analytics real (tracking eventos JS)
5. Deploy a producción

---

## 🏗️ DECISIONES TÉCNICAS TOMADAS (NO cambiar)

1. **Sin Tailwind en landing** → CSS puro, más control
2. **JS vanilla** → Sin dependencias, carga ultrarrápida  
3. **WebP forzado** → Todas las imágenes se convierten
4. **USD como base** → Bs. se calcula en runtime
5. **REF como símbolo** → Legal en Venezuela 2026 ($ prohibido)
6. **CURRENCY_MODE en JS** → Resuelve conflicto PHP/JS
7. **Panel flotante ≠ Dashboard** → Acceso rápido vs gestión completa
8. **PIN en lugar de contraseña** → UX simple para dueño

---

## 💰 CONTEXTO DE NEGOCIO

- **Planes:** OPORTUNIDAD (6 prod, 3 serv) / CRECIMIENTO (18/6) / VISIÓN (40/15)
- **Moneda:** Siempre USD base, Bs. calculado en runtime
- **Mercado:** Venezuela — negocios pequeños, dolarizado de facto
- **Estrategia venta:** Demo en vivo + visita presencial
- **Objetivo:** 3 primeros clientes esta semana

---

## 🚨 BUGS CONOCIDOS

- DollarRate API: VPN a veces bloquea → fallback 36.50 activo
- CRLF warnings en git → Windows, no afecta funcionalidad
- Tab Config: layout visual mejorable (pendiente polish)

---

## 📋 PROMPT EXACTO PARA EMPEZAR

Copia y pega esto al inicio de la nueva sesión:

```
Hola Claude. Soy el arquitecto de SYNTIweb.

Lee el archivo NEXT_SESSION.md del proyecto (está en la 
base de conocimiento) para entender el contexto completo.

ESTADO HOY:
- Semana 1 ✅ 100% | Semana 2 ✅ 100% | Semana 3 ✅ 95%
- Dashboard completo con 6 tabs funcional
- Commit actual: daf1ac9
- URL: http://127.0.0.1:8000/techstart

CÓMO TRABAJAMOS:
- Tú eres mi arquitecto y consultor
- Yo ejecuto prompts en Cursor (agente Sonnet/Haiku)
- Traigo resultados/screenshots aquí
- Hacemos commits al finalizar cada feature

OBJETIVO DE HOY:
[ESCRIBE AQUÍ LO QUE QUIERES HACER]

¿Listo para arrancar?
```

---

## 🔑 DATOS CLAVE

| Item | Valor |
|------|-------|
| Tenant prueba | techstart (ID: 1) |
| PIN acceso | 1234 |
| Landing URL | http://127.0.0.1:8000/techstart |
| Dashboard URL | http://127.0.0.1:8000/tenant/1/dashboard |
| API dólar | ve.dolarapi.com/v1/dolares/oficial |
| Fallback tasa | 36.50 Bs/USD |
| Puerto Laragon | 8080 (Apache) |
| PHP | 8.3.29 |
| Laravel | 12 |

---

**¡A vender! 🚀🇻🇪**
