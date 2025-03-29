<?php

namespace App\Core\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'national_id' => 'required',
            'birth_date' => 'required|date',
            'present_address' => 'nullable',
            'permanent_address' => 'nullable',
            'gender' => 'required',
            'mobile' => 'required|max_digits:11',
//            'user_pic_base64' => 'required',
        ];
    }
}
