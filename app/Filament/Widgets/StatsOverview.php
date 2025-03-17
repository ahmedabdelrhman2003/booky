<?php

namespace App\Filament\Widgets;

use App\Models\Book;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('My Books', Book::query()->where('author_id',auth()->id())->count())
                ->description(' ')->color('primary')
                ->descriptionIcon('heroicon-m-users', IconPosition::After),

            Stat::make('Wallet Balance', auth()->user()->wallet ?? 0)
                ->description('Your current wallet balance')
                ->color('success')
                ->descriptionIcon('heroicon-m-currency-dollar', IconPosition::After)
        ];
    }
}
