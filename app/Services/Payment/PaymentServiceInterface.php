<?php

namespace App\Services\Payment;

use App\DTOs\V1\Payment\PaymobWebhookDTO;
use App\Models\Book;

interface PaymentServiceInterface
{

    public function createIntention(Book $book): string;

    public function handleWebhook(PaymobWebhookDTO $dto);

}
