<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
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
            'email_or_mobile' => ['required', 'string'],
            'password' => [
                'required',
                'confirmed',
                'string',
                'min:8',
                'regex:/[A-Za-z]/',   // at least one letter
                'regex:/\d/',         // at least one number
                'regex:/[\W_]/',      // at least one special char
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email_or_mobile.required' => 'Email or mobile is required.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.regex' => 'Password must contain a letter, number and special character.',
        ];
    }
}
