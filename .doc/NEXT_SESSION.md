# 🚀 PRÓXIMA SESIÓN - SYNTIWEB
**Última actualización:** 2026-02-22
**Commit actual:** `4fb3632`
**Rama:** `feature/limpieza-frankenstein`
**Progreso:** S1 ✅ 100% | S2 ✅ 100% | S3 ✅ 98% | S4 🔥 En curso

---

## 🎯 ESTADO REAL HOY

### Completado en esta sesión:
- ✅ Auditoría técnica con GPT-5.2-Codex (solo lectura)
- ✅ FlyonUI v2.4.1 NPM confirmado como sistema de temas
- ✅ @vite en dashboard head → CSS compilado cargando
- ✅ Tab Diseño: 17 cards con colores reales hardcodeados
- ✅ Preview: primary, secondary, accent, base100 exactos
- ✅ Checkmark activo funcional, agrupación por categorías
- ✅ Documentación sincronizada

### Hallazgos auditoría (pendiente):
- ⚠️ updatePalette LEGACY en DashboardController → limpiar
- ⚠️ Lista 17 temas duplicada en Controller y View → centralizar
- ⚠️ Rutas /tenant/{id}/... sin middleware autenticación
- ⚠️ old_base.blade.php en repo → eliminar

---

## 🏗️ ARQUITECTURA ACTUAL

### Stack:
- Laravel 12, PHP 8.3, MySQL
- FlyonUI v2.4.1 vía NPM (17 temas)
- Blade puro, CSS puro landing, JS vanilla
- Intervention Image v3 (WebP)
- Vite para compilación

### Tema activo se guarda en:
```
$tenant->settings['engine_settings']['visual']['theme']['flyonui_theme']
```

### Colores reales (fuente: flyonui-theme-explorer.html en .doc/):
```
light:      #570df8 / #f000b8 / #37cdbe / bg:#ffffff  (light)
dark:       #661ae6 / #d926a9 / #1fb2a6 / bg:#2a303c  (dark)
black:      #ffffff / #ffffff / #ffffff / bg:#000000   (dark)
claude:     #da7756 / #a0785a / #e8c9a0 / bg:#f5f0e8  (light)
corporate:  #4b6bfb / #7b92b2 / #67cba0 / bg:#ffffff  (light)
ghibli:     #6b7c5c / #c49a6c / #e8a87c / bg:#faf6f0  (light)
gourmet:    #9b2335 / #d4a76a / #c8a97e / bg:#fdfaf5  (light)
luxury:     #ffffff / #a08740 / #c5a028 / bg:#09090b   (dark)
mintlify:   #0ea474 / #7c3aed / #0ea5e9 / bg:#ffffff  (light)
pastel:     #d1c1f7 / #f7d6c1 / #c1f7d6 / bg:#ffffff  (light)
perplexity: #20b8cd / #1a9ab0 / #15808f / bg:#16191d  (dark)
shadcn:     #18181b / #f4f4f5 / #18181b / bg:#ffffff  (light)
slack:      #4a154b / #1264a3 / #ecb22e / bg:#3f0e40   (dark)
soft:       #6b21a8 / #db2777 / #0891b2 / bg:#ffffff  (light)
spotify:    #1db954 / #1ed760 / #1db954 / bg:#121212  (dark)
valorant:   #ff4655 / #bd3944 / #ff4655 / bg:#0f1923  (dark)
vscode:     #007acc / #6a9955 / #569cd6 / bg:#1e1e1e  (dark)
```

---

## 🔥 PRÓXIMOS PASOS (por impacto)

### Para vender YA:
1. **Tenants demo** — 3 negocios atractivos:
   - Pizzería venezolana → tema `gourmet`
   - Barbería → tema `luxury` o `valorant`
   - Boutique/Tienda → tema `soft` o `pastel`
2. **Flujo landing completo** — sección por sección con FlyonUI

### Limpieza técnica (deuda auditoría):
3. Eliminar updatePalette LEGACY de DashboardController
4. Centralizar lista 17 temas (una sola fuente)
5. Middleware autenticación en rutas tenant
6. Eliminar old_base.blade.php del repo

### Semana 4 features:
7. Analytics real: tracking JS → analytics_events tabla
8. SEO automático: meta tags dinámicos por segmento
9. Sistema onboarding nuevos tenants
10. Panel admin básico (crear/gestionar tenants)

### Producción:
11. Servidor + DNS wildcard + SSL wildcard
12. Cron job tasa dólar
13. APP_ENV=production

---

## 🔑 DATOS CLAVE

| Item | Valor |
|------|-------|
| Tenant prueba | techstart (ID:1) PIN:1234 |
| Landing local | http://127.0.0.1:8000/techstart |
| Dashboard | http://127.0.0.1:8000/tenant/1/dashboard |
| Dashboard progreso | http://synticorex:8080/.doc/dashboard.php |
| Rama activa | feature/limpieza-frankenstein |
| Puerto Laragon | 8080 (Apache) |
| FlyonUI | v2.4.1 NPM |
| PHP | 8.3 | Laravel | 12 |

---

## 💡 REGLAS QUE NO ROMPER

- `@vite()` siempre, NUNCA `asset()` para CSS/JS compilado
- Colores Tab Diseño = hardcodeados (no CSS variables de FlyonUI)
- Tema en landing = `data-theme` en `<html>` de base.blade.php
- Agentes Cursor: Sonnet = features, Haiku = fixes puntuales
- Si agente baja versión Tailwind/FlyonUI → directriz forzada
- Commit SIEMPRE antes de iniciar sesión nueva

---

## 📋 PROMPT PARA NUEVA SESIÓN

```
Hola Claude. Soy el arquitecto de SYNTIweb.
Lee NEXT_SESSION.md (base de conocimiento del proyecto).

ESTADO:
- S1✅ S2✅ S3✅98% S4🔥
- Commit: 4fb3632
- Rama: feature/limpieza-frankenstein
- Tab Diseño: 17 temas FlyonUI colores reales ✅
- Auditoría técnica realizada ✅

FLUJO:
- Tú = arquitecto/consultor
- Yo ejecuto en Cursor (Sonnet features / Haiku fixes)
- Traigo screenshots/resultados aquí
- Commit al terminar cada feature

OBJETIVO HOY: [ESCRIBE AQUÍ]
```

---

**¡A vender! 🚀🇻🇪**