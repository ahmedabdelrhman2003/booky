<?php

namespace App\Http\Resources\Book;

use App\Enums\MediaTypes;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'rate' => 5.0, //TODO: IMPLEMENT RATE
            'cover' => $this->getFirstMedia(MediaTypes::BOOK_COVER->value),
            'pages' => $this->pages,
            'language' => $this->language,
            'categories' => CategoryResource::collection($this->categories),
            'author' => new AuthorResource($this->author),
        ];
    }
}
