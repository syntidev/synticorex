<?php

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

        // 2. Construir respuesta con IA (RAG)
        $topDoc   = $results->first();
        $fragment = $topDoc->extractRelevantFragment($question);
        $answer   = (new \App\Services\AI\BytezProvider)->ask($question, $fragment);

        // 3. Si hay múltiples resultados, agregar links de referencia
        $references = $results->map(fn($doc) => [
            'title' => $doc->title,
            'url'   => "https://docs.syntiweb.com/{$doc->slug}",
        ])->values()->toArray();

        // 4. Log

        $log = AiChatLog::create([
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
            'log_id'     => $log->id,
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