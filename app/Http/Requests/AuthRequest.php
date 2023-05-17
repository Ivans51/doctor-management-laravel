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
            'login' => [
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
        };
    }

    public function messages(): array
    {
        return [
            /*'email.required' => __('The :attribute field is required and must be at least :min characters.', ['min' => 5]),*/
        ];
    }
}
