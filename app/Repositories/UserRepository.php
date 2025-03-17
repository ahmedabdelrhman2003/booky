<?php

namespace App\Repositories;

use App\DTOs\V1\User\Auth\LoginSocialDTO;
use App\DTOs\V1\User\Profile\UpdateProfileDTO;
use App\Enum\MediaTypes;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserRepository implements UserRepositoryInterface
{
    public function verifyEmail($id)
    {
        return User::where('id',$id)->update(['email_verified_at' => now()]);
    }

    public function updateProfile(UpdateProfileDTO $dto, $id)
    {
        $data = array_filter(['first_name' => $dto->getFirstName(), 'last_name' => $dto->getLastName()]);
        $user = User::find($id);
        $user->update($data);
        return $user->refresh();
    }

    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    public function findById($id)
    {
        return User::where('id', $id)->first();
    }


    public function checkOldPassword($oldPassword, $newPassword): bool
    {
        return Hash::check($newPassword, $oldPassword);
    }

    public function resetPassword($user, $newPassword)
    {
        return $user->update(['password' => $newPassword]);
    }

    public function changeEmail($user, $email)
    {
        return $user->update(['email' => $email,]);

    }

    public function getBySocialId($dto): ?User
    {
        return User::where('social_id', $dto->getSocialId())->first();
    }

    public function createUserSocial(LoginSocialDTO $dto): ?User
    {
        return User::create(['social_id' => $dto->getSocialId(), 'social_type' => $dto->getSocialType(), 'email' => $dto->getEmail()]);
    }

    public function create($dto)
    {
        return User::create(['first_name' => $dto->getFirstName(), 'last_name' => $dto->getLastName(), 'email' => $dto->getEmail(), 'password' => $dto->getPassword()]);
    }

}
