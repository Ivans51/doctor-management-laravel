<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $arr = explode('@', $this->route()->getActionName());
        $method = $arr[1];  // The controller method

        return match ($method) {
            'loginApi', 'login' => [
                'email' => 'required|email',
                'password' => 'required|min:8',
            ],
            'register' => [
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8|confirmed',
            ],
            'forgot' => [
                'email' => 'required|email',
            ],
            'cf-turnstile-response' => 'required|string',
        };
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email is required.',
            'email.email' => 'Email is invalid.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Passwords must match.',
            'cf-turnstile-response.required' => 'ReCaptcha is required.',
        ];
    }
}
