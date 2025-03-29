<?php

namespace App\Core\FormBuilder\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFormRequest extends FormRequest
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
            'form_type' => 'required',
            'template_type' => 'required',
            'title' => 'required',
            'form_id' => 'required',
            'steps' => 'required_if:template_type,2',
            'steps_name' => 'required_if:template_type,2|array',
            'steps_name.*.name' => 'required',
            'app_model_namespace' => 'required',
            'app_id' => 'nullable',
            'form_data_json' => 'nullable|array',
            'method' => 'required',
            'action' => 'required',
            'autocomplete' => 'nullable',
            'enctype' => 'nullable',
            'status' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'process_type_id.required' => 'Select a process type',
            'form_type.required' => 'Select a form type',
            'template_type.required' => 'Select a template type',
            'title.required' => 'Enter form title',
            'form_id.required' => 'Enter form id',
            'steps.required_if' => 'Number of steps is required when it is a step form and must be getter than 0',
            'steps_name.required_if' => 'Names of steps is required when it is a step form',
            'steps_name.*.name.required' => 'Enter Step Name at :position',
            'app_model_namespace.required' => 'Application model namespace is required',
            'app_id.required' => 'Related table application id is required',
            'method.required' => 'Select a method',
            'action.required' => 'Form action url is required',
            'status.required' => 'Select a status',
        ];
    }
}
