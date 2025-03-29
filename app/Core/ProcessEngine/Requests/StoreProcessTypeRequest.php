<?php

namespace App\Core\ProcessEngine\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProcessTypeRequest extends FormRequest
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
            'name' => 'required',
            'name_bn' => 'required',
            'group_name' => 'required',
            'app_table_name' => 'required',
            'module_folder_name' => 'required',
            'panel_color' => 'nullable',
            'icon_class' => 'nullable',
            'order' => 'required',
            'status' => 'required',
            'final_status' => 'required',
            'permissions' => 'required'
        ];
    }
    public function messages(): array
    {
        return  [
            'name.required' => 'Please enter name',
            'name_bn.required' => 'Please enter name in Bangla',
            'group_name.required' => 'Please enter group name',
            'app_table_name.required' => 'Please enter app table name',
            'module_folder_name.required' => 'Please enter module app folder name',
            'order.required' => 'Please enter order',
            'status.required' => 'Please enter status',
            'permissions.required' => 'Select Permission'
        ];
    }
}
