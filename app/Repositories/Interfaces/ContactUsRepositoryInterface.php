<?php

namespace App\Repositories\Interfaces;

use App\DTOs\V1\User\General\StoreContactUsDTO;
use App\Models\ContactUs;
use Illuminate\Database\Eloquent\Collection;

interface ContactUsRepositoryInterface
{
    public function store(StoreContactUsDTO $dto): ContactUs;
}
