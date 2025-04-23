<?php

namespace App\Services\ContactUs;

use App\DTOs\V1\User\General\StoreContactUsDTO;
use App\Models\ContactUs;
use App\Repositories\Interfaces\ContactUsRepositoryInterface;

class ContactUsService
{
    public function __construct(
        protected ContactUsRepositoryInterface $repository,
    )
    {
    }

    public function store(StoreContactUsDTO $dto): ContactUs
    {
        return $this->repository->store($dto);
    }


}
