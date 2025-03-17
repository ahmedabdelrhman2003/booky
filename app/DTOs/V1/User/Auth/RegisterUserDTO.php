<?php

namespace App\DTOs\V1\User\Auth;


class RegisterUserDTO
{
    protected ?string $first_name;
    protected ?string $last_name;
    protected ?string $email;
    protected ?string $password;

    public function __construct(public array $data)
    {
        if(!$this->map($this->data)){
            throw new \Exception();
        }
    }

    final public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    final public function getLastName(): ?string
    {
        return $this->last_name;
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
        $this->first_name = $data['first_name'];
        $this->last_name = $data['last_name'];
        $this->email = $data['email'];
        $this->password = $data['password'];
        return true;
    }
}
