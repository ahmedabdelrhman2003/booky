<?php

namespace App\Services\Payment;

use App\DTOs\V1\Payment\PaymobWebhookDTO;
use App\Models\Book;
use App\Services\Order\OrderService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class PaymobService implements PaymentServiceInterface
{
    public function __construct(readonly private OrderService $orderService)
    {
    }


    public function createIntention(Book $book): string
    {
        $user = auth('api')->user();
        $response = Http::withToken(config('services.paymob.secret_key'))
            ->post(config('services.paymob.base_url') . '/intention', [
                "amount" => $book->price * 100,
                "currency" => config('services.paymob.currency'),
                "payment_methods" => [(int)config('services.paymob.integration_id')],
                "items" => [
                    [
                        "name" => $book->title,
                        "amount" => $book->price * 100,
                    ]
                ],
                "billing_data" => [
                    "first_name" => $user->first_name,
                    "last_name" => $user->last_name,
                    "email" => $user->email,
                    "phone_number" => $user->phone,
                ]
            ]);
        if ($response->failed()) {
            $response->throw();
        }
        $this->orderService->store($book, $user->id, $response['intention_order_id']);
        $response = $response->json();
        return $response['client_secret'];
    }

    public function handleWebhook(PaymobWebhookDTO $dto): void
    {
        $order = $this->orderService->getByOrderId($dto->getOrderId());
        if (!$order) {
            Log::error('webhook return not found order_id', [ 'webhook' => $dto->getBody()]);
            return;
        }
        if ($dto->isSuccess())
        {$this->orderService->markAsPaid($order->id, $dto->getBody());
         $book = $order->book;        
         $author = $book->author;
         $price = $book->price_before_commission; 
        $author->wallet += $price;
        $author->save();
            Log::info('webhook return success', [ 'webhook' => $dto->getBody()]);
        }else{
            Log::error('webhook return failed', ['webhook' => $dto->getBody()]);
            $this->orderService->markAsFailed($order->id);
        }

    }
}
