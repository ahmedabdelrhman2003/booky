<?php

namespace App\Services\Book;

use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepository,
    )
    {
    }

    public function all(): Collection
    {
        return $this->categoryRepository->all();
    }


}
