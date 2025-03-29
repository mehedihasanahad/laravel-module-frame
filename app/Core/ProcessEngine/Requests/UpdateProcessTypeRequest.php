<?php

namespace App\Core\ProcessEngine\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProcessTypeRequest extends FormRequest
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
}
