<?php

namespace App\Repositories;

use App\Enums\OrderTypesEnum;
use App\Models\Book;
use App\Models\Order;
use App\Repositories\Interfaces\OrderRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface
{


    public function store(Book $book, int $user_id, int $order_id): Order
    {
        return Order::create([
            'user_id' => $user_id,
            'book_id' => $book->id,
            'price' => $book->price,
            'order_id' => $order_id,
        ]);
    }

    public function getByOrderId(?int $getOrderId): ?Order
    {
        return Order::where('order_id', $getOrderId)->first();
    }

    public function markAsPaid(string $id, ?array $getBody)
    {
        $order = Order::find($id);
        return $order->update([
            'status' => OrderTypesEnum::PAID->value,
            'webhook_response' => json_encode($getBody)
        ]);
    }

    public function markAsFailed(string $id)
    {
        return Order::where('id', $id)->update([
            'status' => OrderTypesEnum::FAILED->value,
        ]);
    }


}
