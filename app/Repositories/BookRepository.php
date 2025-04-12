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

    public function findById($id): ?Book
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
            ->when($dto->getCategoryId(), fn(Builder $query, $id) => $query->whereHas('categories', fn($q) => $q->where('categories.id', $id))
            )
            ->when($dto->getAuthorId(), fn(Builder $query, $id) => $query->where('author_id', $id)
            )
            ->when($dto->getLanguage(), fn(Builder $query, $id) => $query->where('language', $dto->getLanguage())
            );
        return $dto->isPaginated() ? $books->paginate($dto->getLimit()) : $books->get();

    }

    public function getByCategories(Book $book) :Collection
    {
        $ids = $book->categories->pluck('id')->toArray();
        return Book::query()->active()->approved()->where('id','!=',$book->id)
            ->whereHas('categories', function (Builder $query) use ($ids) {
                $query->whereIn('categories.id', $ids);
            })->inRandomOrder(5)->get();
    }

}
