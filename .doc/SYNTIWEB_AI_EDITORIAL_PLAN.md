# SYNTIweb — Plan de Implementación: Módulo Asistente Editorial IA

**Versión:** 1.0  
**Fecha:** Febrero 2026  
**Autor:** Product Architect SYNTIweb  
**Contexto:** Documento de implementación técnica para desarrollo en VS Code con Claude (Sonnet 4.5 / Opus 4.5 / Haiku 4.5 + Vibe Coding)  
**Dependencia:** Sistema multitenant SYNTIweb activo

---

## 1. QUÉ ES Y POR QUÉ EXISTE

El Asistente Editorial es un módulo de inteligencia artificial integrado al panel de administración de cada tenant. Actúa **antes de que el contenido llegue a la vitrina pública**, sugiriendo correcciones ortográficas, mejoras de coherencia y optimizaciones SEO en tiempo real.

**El problema que resuelve:**

La vitrina pública de un negocio es su cara digital. Un comerciante que escribe "Pizzaria de jamon y queso con masa delgda" en su panel está publicando eso en Google, en su QR, en su página. Sin este módulo, SYNTIweb publica el error. Con este módulo, SYNTIweb protege la imagen del cliente y la calidad del ecosistema.

**Por qué nadie lo hace en este segmento:**

Las plataformas que tienen IA integrada cuestan $200+/mes. El comerciante venezolano informal no accede a eso. SYNTIweb lo lleva integrado desde el panel más básico.

---

## 2. DÓNDE ACTÚA — COBERTURA DEL ECOSISTEMA

El módulo cubre **todos los campos de texto** que el cliente puede editar y que terminan siendo visibles al público:

| Sección | Campo | Prioridad |
|---------|-------|-----------|
| Perfil del negocio | Nombre, descripción, slogan | Alta |
| Hero / Banner | Título principal, subtítulo, CTA | Alta |
| Productos | Nombre, descripción, categoría | Alta |
| Servicios | Nombre, descripción, precio referencia | Alta |
| Combos | Nombre, descripción, contenido | Alta |
| FAQ | Pregunta, respuesta | Media |
| About / Nosotros | Texto libre de presentación | Media |
| Secciones editables | Cualquier bloque de texto libre | Media |

**Regla:** Si el texto termina en el JSON de la vitrina pública, pasa por el asistente.

---

## 3. ARQUITECTURA TÉCNICA

### 3.1 Principio central — Proveedor intercambiable

El sistema **nunca llama directamente a la API de Anthropic** desde el controlador. Usa una capa de abstracción que permite cambiar de proveedor (Anthropic → OpenAI → Gemini) modificando una sola línea de configuración:

```php
// config/ai.php
return [
    'provider' => env('AI_PROVIDER', 'anthropic'),
    'model'    => env('AI_MODEL', 'claude-haiku-4-5-20251001'),
    'api_key'  => env('AI_API_KEY'),
];
```

```php
// app/Services/AIAssistantService.php
class AIAssistantService
{
    protected AIProviderInterface $provider;

    public function __construct()
    {
        $this->provider = AIProviderFactory::make(config('ai.provider'));
    }

    public function correct(string $text, string $fieldType, string $businessType): AIResponse
    {
        $prompt = $this->buildPrompt($text, $fieldType, $businessType);
        return $this->provider->call($prompt);
    }
}
```

### 3.2 El prompt — 3 capas de contexto

Cada llamada al modelo lleva exactamente 3 capas:

**Capa 1 — Rol fijo (system prompt, siempre igual):**
```
Eres un asistente editorial para negocios venezolanos pequeños y medianos.
Tu trabajo es corregir ortografía, acentos y coherencia, y sugerir mejoras
para que el texto sea más claro, persuasivo y posicionable en Google.
Responde siempre en español venezolano natural. Sé breve, amable y práctico.
Nunca cambies el significado original. Si el texto está bien, dilo.
```

**Capa 2 — Contexto del campo (dinámico, según dónde escribe):**

```php
// app/Config/AIFieldContexts.php
return [
    'business_name'   => 'Nombre del negocio. Máx 60 caracteres. Aparece en Google y en el QR.',
    'business_desc'   => 'Descripción del negocio. Ideal 150 caracteres. Cálida y confiable.',
    'hero_title'      => 'Título principal de la vitrina web. Alto impacto. Máx 70 chars para SEO.',
    'hero_subtitle'   => 'Subtítulo del banner. Complementa el título. Llama a la acción.',
    'cta_text'        => 'Botón de llamada a la acción. Corto, directo, verbo activo. Máx 30 chars.',
    'product_name'    => 'Nombre de producto. Aparece en la vitrina y en búsquedas. Máx 60 chars.',
    'product_desc'    => 'Descripción de producto. Persuasiva, apetitosa o clara según el tipo.',
    'service_name'    => 'Nombre de servicio profesional. Claro y confiable.',
    'service_desc'    => 'Descripción del servicio. Qué incluye, beneficio principal.',
    'combo_name'      => 'Nombre de combo o promoción. Atractivo, fácil de recordar.',
    'combo_desc'      => 'Descripción del combo. Qué incluye y el valor que ofrece.',
    'faq_question'    => 'Pregunta frecuente. Debe sonar como la hace un cliente real.',
    'faq_answer'      => 'Respuesta a pregunta frecuente. Clara, directa y confiable.',
    'about_text'      => 'Historia o presentación del negocio. Humana, cercana, auténtica.',
    'section_text'    => 'Sección editable libre. Coherente con el resto del negocio.',
];
```

**Capa 3 — El texto del usuario:**
```
Tipo de negocio: Pizzería
Campo: Nombre de producto
Texto a revisar: "pizza de jamon y queso con masa delgda"
```

### 3.3 Formato de respuesta del modelo

El modelo responde en JSON estructurado para que el frontend lo procese:

```json
{
  "status": "needs_correction",
  "corrected_text": "Pizza de jamón y queso con masa delgada",
  "corrections": [
    "Se añadió acento a 'jamón'",
    "Se corrigió 'delgda' → 'delgada'",
    "Se aplicaron mayúsculas al inicio"
  ],
  "seo_tip": "Considera añadir el tamaño o ingrediente destacado: 'Pizza familiar de jamón y queso'",
  "score": 72
}
```

`status` puede ser: `ok` | `needs_correction` | `needs_rewrite`

---

## 4. CONTROL DE USO — CRÉDITOS POR PLAN

### 4.1 Límites mensuales

| Plan | Correcciones/mes | Costo estimado tokens |
|------|-----------------|----------------------|
| Básico | 20 | ~$0.02 |
| Profesional | 60 | ~$0.06 |
| Menú | 80 | ~$0.08 |
| Empresarial | 150 | ~$0.15 |

El costo del módulo se absorbe en el precio del plan. No se cobra como extra visible al cliente — es parte del servicio.

### 4.2 Tabla de control

```sql
ai_usage
  id
  tenant_id
  month           -- "2026-02" (YYYY-MM)
  corrections     -- integer, incrementa con cada llamada
  limit           -- copia del límite del plan al inicio del mes
  created_at
  updated_at
```

### 4.3 Middleware de validación

```php
// Antes de cada llamada al asistente
class CheckAIUsage
{
    public function handle(Request $request, Closure $next)
    {
        $usage = AIUsage::currentMonth($request->tenant_id);
        
        if ($usage->corrections >= $usage->limit) {
            return response()->json([
                'error' => 'limit_reached',
                'message' => 'Alcanzaste tus correcciones este mes. 
                              Actualiza tu plan para continuar.',
                'upgrade_plan' => true
            ], 429);
        }
        
        return $next($request);
    }
}
```

El frontend muestra el mensaje amable con CTA al upgrade — upsell natural integrado.

### 4.4 Contador visible en el panel

El cliente ve en su dashboard:
```
Correcciones IA este mes: 14 / 60 utilizadas
```

Transparencia total. Sin sorpresas.

---

## 5. FLUJO DE USUARIO EN EL PANEL

```
1. Cliente escribe en campo (ej: nombre de producto)
2. Al salir del campo (onBlur) o al presionar botón "✨ Revisar"
3. Frontend envía texto + tipo de campo al backend
4. Backend valida créditos disponibles
5. Backend arma el prompt con las 3 capas
6. Llama a API Anthropic (Haiku — rápido y económico)
7. Recibe respuesta JSON estructurada
8. Frontend muestra sugerencias inline:
   ┌─────────────────────────────────────────┐
   │ ✨ Sugerencia IA                         │
   │ "Pizza de jamón y queso con masa delgada"│
   │ • Se añadió acento a 'jamón'             │
   │ • Se corrigió 'delgda' → 'delgada'       │
   │ 💡 Tip SEO: Añade el tamaño o ingrediente│
   │                                          │
   │ [Aplicar sugerencia]  [Ignorar]          │
   └─────────────────────────────────────────┘
9. Cliente decide: acepta, ignora o edita manualmente
10. Se guarda en BD el texto final (con o sin corrección)
11. Contador de uso +1
```

**La corrección no es bloqueante.** El cliente siempre puede ignorar y guardar su texto original. El asistente sugiere, no impone.

---

## 6. PROVEEDOR DE IA — INTERCAMBIABLE

### 6.1 Por qué empezar con Anthropic Haiku

- Más rápido para textos cortos (< 1 segundo de respuesta)
- Mejor manejo de español coloquial latinoamericano
- Costo más bajo del mercado para este caso de uso
- Ya integrado en el flujo de desarrollo del proyecto

### 6.2 Cómo hacer el switch

Cambiar de proveedor es una línea en el `.env`:

```bash
# Hoy
AI_PROVIDER=anthropic
AI_MODEL=claude-haiku-4-5-20251001

# Mañana si se necesita
AI_PROVIDER=openai
AI_MODEL=gpt-4o-mini
```

El resto del sistema no cambia. La interfaz `AIProviderInterface` garantiza que cualquier proveedor responda en el mismo formato.

### 6.3 Interfaz del proveedor

```php
interface AIProviderInterface
{
    public function call(string $prompt): AIResponse;
}

class AnthropicProvider implements AIProviderInterface { ... }
class OpenAIProvider implements AIProviderInterface { ... }
class GeminiProvider implements AIProviderInterface { ... }
```

---

## 7. TAREAS DE IMPLEMENTACIÓN

### TAREA AI-001 — Infraestructura base
- [ ] Crear `AIAssistantService` con inyección de proveedor
- [ ] Crear `AIProviderInterface`
- [ ] Implementar `AnthropicProvider`
- [ ] Variables de entorno: `AI_PROVIDER`, `AI_MODEL`, `AI_API_KEY`
- [ ] Test de llamada básica con texto de prueba

### TAREA AI-002 — Contextos de campo
- [ ] Crear archivo de configuración `AIFieldContexts.php`
- [ ] Definir contextos para todos los campos del ecosistema
- [ ] Método `buildPrompt(text, fieldType, businessType)`
- [ ] Test de construcción de prompt por tipo de campo

### TAREA AI-003 — Control de uso
- [ ] Crear migración `create_ai_usage_table`
- [ ] Crear modelo `AIUsage` con scope `currentMonth`
- [ ] Middleware `CheckAIUsage`
- [ ] Límites por plan en configuración
- [ ] Reset automático mensual (scheduled job)

### TAREA AI-004 — Endpoint API
- [ ] Ruta `POST /api/ai/correct`
- [ ] Validación: `text`, `field_type`, `tenant_id`
- [ ] Respuesta JSON estructurada
- [ ] Rate limiting adicional (máx 5 llamadas/minuto por tenant)

### TAREA AI-005 — Frontend del panel
- [ ] Botón "✨ Revisar" en cada campo de texto
- [ ] Componente de sugerencia inline
- [ ] Animación de carga mientras espera respuesta
- [ ] Botones "Aplicar" / "Ignorar"
- [ ] Indicador de créditos restantes en el panel
- [ ] Mensaje de límite alcanzado con CTA upgrade

### TAREA AI-006 — Integración por secciones
- [ ] Productos y servicios
- [ ] Combos y promociones
- [ ] FAQ
- [ ] Hero / Banner
- [ ] About / Nosotros
- [ ] Secciones editables libres

### TAREA AI-007 — Métricas del módulo
- [ ] Cuántas correcciones se aplican vs se ignoran (tasa de aceptación)
- [ ] Campos con más errores frecuentes
- [ ] Tipos de negocio con más necesidad de corrección
- [ ] Visible en dashboard maestro SYNTIweb

### TAREA AI-008 — Panel de configuración maestro
- [ ] Campo para ingresar/rotar API key sin tocar código (override BD sobre .env)
- [ ] Límites de correcciones por plan editables sin deploy (guardados en BD)
- [ ] Toggle global para activar/desactivar el módulo en todo el ecosistema
- [ ] Toggle por tenant individual (desactivar cliente específico sin afectar otros)
- [ ] Vista de consumo total del ecosistema vs costo estimado en tiempo real
- [ ] Alerta configurable cuando el gasto mensual supere umbral definido
- [ ] Selector de proveedor IA (Anthropic / OpenAI / Gemini) desde el panel
- [ ] Selector de modelo por proveedor (ej: Haiku vs Sonnet)
- [ ] Log de últimas llamadas con status, tokens consumidos y tenant

**Nota:** Los límites de correcciones por plan se calibran con datos reales de uso en producción — no fijarlos antes del lanzamiento. El panel existe precisamente para ajustarlos sin tocar código.

---

## 8. NOTAS DEL PRODUCT ARCHITECT

- El módulo protege el JSON de la vitrina pública — si el dato entra limpio, sale limpio al mundo
- La tasa de aceptación (AI-007) es el KPI más importante: si los clientes ignoran las sugerencias constantemente, el prompt necesita ajuste
- Empezar con Haiku, escalar a Sonnet solo si se necesita mayor calidad en textos largos
- El upsell natural por límite de correcciones es una palanca de conversión de plan — diseñarlo con cuidado para que no genere frustración
- Los límites por plan de la sección 4.1 son estimados iniciales — el panel AI-008 permite ajustarlos con datos reales sin deploy
- Este módulo, bien ejecutado, es un diferenciador de producto real en el mercado venezolano y latinoamericano de este segmento
- Prioridad de implementación: AI-001 → AI-002 → AI-003 → AI-004 → AI-005 (MVP) → AI-006 → AI-007 → AI-008

---

*Documento generado en sesión de arquitectura — exportar como referencia para contexto de desarrollo en VS Code.*
