<?php

namespace App\Http\Requests\Book;

use App\Enum\StatusEnum;
use App\Enums\BookStatusEnum;
use App\Rules\PurchasedBook;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RateBookRequest extends FormRequest
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
            'id' => ['required', 'integer',new PurchasedBook()],
            'rate' => ['required', 'integer', 'between:1,5'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }
}
