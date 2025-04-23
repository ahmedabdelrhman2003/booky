<?php

namespace App\Services\User\Auth;

use App\DTOs\V1\User\Auth\LoginSocialDTO;
use App\DTOs\V1\User\Auth\LoginUserDTO;
use App\DTOs\V1\User\Auth\RegisterUserDTO;
use App\DTOs\V1\User\Auth\ResetPasswordDTO;
use App\DTOs\V1\User\Auth\UpdateProfileDTO;
use App\DTOs\V1\User\Profile\UpdatePasswordDTO;
use App\Enums\MediaTypes;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\UserVerificationRepositoryInterface;
use App\Repositories\UserVerificationRepository;
use App\Services\User\UploadFileService;
use http\Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        private UserVerificationRepositoryInterface $userVerificationRepository,
        protected UploadFileService $uploadFileService)
    {
    }

    public function register(RegisterUserDTO $dto)
    {
        return $this->userRepository->create($dto);
    }

    public function login(LoginUserDTO $dto)
    {
        $user = $this->userRepository->findByEmail($dto->getEmail());
        if (!$user || !Hash::check($dto->getPassword(), $user->password)) {
            throw new \Exception('wrong email or password');
        }
        return $user;
    }

    public function getByEmail(string $email)
    {
        return $this->userRepository->findByEmail($email);

    }

    public function resetPassword(ResetPasswordDTO $dto)
    {
        $passwordResetToken = $this->userVerificationRepository->getByToken($dto->getToken());

        if (!$passwordResetToken) {
            throw new \Exception('wrong token');
        }

        $user = $this->userRepository->findById($passwordResetToken->user_id);

        if (!$user) {
            throw new \Exception('can not reset password');
        }

        $checkOldPassword = $this->userRepository->checkOldPassword($user->password, $dto->getPassword());

        if ($checkOldPassword) {
            throw new \Exception('you can not use the old password');
        }
        DB::transaction(function () use ($user, $dto, $passwordResetToken) {
            $this->userRepository->resetPassword($user, $dto->getPassword());
            $this->userVerificationRepository->clear($user->id);
        });

        return $user;
    }

    public function changePassword(UpdatePasswordDTO $dto)
    {
        $user = auth('api')->user();

        if (!Hash::check($dto->getOldPassword(), $user->password)) {
            throw new WrongPasswordException();
        }

        if ($this->userRepository->checkOldPassword($user->password, $dto->getPassword())){
            throw new CannotUseOldPasswordException();
        }

        return $this->userRepository->resetPassword($user, $dto->getPassword());
    }

    public function generateToken(User $user): string
    {
        return $user->createToken('api')->plainTextToken;
    }

    public function getProfile(): Authenticatable
    {
        return auth('api')->user();
    }

    public function updateProfile(UpdateProfileDTO $dto): User
    {
        $user = $this->userRepository->updateProfile($dto, auth('api')->id());
        try {
            if ($dto->getImage()) {
                $user
                    ->addMedia($dto->getImage())
                    ->toMediaCollection(MediaTypes::USER_PICTURE->value);
            }
            return $user;
        } catch (\Exception $e) {

            Log::error('failed to upload image  with error: ' . $e->getMessage());
        }
        return $user;


    }

    public function logout()
    {
        auth('api')->user()->currentAccessToken()->delete();
    }

    public function deleteUser()
    {
        auth('api')->user()->delete();
    }


    public function socialLogin(LoginSocialDTO $dto): ?User
    {
        $user = $this->userRepository->getBySocialId($dto);
        $email = $this->userRepository->findByEmail($dto->getEmail());
        if ($email &&!$email->is_social){
            throw new  \Exception('Email already in use');
        }
        if (!$user) {
            return $this->userRepository->createUserSocial($dto);
        }

        if ($user->social_type == $dto->getSocialType()) {

            return $user;
        }
        throw new \Exception('different social type');
    }

    public function getFavBooks()
    {
        $id = auth('api')->id();
        return $this->userRepository->getFavBooks($id);
    }

}
