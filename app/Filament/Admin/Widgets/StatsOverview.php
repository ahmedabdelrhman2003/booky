<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Author;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('Total number of registered users')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make('Total Authors', Author::count())
                ->description('Total number of authors')
                ->descriptionIcon('heroicon-m-pencil-square')
                ->color('success'),
        ];
    }
}
