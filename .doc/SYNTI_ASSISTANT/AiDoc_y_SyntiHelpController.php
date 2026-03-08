<?php
// ============================================================
// ARCHIVO 1: app/Models/AiDoc.php
// ============================================================

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class AiDoc extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'product',
        'content',
        'source_file',
    ];

    /**
     * Búsqueda FULLTEXT nativa MySQL.
     * Devuelve los N documentos más relevantes para la pregunta.
     */
    public static function search(string $query, int $limit = 3, ?string $product = null): Collection
    {
        $clean = self::sanitizeQuery($query);

        if (empty($clean)) {
            return new Collection();
        }

        return static::selectRaw('*, MATCH(title, content) AGAINST(? IN BOOLEAN MODE) AS relevance', [$clean])
            ->whereRaw('MATCH(title, content) AGAINST(? IN BOOLEAN MODE)', [$clean])
            ->when($product, fn($q) => $q->where('product', $product))
            ->orderByDesc('relevance')
            ->limit($limit)
            ->get();
    }

    /**
     * Extrae el fragmento más relevante del contenido para mostrar en la respuesta.
     * Busca el párrafo que contiene más keywords de la pregunta.
     */
    public function extractRelevantFragment(string $query, int $maxLength = 600): string
    {
        $keywords  = array_filter(explode(' ', strtolower($query)), fn($w) => strlen($w) > 3);
        $paragraphs = array_filter(explode("\n\n", $this->content));
        $best       = ['score' => 0, 'text' => ''];

        foreach ($paragraphs as $paragraph) {
            $paragraph = trim($paragraph);
            if (strlen($paragraph) < 30) continue;

            $score = 0;
            $lower = strtolower($paragraph);
            foreach ($keywords as $keyword) {
                $score += substr_count($lower, $keyword);
            }

            if ($score > $best['score']) {
                $best = ['score' => $score, 'text' => $paragraph];
            }
        }

        $fragment = $best['text'] ?: substr($this->content, 0, $maxLength);

        // Truncar limpiamente en oración completa
        if (strlen($fragment) > $maxLength) {
            $fragment = substr($fragment, 0, $maxLength);
            $lastDot  = strrpos($fragment, '.');
            if ($lastDot > $maxLength * 0.6) {
                $fragment = substr($fragment, 0, $lastDot + 1);
            }
        }

        return $fragment;
    }

    private static function sanitizeQuery(string $query): string
    {
        // Limpia la query para FULLTEXT boolean mode
        $clean = preg_replace('/[^\w\s\áéíóúüñÁÉÍÓÚÜÑ]/u', ' ', $query);
        $words = array_filter(explode(' ', trim($clean)), fn($w) => strlen($w) > 2);

        // Prefijo + para que cada palabra sea obligatoria
        return implode(' ', array_map(fn($w) => "+{$w}*", $words));
    }
}


<?php
// ============================================================
// ARCHIVO 2: app/Http/Controllers/SyntiHelpController.php
// ============================================================

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\AiDoc;
use App\Models\AiChatLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SyntiHelpController extends Controller
{
    private const MAX_QUESTION_LENGTH = 300;
    private const RESULTS_LIMIT       = 3;

    public function ask(Request $request): JsonResponse
    {
        $request->validate([
            'question' => ['required', 'string', 'max:' . self::MAX_QUESTION_LENGTH],
            'product'  => ['nullable', 'string', 'in:shared,studio,food,cat'],
        ]);

        $question = trim($request->string('question')->toString());
        $product  = $request->string('product')->toString() ?: null;
        $tenant   = $request->user()?->tenant;

        // 1. Buscar en docs
        $results = AiDoc::search($question, self::RESULTS_LIMIT, $product);

        // Fallback: si no hay resultados con el producto, busca en todos
        if ($results->isEmpty() && $product) {
            $results = AiDoc::search($question, self::RESULTS_LIMIT);
        }

        if ($results->isEmpty()) {
            return $this->noResultsResponse($question, $tenant?->id);
        }

        // 2. Construir respuesta desde el doc más relevante
        $topDoc   = $results->first();
        $fragment = $topDoc->extractRelevantFragment($question);

        // 3. Si hay múltiples resultados, agregar links de referencia
        $references = $results->map(fn($doc) => [
            'title' => $doc->title,
            'url'   => "https://docs.syntiweb.com/{$doc->slug}",
        ])->values()->toArray();

        // 4. Log
        $answer = $this->formatAnswer($fragment, $topDoc->title);

        AiChatLog::create([
            'tenant_id' => $tenant?->id,
            'product'   => $product,
            'question'  => $question,
            'answer'    => $answer,
        ]);

        return response()->json([
            'success'    => true,
            'answer'     => $answer,
            'source'     => $topDoc->title,
            'source_url' => "https://docs.syntiweb.com/{$topDoc->slug}",
            'references' => count($references) > 1 ? $references : [],
        ]);
    }

    public function feedback(Request $request): JsonResponse
    {
        $request->validate([
            'log_id'  => ['required', 'integer', 'exists:ai_chat_logs,id'],
            'helpful' => ['required', 'boolean'],
        ]);

        AiChatLog::where('id', $request->integer('log_id'))
            ->update(['helpful' => $request->boolean('helpful') ? 1 : 0]);

        return response()->json(['success' => true]);
    }

    private function formatAnswer(string $fragment, string $sourceTitle): string
    {
        // Limpia markdown básico para mostrar en el widget
        $clean = preg_replace('/#{1,6}\s+/', '', $fragment);
        $clean = preg_replace('/\*{1,2}([^*]+)\*{1,2}/', '$1', $clean ?? '');
        $clean = preg_replace('/`([^`]+)`/', '$1', $clean ?? '');

        return trim($clean ?? $fragment);
    }

    private function noResultsResponse(string $question, ?int $tenantId): JsonResponse
    {
        AiChatLog::create([
            'tenant_id' => $tenantId,
            'question'  => $question,
            'answer'    => 'Sin resultados',
        ]);

        return response()->json([
            'success' => false,
            'answer'  => 'No encontré información sobre eso en la documentación. Puedes revisar la guía completa en docs.syntiweb.com o contactar soporte.',
            'source'  => null,
        ]);
    }
}
