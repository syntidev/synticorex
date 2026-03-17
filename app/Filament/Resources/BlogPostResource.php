<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\BlogPostResource\Pages\CreateBlogPost;
use App\Filament\Resources\BlogPostResource\Pages\EditBlogPost;
use App\Filament\Resources\BlogPostResource\Pages\ListBlogPosts;
use App\Models\BlogPost;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use UnitEnum;

class BlogPostResource extends Resource
{
    protected static ?string $model = BlogPost::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $modelLabel = 'Post';

    protected static ?string $pluralModelLabel = 'Blog';

    protected static UnitEnum|string|null $navigationGroup = 'Contenido';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Contenido')
                    ->schema([
                        TextInput::make('title')
                            ->label('Título')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state))),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Textarea::make('excerpt')
                            ->label('Extracto')
                            ->maxLength(500)
                            ->rows(2),
                        RichEditor::make('content')
                            ->label('Contenido')
                            ->toolbarButtons([
                                'bold', 'italic', 'underline', 'strike', 'link',
                                'h2', 'h3', 'bulletList', 'orderedList', 'blockquote',
                                'codeBlock', 'redo', 'undo',
                            ]),
                    ]),

                Section::make('Detalles del post')
                    ->columns(3)
                    ->schema([
                        Select::make('status')
                            ->label('Estado')
                            ->options([
                                'draft'     => 'Borrador',
                                'published' => 'Publicado',
                            ])
                            ->default('draft')
                            ->required(),
                        DatePicker::make('published_at')
                            ->label('Fecha de publicación'),
                        Toggle::make('featured')
                            ->label('Post destacado')
                            ->afterStateUpdated(function (bool $state): void {
                                if ($state) {
                                    BlogPost::where('featured', true)->update(['featured' => false]);
                                }
                            }),
                        FileUpload::make('featured_image')
                            ->label('Imagen 16:9')
                            ->image()
                            ->disk('public')
                            ->directory('blog')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->maxSize(2048)
                            ->columnSpanFull(),
                        Select::make('blog_category_id')
                            ->label('Categoría')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload(),
                        TextInput::make('author')
                            ->label('Autor')
                            ->default('Equipo SYNTIweb'),
                        TextInput::make('read_time')
                            ->label('Tiempo de lectura')
                            ->default('5 min'),
                    ]),

                Section::make('SEO')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Meta título')
                            ->maxLength(255),
                        Textarea::make('meta_description')
                            ->label('Meta descripción')
                            ->maxLength(320)
                            ->rows(2),
                        TagsInput::make('tags')
                            ->label('Tags SEO')
                            ->separator(',')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                ImageColumn::make('featured_image')
                    ->label('Imagen')
                    ->disk('public')
                    ->width(80)->height(50),
                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->limit(60),
                TextColumn::make('category.name')
                    ->label('Categoría')
                    ->badge(),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft'     => 'warning',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'published' => 'Publicado',
                        'draft'     => 'Borrador',
                        default     => $state,
                    }),
                IconColumn::make('featured')
                    ->label('Destacado')
                    ->boolean(),
                TextColumn::make('published_at')
                    ->label('Publicado')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('views')
                    ->label('Vistas')
                    ->sortable()
                    ->suffix(' lecturas'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'published' => 'Publicado',
                        'draft'     => 'Borrador',
                    ]),
                SelectFilter::make('blog_category_id')
                    ->label('Categoría')
                    ->relationship('category', 'name'),
            ])
            ->actions([
                EditAction::make(),
                Action::make('ver_post')
                    ->label('Ver post')
                    ->icon(Heroicon::OutlinedEye)
                    ->url(fn (BlogPost $record): string => route('blog.show', $record->slug))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('publicar')
                        ->label('Publicar')
                        ->icon(Heroicon::OutlinedCheck)
                        ->action(fn (Collection $records) => $records->each->update(['status' => 'published']))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),
                    BulkAction::make('despublicar')
                        ->label('Despublicar')
                        ->icon(Heroicon::OutlinedXMark)
                        ->action(fn (Collection $records) => $records->each->update(['status' => 'draft']))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListBlogPosts::route('/'),
            'create' => CreateBlogPost::route('/create'),
            'edit'   => EditBlogPost::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('category');
    }
}
