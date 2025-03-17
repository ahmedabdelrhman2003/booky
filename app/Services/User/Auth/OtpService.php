<?php

namespace App\Services\User\Auth;

use App\DTOs\V1\User\Auth\ReSendOtpDTO;
use App\DTOs\V1\User\Auth\VerifyOtpDTO;
use App\Enums\OTPActions;
use App\Exception\InvalidOtpException;
use App\Exception\OtpExpiredException;
use App\Exception\OtpNotFoundException;
use App\Exception\SameOldPhoneException;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\UserVerificationRepositoryInterface;
use Illuminate\Support\Facades\DB;

class OtpService
{

    public function __construct(
        private UserVerificationRepositoryInterface $userVerificationRepository,
        private UserRepositoryInterface $userRepository
    )
    {
    }

    public function sendOtp(ReSendOtpDTO $dto): ?string
    {
        DB::transaction(function () use (&$token, $dto) {
            $this->userVerificationRepository->clear($dto->getUserId());
            $token = $this->userVerificationRepository->create($dto)->token;
        });
        return $token;
    }

    public function reSendOtp(string $token): ?string
    {
        $record = $this->userVerificationRepository->getByToken($token);
        if (!$record){
            throw new \Exception('Not Found');
        }
        return $this->userVerificationRepository->updateToken($record->id)->token;
    }

    public function verifyOtp(VerifyOtpDTO $dto)
    {
        $token = $this->userVerificationRepository->getByToken($dto->getVerificationToken());
        if (!$token) {
            throw new \Exception('wrong data');
        }

        if ($token->isExpired()) {
            throw new \Exception('OTP Expired');
        }

        if($token->otp != $dto->getCode()){
            throw new \Exception('Invalid OTP');
        }

        $action = $token->action;
        $response = $token->user;

        DB::transaction(function () use ($action, $token, &$response) {
            $this->userVerificationRepository->clear($token->user_id);
            switch ($action) {
                case OtpActions::VERIFY_EMAIL->value:
                    $this->userRepository->verifyEmail($token->user_id);
                    break;
                case OtpActions::CHANGE_EMAIL->value:
                    $this->userRepository->changeEmail($token->user,$token->email);
                    $this->userRepository->verifyEmail($token->user_id);
                    break;
                case OtpActions::RESET_PASSWORD->value:
                    $this->userVerificationRepository->clear($token->user_id);
                    $response = $this->userVerificationRepository->createPasswordToken($token)->token;
                    break;
            }

        });

        return [$response,$action];
    }
}
