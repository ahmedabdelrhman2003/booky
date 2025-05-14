<?php

namespace App\DTOs\V1\Payment;

use Exception;

class PaymobWebhookDTO
{
    protected ?bool $isSuccess;
    protected ?int $orderId;
    protected ?array $body;



    public function __construct(public array $data)
    {
        if (!$this->map($this->data)) {
            throw new Exception();
        }
    }

    final protected function map(array $data): bool
    {
        $this->isSuccess = $data['obj']['success'];
        $this->orderId = $data['obj']['order']['id'];
        $this->body = $data;
        return true;
    }

    final public function isSuccess(): ?bool
    {
        return $this->isSuccess;
    }

    final public function getOrderId(): ?int
    {
        return $this->orderId;
    }

    public function getBody(): ?array
    {
        return $this->body;
    }

}
