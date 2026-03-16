<?php

declare(strict_types=1);

namespace App\Filament\Resources\BlogPostResource\Pages;

use App\Filament\Resources\BlogPostResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\SaveAction;
use Filament\Resources\Pages\EditRecord;

class EditBlogPost extends EditRecord
{
    protected static string $resource = BlogPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            SaveAction::make(),
            Action::make('ver_post')
                ->label('Ver post')
                ->icon('heroicon-o-eye')
                ->url(fn ($record) => url('/blog/' . $record->slug))
                ->openUrlInNewTab()
                ->color('gray'),
            DeleteAction::make(),
        ];
    }
}
