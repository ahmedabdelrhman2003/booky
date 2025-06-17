<?php

namespace App\Filament\Admin\Resources;

use App\Enums\MediaTypes;
use App\Filament\Admin\Resources\AuthorResource\Pages;
use App\Filament\Admin\Resources\AuthorResource\RelationManagers;
use App\Filament\Admin\Resources\AuthorResource\RelationManagers\BooksRelationManager;
use App\Models\Author;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AuthorResource extends Resource
{
    protected static ?string $model = Author::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            SpatieMediaLibraryFileUpload::make(MediaTypes::AUTHOR_PICTURE->value)
                ->collection(MediaTypes::AUTHOR_PICTURE->value)
                ->openable()
                ->disabled()->columnSpan(2),

            TextInput::make('name')
                ->label('Name')
                ->disabled(),

            TextInput::make('email')
                ->label('Email')
                ->disabled(),

            TextInput::make('phone')
                ->label('Phone')
                ->disabled(),

            TextInput::make('iban')
                ->disabled(),

            TextInput::make('wallet')
                ->disabled(),



            DatePicker::make('created_at')
                ->label('Created At')
                ->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\SpatieMediaLibraryImageColumn::make(MediaTypes::AUTHOR_PICTURE->value)
                    ->collection(MediaTypes::AUTHOR_PICTURE->value)
                    ->label('Image'),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('phone')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->date()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            BooksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuthors::route('/'),
            'edit' => Pages\EditAuthor::route('/{record}/edit'),
        ];
    }
}
