<?php

namespace App\Repositories\Interfaces;

use App\DTOs\V1\User\Auth\LoginSocialDTO;
use App\DTOs\V1\User\Auth\RegisterUserDTO;
use App\DTOs\V1\User\Profile\UpdateProfileDTO;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

interface UserRepositoryInterface
{
    public function create(RegisterUserDTO $dto);

    public function verifyEmail(int $id);

    public function findByEmail(string $email);

    public function updateProfile(UpdateProfileDTO $dto, $id);

    public function findById(int $id);
    public function checkOldPassword($oldPassword, $newPassword): bool;

    public function resetPassword($user, $newPassword);

    public function changeEmail($user, $email);

    public function getBySocialId(LoginSocialDTO $dto): ?User;

    public function createUserSocial(LoginSocialDTO $dto): ?User;


}
