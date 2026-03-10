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
                    [
                        'role'    => 'system',
                        'content' => 'Eres SYNTiA, la inteligencia de SYNTIweb. Responde SOLO basandote en el contexto proporcionado. Responde en espanol venezolano, directo y claro. Maximo 4 pasos cortos. Usa **negrita** para labels importantes. Si el tema necesita mas detalle, termina con: "Ver guia completa →" y el slug del doc fuente.',
                    ],
                    [
                        'role'    => 'user',
                        'content' => "Contexto:\n{$context}\n\nPregunta: {$question}",
                    ],
                ],
                'params' => ['max_new_tokens' => 200],
            ]);

        $output = $response->json('output.content')
            ?? 'No pude generar una respuesta en este momento.';

        // Eliminar bloque <think>...</think> que genera Qwen3
        $output = preg_replace('/<think>[\s\S]*?<\/think>/i', '', $output);

        return trim($output);
    }
}