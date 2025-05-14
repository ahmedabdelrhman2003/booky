<?php

namespace App\Repositories\Interfaces;

use App\DTOs\V1\User\Author\ListAuthorsDTO;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface AuthorRepositoryInterface
{


    public function all(ListAuthorsDTO $dto): LengthAwarePaginator|Collection;


}
