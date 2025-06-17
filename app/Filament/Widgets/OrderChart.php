<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrderChart extends ChartWidget
{

    function getHeading(): string
    {
        $count = Order::whereHas('book', function ($query) {
            $query->where('author_id', auth()->id());
        })->count();

        return "Orders This Year ({$count})";
    }

    protected function getData(): array
    {
        $currentYear = now()->year;
        $months = range(1, 12);

        // Get orders per month for books of current author
        $ordersPerMonth = Order::selectRaw('MONTH(orders.created_at) as month, COUNT(*) as count')
            ->join('books', 'orders.book_id', '=', 'books.id')
            ->where('books.author_id', auth()->id())
            ->whereYear('orders.created_at', $currentYear)
            ->groupByRaw('MONTH(orders.created_at)')
            ->pluck('count', 'month');

        $data = [];

        foreach ($months as $month) {
            $data[] = $ordersPerMonth->get($month, 0);
        }

        $labels = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Number of Orders',
                    'data' => $data,
                    'borderColor' => '#36A2EB',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
