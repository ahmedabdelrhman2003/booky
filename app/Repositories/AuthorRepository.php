<?php

namespace App\Repositories;

use App\DTOs\V1\User\Author\ListAuthorsDTO;
use App\Models\Author;
use App\Repositories\Interfaces\AuthorRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AuthorRepository implements AuthorRepositoryInterface
{

    public function all(ListAuthorsDTO $dto): LengthAwarePaginator|Collection
    {
        $authors = Author::hasActiveBooks();
        return $dto->isPaginated() ? $authors->paginate($dto->getLimit()) : $authors->get();

    }
}
