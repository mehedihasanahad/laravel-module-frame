<?php

namespace App\Core\ProcessEngine\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProcessPathRequest extends FormRequest
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
            'process_type_id' => 'required',
            'desk_from' => 'required',
            'desk_to' => 'required',
            'status_from' => 'required',
            'status_to' => 'required',
            'file_attachment' => 'required',
            'remarks' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'process_type_id.required' => 'Please select a process type',
            'desk_from.required' => 'Please select a desk',
            'desk_to.required' => 'Please select a desk',
            'status_from.required' => 'Please select a status',
            'status_to.required' => 'Please select a status',
            'file_attachment.required' => 'Please select a attachment',
            'remarks.required' => 'Please select a remark',
        ];
    }
}
