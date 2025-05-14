<?php

namespace App\Repositories\Interfaces;

use App\Models\Book;
use App\Models\Order;

interface OrderRepositoryInterface
{
    public function store(Book $book, int $user_id, int $order_id): Order;

    public function getByOrderId(?int $getOrderId): ?Order;

    public function markAsPaid(string $id, ?array $getBody);

    public function markAsFailed(string $id);
}
