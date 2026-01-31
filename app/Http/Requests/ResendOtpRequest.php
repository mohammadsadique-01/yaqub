<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;

class ResendOtpRequest extends FormRequest
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
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'title' => ['required', 'string', 'in:login,signup,forgotpassword'],
        ];
    }

    public function messages(): array
    {
        return [
            'userId.required' => 'User identifier is required.',
            'title.required' => 'OTP request type is required.',
            'title.in' => 'Invalid OTP request type.',
        ];
    }

    protected function prepareForValidation(): void
    {
        try {
            $this->merge([
                'user_id' => Crypt::decryptString($this->userId),
                'title' => strtolower(trim($this->title)),
            ]);
        } catch (DecryptException $e) {
            abort(422, 'Invalid or expired OTP request.');
        }
    }
}
