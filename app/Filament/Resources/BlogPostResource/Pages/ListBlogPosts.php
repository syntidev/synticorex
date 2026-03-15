<?php

declare(strict_types=1);

namespace App\Filament\Resources\BlogPostResource\Pages;

use App\Filament\Resources\BlogPostResource;
use App\Models\AiDoc;
use App\Models\BlogPost;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ListBlogPosts extends ListRecords
{
    protected static string $resource = BlogPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generar_con_ia')
                ->label('Generar con IA')
                ->icon('tabler--sparkles')
                ->color('info')
                ->form([
                    Textarea::make('tema')
                        ->label('Tema o título del artículo')
                        ->required()
                        ->rows(3),
                    Select::make('producto')
                        ->label('Producto')
                        ->options([
                            'shared' => 'Shared',
                            'studio' => 'Studio',
                            'food' => 'Food',
                            'cat' => 'Cat',
                        ])
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $tema = $data['tema'];
                    $producto = $data['producto'];

                    try {
                        $docs = AiDoc::search($tema, 5, $producto);
                        $contexto = $docs->map(fn (AiDoc $d) => "## {$d->title}\n{$d->extractRelevantFragment($tema)}")->implode("\n\n");

                        $prompt = "Eres el redactor de SYNTIweb, plataforma SaaS venezolana.\nBasándote en este contexto:\n{$contexto}\n\nEscribe un artículo de blog profesional en español sobre: {$tema}\n\nResponde SOLO en JSON válido con esta estructura exacta:\n{\n  \"titulo\": \"título del artículo\",\n  \"categoria\": \"una palabra: producto|tutorial|novedad|consejo\",\n  \"introduccion\": \"párrafo introductorio de 2-3 oraciones\",\n  \"cuerpo\": \"cuerpo del artículo en HTML básico (p, ul, h3), mínimo 300 palabras\",\n  \"meta_descripcion\": \"descripción SEO de máximo 155 caracteres\"\n}";

                        $response = Http::withHeaders([
                            'x-api-key' => config('services.anthropic.key'),
                            'anthropic-version' => '2023-06-01',
                            'Content-Type' => 'application/json',
                        ])->timeout(30)->post('https://api.anthropic.com/v1/messages', [
                            'model' => 'claude-haiku-4-5-20251001',
                            'max_tokens' => 1024,
                            'messages' => [['role' => 'user', 'content' => $prompt]],
                        ]);

                        if (!$response->successful()) {
                            Notification::make()->title('Error al contactar la IA')->body('HTTP ' . $response->status())->danger()->send();
                            return;
                        }

                        $text = $response->json('content.0.text', '');

                        if (preg_match('/\{[\s\S]*\}/', $text, $matches)) {
                            $text = $matches[0];
                        }

                        $parsed = json_decode($text, true);

                        if (!is_array($parsed) || !isset($parsed['titulo'], $parsed['cuerpo'])) {
                            Notification::make()->title('Error al parsear respuesta IA')->body('JSON inválido en la respuesta.')->danger()->send();
                            Log::warning('Blog AI: invalid JSON', ['raw' => $text]);
                            return;
                        }

                        $post = BlogPost::create([
                            'title' => $parsed['titulo'],
                            'slug' => Str::slug($parsed['titulo']),
                            'excerpt' => $parsed['introduccion'] ?? null,
                            'content' => $parsed['cuerpo'],
                            'meta_title' => $parsed['titulo'],
                            'meta_description' => $parsed['meta_descripcion'] ?? null,
                            'status' => 'draft',
                            'author' => 'Equipo SYNTIweb',
                            'read_time' => '5 min',
                        ]);

                        Notification::make()
                            ->title('Borrador generado')
                            ->body('Revisa y agrega imagen antes de publicar.')
                            ->success()
                            ->send();

                        $this->redirect(BlogPostResource::getUrl('edit', ['record' => $post]));
                    } catch (\Throwable $e) {
                        Notification::make()->title('Error generando artículo')->body($e->getMessage())->danger()->send();
                        Log::error('Blog AI generation failed', ['error' => $e->getMessage()]);
                    }
                }),
            CreateAction::make(),
        ];
    }
}
