<?php

namespace App\Filament\Admin\Resources\AuthorResource\RelationManagers;

use App\Enums\BookStatusEnum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;


class BooksRelationManager extends RelationManager
{
    protected static string $relationship = 'books';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                //                Tables\Columns\SpatieMediaLibraryImageColumn::make('cover')
//                    ->collection('book-cover')
//                    ->label('Image'),

                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BooleanColumn::make('activation')
                    ->label('Active'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match (BookStatusEnum::from($state)) {
                        BookStatusEnum::APPROVED => 'success',
                        BookStatusEnum::REJECTED => 'danger',
                        BookStatusEnum::PENDING => 'warning',
                    })
                    ->sortable(),

                TextColumn::make('categories')
                    ->label('Categories')
                    ->getStateUsing(function ($record) {
                        return $record->categories->pluck('name')->join(', '); // Display categories as a comma-separated list
                    })


            ])
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-o-eye')
                    ->label('View Details')
                    ->url(fn ($record) => \App\Filament\Admin\Resources\BookResource::getUrl('view', ['record' => $record]))            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ]);
    }
}
