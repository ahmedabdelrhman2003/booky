<?php

namespace App\DTOs\V1\User\Auth;


class ResetPasswordDTO
{
    protected ?string $token;
    protected ?string $password;

    public function __construct(public array $data)
    {
        if(!$this->map($this->data)){
            throw new \Exception();
        }
    }

    final public function getToken(): ?string
    {
        return $this->token;
    }

    final public function getPassword(): ?string
    {
        return $this->password;
    }


    final protected function map(array $data): bool
    {
        $this->token = $data['token'];
        $this->password = $data['password'];
        return true;
    }
}
