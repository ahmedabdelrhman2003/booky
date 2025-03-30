<?php

namespace App\Http\Requests\Book;

use App\Enum\StatusEnum;
use App\Enums\BookStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetBookRequest extends FormRequest
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
            'id' => ['required', 'integer',
                Rule::exists('books', 'id')
                    ->where('status', BookStatusEnum::APPROVED->value)
                    ->where('activation', 1)]
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }
}
