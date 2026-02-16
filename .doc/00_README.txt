# 📖 README - SYNTIWEB MVP

**Versión:** 2.0  
**Stack:** VS Code + Copilot + Claude API + Continue  
**Timeline:** 4 semanas (160 horas)  
**Inversión:** $60 ($10 Copilot + $50 Claude API)

---

## 🎯 CÓMO USAR ESTA DOCUMENTACIÓN

### ANTES DE EMPEZAR:
1. **Lee primero:** `06_GUIA_HERRAMIENTAS_E_INVERSION_V2.md`
   - Entiende tu stack completo
   - Conoce cuándo usar cada herramienta
   - Configura Continue correctamente

2. **Abre siempre:** `01_ROADMAP_MVP.md`
   - Checklist de tareas diarias
   - Horas estimadas
   - Prioridades

3. **Referencia constante:** `02_MATRIZ_FEATURES_DEFINITIVA.md`
   - Qué features van en cada plan
   - Límites de productos/servicios/imágenes
   - Specs exactas

---

## 📅 GUÍA SEMANAL DETALLADA

### SEMANA 1: FUNDACIÓN (Backend) - 40h

**DÍA 1-2: Database & Auth**
```
Herramienta: Principalmente COPILOT
Archivos: 03_SCHEMA_DATABASE.md

Tareas:
1. Laravel 12 nuevo proyecto
2. Git init + primer commit
3. Migraciones (10 tablas)
4. Modelos Eloquent
5. Seeders (plans, color_palettes)
6. Laravel Breeze

Cómo:
- Copilot Tab autocompleta migraciones
- Para relaciones complejas → Continue + Sonnet
```

**DÍA 3-4: Multitenancy**
```
Herramienta: CONTINUE + SONNET 4.5 (CRÍTICO)
Archivos: 03_SCHEMA_DATABASE.md

Tareas:
1. Middleware IdentifyTenant
2. TenantContentService
3. Testing local multidominio

Prompt Continue:
"Siguiendo SCHEMA_DATABASE.md, crea middleware que:
- Detecte tenant por subdomain o custom_domain
- Cargue tenant en cada request
- Fail 404 si no existe
- Use cache para performance"
```

**DÍA 5: APIs & Services**
```
Herramienta: CONTINUE + SONNET 4.5
Archivos: 02_MATRIZ_FEATURES_DEFINITIVA.md

Tareas:
1. DollarRateService (API BCV)
2. Storage configuration
3. Testing completo

Prompt Continue:
"Crea DollarRateService que:
- Fetch tasa BCV cada hora (cron)
- Cache resultado
- Fallback a última tasa si falla
- Método: convertToBolivares($usd)"
```

**✅ MILESTONE SEMANA 1:**
- [ ] Base de datos poblada
- [ ] Multidominio funcionando en local
- [ ] API dólar conectada
- [ ] 5-10 commits en GitHub

---

### SEMANA 2: TEMPLATE ÚNICO (Frontend) - 40h

**DÍA 1-2: Estructura Base**
```
Herramienta: COPILOT (80%) + CONTINUE (20%)
Archivos: 02_MATRIZ_FEATURES_DEFINITIVA.md

Tareas:
1. master.blade.php estructura
2. Nav responsive
3. Hero section
4. Footer básico

Workflow:
- HTML básico → Copilot Tab
- Lógica condicional por plan → Continue
```

**DÍA 3-4: Secciones Principales**
```
Herramienta: CONTINUE + SONNET 4.5
Archivos: 02_MATRIZ_FEATURES_DEFINITIVA.md

Tareas:
1. Sección Servicios (grid condicional)
2. Sección Productos (catálogo dinámico)

Prompt crítico Continue:
"Crea sección productos que:
- Plan OPORTUNIDAD: 6 productos
- Plan CRECIMIENTO: 18 productos
- Plan VISIÓN: 40 productos + lazy loading
- Grid responsive 3 cols desktop, 1 col mobile
- Precio USD/Bs según preferencia usuario
- Botón WhatsApp con mensaje pre-filled
- @foreach pero con límite según plan"
```

**DÍA 5: Secciones Extras & CSS**
```
Herramienta: COPILOT (mayoría)

Tareas:
1. Header Top, Acerca de, Medios Pago, FAQ, CTA
2. Sistema de paletas CSS dinámico
3. Testing responsive

Workflow:
- HTML sections → Copilot
- CSS variables por paleta → Continue si se complica
```

**✅ MILESTONE SEMANA 2:**
- [ ] Landing completa con todas las secciones
- [ ] Responsive perfecto
- [ ] Renderizado condicional funcionando
- [ ] 3 planes visualizables

---

### SEMANA 3: DASHBOARD FLOTANTE (Fullstack) - 40h

**DÍA 1-2: Estructura Dashboard**
```
Herramienta: COPILOT + CONTINUE
Archivos: 04_DASHBOARD_SPECS.md

Tareas:
1. Side drawer HTML/CSS
2. Sistema activación (Alt+S / long press)
3. Modal PIN autenticación
4. 6 tabs estructura

Distribución:
- HTML/CSS básico → Copilot
- JS activación → Continue + Sonnet
```

**DÍA 3-4: CRUD Completo**
```
Herramienta: CONTINUE + SONNET 4.5
Archivos: 04_DASHBOARD_SPECS.md, 02_MATRIZ_FEATURES.md

Tareas:
1. Tab Productos: CRUD completo
2. Tab Servicios: CRUD completo
3. Tab Info: Form básico

Prompt Continue:
"Crea CRUD productos para dashboard:
Frontend (Blade):
- Lista productos con edit/delete
- Form add/edit con validación JS
- Preview de imagen
- Submit AJAX

Backend (Controller):
- Validar según plan_id (6/18/40 límite)
- Store/Update/Delete
- JSON response
- Proteger con auth"
```

**DÍA 5: Upload & Processing**
```
Herramienta: CONTINUE + SONNET 4.5
Archivos: 02_MATRIZ_FEATURES.md

Tareas:
1. Drag & drop upload
2. Image processing (WebP)
3. Tab Diseño (selector paleta)
4. Tab Analytics (visualización)

Prompt Continue:
"Sistema upload imágenes:
- Drag & drop con preview
- Validar: max 2MB, JPG/PNG
- Backend: resize 800px, convert WebP
- Guardar: /storage/tenants/{id}/product_01.webp
- Eliminar anterior si existe
- Usar Intervention Image"
```

**✅ MILESTONE SEMANA 3:**
- [ ] Dashboard activable y funcional
- [ ] CRUD productos/servicios completo
- [ ] Upload imágenes optimizado
- [ ] Cambios en tiempo real (AJAX)

---

### SEMANA 4: ANALYTICS & POLISH (Optimización) - 40h

**DÍA 1-2: System Analytics**
```
Herramienta: COPILOT + CONTINUE (mix)
Archivos: 02_MATRIZ_FEATURES.md

Tareas:
1. Tracking eventos JS
2. Store en analytics_events
3. Dashboard analytics por plan

Distribución:
- JS tracking básico → Copilot
- Agregaciones complejas → Continue + Sonnet
- Analytics Plan VISIÓN → Continue + Opus 4.5
```

**DÍA 3-4: SEO Automático**
```
Herramienta: CONTINUE + SONNET 4.5 (Plan 1-2)
           + CONTINUE + OPUS 4.5 (Plan 3)
Archivos: 05_SEO_AUTOMATICO.md

Tareas:
1. SEO básico (Plan OPORTUNIDAD)
2. SEO por segmento (Plan CRECIMIENTO)
3. SEO profundo + Schema.org (Plan VISIÓN)

Prompt para Opus 4.5:
"Siguiendo 05_SEO_AUTOMATICO.md, implementa:
- SeoService completo
- Detección automática de segmento negocio
- Schema.org: LocalBusiness, Product, FAQ
- Templates por segmento (10 tipos)
- Integración en master.blade.php
- 100% automático, sin input usuario"
```

**DÍA 5: Testing & Documentation**
```
Herramienta: CONTINUE + OPUS 4.5
Archivos: 01_ROADMAP_MVP.md

Tareas:
1. Testing E2E completo
2. Performance optimization
3. Checklist pre-lanzamiento
4. Documentación usuario

Prompt Continue + Opus:
"Testing E2E para Syntiweb:
- Flujo completo: registro → dashboard → edición → visualización
- Probar 3 planes diferentes
- Edge cases: límites productos, upload fallido, etc.
- Documentar bugs encontrados
- Sugerir fixes prioritarios"
```

**✅ MILESTONE SEMANA 4:**
- [ ] Analytics completo por plan
- [ ] SEO automatizado funcionando
- [ ] Performance optimizado (<2s carga)
- [ ] MVP LISTO PARA BETA 🚀

---

## 🛠️ WORKFLOW DIARIO ESTÁNDAR

### INICIO DEL DÍA (8:00-8:30am):
```
1. Git pull (si trabajas de múltiples lugares)
2. Abrir VS Code en proyecto
3. Verificar Copilot activo (✓ en status bar)
4. Abrir 01_ROADMAP_MVP.md
5. Identificar tareas del día
6. Crear branch: git checkout -b feature/nombre-tarea
```

### DESARROLLO (8:30am-6:00pm):
```
Para cada tarea:

PASO 1: ¿Es simple o compleja?
- Simple (HTML, CSS básico, rutas) → Copilot
- Compleja (lógica, servicios, APIs) → Continue

PASO 2: Si usas Copilot
- Escribe comentario descriptivo
- Tab para aceptar
- Ajusta manualmente si necesario

PASO 3: Si usas Continue
- Cmd+L para chat
- Prompt claro con archivo de referencia
- Ej: "Siguiendo SCHEMA_DATABASE.md, crea..."
- Revisa código generado
- Apply y prueba

PASO 4: Commit frecuente
git add .
git commit -m "feat: descripción clara"

PASO 5: Testing manual
- php artisan serve
- Prueba en navegador
- Fix bugs con Copilot
```

### FIN DEL DÍA (6:00-6:30pm):
```
1. Commit final si hay cambios
2. Git push origin feature/nombre-tarea
3. Merge a develop si todo funciona
4. Actualizar checklist en ROADMAP
5. Planear mañana (5 min)
```

---

## 🚨 TROUBLESHOOTING COMÚN

### "Copilot no sugiere nada"
```
✅ Verifica que está activo (status bar)
✅ Escribe comentario más descriptivo
✅ Intenta con Cmd+\ para forzar sugerencia
✅ Si persiste → Usa Continue
```

### "Continue da respuesta incorrecta"
```
✅ Verifica que referenciaste archivo correcto (@filename)
✅ Haz prompt más específico
✅ Si Sonnet falla 2+ veces → Cambia a Opus
✅ Copia error exacto en nuevo prompt
```

### "Me quedé atascado >1 hora"
```
✅ PARA - No desperdicies tiempo
✅ Usa Continue + Sonnet (si usabas Copilot)
✅ Usa Continue + Opus (si usabas Sonnet)
✅ Busca en docs Laravel si es framework
✅ Si >3 horas atascado → Replantea enfoque
```

### "Gastando muchos tokens API"
```
❌ No uses Continue para tareas que Copilot puede hacer
❌ No regeneres respuestas 5+ veces (ajusta prompt)
❌ No uses Opus para lo que Sonnet puede
✅ Prompts claros y específicos
✅ Reutiliza código que ya funciona
```

---

## 📚 ORDEN DE LECTURA DE DOCS

### PRIMERA VEZ (hoy):
```
1. Este README (10 min)
2. 06_GUIA_HERRAMIENTAS_E_INVERSION_V2.md (30 min)
3. 01_ROADMAP_MVP.md (20 min)
4. Hojear otros docs para familiarizarte (20 min)

Total: 1h 20min de lectura antes de codificar
```

### DURANTE DESARROLLO:
```
Semana 1: 03_SCHEMA_DATABASE.md (referencia constante)
Semana 2: 02_MATRIZ_FEATURES_DEFINITIVA.md (abierto siempre)
Semana 3: 04_DASHBOARD_SPECS.md (guía detallada)
Semana 4: 05_SEO_AUTOMATICO.md (implementación exacta)
```

---

## 🎯 CHECKLIST INICIAL (ANTES DE CODIFICAR)

### Setup herramientas:
- [ ] VS Code instalado
- [ ] Copilot activo (renovado $10)
- [ ] Claude API con $50 crédito
- [ ] Continue instalado
- [ ] Continue configurado (config.json)
- [ ] Git configurado (user.name, user.email)
- [ ] Cuenta GitHub lista

### Setup proyecto:
- [ ] Carpeta .doc/ creada
- [ ] Todos los .md en .doc/
- [ ] Laravel 12 requirements verificados (PHP 8.2, Composer)
- [ ] Editor config listo (.editorconfig)

### Mental:
- [ ] Leíste toda la documentación
- [ ] Entiendes cuándo usar cada herramienta
- [ ] Tienes claro el objetivo del MVP
- [ ] Sabes que vas a completarlo en 4 semanas
- [ ] Confianza en el proceso ✅

---

## 💪 RECORDATORIOS IMPORTANTES

### 1. NO BUSQUES MÁS HERRAMIENTAS
Ya tienes todo lo necesario. Más herramientas = más confusión.

### 2. CONFÍA EN EL ROADMAP
160 horas están mapeadas. Sigue el plan, no improvises orden.

### 3. COMMITS FRECUENTES
Git commit cada feature que funciona. Nunca >2 horas sin commit.

### 4. TESTING MANUAL DIARIO
No acumules features sin probar. Prueba cada día lo que hiciste.

### 5. USA LA HERRAMIENTA CORRECTA
Copilot para rutina, Continue para complejidad. NO al revés.

### 6. PIDE AYUDA SI TE ATASCAS
>3 horas en mismo problema = red flag. Cambia enfoque o herramienta.

### 7. MVP ≠ PERFECTO
Funcional > Bonito. Lanza rápido, mejora después.

---

## 🚀 MENSAJE FINAL

Tienes en tus manos:
- ✅ Documentación completa y detallada
- ✅ Herramientas correctas ($60 bien invertidos)
- ✅ Plan de 4 semanas paso a paso
- ✅ Guía de cuándo usar qué modelo
- ✅ Troubleshooting común cubierto

**Solo falta ejecutar.**

No dudes, no busques más alternativas, no cambies el plan.

**ABRE VS CODE Y EMPIEZA.**

Mañana 8AM:
```
git init
git commit -m "Initial commit - Let's build this"
```

---

**¡ADELANTE, CAMPEÓN! 🔥**

*Última actualización: 2026-02-16*
*Versión: 2.0 (Definitiva para tu realidad)*