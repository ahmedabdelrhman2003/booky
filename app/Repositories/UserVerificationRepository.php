<?php

namespace App\Repositories;


use App\DTOs\V1\User\Auth\ReSendOtpDTO;
use App\Enums\OTPActions;
use App\Models\UserVerification;
use App\Repositories\Interfaces\UserVerificationRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserVerificationRepository implements UserVerificationRepositoryInterface
{
    public function getByToken(string $token): ?UserVerification
    {
        return UserVerification::where('token', $token)->first();
    }

    public function createPasswordToken(Model $token): UserVerification
    {
        return UserVerification::create([
            'user_id' => $token->user_id,
            'otp' => 1234,
            'token' => Str::random(64),
            'action' => $token->action,
            'expired_at' => now()->addMinutes(5)
        ]);
    }

    public function create(ReSendOtpDTO $dto): UserVerification
    {
        return UserVerification::create([
            'user_id' => $dto->getUserId(),
            'otp' => 1234,
            'token' => Str::random(64),
            'action' => $dto->getAction(),
            'expired_at' => now()->addMinutes(5)
        ]);
    }

    public function clear($userId): void
    {
        UserVerification::where('user_id', $userId)->delete();
    }

    public function getByPasswordToken(string $token): ?UserVerification
    {
        return UserVerification::where('token', $token)->where('action', OTPActions::RESET_PASSWORD->value)->first();

    }

    public function updateToken(int $id)
    {
        $record = UserVerification::find($id);
        $record->update([
            'otp' => 1234,
            'token' => Str::random(64),
            'expired_at' => now()->addMinutes(5)
        ]);
        return $record->refresh();
    }
}
