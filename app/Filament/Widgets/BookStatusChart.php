<?php

namespace App\Filament\Widgets;

use App\Models\Book;
use Filament\Widgets\ChartWidget;

class BookStatusChart extends ChartWidget
{


    function getHeading(): string
    {
        $count = Book::where('author_id',auth()->id())->count();
        return "Books ({$count})";
    }


    protected function getData(): array
    {
        $approved = Book::where('status', 'approved')->where('author_id',auth()->id())->count();
        $pending = Book::where('status', 'pending')->where('author_id',auth()->id())->count();
        $rejected = Book::where('status', 'rejected')->where('author_id',auth()->id())->count();
        return [
            'datasets' => [
                [
                    'label' => 'Books',
                    'data' => [
                        $approved,
                        $pending,
                        $rejected,
                    ],
                    'backgroundColor' => [
                        '#4CAF50', // Green for Approved
                        '#FFC107', // Amber for Pending
                        '#F44336', // Red for Rejected
                    ],
                    'borderColor' => [
                        '#388E3C', // Dark Green
                        '#FFA000', // Dark Amber
                        '#D32F2F', // Dark Red
                    ]
                ],
            ],
            'labels' => [
                'Approved',
                'Pending',
                'Rejected',
            ],

        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
