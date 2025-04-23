<?php

namespace App\DTOs\V1\User\General;

use Exception;

class StoreContactUsDTO
{
    protected string $name;
    protected string $email;
    protected string $message;

    public function __construct(public array $data)
    {
        if (!$this->map($this->data)) {
            throw new Exception();
        }
    }

    final protected function map(array $data): bool
    {
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->message = $data['message'];
        return true;
    }

    final public function getName(): string
    {
        return $this->name;
    }

    final public function getMessage(): string
    {
        return $this->message;
    }

    final public function getEmail(): string
    {
        return $this->email;
    }

}
