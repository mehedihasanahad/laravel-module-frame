<?php

namespace App\Core\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'national_id' => 'required',
            'birth_date' => 'required|date',
            'roles' => 'required',
            'desk_id'=>'nullable',
            'terms' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Please enter your first name',
            'last_name.required' => 'Please enter your last name',
            'email.required' => 'Please enter your email',
            'password.required' => 'Please enter a password',
            'national_id.required' => 'Please enter your national ID',
            'birth_date.required' => 'Please enter your birth date',
            'roles.required' => 'Please select at least one role',
            'terms.required' => 'Please accept the terms and conditions',

        ];
    }
}
