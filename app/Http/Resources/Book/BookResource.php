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
            'rate' => (int)round($this->rate()),
            'is_rated' => (boolean)$this->isRated(),
            'cover' => $this->getFirstMediaUrl(MediaTypes::BOOK_COVER->value),
            'pages' => $this->pages,
            'language' => $this->language,
            'is_purchased' => (boolean)$this->isPurchased(),
            'is_favorite' => $this->isFavorite(),
            'categories' => CategoryResource::collection($this->categories),
            'author' => new AuthorResource($this->author),
            $this->mergeWhen($this->isPurchased(), [
                'record' => $this->getFirstMediaUrl(MediaTypes::BOOK_AUDIO->value),
                'pdf' => $this->getFirstMediaUrl(MediaTypes::BOOK_PDF->value),
            ]),
        ];
    }
}
