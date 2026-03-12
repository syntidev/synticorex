<?php
declare(strict_types=1);

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;

class GeminiProvider
{
    public function ask(string $question, string $context): string
    {
        // Usamos el modelo flash-latest como en tu curl
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

        // Retornamos el texto o el fallback si algo falla
        return $response->json('candidates.0.content.parts.0.text') 
            ?? 'Lo siento, no pude obtener respuesta de Gemini.';
    }

    private function preparePrompt(string $question, string $context): string
    {
        return "Eres SYNTiA, la asistente inteligente de SYNTIweb. " .
           "Tu personalidad es amable, acogedora y profesional. " .
           "REGLAS DE ORO: " .
           "1. SALUDO: Comienza siempre con un saludo cordial y humano (ej. '¡Hola! Qué gusto saludarte', '¡Bienvenido! Gracias por escribirnos'). " .
           "2. TONO: Usa un lenguaje educado, servicial y positivo, típico de la hospitalidad venezolana de alto nivel, pero sin usar modismos informales (evita 'chamo', 'pana', etc.). " .
           "3. RESPUESTA: Responde brevemente (máximo 3 líneas) usando SOLO el contexto proporcionado. " .
           "4. CIERRE: Despídete invitando a seguir consultando o agradeciendo la confianza. " .
           "5. FORMATO: Usa **negrita** para términos clave.\n\n" .
           "CONTEXTO:\n{$context}\n\n" .
           "PREGUNTA DEL USUARIO:\n{$question}";
    }
}