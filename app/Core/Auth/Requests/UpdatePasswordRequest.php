<?php

namespace App\Core\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'password' => 'required|min:6,same:confirmation_password',
            'confirmation_password' => 'required|min:6|same:password',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function messages(): array
    {
        return [
            'password.required' => 'Please provide your password',
            'password.min' => 'Password should contain minimum 6 Characters',
            'password.same' => 'Password and confirmation password should be same',
            'confirmation_password.required' => 'Please provide your confirmation password',
            'confirmation_password.min' => 'Confirmation password should contain minimum 6 Characters',
            'confirmation_password.same' => 'Password and confirmation password should be same',
        ];
    }
}
