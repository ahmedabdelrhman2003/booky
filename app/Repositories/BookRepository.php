<?php

namespace App\Repositories;

use App\DTOs\V1\User\Book\SetUpDTO;
use App\Models\Book;
use App\Repositories\Interfaces\BookRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BookRepository implements BookRepositoryInterface
{

    public function findById($id): Book
    {
        return Book::active()->approved()->where('id', $id)->with('author', 'categories')->first();
    }

    public function all(SetUpDTO $dto): LengthAwarePaginator|Collection
    {
        $books = Book::query()->active()->approved()->with('author', 'categories')
            ->when($dto->getSearch(), function (Builder $query, $search) {
                $query->where('title', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%");
            })
            ->when($dto->getCategoryId(), fn(Builder $query, $categoryId) => $query->whereHas('categories', fn($q) => $q->where('categories.id', $categoryId))
            );

        return $dto->isPaginated() ? $books->paginate($dto->getLimit()) : $books->get();

    }
}
