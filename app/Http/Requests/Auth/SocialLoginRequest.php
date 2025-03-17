<?php

namespace App\Http\Requests\Auth;

use App\Enum\SocialTypes;
use App\Enums\SocialTypesEnum;
use App\Models\User;
use App\Rules\PhoneRule;
use App\Rules\PhoneUnique;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class SocialLoginRequest extends FormRequest
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
            'email' => ['string', 'email', 'required', 'max:255'],
            'social_type' => ['required', 'in:' . implode(',', array_column(SocialTypesEnum::cases(), 'value'))],
            'social_id' => ['required', 'string', 'max:255'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $socialId = $this->input('social_id');

            if (!User::where('social_id', $socialId)->exists()) {
                if (!$this->input('email')) {
                    $validator->errors()->add('email', __('The email field is required'));
                }
            }
        });
    }
}
