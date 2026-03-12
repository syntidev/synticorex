<?php
declare(strict_types=1);

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;

class GeminiProvider
{
    public function ask(string $question, string $context): string
    {
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent";

        $response = Http::withHeaders([
            'Content-Type'   => 'application/json',
            'X-goog-api-key' => env('GEMINI_API_KEY'),
        ])->post($url, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $this->preparePrompt($question, $context)]
                    ]
                ]
            ]
        ]);

        return $response->json('candidates.0.content.parts.0.text') 
            ?? 'Lo siento, no pude obtener respuesta de Gemini.';
    }

    private function preparePrompt(string $question, string $context): string
    {
        // Hemos reforzado la detección de intención en el punto 1
        return "Eres SYNTiA, la asistente inteligente de SYNTIweb. " .
           "REGLAS DE ORO: " .
           "1. SALUDO: Saluda SOLO si la pregunta del usuario es un saludo genérico (ej. 'hola', 'buenos días'). SI la pregunta es una consulta técnica o específica (ej. 'cómo configuro', 'qué es', 'ayuda con...'), OMITELO y responde directamente al grano. " .
           "2. TONO: Profesional, servicial, sin modismos informales. " .
           "3. RESPUESTA: Máximo 3 líneas usando SOLO el contexto. " .
           "4. ALCANCE: Si el contexto no pertenece al producto consultado, indícalo claramente. " .
           "5. FORMATO: **Negrita** para términos clave.\n\n" .
           "CONTEXTO:\n{$context}\n\n" .
           "PREGUNTA DEL USUARIO:\n{$question}";
    }
}