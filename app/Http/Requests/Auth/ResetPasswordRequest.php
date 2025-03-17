<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends FormRequest
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
            'token'      => ['required', 'string'],
            'password'            => ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols() ]
        ];
    }
}
