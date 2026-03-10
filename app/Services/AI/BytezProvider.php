<?php
declare(strict_types=1);

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;

class BytezProvider
{
    public function ask(string $question, string $context): string
    {
        $response = Http::withHeaders([
                'Authorization' => config('ai.api_key'),
                'Content-Type'  => 'application/json',
            ])
            ->timeout(15)
            ->post('https://api.bytez.com/models/v2/' . config('ai.model'), [
                'messages' => [
                    ['role' => 'system', 'content' => 'Eres SYNTI, asistente de SYNTIweb. Responde SOLO basándote en el contexto proporcionado. Responde en español, breve y claro. Si no tienes información suficiente, dilo.'],
                    ['role' => 'user',   'content' => "Contexto:\n{$context}\n\nPregunta: {$question}"],
                ],
                'params' => ['max_new_tokens' => 300],
            ]);

        \Log::info('Bytez response', $response->json() ?? []);

        return $response->json('output.content')
            ?? 'No pude generar una respuesta en este momento.';
    }
}