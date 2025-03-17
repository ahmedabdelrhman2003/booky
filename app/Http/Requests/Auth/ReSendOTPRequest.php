<?php

namespace App\Http\Requests\Auth;

use App\Enum\OtpActions;
use App\Rules\PhoneExists;
use App\Rules\PhoneRule;
use App\Rules\PhoneUnique;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReSendOTPRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'token' => ['required','string','min:60','max:100']
        ];
    }
}
