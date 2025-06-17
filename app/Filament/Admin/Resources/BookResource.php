<?php

namespace App\Filament\Admin\Resources;

use App\Enums\BookStatusEnum;
use App\Filament\Admin\Resources\BookResource\Pages;
use App\Filament\Admin\Resources\BookResource\RelationManagers;
use App\Models\Book;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
            ])->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('activation')
                    ->label('Activation Status')
                    ->options([
                        1 => 'Active',
                        0 => 'Inactive',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        BookStatusEnum::PENDING->value => 'Pending',
                        BookStatusEnum::APPROVED->value => 'Approved',
                        BookStatusEnum::REJECTED->value => 'Rejected',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

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

                Action::make('Approve')
                    ->action(function (Book $record) {
                        $record->status = 'approved';
                        $record->save();
                    })->hidden(fn(Book $record) => $record->status === 'approved'),

                Action::make('Reject')
                    ->action(function (Book $record) {
                        $record->status = 'rejected';
                        $record->save();
                    })->hidden(fn(Book $record) => $record->status === 'rejected'),
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
            'view' => Pages\ViewBook::route('/{record}'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([

                Section::make('')->schema([
                    SpatieMediaLibraryImageEntry::make('book-cover')
                        ->label('Cover Image')
                        ->collection('book-cover')->columnSpan(1),

                    Infolists\Components\TextEntry::make('title')
                        ->copyable()->weight('bold')->color('primary'),

                    Infolists\Components\TextEntry::make('author.name')->label('Author Name'),

                    Infolists\Components\TextEntry::make('categories')
                        ->label('Categories')
                        ->getStateUsing(function ($record) {
                            return $record->categories->pluck('name')->join(', ');
                        }),

                    Infolists\Components\TextEntry::make('description')
                        ->copyable()->columnSpan(2),

                    Infolists\Components\TextEntry::make('status')
                        ->label('Status')
                        ->badge()
                        ->color(fn(string $state): string => match (BookStatusEnum::from($state)) {
                            BookStatusEnum::APPROVED => 'success',
                            BookStatusEnum::REJECTED => 'danger',
                            BookStatusEnum::PENDING => 'warning',
                        }),

                    Infolists\Components\IconEntry::make('activation')
                        ->label('Activation')
                        ->trueIcon('heroicon-o-check-circle')
                        ->falseIcon('heroicon-o-x-circle')
                        ->alignCenter(),

                    Infolists\Components\TextEntry::make('pages'),
                    Infolists\Components\TextEntry::make('language'),

                    Infolists\Components\TextEntry::make('created_at')
                        ->label('Created at')->date(),

                    Infolists\Components\TextEntry::make('updated_at')
                        ->label('Last Updated')->date(),

                    SpatieMediaLibraryImageEntry::make('book')->openUrlInNewTab()
                        ->label('PDF File')->collection('book')->hint(''),

                    SpatieMediaLibraryImageEntry::make('audio')->openUrlInNewTab()
                        ->label('Audio Book')->collection('audio'),


                    Actions::make([
                        Actions\Action::make('Approve')
                            ->requiresConfirmation()
                            ->color('success')
                            ->icon('heroicon-m-star')
                            ->action(function (Book $record) {
                                $record->status = 'approved';
                                $record->save();
                            })->hidden(fn(Book $record) => $record->status === 'approved'),

                        Actions\Action::make('Reject')->color('danger')
                            ->requiresConfirmation()
                            ->icon('heroicon-m-x-mark')
                            ->action(function (Book $record) {
                                $record->status = 'rejected';
                                $record->save();
                            })->hidden(fn(Book $record) => $record->status === 'rejected'),
                    ])
                ])->columns(2),

            ]);
    }
}


