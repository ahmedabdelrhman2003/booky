<?php

namespace App\Repositories\Interfaces;

use App\DTOs\V1\User\Book\SetUpDTO;
use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface BookRepositoryInterface
{

    public function findById(int $id): ?Book;

    public function all(SetUpDTO $dto): LengthAwarePaginator|Collection;

    public function getByCategories(Book $book): Collection;

    public function favToggle(int $id,int $userId);

    public function rate(int $id, int $userId,int $rate);

}
