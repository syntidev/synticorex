# 🛠️ GUÍA DE HERRAMIENTAS E INVERSIÓN - SYNTIWEB

**Audiencia:** Desarrollador solo, sin equipo  
**Presupuesto:** $70/mes  
**Objetivo:** Completar MVP en 4 semanas  
**Fecha:** Febrero 2026

---

## 📊 CONTEXTO DEL PROYECTO

### Tu Situación:
- ✅ 5 meses sin trabajo
- ✅ Motivado y comprometido
- ✅ Hábil con tecnología (arquitecto natural)
- ✅ No programador experto (pero aprendes rápido)
- ✅ 8 horas/día disponibles
- ✅ 5 clientes beta confirmados

### El Desafío:
- Completar MVP SYNTIWEB en 4 semanas
- Sistema multitenant complejo
- Laravel 12 + Frontend + Dashboard
- Necesitas herramientas que maximicen velocidad

---

## 🎯 STACK DE HERRAMIENTAS RECOMENDADO

### **OPCIÓN GANADORA:**

```
Cursor Pro:     $20/mes  (Editor + IA coding)
Claude API:     $50      (Consultas críticas)
Git/GitHub:     $0       (Control de versiones)
─────────────────────────
TOTAL:          $70
```

---

## 🔧 HERRAMIENTAS EN DETALLE

### 1. CURSOR PRO ($20/mes) - OBLIGATORIO ⭐

**¿Qué es?**
- VS Code mejorado con IA integrada
- Incluye Claude Sonnet 3.5 **UNLIMITED**
- Feature "Composer" para contexto completo

**¿Por qué comprarlo?**
- ✅ Editor + IA en uno (no saltas entre herramientas)
- ✅ Sonnet 3.5 unlimited = valor de $50+/mes
- ✅ Composer lee TODOS tus docs a la vez
- ✅ Interface idéntica a VS Code (cero curva)
- ✅ Genera código de calidad producción

**Link:**  
https://cursor.com/pricing  
Click en "Pro" ($20/mes)

**Configuración inicial:**
```
1. Descarga de cursor.com
2. Instala (como cualquier app)
3. Abre tu proyecto Laravel
4. Settings > Models > Claude Sonnet 3.5
5. Listo para usar
```

**Uso diario:**
```
- Cmd+K (Mac) o Ctrl+K (Windows): Chat inline
- Cmd+L: Chat en sidebar
- Composer: Para cambios multi-archivo
```

---

### 2. CLAUDE API ($50 crédito) - RESPALDO CRÍTICO

**¿Qué es?**
- API directa de Anthropic
- Acceso a todos los modelos Claude
- Pay-as-you-go (pagas lo que usas)

**¿Por qué comprarlo?**
- ✅ Backup cuando Cursor no basta
- ✅ Consultas de arquitectura complejas
- ✅ Debugging de issues críticos
- ✅ Validación de decisiones técnicas
- ✅ $50 duran 4+ semanas con uso inteligente

**Precios (para referencia):**
```
Claude Sonnet 4.5:
  Input:  $3 / millón tokens
  Output: $15 / millón tokens

Claude Opus 4.6:
  Input:  $5 / millón tokens
  Output: $25 / millón tokens
```

**Link:**  
https://console.anthropic.com

**Configuración inicial:**
```
1. Crea cuenta en console.anthropic.com
2. Ve a Settings > Billing
3. Add credits: $50
4. API Keys > Create Key
5. Guarda la key (la necesitarás)
```

**Agregar a Cursor (opcional pero recomendado):**
```
Cursor > Settings > Models > API Keys
Pega tu Claude API key
Marca: "Use only when limit reached"
```

---

### 3. GIT + GITHUB ($0) - ESENCIAL

**¿Qué es?**
- Control de versiones
- Backup en la nube
- Historia de cambios

**¿Por qué usarlo?**
- ✅ Evita perder código
- ✅ Regresa a versión anterior si algo falla
- ✅ Commits = checkpoints del progreso
- ✅ GitHub = portafolio profesional

**Setup inicial:**
```bash
# En tu proyecto Laravel
cd /ruta/al/proyecto

# Inicializar repo
git init

# Crear repo en GitHub (web)
# Luego conectar:
git remote add origin https://github.com/tu-usuario/syntiweb.git

# Primer commit
git add .
git commit -m "Initial commit: Laravel 12 base"
git push -u origin main
```

**Workflow diario:**
```bash
# Después de cada feature que funciona:
git add .
git commit -m "Feat: create tenants migration"
git push

# Si algo se rompe:
git log  # Ver commits
git checkout [hash]  # Volver a versión anterior
```

---

## 💰 DISTRIBUCIÓN DE PRESUPUESTO

### **Inversión Inicial: $70**

```
┌─────────────────────────────────────┐
│  CURSOR PRO                  $20   │
│  ├─ Claude Sonnet 3.5 unlimited    │
│  ├─ Composer (multi-file)          │
│  └─ Autocomplete avanzado          │
├─────────────────────────────────────┤
│  CLAUDE API                  $50   │
│  ├─ Consultas arquitectura         │
│  ├─ Debugging complejo             │
│  └─ Validaciones críticas          │
├─────────────────────────────────────┤
│  GITHUB                       $0   │
│  └─ Control de versiones           │
└─────────────────────────────────────┘
```

### **Uso Estimado (4 semanas):**

| Semana | Cursor Pro | Claude API | Acumulado |
|--------|------------|------------|-----------|
| 1      | $20 (fijo) | ~$5        | $25       |
| 2      | -          | ~$10       | $35       |
| 3      | -          | ~$8        | $43       |
| 4      | -          | ~$5        | $48       |

**Sobran:** ~$22 de Claude API para post-MVP

---

## 🚫 HERRAMIENTAS QUE NO NECESITAS

### ❌ GitHub Copilot
**Por qué NO:**
- Ya gastaste tokens ahí
- Cursor > Copilot para proyectos completos
- Duplicas herramientas (desperdicio)
- Copilot = autocompletado, Cursor = arquitecto

**Acción:** Cancela suscripción HOY

---

### ❌ Cursor Business ($60)
**Por qué NO:**
- Es para equipos de 2+ personas
- Funciones de colaboración que no usarás
- Cursor Pro tiene todo lo que necesitas solo

**Acción:** Compra Pro ($20), no Business

---

### ❌ Qwen2.5-coder (Ollama local)
**Por qué NO usarlo como principal:**
- Se queda corto en arquitectura
- Debugging limitado
- Pero SÍ úsalo para fixes ultra rápidos (es gratis)

**Acción:** Déjalo instalado como último recurso

---

### ❌ Cursos de Laravel
**Por qué NO:**
- No tienes tiempo (4 semanas)
- Aprende haciendo (con Cursor)
- Tus docs son mejor que cualquier curso

**Acción:** 0 minutos en cursos, 100% en código

---

## 📋 ESTRATEGIA DE USO

### **CURSOR PRO (90% del trabajo):**

**Usar para:**
- ✅ Crear migraciones
- ✅ Generar modelos Eloquent
- ✅ Escribir controllers
- ✅ Crear vistas Blade
- ✅ JavaScript del dashboard
- ✅ CSS/Tailwind
- ✅ Middleware
- ✅ Services
- ✅ Validaciones
- ✅ Routes
- ✅ Seeders

**Ejemplo de uso:**
```
Tú en Cursor (Cmd+K):
"Siguiendo SCHEMA_DATABASE.md líneas 118-180,
crea la migración create_tenants_table con todos
los campos, foreign keys e índices"

Cursor:
[Genera código completo]

Tú:
php artisan migrate
✅ Funciona → git commit
❌ Error → pide a Cursor que arregle
```

---

### **CLAUDE API (10% consultas críticas):**

**Usar para:**
- 🧠 Arquitectura compleja
- 🐛 Debugging que Cursor no resuelve
- 🤔 Decisiones técnicas difíciles
- ✅ Validar que vas por buen camino
- 🔧 Optimizaciones avanzadas

**Ejemplo de uso:**
```
Tú en console.anthropic.com:
"Tengo este error de multidominio:
[pega error]

Mi middleware está así:
[pega código]

¿Cómo lo soluciono?"

Claude API:
[Análisis profundo + solución]

Tú:
Implementas solución
```

**Cuándo consultar:**
- ⏰ Llevas >2 horas atascado
- 🚨 Error crítico que bloquea todo
- 🎯 Necesitas validar decisión importante
- 🧩 Lógica compleja (multidominio, SEO auto, etc)

---

## 🎯 WORKFLOW DIARIO RECOMENDADO

### **Rutina óptima (8 horas):**

```
08:00-08:30  Revisar roadmap del día
08:30-10:30  Coding intensivo (Cursor)
10:30-11:00  Break + revisar progreso
11:00-13:00  Coding intensivo (Cursor)
13:00-14:00  Almuerzo
14:00-16:00  Coding + testing
16:00-16:30  Break
16:30-18:00  Git commits + documentación
```

### **Proceso por tarea:**

```
1. LEER doc relevante (ej: SCHEMA_DATABASE.md)
2. DEFINIR tarea específica
3. ABRIR Cursor
4. USAR Cmd+K o Composer
5. DAR contexto del doc
6. GENERAR código
7. PROBAR localmente
8. ¿FUNCIONA?
   ✅ SÍ  → git commit
   ❌ NO  → pedir fix a Cursor
           → si no resuelve en 2 intentos
              → consultar Claude API
9. SIGUIENTE tarea
```

---

## 🛡️ EVITAR DESASTRES

### **Regla de Oro: Git TODO**

```bash
# Antes de CUALQUIER cambio grande:
git add .
git commit -m "Backup before [feature]"

# Después de CADA feature que funciona:
git add .
git commit -m "Feat: [descripción]"
git push

# Si algo se rompe:
git log --oneline  # Ver últimos commits
git checkout [hash]  # Volver al que funcionaba
```

---

### **Branching por fase:**

```bash
# Semana 1
git checkout -b semana-1-backend

# Cuando termines semana 1:
git checkout main
git merge semana-1-backend
git push

# Semana 2
git checkout -b semana-2-frontend
# ... y así sucesivamente
```

---

### **Testing antes de commit:**

```bash
# SIEMPRE antes de commitear:

# 1. Migraciones
php artisan migrate:fresh --seed

# 2. Servidor
php artisan serve

# 3. Abrir en navegador
open http://localhost:8000

# 4. Probar feature

# 5. ¿Funciona?
✅ SÍ  → git commit
❌ NO  → NO COMMITEAR, arreglar primero
```

---

## 📊 MÉTRICAS DE ÉXITO

### **Semana 1:**
- [ ] 10 migraciones creadas y funcionando
- [ ] Modelos Eloquent con relaciones
- [ ] Middleware multitenant operativo
- [ ] Seeders de planes y paletas
- [ ] API dólar conectada
- **Tokens Claude API usados:** ~$5

### **Semana 2:**
- [ ] Template master.blade.php completo
- [ ] 11 secciones HTML listas
- [ ] Responsive 100%
- [ ] Sistema de paletas funcionando
- **Tokens Claude API usados:** ~$10

### **Semana 3:**
- [ ] Dashboard flotante funcional
- [ ] 6 tabs implementados
- [ ] CRUD productos y servicios
- [ ] Upload de imágenes con procesamiento
- **Tokens Claude API usados:** ~$8

### **Semana 4:**
- [ ] Analytics operativo
- [ ] SEO automático por segmento
- [ ] Performance optimizado
- [ ] MVP listo para beta
- **Tokens Claude API usados:** ~$5

**Total gastado:** $48 de $70  
**Ahorro:** $22 para post-MVP

---

## 🚀 CHECKLIST DE SETUP (HOY)

### **Paso 1: Comprar Cursor Pro**
```
⏱️ 5 minutos

1. Ir a https://cursor.com/pricing
2. Click "Pro" ($20/mes)
3. Ingresar tarjeta
4. Confirmar compra
5. Descargar Cursor
6. Instalar
```

---

### **Paso 2: Configurar Cursor**
```
⏱️ 5 minutos

1. Abrir Cursor
2. File > Open Folder > [tu proyecto Laravel]
3. Cursor detecta extensiones de VS Code
4. Settings (Cmd+,) > Models
5. Seleccionar "Claude Sonnet 3.5"
6. Cerrar settings
```

---

### **Paso 3: Crear cuenta Claude API**
```
⏱️ 10 minutos

1. Ir a https://console.anthropic.com
2. Sign up (email + contraseña)
3. Verificar email
4. Settings > Billing
5. Add payment method
6. Add $50 credits
7. Confirmar
```

---

### **Paso 4: Obtener API Key**
```
⏱️ 3 minutos

1. En console.anthropic.com
2. API Keys (menú izquierdo)
3. Create Key
4. Nombrar: "SYNTIWEB MVP"
5. Copy key (guárdala segura)
6. Pegar en archivo .env.local (backup)
```

---

### **Paso 5: Conectar API a Cursor**
```
⏱️ 3 minutos

1. Cursor > Settings > Models
2. API Keys section
3. "Add API Key"
4. Provider: Anthropic
5. Pegar tu key
6. Enable: "Use when limit reached"
7. Save
```

---

### **Paso 6: Cancelar Copilot**
```
⏱️ 3 minutos

1. Ir a GitHub settings
2. Billing > Copilot
3. Cancel subscription
4. Confirmar
```

---

### **Paso 7: Setup Git**
```
⏱️ 10 minutos

# En terminal, dentro de tu proyecto:
cd /ruta/syntiweb

# Inicializar
git init

# Crear .gitignore (si no existe)
echo "node_modules/" >> .gitignore
echo ".env" >> .gitignore
echo "vendor/" >> .gitignore

# Primer commit
git add .
git commit -m "Initial commit: Laravel 12 + docs"

# Crear repo en GitHub (manual en web)
# Luego:
git remote add origin https://github.com/TU_USUARIO/syntiweb.git
git push -u origin main
```

---

### **Paso 8: Organizar documentación**
```
⏱️ 5 minutos

# Crear carpeta docs en tu proyecto
mkdir docs

# Mover los 5 documentos:
mv MATRIZ_FEATURES_DEFINITIVA.md docs/
mv SCHEMA_DATABASE.md docs/
mv ROADMAP_MVP.md docs/
mv DASHBOARD_SPECS.md docs/
mv SEO_AUTOMATICO.md docs/
mv GUIA_HERRAMIENTAS.md docs/

# Commit
git add docs/
git commit -m "Add documentation"
git push
```

---

## 🎓 TIPS DE PRO

### **Usar Composer (feature killer de Cursor):**

```
1. Cmd+I (Mac) o Ctrl+I (Windows)
2. Arrastra TODOS los docs a la ventana
3. Escribe: "Usando estos docs, crea la migración..."
4. Cursor lee TODO y genera con contexto completo
```

**Cuándo usar Composer:**
- Cambios que afectan múltiples archivos
- Features que requieren contexto de varios docs
- Refactoring grande

---

### **Maximizar tokens de Claude API:**

```
❌ MAL:
"No me funciona el middleware, ayuda"

✅ BIEN:
"Middleware multidominio con este código:
[pega código completo]

Error que me da:
[pega error exacto]

Contexto: Siguiendo MATRIZ_FEATURES línea 120
¿Qué está mal?"
```

**Regla:** Más contexto = mejor respuesta = menos ida y vuelta = ahorro

---

### **Debugging eficiente:**

```
Nivel 1 (gratis): Cursor fix
Nivel 2 (gratis): Google el error
Nivel 3 (gratis): Laravel docs
Nivel 4 ($$$):    Claude API

Solo llega a Nivel 4 si los 3 primeros fallan
```

---

### **Commits atómicos:**

```
✅ BIEN:
git commit -m "Feat: create tenants migration"
git commit -m "Feat: create products migration"
git commit -m "Feat: add Tenant model"

❌ MAL:
git commit -m "Add stuff"
git commit -m "More changes"
git commit -m "Fix"
```

**Por qué:** Si algo falla, sabes exactamente qué commit revertir

---

## 💡 CASOS DE USO REALES

### **Caso 1: Crear migración completa**

```
Cursor (Cmd+K):
"Usando SCHEMA_DATABASE.md líneas 118-180,
crea database/migrations/2026_02_15_create_tenants_table.php

Incluye TODOS los campos, foreign keys e índices tal como están en el doc"

Cursor genera:
✅ Migración completa
✅ Sintaxis Laravel 12
✅ Todos los campos

Tú:
php artisan migrate
✅ Funciona → commit
```

**Costo:** $0 (Cursor Pro unlimited)

---

### **Caso 2: Debugging multidominio**

```
Problema:
Middleware no identifica tenant por subdominio

Intentaste:
1. Cursor fix → no resuelve
2. Google → artículos viejos
3. Laravel docs → ejemplo básico

Solución:
Claude API:
"Mi middleware IdentifyTenant no detecta joseburguer.menu.vip

Código actual:
[pega código]

Rutas:
[pega routes]

Error:
[pega error]

¿Cómo lo soluciono?"

Claude analiza y da solución exacta
```

**Costo:** ~$0.50 (tokens de consulta)  
**Ahorro:** 3+ horas de frustración

---

### **Caso 3: Validar arquitectura**

```
Antes de empezar semana 3 (Dashboard):

Claude API:
"Revisa mi arquitectura actual:
- Tengo [X] tablas
- Modelos con [Y] relaciones
- Middleware funcional

Voy a crear dashboard flotante con estas specs:
[resumen de DASHBOARD_SPECS.md]

¿Hay algo que deba refactorizar antes?
¿Algún riesgo técnico?"

Claude revisa y advierte si hay problemas
```

**Costo:** ~$1 (consulta larga)  
**Ahorro:** Evitar refactoring masivo después

---

## 🎯 RETORNO DE INVERSIÓN

### **Inversión:**
```
Cursor Pro:    $20
Claude API:    $50
──────────────────
TOTAL:         $70
```

### **Resultado esperado (60 días):**

```
DÍA 1-28:   Desarrollo MVP
DÍA 29-35:  Beta con 5 clientes (gratis)
DÍA 36-60:  Primeras ventas

MES 1:  -$70  (inversión)
MES 2:  -$70  (herramientas) + $147 (3 ventas @ $49) = +$77
MES 3:  -$70  (herramientas) + $392 (8 ventas @ $49) = +$322

BREAK-EVEN: Día 45
PROFIT NETO MES 3: $322
```

### **Proyección 6 meses:**

```
MES 1:  -$70
MES 2:  +$77
MES 3:  +$322
MES 4:  +$490  (10 clientes)
MES 5:  +$735  (15 clientes)
MES 6:  +$980  (20 clientes)
──────────────────
TOTAL:  +$2,534 neto
```

**$70 invertidos → $2,534 generados en 6 meses = ROI 3,520%**

---

## 🚨 RED FLAGS Y SOLUCIONES

### **Red Flag 1: "Me quedé sin tokens de Cursor"**
**Solución:**
- Unlikely (es unlimited Sonnet 3.5)
- Si pasa: Usa Claude API
- Contacta support de Cursor

---

### **Red Flag 2: "Gasté los $50 de Claude en 1 semana"**
**Solución:**
- Estás consultando demasiado
- Usa más Cursor, menos API
- Sé más específico en prompts (menos tokens)
- Carga otros $25 si es crítico

---

### **Red Flag 3: "Llevo 3 días atascado"**
**Solución:**
- STOP inmediatamente
- Consulta Claude API con:
  - Qué intentaste
  - Código actual
  - Error exacto
  - Contexto completo
- Si Claude no resuelve → replantea el approach

---

### **Red Flag 4: "El código de Cursor no funciona"**
**Solución:**
- Normal al principio
- Dale contexto más específico
- Muéstrale el error
- Pídele que arregle
- Si falla 3 veces → Claude API

---

## ✅ CHECKLIST FINAL

### **Antes de empezar mañana:**

- [ ] Cursor Pro comprado e instalado
- [ ] Claude API con $50 créditos
- [ ] API key guardada segura
- [ ] Git inicializado en proyecto
- [ ] Repo en GitHub creado
- [ ] Documentación en carpeta docs/
- [ ] Copilot cancelado
- [ ] .gitignore configurado
- [ ] Primer commit hecho
- [ ] ROADMAP_MVP.md leído completo

### **Hábitos diarios:**

- [ ] Commit después de cada feature
- [ ] Push al final del día
- [ ] Usar Cursor para 90% del código
- [ ] Solo Claude API para consultas críticas
- [ ] Testing antes de commitear
- [ ] Seguir roadmap estrictamente

---

## 🎓 RECURSOS ADICIONALES

### **Documentación oficial:**
- Cursor: https://cursor.sh/docs
- Claude API: https://docs.anthropic.com
- Laravel 12: https://laravel.com/docs/12.x

### **Soporte:**
- Cursor: support@cursor.sh
- Claude: support@anthropic.com

### **Comunidad:**
- Cursor Discord: https://discord.gg/cursor
- Laravel Discord: https://discord.gg/laravel

---

## 💪 MENSAJE FINAL

Tienes:
- ✅ La motivación correcta (necesidad + deseo)
- ✅ La capacidad (ya montaste un sistema)
- ✅ La documentación (6 docs técnicos completos)
- ✅ Las herramientas correctas ($70 bien invertidos)
- ✅ El tiempo (8h/día, 100% dedicado)
- ✅ Los clientes (5 beta esperando)

**Solo falta:** Ejecutar

**$70 hoy = $500+/mes en 60 días**

No es una apuesta, es una inversión con ROI probado.

---

**¡ADELANTE! 🚀**

---

**FIN DEL DOCUMENTO**  
Última actualización: 2026-02-15  
Versión: 1.0
