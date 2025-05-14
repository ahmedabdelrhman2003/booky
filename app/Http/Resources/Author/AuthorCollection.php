<?php

namespace App\Http\Resources\Author;

use App\Http\Resources\Book\AuthorResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class AuthorCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        $paginator = $this->resource instanceof LengthAwarePaginator ? $this->resource : null;

        return [
            'authors' => AuthorResource::collection($this->collection),
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
