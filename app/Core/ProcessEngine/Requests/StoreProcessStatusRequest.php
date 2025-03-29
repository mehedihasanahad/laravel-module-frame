<?php

namespace App\Core\ProcessEngine\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProcessStatusRequest extends FormRequest
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
            'id' => 'required|unique:process_statuses,id,' . $this->id . ',id,process_type_id,' . $this->input('process_type_id'),
            'process_type_id' => 'required|exists:process_types,id',
            'status_name' => 'required',
            'color' => 'nullable',
            'addon_path' => 'nullable',
            'status' => 'required',
            'addon_status' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'Please enter id',
            'id.unique' => 'The id has already been taken',
            'process_type_id.required' => 'Please select a process type',
            'status_name.required' => 'Please enter  name',
            'status.required' => 'Please select a status',
            'addon_status.required' => 'Please select a addon status',

        ];
    }


}
