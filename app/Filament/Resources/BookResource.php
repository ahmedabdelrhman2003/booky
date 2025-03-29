<?php

namespace App\Filament\Resources;

use App\Enums\BookLangEnum;
use App\Enums\BookStatusEnum;
use App\Filament\Resources\BookResource\Pages;
use App\Filament\Resources\BookResource\RelationManagers;
use App\Jobs\UploadAudioBook;
use App\Models\Blog;
use App\Models\Book;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                Forms\Components\TextInput::make('title')->required()->maxLength(255)->columnSpan(2),

                Forms\Components\RichEditor::make('description')->maxLength(65365)->required()->columnSpan(2),

                Forms\Components\TextInput::make('pages')
                    ->label('number of pages')->required()->integer(),

                Forms\Components\TextInput::make('price_before_commission')->label('Price')->required()->integer(),

                    Select::make('categories')
                        ->relationship('categories', 'name') // Relates to categories
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->required(),

                    Select::make('language')->required()
                        ->options([
                            'english' => 'english',
                            'arabic' => 'arabic',
                        ]),
                    Forms\Components\Hidden::make('author_id')->default(auth()->id()),

                Forms\Components\SpatieMediaLibraryFileUpload::make('cover')->image()->columnSpan(2)
                    ->collection('book-cover')->required()->label('book cover'),

                Forms\Components\SpatieMediaLibraryFileUpload::make('book')->openable()->downloadable()
                    ->acceptedFileTypes(['application/pdf'])->maxSize(10000)->collection('book')->required(),

                Forms\Components\SpatieMediaLibraryFileUpload::make('audio')->label('audio book')->downloadable()
                  ->maxSize(10000)->collection('audio'),

                ])->columns(2)->columnSpan(2)

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
                ->sortable()->searchable(),

                TextColumn::make('categories')
                    ->label('Categories')
                    ->getStateUsing(function ($record) {
                        return $record->categories->pluck('name')->join(', '); // Display categories as a comma-separated list
                    })->default('----')
                    ->searchable()

            ])->modifyQueryUsing(function (Builder $query) {
                    return $query->where('author_id', auth()->id());
            })
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('activation')
                    ->label('Activation Status')
                    ->options([
                        1 => 'Active',
                        0 => 'Inactive',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options(BookStatusEnum::class)
            ])
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
                ]),
            ]);
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

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['author_id'] = auth()->id();
        $data['status'] = BookStatusEnum::PENDING->value;

        return $data;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('author_id',auth()->id());
    }

}
