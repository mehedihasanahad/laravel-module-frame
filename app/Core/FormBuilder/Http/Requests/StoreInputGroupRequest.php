<?php

namespace App\Core\FormBuilder\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInputGroupRequest extends FormRequest
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
            'form_id' => 'required',
            'component_id' => 'required',
            'step_no' => 'nullable',
            'label' => 'required',
            'value' => 'nullable'
        ];
    }

    public function messages(): array
    {
        return [
            'form_id.required' => 'Select a form',
            'component_id.required' => 'Select a component',
            'label.required' => 'Label is required',
        ];
    }
}
