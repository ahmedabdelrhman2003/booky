<?php

namespace App\Rules;

use App\Models\Book;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PurchasedBook implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
    }

    public function passes($attribute, $value): bool
    {
        return Book::purchased()->where('id', $value)->exists();
    }

    public function message(): string
    {
        return 'You have not purchased this book.';
    }
}
