<?php

namespace App\Services\Book;

use App\DTOs\V1\User\Book\SetUpDTO;
use App\Models\Book;
use App\Repositories\Interfaces\BookRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

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

    public function getSuggestedBooks(Book $book): Collection
    {
        return $this->bookRepository->getByCategories($book);
    }

}
