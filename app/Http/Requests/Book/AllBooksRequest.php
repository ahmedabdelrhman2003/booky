<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;

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
            'search' => ['nullable', 'string', 'min:1', 'max:30'],
            'category_id' => ['nullable','integer','exists:categories,id']
        ];
    }
}
