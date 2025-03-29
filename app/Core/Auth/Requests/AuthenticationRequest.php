<?php

namespace App\Core\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthenticationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'email'     => 'required|email',
            'password'  => 'required|min:6'
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'    => 'Please provide your email',
            'password.required' => 'Please provide your password',
            'password.min'      => 'Password should contain minimum 6 letters',
        ];
    }
}
