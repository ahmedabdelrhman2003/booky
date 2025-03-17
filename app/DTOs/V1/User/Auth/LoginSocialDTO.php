<?php

namespace App\DTOs\V1\User\Auth;

class LoginSocialDTO
{
    protected ?string $social_id;
    protected ?string $social_type;
    protected ?string $email;

    public function __construct(public array $data)
    {
        if(!$this->map($this->data)){
            throw new \Exception();
        }
    }

    final public function getSocialType(): ?string
    {
        return $this->social_type;
    }

    final public function getSocialId(): ?string
    {
        return $this->social_id;
    }

    final public function getEmail(): ?string
    {
        return $this->email;
    }


    final protected function map(array $data): bool
    {
        $this->email = $data['email'];
        $this->social_id = $data['social_id'];
        $this->social_type = $data['social_type'];
        return true;
    }
}
