<?php

namespace App\Services\General;

use App\Repositories\Interfaces\FaqRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class FaqService
{
    public function __construct(
        protected FaqRepositoryInterface $repository,
    )
    {
    }

    public function all(): Collection
    {
        return $this->repository->all();
    }


}
