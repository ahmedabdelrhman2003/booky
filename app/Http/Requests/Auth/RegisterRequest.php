<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'min:2', 'max:50'],
            'last_name'  => ['required', 'string', 'min:2', 'max:50'],
            'email'      => ['required', 'string','email','unique:users,email'],
            'password'   => ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols() ]
        ];
    }
}
