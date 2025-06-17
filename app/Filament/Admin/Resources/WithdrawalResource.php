<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\WithdrawalResource\Pages;
use App\Filament\Admin\Resources\WithdrawalResource\RelationManagers;
use App\Models\Withdrawal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WithdrawalResource extends Resource
{
    protected static ?string $model = Withdrawal::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function form(Form $form): Form
    {

        return $form->schema([
            Forms\Components\Select::make('author_id')
                ->relationship('author', 'name')
                ->label('Author'),


            Forms\Components\TextInput::make('author.iban')
                ->label('Iban')
                ->disabled()
                ->afterStateHydrated(fn($set, $record) => $set('author.iban', $record?->author?->iban)),

            Forms\Components\TextInput::make('amount'),

            Forms\Components\Select::make('author_id')
                ->relationship('author', 'email')->label('Email'),

            Forms\Components\Select::make('author_id')
                ->relationship('author', 'phone')->label('Phone'),

            Forms\Components\Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'approved' => 'Accepted',
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('author.name')->label('Author'),
                Tables\Columns\TextColumn::make('author.iban')->label('Iban')->searchable(),
                Tables\Columns\TextColumn::make('amount')->sortable()->money('EPG', true),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                    }),
                Tables\Columns\TextColumn::make('created_at')->since()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Accepted',
                    ])
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->modalActions([
                    Tables\Actions\Action::make('Accept')
                        ->visible(fn($record) => $record->status === 'pending')
                        ->action(function (Withdrawal $record) {
                            $record->status = 'approved';
                            $record->save();

                            if ($record->author) {
                                $record->author->wallet = 0;
                                $record->author->save();
                            }
                        })
                        ->color('primary')
                        ->icon('heroicon-o-check-circle'),

                    // Keep the default close action
                    Tables\Actions\Action::make('close')
                        ->label('Close')
                        ->color('gray'),
                ]),
                Tables\Actions\Action::make('Accept')
                    ->visible(fn($record) => $record->status === 'pending')
                    ->action(function (Withdrawal $record) {
                        $record->status = 'approved';
                        $record->save();
                        if ($record->author) {
                            $record->author->wallet = 0;
                            $record->author->save();
                        }
                    })->requiresConfirmation()
                    ->color('primary')
                    ->icon('heroicon-o-check-circle'),
            ])->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('Accept')
                        ->action(function (array $records) {
                            foreach ($records as $record) {
                                if ($record->status === 'pending') {
                                    $record->status = 'approved';
                                    $record->save();
                                    if ($record->author) {
                                        $record->author->wallet = 0;
                                        $record->author->save();
                                    }
                                }
                            }
                        })->requiresConfirmation()
                        ->color('primary')
                        ->icon('heroicon-o-check-circle'),
                ]),
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
            'index' => Pages\ListWithdrawals::route('/'),
        ];
    }
}
