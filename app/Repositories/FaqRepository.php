<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Faq;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\FaqRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class FaqRepository implements FaqRepositoryInterface
{


    public function all(): Collection
    {
        return Faq::all();
    }
}
