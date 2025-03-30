<?php

namespace App\Http\Requests\Book;

use App\Enums\BookLangEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class AllBooksRequest extends FormRequest
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
            'pagination' => ['nullable', 'boolean'],
            'limit' => ['nullable', 'integer', 'min:1'],
            'filter' => ['nullable', 'array'],
            'filter.category_id' => ['nullable','integer','exists:categories,id'],
            'filter.author_id' => ['nullable','integer','exists:authors,id'],
            'filter.language' => ['nullable','string',new Enum(BookLangEnum::class)],
            'filter.search' => ['nullable', 'string', 'min:1', 'max:30'],
        ];
    }
}
