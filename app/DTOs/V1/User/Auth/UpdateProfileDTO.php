<?php

namespace App\DTOs\V1\User\Auth;

use Illuminate\Http\UploadedFile;

class UpdateProfileDTO
{
    protected ?string $first_name =null;
    protected ?string $last_name = null;
    protected ?string $phone = null;
    protected ?string $gender= null;
    protected ?string $birth_date = null;
    protected ?UploadedFile   $image = null;

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

    final public function getBirthDate(): ?string
    {
        return $this->birth_date;
    }

    final public function getGender(): ?string
    {
        return $this->gender;
    }

    final public function getPhone(): ?string
    {
        return $this->phone;
    }

    final public function getImage(): ?UploadedFile
    {
        return $this->image;
    }

    final protected function map(array $data): bool
    {
        $this->first_name = $data['first_name'] ?? $this->first_name;
        $this->last_name = $data['last_name'] ?? $this->last_name;
        $this->birth_date = $data['birth_date'] ?? $this->birth_date;
        $this->gender = $data['gender'] ?? $this->gender;
        $this->phone = $data['phone'] ?? $this->phone;
        $this->image = $data['image'] ?? $this->image;
        return true;
    }



}
