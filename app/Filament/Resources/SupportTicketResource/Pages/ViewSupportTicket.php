<?php

declare(strict_types=1);

namespace App\Filament\Resources\SupportTicketResource\Pages;

use App\Filament\Resources\SupportTicketResource;
use App\Models\AiDoc;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ViewSupportTicket extends ViewRecord
{
    protected static string $resource = SupportTicketResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ticket')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('subject')
                            ->label('Asunto')
                            ->columnSpanFull(),
                        TextEntry::make('message')
                            ->label('Mensaje')
                            ->columnSpanFull(),
                        TextEntry::make('category')
                            ->label('Categoría')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'billing' => 'warning',
                                'technical' => 'info',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'billing' => 'Facturación',
                                'technical' => 'Técnico',
                                'general' => 'General',
                                default => $state,
                            }),
                        TextEntry::make('status')
                            ->label('Estado')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'open' => 'danger',
                                'answered' => 'warning',
                                'closed' => 'success',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'open' => 'Abierto',
                                'answered' => 'Respondido',
                                'closed' => 'Cerrado',
                                default => $state,
                            }),
                        TextEntry::make('tenant.business_name')
                            ->label('Negocio'),
                        TextEntry::make('user.name')
                            ->label('Usuario'),
                        TextEntry::make('created_at')
                            ->label('Creado')
                            ->dateTime('d M Y H:i'),
                    ]),

                Section::make('Respuesta IA')
                    ->schema([
                        TextEntry::make('ai_suggestion')
                            ->label('Sugerencia generada por IA — edita antes de enviar')
                            ->placeholder('Sin sugerencia aún. Usa el botón "Sugerir respuesta con IA".')
                            ->columnSpanFull(),
                    ]),

                Section::make('Tu respuesta')
                    ->schema([
                        TextEntry::make('admin_reply')
                            ->label('Respuesta enviada')
                            ->placeholder('Sin respuesta aún.')
                            ->columnSpanFull(),
                        TextEntry::make('replied_at')
                            ->label('Respondido el')
                            ->dateTime('d M Y H:i')
                            ->placeholder('—'),
                    ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sugerir_ia')
                ->label('Sugerir respuesta con IA')
                ->icon('tabler--sparkles')
                ->color('info')
                ->action(function (): void {
                    $ticket = $this->record;

                    try {
                        $docs = AiDoc::search("{$ticket->subject} {$ticket->message}", 4);
                        $contexto = $docs->map(fn (AiDoc $d) => "## {$d->title}\n{$d->extractRelevantFragment($ticket->subject)}")->implode("\n\n");

                        $prompt = "Eres el agente de soporte de SYNTIweb.\nContexto de la plataforma:\n{$contexto}\n\nEl cliente pregunta (categoría: {$ticket->category}):\nAsunto: {$ticket->subject}\nMensaje: {$ticket->message}\n\nEscribe una respuesta de soporte en español: profesional, empática, concreta.\nMáximo 150 palabras. Solo la respuesta, sin saludos genéricos ni firmas.";

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
                        $ticket->update(['ai_suggestion' => $text]);

                        Notification::make()
                            ->title('Sugerencia lista')
                            ->body('Edita y envía cuando estés listo.')
                            ->success()
                            ->send();

                        $this->refreshFormData(['ai_suggestion']);
                    } catch (\Throwable $e) {
                        Notification::make()->title('Error generando sugerencia')->body($e->getMessage())->danger()->send();
                        Log::error('Ticket AI suggestion failed', ['error' => $e->getMessage(), 'ticket_id' => $ticket->id]);
                    }
                })
                ->visible(fn (): bool => $this->record->status !== 'closed'),

            Action::make('enviar_respuesta')
                ->label('Enviar respuesta')
                ->icon('tabler--send')
                ->color('success')
                ->form([
                    Textarea::make('admin_reply')
                        ->label('Tu respuesta')
                        ->required()
                        ->rows(5)
                        ->default(fn () => $this->record->ai_suggestion ?? $this->record->admin_reply ?? ''),
                ])
                ->action(function (array $data): void {
                    $this->record->update([
                        'admin_reply' => $data['admin_reply'],
                        'status' => 'answered',
                        'replied_at' => now(),
                    ]);

                    $this->record->user?->notify(new \App\Notifications\TicketAnsweredNotification($this->record));

                    Notification::make()
                        ->title('Respuesta enviada')
                        ->success()
                        ->send();

                    $this->refreshFormData(['admin_reply', 'status', 'replied_at']);
                })
                ->visible(fn (): bool => $this->record->status !== 'closed'),

            Action::make('cerrar_ticket')
                ->label('Cerrar ticket')
                ->icon('tabler--circle-check')
                ->color('gray')
                ->requiresConfirmation()
                ->action(function (): void {
                    $this->record->update(['status' => 'closed']);

                    Notification::make()
                        ->title('Ticket cerrado')
                        ->success()
                        ->send();

                    $this->refreshFormData(['status']);
                })
                ->visible(fn (): bool => $this->record->status !== 'closed'),
        ];
    }
}
