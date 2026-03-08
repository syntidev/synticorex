# SYNTIWEB — PLAN MAESTRO DE DOCUMENTACIÓN
## Mintlify + AI Help Assistant + RAG Laravel
**Versión:** 1.0 · **Fecha:** 07 MAR 2026  
**Arquitecto:** Carlos Bolívar · **Co-arquitecto:** Claude  
**Stack:** Laravel 12 · Tailwind v4 · Preline 4 · MySQL · Hostinger · Claude API

---

## VISIÓN GENERAL

```
docs.syntiweb.com          ← Mintlify (público, SEO, desarrolladores + usuarios)
syntiweb.com/help          ← Widget AI embebido en dashboard (RAG sobre los mismos .md)
```

Un solo source of truth: la carpeta `/docs` del repo.  
Mintlify la publica. El RAG la indexa. El asistente la consume.  
**Cero duplicación. Cero desincronización.**

---

## ARQUITECTURA DEFINITIVA

```
GitHub repo (Laravel)
  └── /docs/
        ├── studio/
        ├── food/
        ├── cat/
        └── shared/
              ↓
    ┌─────────────────────────────────┐
    │   GitHub Action: docs-sync.yml  │
    └────────┬────────────────────────┘
             │
     ┌───────┴───────┐
     │               │
  Mintlify        php artisan ai:index-docs
  (publica)       (indexa embeddings → MySQL)
     │               │
docs.syntiweb.com    └─→ tabla: ai_docs
                          └─→ Claude API
                               └─→ Widget /help en dashboard
```

---

## ESTRUCTURA DE CARPETAS (crear en el repo ahora)

```
/docs
  ├── mint.json                    ← config Mintlify
  ├── context.md                   ← brief del producto (para Claude)
  │
  ├── /shared
  │     ├── introduccion.mdx
  │     ├── quickstart.mdx
  │     ├── cuenta-y-planes.mdx
  │     ├── dominio-y-subdominio.mdx
  │     ├── moneda-y-precios.mdx
  │     ├── whatsapp.mdx
  │     ├── imagenes.mdx
  │     └── faq.mdx
  │
  ├── /studio
  │     ├── que-es-studio.mdx
  │     ├── wizard-onboarding.mdx
  │     ├── dashboard.mdx
  │     ├── hero-y-banner.mdx
  │     ├── productos-y-servicios.mdx
  │     ├── paletas-de-color.mdx
  │     ├── qr-sticker.mdx
  │     └── seo-automatico.mdx
  │
  ├── /food
  │     ├── que-es-food.mdx
  │     ├── wizard-food.mdx
  │     ├── categorias-menu.mdx
  │     ├── items-lista.mdx
  │     ├── pedido-rapido-whatsapp.mdx
  │     ├── extras-opciones.mdx
  │     └── horarios-atencion.mdx
  │
  └── /cat
        ├── que-es-cat.mdx
        ├── wizard-cat.mdx
        ├── catalogo-productos.mdx
        ├── carrito-whatsapp.mdx
        ├── mini-order-sc.mdx
        ├── badges-productos.mdx
        └── variantes.mdx
```

---

## ARCHIVO mint.json (raíz de /docs)

```json
{
  "name": "SYNTIweb Docs",
  "logo": {
    "light": "/logo/syntiweb-light.svg",
    "dark": "/logo/syntiweb-dark.svg"
  },
  "favicon": "/favicon.ico",
  "colors": {
    "primary": "#4A80E4",
    "light": "#6B9EFF",
    "dark": "#2D5FC4"
  },
  "topbarLinks": [
    { "name": "Dashboard", "url": "https://app.syntiweb.com/dashboard" }
  ],
  "topbarCtaButton": {
    "name": "Empieza gratis",
    "url": "https://syntiweb.com/registro"
  },
  "anchors": [
    { "name": "SYNTIstudio", "icon": "window", "url": "studio" },
    { "name": "SYNTIfood", "icon": "utensils", "url": "food" },
    { "name": "SYNTIcat", "icon": "shopping-bag", "url": "cat" }
  ],
  "navigation": [
    {
      "group": "Primeros pasos",
      "pages": [
        "shared/introduccion",
        "shared/quickstart",
        "shared/cuenta-y-planes"
      ]
    },
    {
      "group": "SYNTIstudio",
      "pages": [
        "studio/que-es-studio",
        "studio/wizard-onboarding",
        "studio/dashboard",
        "studio/hero-y-banner",
        "studio/productos-y-servicios",
        "studio/paletas-de-color",
        "studio/qr-sticker",
        "studio/seo-automatico"
      ]
    },
    {
      "group": "SYNTIfood",
      "pages": [
        "food/que-es-food",
        "food/wizard-food",
        "food/categorias-menu",
        "food/items-lista",
        "food/pedido-rapido-whatsapp",
        "food/extras-opciones",
        "food/horarios-atencion"
      ]
    },
    {
      "group": "SYNTIcat",
      "pages": [
        "cat/que-es-cat",
        "cat/wizard-cat",
        "cat/catalogo-productos",
        "cat/carrito-whatsapp",
        "cat/mini-order-sc",
        "cat/badges-productos",
        "cat/variantes"
      ]
    },
    {
      "group": "General",
      "pages": [
        "shared/moneda-y-precios",
        "shared/dominio-y-subdominio",
        "shared/whatsapp",
        "shared/imagenes",
        "shared/faq"
      ]
    }
  ],
  "footerSocials": {
    "instagram": "https://instagram.com/syntiweb",
    "twitter": "https://twitter.com/syntiweb"
  }
}
```

---

## ARCHIVO context.md (brújula para Claude al generar docs)

```markdown
# SYNTIweb — Product Context

**Producto:** SYNTIweb  
**Tipo:** Plataforma SaaS multi-producto para negocios venezolanos  
**URL:** syntiweb.com  
**Stack:** Laravel 12, Tailwind v4, Preline, MySQL, Hostinger

## Los 3 productos

### SYNTIstudio
Landing page completa para cualquier tipo de negocio.
- Crea página web profesional en minutos
- Hero personalizable con foto y CTA
- Catálogo de productos/servicios con fotos WebP
- QR sticker descargable
- SEO automático por segmento de negocio
- Sistema de moneda: REF / $ / ambos con toggle automático

Planes: Oportunidad $13/mes · Crecimiento $19/mes · Visión $25/mes

### SYNTIfood
Menú digital para restaurantes con pedido vía WhatsApp.
- Estructura híbrida: foto de categoría + lista de ítems con precios
- Pedido Rápido: acumulador de ítems → mensaje WhatsApp formateado
- Tasa BCV automática
- Extras/opciones por ítem (plan semestral+)

Planes: Básico $9/mes · Semestral $39 · Anual $69

### SYNTIcat
Catálogo de productos con carrito y checkout por WhatsApp.
- Carrito localStorage + Cart Drawer lateral
- Mini Order Engine → código SC-XXXX
- Badges (nuevo/hot/promo) en productos
- Variantes (talla, color, opciones)
- Carrito completo solo en plan Semestral/Anual

Planes: Básico $9/mes · Semestral $39 · Anual $69

## Usuarios objetivo
- Restaurantes y comidas rápidas (food)
- Tiendas y emprendedores (cat)
- Cualquier negocio local venezolano (studio)

## Reglas de escritura docs
- Lenguaje simple, sin jerga técnica
- Pasos numerados, acciones concretas
- El lector es dueño de negocio, NO desarrollador
- Máximo 500 palabras por archivo
- Incluir ejemplo real en cada feature
```

---

## FASE 1 — SETUP MINTLIFY (Día 1 · ~2 horas)

### Pasos

**1. Crear cuenta Mintlify (plan Hobby = $0)**
- Ir a mintlify.com → Sign up con GitHub
- Conectar el repo `syntiweb`
- Apuntar a `/docs` como directorio raíz

**2. Crear estructura de carpetas en el repo**
```bash
mkdir -p docs/shared docs/studio docs/food docs/cat docs/logo
touch docs/mint.json docs/context.md
```

**3. Agregar `docs/` al `.gitignore` de forma inversa**  
No ignorar nada en `/docs`. Asegurarse que todo commit de `/docs` dispare Mintlify rebuild.

**4. Configurar DNS en Hostinger**
```
Tipo: CNAME
Nombre: docs
Valor: hosting.mintlify.com
```
Resultado: `docs.syntiweb.com` → Mintlify

**5. En Mintlify dashboard → Settings → Custom Domain**
```
docs.syntiweb.com
```

---

## FASE 2 — GENERAR DOCUMENTACIÓN INICIAL CON CLAUDE (Día 1-2 · ~3 horas)

### Prompt maestro para VS Code + Claude

Usar este prompt por cada sección. Copiar y pegar con el archivo relevante del proyecto:

```
Actúa como Technical Documentation Writer para un SaaS venezolano.

Producto: [SYNTIstudio / SYNTIfood / SYNTIcat]
Archivo a generar: [nombre del archivo]
Contexto del producto: [pegar contenido de context.md]

Genera documentación para USUARIOS FINALES (dueños de negocio, no desarrolladores).

Formato: MDX para Mintlify
Estructura obligatoria:
- Título H1 descriptivo
- Descripción breve (2 líneas)
- Pasos numerados con acciones concretas
- Un ejemplo real del mundo venezolano
- Nota final con tip o advertencia si aplica

Reglas:
- Sin jerga técnica
- Sin palabras en inglés innecesarias
- Máximo 400 palabras
- Usar callouts de Mintlify: <Note>, <Warning>, <Tip>
```

### Orden de generación recomendado

1. `shared/introduccion.mdx` — qué es SYNTIweb y los 3 productos
2. `shared/quickstart.mdx` — desde registro hasta primera página publicada
3. `shared/cuenta-y-planes.mdx` — diferencias de planes con tabla
4. `studio/wizard-onboarding.mdx` — el flujo principal
5. `studio/productos-y-servicios.mdx` — agregar catálogo
6. `food/wizard-food.mdx` + `food/pedido-rapido-whatsapp.mdx`
7. `cat/wizard-cat.mdx` + `cat/carrito-whatsapp.mdx`
8. `shared/faq.mdx` — las 10 preguntas más comunes (inventar con criterio real)

---

## FASE 3 — RAG ENGINE EN LARAVEL (Día 3-4 · ~6 horas)

### Migración: tabla ai_docs

```php
// database/migrations/xxxx_create_ai_docs_table.php
Schema::create('ai_docs', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('product')->default('shared'); // studio|food|cat|shared
    $table->text('content');
    $table->json('embedding');                    // vector float[] como JSON
    $table->string('source_file')->nullable();    // path del .mdx original
    $table->timestamps();
});

Schema::create('ai_chat_logs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tenant_id')->constrained();
    $table->string('product')->nullable();         // contexto del producto
    $table->text('question');
    $table->text('answer');
    $table->integer('tokens_used')->default(0);
    $table->timestamps();
});
```

### Modelo AiDoc

```php
// app/Models/AiDoc.php
class AiDoc extends Model
{
    protected $fillable = ['title', 'product', 'content', 'embedding', 'source_file'];
    protected $casts = ['embedding' => 'array'];

    // Similitud coseno simple en PHP
    public static function similaritySearch(array $queryEmbedding, int $topK = 4, string $product = null): Collection
    {
        $docs = static::when($product, fn($q) => $q->where('product', $product))->get();

        return $docs->map(function ($doc) use ($queryEmbedding) {
            $score = self::cosineSimilarity($queryEmbedding, $doc->embedding);
            return ['doc' => $doc, 'score' => $score];
        })
        ->sortByDesc('score')
        ->take($topK)
        ->pluck('doc');
    }

    private static function cosineSimilarity(array $a, array $b): float
    {
        $dot = array_sum(array_map(fn($x, $y) => $x * $y, $a, $b));
        $normA = sqrt(array_sum(array_map(fn($x) => $x * $x, $a)));
        $normB = sqrt(array_sum(array_map(fn($x) => $x * $x, $b)));
        return ($normA * $normB) > 0 ? $dot / ($normA * $normB) : 0;
    }
}
```

### Servicio de Embeddings

```php
// app/AI/EmbeddingService.php
class EmbeddingService
{
    public static function generate(string $text): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            'Content-Type'  => 'application/json',
        ])->post('https://api.openai.com/v1/embeddings', [
            'model' => 'text-embedding-3-small',   // $0.02/1M tokens — baratísimo
            'input' => mb_substr($text, 0, 8000),  // límite seguro
        ]);

        return $response->json('data.0.embedding', []);
    }
}
```

### Comando de indexación

```php
// app/Console/Commands/IndexDocs.php
class IndexDocs extends Command
{
    protected $signature   = 'ai:index-docs {--product=all}';
    protected $description = 'Indexa archivos /docs para el asistente AI';

    public function handle(): void
    {
        $product = $this->option('product');
        $basePath = base_path('docs');

        $folders = $product === 'all'
            ? ['shared', 'studio', 'food', 'cat']
            : [$product];

        AiDoc::truncate(); // re-indexación completa

        foreach ($folders as $folder) {
            $files = File::glob("{$basePath}/{$folder}/*.mdx");

            foreach ($files as $file) {
                $content   = File::get($file);
                $title     = basename($file, '.mdx');
                $embedding = EmbeddingService::generate($content);

                AiDoc::create([
                    'title'       => $title,
                    'product'     => $folder,
                    'content'     => $content,
                    'embedding'   => $embedding,
                    'source_file' => str_replace(base_path(), '', $file),
                ]);

                $this->line("✓ Indexado: {$folder}/{$title}");
                sleep(1); // respetar rate limit API
            }
        }

        $this->info('Indexación completada: ' . AiDoc::count() . ' documentos.');
    }
}
```

---

## FASE 4 — ENDPOINT AI HELP (Día 4 · ~2 horas)

### Ruta

```php
// routes/api.php
Route::post('/help-ai', [AiHelpController::class, 'ask'])
    ->middleware(['auth:sanctum', 'throttle:20,1']); // 20 req/min por usuario
```

### Controlador

```php
// app/Http/Controllers/AiHelpController.php
class AiHelpController extends Controller
{
    public function ask(Request $request): JsonResponse
    {
        $request->validate(['question' => 'required|string|max:500']);

        $question = $request->input('question');
        $product  = $request->input('product', null); // filtro por producto activo
        $tenant   = auth()->user()->tenant;

        // 1. Generar embedding de la pregunta
        $questionEmbedding = EmbeddingService::generate($question);

        // 2. Buscar docs más relevantes
        $docs = AiDoc::similaritySearch($questionEmbedding, 4, $product);

        if ($docs->isEmpty()) {
            return response()->json(['answer' => 'No encontré información sobre eso. Intenta con otras palabras o contacta soporte.']);
        }

        // 3. Construir contexto
        $context = $docs->map(fn($d) => "## {$d->title}\n{$d->content}")->join("\n\n---\n\n");

        // 4. Prompt final
        $prompt = <<<EOT
Eres el asistente de ayuda de SYNTIweb, una plataforma para negocios venezolanos.

Responde ÚNICAMENTE usando la documentación proporcionada.
Si la respuesta no está en la documentación, di: "No tengo información sobre eso, puedes contactar a soporte."

Responde en español, de forma clara y concisa, máximo 3 párrafos o una lista de pasos.

DOCUMENTACIÓN:
{$context}

PREGUNTA DEL USUARIO:
{$question}
EOT;

        // 5. Llamar a Claude
        $response = Http::withHeaders([
            'x-api-key'   => env('CLAUDE_API_KEY'),
            'content-type' => 'application/json',
            'anthropic-version' => '2023-06-01',
        ])->post('https://api.anthropic.com/v1/messages', [
            'model'      => 'claude-haiku-4-5-20251001', // rápido y barato para ayuda
            'max_tokens' => 600,
            'messages'   => [['role' => 'user', 'content' => $prompt]],
        ]);

        $answer = $response->json('content.0.text', 'No pude procesar tu pregunta. Intenta de nuevo.');

        // 6. Log para análisis
        AiChatLog::create([
            'tenant_id' => $tenant->id,
            'product'   => $product,
            'question'  => $question,
            'answer'    => $answer,
            'tokens_used' => $response->json('usage.output_tokens', 0),
        ]);

        return response()->json(['answer' => $answer]);
    }
}
```

---

## FASE 5 — WIDGET EN EL DASHBOARD (Día 5 · ~3 horas)

### Botón flotante (Blade + Tailwind + Preline)

```html
{{-- resources/views/dashboard/partials/ai-help-widget.blade.php --}}

{{-- Botón flotante --}}
<button
    id="ai-help-btn"
    onclick="document.getElementById('ai-help-modal').classList.remove('hidden')"
    class="fixed bottom-6 right-6 z-50 flex items-center gap-2
           bg-[#4A80E4] hover:bg-[#2D5FC4] text-white
           px-4 py-3 rounded-full shadow-lg transition-all duration-200
           text-sm font-medium">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-3 3v-3z"/>
    </svg>
    Ayuda IA
</button>

{{-- Modal de chat --}}
<div id="ai-help-modal"
     class="hidden fixed inset-0 z-[100] flex items-end justify-end p-4 sm:p-6"
     x-data="aiHelp()" @keydown.escape.window="close()">

    {{-- Overlay --}}
    <div class="absolute inset-0 bg-black/20 backdrop-blur-sm"
         @click="close()"></div>

    {{-- Panel chat --}}
    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl
                border border-gray-200 flex flex-col overflow-hidden"
         style="height: 520px;">

        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-3
                    bg-[#4A80E4] text-white">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></div>
                <span class="font-semibold text-sm">Asistente SYNTIweb</span>
            </div>
            <button @click="close()" class="text-white/80 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Mensajes --}}
        <div id="ai-messages"
             class="flex-1 overflow-y-auto p-4 space-y-3 text-sm">
            <div class="bg-gray-100 text-gray-700 rounded-xl rounded-tl-none px-3 py-2 max-w-[85%]">
                Hola 👋 Soy tu asistente. ¿En qué te ayudo hoy?
            </div>
        </div>

        {{-- Input --}}
        <div class="border-t border-gray-200 p-3 flex gap-2">
            <input
                id="ai-input"
                type="text"
                x-model="question"
                @keydown.enter="ask()"
                placeholder="¿Cómo agrego un producto?"
                class="flex-1 text-sm border border-gray-200 rounded-xl px-3 py-2
                       focus:outline-none focus:ring-2 focus:ring-[#4A80E4]/30 focus:border-[#4A80E4]">
            <button
                @click="ask()"
                :disabled="loading"
                class="bg-[#4A80E4] disabled:bg-gray-300 text-white px-4 py-2
                       rounded-xl text-sm font-medium transition-colors">
                <span x-show="!loading">Enviar</span>
                <span x-show="loading">...</span>
            </button>
        </div>
    </div>
</div>

<script>
function aiHelp() {
    return {
        question: '',
        loading: false,

        close() {
            document.getElementById('ai-help-modal').classList.add('hidden');
        },

        async ask() {
            if (!this.question.trim() || this.loading) return;

            const q = this.question;
            this.question = '';
            this.loading = true;

            this.addMessage(q, 'user');

            try {
                const res = await fetch('/api/help-ai', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        question: q,
                        product: '{{ $tenant->plan->blueprint ?? "studio" }}'
                    })
                });

                const data = await res.json();
                this.addMessage(data.answer, 'assistant');

            } catch (e) {
                this.addMessage('Ocurrió un error. Intenta de nuevo.', 'assistant');
            } finally {
                this.loading = false;
            }
        },

        addMessage(text, role) {
            const container = document.getElementById('ai-messages');
            const div = document.createElement('div');

            div.className = role === 'user'
                ? 'ml-auto bg-[#4A80E4] text-white rounded-xl rounded-tr-none px-3 py-2 max-w-[85%] text-sm'
                : 'bg-gray-100 text-gray-700 rounded-xl rounded-tl-none px-3 py-2 max-w-[85%] text-sm';

            div.textContent = text;
            container.appendChild(div);
            container.scrollTop = container.scrollHeight;
        }
    }
}
</script>
```

### Incluir en el layout del dashboard

```html
{{-- resources/views/layouts/dashboard.blade.php --}}
{{-- Al final del body --}}
@include('dashboard.partials.ai-help-widget')
```

---

## FASE 6 — GITHUB ACTION: SYNC AUTOMÁTICO (Día 5 · ~1 hora)

```yaml
# .github/workflows/docs-sync.yml
name: Sync Docs & Re-index AI

on:
  push:
    paths:
      - 'docs/**'
    branches:
      - main

jobs:
  reindex:
    runs-on: ubuntu-latest

    steps:
      - uses: actions/checkout@v4

      - name: Trigger re-indexación en producción
        run: |
          curl -X POST \
            -H "Authorization: Bearer ${{ secrets.DEPLOY_TOKEN }}" \
            -H "Content-Type: application/json" \
            https://app.syntiweb.com/api/internal/reindex-docs
```

### Endpoint interno de re-indexación

```php
// routes/api.php (protegido con token interno)
Route::post('/internal/reindex-docs', function (Request $request) {
    $token = $request->bearerToken();
    if ($token !== env('INTERNAL_DEPLOY_TOKEN')) abort(403);

    Artisan::queue('ai:index-docs');
    return response()->json(['status' => 'queued']);
});
```

---

## FASE 7 — ANÁLISIS DE PREGUNTAS (post-lanzamiento)

La tabla `ai_chat_logs` te da oro puro:

```sql
-- Las 10 preguntas más frecuentes (mejorar esas docs primero)
SELECT question, COUNT(*) as total
FROM ai_chat_logs
GROUP BY question
ORDER BY total DESC
LIMIT 10;

-- Preguntas sin buena respuesta (contienen "No tengo información")
SELECT question, answer, created_at
FROM ai_chat_logs
WHERE answer LIKE '%No tengo información%'
ORDER BY created_at DESC;
```

Eso te dice exactamente qué docs faltan o están mal escritas.

---

## COSTOS ESTIMADOS (Venezuela-aware)

| Componente | Precio | Notas |
|---|---|---|
| Mintlify Hobby | $0 | 1 editor, suficiente ahora |
| text-embedding-3-small | ~$0.02/1M tokens | Indexar 50 docs ≈ $0.001 total |
| claude-haiku (respuestas) | ~$0.003/pregunta | 1000 preguntas/mes ≈ $3 |
| Hostinger (ya tienes) | $0 adicional | Todo centralizado |
| **Total mensual estimado** | **~$3-8** | Con 1000 consultas AI |

---

## CHECKLIST DE IMPLEMENTACIÓN

### Semana actual (Fase B activa)
- [ ] Crear carpeta `/docs` en repo con estructura definida
- [ ] Crear `mint.json` + `context.md`
- [ ] Conectar Mintlify con GitHub
- [ ] DNS: CNAME docs → Mintlify en Hostinger
- [ ] Generar con Claude los 5 archivos más críticos (quickstart, shared, studio)

### Antes del lanzamiento (Fase E)
- [ ] Migración `ai_docs` + `ai_chat_logs`
- [ ] `EmbeddingService.php` + `AiDoc.php`
- [ ] Comando `ai:index-docs` probado en local
- [ ] Endpoint `POST /api/help-ai` con auth
- [ ] Widget en dashboard integrado
- [ ] GitHub Action docs-sync activa
- [ ] Variables de entorno: `OPENAI_API_KEY` + `CLAUDE_API_KEY` + `INTERNAL_DEPLOY_TOKEN`

### Post-lanzamiento (Fase F)
- [ ] Revisar `ai_chat_logs` cada semana
- [ ] Agregar docs de preguntas frecuentes reales
- [ ] Upgrade Mintlify si llegan colaboradores
- [ ] Evaluar pasar embeddings a Qdrant si el volumen escala

---

## VARIABLES DE ENTORNO A AGREGAR EN .env

```env
# AI Documentation Assistant
OPENAI_API_KEY=sk-...              # Para embeddings (text-embedding-3-small)
CLAUDE_API_KEY=sk-ant-...          # Para respuestas (claude-haiku)
INTERNAL_DEPLOY_TOKEN=tu-token-secreto-aqui

# Mintlify (solo referencia, no se usa en código)
# docs.syntiweb.com → CNAME → hosting.mintlify.com
```

---

## DECISIONES ARQUITECTÓNICAS CLAVE

**¿Por qué Haiku para el chat y no Sonnet?**  
Respuestas de ayuda no necesitan razonamiento complejo. Haiku es 10x más rápido y barato. El contexto ya está curado por el RAG.

**¿Por qué embeddings OpenAI y no Anthropic?**  
Anthropic no tiene endpoint de embeddings propio. `text-embedding-3-small` es el estándar de la industria y cuesta casi nada.

**¿Por qué similitud coseno en PHP y no pgvector?**  
Para el volumen inicial (< 100 documentos), PHP en memoria es suficiente y cero infraestructura adicional. Migrar a pgvector o Qdrant cuando superes 500 documentos o notes latencia.

**¿Por qué /docs en el mismo repo y no repo separado?**  
Sincronización perfecta. Cuando cambias una feature, la doc está en el mismo commit. El GitHub Action solo dispara cuando `/docs/**` cambia, no en cada commit de código.

---

*Documento generado: 07 MAR 2026 · SYNTIweb Arquitectura*
