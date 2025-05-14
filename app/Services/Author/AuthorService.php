<?php

namespace App\Services\Author;

use App\DTOs\V1\User\Author\ListAuthorsDTO;
use App\Repositories\Interfaces\AuthorRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AuthorService
{
    public function __construct(
        protected AuthorRepositoryInterface $authorRepository,
    )
    {
    }

    public function all(ListAuthorsDTO $dto): LengthAwarePaginator|Collection
    {
        return $this->authorRepository->all($dto);
    }

}
