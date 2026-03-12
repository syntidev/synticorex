<?php
declare(strict_types=1);

namespace App\Services\AI;

class AIServiceSwitcher
{
    public static function getProvider()
    {
        $provider = env('AI_PROVIDER', 'bytez');

        return match ($provider) {
            'gemini'  => new GeminiProvider(),
            'minimax' => new MiniMaxProvider(),
            default   => new BytezProvider(),
        };
    }
}