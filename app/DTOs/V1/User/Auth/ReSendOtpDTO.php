<?php

namespace App\DTOs\V1\User\Auth;

use Exception;

class ReSendOtpDTO
{
    protected string $email;
    protected string $action;
    protected string $userId;

    public function __construct(public array $data)
    {
        if (!$this->map($this->data)) {
            throw new Exception();
        }
    }

    final protected function map(array $data): bool
    {
        $this->email = $data['email'];
        $this->action = $data['action'];
        $this->userId = isset($data['user_id']) ? $data['user_id'] : null;
        return true;
    }

    final public function getEmail(): ?string
    {
        return $this->email;
    }

    final public function getAction(): ?string
    {
        return $this->action;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }
}
