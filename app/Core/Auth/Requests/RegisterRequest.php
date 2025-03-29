<?php

namespace App\Core\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
//            'password' => 'required|min:8',
//            'confirmation_password' => 'required|same:password',
            'division_id' => 'required',
            'district_id' => 'required',
            'upzila_id' => 'required',
            'mobile' => 'required|regex:/(01)[0-9]{9}/',
            'national_id' => 'required',
            'terms_and_condition' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your name',
            'email.required' => 'Please enter your email',
            'email.unique' => 'This email is already taken',
//            'password.required' => 'Please enter your password',
//            'password.min' => 'Password must be at least 8 digits',
//            'confirmation_password.required' => 'Please enter your Confirmation Password',
//            'confirmation_password.same' => 'Password and Confirm Password Does not match',
            'division_id.required' => 'Please select a division',
            'district_id.required' => 'Please select a division',
            'upzila_id.required' => 'Please select a division',
            'mobile.required' => 'Please enter your mobile',
            'mobile.regex' => 'The mobile number format is invalid.',
            'terms_and_condition.required' => 'Please accept the terms and condition',
        ];
    }

}
