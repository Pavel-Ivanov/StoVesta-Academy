<?php

namespace App\Filament\Intern\Resources;

use App\Enums\DocumentType;
use App\Filament\Intern\Resources\FormsResource\Pages;
use App\Models\Document;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class FormsResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationLabel = 'Формы';
    protected static ?string $navigationIcon  = 'heroicon-o-clipboard-document';
    protected static ?int    $navigationSort  = 3;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Название')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('pages_count')
                    ->label('Листов')
                    ->counts('pages')
                    ->formatStateUsing(function ($state) {
                        $pages = (int) $state;
                        return $pages >= 1 ? $pages : 0;
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Добавлен')->dateTime('d.m.Y H:i')->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Просмотр')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Document $r) => route('documents.view', $r), shouldOpenInNewTab: true)
                    ->disabled(fn (Document $r) => $r->pages()->count() === 0),
                Tables\Actions\Action::make('download_all')
                    ->label('Скачать всё')
                    ->icon('heroicon-o-archive-box-arrow-down')
                    ->url(fn (Document $r) => route('documents.downloadAll', $r), shouldOpenInNewTab: true)
                    ->disabled(fn (Document $r) => $r->pages()->count() === 0),
            ])
            ->bulkActions([]);
    }

    public static function canCreate(): bool { return false; }
    public static function canEdit($record): bool { return false; }
    public static function canDelete($record): bool { return false; }

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();
        $roles = method_exists($user, 'getRoleNames') ? $user->getRoleNames()->toArray() : [];
        $allowed = array_merge(['all'], $roles);

        return parent::getEloquentQuery()
            ->where('type', DocumentType::FORM->value)
            ->whereIn('visibility', $allowed);
    }

    public static function getPages(): array
    {
        return [ 'index' => Pages\ListForms::route('/'), ];
    }
}
