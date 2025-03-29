<?php

namespace App\Core\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
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
            'national_id' => 'required',
            'birth_date' => 'required',
            'status' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Please enter your first name',
            'last_name.required' => 'Please enter your first name',
            'national_id.required' => 'Please enter your first name',
            'birth_date.required' => 'Please enter your first name',
            'status.required' => 'Please select a status',
        ];
    }
}
