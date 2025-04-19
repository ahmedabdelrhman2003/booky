<?php

namespace App\Http\Resources\Book;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class ShowBookDetailsCollection extends ResourceCollection
{

    public function __construct(public $book,public $suggestedBooks)
    {
    }
    public function toArray(Request $request): array
    {
        return [
        'book' => new BookResource($this->book),
        'suggested_books' => BookResource::collection($this->suggestedBooks)
        ];
    }
}
