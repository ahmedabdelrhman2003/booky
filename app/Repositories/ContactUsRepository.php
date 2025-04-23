<?php

namespace App\Repositories;

use App\DTOs\V1\User\General\StoreContactUsDTO;
use App\Models\Category;
use App\Models\ContactUs;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\ContactUsRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ContactUsRepository implements ContactUsRepositoryInterface
{


    public function store(StoreContactUsDTO $dto): ContactUs
    {
        return ContactUs::create([
            'name' => $dto->getName(),
            'email' => $dto->getEmail(),
            'message' => $dto->getMessage(),
        ]);
    }
}
