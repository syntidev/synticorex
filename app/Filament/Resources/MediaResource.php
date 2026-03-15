<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\MediaResource\Pages\ListMedia;
use App\Models\MediaFile;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use UnitEnum;

class MediaResource extends Resource
{
    protected static ?string $model = MediaFile::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = 'Galería';

    protected static ?string $modelLabel = 'Media';

    protected static ?string $pluralModelLabel = 'Galería';

    protected static UnitEnum|string|null $navigationGroup = 'Contenido';

    protected static ?int $navigationSort = 15;

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                ImageColumn::make('preview')
                    ->label('Preview')
                    ->getStateUsing(fn (MediaFile $record): ?string => str_starts_with($record->mime_type ?? '', 'image/') ? $record->getUrl() : null)
                    ->square()
                    ->width(60)
                    ->height(60),
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('mime_type')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (?string $state): string => match (true) {
                        str_starts_with($state ?? '', 'image/') => 'success',
                        $state === 'application/pdf'            => 'warning',
                        default                                 => 'gray',
                    }),
                TextColumn::make('human_readable_size')
                    ->label('Tamaño'),
                TextColumn::make('created_at')
                    ->label('Subido')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                Action::make('copiar_url')
                    ->label('Copiar URL')
                    ->icon('heroicon-o-document-duplicate')
                    ->action(function (MediaFile $record): void {
                        Notification::make()
                            ->title('URL copiada')
                            ->body($record->getUrl())
                            ->success()
                            ->send();
                    }),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Action::make('upload')
                    ->label('Subir archivos')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->form([
                        FileUpload::make('files')
                            ->label('Archivos')
                            ->multiple()
                            ->disk('public')
                            ->directory('media-library')
                            ->acceptedFileTypes(['image/*', 'application/pdf'])
                            ->maxSize(5120),
                    ])
                    ->action(function (array $data): void {
                        $files = $data['files'] ?? [];
                        foreach ($files as $path) {
                            $fullPath = Storage::disk('public')->path($path);
                            $fileName = basename($path);
                            $mimeType = mime_content_type($fullPath) ?: 'application/octet-stream';
                            $size = filesize($fullPath) ?: 0;

                            MediaFile::create([
                                'name'      => pathinfo($fileName, PATHINFO_FILENAME),
                                'file_name' => $fileName,
                                'mime_type' => $mimeType,
                                'disk'      => 'public',
                                'size'      => $size,
                            ]);
                        }

                        Notification::make()
                            ->title(count($files) . ' archivo(s) subido(s)')
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMedia::route('/'),
        ];
    }
}
