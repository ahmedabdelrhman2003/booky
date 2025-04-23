<?php

namespace App\Http\Resources\User;

use App\Enums\MediaTypes;
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
            'image' => $this->hasMedia(MediaTypes::USER_PICTURE->value) ? $this->getFirstMediaUrl(MediaTypes::USER_PICTURE->value) : null,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date,
            'phone' => $this->phone,
            'access_token' => $this->when($this->token, $this->token),
        ];
    }
}
