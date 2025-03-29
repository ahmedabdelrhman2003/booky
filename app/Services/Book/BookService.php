<?php

namespace App\Services\Book;

use App\DTOs\V1\User\Book\SetUpDTO;
use App\Models\Book;
use App\Repositories\Interfaces\BookRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Ramsey\Collection\Collection;

class BookService
{
    public function __construct(
        protected BookRepositoryInterface $bookRepository,
    )
    {
    }

    public function all(SetUpDTO $dto): LengthAwarePaginator|Collection
    {
        return $this->bookRepository->all($dto);
    }

    public function findById(int $id): Book
    {
        return $this->bookRepository->findById($id);
    }

}
