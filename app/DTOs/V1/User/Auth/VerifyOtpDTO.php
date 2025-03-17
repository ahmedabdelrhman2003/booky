<?php

namespace App\DTOs\V1\User\Auth;

class VerifyOtpDTO
{
    protected string $verificationToken;
    protected string $code;

    public function __construct(public array $data)
    {
        if(!$this->map($this->data)){
            throw new \Exception();
        }
    }

    final protected function map(array $data): bool
    {
        $this->verificationToken = $data['verification_token'];
        $this->code = $data['code'];
        return true;
    }

    final public function getVerificationToken(): ?string
    {
        return $this->verificationToken;
    }

    final public function getCode(): ?string
    {
        return $this->code;
    }
}
