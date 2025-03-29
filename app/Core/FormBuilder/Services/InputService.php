<?php

namespace App\Core\FormBuilder\Services;

use App\Libraries\Encryption;

class InputService
{
    public static function formatData($input): array
    {
        $attribute_bag = collect($input->attribute_bag)
            ->map(fn($collection, $key) => [
                'key' => $key,
                'value' => $collection
            ])
            ->when(!$input->attribute_bag, function () {
                return collect([['key' => '', 'value' => '']]);
            })->values()->toArray();
        $validation = $input->validation;
        $validationRules = collect($validation['rules'])
            ->map(fn($collection) => [
                ['rule' => $collection]
            ])
            ->when(!$validation['rules'], function () {
                return collect([['rule' => '']]);
            })
            ->toArray();
        $validationMessages = collect($validation['message'])
            ->map(fn($collection, $key) => [
                [
                    'key' => $key,
                    'value' => $collection
                ]
            ])
            ->when(!$validation['message'], function () {
                return collect([['key' => '', 'value' => '']]);
            })->values()
            ->toArray();

        $validation= [];

        foreach ($validationMessages as $key => $value) {
            $validation[$key]['rules'] = $validationRules[$key] ?? [['rule' => '']];
            $validation[$key]['message'] = $value;
        }

        return [
            'id' => Encryption::encodeId($input->id),
            'form_id' => $input->form_id,
            'component_id' => $input->component_id ?? null,
            'label' => $input->label,
            'label_position' => $input->label_position,
            'width' => $input->width,
            'group_id' => $input->group_id ?? '',
            'input_tag_name' => $input->input_tag_name,
            'is_loop' => $input->is_loop,
            'loop_data' => $input->loop_data ?? '',
            'input_id' => $input->input_id,
            'name' => $input->name,
            'model_namespace' => $input->model_namespace,
            'column_name' => $input->column_name,
            'order' => $input->order,
            'status' => $input->status,
            'attribute_bag' => $attribute_bag,
            'validation' => $validation,
        ];
    }
}
