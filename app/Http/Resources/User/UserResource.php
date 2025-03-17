<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    public function __construct($resource, private readonly ?string $token = null)
    {
        parent::__construct($resource);
    }

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'image' => null,
            // new MediaResource($this->getFirstMedia(MediaTypes::PROFILE_PICTURE->value)) ,
            'access_token' => $this->when($this->token, $this->token),
        ];
    }
}
