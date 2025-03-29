<?php

namespace App\Core\FormBuilder\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreComponentRequest extends FormRequest
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
            'parent_id' => 'nullable',
            'title' => 'required',
            'is_loop' => 'nullable',
            'loop_data' => 'required_if:is_loop,1',
            'template_type' => 'required',
            'order' => 'required',
            'step_no' => 'nullable',
            'status' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'form_id.required' => 'Select a Form',
            'title.required' => 'Component Title is required',
            'loop_data.required_if' => 'Loop Data is required if is loop is selected',
            'template_type.required' => 'Select a Template Type',
            'order.required' => 'Order is required',
            'status.required' => 'Select a Status',
        ];
    }
}
