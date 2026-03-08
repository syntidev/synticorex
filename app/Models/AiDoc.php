<?php

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

        return static::selectRaw('*, MATCH(title, content) AGAINST(? IN NATURAL LANGUAGE MODE) AS relevance', [$clean])
            ->whereRaw('MATCH(title, content) AGAINST(? IN NATURAL LANGUAGE MODE)', [$clean])
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
        $clean = preg_replace('/[^\w\s\áéíóúüñÁÉÍÓÚÜÑ]/u', ' ', $query);
        $words = array_filter(
            explode(' ', trim($clean)),
            fn($w) => strlen($w) > 3
        );

        if (empty($words)) {
            return '';
        }

        // Natural language mode — sin + obligatorio
        return implode(' ', $words);
    }
}
