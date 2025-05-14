<?php

namespace App\Http\Requests\Payment;

use App\Enums\BookStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateIntentRequest extends FormRequest
{
    /**
     * Allow all users to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Define the validation rules for the registration form.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'book_id' => ['required', 'integer',
                Rule::exists('books', 'id')
                    ->where('status', BookStatusEnum::APPROVED->value)
                    ->where('activation', 1),
                Rule::unique('orders', 'book_id')
                    ->where(function ($query) {
                        return $query->where('user_id', auth('api')->id());
                    }),
            ],
        ];
    }

}
