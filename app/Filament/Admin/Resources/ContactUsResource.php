<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ContactUsResource\Pages;
use App\Filament\Admin\Resources\ContactUsResource\RelationManagers;
use App\Models\ContactUs;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContactUsResource extends Resource
{
    protected static ?string $model = ContactUs::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getPluralLabel(): string
    {
        return 'Contact Us';
    }

    public static function getSlug(): string
    {
        return 'contact-us';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([

                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->disabled(),

                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->disabled(),

                Forms\Components\Textarea::make('message')
                    ->label('Message')->columnSpan(2)
                    ->disabled(),

                RichEditor::make('reply')
                    ->label('Reply')
                    ->nullable()->columnSpan(2),
                ])->columns(2)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('email')->searchable(),
                IconColumn::make('replied')
                    ->label('Replied')->boolean()
                    ->getStateUsing(fn ($record) => filled($record->reply)),
                TextColumn::make('created_at')->dateTime(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactUs::route('/'),
            'edit' => Pages\EditContactUs::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
