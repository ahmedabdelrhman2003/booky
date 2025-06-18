<?php

namespace App\Filament\Pages;

use App\Models\Withdrawal;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class WalletPage extends Page
{
    public $wallet;
    public  $min_amount;
    protected static string $view = 'filament.pages.wallet-page';


    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Wallet';
    protected static ?string $slug = 'wallet';
    protected static ?string $title = 'Wallet';


    public function mount(): void
    {
        $this->wallet = auth()->user()->wallet;
        $this->min_amount  = 100;
    }

    public function requestMoney(): void
    {
        if ($this->wallet < $this->min_amount) {
            Notification::make()
                ->title('Insufficient balance.')
                ->danger()
                ->send();
        }else{
            Withdrawal::create([
                'author_id' => auth()->user()->id,
                'amount' =>$this->wallet ,
            ]);
            Notification::make()
                ->title('Your withdrawal request has been sent.')
                ->success()
                ->send();
        }


    }
}
