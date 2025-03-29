<?php

namespace App\Core\FormBuilder\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInputRequest extends FormRequest
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
            'label' => 'required',
            'label_position' => 'required',
            'width' => 'required',
            'group_id' => 'nullable',
            'input_tag_name' => 'required',
            'is_loop' => 'required',
            'loop_data' => 'required_if:is_loop,1',
            'input_id' => 'required',
            'name' => 'required',
            'model_namespace' => 'required',
            'column_name' => 'required',
            'order' => 'required',
            'status' => 'required',
            'attribute_bag' => 'required|array',
            'attribute_bag.*.key' => 'required',
            'attribute_bag.*.value' => 'required',
            'validation' => 'nullable|array',
//            'validation.*.rules.*.rule' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'form_id.required' => 'Select a form',
            'component_id.required' => 'Select a component',
            'label.required' => 'Enter a label',
            'label_position.required' => 'Select a label position',
            'width.required' => 'Select a width',
            'input_tag_name.required' => 'Select a input tag name',
            'is_loop.required' => 'Select a is loop status',
            'loop_data.required_if' => 'Enter loop data',
            'input_id.required' => 'Select a input',
            'name.required' => 'Enter a name',
            'model_namespace.required' => 'Enter a model namespace',
            'column_name.required' => 'Enter a column name',
            'order.required' => 'Enter a order',
            'status.required' => 'Select a status',
            'attribute_bag.required' => 'Enter a attribute bag',
            'attribute_bag.array' => 'Enter a attribute bag as array',
            'attribute_bag.*.key.required' => 'Enter attribute key at :position',
            'attribute_bag.*.value.required' => 'Enter  attribute value :position',
            'validation.array' => 'Enter a validation as array',
            'validation.required' => 'Enter a validation',

        ];
    }
}
