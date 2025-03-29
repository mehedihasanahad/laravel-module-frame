<?php

use App\Core\FormBuilder\Models\Form;
use App\Libraries\Encryption;
use Illuminate\Support\Str;

if (!function_exists('decodeRoutePrams')) {
    function decodeRoutePrams($params): array
    {
        return collect($params)->mapWithKeys(fn($value, $key) => [
            $key => Encryption::decodeId($value)
        ])->toArray();
    }
}

if (!function_exists('formWithInputs')) {
    function formWithInputs($processTypeId, $formType)
    {
        return Form::query()
            ->where([
                'process_type_id' => $processTypeId,
                'form_type' => $formType
            ])
            ->with(['inputs' => function ($q) {
                return $q->where('status', 1)
                    ->orderBy('order', 'ASC');
            }])
            ->first();
    }
}

if (!function_exists('formattedInputsByModelNamespace')) {
    function formattedInputsByModelNamespace($inputs, $request)
    {
        return $inputs->groupBy('model_namespace')
            ->map(fn($inputs) => $inputs->flatMap(function ($input) use ($request) {
                $name = Str::replace('[]', '', $input['name']);
                if ($request->has($name)) {
                    return [
                        [$input['column_name'], $request->{$name}]
                    ];
                } else {
                    return [
                        [$input['column_name'], null]
                    ];
                }
            }))
            ->toArray();
    }

}

if (!function_exists('formTypes')) {
    function formTypes(): array
    {
        return [
            ['name' => 'Add Form', 'value' => 1],
            ['name' => 'Edit Form', 'value' => 2],
        ];
    }
}
if (!function_exists('templateTypes')) {
    function templateTypes(): array
    {
        return [
            ['name' => 'Default', 'value' => 1],
            ['name' => 'Step Form', 'value' => 2],
        ];
    }
}
if (!function_exists('methods')) {
    function methods(): array
    {
        return [
            ['name' => 'POST', 'value' => 'POST'],
            ['name' => 'PUT', 'value' => 'PUT'],
            ['name' => 'PATCH', 'value' => 'PATCH'],
            ['name' => 'DELETE', 'value' => 'DELETE'],
        ];
    }
}
if (!function_exists('labelPositions')) {
    function labelPositions(): array
    {
        return [
            ['name' => 'Upper', 'value' => 1],
            ['name' => 'Left Side', 'value' => 2],
        ];
    }
}
if (!function_exists('inputWidths')) {
    function inputWidths(): array
    {
        return [
            ['name' => 'Default', 'value' => 1],
            ['name' => 'Full Row', 'value' => 2],
        ];
    }
}

if (!function_exists('inputTags')) {
    function inputTags(): array
    {
        return Config::get('inputTags');
    }
}

