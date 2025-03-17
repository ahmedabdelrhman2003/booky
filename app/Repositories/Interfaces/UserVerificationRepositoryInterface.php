<?php

namespace App\Repositories\Interfaces;

use App\DTOs\V1\User\Auth\ReSendOtpDTO;
use App\Models\UserVerification;
use Illuminate\Database\Eloquent\Model;

interface UserVerificationRepositoryInterface
{
    public function getByToken(string $token): ?UserVerification;
    public function getByPasswordToken(string $token): ?UserVerification;
    public function create(ReSendOtpDTO $dto): UserVerification;
    public function clear($userId): void;
    public function createPasswordToken(Model $token): UserVerification;

    public function updateToken(int $id);

}
