<?php

namespace App\Repositories;

use App\DTOs\V1\User\Book\SetUpDTO;
use App\Models\Book;
use App\Models\Order;
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
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%$search%")
                        ->orWhere('description', 'like', "%$search%");
                });
            }
            )
            ->when($dto->getCategoryId(), fn(Builder $query, $id) => $query->whereHas('categories', fn($q) => $q->where('categories.id', $id))
            )
            ->when($dto->getAuthorId(), fn(Builder $query, $id) => $query->where('author_id', $id)
            )
            ->when($dto->getLanguage(), fn(Builder $query, $id) => $query->where('language', $dto->getLanguage())
            )
            ->when($dto->getPurchased(), fn(Builder $query, $id) => $query->purchased()
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

    public function favToggle(int $id, int $userId)
    {
        $book = Book::active()->find($id);
        return $book->favUsers()->toggle($userId);
    }

    public function rate(int $id, int $userId,int $rate)
    {
        return Order::where('book_id', $id)
            ->where('user_id', $userId)->paid()
            ->update(['rate'=>$rate]);
    }
}
