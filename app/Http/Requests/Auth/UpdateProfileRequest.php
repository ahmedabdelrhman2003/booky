<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateProfileRequest extends FormRequest
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
            'first_name' => [ 'string', 'min:2', 'max:50'],
            'last_name'  => [ 'string', 'min:2', 'max:50'],
            'birth_date'  => ['nullable', 'date','date_format:Y-m-d'],
            'gender'  => ['nullable', 'string', 'in:male,female'],
            'phone'  => ['nullable',  'min:8', 'max:15', Rule::unique('users', 'phone')->ignore(auth('api')->id()),'regex:/^[0-9]+$/'],
            'image'  => ['nullable',  'file', 'mimes:jpg,png,webp,jpeg','max:2048'],
        ];
    }
}
