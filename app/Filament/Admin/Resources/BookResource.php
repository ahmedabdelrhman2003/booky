<?php

namespace App\Filament\Admin\Resources;

use App\Enums\BookStatusEnum;
use App\Filament\Admin\Resources\BookResource\Pages;
use App\Filament\Admin\Resources\BookResource\RelationManagers;
use App\Models\Book;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('cover')
                    ->collection('book-cover')
                    ->label('Image'),

                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BooleanColumn::make('activation')
                    ->label('Active'),

                TextColumn::make('status')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'success' => BookStatusEnum::APPROVED->value,
                        'danger' => BookStatusEnum::REJECTED->value,
                        'warning' => BookStatusEnum::PENDING->value,
                    }) ->sortable()->searchable(),

                TextColumn::make('categories')
                    ->label('Categories')
                    ->getStateUsing(function ($record) {
                        return $record->categories->pluck('name')->join(', '); // Display categories as a comma-separated list
                    })
                    ->searchable()

            ])
            ->filters([
                //
            ])->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('activation')
                    ->label('Activation Status')
                    ->options([
                        1 => 'Active',
                        0 => 'Inactive',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options(collect(BookStatusEnum::cases())->mapWithKeys(fn($case) => [
                        $case->value => $case->getLabel(),
                    ])->toArray())
            ->actions([
                Tables\Actions\EditAction::make(),

                Action::make('Activate')
                    ->action(function (Book $record) {
                        $record->activation = true;
                        $record->save();
                    })->hidden(fn(Book $record): bool => $record->activation),

                Action::make('Deactivate')
                    ->action(function (Book $record) {
                        $record->activation = false;
                        $record->save();
                    })->visible(fn(Book $record): bool => $record->activation),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
