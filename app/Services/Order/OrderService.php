<?php

namespace App\Services\Order;

use App\Models\Book;
use App\Models\Order;
use App\Repositories\Interfaces\OrderRepositoryInterface;


class OrderService
{
    public function __construct(private readonly OrderRepositoryInterface $orderRepository)
    {

    }

    public function store(Book $book, int $user_id, int $order_id): Order
    {
        return $this->orderRepository->store($book, $user_id, $order_id);
    }

    public function getByOrderId(?int $getOrderId): ?Order
    {
        return $this->orderRepository->getByOrderId($getOrderId);
    }

    public function markAsPaid(string $id, ?array $getBody)
    {
        return $this->orderRepository->markAsPaid($id, $getBody);
    }

    public function markAsFailed(string $id)
    {
        return $this->orderRepository->markAsFailed($id);
    }
}
