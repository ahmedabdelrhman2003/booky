<?php

namespace App\DTOs\V1\User\Auth;


class LoginUserDTO
{
    protected ?string $email;
    protected ?string $password;

    public function __construct(public array $data)
    {
        if(!$this->map($this->data)){
            throw new \Exception();
        }
    }

    final public function getEmail(): ?string
    {
        return $this->email;
    }

    final public function getPassword(): ?string
    {
        return $this->password;
    }

    final protected function map(array $data): bool
    {
        $this->email = $data['email'];
        $this->password = $data['password'];
        return true;
    }
}
