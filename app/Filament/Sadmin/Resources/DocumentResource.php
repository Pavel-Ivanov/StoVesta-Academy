<?php

namespace App\Filament\Sadmin\Resources;

use App\Enums\DocumentType;
use App\Filament\Sadmin\Resources\DocumentResource\Pages;
use App\Models\Document;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\Permission\Models\Role;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;
    protected static ?string $modelLabel = 'Документ';
    protected static ?string $pluralModelLabel = 'Документы';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Документы';
    protected static ?string $navigationGroup = 'Википедия';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->label('Название')
                ->required()
                ->maxLength(255),

            Forms\Components\Select::make('type')
                ->label('Тип документа')
                ->options(DocumentType::options())
                ->required(),

            Forms\Components\Select::make('status')
                ->label('Состояние')
                ->options(\App\Enums\DocumentStatus::options())
                ->default('draft')
                ->required(),

            Forms\Components\Toggle::make('is_published')
                ->label('Опубликован')
                ->default(false)
                ->inline(false),

            Forms\Components\Textarea::make('description')
                ->label('Описание')
                ->rows(4)
                ->columnSpanFull(),

            Forms\Components\Repeater::make('pages')
                ->label('Листы документа')
                ->relationship('pages')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Название листа')
                        ->maxLength(255),
                    Forms\Components\FileUpload::make('file_path')
                        ->label('Файл листа')
                        ->disk('public')
                        ->directory('documents')
                        ->required()
                        ->imagePreviewHeight('160')
                        ->openable()
                        ->downloadable(),
                    Forms\Components\TextInput::make('sort_order')
                        ->label('Порядок')
                        ->numeric()
                        ->default(0)
                        ->suffixIcon('heroicon-o-bars-3')
                        ->helperText('Меньше — раньше'),
                ])
                ->collapsed()
                ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                ->columns(2)
                ->minItems(1)
                ->required()
                ->helperText('Добавьте хотя бы один лист документа.'),

            Forms\Components\Select::make('visibility')
                ->label('Видимость (минимальная роль)')
                ->options(function () {
                    $roles = Role::query()->orderBy('name')->pluck('name', 'name')->toArray();
                    return array_merge(['all' => 'Все'], $roles);
                })
                ->default('all')
                ->required()
                ->helperText('Документ увидят пользователи, у которых есть эта роль. "Все" — виден всем'),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Тип')
                    ->state(fn (Document $r) => $r->type?->label() ?? '-')
                    ->badge(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Состояние')
                    ->state(fn (Document $r) => $r->status?->label() ?? '-')
                    ->badge(),
                Tables\Columns\TextColumn::make('pages_count')
                    ->label('Листов')
                    ->counts('pages')
                    ->formatStateUsing(function ($state) {
                        $pages = (int) $state;
                        return $pages >= 1 ? $pages : 0;
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('visibility')
                    ->label('Видимость')
                    ->badge(),
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Опубликован')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Изменён')
                    ->dateTime('d.m.Y H:i')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Тип документа')
                    ->options(DocumentType::options()),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Состояние')
                    ->options(\App\Enums\DocumentStatus::options()),
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Опубликован')
                    ->boolean(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->hiddenLabel()
                    ->tooltip('Просмотр'),
                Tables\Actions\EditAction::make()
                    ->hiddenLabel()
                    ->tooltip('Редактировать'),
                Tables\Actions\DeleteAction::make()
                    ->hiddenLabel()
                    ->tooltip('Удалить'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Нет документов')
            ->emptyStateDescription('Создайте первый документ, чтобы он появился в списке.')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
            'view' => Pages\ViewDocument::route('/{record}'),
        ];
    }
}
