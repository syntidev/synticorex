<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\AiDoc;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class IndexDocs extends Command
{
    protected $signature   = 'ai:index-docs {--product=all : studio|food|cat|shared|all}';
    protected $description = 'Indexa archivos /docs/*.mdx para el asistente SYNTI';

    public function handle(): int
    {
        $product  = $this->option('product');
        $basePath = base_path('docs');

        if (! File::isDirectory($basePath)) {
            $this->error('❌ Carpeta /docs no encontrada en: ' . $basePath);
            return self::FAILURE;
        }

        $folders = $product === 'all'
            ? ['shared', 'studio', 'food', 'cat']
            : [$product];

        $this->info('🔄 Iniciando indexación...');
        $bar   = $this->output->createProgressBar();
        $count = 0;

        foreach ($folders as $folder) {
            $files = File::glob("{$basePath}/{$folder}/*.mdx");

            foreach ($files as $file) {
                $raw     = File::get($file);
                $title   = $this->extractTitle($raw);
                $content = $this->stripFrontmatter($raw);
                $slug    = "{$folder}/" . basename($file, '.mdx');

                AiDoc::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'title'       => $title,
                        'product'     => $folder,
                        'content'     => $content,
                        'source_file' => str_replace(base_path() . DIRECTORY_SEPARATOR, '', $file),
                    ]
                );

                $bar->advance();
                $count++;
            }
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("✅ Indexados {$count} documentos. SYNTI está listo.");

        return self::SUCCESS;
    }

    private function extractTitle(string $raw): string
    {
        // Extrae title del frontmatter: title: "Mi título"
        if (preg_match('/^title:\s*["\']?(.+?)["\']?\s*$/m', $raw, $matches)) {
            return trim($matches[1]);
        }

        // Fallback: primer H1
        if (preg_match('/^#\s+(.+)$/m', $raw, $matches)) {
            return trim($matches[1]);
        }

        return 'Sin título';
    }

    private function stripFrontmatter(string $raw): string
    {
        // Remueve el bloque --- frontmatter ---
        $clean = preg_replace('/^---[\s\S]*?---\n/', '', $raw);

        // Remueve tags MDX (<Note>, <Steps>, etc.) pero conserva el texto
        $clean = preg_replace('/<[A-Z][a-zA-Z]*[^>]*>/', '', $clean ?? '');
        $clean = preg_replace('/<\/[A-Z][a-zA-Z]*>/', '', $clean ?? '');

        // Remueve markdown de código
        $clean = preg_replace('/```[\s\S]*?```/', '', $clean ?? '');

        // Limpia espacios múltiples
        $clean = preg_replace('/\n{3,}/', "\n\n", $clean ?? '');

        return trim($clean ?? '');
    }
}
