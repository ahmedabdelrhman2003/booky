<?php

namespace App\Http\Resources\Book;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class BookCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        $paginator = $this->resource instanceof LengthAwarePaginator ? $this->resource : null;

        return [
            'books' => BookResource::collection($this->collection),
            'pagination' => $paginator ? [
                'total' => $paginator->total(),
                'count' => $paginator->count(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'total_pages' => $paginator->lastPage()
            ] : null
        ];
    }
}
