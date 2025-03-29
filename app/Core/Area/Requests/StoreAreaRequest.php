<?php

namespace App\Core\Area\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAreaRequest extends FormRequest
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
            'area_nm' => 'required',
            'area_nm_ban' => 'required',
            'area_type' => 'required',
            'pare_id' => 'required_unless:area_type,1|nullable',

        ];
    }

    public function messages(): array
    {
        return [
            'area_nm.required' => 'Please enter your Area name english',
            'area_nm_ban.required' => 'Please enter your Area name bangla',
            'area_type.required' => 'Please choose an option',
            'pare_id.required_unless' => 'Please choose an option.',



        ];
    }
}
